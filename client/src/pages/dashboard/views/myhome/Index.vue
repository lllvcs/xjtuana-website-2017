<template>
<div>
  <section class="content" v-if="user">
    <div class="row">
      <!--左侧col开始-->
      <div class="col-md-8 col-lg-9">
        <!--个人资料开始-->
        <div class="box box-success">
          <div class="box-header with-border">
            <i class="fa fa-fw fa-vcard-o"></i>

            <h3 class="box-title">个人资料</h3>
          </div>

          <div class="box-body">
            <table class="table table-striped table-hover text-center">
              <tbody>
                <tr>
                  <th>我的积分</th>

                  <td>{{ points }}</td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>
        <!--个人资料结束-->

        <div class="box box-info">

          <div class="overlay" v-show="loadingHistory">
            <i class="fa fa-refresh fa-spin"></i>
          </div>

          <div class="box-header with-border">
            <i class="fa fa-hand-peace-o" aria-hidden="true"></i>

            <h3 class="box-title">积分历史信息</h3>
          </div>

          <div class="box-body">

            <div v-if="!loadingHistory && recordResult && recordResult.data">

              <div class="page-info">
                <div class="text-left page-perpage col-xs-6">每页显示
                  <select v-model="formRecord.perpage">
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                  </select>
              条</div>
                <div class="text-right page-current col-xs-6">当前第
                  <select v-model="formRecord.page">
                    <option v-for="i in recordResult.last_page" :value="i" :key="i">{{ i }}</option>
                  </select>
              页</div>
              </div>

              <table class="table table-striped table-bordered table-hover text-center">
                <thead>
                  <tr>
                    <th class="col-xs-2">分数</th>
                    <th class="col-xs-6">描述</th>
                    <th class="col-xs-2">修改人</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="dataRecord in recordResult.data" :key="dataRecord.id">
                    <td>{{ dataRecord.change }}</td>
                    <td>{{ dataRecord.desc }}</td>
                    <td>{{ dataRecord.editor }}</td>
                  </tr>
                </tbody>
              </table>

              <div class="box-footer">
                <div :title="'共' + recordResult.total + '条信息'" class="text-left page-total col-xs-2" style="padding-top: 10px;padding-bottom: ‒10;padding-bottom: 10px; white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                  共 {{ recordResult.total }} 条信息
                </div>

                <div class="text-right page-pagination col-xs-10" style="height: 39px;">
                  <vue-pagination style="margin-top: 3px;margin-bottom: 3px;" v-model="formRecord.page" :total="recordResult.last_page"></vue-pagination>
                </div>
              </div>

            </div>

            <div v-else class="text-center">
              <p>暂无查询结果</p>
            </div>

          </div>

        </div>

      </div>
      <!--左侧col结束-->

      <!--右侧col开始-->
      <div class="col-md-4 col-lg-3">
        <!--社团信息开始-->
        <div class="box box-danger">

          <div class="box-header with-border">
            <i class="fa fa-fw fa-user-secret"></i>

            <h3 class="box-title">社团信息</h3>
          </div>

          <div class="box-body">
            <table class="table table-striped table-hover text-center">
              <tbody>
                <tr>
                  <th>所属部门</th>

                  <td>{{ user.member.department.name }}</td>
                </tr>

                <tr>
                  <th>职位</th>

                  <td>{{ user.member.designation.name }}</td>
                </tr>

                <tr>
                  <th>入社时间</th>

                  <td>{{ user.member.created_at.substring(0,10) }}</td>
                </tr>

                <tr v-if="user.member.deleted_at">
                  <th>退役时间</th>
                  <td>{{ user.member.deleted_at.substring(0,10) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <!--社团信息结束-->

        <!--负责宿舍楼开始-->
        <div class="box box-primary">
          <div class="box-header with-border">
            <i class="fa fa-fw fa-building"></i>

            <h3 class="box-title">负责宿舍楼</h3>
          </div>

          <div class="box-body">
            <div v-if="user.member.buildings.length > 0">
              <table class="table table-striped table-hover text-center">
                <thead>
                  <tr>
                    <th>校区</th>

                    <th>楼名</th>
                  </tr>
                </thead>

                <tbody>
                  <tr v-for="building in user.member.buildings" :key="building.id">
                    <td>{{ building.campus.name }}</td>

                    <td>{{ building.name }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <p v-else>暂无负责宿舍楼</p>
          </div>
        </div>
        <!--负责宿舍楼结束-->
      </div>
      <!--右侧col结束-->
    </div>
    <!-- /.row (main row) -->
  </section>
</div>
</template>

<script>
import { mapState } from 'vuex'
import VuePagination from 'vue-bs-pagination'
export default {
  components: {
    VuePagination,
  },
  data () {
    return {
      loading: true,
      points: 0,
      loadingHistory: true,
      recordResult: null,
      formRecord: {
        id: '',
        page: 1,
        perpage: 15,
      },
    }
  },
  updated () {
    if (this.user !== null && this.user.member !== null) {
      this.points = this.user.member.points !== null ? this.user.member.points.points : 0
    }
  },
  computed: {
    ...mapState([
      'user',
    ]),
  },
  watch: {
    'formRecord.page': 'showHistory',
    'formRecord.perpage': 'newQueryHis',
  },
  methods: {
    newQueryHis () {
      if (this.formRecord.page !== 1) {
        this.formRecord.page = 1
      } else {
        this.showHistory()
      }
    },
    showHistory () {
      this.loadingHistory = true
      this.formRecord.id = this.user.member.id
      this.$axios
        .get('/api/points_record', {
          params: this.formRecord,
        })
        .then(response => {
          this.recordResult = response.data.data
          this.loadingHistory = false
        })
    },
  },
  created () {
    this.showHistory()
  },
}
</script>
