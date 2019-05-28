<template>
<div>
  <section class="content">

    <!--第一row开始-->
    <div v-if="order_statistics" class="row">
      <!--等待接单开始-->
      <div class="col-lg-3 col-xs-6">
        <router-link :to="{ name: 'order.index', query: { status_id: 1 } }">
          <div class="small-box bg-red">
            <div class="inner">
              <h3>{{ order_statistics.newOrders }}</h3>

              <p>等待接单</p>
            </div>
            <div class="icon">
              <i class="fa fa-bell"></i>
            </div>

            <div class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </div>
          </div>
        </router-link>
      </div>
      <!--等待接单结束-->

      <!--正在处理开始-->
      <div class="col-lg-3 col-xs-6">
        <router-link :to="{ name: 'order.index', query: { status_id: 2 } }">
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{ order_statistics.receivedOrders }}</h3>

              <p>正在处理</p>
            </div>
            <div class="icon">
              <i class="fa fa-wrench"></i>
            </div>
            <div class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </div>
          </div>
        </router-link>
      </div>
      <!--正在处理结束-->

      <!--待评价/已完成开始-->
      <div class="col-lg-3 col-xs-6">
        <router-link :to="{ name: 'order.index', query: { status_id: 4 } }">
          <div class="small-box bg-green">
            <div class="inner">
              <h3>{{ order_statistics.finishedOrders }}</h3>

              <p>待评价/已完成</p>
            </div>
            <div class="icon">
              <i class="fa fa-star"></i>
            </div>
            <div class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </div>
          </div>
        </router-link>
      </div>
      <!--待评价/已完成结束-->

      <!--全部报修开始-->
      <div class="col-lg-3 col-xs-6">
        <router-link :to="{ name: 'order.index', query: { status_id: '' } }">
          <div class="small-box bg-blue">
            <div class="inner">
              <h3>{{ order_statistics.allOrders }}</h3>

              <p>全部报修</p>
            </div>
            <div class="icon">
              <i class="fa fa-flag"></i>
            </div>
            <div class="small-box-footer">
              More info <i class="fa fa-arrow-circle-right"></i>
            </div>
          </div>
        </router-link>
      </div>
      <!--全部报修结束-->
    </div>
    <!--第一row结束-->

    <!--第二row开始-->
    <div class="row">
      <!--左侧col开始-->
      <section class="col-md-7 col-lg-8">

        <div class="box box-primary hidden-xs">

          <div class="box-header with-border">
            <i class="fa fa-line-chart"></i>
            <h3 class="box-title">报修统计</h3>
          </div>
          <div class="box-body">
            <DailyChart />
          </div>
        </div>
      </section>
      <!--左侧col结束-->

      <!--右侧col开始-->
      <section class="col-md-5 col-lg-4">

        <!--我的维修开始-->
        <router-link :to="{ name: 'myhome.order' }">
          <div class="info-box">
            <span class="info-box-icon bg-orange">
              <i class="fa fa-calendar-check-o"></i>
            </span>
            <div class="info-box-content">
              <p class="h4">
                <strong>我的维修</strong>
                <span
                  v-if="tasks && tasks.todoOrders.length"
                  class="label label-warning pull-right">
                  {{ tasks.todoOrders.length }}
                </span>
              </p>
              <p>查看负责的报修单</p>
            </div>
          </div>
        </router-link>
        <!--我的维修结束-->

        <!--全部报修开始-->
        <a href="http://nethelp.xjtu.edu.cn/stunetwork" target="_blank">
          <div class="info-box">
            <span class="info-box-icon bg-teal">
              <i class="fa fa-globe"></i>
            </span>
            <div class="info-box-content">
              <p class="h4">
                <strong>校园网状态</strong>
              </p>
              <p>查看学生区交换机状态</p>
            </div>
          </div>
        </a>
        <!--全部报修结束-->

        <!--全部报修开始-->
        <router-link :to="{ name: 'order.index' }">
          <div class="info-box">
            <span class="info-box-icon bg-maroon">
              <i class="fa fa-file-text-o"></i>
            </span>
            <div class="info-box-content">
              <p class="h4">
                <strong>全部报修</strong>
                <span
                  v-if="order_statistics && order_statistics.newOrders"
                  class="label label-primary pull-right">
                  {{ order_statistics.newOrders }}
                </span>
              </p>
              <p>查看全部报修单</p>
            </div>
          </div>
        </router-link>
        <!--全部报修结束-->

        <!--报修统计开始-->
        <router-link :to="{ name: 'order_statistics.index' }">
          <div class="info-box">
            <span class="info-box-icon bg-green">
              <i class="fa fa-bar-chart-o"></i>
            </span>
            <div class="info-box-content">
              <p class="h4"><strong>报修统计</strong></p>
              <p>查看报修统计图表</p>
            </div>
          </div>
        </router-link>
        <!--报修统计结束-->

        <!--社员列表开始-->
        <router-link :to="{ name: 'member.index' }">
          <div class="info-box">
            <span class="info-box-icon bg-purple">
              <i class="fa fa-address-book-o"></i>
            </span>
            <div class="info-box-content">
              <p class="h4"><strong>社员列表</strong></p>
              <p>查看社团通讯录</p>
            </div>
          </div>
        </router-link>
        <!--社员列表开始-->

        <!--个人资料开始-->
        <router-link :to="{ name: 'myhome.profile' }">
          <div class="info-box">
            <span class="info-box-icon bg-aqua">
              <i class="fa fa-id-card-o"></i>
            </span>
            <div class="info-box-content">
              <p class="h4"><strong>个人资料</strong></p>
              <p>查看/修改个人资料</p>
            </div>
          </div>
        </router-link>
        <!--个人资料结束-->

      </section>
      <!--右侧col结束-->
    </div>
    <!--第二row结束-->
  </section>
</div>
</template>

<script>
import { mapState } from 'vuex'
import DailyChart from './order/statistics/DailyChart'

export default {
  components: {
    DailyChart,
  },
  computed: {
    ...mapState([
      'order_statistics',
      'tasks',
    ]),
  },
  data () {
    return {
    }
  },
}
</script>
