import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";
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

const pages = import.meta.glob("./Pages/**/*.vue");

createInertiaApp({
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),

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
                    $route: (...args) => window.route(...args),
                    active: (route) => window.route().current(route),
                },
            })
            .mount(el);
    },
});
