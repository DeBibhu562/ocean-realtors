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
            workbox: {
                cleanupOutdatedCaches: true,
                clientsClaim: true,
                skipWaiting: true,
                runtimeCaching: [
                    {
                        urlPattern: ({ url, request }) =>
                            request.method === 'GET' &&
                            url.pathname.startsWith('/build/assets/'),
                        handler: 'NetworkFirst',
                        options: {
                            cacheName: 'vite-build-assets-v3',
                            networkTimeoutSeconds: 5,
                            cacheableResponse: { statuses: [0, 200] },
                            expiration: {
                                maxEntries: 200,
                                maxAgeSeconds: 60 * 60 * 24 * 7,
                            },
                        },
                    },
                    {
                        urlPattern: ({ request, url }) =>
                            (request.destination === 'script' ||
                                request.destination === 'style' ||
                                request.destination === 'worker') &&
                            !url.pathname.startsWith('/build/assets/'),
                        handler: 'StaleWhileRevalidate',
                        options: {
                            cacheName: 'static-assets-v3',
                            expiration: {
                                maxEntries: 120,
                                maxAgeSeconds: 60 * 60 * 24 * 14,
                            },
                        },
                    },
                    {
                        urlPattern: ({ request }) => request.destination === 'image' || request.destination === 'font',
                        handler: 'StaleWhileRevalidate',
                        options: {
                            cacheName: 'media-assets-v2',
                            expiration: {
                                maxEntries: 400,
                                maxAgeSeconds: 60 * 60 * 24 * 30,
                            },
                        },
                    },
                    {
                        urlPattern: ({ url, request }) =>
                            request.method === 'GET' &&
                            url.pathname.startsWith('/api/'),
                        handler: 'NetworkFirst',
                        options: {
                            cacheName: 'api-get-v1',
                            networkTimeoutSeconds: 3,
                            cacheableResponse: { statuses: [0, 200] },
                            expiration: {
                                maxEntries: 80,
                                maxAgeSeconds: 60 * 5,
                            },
                        },
                    },
                ],
            },
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
    esbuild: {
        target: 'es2022',
        supported: {
            'class-field': true,
            'class-static-field': true,
        },
    },
    build: {
        target: 'es2022',
        modulePreload: {
            polyfill: false,
        },
        cssCodeSplit: true,
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        if (id.includes('@googlemaps')) return 'vendor-googlemaps';
                        if (id.includes('leaflet')) return 'vendor-leaflet';
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
