import Vue from 'vue'
import Router from 'vue-router'
import { scrollBehavior } from '~/utils'

Vue.use(Router)

const page = path => () => import(`~/pages/${path}`).then(m => m.default || m)

export const routes = [
  { path: '/', name: 'welcome', component: page('welcome')},
  { path: '/login', name: 'login', component: page('auth/login') },
  { path: '/register', name: 'register', component: page('auth/register') },
  { path: '/password/reset', name: 'password.request', component: page('auth/password/email') },
  { path: '/password/reset/:token', name: 'password.reset', component: page('auth/password/reset') },

  { path: '/settings',
    component: page('settings/index'),
    children: [
      { path: '', redirect: { name: 'settings.profile' } },
      { path: 'profile', name: 'settings.profile', component: page('settings/profile') },
      { path: 'password', name: 'settings.password', component: page('settings/password') }
    ] },

  { path: '/:workspace', name: 'home', component: page('products') },
  { path: '/:workspace/:product', name: 'ideas', component: page('ideas') },
  { path: '/:workspace/:product/ideas/sort/:order', name: 'ideas.sort', component: page('ideas') },
  { path: '/:workspace/:product/ideas/sort/:order/:page', name: 'ideas.sort.page', component: page('ideas') },

  { path: '/:workspace/:product/ideas/topic/:idea', name: 'idea', component: page('idea') },
  { path: '/:workspace/:product/ideas/topic/:idea/:page', name: 'idea.page', component: page('idea') }
]

export function createRouter () {
  return new Router({
    routes,
    scrollBehavior,
    mode: 'history'
  })
}
