import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // ==== [ Halaman Admin Login ] ==== \\
                'resources/css/Utama_Login_Admin.css',
                'resources/js/Utama_Login_Admin.js',

                // ==== [ Halaman Dashboard Admin ] ==== \\
                'resources/css/Utama_Dashboard_Admin.css',
                'resources/js/Utama_Dashboard_Admin.js',

                // ==== [ Halaman Dashboard Admin ] ==== \\
                'resources/js/Utama_Login_Pasien.js',
            ],
            refresh: true,
        }),
    ],
});
