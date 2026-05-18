<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: { type: Boolean },
    status: { type: String },
});
</script>

<template>
    <Head title="My Profile" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-black text-gray-900 leading-tight">My Profile</h2>
                    <p class="text-sm text-gray-500 font-medium mt-1">Manage your personal information and account security.</p>
                </div>
            </div>
        </template>

        <div class="space-y-8">
            <!-- Profile Overview Card -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-10 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 blur-3xl rounded-full -mr-32 -mt-32"></div>
                
                <div class="flex flex-col md:flex-row md:items-center gap-8 relative z-10">
                    <div class="w-32 h-32 rounded-3xl bg-primary/10 flex items-center justify-center text-primary font-black text-4xl shadow-inner border border-primary/20 shrink-0 uppercase">
                        {{ $page.props.auth.user.name.charAt(0) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <h1 class="text-3xl font-black text-gray-900">{{ $page.props.auth.user.name }}</h1>
                            <span class="bg-green-50 text-green-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border border-green-100">Verified Agent</span>
                        </div>
                        <p class="text-gray-500 font-medium flex items-center">
                            <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            {{ $page.props.auth.user.email }}
                        </p>
                        <div class="mt-6 flex items-center space-x-6">
                            <div>
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Properties</div>
                                <div class="text-xl font-black text-gray-900">12</div>
                            </div>
                            <div class="w-px h-8 bg-gray-100"></div>
                            <div>
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Leads</div>
                                <div class="text-xl font-black text-gray-900">48</div>
                            </div>
                            <div class="w-px h-8 bg-gray-100"></div>
                            <div>
                                <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Member Since</div>
                                <div class="text-xl font-black text-gray-900">2024</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Personal Information -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 h-fit">
                    <div class="flex items-center space-x-3 mb-8">
                        <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 tracking-tight">Personal Information</h3>
                    </div>
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                    />
                </div>

                <!-- Security & Danger Zone -->
                <div class="space-y-8">
                    <!-- Security -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                        <div class="flex items-center space-x-3 mb-8">
                            <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 tracking-tight">Security</h3>
                        </div>
                        <UpdatePasswordForm />
                    </div>

                    <!-- Danger Zone -->
                    <div class="bg-red-50/30 rounded-3xl border border-red-100 p-8">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center text-red-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </div>
                            <h3 class="text-lg font-bold text-red-900 tracking-tight">Danger Zone</h3>
                        </div>
                        <p class="text-sm text-red-600/70 mb-6 font-medium leading-relaxed">Once your account is deleted, all of its resources and data will be permanently deleted. This action cannot be undone.</p>
                        <DeleteUserForm />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
