import vue from "@vitejs/plugin-vue";
import path from "path";

export default ({ command }) => ({
    base: command === 'serve' ? '' : '/build/',
    publicDir: 'fake_dir_so_nothing_gets_copied',
    build: {
        manifest: true,
        outDir: 'public/build',
        emptyOutDir: true,
        rollupOptions: {
            input: 'resources/js/app.js',
        },
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js/'),
        }
    },
    plugins: [vue()],
});
