import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import laravel from 'laravel-vite-plugin';
import vue from "@vitejs/plugin-vue";
import path from "path";

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel([
            'resources/scripts/app.ts',
        ]),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/scripts/'),
        }
    }
});
