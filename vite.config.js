import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '192.168.1.226', // <--- Add this line
        port: 5173, // Or whatever port you prefer for Vite
        hmr: {
            host: '192.168.1.226', // <--- Add this for Hot Module Replacement
        },
    },
});
