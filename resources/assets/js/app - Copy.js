
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');

import VueRouter from 'vue-router';
Vue.use(VueRouter);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const example = require('./components/Example.vue');

const routes = [
		{
			path: ws+"/example",
			component: example

		},
];
		alert(routes[0].path);

//Vue.component('example', require('./components/Example.vue'));

const router = new VueRouter({
	routes,
//	mode: 'history',
});

const app = new Vue({
    el: '#app',
    
    data: {
    	clicks: 0,
    	count: 0,
    	persons: [
    		{name: "ali", roll: "rol1"},
    		{name: "ali2", roll: "rol2"},
    		{name: "ali3", roll: "rol3"},
    	]
    },

    router,

    methods: {
		addrow: function(){
			this.count = this.persons.length+1;
			this.persons.push({name:"name"+this.count, roll:"rol"+this.count});
		},
		removerow: function(){
			//console.log(row);
			this.persons.splice(0, 1);
		}
    },

    computed: {
		counter:	function(){
	    		return this.clicks;
    		}
    }


});