import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/css/style.css', 
                'resources/css/dashboard.css', 
                'resources/css/admin.css', 
                'resources/css/games.css', 
                'resources/js/app.js',
                'resources/css/theme.css',
                'resources/js/theme-switcher.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});