import Vue from 'vue'
import App from './App.vue'
import router from './router'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import './assets/global.css'
import './assets/fonts/iconfont.css'
import axios from 'axios'


Vue.prototype.$http=axios
axios.defaults.baseURL="https://api.neko.exchange"

Vue.config.productionTip = false
Vue.use(ElementUI);
new Vue({
  router,
  render: h => h(App)
}).$mount('#app')
