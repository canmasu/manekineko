import Vue from 'vue'
import VueRouter from 'vue-router'
import Login from '../components/login.vue'
import Home from '../components/home.vue'
import Welcome from '../components/welcome.vue'
import Trade from '../components/trade.vue'
import Launchpad from '../components/launchpad.vue'
import Wallet from '../components/wallet.vue'


Vue.use(VueRouter)

const routes = [
  {path: '/', redirect:'/login'},
  {path: '/login', component:Login },
  {path: '/home', component:Home,
    redirect :'/welcome', 
    children : [
      {path: '/welcome', component:Welcome },
      {path: '/wallet', component:Wallet },
      {path: '/trade', component:Trade },
      {path: '/launchpad', component:Launchpad }
    ]
  }
  
]
const router = new VueRouter({
  routes
})

export default router