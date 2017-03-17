/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Datepicker from 'vuejs-datepicker';

Vue.component('example', require('./components/Example.vue'));
Vue.component('alert', require('./components/Alert.vue'));
Vue.component('alert-hidden', require('./components/AlertHidden.vue'));
Vue.component('vue-modal', require('./components/VueModal.vue'));
// Vue.directive('ajaxform', require('./directives/ajaxform.js'));
Vue.component('datepicker', Datepicker);

Vue.prototype.trans = (key) => {
    return _.get(window.trans, key, key);
};

// var ck = require('ckeditor');
import 'ckeditor';

