import Vue from 'vue'
import App from './App.vue'
import VueRouter from 'vue-router';
import axios from "axios";

import BlogMain from "./components/BlogMain";
import PostEdit from "./components/PostEdit";
import PostView from "./components/PostView";
import NotFound from "./components/NotFound";
import PostCreate from "./components/PostCreate";

Vue.prototype.$http = axios;
Vue.config.productionTip = false
Vue.use(VueRouter);

const routes = [
    { path: '/', component: BlogMain, name: 'index' },
    { path: '/show/:id', component: PostView, name: 'show' },
    { path: '/create', component: PostCreate, name: 'create' },
    { path: '/edit/:id', component: PostEdit, name: 'edit' },
    { path: '*', component: NotFound },

];


const router = new VueRouter({
    routes,
    mode: 'history'
});




new Vue({
    router,
    render: h => h(App),
    output: {
        filename: '[name].[hash].bundle.js'
    }

}).$mount('#app')
