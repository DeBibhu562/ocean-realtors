<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import SiteLogo from '@/Components/SiteLogo.vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <!-- Offcanvas Menu Wrapper Begin -->
    <div class="offcanvas-menu-overlay"></div>
    <div class="offcanvas-menu-wrapper">
        <div class="canvas-close">
            <span class="icon_close"></span>
        </div>
        <div class="logo">
            <SiteLogo size="md" theme="light" />
        </div>
        <div id="mobile-menu-wrap"></div>
        <div class="om-widget">
            <ul>
                <li><i class="icon_mail_alt"></i> Aler.support@gmail.com</li>
                <li><i class="fa fa-mobile-phone"></i> 125-711-811 <span>125-668-886</span></li>
            </ul>
            <!-- Auth links removed -->
        </div>
        <div class="om-social">
            <a href="#"><i class="fa fa-facebook"></i></a>
            <a href="#"><i class="fa fa-twitter"></i></a>
            <a href="#"><i class="fa fa-youtube-play"></i></a>
            <a href="#"><i class="fa fa-instagram"></i></a>
            <a href="#"><i class="fa fa-pinterest-p"></i></a>
        </div>
    </div>
    <!-- Offcanvas Menu Wrapper End -->

    <!-- Header Section Begin -->
    <header class="header-section">
        <div class="hs-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-2">
                        <div class="logo">
                            <SiteLogo size="md" theme="light" />
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="ht-widget">
                            <ul>
                                <li><i class="icon_mail_alt"></i> Aler.support@gmail.com</li>
                                <li><i class="fa fa-mobile-phone"></i> 125-711-811 <span>125-668-886</span></li>
                            </ul>
                            <!-- Auth links removed -->
                        </div>
                    </div>
                </div>
                <div class="canvas-open">
                    <span class="icon_menu"></span>
                </div>
            </div>
        </div>
        <div class="hs-nav">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9">
                        <nav class="nav-menu">
                            <ul>
                                <li :class="{ active: $page.url === '/' }"><Link href="/">Home</Link></li>
                                <li><a href="#">Properties</a>
                                    <ul class="dropdown">
                                        <li><Link :href="route('properties.index')">Property Grid</Link></li>
                                        <li><Link href="/profile">Property List</Link></li>
                                    </ul>
                                </li>
                                <li :class="{ active: $page.url === '/agents' }"><Link href="/agents">Agents</Link></li>
                                <li :class="{ active: $page.url === '/about' }"><Link href="/about">About</Link></li>
                                <li :class="{ active: $page.url === '/blog' }"><Link href="/blog">Blog</Link></li>
                                <li :class="{ active: $page.url === '/contact' }"><Link href="/contact">Contact</Link></li>
                            </ul>
                        </nav>
                    </div>
                    <div class="col-lg-3">
                        <div class="hn-social">
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-youtube-play"></i></a>
                            <a href="#"><i class="fa fa-instagram"></i></a>
                            <a href="#"><i class="fa fa-pinterest-p"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- Header End -->
</template>
