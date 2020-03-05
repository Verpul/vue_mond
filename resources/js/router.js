import Vue from 'vue';
import VueRouter from 'vue-router';
import Employees from './components/Employees';
import Users from './components/Users';

Vue.use(VueRouter);

const router =  new VueRouter({
	mode: 'history',
	routes: [
		{ path: '/employees', component: Employees},
		{ path: '/users', component: Users},
	]	
});

export default router;