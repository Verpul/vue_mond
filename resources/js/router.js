import Vue from 'vue';
import VueRouter from 'vue-router';
import Employees from './components/Employees';
import Users from './components/Users';
import Todo from './components/Todo';

Vue.use(VueRouter);

const router =  new VueRouter({
	mode: 'history',
	routes: [
		{ path: '/employees', component: Employees},
		{ path: '/users', component: Users},
		{ path: '/todo', component: Todo},
	]	
});

export default router;