<script setup>
import { ref } from 'vue';

const props = defineProps({
    filters: Object,
    activeFilterCount: Number,
    cities: { type: Array, default: () => ['Mumbai', 'Delhi', 'Bangalore', 'Pune', 'Hyderabad', 'Chennai', 'Noida', 'Gurgaon', 'Navi Mumbai', 'Thane'] }
});

defineEmits(['clear']);

const sections = ref({
    keyword: true,
    location: true,
    status: true,
    type: true,
    price: true,
    rooms: true,
    amenities: false
});

const propertyTypes = [
    { label: 'Apartment', value: 'apartment', icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4' },
    { label: 'House', value: 'house', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6' },
    { label: 'Villa', value: 'villa', icon: 'M8 14v20c0 4.418 7.163 8 16 8 1.387 0 2.717-.087 3.998-.253V21.747c-1.281.166-2.611.253-3.998.253-8.837 0-16-3.582-16-8z' },
    { label: 'Office', value: 'office', icon: 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745V6a2 2 0 012-2h14a2 2 0 012 2v7.255z' },
];

const amenities = [
    'Swimming Pool', 'Parking', 'Gym', 'Lift', 'Garden', 'Security', 'Power Backup', 'WiFi'
];

const bedOptions = [1, 2, 3, 4, 5];
const bathOptions = [1, 2, 3, 4];

const pricePresets = [
    { label: 'Under 50K', min: 0, max: 50000 },
    { label: '50K - 1L', min: 50000, max: 100000 },
    { label: '1L - 2.5L', min: 100000, max: 250000 },
    { label: '2.5L+', min: 250000, max: 10000000 },
];

const applyPricePreset = (preset) => {
    props.filters.min_price = preset.min;
    props.filters.max_price = preset.max;
};

const toggleSection = (section) => sections.value[section] = !sections.value[section];

const toggleType = (type) => {
    const index = props.filters.type.indexOf(type);
    if (index > -1) props.filters.type.splice(index, 1);
    else props.filters.type.push(type);
};

const toggleAmenity = (amenity) => {
    const index = props.filters.amenities.indexOf(amenity);
    if (index > -1) props.filters.amenities.splice(index, 1);
    else props.filters.amenities.push(amenity);
};
</script>

<template>
    <div class="space-y-3">
        <!-- Keyword -->
        <div class="bg-white border border-border rounded-xl p-4">
            <div @click="toggleSection('keyword')" class="flex items-center justify-between cursor-pointer mb-4">
                <h3 class="text-sm font-bold text-navy">Search</h3>
                <svg class="h-4 w-4 text-text-muted transition-transform duration-300" :class="{ 'rotate-180': !sections.keyword }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </div>
            <div v-show="sections.keyword" class="relative">
                <input 
                    v-model="filters.keyword" 
                    type="text" 
                    placeholder="Search by title, project..."
                    class="w-full h-10 pl-10 pr-4 text-xs border-gray-100 bg-gray-50 rounded-xl focus:ring-primary focus:border-primary focus:bg-white transition-all"
                />
                <svg class="absolute left-3.5 top-3 h-4 w-4 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
        </div>

        <!-- Location -->
        <div class="bg-white border border-border rounded-xl p-4">
            <div @click="toggleSection('location')" class="flex items-center justify-between cursor-pointer mb-4">
                <h3 class="text-sm font-bold text-navy">Location</h3>
                <svg class="h-4 w-4 text-text-muted transition-transform duration-300" :class="{ 'rotate-180': !sections.location }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </div>
            <div v-show="sections.location">
                <select v-model="filters.city" class="w-full h-10 px-3 text-xs border-gray-100 bg-gray-50 rounded-xl focus:ring-primary focus:border-primary focus:bg-white transition-all appearance-none">
                    <option value="">Properties Everywhere</option>
                    <option v-for="cityOption in cities" :key="cityOption" :value="cityOption">{{ cityOption }}</option>
                </select>
            </div>
        </div>

        <!-- Listing Type -->
        <div class="bg-white border border-border rounded-xl p-4">
            <div @click="toggleSection('status')" class="flex items-center justify-between cursor-pointer mb-4">
                <h3 class="text-sm font-bold text-navy">Listing Type</h3>
                <svg class="h-4 w-4 text-text-muted transition-transform duration-300" :class="{ 'rotate-180': !sections.status }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </div>
            <div v-show="sections.status" class="flex p-1 bg-gray-50 rounded-xl border border-gray-100">
                <button 
                    v-for="s in ['all', 'rent', 'sale']" 
                    :key="s"
                    @click="filters.status = s"
                    class="flex-1 py-1.5 text-xs font-black rounded-lg transition-all capitalize"
                    :class="filters.status === s ? 'bg-white text-primary shadow-sm' : 'text-text-muted hover:text-navy'"
                >
                    {{ s }}
                </button>
            </div>
        </div>

        <!-- Property Type -->
        <div class="bg-white border border-border rounded-xl p-4">
            <div @click="toggleSection('type')" class="flex items-center justify-between cursor-pointer mb-4">
                <h3 class="text-sm font-bold text-navy">Property Type</h3>
                <svg class="h-4 w-4 text-text-muted transition-transform duration-300" :class="{ 'rotate-180': !sections.type }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </div>
            <div v-show="sections.type" class="grid grid-cols-2 gap-2.5">
                <button 
                    v-for="type in propertyTypes" 
                    :key="type.value"
                    @click="toggleType(type.value)"
                    class="flex flex-col items-center p-3 rounded-xl border transition-all duration-300"
                    :class="filters.type.includes(type.value) ? 'border-primary bg-primary/5 text-primary' : 'border-gray-100 bg-white text-text-muted hover:border-primary/20 hover:bg-gray-50'"
                >
                    <svg class="h-5 w-5 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="type.icon" /></svg>
                    <span class="text-[10px] font-black uppercase tracking-tight">{{ type.label }}</span>
                </button>
            </div>
        </div>

        <!-- Price -->
        <div class="bg-white border border-border rounded-xl p-4">
            <div @click="toggleSection('price')" class="flex items-center justify-between cursor-pointer mb-4">
                <h3 class="text-sm font-bold text-navy">Budget</h3>
                <svg class="h-4 w-4 text-text-muted transition-transform duration-300" :class="{ 'rotate-180': !sections.price }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </div>
            <div v-show="sections.price" class="space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-[9px] font-black text-text-muted uppercase mb-1.5 block tracking-widest">Min (₹)</label>
                        <input
                            v-model.number="filters.min_price"
                            type="number"
                            min="0"
                            step="1000"
                            placeholder="0"
                            class="w-full h-9 px-3 text-xs border-gray-100 bg-gray-50 rounded-lg focus:ring-primary focus:border-primary focus:bg-white"
                        />
                    </div>
                    <div>
                        <label class="text-[9px] font-black text-text-muted uppercase mb-1.5 block tracking-widest">Max (₹)</label>
                        <input
                            v-model.number="filters.max_price"
                            type="number"
                            min="0"
                            step="1000"
                            placeholder="Any"
                            class="w-full h-9 px-3 text-xs border-gray-100 bg-gray-50 rounded-lg focus:ring-primary focus:border-primary focus:bg-white"
                        />
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button
                        v-for="preset in pricePresets"
                        :key="preset.label"
                        type="button"
                        @click="applyPricePreset(preset)"
                        class="px-3 py-1.5 text-[10px] font-black rounded-lg border border-gray-100 text-text-muted hover:border-primary hover:text-primary hover:bg-primary/5 transition-all"
                    >
                        {{ preset.label }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Bedrooms & Baths -->
        <div class="bg-white border border-border rounded-xl p-4">
            <div @click="toggleSection('rooms')" class="flex items-center justify-between cursor-pointer mb-4">
                <h3 class="text-sm font-bold text-navy">Rooms</h3>
                <svg class="h-4 w-4 text-text-muted transition-transform duration-300" :class="{ 'rotate-180': !sections.rooms }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </div>
            <div v-show="sections.rooms" class="space-y-5">
                <div>
                    <label class="text-[9px] font-black text-text-muted uppercase mb-3 block tracking-widest">Bedrooms</label>
                    <div class="flex flex-wrap gap-2">
                        <button
                            v-for="bed in bedOptions"
                            :key="`bed-${bed}`"
                            type="button"
                            @click="filters.beds = filters.beds === bed ? null : bed"
                            class="min-w-[2.5rem] h-9 px-2 text-[11px] font-black rounded-lg border transition-all duration-300"
                            :class="filters.beds === bed ? 'border-primary bg-primary text-white shadow-lg' : 'border-gray-100 bg-gray-50 text-text-muted hover:border-primary/30'"
                        >
                            {{ bed === 5 ? '5+' : bed }}
                        </button>
                    </div>
                </div>
                <div>
                    <label class="text-[9px] font-black text-text-muted uppercase mb-3 block tracking-widest">Bathrooms</label>
                    <div class="flex flex-wrap gap-2">
                        <button
                            v-for="bath in bathOptions"
                            :key="`bath-${bath}`"
                            type="button"
                            @click="filters.baths = filters.baths === bath ? null : bath"
                            class="min-w-[2.5rem] h-9 px-2 text-[11px] font-black rounded-lg border transition-all duration-300"
                            :class="filters.baths === bath ? 'border-primary bg-primary text-white shadow-lg' : 'border-gray-100 bg-gray-50 text-text-muted hover:border-primary/30'"
                        >
                            {{ bath === 4 ? '4+' : bath }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Toggle -->
        <div class="bg-white border border-border rounded-xl p-4">
            <label class="flex items-center justify-between cursor-pointer group">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-lg bg-yellow-50 flex items-center justify-center mr-3 text-yellow-500">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                    </div>
                    <span class="text-sm font-bold text-navy">Featured Only</span>
                </div>
                <input 
                    type="checkbox" 
                    v-model="filters.featured"
                    class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary"
                />
            </label>
        </div>

        <!-- Amenities -->
        <div class="bg-white border border-border rounded-xl p-4">
            <div @click="toggleSection('amenities')" class="flex items-center justify-between cursor-pointer mb-4">
                <h3 class="text-sm font-bold text-navy">Amenities</h3>
                <svg class="h-4 w-4 text-text-muted transition-transform duration-300" :class="{ 'rotate-180': !sections.amenities }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </div>
            <div v-show="sections.amenities" class="grid grid-cols-1 gap-2">
                <label v-for="amenity in amenities" :key="amenity" class="flex items-center cursor-pointer group py-0.5">
                    <input 
                        type="checkbox" 
                        :checked="filters.amenities.includes(amenity)" 
                        @change="toggleAmenity(amenity)"
                        class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary"
                    />
                    <span class="ml-3 text-xs font-medium text-text-secondary group-hover:text-navy transition-colors">{{ amenity }}</span>
                </label>
            </div>
        </div>
    </div>
</template>
