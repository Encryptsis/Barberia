// vite.config.js

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/generales.css',
                'resources/css/acceso.css',
                'resources/css/preloader.css',
                'resources/js/index.js',        // Añadir index.js
                'resources/js/app.js',
                'resources/js/preloader.js',
                'resources/js/assets.js',
            ],
            refresh: true,
        }),
    ],
});
