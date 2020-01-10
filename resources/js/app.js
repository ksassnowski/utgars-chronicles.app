require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import { InertiaApp } from '@inertiajs/inertia-vue';
import PortalVue from 'portal-vue'
import VueMeta from 'vue-meta'
import * as Sentry from '@sentry/browser';
import { Vue as VueIntegration } from '@sentry/integrations';

Vue.use(InertiaApp);
Vue.use(PortalVue);
Vue.use(VueMeta);

Vue.prototype.$route = (...args) => route(...args).url();

window.Bus = new Vue();

const app = document.getElementById('app');

new Vue({
    render: h => h(InertiaApp, {
        props: {
            initialPage: JSON.parse(app.dataset.page),
            resolveComponent: name => import(`@/Pages/${name}`).then(module => module.default),
        },
    }),
}).$mount(app);

Sentry.init({
    dsn: process.env.MIX_SENTRY_DSN,
    integrations: [
        new VueIntegration({ Vue }),
    ],
});
