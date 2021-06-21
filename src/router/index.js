import Vue from 'vue'
import VueRouter from 'vue-router'
import Login from '../components/login.vue'
import Home from '../components/home.vue'
import Welcome from '../components/welcome.vue'
import Wallet from '../components/wallet.vue'
import Trade from '../components/trade.vue'
import Launchpad from '../components/launchpad.vue'
import Manekineko from '../components/manekineko.vue'
import Analytics from '../components/analytics.vue'
import Contact from '../components/contact.vue'
import Token from '../components/token.vue'
import Mint from '../components/mint.vue'


Vue.use(VueRouter)

const routes = [
  {path: '/', redirect:'/home'},
  {path: '/login', component:Login },
  {path: '/home', component:Home,
    redirect :'/welcome', 
    children : [
      {path: '/welcome', component:Welcome },
      {path: '/wallet', component:Wallet },
      {path: '/trade', component:Trade },
      {path: '/launchpad', component:Launchpad },
      {path: '/manekineko', component:Manekineko },
      {path: '/mint', component:Mint },
      {path: '/analytics', component:Analytics },
      {path: '/contact', component:Contact },
      {path: '/token/:id', component:Token }
    ]
  }
  
]
const router = new VueRouter({
  routes
})

export default router