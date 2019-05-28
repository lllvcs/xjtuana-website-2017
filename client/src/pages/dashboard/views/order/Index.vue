<template>
  <section class="content">
    <div class="row">
      <div class="col-xs-12"><!--查询条件开始-->
        <div class="box box-primary">
          <div class="box-header with-border">
            <i class="fa fa-search"></i>

            <h3 class="box-title">查询条件</h3>
          </div>

          <div class="box-body">
            <form class="row" @keyup.enter="newQuery" @submit.prevent="newQuery">
              <div class="form-group col-md-6 col-lg-3">
                <label for="input-search-status">报修单状态</label>
                <select class="form-control" id="input-search-status" name="status_id" v-model="form.status_id">
                  <option value="">- 查看全部 -</option>
                  <option v-for="status in $store.state.order_status" :value="status.id" :key="status.id">
                    {{ status.name }}
                  </option>
                </select>
              </div>

              <div class="form-group col-md-6 col-lg-3">
                <label for="input-search-department">运维部</label>
                <select class="form-control" id="input-search-department" name="department_id" v-model="form.department_id">
                  <option value="">- 查看全部 -</option>
                  <option v-for="department in departmentsOfService" :value="department.id" :key="department.id">
                    {{ department.name }}
                  </option>
                </select>
              </div>

              <div class="form-group col-md-6 col-lg-3">
                <label for="input-search-building">宿舍楼</label>
                <select class="form-control" id="input-search-building" name="building_id" v-model="form.building_id">
                  <option value="">- 查看全部 -</option>
                  <template v-for="department in departments">
                    <template v-if="department.id === form.department_id">
                      <option v-for="building in department.buildings" :value="building.id" :key="building.id">
                        {{ building.name }}
                      </option>
                    </template>
                  </template>
                </select>
              </div>

              <div class="form-group col-md-6 col-lg-3">
                <label for="input-search-room">房间号</label>
                <input class="form-control" id="input-search-room" name="room" type="text" placeholder="请输入要查询的房间号" v-model="form.room">
              </div>

              <transition name="slide-fade">
                <div v-show="moreOptions">
                  <div class="form-group col-md-6 col-lg-3">
                    <label for="input-search-id">报修单号</label>
                    <input class="form-control" id="input-search-id" name="id" type="text" placeholder="请输入要查询的报修单号" v-model="form.id">
                  </div>

                  <div class="form-group col-md-6 col-lg-3">
                    <label for="input-search-keyword">关键词</label>
                    <input class="form-control" id="input-search-keyword" name="keyword" type="text" placeholder="请输入要查询的关键词" v-model="form.keyword">
                  </div>

                  <OrderDateRangePicker
                    class="col-md-12 col-lg-6"
                    :start.sync="form.created_at_start"
                    :end.sync="form.created_at_end"
                    label="报修日期"
                  />

                  <div class="form-group col-md-6 col-lg-3">
                    <label for="input-search-user_name">报修用户</label>
                    <input class="form-control" id="input-search-user_name" name="user_name" type="text" placeholder="请输入要查询的报修人姓名" v-model="form.user_name">
                  </div>

                  <div class="form-group col-md-6 col-lg-3">
                    <label for="input-search-user_netid">报修用户NETID</label>
                    <input class="form-control" id="input-search-user_netid" name="user_netid" type="text" placeholder="请输入要查询的报修人NETID" v-model="form.user_netid">
                  </div>

                  <div class="form-group col-md-6 col-lg-3">
                    <label for="input-search-member_name">负责网管</label>
                    <input class="form-control" id="input-search-member_name" name="member_name" type="text" placeholder="请输入要查询的网管姓名" v-model="form.member_name">
                  </div>

                  <div class="form-group col-md-6 col-lg-3">
                    <label for="input-search-member_netid">负责网管NETID</label>
                    <input class="form-control" id="input-search-member_netid" name="member_netid" type="text" placeholder="请输入要查询的网管姓名" v-model="form.member_netid">
                  </div>
                </div>
              </transition>
            </form>

            <button type="button" class="btn btn-sm btn-warning pull-left"
              @click="moreOptions = !moreOptions"><i class="fa fa-fw" :class="{ 'fa-plus': !moreOptions, 'fa-minus': moreOptions }"></i> 更多</button>
            <button class="btn btn-sm btn-info pull-right" @click="reset"><i class="fa fa-fw fa-undo"></i> 重置</button>
          </div>

          <div class="box-footer text-center">
            <button class="btn btn-primary" @click="newQuery" :disabled="loading"><i class="fa fa-fw fa-search"></i> 查询</button>
          </div>
        </div>
      </div><!--查询条件结束-->

      <div class="col-xs-12"><!--报修单列表开始 -->
        <div class="box box-success">
          <div class="overlay" v-show="loading">
            <i class="fa fa-refresh fa-spin"></i>
          </div>

          <div class="box-header with-border">
            <i class="fa fa-list"></i>

            <h3 class="box-title">报修单列表</h3>
            <div v-if="canAddAll()&& showEditAll === true" class="box-tool pull-right">
              <button class="btn btn-success btn-sm" @click="selectAllOrNot();" ><i class="fa fa-plus"></i> {{buttomText}}</button>
            </div>
            <div v-if="canAddAll()&& showEditAll === false" class="box-tool pull-right">
              <button class="btn btn-success btn-sm" @click="showEditAll = true;resetAdd();form.status_id = 4" :disabled="showEditAll"><i class="fa fa-plus"></i> 批量加分</button>
            </div>
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
                    <th v-show="showEditAll">选择</th>
                    <th class="hidden-xs">单号</th>
                    <th>报修时间</th>
                    <th>宿舍</th>
                    <th>状态</th>
                    <th class="hidden-xs">负责网管</th>
                    <th>操作</th>
                  </tr>
                </thead>

                <tbody>
                  <tr  v-for="(data, index) in results.data" v-show="!showEditAll||canAddPoint(data)" :key="data.id" :class="{ 'text-muted': data.deleted_at, 'text-danger': data.status_id === 1, 'text-warning': data.status_id === 2, 'text-info': data.status_id === 3, 'text-success': data.status_id === 4 }">
                    <td v-show="showEditAll"><input type="checkbox" class="weui-check" v-on:click="addIt(index, data)" :disabled="!canAddPoint(data)" v-model="editorList[index]"  name="checkbox" /></td>
                    <td class="hidden-xs">{{ data.id }}</td>
                    <td>{{ data.created_at }}</td>
                    <td>{{ data.building.name }} - {{ data.room }}</td>
                    <td>{{ data.status.name }}</td>
                    <td class="hidden-xs">{{ data.member ? data.member.user.profile.name : '暂无' }}</td>
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
                <VuePagination
                  :total="results.last_page"
                  v-model="form.page"
                />
              </div>
            </div>

            <div v-else class="text-center">
              <p>暂无查询结果</p>
            </div>
          </div>
        </div>
      </div><!--报修单列表结束-->

      <!--填写维修报告开始-->
      <div v-if="canAddAll() && showEditAll" class="col-xs-12">
        <div class="box box-warning">
          <div class="box-header with-border">
            <i class="fa fa-flag-o"></i>

            <h3 class="box-title">批量评分</h3>

            <div class="box-tool pull-right">
              <span data-toggle="tooltip" title="" class="badge bg-yellow" data-original-title="批量修改个数">{{ orderCount }}</span>
            </div>
          </div>

          <div class="box-body">
            <form id="feedbackForm" autocomplete="off">
              <div class="form-group" :class="{ 'has-error': errors.has('score') }">
                <label for="input-points">给分 <span class="text-muted">（积分系统）</span></label>
                <div>
                  <label class="radio-inline">
                    <input type="radio" name="points" value='-2'
                      v-model="addForm.points" v-validate="'required'" data-vv-as="员工评分"> 差评
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="points" value='-1'
                      v-model="addForm.points" v-validate="'required'" data-vv-as="员工评分"> 不太好
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="points" value='0'
                      v-model="addForm.points" v-validate="'required'" data-vv-as="员工评分"> 一般般
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="points" value='1'
                      v-model="addForm.points" v-validate="'required'" data-vv-as="员工评分"> 还不错
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="points" value='2'
                      v-model="addForm.points" v-validate="'required'" data-vv-as="员工评分"> 好评
                  </label>
                </div>
                <div class="help-block with-errors">{{ errors.first('score') }}</div>
              </div>

              <div class="form-group" :class="{ 'has-error': errors.has('detail') }">
                <label for="input-detail">评分描述</label>
                <textarea class="form-control" id="input-detail" name="detail" placeholder="请输入评分描述" rows=3
                  v-model="addForm.desc" v-validate="'required'" data-vv-as="评分描述">
                </textarea>
                <div class="help-block with-errors">{{ errors.first('detail') }}</div>
              </div>
            </form>
          </div>

          <div class="box-footer text-center">
            <button
              class="btn btn-warning"
              @click="addOrderPoints">
              <i class="fa fa-fw fa-flag-o"></i> 提交报告
            </button>
            <button
              class="btn btn-default"
              @click="showEditAll = false">
              <i class="fa fa-fw fa-undo"></i> 取消
            </button>
          </div>
        </div>
      </div>
      <!--填写维修报告结束-->
    </div><!-- /.row (main row) -->
  </section>
</template>

<script>
import { mapState, mapGetters } from 'vuex'
import { swal } from '@/plugins'
import VuePagination from 'vue-bs-pagination'
import OrderDateRangePicker from '@/pages/dashboard/components/widgets/OrderDateRangePicker'
import moment from '@/plugins/moment'
export default {
  name: 'OrderIndex',
  components: {
    OrderDateRangePicker,
    VuePagination,
  },
  data () {
    return {
      showEditAll: false,
      editorList: new Array(50),
      editorLast: new Array(50),
      selectAllFlag: 0,
      itflag: '',
      form: {
        id: '',
        department_id: '',
        building_id: '',
        room: '',
        keyword: '',
        status_id: '',
        user_name: '',
        user_netid: '',
        member_name: '',
        member_netid: '',
        created_at_start: moment('2015-10-25').format('YYYY-MM-DD'),
        created_at_end: moment().format('YYYY-MM-DD'),
        page: 1,
        perpage: 15,
      },
      canSelectCount: '',
      buttomText: '全选',
      orderCount: 0,
      addForm: {
        desc: '',
        editor: '',
        points: '',
        memberList: new Array(50),
        orderList: new Array(50),
        netidList: new Array(50),
      },
      results: null,
      loading: false,
      moreOptions: document.documentElement.clientWidth > 768,
    }
  },
  computed: {
    ...mapState([
      'user',
      'departments',
    ]),
    ...mapGetters([
      'isManager',
      'isAdmin',
      'departmentsOfService',
    ]),
  },
  watch: {
    'form.department_id' () {
      if (this.form.building_id !== '') {
        this.form.building_id = ''
      } else {
        this.newQuery()
      }
    },
    'form.building_id': 'newQuery',
    'form.status_id': 'newQuery',
    'form.created_at_start': 'newQuery',
    'form.perpage': 'newQuery',
    'form.page': 'fetchData',
    'orderCount': 'buttonState',
    'showEditAll': 'newQuery',
  },
  created () {
    if (!this.mergeQuery()) {
      this.fetchData()
    }
  },
  activated () {
    this.mergeQuery()
  },
  methods: {
    async addOrderPoints () {
      this.addForm.editor = this.user.profile.name
      for (var i = 0; i < this.results.to; i = i + 1) {
        if (this.editorList[i] === true) {
          this.addForm.orderList[i] = this.results.data[i]['id']
          this.addForm.memberList[i] = this.results.data[i]['member_id']
          this.addForm.netidList[i] = this.results.data[i]['member']['user']['netid']
        }
      }
      let isValid = await this.$validator.validateAll('addForm')

      if (!isValid || this.orderCount === 0) return false
      swal({
        title: '是否确认提交？',
        text: '表单评分会操作会加入日志',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.post(
          '/api/points/editorder',
          this.addForm
        ),
      }).then(response => {
        return swal({
          title: response.data.status ? '操作失败！' : '操作成功！',
          text: response.data.message,
          type: response.data.status ? 'error' : 'success',
        })
      }).then(() => {
        this.resetAdd()
        this.showEditAll = false
        this.form.status_id = 4
        this.fetchData()
      }).catch(
        swal.noop
      )
    },

    buttonState () {
      this.selectAllFlag = 0
      this.buttomText = '全选'
      if (this.orderCount === this.canSelectCount) {
        this.selectAllFlag = 1
        this.buttomText = '全消'
      }
    },

    allCanSelect () {
      this.canSelectCount = 0
      for (var i = 0; i < this.results.to; i = i + 1) {
        if (this.canAddPoint(this.results.data[i])) {
          this.canSelectCount = this.canSelectCount + 1
        }
      }
    },

    selectAllOrNot () {
      if (this.selectAllFlag === 0) {
        for (var i = 0; i < this.results.to; i = i + 1) {
          this.orderCount = this.canSelectCount
          if (this.canAddPoint(this.results.data[i])) {
            this.editorLast[i] = false
            this.editorList[i] = true
          }
        }
      } else {
        for (i = 0; i < this.results.to; i = i + 1) {
          this.orderCount = 0
          this.editorList[i] = false
          this.editorLast[i] = true
        }
      }
    },

    canAddAll () {
      return this.isAdmin || this.isManager
    },

    canAddPoint (data) {
      return data && !data.deleted_at && data.status_id === 4 && (data.member_id === this.user.member.id || this.isAdmin || (this.isManager && data.building.department_id === this.user.member.department_id))
    },

    addIt (index, data) {
      if (this.editorList[index]) {
        if (this.editorLast[index] === false) {
          this.orderCount = this.orderCount - 1
          this.editorLast[index] = true
        }
      }

      if (!this.editorList[index]) {
        if (typeof this.editorLast[index] === 'undefined' || this.editorLast[index] == null || this.editorLast[index] === '' || this.editorLast[index]) {
          this.orderCount = this.orderCount + 1
          this.editorLast[index] = false
        }
      }
    },

    resetAdd () {
      this.addForm = {
        desc: '',
        editor: '',
        points: '',
        orderList: new Array(50),
        memberList: new Array(50),
        netidList: new Array(50),
      }
      this.selectAllFlag = 0
      this.buttomText = '全选'
      for (var i = 0; i < this.results.to; i = i + 1) {
        this.orderCount = 0
        this.editorList[i] = false
        this.editorLast[i] = true
      }
    },

    reset () {
      this.form = {
        department_id: '',
        building_id: '',
        room: '',
        keyword: '',
        status_id: '',
        member: '',
        created_at_start: moment('2015-10-25').format('YYYY-MM-DD'),
        created_at_end: moment().format('YYYY-MM-DD'),
        page: 1,
        perpage: 15,
      }
    },
    newQuery () {
      if (this.form.page !== 1) {
        this.form.page = 1
        this.allCanSelect()
      } else {
        this.fetchData()
      }
    },
    fetchData () {
      this.canSelectCount = 0
      this.loading = true
      this.$axios.get(
        '/api/order',
        { params: this.form }
      ).then(response => {
        this.results = response.data.data
        this.loading = false
        this.allCanSelect()
        this.resetAdd()
      }).catch(null)
    },
    mergeQuery () {
      if (Object.keys(this.$route.query).length > 0) {
        Object.assign(this.form, this.$route.query)
        this.$router.replace({ name: this.$route.name })
        return true
      }
      return false
    },
  },
}
</script>

<style lang="scss" type="text/css" scoped>
.pagination {
  margin: 0;
  a {
    cursor: pointer;
  }
}
.page-perpage{
  padding: 10px;
}
.page-total{
  padding: 10px;
}
.page-current{
  padding: 10px;
}
.slide-fade-enter-active {
  transition: all .5s ease;
}
.slide-fade-leave-active {
  transition: all .2s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slide-fade-enter, .slide-fade-leave-to
/* .slide-fade-leave-active for below version 2.1.8 */ {
  transform: translateX(10px);
  opacity: 0;
}
</style>
