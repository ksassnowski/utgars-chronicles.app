import fs from "fs";
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from "@vitejs/plugin-vue";
import path from "path";
import { homedir } from "os"

const host = "utgars-chronicles.test";

function detectServerConfig(host) {
    let keyPath = path.resolve(homedir(), `.config/valet/Certificates/${host}.key`)
    let certificatePath = path.resolve(homedir(), `.config/valet/Certificates/${host}.crt`)

    if (!fs.existsSync(keyPath)) {
        return {}
    }

    if (!fs.existsSync(certificatePath)) {
        return {}
    }

    return {
        hmr: {host},
        host,
        https: {
            key: fs.readFileSync(keyPath),
            cert: fs.readFileSync(certificatePath),
        },
    }
}

export default defineConfig({
    plugins: [
        laravel([
            'resources/scripts/app.js',
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
            ziggy: path.resolve('vendor/tightenco/ziggy/dist/vue.m'),
        }
    },
    server: detectServerConfig(host),
});
