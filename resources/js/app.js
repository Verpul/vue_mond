require('./bootstrap');

window.Vue = require('vue');

import router from './router';
import { Form, HasError, AlertError } from 'vform';
import VueSweetalert2 from 'vue-sweetalert2';

Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
Vue.component('pagination', require('laravel-vue-pagination'));

window.Form = Form;

Vue.use(VueSweetalert2);

const app = new Vue({
	router,
    el: '#app',
});
