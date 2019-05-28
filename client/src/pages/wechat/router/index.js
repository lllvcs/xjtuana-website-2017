import Vue from 'vue'
import VueRouter from 'vue-router'
// import store from '../store/index'

import Order from '@/pages/wechat/views/order/Order'
import OrderIndex from '@/pages/wechat/views/order/index/Index'
import OrderCreate from '@/pages/wechat/views/order/create/Create'
import OrderShow from '@/pages/wechat/views/order/show/Show'
import User from '@/pages/wechat/views/user/User'

Vue.use(VueRouter)

const router = new VueRouter({
  base: '/wechat/',
  linkActiveClass: 'active',
  routes: [
    {
      path: '/',
      redirect: {
        name: 'order.index',
      },
    },
    {
      path: '/order',
      component: Order,
      meta: {
      },
      children: [
        {
          path: '/order/index',
          name: 'order.index',
          component: OrderIndex,
          meta: {
            title: '我的报修',
          },
        },
        {
          path: '/order/create',
          name: 'order.create',
          component: OrderCreate,
          meta: {
            title: '新建报修',
          },
        },
        {
          path: '/order/:id',
          name: 'order.show',
          component: OrderShow,
          meta: {
            title: '查看报修',
          },
        },
      ],
    },
    {
      path: '/user',
      name: 'user',
      component: User,
      meta: {
        title: '用户',
      },
    },
  ],
})

// router.beforeEach((to, from, next) => {
//   // 判断路由权限
//   let nextArg
//   if (to.matched.some(record => record.meta.onlyManager)) {
//     if (!store.getters.isManager) {
//       nextArg = {
//         name: 'home',
//       }
//     }
//   }
//   if (to.matched.some(record => record.meta.onlyAdmin)) {
//     if (!store.getters.isAdmin) {
//       nextArg = {
//         name: 'home',
//       }
//     }
//   }

//   next(nextArg)
// })

export default router
