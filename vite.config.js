import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [ 'resources/js/app.js',
                'resources/js/chart-setup.js',  
                'resources/admin_assets/css/sb-admin-2.min.css',],
            refresh: true,
        }),
    ],
    server: {
        hmr: {
            overlay: false
        }
    }
});
