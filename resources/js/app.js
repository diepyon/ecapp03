import Vue from "vue";
import router from "./router";
import App from "./components/page/App"

import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)

require('./bootstrap');

/* import { fas } from '@fortawesome/free-solid-svg-icons'
import { far } from '@fortawesome/free-regular-svg-icons'

import {fab} from  '@fortawesome/fontawesome-free/scss/brands';
library.add(fab)


library.add(far,fas)
library.add(far)
Vue.component('font-awesome-icon', FontAwesomeIcon)
*/





window.Vue = require('vue').default;

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

const app = new Vue({
    el: '#app',
    
    router,
    
    components: {
        App
    },    
});