/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

Vue.use(require('vue-resource'));

Vue.use(require('vue-infinite-scroll'));

import VueSimpleAlert from "vue-simple-alert";

import VModal from 'vue-js-modal';

import { VuejsDatatableFactory } from 'vuejs-datatable';

Vue.use( VuejsDatatableFactory );

Vue.use(VueSimpleAlert);

Vue.use(VModal, {dynamic: true, dynamicDefaults: { clickToClose: false }, injectModalsContainer: true });


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('transfers-component', require('./components/TransfersComponent.vue').default);
Vue.component('model-component', require('./components/ModelComponent.vue').default);
Vue.component('InfiniteLoading', require('vue-infinite-loading'));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
