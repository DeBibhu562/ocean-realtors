<script setup>
import { Link } from '@inertiajs/vue3';
import NavbarSearch from './NavbarSearch.vue';
import AnnouncementTopBar from './AnnouncementTopBar.vue';
import { computed } from 'vue';
import { usePropertyFilters } from '@/Composables/usePropertyFilters';
import WhatsAppIcon from '@/Components/WhatsAppIcon.vue';
import { siteContact, whatsappUrl } from '@/config/site';

const { filters, runSearch } = usePropertyFilters();

const categoryLabel = computed(() => {
    let label = '';
    
    if (filters.type && filters.type.length > 0) {
        label += filters.type[0].charAt(0).toUpperCase() + filters.type[0].slice(1);
    } else {
        label += 'Properties';
    }

    if (filters.city) {
        label += ` in ${filters.city}`;
    } else {
        label += ' Everywhere';
    }

    return label;
});

const handleSearch = () => {
    runSearch();
};
</script>

<template>
    <nav class="relative z-[220] w-full overflow-visible bg-white h-14 md:h-16 flex items-center shadow-sm border-b border-gray-200">
        <div class="container-page w-full max-w-full flex items-center justify-between gap-2 md:gap-3 min-w-0 px-3 sm:px-4">
            <!-- Left Side: Logo & Category Selector -->
            <div class="flex items-center gap-1.5 md:gap-4 min-w-0 shrink-0">
                <Link href="/" aria-label="Ocean Realtors home" class="shrink-0">
                    <img
                        src="/img/logo_oceanrealtors.webp"
                        alt="Ocean Realtors"
                        class="h-7 w-auto max-w-[110px] object-contain md:h-8 md:max-w-none"
                        width="264"
                        height="80"
                        loading="eager"
                        decoding="async"
                    />
                </Link>

                <div class="hidden xl:flex items-center bg-gray-50 rounded-xl px-4 py-2 border border-gray-200 cursor-pointer hover:bg-gray-100 transition-all group">
                    <span class="text-xs font-bold text-text-primary mr-3 tracking-wide">{{ categoryLabel }}</span>
                    <svg class="h-3.5 w-3.5 text-text-muted group-hover:text-text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                </div>
            </div>

            <!-- Center: Search Bar -->
            <div class="relative z-[230] min-w-0 flex-1 px-0.5 sm:px-1">
                <NavbarSearch @search="handleSearch" />
            </div>

            <!-- Right Side -->
            <div class="flex shrink-0 items-center gap-1 sm:gap-2">
                <a
                    :href="`tel:${siteContact.phoneTel}`"
                    class="hidden md:inline-flex items-center text-text-secondary hover:text-primary transition-colors whitespace-nowrap"
                    :aria-label="`Call ${siteContact.phoneDisplay}`"
                >
                    <span class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center mr-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                    </span>
                    <span class="text-xs font-bold tracking-wider hidden lg:inline">{{ siteContact.phoneDisplay }}</span>
                </a>
                <a
                    :href="whatsappUrl()"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-500 px-2 py-2 text-white transition-colors hover:bg-emerald-600 sm:px-3"
                    :aria-label="`WhatsApp ${siteContact.phoneDisplay}`"
                >
                    <WhatsAppIcon class="h-4 w-4 shrink-0" />
                    <span class="text-xs font-bold hidden md:inline">WhatsApp</span>
                </a>
            </div>
        </div>
    </nav>
    <AnnouncementTopBar variant="light" />
</template>
