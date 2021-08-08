import 'vite/dynamic-import-polyfill';

import { createApp, h, configureCompat } from "vue";
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from "@inertiajs/progress";

import "../css/app.css";
import "./bootstrap";

InertiaProgress.init({
    delay: 250,
    color: "#29d",
    includeCSS: true,
    showSpinner: false
});

const pages = import.meta.glob('./Pages/**/*.vue');

configureCompat({
    MODE: 3,
});

createInertiaApp({
    resolve: async (name) => {
        const importPage = pages[`./Pages/${name}.vue`];

        if (!importPage) {
            throw new Error(`Unknown page ${name}. Is it located under Pages with a .vue extension?`);
        }

        return importPage().then(module => module.default);
    },

    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mixin({
                methods: {
                    $route: (...args) => window.route(...args).url(),
                    active: (route) => window.route().current(route),
                }
            })
            .mount(el);
    }
});
