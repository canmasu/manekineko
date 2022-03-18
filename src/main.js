import Vue from 'vue'
import App from './App.vue'
import router from './router'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import './assets/global.css'
import './assets/fonts/iconfont.css'
import axios from 'axios'
import VueAnalytics from 'vue-analytics';



Vue.prototype.$http=axios
axios.defaults.baseURL="https://api.neko.exchange"

Vue.config.productionTip = false


// google analytics
Vue.use(VueAnalytics, {
  id: 'UA-222268850-1',
  router
})


Vue.use(ElementUI);
new Vue({
  router,
  render: h => h(App)
}).$mount('#app');
