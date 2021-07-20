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

new Vue({
    render: h =>
        h(App, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: async (name) =>
                    (await import(`./Pages/${name}.vue`)).default
            }
        })
}).$mount(app);
