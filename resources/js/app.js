import 'vite/dynamic-import-polyfill';

import Vue from "vue";
import { App, plugin } from "@inertiajs/inertia-vue";
import { InertiaProgress } from "@inertiajs/progress";
import PortalVue from "portal-vue";
import VueMeta from "vue-meta";

import "../css/app.css";
import "./bootstrap";

Vue.use(plugin);
Vue.use(PortalVue);
Vue.use(VueMeta);

Vue.prototype.$route = (...args) => route(...args);

window.Bus = new Vue();

const app = document.getElementById("app");

InertiaProgress.init({
    delay: 250,
    color: "#29d",
    includeCSS: true,
    showSpinner: false
});

const pages = import.meta.glob('./Pages/**/*.vue');

new Vue({
    render: h =>
        h(App, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: async (name) => {
                    const importPage = pages[`./Pages/${name}.vue`];

                    if (!importPage) {
                        throw new Error(`Unknown page ${name}. Is it located under Pages with a .vue extension?`);
                    }

                    return importPage().then(module => module.default);
                },
            },
        })
}).$mount(app);
