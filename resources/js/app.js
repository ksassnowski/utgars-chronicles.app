import { createApp, h } from "vue";
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

createInertiaApp({
    resolve: async (name) => {
        return (await import(`./Pages/${name}.vue`)).default;
    },

    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .directive("focus", {
                mounted(el) {
                    el.focus();
                },
            })
            .mixin({
                methods: {
                    $route: (...args) => window.route(...args).url(),
                    active: (route) => window.route().current(route),
                }
            })
            .mount(el);
    }
});
