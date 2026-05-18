<script setup>
import OceanHeader from '@/Components/OceanHeader.vue';
import OceanFooter from '@/Components/OceanFooter.vue';
import { onMounted, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

const initTemplate = () => {
    if (window.jQuery) {
        const $ = window.jQuery;
        
        // Background Set
        $('.set-bg').each(function () {
            var bg = $(this).data('setbg');
            $(this).css('background-image', 'url(' + bg + ')');
        });

        // Initialize Carousels
        if ($(".hs-slider").length > 0) {
            $(".hs-slider").owlCarousel({
                loop: true, margin: 20, nav: true, items: 1, dots: false,
                navText: ['<i class="arrow_left"></i>', '<i class="arrow_right"></i>'],
                smartSpeed: 1200, autoHeight: false, autoplay: true
            });
        }

        if ($(".fp-slider").length > 0) {
            $(".fp-slider").owlCarousel({
                loop: true, margin: 0, items: 1, dots: false, nav: true,
                animateOut: 'fadeOut', animateIn: 'fadeIn',
                navText: ['<i class="arrow_left"></i>', '<i class="arrow_right"></i>'],
                smartSpeed: 1200, autoHeight: false, autoplay: true
            });
        }

        if ($(".testimonial-slider").length > 0) {
            $(".testimonial-slider").owlCarousel({
                loop: true, margin: 0, items: 2, dots: false, nav: true,
                navText: ['<i class="arrow_left"></i>', '<i class="arrow_right"></i>'],
                smartSpeed: 1200, autoHeight: false, autoplay: true,
                responsive: { 0: { items: 1 }, 768: { items: 2 } }
            });
        }

        // Nice Select
        $('select').niceSelect();
        
        // Mobile Menu
        if ($(".nav-menu").length > 0 && $("#mobile-menu-wrap").children().length === 0) {
            $(".nav-menu").slicknav({
                prependTo: '#mobile-menu-wrap',
                allowParentLinks: true
            });
        }

        // MixItUp
        if ($('.property-filter').length > 0 && window.mixitup) {
            var containerEl = document.querySelector('.property-filter');
            window.mixitup(containerEl);
        }
    }
};

onMounted(() => {
    initTemplate();
});

// Re-initialize on page change
watch(() => page.url, () => {
    setTimeout(() => {
        initTemplate();
    }, 100);
});
</script>

<template>
    <div>
        <OceanHeader />
        <main>
            <slot />
        </main>
        <OceanFooter />
    </div>
</template>
