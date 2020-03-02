import Vue from 'vue';
import VueRouter from 'vue-router';
import Employees from './components/Employees';

Vue.use(VueRouter);

const router =  new VueRouter({
	mode: 'history',
	routes: [
		{ path: '/employees', component: Employees},
	]	
});

export default router;