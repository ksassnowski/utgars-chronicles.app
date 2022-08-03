import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";
// @ts-ignore
import { ZiggyVue } from 'ziggy';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

import "../css/app.css";
import "./bootstrap";

InertiaProgress.init({
    delay: 250,
    color: "#29d",
    includeCSS: true,
    showSpinner: false,
});

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
