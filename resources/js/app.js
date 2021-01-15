require('./bootstrap');

window.Vue = require('vue').default;
import VueRouter from 'vue-router'
import VueProgressBar from 'vue-progressbar'
import swal from 'sweetalert2'
import { Form, HasError, AlertError } from 'vform'

import Users from './components/Admin/Users'
import Dashboard from './components/Admin/Dashboard'

Vue.use(VueRouter)
Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '3px'
})
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
Vue.component('pagination', require('laravel-vue-pagination'));

window.swal = swal;
window.Form = Form;
window.Fire = new Vue();
const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
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
    ],
});

const app = new Vue({
    el: '#app',
    router,
});
