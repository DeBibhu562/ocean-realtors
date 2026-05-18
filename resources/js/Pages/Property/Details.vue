<script setup>
import OceanLayout from '@/Layouts/OceanLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    property: Object
});

const activePhoto = ref(props.property.photos ? props.property.photos[0] : props.property.image);
</script>

<template>
    <Head :title="property.title + ' - Ocean Realtors'" />
    <OceanLayout>
        <section class="property-details-section spad bg-gray-50 dark:bg-gray-950">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="pd-text bg-white dark:bg-gray-900 p-8 rounded-3xl shadow-xl border dark:border-gray-800">
                            <!-- Header Info -->
                            <div class="pd-title mb-8">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="flex gap-2 mb-4">
                                            <span class="bg-blue-600 text-white px-4 py-1 rounded-full text-xs font-black uppercase">{{ property.status }}</span>
                                            <span class="bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 px-4 py-1 rounded-full text-xs font-black uppercase">{{ property.type }}</span>
                                        </div>
                                        <h2 class="text-4xl font-black text-gray-900 dark:text-white mb-2 leading-tight">{{ property.title }}</h2>
                                        <p class="text-lg text-gray-500 font-medium"><span class="icon_pin_alt text-blue-500"></span> {{ property.address }}, {{ property.city }}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-4xl font-black text-blue-600">₹{{ Number(property.price).toLocaleString('en-IN') }}</div>
                                        <div v-if="property.listing_type === 'Rent'" class="text-gray-500 font-bold uppercase text-xs">Per Month</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Main Gallery -->
                            <div class="property-gallery mb-12">
                                <div class="main-photo mb-4 rounded-2xl overflow-hidden aspect-video shadow-lg border-4 border-white dark:border-gray-800">
                                    <img :src="activePhoto" class="w-full h-full object-cover transition-all duration-700" />
                                </div>
                                <div v-if="property.photos && property.photos.length > 1" class="thumbnail-grid grid grid-cols-5 gap-4">
                                    <div v-for="(p, idx) in property.photos" :key="idx" 
                                         @click="activePhoto = p"
                                         class="aspect-square rounded-xl overflow-hidden cursor-pointer border-2 transition-all"
                                         :class="activePhoto === p ? 'border-blue-600 scale-105 shadow-md' : 'border-transparent opacity-60 hover:opacity-100'">
                                        <img :src="p" class="w-full h-full object-cover" />
                                    </div>
                                </div>
                            </div>

                            <!-- Key Features -->
                            <div class="grid grid-cols-4 gap-6 mb-12 p-6 bg-gray-50 dark:bg-gray-800 rounded-2xl border dark:border-gray-700">
                                <div v-if="property.bhk" class="text-center">
                                    <div class="text-gray-400 text-xs font-black uppercase mb-1">BHK</div>
                                    <div class="text-lg font-bold text-gray-900 dark:text-white">{{ property.bhk }}</div>
                                </div>
                                <div v-if="property.bathrooms" class="text-center border-l dark:border-gray-700">
                                    <div class="text-gray-400 text-xs font-black uppercase mb-1">Baths</div>
                                    <div class="text-lg font-bold text-gray-900 dark:text-white">{{ property.bathrooms }}</div>
                                </div>
                                <div v-if="property.balconies" class="text-center border-l dark:border-gray-700">
                                    <div class="text-gray-400 text-xs font-black uppercase mb-1">Balcony</div>
                                    <div class="text-lg font-bold text-gray-900 dark:text-white">{{ property.balconies }}</div>
                                </div>
                                <div v-if="property.size" class="text-center border-l dark:border-gray-700">
                                    <div class="text-gray-400 text-xs font-black uppercase mb-1">Area</div>
                                    <div class="text-lg font-bold text-gray-900 dark:text-white">{{ property.size }} SqFt</div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="pd-desc mb-12">
                                <h4 class="text-2xl font-black text-gray-900 dark:text-white mb-6 border-l-4 border-blue-600 pl-4">Description</h4>
                                <p class="text-gray-600 dark:text-gray-400 leading-relaxed text-lg">{{ property.description }}</p>
                            </div>

                            <!-- Amenities -->
                            <div v-if="property.amenities && property.amenities.length" class="pd-amenities mb-12">
                                <h4 class="text-2xl font-black text-gray-900 dark:text-white mb-6 border-l-4 border-blue-600 pl-4">Amenities</h4>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    <div v-for="a in property.amenities" :key="a" class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-800 rounded-xl border dark:border-gray-700">
                                        <i class="fa fa-check-circle text-blue-500"></i>
                                        <span class="font-bold text-gray-700 dark:text-gray-300 text-sm uppercase">{{ a }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Highlights -->
                            <div v-if="property.highlights && property.highlights.length" class="pd-highlights">
                                <h4 class="text-2xl font-black text-gray-900 dark:text-white mb-6 border-l-4 border-blue-600 pl-4">Property Highlights</h4>
                                <div class="flex flex-wrap gap-2">
                                    <span v-for="h in property.highlights" :key="h" class="bg-blue-50 dark:bg-blue-900 text-blue-600 dark:text-blue-300 px-6 py-2 rounded-xl text-sm font-black uppercase shadow-sm">
                                        # {{ h }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Agent Contact -->
                    <div class="col-lg-4">
                        <div class="sidebar-contact sticky top-8 space-y-6">
                            <div class="bg-blue-600 text-white p-8 rounded-3xl shadow-2xl">
                                <h4 class="text-2xl font-black mb-6">Interested in this property?</h4>
                                <p class="mb-8 opacity-80 font-medium">Connect with our dedicated agent to schedule a private viewing.</p>
                                <div class="agent-info flex items-center gap-4 mb-8">
                                    <img src="https://ui-avatars.com/api/?name=Ocean+Agent&background=random" class="w-16 h-16 rounded-full border-2 border-white/50" />
                                    <div>
                                        <div class="font-black text-xl leading-tight">Ocean Realtors</div>
                                        <div class="text-xs uppercase font-bold opacity-70">Exclusive Agent</div>
                                    </div>
                                </div>
                                <button class="w-full bg-white text-blue-600 font-black py-4 rounded-2xl hover:bg-gray-100 transition shadow-lg">Contact Agent Now</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </OceanLayout>
</template>

<style scoped>
.spad { padding-top: 100px; padding-bottom: 100px; }
.shadow-xl { box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
</style>
