<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const page = usePage();
const isSidebarOpen = ref(true);
const isAdmin = page.props.auth?.user?.is_admin === true;

const navLinks = [
    { name: 'Dashboard', icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', href: route('dashboard'), active: route().current('dashboard') },
    { name: 'My Profile', icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', href: route('profile.edit'), active: route().current('profile.edit') },
    { name: 'Leads', icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', href: route('leads.index'), active: route().current('leads.index') },
    ...(isAdmin ? [
        { name: 'Agents', icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M9 20H4v-2a3 3 0 015.356-1.857M9 20h6M12 12a4 4 0 100-8 4 4 0 000 8z', href: route('admin.agents.index'), active: route().current('admin.agents.*') },
        { name: 'Reviews', icon: 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118L2.98 10.1c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z', href: route('admin.reviews.index'), active: route().current('admin.reviews.*') },
        { name: 'Blog', icon: 'M12 6.253v13m0-13C10.832 5.483 9.246 5 7.5 5 5.754 5 4.168 5.483 3 6.253v13C4.168 18.483 5.754 18 7.5 18c1.746 0 3.332.483 4.5 1.253m0-13C13.168 5.483 14.754 5 16.5 5c1.746 0 3.332.483 4.5 1.253v13C19.832 18.483 18.246 18 16.5 18c-1.746 0-3.332.483-4.5 1.253', href: route('admin.blog.index'), active: route().current('admin.blog.*') },
        { name: 'Blog Writers', icon: 'M11 5h2m-1-1v2m-7 3h14M5 9l1 10h12l1-10M9 13v3m6-3v3', href: route('admin.blog-writers.index'), active: route().current('admin.blog-writers.*') },
    ] : []),
    { name: 'Settings', icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', href: route('settings.index'), active: route().current('settings.index') },
];
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex">
        <!-- Sidebar -->
        <aside 
            :class="isSidebarOpen ? 'w-64' : 'w-20'"
            class="bg-navy border-r border-white/5 transition-all duration-300 flex flex-col z-50 overflow-hidden"
        >
            <!-- Sidebar Header -->
            <div class="h-16 flex items-center px-6 shrink-0 bg-navy-dark">
                <Link href="/" class="flex items-center space-x-3 group">
                    <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <span class="text-white font-black text-xl leading-none">O</span>
                    </div>
                    <span v-if="isSidebarOpen" class="text-white font-bold text-lg tracking-tight truncate transition-opacity duration-300">Ocean Admin</span>
                </Link>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto no-scrollbar">
                <Link 
                    v-for="link in navLinks" 
                    :key="link.name" 
                    :href="link.href"
                    class="flex items-center p-3 rounded-xl transition-all duration-200 group"
                    :class="link.active 
                        ? 'bg-primary text-white shadow-lg shadow-primary/20' 
                        : 'text-white/60 hover:bg-white/5 hover:text-white'"
                >
                    <svg 
                        class="h-6 w-6 shrink-0 transition-colors" 
                        :class="link.active ? 'text-white' : 'text-white/40 group-hover:text-white'"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="link.icon" />
                    </svg>
                    <span v-if="isSidebarOpen" class="ml-4 font-semibold text-sm transition-opacity duration-300">{{ link.name }}</span>
                </Link>
            </nav>

            <!-- Sidebar Footer (User Mini Profile) -->
            <div class="p-4 bg-navy-dark border-t border-white/5 shrink-0">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-primary font-bold text-sm shrink-0 uppercase">
                        {{ $page.props.auth.user.name.charAt(0) }}
                    </div>
                    <div v-if="isSidebarOpen" class="flex-1 min-w-0">
                        <div class="text-sm font-bold text-white truncate">{{ $page.props.auth.user.name }}</div>
                        <div class="text-[10px] text-white/40 font-medium truncate">Agent ID: #12345</div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Topbar -->
            <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-8 sticky top-0 z-40">
                <div class="flex items-center space-x-4">
                    <button @click="isSidebarOpen = !isSidebarOpen" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    
                    <!-- Search Bar -->
                    <div class="hidden md:flex items-center bg-gray-50 rounded-full px-4 py-2 w-80 group focus-within:ring-2 ring-primary/20 transition-all">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" placeholder="Global Search..." class="bg-transparent border-none focus:ring-0 text-sm ml-2 w-full placeholder:text-gray-400 font-medium" />
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <button class="relative p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <svg class="h-6 w-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
                    </button>

                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button class="flex items-center space-x-2 p-1 rounded-full hover:bg-gray-50 transition-colors">
                                    <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs">
                                        {{ $page.props.auth.user.name.charAt(0) }}
                                    </div>
                                    <span class="hidden sm:inline text-sm font-bold text-gray-700">{{ $page.props.auth.user.name }}</span>
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </template>

                            <template #content>
                                <DropdownLink :href="route('profile.edit')">Account Settings</DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-8 no-scrollbar bg-gray-50/50">
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
