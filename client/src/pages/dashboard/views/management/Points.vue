<template>
  <div>

    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">

        <!--查询条件开始-->
        <div class="col-xs-12">

          <div class="box box-primary">

            <div class="box-header with-border">
              <i class="fa fa-search"></i>

              <h3 class="box-title">查询条件</h3>

            </div>

            <div class="box-body">
              <form class="row" @submit.prevent="newQuery">
                <div class="form-group col-md-6 col-lg-3">
                  <label for="input-search-old_members">社员状态</label>
                  <select class="form-control" id="input-search-old_members" name="old_members" v-model="form.old_members">
                    <option :value="0">现役社员</option>
                    <option :value="1">退役社员</option>
                  </select>
                </div>

                <div class="form-group col-md-6 col-lg-3">
                  <label for="input-search-department">所属部门</label>
                  <select class="form-control" id="input-search-department" name="department_id" v-model="form.department_id">
                    <option value="">- 查看全部 -</option>
                    <option v-for="department in departments" :value="department.id" :key="department.id">
                      {{ department.name }}
                    </option>
                  </select>
                </div>

                <div class="form-group col-md-6 col-lg-3">
                  <label for="input-update-designation">职位</label>
                  <select class="form-control" id="input-update-designation" name="designation_id" v-model="form.designation_id">
                    <option value="">- 查看全部 -</option>
                    <option v-for="designation in designations" :value="designation.id" :key="designation.id">
                      {{ designation.name }}
                    </option>
                  </select>
                </div>

                <div class="form-group col-md-6 col-lg-3">
                  <label for="input-search-name">姓名</label>
                  <input class="form-control" id="input-search-name" name="name" type="text" placeholder="请输入要查询的社员姓名" v-model="form.name">
                </div>
              </form>
              <button class="btn btn-sm btn-info pull-right" @click="reset">
                <i class="fa fa-fw fa-undo"></i> 重置</button>
            </div>

            <div class="box-footer text-center">
              <button @click="newQuery" class="btn btn-primary">
                <i class="fa fa-fw fa-search"></i> 查询</button>
            </div>

          </div>
        </div>
        <!--查询条件结束-->

        <!--编辑积分开始-->
        <div class="col-xs-12" v-if="editMember">

          <div class="box box-danger">

            <div class="box-header with-border">
              <i class="fa fa-edit"></i>

              <h3 class="box-title">编辑积分</h3>
            </div>

            <div class="box-body">
              <form @submit.prevent="updatePoints" data-vv-scope="updateForm">

                <div class="form-group col-md-6 col-lg-3">
                  <label for="input-update-points">修改后积分</label>
                  <input class="form-control" id="input-update-points" name="points" type="text" placeholder="请输入积分多少 " v-model="updateForm.points">
                </div>

                <div class="form-group col-md-6 col-lg-9" :class="{ 'has-error': errors.has('updateForm.desc') }">
                  <label for="input-update-desc">修改原因</label>
                  <input type="text" class="form-control" id="input-update-desc" name="desc" placeholder="请输入原因" v-model="updateForm.desc" v-validate="'required'" data-vv-as="修改原因">
                  <div class="help-block with-errors">{{ errors.first('updateForm.desc') }}</div>
                </div>

              </form>
            </div>

            <div class="box-footer text-center">
              <button @click="updatePoints" class="btn btn-success">
                <i class="fa fa-fw fa-check"></i> 提交</button>
              <button @click="setEdit(null)" class="btn btn-default">
                <i class="fa fa-fw fa-undo"></i> 取消</button>
            </div>
          </div>
        </div>
        <!--编辑积分结束-->

        <!--添加社员开始-->
        <div class="col-xs-12" v-show="showCreate">

          <div class="box box-warning">

            <div class="box-header with-border">
              <i class="fa fa-user-plus"></i>

              <h3 class="box-title">添加积分</h3>
            </div>

            <div class="box-body">
              <form @submit.prevent="createMember" data-vv-scope="createForm">

                <div class="form-group col-md-6 col-lg-6" :class="{ 'has-error': errors.has('createForm.netid') }">
                  <label for="input-create-netid">NETID</label>
                  <input type="text" class="form-control" id="input-create-netid" name="netid" placeholder="请输入要添加社员的NETID" v-model="createForm.netid" v-validate="'required'" data-vv-as="所属部门">
                  <div class="help-block with-errors">{{ errors.first('createForm.netid') }}</div>
                </div>

                <div class="form-group col-md-6 col-lg-6">
                  <label for="input-create-points">新的积分</label>
                  <input class="form-control" id="input-create-points" name="points" type="text" placeholder="请输入积分多少 " v-model="createForm.points">
                </div>

              </form>
            </div>

            <div class="box-footer text-center">
              <button @click="createMember" class="btn btn-success">
                <i class="fa fa-fw fa-check"></i> 提交</button>
              <button @click="showCreate = false; errors.clear('createForm')" class="btn btn-default">
                <i class="fa fa-fw fa-undo"></i> 取消</button>
            </div>
          </div>
        </div>
        <!--添加社员结束-->

        <!--社员列表开始-->
        <div class="col-xs-12">

          <div class="box box-success">

            <div class="overlay" v-show="loading">
              <i class="fa fa-refresh fa-spin"></i>
            </div>

            <div class="box-header with-border">
              <i class="fa fa-users"></i>

              <h3 class="box-title">社员列表</h3>
              <div class="box-tool pull-right">
                <button class="btn btn-success btn-sm" @click="showCreate = true; setEdit(null)" :disabled="showCreate">
                  <i class="fa fa-plus"></i> 添加</button>
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
                      <th>部门</th>
                      <th>职位</th>
                      <th>姓名</th>
                      <th>积分</th>
                      <th>操作</th>
                      <th>历史</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="data in results.data" :key="data.id">
                      <td>{{ data.member.department.name }}</td>
                      <td>{{ data.member.designation.name }}</td>
                      <td>{{ data.member.user.profile.name }}</td>
                      <td>{{ data.points }}</td>
                      <td>
                        <button @click="setEdit(data)" class="btn btn-xs btn-primary">
                          <i class="fa fa-edit"></i> 编辑</button>
                      </td>
                      <td>
                        <button @click="setEditRecord(data); historyPoints = true;showHistory(data)" class="btn btn-xs btn-primary">
                          <i class="fa fa-edit"></i> 历史</button>
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

          </div>
        </div>
        <!--社员列表结束-->

        <!--社员历史积分信息开始-->
        <div class="col-xs-12" v-show="historyPoints">

          <div class="box box-danger">

            <div class="overlay" v-show="loadingHistory">
              <i class="fa fa-refresh fa-spin"></i>
            </div>

            <div class="box-header with-border">
              <i class="fa fa-hand-peace-o" aria-hidden="true"></i>

              <h3 class="box-title">积分历史信息</h3>
            </div>

            <div class="box-body">

              <div v-if="resultsRecord_in_points && resultsRecord_in_points.data">

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
                      <option v-for="i in resultsRecord_in_points.last_page" :value="i" :key="i">{{ i }}</option>
                    </select>
                    页</div>
                </div>

                <table class="table table-striped table-bordered table-hover text-center">
                  <thead>
                    <tr>
                      <th class="col-xs-2">分数</th>
                      <th class="col-xs-6">描述</th>
                      <th class="col-xs-2">修改人</th>
                      <th class="col-xs-2">删除</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="dataRecord in resultsRecord_in_points.data" :key="dataRecord.id">
                      <td>{{ dataRecord.change }}</td>
                      <td>{{ dataRecord.desc }}</td>
                      <td>{{ dataRecord.editor }}</td>
                      <td>
                        <button @click="delectHistory(dataRecord.id)" class="btn btn-xs btn-primary">
                          <i class="fa fa-fire" aria-hidden="true"></i> 删除</button>
                      </td>
                    </tr>
                  </tbody>
                </table>

                <div class="text-left page-total col-xs-2">
                  共 {{ resultsRecord_in_points.total }} 条信息
                </div>

                <div class="text-right page-pagination col-xs-10">
                  <vue-pagination v-model="formRecord.page" :total="resultsRecord_in_points.last_page"></vue-pagination>
                </div>

              </div>

              <div v-else class="text-center">
                <p>暂无查询结果</p>
              </div>

            </div>

          </div>
        </div>
        <!--社员历史积分信息结束-->

      </div>
      <!-- /.row (main row) -->

    </section>

  </div>
</template>

<script>
import { mapState } from 'vuex'
import { swal } from '@/plugins'
import VuePagination from 'vue-bs-pagination'
export default {
  components: {
    VuePagination,
  },
  data () {
    return {
      resultsRecord_in_points: null,
      formRecord: {
        id: '',
        page: 1,
        perpage: 15,
      },
      form: {
        old_members: 0,
        department_id: '',
        designation_id: '',
        name: '',
        page: 1,
        perpage: 15,
      },
      updateForm: {
        id: '',
        desc: '',
        points: '',
        editor: '',
      },
      loadingHistory: false,
      loading: false,
      results: null,
      editMember: null,
      editMemberRecord: null,
      historyPoints: false,
      showCreate: false,
      createForm: {
        netid: '',

        points: '',
      },
    }
  },
  computed: {
    ...mapState(['user', 'departments', 'designations']),
  },
  watch: {
    'formRecord.page': 'showHistory',
    'formRecord.perpage': 'newQueryHis',
    'form.old_members': 'newQuery',
    'form.department_id': 'newQuery',
    'form.designation_id': 'newQuery',
    'form.perpage': 'newQuery',
    'form.page': 'fetchData',
  },
  created () {
    this.fetchData()
  },
  methods: {
    reset () {
      this.form = {
        old_members: 0,
        department_id: '',
        designation_id: '',
        name: '',
        page: 1,
        perpage: 15,
      }
    },
    newQuery () {
      this.historyPoints = false
      if (this.form.page !== 1) {
        this.form.page = 1
      } else {
        this.fetchData()
      }
    },

    newQueryHis () {
      if (this.formRecord.page !== 1) {
        this.formRecord.page = 1
      } else {
        this.showHistory()
      }
    },

    fetchData () {
      this.loading = true
      this.$axios
        .get('/api/points', {
          params: this.form,
        })
        .then(response => {
          this.setEdit(null)
          this.results = response.data.data
          this.loading = false
        })
    },
    showHistory () {
      this.loadingHistory = true
      this.formRecord.id = this.editMemberRecord.member_id
      this.$axios
        .get('/api/points_record', {
          params: this.formRecord,
        })
        .then(response => {
          this.resultsRecord_in_points = response.data.data
          this.loadingHistory = false
        })
    },
    async createMember () {
      let isValid = await this.$validator.validateAll('createForm')
      if (!isValid) return false
      swal({
        title: '是否确认提交？',
        text: '添加社员操作会记入操作日志',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.post('/api/points', this.createForm),
      })
        .then(response => {
          return swal({
            title: response.data.status ? '操作失败！' : '操作成功！',
            text: response.data.message,
            type: response.data.status ? 'error' : 'success',
          })
        })
        .then(() => {
          this.fetchData()
        })
        .catch(swal.noop)
    },

    async updatePoints () {
      let isValid = await this.$validator.validateAll('updateForm')
      this.updateForm.editor = this.user.profile.name
      this.updateForm.id = this.editMember.id
      if (!isValid) return false
      swal({
        title: '是否确认提交？',
        text: '编辑社员操作会记入操作日志',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () =>
          this.$axios.patch(
            `/api/points/${this.editMember.member_id}`,
            this.updateForm
          ),
      })
        .then(response => {
          return swal({
            title: response.data.status ? '操作失败！' : '操作成功！',
            text: response.data.message,
            type: response.data.status ? 'error' : 'success',
          })
        })
        .then(() => {
          this.fetchData()
        })
        .catch(swal.noop)
    },
    setEdit (member) {
      if (member === null) {
        this.editMember = null
        this.errors.clear('updateForm')
      } else {
        this.editMember = JSON.parse(JSON.stringify(member))
        this.showCreate = false
      }
    },
    setEditRecord (member) {
      if (member === null) {
        this.editMemberRecord = null
        this.errors.clear('updateForm')
      } else {
        this.editMemberRecord = JSON.parse(JSON.stringify(member))
        this.showCreate = false
      }
    },
    delectHistory (historyId) {
      if (historyId == null) {
        return false
      }
      swal({
        title: '是否确认提交？',
        text: '编辑社员操作会记入操作日志',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.delete(`/api/points_record/${historyId}`),
      })
        .then(response => {
          return swal({
            title: response.data.status ? '操作失败！' : '操作成功！',
            text: response.data.message,
            type: response.data.status ? 'error' : 'success',
          })
        })
        .then(() => {
          this.showHistory()
        })
        .catch(swal.noop)
    },
  },
}
</script>
