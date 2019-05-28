<template>
<div>

  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <!--报修单列表开始 -->
      <div class="col-xs-12">

        <div class="box box-success">

          <div class="overlay" v-show="loading">
            <i class="fa fa-refresh fa-spin"></i>
          </div>

          <div class="box-header with-border">
            <i class="fa fa-copy"></i>

            <h3 class="box-title">报修单列表</h3>

          </div>

          <div class="box-body">

            <div v-if="results && results.data">

              <div class="page-info">
                <div class="text-left page-perpage col-xs-6">每页显示
                  <select v-model="form.perpage">
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                  </select>
                条</div>
                <div class="text-right page-current col-xs-6">当前第
                  <select v-model="form.page">
                    <option v-for="i in results.last_page" :value="i" :key="i">{{ i }}</option>
                  </select>
                页</div>
              </div>

              <table class="table table-striped table-bordered table-hover text-center">
                <thead>
                  <tr>
                    <th class="hidden-xs">单号</th>
                    <th>报修时间</th>
                    <th>宿舍</th>
                    <th>状态</th>
                    <th>操作</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="data in results.data" :key="data.id" :class="{ 'text-muted': data.deleted_at, 'text-danger': data.status_id === 1, 'text-warning': data.status_id === 2, 'text-info': data.status_id === 3, 'text-success': data.status_id === 4 }">
                    <td class="hidden-xs">{{ data.id }}</td>
                    <td>{{ data.created_at }}</td>
                    <td>{{ data.building.name }} - {{ data.room }}</td>
                    <td>{{ data.status.name }}</td>
                    <td>
                      <router-link :to="{ name : 'order.show', params : { id: data.id } }">查看</router-link>
                    </td>
                  </tr>
                </tbody>
              </table>

              <div class="text-left page-total col-xs-2">
                共 {{ results.total }} 条信息
              </div>

              <div class="text-right page-pagination col-xs-10">
                <vue-pagination v-model="form.page" :total="results.last_page"></vue-pagination>
              </div>

            </div>

            <div v-else class="text-center">
              <p>暂无查询结果</p>
            </div>

          </div>

          <div class="box-footer text-center">

          </div>

        </div>

      </div>
      <!--报修单列表结束-->

    </div>
    <!-- /.row (main row) -->

  </section>

</div>
</template>

<script>
import { mapState } from 'vuex'
import VuePagination from 'vue-bs-pagination'
import moment from '@/plugins/moment'

export default {
  components: {
    VuePagination,
  },
  data () {
    return {
      form: {
        building_id: '',
        room: '',
        keyword: '',
        status_id: '',
        created_at_start: moment('2015-10-25').format('YYYY-MM-DD'),
        created_at_end: moment().format('YYYY-MM-DD'),
        page: 1,
        perpage: 15,
      },
      loading: false,
      results: null,
    }
  },
  computed: {
    ...mapState([
      'departments',
    ]),
  },
  watch: {
    'form': {
      handler () {
        this.fetchData()
      },
      deep: true,
    },
  },
  methods: {
    newQuery () {
      if (this.form.page !== 1) {
        this.form.page = 1
      } else {
        this.fetchData()
      }
    },
    fetchData () {
      this.loading = true
      this.$axios.get(
        '/api/user/order',
        { params: this.form }
      ).then((response) => {
        this.results = response.data.data
        this.loading = false
      }).catch(null)
    },
  },
  created () {
    this.fetchData()
  },
}
</script>
