// vite.config.js

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/generales.css',
                'resources/css/preloader.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
