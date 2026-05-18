<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    watermark: String,
});

const watermarkForm = useForm({ watermark: null });
const uploadWatermark = () => {
    watermarkForm.post(route('settings.watermark'), { onSuccess: () => watermarkForm.reset() });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="System Settings" />
        
        <template #header>
            <h2 class="text-2xl font-black text-gray-900 leading-tight">System Settings</h2>
        </template>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Branding Section -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <div class="flex items-start space-x-6">
                        <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center shrink-0">
                            <svg class="h-8 w-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" /></svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-gray-900">Visual Branding & Watermark</h3>
                            <p class="text-sm text-gray-500 mt-1">Upload your agency logo to automatically protect and brand all property photos. We recommend a transparent PNG for best results.</p>
                            
                            <div class="mt-8 space-y-8">
                                <div v-if="watermark" class="p-10 bg-navy rounded-3xl flex items-center justify-center border border-white/10 relative group overflow-hidden shadow-2xl shadow-navy/20">
                                    <img :src="watermark" class="h-32 w-auto opacity-90 group-hover:scale-110 transition-transform duration-700" />
                                    <div class="absolute inset-0 bg-navy/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <span class="text-xs font-black text-white uppercase tracking-widest bg-primary px-4 py-2 rounded-full shadow-lg">Active Branding</span>
                                    </div>
                                </div>

                                <form @submit.prevent="uploadWatermark" class="space-y-6">
                                    <div class="relative">
                                        <label class="block group cursor-pointer">
                                            <div class="border-2 border-dashed border-gray-200 group-hover:border-primary/50 transition-colors rounded-3xl p-12 text-center bg-gray-50/50">
                                                <svg class="h-10 w-10 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                                <span class="text-base font-bold text-gray-600 group-hover:text-primary transition-colors block">Click to upload transparent PNG</span>
                                                <span class="text-xs text-gray-400 mt-1 block">Maximum file size: 2MB</span>
                                                <input type="file" @input="watermarkForm.watermark = $event.target.files[0]" accept="image/png" class="hidden" />
                                            </div>
                                        </label>
                                        <InputError :message="watermarkForm.errors.watermark" class="mt-2" />
                                    </div>
                                    <PrimaryButton :disabled="watermarkForm.processing" class="w-full justify-center py-4 rounded-2xl shadow-xl shadow-primary/20 text-sm font-black uppercase tracking-widest">
                                        Update Global Watermark
                                    </PrimaryButton>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Side Cards -->
            <div class="space-y-6">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <h4 class="font-bold text-gray-900 mb-2">Account Status</h4>
                    <div class="flex items-center space-x-2 text-green-600">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-xs font-black uppercase tracking-tighter">Verified Agent Account</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-4 leading-relaxed">Your account is in good standing. All features are unlocked and available for use.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
