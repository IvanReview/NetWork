import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import * as auth from '../helpers/http_service'


Vue.use(VueRouter)

const routes = [
    {
      path: '/',
      meta: { requiresAuth: true },
      component: () => import('../components/MainWrap.vue'),
      children: [
          {
              path: '/feed',
              name: 'Home',
              meta: {requiresAuth: true},
              component: () => import('../components/HomePage.vue'),
          },
          {
              path: '/users',
              name: 'AllUsers',
              meta: { requiresAuth: true },
              component: () => import('../components/AllUsers.vue')
          },
          {
              path: '/images',
              name: 'Images',
              meta: { requiresAuth: true },
              component: () => import('../components/Images.vue')
          },
          {
              path: '/messages',
              name: 'Messages',
              meta: { requiresAuth: true },
              component: () => import('../components/Dialogs.vue')
          },
          {
              path: '/feed-edit',
              name: 'EditUser',
              meta: { requiresAuth: true },
              component: () => import('../components/EditUser.vue')
          },
          {
              path: '/friends',
              name: 'Friends',
              meta: { requiresAuth: true },
              component: () => import('../components/Friends.vue')
          },

          {
              path: '/user/:id',
              name: 'User',
              meta: { requiresAuth: true },
              component: () => import('../components/User.vue')
          },

          {
              path: '/images-friends/:id',
              name: 'ImagesUser',
              meta: { requiresAuth: true },
              component: () => import('../components/ImagesUser.vue')
          },

          {
              path: '/friends-user/:id',
              name: 'UserFriends',
              meta: { requiresAuth: true },
              component: () => import('../components/UserFriends.vue')
          },

      ]
    },



    {
      path: '/login',
      name: 'Login',

      component: () => import('../components/auth/Login.vue')
    },
    {
      path: '/register',
      name: 'Register',

      component: () => import('../components/auth/Register.vue')
    },
    {
      path: '*',
      name: 'Error404',
      component:  () => import('../components/errors/Error404.vue'),
    },
]



const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

  router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {

      if (!auth.isLoggedIn()) {
        next({
          path: '/login',
          query: { redirect: to.fullPath }
        })
      }
      /*else if(getProfile().role !== 'user') {
        next('/404')
      }*/
      else {
        next()
      }

    }  else {
      next()
    }
  })


export default router
