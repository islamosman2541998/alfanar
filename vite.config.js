import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/bootstrap.rtl.css',
                'resources/css/bootstrap.ltr.css',
                'resources/css/site.css',
                'resources/js/site.js',
                'resources/css/admin.css',
                'resources/js/admin.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        cssCodeSplit: true,
        chunkSizeWarningLimit: 900,
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
