import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        vue(), // 👈 Add this line
        laravel([
            'resources/js/app.js',
            'resources/css/app.css',
        ]),
    ],
});
