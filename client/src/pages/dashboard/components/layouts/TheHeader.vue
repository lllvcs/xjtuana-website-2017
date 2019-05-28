<template>
  <header class="main-header">
    <a href="/" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><img src="@/assets/img/logo_sm_white.png" alt=""></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><img src="@/assets/img/logo_nav.png" alt=""></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <!--<li><a :href="$laroute.route('home')"><i class="fa fa-home"></i></a></li>-->

          <li v-if="tasks" class="dropdown tasks-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span v-if="tasksCount" class="label label-danger">{{ tasksCount }}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header text-center">
                <span v-if="tasks.todoOrders.length">
                  你有 {{ tasks.todoOrders.length }} 个待处理报修
                </span>
                <span v-else class="text-muted">
                  当前没有待处理报修
                </span>
              </li>

              <li>
                <ul class="menu">
                  <li
                    v-for="order in tasks.todoOrders"
                    :key="order.id">
                    <router-link :to="{ name: 'order.show', params: { id: order.id } }">
                      <h3>
                        {{ order.building.name }} - {{ order.room }}
                        <small class="pull-right">{{ order.status.name }}</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-aqua" :style="{ width: 33.3 * (order.status_id - 1) + '%' }" role="progressbar"></div>
                      </div>
                    </router-link>
                  </li>
                </ul>
              </li>

              <li class="footer">
                <router-link :to="{ name: 'order.index' }">
                  <i class="fa fa-file-text-o"></i>
                  查看所有报修
                </router-link>
              </li>
            </ul>
          </li>

          <li v-if="user" class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-user"></i>
              <span class="hidden-xs">{{ user.profile.name }} ({{ user.netid }}<small>/{{points}}</small>)</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="@/assets/img/logo_sm_white.png" class="img-circle" alt="User Image">

                <p>
                  {{ user.profile.name }}
                  <small>{{ user.member.department.name }} - {{ user.member.designation.name }}</small>
                  <small>积分为:{{points}}</small>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <router-link :to="{ name: 'myhome.profile' }">我的资料</router-link>
                  </div>
                  <div class="col-xs-4 text-center">
                    <router-link :to="{ name: 'myhome.index' }">个人信息</router-link>
                  </div>
                  <div class="col-xs-4 text-center">
                    <router-link :to="{ name: 'myhome.order' }">我的维修</router-link>
                  </div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="/" class="btn btn-default btn-flat"><i class="fa fa-home"></i> 社团首页</a>
                </div>
                <div class="pull-right">
                  <a href="/logout" class="btn btn-default btn-flat"><i class="fa fa-sign-out"></i> 退出登录</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" @click.prevent="$store.dispatch('refresh')"><i class="fa fa-refresh"></i></a>
            <!--<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>-->
          </li>
        </ul>
      </div>
    </nav>
  </header>
</template>

<script>
import { mapState, mapGetters } from 'vuex'
export default {
  name: 'TheHeader',
  computed: {
    ...mapState([
      'user',
      'tasks',
    ]),
    ...mapGetters([
      'tasksCount',
    ]),

  },
  data () {
    return {
      points: 0,
    }
  },
  updated () {
    if (this.user !== null && this.user.member !== null) {
      this.points = this.user.member.points !== null ? this.user.member.points.points : 0
    }
  },
  method: {
  },
}
</script>

<style lang="scss" type="text/css" scoped>

</style>
