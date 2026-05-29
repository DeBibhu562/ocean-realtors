<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, defineAsyncComponent, ref, watch } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
const ListingLocationMap = defineAsyncComponent(() => import('@/Components/Listing/ListingLocationMap.vue'));
import { useListingLocationOptions } from '@/Composables/useListingLocationOptions';

const props = defineProps({
    properties: {
        type: [Array, Object],
        default: () => [],
    },
    watermark: String,
    propertyStats: {
        type: Object,
        default: () => ({ total: 0, active: 0, featured: 0 }),
    },
    pendingReviewsCount: {
        type: Number,
        default: 0,
    },
    agents: {
        type: Array,
        default: () => [],
    },
});

const properties = computed(() => {
    if (Array.isArray(props.properties)) return props.properties;
    if (Array.isArray(props.properties?.data)) return props.properties.data;
    return [];
});
const propertyPagination = computed(() => {
    if (!props.properties || Array.isArray(props.properties)) {
        return { links: [], from: null, to: null, total: properties.value.length };
    }

    return {
        links: Array.isArray(props.properties.links) ? props.properties.links : [],
        from: props.properties.from ?? null,
        to: props.properties.to ?? null,
        total: props.properties.total ?? properties.value.length,
    };
});

const activeTab = ref('properties');
const showingPropertyModal = ref(false);
const editingProperty = ref(null);
const currentStep = ref(1);
const totalSteps = 6;
const stepLabels = [
    'Basic Information',
    'Location',
    'Pricing & Deal Terms',
    'Property Specifications',
    'Highlights & Amenities',
    'Media & Publish',
];

const form = useForm({
    title: '', description: '', price: '', status: 'For Sale', type: 'Apartment', address: '', city: 'Gurgaon',
    bedrooms: 2, bathrooms: 2, garage: 1, size: 0, is_featured: false,
    category: 'Residential', listing_type: 'Rent', listing_status: 'Active', bhk: '2 BHK', society_name: '',
    built_up_area: '', carpet_area: '', age_of_property: 0, balconies: 1, furnish_type: 'Unfurnished',
    amenities: [], covered_parking: '1', open_parking: '0', tenant_type: 'Family',
    bachelor_preference: 'Open for both', pet_friendly: false, available_from: '',
    price_negotiable: false, pg_food_included: false,
    booking_amount: '', food_charges: '',
    maintenance_charges: '', maintenance_type: 'Separate', security_deposit: '1 month',
    lock_in_period: 'None', brokerage_charge: 'None', brokerage_negotiable: false,
    parking_charges_type: 'Separate', painting_charges: 'None',
    floor_no: '', total_floors: '', facing: 'North', servant_room: false, rera_id: '',
    highlights: [], existing_photos: [], new_photos: [],
    area: '', locality: '', latitude: '', longitude: '',
    agent_id: '',
    _method: null
});

const photoPreviews = ref([]);
const {
    cityOptions,
    areaOptions,
    localityOptions,
    loadingAreas,
    loadingLocalities,
    citySelect,
    areaSelect,
    localitySelect,
    LOCATION_OTHER,
    initLocationStep,
    applyGeocodeResult,
} = useListingLocationOptions(form);

const openCreateModal = async () => {
    editingProperty.value = null;
    currentStep.value = 1;
    photoPreviews.value = [];
    form.reset();
    form.city = 'Gurgaon';
    setBuiltUpFromSqFt('');
    setCarpetFromSqFt('');
    await initLocationStep();
    showingPropertyModal.value = true;
};

const openEditModal = async (property) => {
    editingProperty.value = property;
    currentStep.value = 1;
    photoPreviews.value = [];
    form.defaults({
        ...property,
        amenities: property.amenities || [],
        highlights: property.highlights || [],
        existing_photos: property.photos || [],
        new_photos: [],
        available_from: property.available_from ? new Date(property.available_from).toISOString().split('T')[0] : '',
        area: property.area || '',
        locality: property.locality || '',
        latitude: property.latitude ? String(property.latitude) : '',
        longitude: property.longitude ? String(property.longitude) : '',
    });
    form.reset();
    setBuiltUpFromSqFt(property.built_up_area || '');
    setCarpetFromSqFt(property.carpet_area || '');
    await initLocationStep();
    showingPropertyModal.value = true;
};

const handlePhotoUpload = (e) => {
    const files = Array.from(e.target.files);
    files.forEach(file => {
        if (form.new_photos.length + form.existing_photos.length < 20) {
            form.new_photos.push(file);
            photoPreviews.value.push(URL.createObjectURL(file));
        }
    });
};

const removeNewPhoto = (index) => {
    form.new_photos.splice(index, 1);
    photoPreviews.value.splice(index, 1);
};

const removeExistingPhoto = (index) => {
    form.existing_photos.splice(index, 1);
};

const isSell = computed(() => form.listing_type === 'Sell');
const isRent = computed(() => form.listing_type === 'Rent');
const isPg = computed(() => form.listing_type === 'PG');
const showPgFields = computed(() => isPg.value || isRent.value);
const cityOptionName = (city) => (typeof city === 'string' ? city : city?.name ?? '');

const toSqM = (sqFt) => sqFt / 10.7639;
const toSqYd = (sqFt) => sqFt / 9;
const toSqFtFromSqM = (sqM) => sqM * 10.7639;
const toSqFtFromSqYd = (sqYd) => sqYd * 9;
const roundArea = (value) => {
    const n = Number(value);
    if (!Number.isFinite(n) || n <= 0) return '';
    return (Math.round(n * 100) / 100).toString();
};

const builtUpSqFt = ref('');
const builtUpSqM = ref('');
const builtUpSqYd = ref('');
const carpetSqFt = ref('');
const carpetSqM = ref('');
const carpetSqYd = ref('');
let syncingAreaUnits = false;

const setBuiltUpFromSqFt = (sqFt) => {
    syncingAreaUnits = true;
    const ft = Number(sqFt);
    if (!Number.isFinite(ft) || ft <= 0) {
        builtUpSqFt.value = '';
        builtUpSqM.value = '';
        builtUpSqYd.value = '';
        form.built_up_area = '';
    } else {
        builtUpSqFt.value = roundArea(ft);
        builtUpSqM.value = roundArea(toSqM(ft));
        builtUpSqYd.value = roundArea(toSqYd(ft));
        form.built_up_area = roundArea(ft);
    }
    syncingAreaUnits = false;
};

const setCarpetFromSqFt = (sqFt) => {
    syncingAreaUnits = true;
    const ft = Number(sqFt);
    if (!Number.isFinite(ft) || ft <= 0) {
        carpetSqFt.value = '';
        carpetSqM.value = '';
        carpetSqYd.value = '';
        form.carpet_area = '';
    } else {
        carpetSqFt.value = roundArea(ft);
        carpetSqM.value = roundArea(toSqM(ft));
        carpetSqYd.value = roundArea(toSqYd(ft));
        form.carpet_area = roundArea(ft);
    }
    syncingAreaUnits = false;
};

watch(builtUpSqFt, (val) => { if (!syncingAreaUnits) setBuiltUpFromSqFt(val); });
watch(builtUpSqM, (val) => { if (!syncingAreaUnits) setBuiltUpFromSqFt(toSqFtFromSqM(Number(val))); });
watch(builtUpSqYd, (val) => { if (!syncingAreaUnits) setBuiltUpFromSqFt(toSqFtFromSqYd(Number(val))); });
watch(carpetSqFt, (val) => { if (!syncingAreaUnits) setCarpetFromSqFt(val); });
watch(carpetSqM, (val) => { if (!syncingAreaUnits) setCarpetFromSqFt(toSqFtFromSqM(Number(val))); });
watch(carpetSqYd, (val) => { if (!syncingAreaUnits) setCarpetFromSqFt(toSqFtFromSqYd(Number(val))); });
watch(
    () => form.listing_type,
    (listingType) => {
        form.status = listingType === 'Sell' ? 'For Sale' : 'For Rent';
    },
    { immediate: true }
);

const normalizePricingByIntent = () => {
    if (isSell.value) {
        form.security_deposit = '';
        form.lock_in_period = '';
        form.pg_food_included = false;
        form.food_charges = '';
        form.maintenance_type = '';
    } else {
        form.booking_amount = '';
    }

    if (!showPgFields.value) {
        form.pg_food_included = false;
        form.food_charges = '';
    }
};

const submit = () => {
    // Ensure numbers are numbers
    form.bedrooms = parseInt(form.bhk.split(' ')[0]) || 0;
    form.garage = parseInt(form.covered_parking) || 0;
    form.size = parseInt(form.built_up_area) || 0;
    normalizePricingByIntent();

    if (editingProperty.value) {
        form._method = 'PUT';
        form.post(route('properties.update', editingProperty.value.id), {
            forceFormData: true,
            onSuccess: () => closeModal(),
        });
    } else {
        form._method = null;
        form.post(route('properties.store'), {
            forceFormData: true,
            onSuccess: () => closeModal(),
        });
    }
};

const closeModal = () => {
    showingPropertyModal.value = false;
    form.reset();
    photoPreviews.value = [];
    setBuiltUpFromSqFt('');
    setCarpetFromSqFt('');
};

const watermarkForm = useForm({ watermark: null });
const uploadWatermark = () => {
    watermarkForm.post(route('settings.watermark'), { onSuccess: () => watermarkForm.reset() });
};

const deleteProperty = (id) => {
    if (confirm('Are you sure you want to delete this property?')) {
        form.delete(route('properties.destroy', id));
    }
};

const highlightOptions = [
    { label: 'Close to Metro', category: 'Location' },
    { label: 'Power Backup', category: 'Amenities' },
    { label: 'Lift', category: 'Amenities' },
    { label: 'Gymnasium', category: 'Amenities' },
    { label: 'Swimming Pool', category: 'Amenities' },
    { label: 'Natural Light', category: 'Features' },
    { label: 'Vastu Compliant', category: 'Features' },
    { label: 'Gated Community', category: 'Security' },
];

const toggleHighlight = (item) => {
    const idx = form.highlights.indexOf(item);
    if (idx > -1) form.highlights.splice(idx, 1);
    else if (form.highlights.length < 4) form.highlights.push(item);
};

const amenityOptions = [
    'Lift', 'Power Backup', 'Gym', 'Swimming Pool', 'Club House',
    'Security', 'Parking', 'Garden', 'Children Play Area', 'Fire Safety',
];

const toggleAmenity = (item) => {
    const idx = form.amenities.indexOf(item);
    if (idx > -1) form.amenities.splice(idx, 1);
    else form.amenities.push(item);
};

const goNextStep = () => {
    if (currentStep.value < totalSteps) currentStep.value += 1;
};

const goPrevStep = () => {
    if (currentStep.value > 1) currentStep.value -= 1;
};
</script>

<template>
    <Head title="Property Management" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-black text-gray-900 leading-tight">Property Management</h2>
        </template>

        <div class="space-y-8">
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div v-for="stat in [
                    { label: 'Total Properties', value: propertyStats.total, icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', color: 'text-primary bg-primary/10' },
                    { label: 'Active Listings', value: propertyStats.active, icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', color: 'text-green-600 bg-green-50' },
                    { label: 'Featured', value: propertyStats.featured, icon: 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z', color: 'text-amber-500 bg-amber-50' },
                    { label: 'Pending Reviews', value: pendingReviewsCount, icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', color: 'text-blue-500 bg-blue-50' }
                ]" :key="stat.label" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-4">
                    <div :class="stat.color" class="w-12 h-12 rounded-xl flex items-center justify-center">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="stat.icon" /></svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ stat.label }}</div>
                        <div class="text-2xl font-black text-gray-900">{{ stat.value }}</div>
                    </div>
                </div>
            </div>

            <!-- Main Tab Container -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Tab Header -->
                <div class="px-8 pt-6 border-b border-gray-50 flex items-center justify-between">
                    <div class="flex space-x-8">
                        <button 
                            v-for="tab in [{id: 'properties', label: 'Inventory'}, {id: 'settings', label: 'Branding & Settings'}]" 
                            :key="tab.id"
                            @click="activeTab = tab.id" 
                            class="pb-4 text-sm font-bold transition-all relative"
                            :class="activeTab === tab.id ? 'text-primary' : 'text-gray-400 hover:text-gray-600'"
                        >
                            {{ tab.label }}
                            <div v-if="activeTab === tab.id" class="absolute bottom-0 left-0 right-0 h-1 bg-primary rounded-full"></div>
                        </button>
                    </div>
                    
                    <button 
                        v-if="activeTab === 'properties'" 
                        @click="openCreateModal"
                        class="mb-4 bg-primary hover:bg-primary-hover text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-primary/20 transition-all active:scale-95 flex items-center space-x-2"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        <span>Add Property</span>
                    </button>
                </div>

                <!-- Tab Content -->
                <div class="p-0">
                    <!-- Inventory List -->
                    <div v-if="activeTab === 'properties'" class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Property Details</th>
                                    <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Value</th>
                                    <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Location</th>
                                    <th class="px-8 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                <tr v-for="property in properties" :key="property.id" class="hover:bg-gray-50/50 transition-colors group">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center space-x-4">
                                            <div class="relative shrink-0">
                                                <img :src="property.image || '/img/placeholder.jpg'" class="w-16 h-16 rounded-2xl object-cover border border-gray-100 shadow-sm" />
                                                <div v-if="property.is_featured" class="absolute -top-2 -right-2 bg-amber-400 text-white p-1 rounded-lg shadow-sm">
                                                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                                </div>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-sm font-bold text-gray-900 truncate max-w-[250px] group-hover:text-primary transition-colors">{{ property.title }}</div>
                                                <div class="flex items-center mt-1 space-x-2">
                                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-500 rounded text-[10px] font-bold uppercase tracking-tight">{{ property.type }}</span>
                                                    <span :class="property.listing_type === 'Rent' ? 'bg-blue-50 text-blue-600' : 'bg-green-50 text-green-600'" class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-tight">For {{ property.listing_type }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <div class="text-base font-black text-gray-900">₹{{ Number(property.price).toLocaleString() }}</div>
                                        <div v-if="property.listing_type === 'Rent'" class="text-[10px] font-bold text-gray-400 uppercase">per month</div>
                                    </td>
                                    <td class="px-8 py-5 text-sm">
                                        <div class="flex items-center text-gray-600 font-medium">
                                            <svg class="h-4 w-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                                            {{ property.city }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <button @click="openEditModal(property)" class="p-2 text-gray-400 hover:text-primary hover:bg-primary/5 rounded-xl transition-all">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                            </button>
                                            <button @click="deleteProperty(property.id)" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="properties.length === 0">
                                    <td colspan="4" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                                <svg class="h-8 w-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                            </div>
                                            <h3 class="text-sm font-bold text-gray-900">No properties found</h3>
                                            <p class="text-xs text-gray-500 mt-1">Start by adding your first listing to the portal.</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div
                            v-if="propertyPagination.links.length > 3"
                            class="flex items-center justify-between border-t border-gray-100 px-8 py-4"
                        >
                            <p class="text-sm text-gray-500">
                                Showing {{ propertyPagination.from || 0 }}-{{ propertyPagination.to || properties.length }} of {{ propertyPagination.total }}
                            </p>
                            <div class="flex flex-wrap items-center gap-1">
                                <Link
                                    v-for="link in propertyPagination.links"
                                    :key="`prop-page-${link.label}`"
                                    :href="link.url || '#'"
                                    class="rounded-lg px-3 py-1.5 text-sm"
                                    :class="link.active ? 'bg-primary text-white' : link.url ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'pointer-events-none text-gray-300'"
                                    v-html="link.label"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Branding Settings -->
                    <div v-if="activeTab === 'settings'" class="p-8 max-w-2xl">
                        <div class="flex items-start space-x-6">
                            <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center shrink-0">
                                <svg class="h-8 w-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" /></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Visual Branding & Watermark</h3>
                                <p class="text-sm text-gray-500 mt-1">Upload your agency logo to automatically protect and brand all property photos. We recommend a transparent PNG for best results.</p>
                                
                                <div class="mt-8 space-y-8">
                                    <div v-if="watermark" class="p-6 bg-navy rounded-2xl flex items-center justify-center border border-white/10 relative group overflow-hidden">
                                        <img :src="watermark" class="h-24 w-auto opacity-90 group-hover:scale-105 transition-transform duration-500" />
                                        <div class="absolute inset-0 bg-navy/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                            <span class="text-xs font-bold text-white uppercase tracking-widest">Active Branding</span>
                                        </div>
                                    </div>

                                    <form @submit.prevent="uploadWatermark" class="space-y-6">
                                        <div class="relative">
                                            <label class="block group cursor-pointer">
                                                <div class="border-2 border-dashed border-gray-200 group-hover:border-primary/50 transition-colors rounded-2xl p-8 text-center bg-gray-50/50">
                                                    <svg class="h-8 w-8 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                                    <span class="text-sm font-bold text-gray-600 group-hover:text-primary transition-colors">Click to upload transparent PNG</span>
                                                    <input type="file" @input="watermarkForm.watermark = $event.target.files[0]" accept="image/png" class="hidden" />
                                                </div>
                                            </label>
                                            <InputError :message="watermarkForm.errors.watermark" class="mt-2" />
                                        </div>
                                        <PrimaryButton :disabled="watermarkForm.processing" class="w-full justify-center py-3 rounded-xl shadow-lg shadow-primary/20">
                                            Update Global Watermark
                                        </PrimaryButton>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ADVANCED 5-STEP LISTING MODAL -->
        <Modal :show="showingPropertyModal" @close="closeModal" max-width="5xl">
            <div class="listing-modal p-8 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-8 border-b pb-6">
                    <div>
                        <h2 class="text-2xl font-black text-gray-900 dark:text-white">{{ editingProperty ? 'Update Listing' : 'Create New Listing' }}</h2>
                        <p class="text-sm text-gray-500">Step {{ currentStep }} of {{ totalSteps }}: {{ stepLabels[currentStep - 1] }}</p>
                    </div>
                    <div class="flex gap-2">
                        <div v-for="s in totalSteps" :key="s" :class="currentStep >= s ? 'bg-blue-600' : 'bg-gray-200'" class="h-1.5 w-10 rounded-full transition-all"></div>
                    </div>
                </div>

                <form @submit.prevent="submit" class="space-y-8">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-[220px_1fr]">
                        <aside class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                            <p class="mb-3 text-xs font-black uppercase tracking-wider text-gray-500">Listing Progress</p>
                            <div class="space-y-3">
                                <div
                                    v-for="(label, idx) in stepLabels"
                                    :key="`step-rail-${label}`"
                                    class="flex items-start gap-2"
                                >
                                    <div
                                        class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-[11px] font-black"
                                        :class="currentStep >= idx + 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500'"
                                    >
                                        {{ idx + 1 }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold" :class="currentStep === idx + 1 ? 'text-gray-900' : 'text-gray-500'">
                                            {{ label }}
                                        </p>
                                        <p class="text-[11px] text-gray-400">
                                            {{ currentStep > idx + 1 ? 'Completed' : currentStep === idx + 1 ? 'In progress' : 'Pending' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </aside>
                        <div class="space-y-8">
                    <!-- STEP 1 -->
                    <div v-if="currentStep === 1" class="space-y-6">
                        <h3 class="font-bold text-lg border-l-4 border-blue-600 pl-3">Basic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><InputLabel value="Category*" /><select v-model="form.category" class="w-full rounded-lg border-gray-300 dark:bg-gray-900"><option>Residential</option><option>Commercial</option></select></div>
                            <div><InputLabel value="Looking to*" /><select v-model="form.listing_type" class="w-full rounded-lg border-gray-300 dark:bg-gray-900"><option>Rent</option><option>Sell</option><option>PG</option></select></div>
                            <div><InputLabel value="Property Type*" /><select v-model="form.type" class="w-full rounded-lg border-gray-300 dark:bg-gray-900"><option>Apartment/ Flat</option><option>Villa</option><option>Studio</option><option>Builder Floor</option><option>Plot</option></select></div>
                        </div>
                        <div><InputLabel value="Society / Project Name" /><TextInput v-model="form.society_name" class="w-full" placeholder="DLF Camellias / M3M Golf Estate" /></div>
                    </div>

                    <!-- STEP 2 -->
                    <div v-if="currentStep === 2" class="space-y-6">
                        <h3 class="font-bold text-lg border-l-4 border-blue-600 pl-3">Location</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <InputLabel value="City*" />
                                <select v-model="citySelect" class="w-full rounded-lg border-gray-300 dark:bg-gray-900">
                                    <option value="">Select city</option>
                                    <option v-for="city in cityOptions" :key="cityOptionName(city)" :value="cityOptionName(city)">{{ cityOptionName(city) }}</option>
                                    <option :value="LOCATION_OTHER">Other</option>
                                </select>
                                <TextInput v-if="citySelect === LOCATION_OTHER" v-model="form.city" class="mt-2 w-full" placeholder="Enter city" required />
                            </div>
                            <div>
                                <InputLabel value="Area" />
                                <select v-model="areaSelect" class="w-full rounded-lg border-gray-300 dark:bg-gray-900" :disabled="loadingAreas">
                                    <option value="">Select area</option>
                                    <option v-for="area in areaOptions" :key="area.name" :value="area.name">{{ area.name }}</option>
                                    <option :value="LOCATION_OTHER">Other</option>
                                </select>
                                <TextInput v-if="areaSelect === LOCATION_OTHER" v-model="form.area" class="mt-2 w-full" placeholder="Enter area" />
                            </div>
                            <div>
                                <InputLabel value="Locality" />
                                <select v-model="localitySelect" class="w-full rounded-lg border-gray-300 dark:bg-gray-900" :disabled="loadingLocalities">
                                    <option value="">Select locality</option>
                                    <option v-for="loc in localityOptions" :key="loc.name" :value="loc.name">{{ loc.name }}</option>
                                    <option :value="LOCATION_OTHER">Other</option>
                                </select>
                                <TextInput v-if="localitySelect === LOCATION_OTHER" v-model="form.locality" class="mt-2 w-full" placeholder="Enter locality" />
                            </div>
                        </div>
                        <div>
                            <InputLabel value="Full Address*" />
                            <TextInput v-model="form.address" class="w-full" placeholder="Street, Sector, Area..." required />
                        </div>
                        <div class="rounded-2xl border border-gray-200 p-4">
                            <h4 class="mb-3 text-sm font-black uppercase tracking-wide text-gray-600">Map Locator</h4>
                            <ListingLocationMap
                                :latitude="form.latitude"
                                :longitude="form.longitude"
                                :city="form.city"
                                :active="currentStep === 2 && showingPropertyModal"
                                @geocoded="applyGeocodeResult"
                            />
                            <div class="mt-3 grid grid-cols-2 gap-3">
                                <div><InputLabel value="Latitude" /><TextInput v-model="form.latitude" class="w-full" placeholder="28.4595" /></div>
                                <div><InputLabel value="Longitude" /><TextInput v-model="form.longitude" class="w-full" placeholder="77.0266" /></div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3 -->
                    <div v-if="currentStep === 3" class="space-y-6">
                        <h3 class="font-bold text-lg border-l-4 border-blue-600 pl-3">Pricing & Deal Terms</h3>
                        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4">
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Pricing profile</p>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ isSell ? 'Sell listing: token + brokerage percentage.' : 'Rent/PG listing: rent, deposit, lock-in, and utilities.' }}
                            </p>
                        </div>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div class="rounded-xl border border-gray-200 p-4">
                                <InputLabel :value="isSell ? 'Expected Sale Price*' : 'Monthly Rent*'" />
                                <TextInput type="number" v-model="form.price" class="mt-1 w-full" required />
                            </div>
                            <div class="rounded-xl border border-gray-200 p-4">
                                <InputLabel value="Listing Status" />
                                <select v-model="form.listing_status" class="mt-1 w-full rounded-lg border-gray-300 dark:bg-gray-900">
                                    <option>Active</option><option>Draft</option><option>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div v-if="isSell" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div><InputLabel value="Token / Booking Amount" /><TextInput type="number" v-model="form.booking_amount" class="w-full" placeholder="Booking amount" /></div>
                            <div><InputLabel value="Brokerage (%)" /><TextInput v-model="form.brokerage_charge" class="w-full" placeholder="e.g. 1.5%" /></div>
                        </div>

                        <div v-else class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div><InputLabel value="Security Deposit" /><TextInput v-model="form.security_deposit" class="w-full" /></div>
                            <div><InputLabel value="Maintenance Charges" /><TextInput type="number" v-model="form.maintenance_charges" class="w-full" /></div>
                            <div><InputLabel value="Maintenance Type" /><select v-model="form.maintenance_type" class="w-full rounded-lg border-gray-300 dark:bg-gray-900"><option>Included</option><option>Separate</option></select></div>
                            <div><InputLabel value="Lock-in Period" /><TextInput v-model="form.lock_in_period" class="w-full" placeholder="e.g. 6 months" /></div>
                            <div><InputLabel value="Brokerage" /><select v-model="form.brokerage_charge" class="w-full rounded-lg border-gray-300 dark:bg-gray-900"><option>None</option><option>Half Month Rent</option><option>One Month Rent</option><option>Custom</option></select></div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div><InputLabel value="Tenant Type" /><select v-model="form.tenant_type" class="w-full rounded-lg border-gray-300 dark:bg-gray-900"><option>Family</option><option>Bachelors</option><option>Any</option></select></div>
                            <div v-if="showPgFields"><InputLabel value="Food Charges" /><TextInput type="number" v-model="form.food_charges" class="w-full" placeholder="Optional monthly food charges" /></div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 md:grid-cols-3">
                            <label class="flex items-center"><input type="checkbox" v-model="form.is_featured" class="rounded text-blue-600" /><span class="ml-2 text-sm font-bold">Featured</span></label>
                            <label class="flex items-center"><input type="checkbox" v-model="form.price_negotiable" class="rounded text-blue-600" /><span class="ml-2 text-sm font-bold">Price Negotiable</span></label>
                            <label class="flex items-center"><input type="checkbox" v-model="form.pet_friendly" class="rounded text-blue-600" /><span class="ml-2 text-sm font-bold">Pet Friendly</span></label>
                            <label v-if="showPgFields" class="flex items-center"><input type="checkbox" v-model="form.pg_food_included" class="rounded text-blue-600" /><span class="ml-2 text-sm font-bold">PG Food Included</span></label>
                        </div>
                    </div>

                    <!-- STEP 4 -->
                    <div v-if="currentStep === 4" class="space-y-6">
                        <h3 class="font-bold text-lg border-l-4 border-blue-600 pl-3">Property Specifications</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div><InputLabel value="BHK" /><select v-model="form.bhk" class="w-full rounded-lg border-gray-300 dark:bg-gray-900"><option v-for="n in ['1 BHK','2 BHK','3 BHK','4 BHK','5+ BHK']" :key="n">{{n}}</option></select></div>
                            <div><InputLabel value="Bathrooms" /><TextInput type="number" v-model="form.bathrooms" class="w-full" /></div>
                            <div><InputLabel value="Balconies" /><TextInput type="number" v-model="form.balconies" class="w-full" /></div>
                            <div><InputLabel value="Age (years)" /><TextInput type="number" v-model="form.age_of_property" class="w-full" /></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="rounded-xl border border-gray-200 p-4">
                                <InputLabel value="Built Up Area Converter" />
                                <div class="mt-2 grid grid-cols-1 gap-3 sm:grid-cols-3">
                                    <div><label class="text-xs font-semibold text-gray-500">sq.ft</label><TextInput type="number" v-model="builtUpSqFt" class="w-full" /></div>
                                    <div><label class="text-xs font-semibold text-gray-500">sq.m</label><TextInput type="number" v-model="builtUpSqM" class="w-full" /></div>
                                    <div><label class="text-xs font-semibold text-gray-500">sq.yd</label><TextInput type="number" v-model="builtUpSqYd" class="w-full" /></div>
                                </div>
                            </div>
                            <div class="rounded-xl border border-gray-200 p-4">
                                <InputLabel value="Carpet Area Converter" />
                                <div class="mt-2 grid grid-cols-1 gap-3 sm:grid-cols-3">
                                    <div><label class="text-xs font-semibold text-gray-500">sq.ft</label><TextInput type="number" v-model="carpetSqFt" class="w-full" /></div>
                                    <div><label class="text-xs font-semibold text-gray-500">sq.m</label><TextInput type="number" v-model="carpetSqM" class="w-full" /></div>
                                    <div><label class="text-xs font-semibold text-gray-500">sq.yd</label><TextInput type="number" v-model="carpetSqYd" class="w-full" /></div>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div><InputLabel value="Floor No" /><TextInput type="number" v-model="form.floor_no" class="w-full" /></div>
                            <div><InputLabel value="Total Floors" /><TextInput type="number" v-model="form.total_floors" class="w-full" /></div>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div><InputLabel value="Furnishing" /><select v-model="form.furnish_type" class="w-full rounded-lg border-gray-300 dark:bg-gray-900"><option>Unfurnished</option><option>Semi Furnished</option><option>Fully Furnished</option></select></div>
                            <div><InputLabel value="Facing" /><select v-model="form.facing" class="w-full rounded-lg border-gray-300 dark:bg-gray-900"><option>North</option><option>South</option><option>East</option><option>West</option></select></div>
                            <div><InputLabel value="Covered Parking" /><TextInput type="number" v-model="form.covered_parking" class="w-full" /></div>
                            <div><InputLabel value="Open Parking" /><TextInput type="number" v-model="form.open_parking" class="w-full" /></div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="flex items-center"><input type="checkbox" v-model="form.servant_room" class="rounded text-blue-600" /><span class="ml-2 text-sm font-bold">Servant Room</span></label>
                            <label class="flex items-center"><input type="checkbox" v-model="form.brokerage_negotiable" class="rounded text-blue-600" /><span class="ml-2 text-sm font-bold">Brokerage Negotiable</span></label>
                        </div>
                    </div>

                    <!-- STEP 5 -->
                    <div v-if="currentStep === 5" class="space-y-8">
                        <div class="space-y-6">
                            <h3 class="font-bold text-lg border-l-4 border-blue-600 pl-3">Highlights</h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                <button v-for="o in highlightOptions" :key="o.label" type="button" @click="toggleHighlight(o.label)" :class="form.highlights.includes(o.label) ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-50 dark:bg-gray-900 border-gray-200'" class="text-left px-4 py-3 border rounded-xl text-xs font-bold transition-all shadow-sm">
                                    {{ o.label }}
                                </button>
                            </div>
                            <div><InputLabel value="Description" /><textarea v-model="form.description" class="w-full rounded-xl border-gray-300 dark:bg-gray-900" rows="4" placeholder="Describe unique features..."></textarea></div>
                            <div><InputLabel value="RERA ID" /><TextInput v-model="form.rera_id" class="w-full" placeholder="RERA Registration ID" /></div>
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
                                <button
                                    v-for="amenity in amenityOptions"
                                    :key="amenity"
                                    type="button"
                                    class="rounded-xl border px-3 py-2 text-xs font-bold transition-all"
                                    :class="form.amenities.includes(amenity) ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-50 border-gray-200 text-gray-700'"
                                    @click="toggleAmenity(amenity)"
                                >
                                    {{ amenity }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 6 -->
                    <div v-if="currentStep === 6" class="space-y-8">
                        <div v-if="agents.length" class="space-y-2">
                            <InputLabel value="Listing agent" />
                            <select v-model="form.agent_id" class="w-full rounded-lg border-gray-300 dark:bg-gray-900">
                                <option value="">Default agency agent</option>
                                <option v-for="agent in agents" :key="agent.id" :value="agent.id">
                                    {{ agent.name }}{{ agent.city ? ` (${agent.city})` : '' }}
                                </option>
                            </select>
                            <InputError :message="form.errors.agent_id" />
                            <p class="text-xs text-gray-500">Assigns this property to the agent profile and listing count.</p>
                        </div>
                        <div class="space-y-6 bg-gray-50 dark:bg-gray-900 p-6 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700">
                            <div class="flex justify-between items-center">
                                <h3 class="font-bold text-lg">Property Media (Max 20)</h3>
                                <div v-if="form.progress" class="text-sm font-black text-blue-600 animate-pulse">Processing: {{ form.progress.percentage }}%</div>
                            </div>
                            
                            <!-- PREVIEW GRID -->
                            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4">
                                <!-- Existing -->
                                <div v-for="(photo, idx) in form.existing_photos" :key="'ex-'+idx" class="relative group aspect-square">
                                    <img :src="photo" class="w-full h-full object-cover rounded-xl border-2 border-blue-100" />
                                    <button type="button" @click="removeExistingPhoto(idx)" class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs shadow-lg hover:scale-110 transition">×</button>
                                </div>
                                <!-- New Previews -->
                                <div v-for="(url, idx) in photoPreviews" :key="'new-'+idx" class="relative group aspect-square">
                                    <img :src="url" class="w-full h-full object-cover rounded-xl border-2 border-green-200 shadow-md" />
                                    <div class="absolute inset-0 bg-black/40 rounded-xl opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                        <button type="button" @click="removeNewPhoto(idx)" class="bg-white text-red-600 font-bold px-2 py-1 rounded text-xs">Remove</button>
                                    </div>
                                    <div class="absolute bottom-1 right-1 bg-green-500 text-[8px] text-white px-1 rounded font-bold uppercase">New</div>
                                </div>
                                <!-- Upload Trigger -->
                                <label v-if="form.existing_photos.length + form.new_photos.length < 20" class="aspect-square flex flex-col items-center justify-center bg-white dark:bg-gray-800 rounded-xl border-2 border-dashed border-blue-200 hover:border-blue-400 cursor-pointer transition group">
                                    <i class="fa fa-plus text-blue-300 group-hover:text-blue-500 text-xl mb-1"></i>
                                    <span class="text-[10px] font-bold text-gray-400">Add Photos</span>
                                    <input type="file" @change="handlePhotoUpload" multiple accept="image/*" class="hidden" />
                                </label>
                            </div>
                            <InputError :message="form.errors.new_photos" />
                        </div>

                        <div class="rounded-xl border border-gray-200 p-4 text-sm text-gray-600">
                            Review complete listing details and click publish.
                        </div>
                    </div>

                    <!-- ACTIONS -->
                    <div class="flex justify-between items-center border-t pt-8">
                        <SecondaryButton v-if="currentStep > 1" @click="goPrevStep">Previous Step</SecondaryButton>
                        <div v-else></div>
                        
                        <div class="flex gap-4">
                            <button type="button" @click="closeModal" class="text-gray-500 font-bold hover:text-gray-800 transition">Discard</button>
                            <PrimaryButton v-if="currentStep < totalSteps" type="button" @click="goNextStep" class="px-8 py-3">Next Step</PrimaryButton>
                            <PrimaryButton v-else :disabled="form.processing" class="px-10 py-3 bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-200">
                                {{ form.processing ? 'Optimizing & Watermarking...' : (editingProperty ? 'Update Property' : 'Publish Property') }}
                            </PrimaryButton>
                        </div>
                    </div>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<style scoped>
.aspect-square { aspect-ratio: 1 / 1; }
.listing-modal :is(input:not([type='checkbox']):not([type='file']), select, textarea) {
    border-radius: 0.85rem;
    border: 1px solid #dbe4f0;
    background: #ffffff;
    min-height: 44px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    transition: all 0.15s ease;
}
.listing-modal :is(input:not([type='checkbox']):not([type='file']), select, textarea):focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
}
</style>
