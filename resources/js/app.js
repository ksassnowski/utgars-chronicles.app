require("./bootstrap");

window.Vue = require("vue");

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import { App, plugin } from "@inertiajs/inertia-vue";
import { InertiaProgress } from "@inertiajs/progress";
import PortalVue from "portal-vue";
import VueMeta from "vue-meta";
import * as Sentry from "@sentry/browser";
import { Vue as VueIntegration } from "@sentry/integrations";

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

Sentry.init({
    dsn: process.env.MIX_SENTRY_DSN,
    integrations: [new VueIntegration({ Vue })]
});
