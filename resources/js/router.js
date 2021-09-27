import Vue from 'vue'
import Router from 'vue-router'
import Home from './components/page/Home'
import About from './components/page/About.vue'
import StockCreate from './components/page/StockCreate.vue'
import StockArchive from './components/page/StockArchive.vue'
import StockArchiveImage from './components/page/StockArchiveImage.vue'
import StockSingle from './components/page/StockSingle.vue' 
import NotFound from './components/page/NotFound.vue'

Vue.use(Router)

export default new Router({
  mode: 'history',
    routes: [
    {
      path: '*',
      name: NotFound,
      component: NotFound
    },      
    {
      path: '/',
      name: 'home',
      component: Home
      },
    {
      path: '/about',
      name: 'about',
      component: About
        },
    {
      path: '/stocks/create',
      name: 'stockcreate',
      component: StockCreate
        },
    {
      path: '/stocks',
      name: 'stockarchive',
      component: StockArchive
    }, 
    {
      path: '/image',
      name: 'stockarchiveimage',
      component: StockArchiveImage
    },     
    {
      path: '/stocks/:id(\\d+)',
      name: 'stocksingle',
      component: StockSingle,
      props: route => ({ id: Number(route.params.id) })
    },    
  ]
})