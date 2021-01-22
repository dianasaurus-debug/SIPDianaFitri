require('./bootstrap');

window.Vue = require('vue').default;
import VueRouter from 'vue-router'
import VueProgressBar from 'vue-progressbar'
import swal from 'sweetalert2'
import { Form, HasError, AlertError } from 'vform'
import moment from 'moment'


import Users from './components/Admin/Users'
import Dashboard from './components/Admin/Dashboard'
import Categories from './components/Admin/Categories'
import Concerts from './components/Admin/Concerts'

import AllConcerts from './components/User/AllConcerts'
import AllCategories from './components/User/AllCategories'
import MyTickets from './components/User/MyTickets'


Vue.use(VueRouter)
Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '3px'
})
Vue.filter('formatDate', function(value) {
    if (value) {
        return moment(String(value)).format('MMMM Do YYYY')
    }
})
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('pulse-loader', require('vue-spinner/src/PulseLoader').default);
Vue.component('date-picker', require('vuejs-datepicker').default);


window.swal = swal;
window.Form = Form;
window.Fire = new Vue();
const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2000,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', swal.stopTimer)
        toast.addEventListener('mouseleave', swal.resumeTimer)
    }
})
window.toast = toast;

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/admin/dashboard',
            name: 'dashboard',
            component: Dashboard,
        },
        {
            path: '/admin/users',
            name: 'users',
            component: Users,
        },
        {
            path: '/admin/categories',
            name: 'categories',
            component: Categories,
        },
        {
            path: '/admin/concerts',
            name: 'concerts',
            component: Concerts,
        },
        {
            path: '/user/concerts',
            name: 'allconcerts',
            component: AllConcerts,
        },
        {
            path: '/user/categories',
            name: 'allcategories',
            component: AllCategories,
        },
        {
            path: '/user/mytickets',
            name: 'mytickets',
            component: MyTickets,
        },

    ],
});

const app = new Vue({
    el: '#app',
    router,
});
