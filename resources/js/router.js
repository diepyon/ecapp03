import Vue from 'vue'
import Router from 'vue-router'
import Home from './components/page/Home'
import About from './components/page/About.vue'
import Post from './components/page/Post.vue'
import PostArchive from './components/page/PostArchive.vue'
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
      path: '/post',
      name: 'post',
      component: Post
        },
    {
      path: '/postarchive',
      name: 'postarchive',
      component: PostArchive
    },    
    
  ]
})