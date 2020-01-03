require('./bootstrap');

window.Vue = require('vue');
Vue.component('game-board', require('@/components/GameBoard.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import { InertiaApp } from '@inertiajs/inertia-vue';
import PortalVue from 'portal-vue'

Vue.use(InertiaApp);
Vue.use(PortalVue);
Vue.prototype.$route = (...args) => route(...args).url()

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
