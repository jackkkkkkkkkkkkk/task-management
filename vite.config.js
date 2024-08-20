import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        })
    ],
    server: {
        host: 'localhost',
        port: 5173, // Keep Vite running on a different port
        hmr: {
            host: 'localhost',
        },
    },
});
