/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

// importamos los plugins que hemos creado en js/plugins
import './plugins/vee-validate';
import './plugins/vue-snotify';
import './plugins/vue-wysiwyg';

Vue.prototype.$learningBus = new Vue;

Vue.component('topics', require('./components/Topics/Topics.vue').default);

const app = new Vue({
    el: '#app',
});
