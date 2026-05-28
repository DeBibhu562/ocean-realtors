<script setup>
import { Link } from '@inertiajs/vue3';
import { siteContact, footerLinks, whatsappUrl } from '@/config/site';
import SiteLogo from '@/Components/SiteLogo.vue';
import WhatsAppIcon from '@/Components/WhatsAppIcon.vue';

const currentYear = new Date().getFullYear();
</script>

<template>
    <footer class="bg-navy text-white pt-10 pb-8 md:pt-14 md:pb-8">
        <div class="container-page max-w-6xl">
            <div
                class="grid grid-cols-1 gap-8 border-b border-white/10 pb-8 sm:grid-cols-2 sm:gap-x-8 sm:gap-y-10 md:gap-10 md:pb-12 lg:grid-cols-5 lg:gap-8"
            >
                <!-- Brand -->
                <div class="flex flex-col items-center text-center sm:items-start sm:text-left lg:col-span-2 lg:items-start lg:text-left">
                    <SiteLogo size="lg" theme="dark" class="inline-flex justify-center sm:justify-start" />
                    <p class="mt-4 max-w-sm text-sm leading-relaxed text-white/65">
                        Trusted property consultants in Gurgaon and the NCR — verified listings, dedicated agents, and end-to-end support for rent, buy, and commercial spaces.
                    </p>
                    <div class="mt-5 flex w-full max-w-xs flex-col gap-2.5 sm:max-w-none sm:flex-row sm:flex-wrap sm:justify-start">
                        <a
                            :href="`tel:${siteContact.phoneTel}`"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-white/10 px-4 py-3 text-xs font-semibold text-white transition-colors hover:bg-primary sm:w-auto sm:py-2"
                        >
                            Call {{ siteContact.phoneDisplay }}
                        </a>
                        <a
                            :href="whatsappUrl()"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 text-xs font-semibold text-white transition-colors hover:bg-emerald-500 sm:w-auto sm:py-2"
                        >
                            <WhatsAppIcon class="h-4 w-4" />
                            WhatsApp {{ siteContact.phoneDisplay }}
                        </a>
                    </div>
                </div>

                <!-- Company + Resources: 2 columns on mobile -->
                <div class="grid grid-cols-2 gap-6 sm:contents">
                    <div class="text-center sm:text-left">
                        <h3 class="mb-3 text-[11px] font-bold uppercase tracking-widest text-white/45 sm:mb-4">
                            Company
                        </h3>
                        <ul class="space-y-2.5">
                            <li v-for="link in footerLinks.company" :key="link.href">
                                <Link
                                    :href="link.href"
                                    class="text-sm text-white/65 transition-colors hover:text-white"
                                >
                                    {{ link.label }}
                                </Link>
                            </li>
                        </ul>
                    </div>

                    <div class="text-center sm:text-left">
                        <h3 class="mb-3 text-[11px] font-bold uppercase tracking-widest text-white/45 sm:mb-4">
                            Resources
                        </h3>
                        <ul class="space-y-2.5">
                            <li v-for="link in footerLinks.business" :key="link.href">
                                <Link
                                    :href="link.href"
                                    class="text-sm text-white/65 transition-colors hover:text-white"
                                >
                                    {{ link.label }}
                                </Link>
                            </li>
                            <li v-for="link in footerLinks.legal" :key="link.href">
                                <Link
                                    :href="link.href"
                                    class="text-sm text-white/65 transition-colors hover:text-white"
                                >
                                    {{ link.label }}
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Contact -->
                <div
                    class="col-span-1 border-t border-white/10 pt-6 text-center sm:border-0 sm:pt-0 sm:text-left lg:col-span-1"
                >
                    <h3 class="mb-4 text-[11px] font-bold uppercase tracking-widest text-white/45">
                        Contact
                    </h3>
                    <ul class="mx-auto max-w-xs space-y-3.5 text-sm text-white/65 sm:mx-0 sm:max-w-none">
                        <li class="flex items-start justify-center gap-2.5 sm:justify-start">
                            <svg
                                class="mt-0.5 h-4 w-4 shrink-0 text-primary"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                />
                            </svg>
                            <span class="text-left">{{ siteContact.address.full }}</span>
                        </li>
                        <li>
                            <a
                                :href="`tel:${siteContact.phoneTel}`"
                                class="transition-colors hover:text-white"
                            >
                                {{ siteContact.phoneDisplay }}
                            </a>
                        </li>
                        <li>
                            <a
                                :href="`mailto:${siteContact.email}`"
                                class="break-all transition-colors hover:text-white"
                            >
                                {{ siteContact.email }}
                            </a>
                        </li>
                        <li class="text-xs text-white/45">{{ siteContact.hours }}</li>
                    </ul>
                </div>
            </div>

            <!-- Bottom bar -->
            <div
                class="flex flex-col items-center gap-4 pt-8 text-center text-xs text-white/45 md:flex-row md:justify-between md:text-left"
            >
                <p>&copy; {{ currentYear }} {{ siteContact.company }}. All rights reserved.</p>
                <div class="flex flex-wrap justify-center gap-x-5 gap-y-2 md:justify-end">
                    <Link
                        v-for="link in footerLinks.explore.slice(0, 2)"
                        :key="link.href"
                        :href="link.href"
                        class="transition-colors hover:text-white"
                    >
                        {{ link.label }}
                    </Link>
                    <Link href="/privacy-policy" class="transition-colors hover:text-white">Privacy</Link>
                    <Link href="/terms" class="transition-colors hover:text-white">Terms</Link>
                </div>
            </div>
        </div>
    </footer>
</template>
