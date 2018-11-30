import Vue from 'vue'
import App from'./App.vue'
import store from './store/index'
import { http } from './services/http'
import ElementUI from 'element-ui'
import locale from 'element-ui/lib/locale/lang/ja'
import '../sass/element-variables.scss'

Vue.use(ElementUI, { locale })

const app = new Vue({
    el: '#app', 
    store, 
    render: h => h(App), 
    created: () => { http.init() }
});
