import Vue from 'vue'
import VueRouter from 'vue-router'
import Login from '../components/login.vue'
import Home from '../components/home.vue'
import Welcome from '../components/welcome.vue'
import Wallet from '../components/wallet.vue'
import Marketplace from '../components/marketplace.vue'
import Launchpad from '../components/launchpad.vue'
import Earn from '../components/earn.vue'
import Analytics from '../components/analytics.vue'
import Contact from '../components/contact.vue'
import Token from '../components/token.vue'
import Sale from '../components/sale.vue'
import Wish from '../components/wish.vue'
import Clubhouse from '../components/clubhouse.vue'
import OpenDeal from '../components/opendeal.vue'
import ApprovedDeal from '../components/approveddeal.vue'
import TradeHistory from '../components/tradehistory.vue'
import ReceivedGift from '../components/receivedgift.vue'
import SentGift from '../components/sentgift.vue'
import SelectGuardian from '../components/selectGuardian.vue'
import WishingWall from '../components/wishingWall.vue'
import ViewGuardian from '../components/viewGuardian.vue'
import Auction from '../components/auction.vue'
import Painter from '../components/painter.vue'
import Generate from '../components/wish_generate.vue'


// meta web
import VueMeta from 'vue-meta'


Vue.use(VueMeta)
Vue.use(VueRouter)

const routes = [
  {path: '/', redirect:'/welcome'},
  {path: '/login', component:Login },
  {path: '/home', component:Home,
    redirect :'/wish/guardian', 
    children : [
      {path: '/welcome', component:Welcome },
      {path: '/wishingWall', component:WishingWall},
      {path: '/viewGuardian/:id/', component:ViewGuardian},
      {path: '/selectGuardian', component:SelectGuardian},
      {path: '/wallet', component:Wallet },
      {path: '/receivedgift', component:ReceivedGift },
      {path: '/sentgift', component:SentGift },
      {path: '/marketplace', component:Marketplace },
      {path: '/opendeal', component:OpenDeal },
      {path: '/approveddeal', component:ApprovedDeal },
      {path: '/tradehistory', component:TradeHistory },
      {path: '/launchpad', component:Launchpad },
      {path: '/earn', component:Earn },
      {path: '/clubhouse', component:Clubhouse },
      {path: '/wish/:guardian', component:Wish },
      {path: '/painter/:guardian', component:Painter },
      {path: '/analytics', component:Analytics },
      {path: '/contact', component:Contact },
      {path: '/token/:id/:ref', component:Token },
      {path: '/auction/:id/:ref', component:Auction },
      {path: '/sale/:id/', component:Sale },
      {path: '/generate/:guardian', component:Generate }
    ]
  }
  
]
const router = new VueRouter({
  routes
})


export default router