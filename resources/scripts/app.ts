import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { ZiggyVue } from 'ziggy-js';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

import "../css/app.css";
import "./bootstrap";

createInertiaApp({
    title: (title) => `${title} — Utgar's Chronicles`,

    // @ts-ignore
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),

    // @ts-ignore
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .directive("focus", {
                mounted(el) {
                    el.focus();
                },
            })
            .mixin({
                methods: {
                    // @ts-ignore
                    active: (route) => window.route().current(route),
                },
            })
            .mount(el);
    },
});
