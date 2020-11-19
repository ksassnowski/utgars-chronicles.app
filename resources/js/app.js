require("./bootstrap");

import Vue from "vue";
import { App, plugin } from "@inertiajs/inertia-vue";
import { InertiaProgress } from "@inertiajs/progress";
import PortalVue from "portal-vue";
import VueMeta from "vue-meta";

Vue.use(plugin);
Vue.use(PortalVue);
Vue.use(VueMeta);

Vue.prototype.$route = (...args) => route(...args).url();

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
                resolveComponent: name =>
                    import(`@/Pages/${name}`).then(module => module.default)
            }
        })
}).$mount(app);
