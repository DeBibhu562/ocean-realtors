<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';

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
});

const properties = computed(() => {
    if (Array.isArray(props.properties)) return props.properties;
    if (Array.isArray(props.properties?.data)) return props.properties.data;
    return [];
});

const activeTab = ref('properties');
const showingPropertyModal = ref(false);
const editingProperty = ref(null);
const currentStep = ref(1);

const form = useForm({
    title: '', description: '', price: '', status: 'For Sale', type: 'Apartment', address: '', city: 'Gurgaon',
    bedrooms: 2, bathrooms: 2, garage: 1, size: 0, is_featured: false,
    category: 'Residential', listing_type: 'Rent', bhk: '2 BHK', society_name: '',
    built_up_area: '', carpet_area: '', age_of_property: 0, balconies: 1, furnish_type: 'Unfurnished',
    amenities: [], covered_parking: '1', open_parking: '0', tenant_type: 'Family',
    bachelor_preference: 'Open for both', pet_friendly: false, available_from: '',
    maintenance_charges: '', maintenance_type: 'Separate', security_deposit: '1 month',
    lock_in_period: 'None', brokerage_charge: 'None', brokerage_negotiable: false,
    parking_charges_type: 'Separate', painting_charges: 'None',
    floor_no: '', total_floors: '', facing: 'North', servant_room: false, rera_id: '',
    highlights: [], existing_photos: [], new_photos: [],
    _method: null
});

const photoPreviews = ref([]);

const openCreateModal = () => {
    editingProperty.value = null;
    currentStep.value = 1;
    photoPreviews.value = [];
    form.reset();
    showingPropertyModal.value = true;
};

const openEditModal = (property) => {
    editingProperty.value = property;
    currentStep.value = 1;
    photoPreviews.value = [];
    form.defaults({
        ...property,
        amenities: property.amenities || [],
        highlights: property.highlights || [],
        existing_photos: property.photos || [],
        new_photos: [],
        available_from: property.available_from ? new Date(property.available_from).toISOString().split('T')[0] : ''
    });
    form.reset();
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

const submit = () => {
    // Ensure numbers are numbers
    form.bedrooms = parseInt(form.bhk.split(' ')[0]) || 0;
    form.garage = parseInt(form.covered_parking) || 0;
    form.size = parseInt(form.built_up_area) || 0;

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
                    { label: 'Total Properties', value: properties.length, icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', color: 'text-primary bg-primary/10' },
                    { label: 'Active Listings', value: properties.filter(p => p.status === 'For Sale' || p.status === 'For Rent').length, icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', color: 'text-green-600 bg-green-50' },
                    { label: 'Featured', value: properties.filter(p => p.is_featured).length, icon: 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z', color: 'text-amber-500 bg-amber-50' },
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

        <!-- COMPACT 2-PAGE MODAL -->
        <Modal :show="showingPropertyModal" @close="closeModal" max-width="5xl">
            <div class="p-8 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-8 border-b pb-6">
                    <div>
                        <h2 class="text-2xl font-black text-gray-900 dark:text-white">{{ editingProperty ? 'Update Listing' : 'Create New Listing' }}</h2>
                        <p class="text-sm text-gray-500">Step {{ currentStep }} of 2: {{ currentStep === 1 ? 'Primary Details & Financials' : 'Amenities & Property Media' }}</p>
                    </div>
                    <div class="flex gap-2">
                        <div v-for="s in 2" :key="s" :class="currentStep >= s ? 'bg-blue-600' : 'bg-gray-200'" class="h-1.5 w-12 rounded-full transition-all"></div>
                    </div>
                </div>

                <form @submit.prevent="submit" class="space-y-8">
                    <!-- PAGE 1: PRIMARY & FINANCIALS -->
                    <div v-if="currentStep === 1" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <h3 class="font-bold text-lg border-l-4 border-blue-600 pl-3">Basic Information</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div><InputLabel value="Category*" /><select v-model="form.category" class="w-full rounded-lg border-gray-300 dark:bg-gray-900"><option>Residential</option><option>Commercial</option></select></div>
                                <div><InputLabel value="Looking to*" /><select v-model="form.listing_type" class="w-full rounded-lg border-gray-300 dark:bg-gray-900"><option>Rent</option><option>Sell</option><option>PG</option></select></div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div><InputLabel value="Property Type*" /><select v-model="form.type" class="w-full rounded-lg border-gray-300 dark:bg-gray-900"><option>Apartment</option><option>Villa</option><option>Studio</option><option>Floor</option></select></div>
                                <div><InputLabel value="Status*" /><select v-model="form.status" class="w-full rounded-lg border-gray-300 dark:bg-gray-900"><option>For Sale</option><option>For Rent</option></select></div>
                            </div>
                            <div><InputLabel value="Property Title*" /><TextInput v-model="form.title" class="w-full" placeholder="e.g. Modern 3BHK Apartment in DLF Phase 5" required /></div>
                            <div><InputLabel value="Full Address*" /><TextInput v-model="form.address" class="w-full" placeholder="Street, Sector, Area..." required /></div>
                            <div><InputLabel value="City*" /><TextInput v-model="form.city" class="w-full" required /></div>
                        </div>

                        <div class="space-y-6">
                            <h3 class="font-bold text-lg border-l-4 border-blue-600 pl-3">Financial Details</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div><InputLabel :value="form.listing_type === 'Rent' ? 'Rent Amount*' : 'Sale Price*'" /><TextInput type="number" v-model="form.price" class="w-full" required /></div>
                                <div><InputLabel value="Maintenance" /><TextInput type="number" v-model="form.maintenance_charges" class="w-full" /></div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div><InputLabel value="Security Deposit" /><TextInput v-model="form.security_deposit" class="w-full" /></div>
                                <div><InputLabel value="Brokerage" /><select v-model="form.brokerage_charge" class="w-full rounded-lg border-gray-300 dark:bg-gray-900"><option>None</option><option>15 Days</option><option>30 Days</option></select></div>
                            </div>
                            <div><InputLabel value="Availability Date" /><TextInput type="date" v-model="form.available_from" class="w-full" /></div>
                            <div class="flex items-center gap-4 pt-4">
                                <label class="flex items-center"><input type="checkbox" v-model="form.is_featured" class="rounded text-blue-600" /><span class="ml-2 text-sm font-bold">Featured Listing</span></label>
                                <label class="flex items-center"><input type="checkbox" v-model="form.pet_friendly" class="rounded text-blue-600" /><span class="ml-2 text-sm font-bold">Pet Friendly</span></label>
                            </div>
                        </div>
                    </div>

                    <!-- PAGE 2: FEATURES & MEDIA -->
                    <div v-if="currentStep === 2" class="space-y-10">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <h3 class="font-bold text-lg border-l-4 border-blue-600 pl-3">Specifications</h3>
                                <div class="grid grid-cols-3 gap-4">
                                    <div><InputLabel value="BHK" /><select v-model="form.bhk" class="w-full rounded-lg border-gray-300 dark:bg-gray-900"><option v-for="n in ['1 BHK','2 BHK','3 BHK','4 BHK','5+ BHK']" :key="n">{{n}}</option></select></div>
                                    <div><InputLabel value="Baths" /><TextInput type="number" v-model="form.bathrooms" class="w-full" /></div>
                                    <div><InputLabel value="Balconies" /><TextInput type="number" v-model="form.balconies" class="w-full" /></div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div><InputLabel value="Built Up Area" /><TextInput type="number" v-model="form.built_up_area" class="w-full" /></div>
                                    <div><InputLabel value="Property Age" /><TextInput type="number" v-model="form.age_of_property" class="w-full" /></div>
                                </div>
                                <div><InputLabel value="Furnishing" /><select v-model="form.furnish_type" class="w-full rounded-lg border-gray-300 dark:bg-gray-900"><option>Unfurnished</option><option>Semi Furnished</option><option>Fully Furnished</option></select></div>
                            </div>

                            <div class="space-y-6">
                                <h3 class="font-bold text-lg border-l-4 border-blue-600 pl-3">Highlights</h3>
                                <div class="grid grid-cols-2 gap-2">
                                    <button v-for="o in highlightOptions" :key="o.label" type="button" @click="toggleHighlight(o.label)" :class="form.highlights.includes(o.label) ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-50 dark:bg-gray-900 border-gray-200'" class="text-left px-4 py-3 border rounded-xl text-xs font-bold transition-all shadow-sm">
                                        {{ o.label }}
                                    </button>
                                </div>
                                <div><InputLabel value="Description" /><textarea v-model="form.description" class="w-full rounded-xl border-gray-300 dark:bg-gray-900" rows="3" placeholder="Describe unique features..."></textarea></div>
                            </div>
                        </div>

                        <!-- PHOTO UPLOADER WITH PREVIEW -->
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
                    </div>

                    <!-- ACTIONS -->
                    <div class="flex justify-between items-center border-t pt-8">
                        <SecondaryButton v-if="currentStep > 1" @click="currentStep--">Previous Step</SecondaryButton>
                        <div v-else></div>
                        
                        <div class="flex gap-4">
                            <button type="button" @click="closeModal" class="text-gray-500 font-bold hover:text-gray-800 transition">Discard</button>
                            <PrimaryButton v-if="currentStep === 1" type="button" @click="currentStep = 2" class="px-8 py-3">Next: Features & Photos</PrimaryButton>
                            <PrimaryButton v-else :disabled="form.processing" class="px-10 py-3 bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-200">
                                {{ form.processing ? 'Optimizing & Watermarking...' : (editingProperty ? 'Update Property' : 'Publish Property') }}
                            </PrimaryButton>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<style scoped>
.aspect-square { aspect-ratio: 1 / 1; }
</style>
