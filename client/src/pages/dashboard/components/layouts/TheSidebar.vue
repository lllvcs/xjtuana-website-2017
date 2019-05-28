<template>
  <aside class="main-sidebar">
    <section class="sidebar">
      <div v-if="user" class="user-panel">
        <div class="pull-left image">
          <i class="fa fa-user"></i>
        </div>
        <div class="pull-left info" v-if="$store.state.user">
          <p>{{ user.profile.name }}
            <small>({{ user.netid }})</small>
          </p>
          <p>{{ user.member.department.name }} - {{ user.member.designation.name }}</p>
        </div>
      </div>

      <ul class="sidebar-menu">

        <li class="header">控制台丨Dashboard</li>
        <router-link :to="{ name: 'home' }" tag="li" exact>
          <a>
            <i class="fa fa-dashboard"></i>
            <span>首页</span>
          </a>
        </router-link>

        <!--<router-link :to="{ name: 'myhome.index' }" tag="li">-->
        <!--  <a><i class="fa fa-user-circle-o"></i> <span>个人中心</span></a>-->
        <!--</router-link>-->

        <router-link :to="{ name: 'member.index' }" tag="li" exact>
          <a>
            <i class="fa fa-address-book-o"></i>
            <span>社员列表</span>
          </a>
        </router-link>

        <router-link :to="{ name: 'benefit.index' }" tag="li">
          <a>
            <i class="fa fa-gift"></i>
            <span>社团福利</span>
          </a>
        </router-link>

        <!--<router-link :to="{ name: 'user.index' }" tag="li" exact>-->
        <!--  <a><i class="fa fa-address-book"></i> <span>用户列表</span></a>-->
        <!--</router-link>-->

        <!--<router-link :to="{ name: 'myhome.profile' }" tag="li" exact>-->
        <!--  <a><i class="fa fa-vcard-o"></i> <span>我的资料</span></a>-->
        <!--</router-link>-->

        <!--<router-link :to="{ name: 'myhome.order' }" tag="li" exact>-->
        <!--  <a>-->
        <!--    <i class="fa fa-file-text-o"></i> -->
        <!--    <span>我的报修单</span>-->
        <!--    <span -->
        <!--      v-if="tasks && tasks.todoOrders.length" -->
        <!--      class="label label-warning pull-right">-->
        <!--      {{ tasks.todoOrders.length }}-->
        <!--    </span>-->
        <!--  </a>-->
        <!--</router-link>-->

        <!--积分商城开始-->
        <li class="header">积分商场丨Shop</li>

        <router-link :to="{ name: 'shop.index' }" tag="li" exact>
          <a>
            <i class="fa fa-money"></i>
            <span>商城购物</span>
          </a>
        </router-link>

        <router-link :to="{ name: 'shop.my_items' }" tag="li" exact>
          <a>
            <i class="fa fa-navicon"></i>
            <span>订单列表</span>
          </a>
        </router-link>
        <!--
        <router-link :to="{ name: 'shop.my_items' }" tag="li" exact>
          <a>
            <i class="fa fa-shopping-cart"></i>
            <span>我的购物</span>
          </a>
        </router-link>
        -->

        <!--积分商城结束-->

        <!--报修单管理开始-->
        <li class="header">报修单管理丨Orders</li>

        <router-link :to="{ name: 'order.index' }" tag="li" exact>
          <a>
            <i class="fa fa-wrench"></i>
            <span>全部报修单</span>
            <span v-if="order_statistics && order_statistics.newOrders" class="label label-primary pull-right">
              {{ order_statistics.newOrders }}
            </span>
          </a>
        </router-link>

        <router-link :to="{ name: 'order_statistics.index' }" tag="li" exact>
          <a>
            <i class="fa fa-bar-chart-o"></i>
            <span>报修统计</span>
          </a>
        </router-link>

        <!--报修单管理结束-->

        <template v-if="isManager">
          <!--社团管理开始-->
          <li class="header">社团管理丨Management</li>

          <router-link :to="{ name: 'management.member' }" tag="li" v-if="isAdmin" exact>
            <a>
              <i class="fa fa-users"></i>
              <span>社员管理</span>
            </a>
          </router-link>

          <router-link :to="{ name: 'management.append_order' }" tag="li" exact>
            <a>
              <i class="fa fa-file-text-o"></i>
              <span>手动加单</span>
            </a>
          </router-link>

          <router-link :to="{ name: 'management.points' }" tag="li" v-if="isAdmin" exact>
            <a>
              <i class="fa fa-credit-card"></i>
              <span>积分管理</span>
            </a>
          </router-link>

          <router-link :to="{ name: 'management.building_member' }" tag="li" exact>
            <a>
              <i class="fa fa-user-plus"></i>
              <span>网管分配</span>
            </a>
          </router-link>

          <router-link :to="{ name: 'management.faq' }" tag="li">
            <a>
              <i class="fa fa-question-circle-o"></i>
              <span>FAQ管理</span>
            </a>
          </router-link>
          <!--社团管理结束-->
        </template>

        <!--社团工具开始-->
        <li class="header">社团工具丨Tools</li>

        <router-link :to="{ name: 'tool.networklog' }" tag="li" exact>
          <a>
            <i class="fa fa-compass"></i>
            <span>网络日志查询</span>
          </a>
        </router-link>

        <template v-if="isManager">
          <router-link :to="{ name: 'tool.userinfo' }" tag="li">
            <a>
              <i class="fa fa-id-card"></i>
              <span>学生信息查询</span>
            </a>
          </router-link>

          <router-link :to="{ name: 'tool.sms' }" tag="li" exact>
            <a>
              <i class="fa fa-envelope"></i>
              <span>短信平台</span>
            </a>
          </router-link>
        </template>
        <!--社团工具结束-->

        <!--微信管理开始-->
        <template v-if="isAdmin">
          <li class="header">微信管理丨Wechat</li>

          <router-link :to="{ name: 'wechat.index' }" tag="li">
            <a>
              <i class="fa fa-wechat"></i>
              <span>微信服务号</span>
            </a>
          </router-link>
        </template>
        <!--微信管理结束-->

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
</template>

<script>
import { mapState, mapGetters } from 'vuex'
export default {
  name: 'TheSidebar',
  computed: {
    ...mapState(['user', 'tasks', 'order_statistics']),
    ...mapGetters(['isManager', 'isAdmin']),
  },
  created () {
    this.$router.afterEach(() => {
      const bodyClass = document.querySelector('body').classList
      if (bodyClass.contains('sidebar-open')) {
        bodyClass.remove('sidebar-open')
      }
    })
  },
}
</script>

<style lang="scss" type="text/css" scoped>
.sidebar-collapse {
  .user-panel {
    display: none;
  }
}

.user-panel {
  .image {
    width: 50px;
    color: white;
    font-size: 30px;
    text-align: center;
  }
}
</style>
