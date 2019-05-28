import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '../store/index'
import Home from '../views/Home'

Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',

  base: '/dashboard/',

  linkActiveClass: 'active',

  routes: [
    {
      path: '/',
      redirect: {
        name: 'home',
      },
      meta: {
        title: '首页',
      },
    },

    {
      path: '/home',
      name: 'home',
      // component: () => import('../views/Home'),
      component: Home,
      meta: {
        title: '首页',
      },
    },

    {
      path: '/myhome',
      name: 'myhome.index',
      component: () =>
        import('../views/myhome/Index'),
      meta: {
        title: '个人中心',
      },
    },

    {
      path: '/myhome/profile',
      name: 'myhome.profile',
      component: () =>
        import('../views/myhome/Profile'),
      meta: {
        title: '我的资料',
      },
    },

    {
      path: '/myhome/order',
      name: 'myhome.order',
      component: () =>
        import('../views/myhome/Order'),
      meta: {
        title: '我的维修',
      },
    },

    {
      path: '/myhome/index',
      name: 'myhome.points',
      component: () =>
        import('../views/myhome/Index'),
      meta: {
        title: '我的积分',
      },
    },

    {
      path: '/member',
      name: 'member.index',
      component: () =>
        import('../views/member/Index'),
      meta: {
        title: '社员列表',
      },
    },

    {
      path: '/benefit',
      name: 'benefit.index',
      component: () =>
        import('../views/benefit/Index'),
      meta: {
        title: '社团福利',
      },
      redirect: {
        name: 'benefit.service',
      },
      children: [{
        path: 'service',
        name: 'benefit.service',
        component: () =>
            import('../views/benefit/Service'),
        meta: {
          title: '常用服务',
        },
      },
      {
        path: 'software',
        name: 'benefit.software',
        component: () =>
            import('../views/benefit/Software'),
        meta: {
          title: '软件下载',
        },
      },
      {
        path: 'accounts',
        name: 'benefit.accounts',
        component: () =>
            import('../views/benefit/Accounts'),
        meta: {
          title: '会员帐号',
        },
      },
      ],
    },

    {
      path: '/shop/index',
      name: 'shop.index',
      component: () =>
        import('../views/shop/Index'),
      meta: {
        title: '积分商城',
      },
    },

    {
      path: '/shop/my_items',
      name: 'shop.my_items',
      component: () =>
        import('../views/shop/My_items'),
      meta: {
        title: '我的购物',
      },
    },

    {
      path: '/member/:id',
      name: 'member.show',
      component: () =>
        import('../views/member/Show'),
      meta: {
        title: '查看社员信息',
      },
    },

    {
      path: '/order',
      name: 'order.index',
      component: () =>
        import('../views/order/Index'),
      meta: {
        title: '全部报修单',
      },
    },

    {
      path: '/order/:id',
      name: 'order.show',
      component: () =>
        import('../views/order/Show'),
      meta: {
        title: '报修单详情',
      },
    },

    {
      path: '/order_statistics',
      name: 'order_statistics.index',
      component: () =>
        import('../views/order/statistics/Index'),
      redirect: {
        name: 'order_statistics.daily',
      },
      meta: {
        title: '报修统计',
      },
      children: [{
        path: 'daily',
        name: 'order_statistics.daily',
        component: () =>
            import('../views/order/statistics/DailyChart'),
        meta: {
          title: '报修统计',
        },
      },
      {
        path: '/member',
        name: 'order_statistics.member',
        component: () =>
            import('../views/order/statistics/MemberChart'),
        meta: {
          title: '报修统计',
        },
      },
      {
        path: 'building',
        name: 'order_statistics.building',
        component: () =>
            import('../views/order/statistics/BuildingChart'),
        meta: {
          title: '报修统计',
        },
      },
      ],
    },

    {
      path: '/management/building_member',
      name: 'management.building_member',
      component: () =>
        import('../views/management/BuildingMember'),
      meta: {
        title: '网管分配',
        onlyManager: true,
      },
    },

    {
      path: '/management/append_order',
      name: 'management.append_order',
      component: () =>
        import('../views/management/AppendOrder'),
      meta: {
        title: '手动加单',
        onlyManager: true,
      },
    },

    {
      path: '/management/points',
      name: 'management.points',
      component: () =>
        import('../views/management/Points'),
      meta: {
        title: '积分管理',
        onlyManager: true,
      },
    },

    {
      path: '/management/member',
      name: 'management.member',
      component: () =>
        import('../views/management/Member'),
      meta: {
        title: '社员管理',
        onlyAdmin: true,
      },
    },

    {
      path: '/management/faq',
      name: 'management.faq',
      component: () =>
        import('../views/management/Faq'),
      meta: {
        title: 'FAQ管理',
        onlyManager: true,
      },
    },

    {
      path: '/management/faq/category',
      name: 'management.faq_category',
      component: () =>
        import('../views/management/FaqCategory'),
      meta: {
        title: 'FAQ类别管理',
        onlyManager: true,
      },
    },

    {
      path: '/tool/networklog',
      name: 'tool.networklog',
      component: () =>
        import('../views/tool/NetworkLog'),
      meta: {
        title: '网络日志查询',
      // onlyManager: true,
      },
    },

    {
      path: '/tool/sms',
      name: 'tool.sms',
      component: () =>
        import('../views/tool/Sms'),
      meta: {
        title: '短信平台',
        onlyManager: true,
      },
    },

    {
      path: '/tool/userinfo',
      name: 'tool.userinfo',
      component: () =>
        import('../views/tool/UserInfo'),
      meta: {
        title: '学生信息查询',
        onlyManager: true,
      },
    },

    {
      path: '/wechat',
      name: 'wechat.index',
      component: () =>
        import('../views/wechat/Wechat'),
      meta: {
        title: '微信服务号',
        onlyAdmin: true,
      },
      children: [{
        path: '/wechat/menu',
        name: 'wechat.menu',
        component: () =>
            import('../views/wechat/components/Menu'),
        meta: {
          title: '自定义菜单 - 微信服务号',
          onlyAdmin: true,
        },
      },
      {
        path: '/wechat/template',
        name: 'wechat.template',
        component: () =>
            import('../views/wechat/components/Template'),
        meta: {
          title: '模板消息 - 微信服务号',
          onlyAdmin: true,
        },
      },
      ],
    },

  ],
})

router.beforeEach(async (to, from, next) => {
  if (!store.state.initialized) {
    await store.dispatch('init')
  } else {
    // 目前策略：每次切换路由刷新store
    store.dispatch('refresh')
  }

  // 判断路由权限
  let nextArg
  if (to.matched.some(record => record.meta.onlyManager)) {
    if (!store.getters.isManager) {
      nextArg = {
        name: 'home',
      }
    }
  }
  if (to.matched.some(record => record.meta.onlyAdmin)) {
    if (!store.getters.isAdmin) {
      nextArg = {
        name: 'home',
      }
    }
  }

  next(nextArg)
})

export default router
