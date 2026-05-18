import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { VitePWA } from 'vite-plugin-pwa';
import { visualizer } from 'rollup-plugin-visualizer';
import viteImagemin from 'vite-plugin-imagemin';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            ssr: 'resources/js/ssr.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        VitePWA({
            registerType: 'autoUpdate',
            manifest: {
                name: 'Ocean Realtors',
                short_name: 'OceanRealtors',
                description: 'Premium Property Portal',
                theme_color: '#0A1628',
                background_color: '#ffffff',
                display: 'standalone',
                icons: [
                    {
                        src: '/img/pwa-192x192.png',
                        sizes: '192x192',
                        type: 'image/png'
                    },
                    {
                        src: '/img/pwa-512x512.png',
                        sizes: '512x512',
                        type: 'image/png',
                        purpose: 'any maskable'
                    }
                ]
            }
        }),
        viteImagemin({
            gifsicle: { optimizationLevel: 7, interlaced: false },
            optipng: { optimizationLevel: 7 },
            mozjpeg: { quality: 80 },
            pngquant: { quality: [0.8, 0.9], speed: 4 },
            svgo: {
                plugins: [
                    { name: 'removeViewBox' },
                    { name: 'removeEmptyAttrs', active: false }
                ]
            }
        }),
        visualizer({
            open: false,
            filename: 'build-stats.html'
        })
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        if (id.includes('vue')) return 'vendor-vue';
                        if (id.includes('@inertiajs')) return 'vendor-inertia';
                        return 'vendor';
                    }
                }
            }
        },
        chunkSizeWarningLimit: 1000
    }
});
