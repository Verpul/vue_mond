require('./bootstrap');

window.Vue = require('vue');

import router from './router';
import { Form, HasError, AlertError } from 'vform';
import VueSweetalert2 from 'vue-sweetalert2';
import Loading from 'vue-loading-overlay';

Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
Vue.component('pagination', require('laravel-vue-pagination'));

window.Form = Form;

const VueInputMask = require('vue-inputmask').default;

Vue.use(VueInputMask);
Vue.use(VueSweetalert2);
Vue.use(Loading);

const app = new Vue({
	router,
    el: '#app',
});
