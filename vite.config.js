import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        VitePWA({
            registerType: 'autoUpdate',
            outDir: 'public/build',
            manifest: {
                name: 'Hôtels & Complexe hotelier',
                short_name: 'Hotel ERP',
                description: 'Plateforme de réservation hôtelière et gestion ERP (CRM, Stock, Facturation, RH)',
                theme_color: '#0B192C',
                background_color: '#F4EBE1',
                display: 'standalone',
                orientation: 'portrait',
                icons: [
                    {
                        src: '/images/icons/icon-192x192.png',
                        sizes: '192x192',
                        type: 'image/png'
                    },
                    {
                        src: '/images/icons/icon-512x512.png',
                        sizes: '512x512',
                        type: 'image/png'
                    }
                ]
            }
        })
    ],
    css: {
        preprocessorOptions: {
            scss: {
                quietDeps: true, // Masque les avertissements de dépréciation Bootstrap Sass
            }
        }
    }
});
