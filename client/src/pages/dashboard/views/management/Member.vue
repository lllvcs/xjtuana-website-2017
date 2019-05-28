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
            <div class="box-tool pull-right">
              <button class="btn btn-danger btn-sm" @click="exportMember"><i class="fa fa-download"></i> 导出现役社员通讯录</button>
            </div>
          </div>

          <div class="box-body">
            <form class="row" @submit.prevent="newQuery">
              <div class="form-group col-md-6 col-lg-4">
                <label for="input-search-department">所属部门</label>
                <select class="form-control" id="input-search-department" name="department_id" v-model="form.department_id">
                  <option value="">- 查看全部 -</option>
                  <option
                    v-for="department in departments"
                    :key="department.id"
                    :value="department.id">
                    {{ department.name }}
                  </option>
                </select>
              </div>

              <div class="form-group col-md-6 col-lg-4">
                <label for="input-update-designation">职位</label>
                <select class="form-control" id="input-update-designation" name="designation_id" v-model="form.designation_id">
                  <option value="">- 查看全部 -</option>
                  <option v-for="designation in designations" :value="designation.id" :key="designation.id">
                    {{ designation.name }}
                  </option>
                </select>
              </div>

              <div class="form-group col-md-6 col-lg-4">
                <label for="input-search-old_members">社员状态</label>
                <select class="form-control" id="input-search-old_members" name="old_members" v-model="form.old_members">
                  <option :value="0">现役社员</option>
                  <option :value="1">退役社员</option>
                  <option :value="null">全部</option>
                </select>
              </div>

              <div class="form-group col-md-6 col-lg-4">
                <label for="input-search-id">ID</label>
                <input class="form-control" id="input-search-id" name="id" type="text" placeholder="请输入要查询的社员ID" v-model="form.id">
              </div>

              <div class="form-group col-md-6 col-lg-4">
                <label for="input-search-netid">NetID</label>
                <input class="form-control" id="input-search-netid" name="netid" type="text" placeholder="请输入要查询的社员NetID" v-model="form.netid">
              </div>

              <div class="form-group col-md-6 col-lg-4">
                <label for="input-search-name">姓名</label>
                <input class="form-control" id="input-search-name" name="name" type="text" placeholder="请输入要查询的社员姓名" v-model="form.name">
              </div>
            </form>
            <button class="btn btn-sm btn-info pull-right" @click="reset"><i class="fa fa-fw fa-undo"></i> 重置</button>
          </div>

          <div class="box-footer text-center">
            <button @click="newQuery" class="btn btn-primary"><i class="fa fa-fw fa-search"></i> 查询</button>
          </div>

        </div>
      </div>
      <!--查询条件结束-->

      <!--编辑社员开始-->
      <div class="col-xs-12" v-if="editMember">

        <div class="box box-danger">

          <div class="box-header with-border">
            <i class="fa fa-edit"></i>

            <h3 class="box-title">编辑社员</h3>
            <div class="box-tool pull-right">
              <button class="btn btn-danger btn-sm" @click="deleteMember" v-if="!editMember.deleted_at"><i class="fa fa-sign-out"></i> 退社</button>
              <button class="btn btn-default btn-sm" @click="updateMemberNetid" v-if="!editMember.deleted_at"><i class="fa fa-retweet"></i> 修改NETID</button>
              <button class="btn btn-success btn-sm" @click="restoreMember" v-if="editMember.deleted_at"><i class="fa fa-sign-in"></i> 回社</button>
              <button class="btn btn-default btn-sm" @click="forceDeleteMember" v-if="editMember.deleted_at"><i class="fa fa-trash"></i> 永久删除</button>
            </div>
          </div>

          <div class="box-body">
            <form @submit.prevent="updateMember" data-vv-scope="updateForm">
              <div class="form-group col-md-6 col-lg-6">
                <label for="input-update-id">ID</label>
                <input type="text" class="form-control" id="input-update-id" :value="editMember.id" disabled>
              </div>
              <div class="form-group col-md-6 col-lg-6">
                <label for="input-update-netid">NetID</label>
                <input type="text" class="form-control" id="input-update-netid" :value="editMember.user.netid" disabled>
              </div>

              <div class="form-group col-md-6 col-lg-6" :class="{ 'has-error': errors.has('updateForm.department_id') }">
                <label for="input-update-department">所属部门</label>
                <select class="form-control" id="input-update-department" name="department_id"
                  v-model="editMember.department_id" v-validate="'required'" data-vv-as="所属部门">
                  <option v-for="department in departments" :value="department.id" :key="department.id">
                    {{ department.name }}
                  </option>
                </select>
                <div class="help-block with-errors">{{ errors.first('updateForm.department_id') }}</div>
              </div>

              <div class="form-group col-md-6 col-lg-6" :class="{ 'has-error': errors.has('updateForm.designation_id') }">
                <label for="input-update-designation">职位</label>
                <select class="form-control" id="input-update-designation" name="designation_id"
                  v-model="editMember.designation_id" v-validate="'required'" data-vv-as="职位">
                  <option v-for="designation in designations" :value="designation.id" :key="designation.id">
                    {{ designation.name }}
                  </option>
                </select>
                <div class="help-block with-errors">{{ errors.first('updateForm.designation_id') }}</div>
              </div>

            </form>
          </div>

          <div class="box-footer text-center">
            <button @click="updateMember" class="btn btn-success"><i class="fa fa-fw fa-check"></i> 提交</button>
            <button @click="setEdit(null)" class="btn btn-default"><i class="fa fa-fw fa-undo"></i> 取消</button>
          </div>
        </div>
      </div>
      <!--编辑社员结束-->

      <!--添加社员开始-->
      <div class="col-xs-12" v-show="showCreate">

        <div class="box box-warning">

          <div class="box-header with-border">
            <i class="fa fa-user-plus"></i>

            <h3 class="box-title">添加社员</h3>
          </div>

          <div class="box-body">
            <form @submit.prevent="createMember" data-vv-scope="createForm">

              <div class="form-group col-md-12 col-lg-12" :class="{ 'has-error': errors.has('createForm.netid') }">
                <label for="input-create-netid">NETID</label>
                <input type="text" class="form-control" id="input-create-netid" name="netid" placeholder="请输入要添加社员的NETID"
                  v-model="createForm.netid" v-validate="'required'" data-vv-as="所属部门">
                <div class="help-block with-errors">{{ errors.first('createForm.netid') }}</div>
              </div>

              <div class="form-group col-md-6 col-lg-6" :class="{ 'has-error': errors.has('createForm.department_id') }">
                <label for="input-create-department">所属部门</label>
                <select class="form-control" id="input-create-department" name="department_id"
                  v-model="createForm.department_id" v-validate="'required'" data-vv-as="所属部门">
                  <option disabled style="display:none;" value="">- 选择部门 -</option>
                  <option v-for="department in departments" :value="department.id" :key="department.id">
                    {{ department.name }}
                  </option>
                </select>
                <div class="help-block with-errors">{{ errors.first('createForm.department_id') }}</div>
              </div>

              <div class="form-group col-md-6 col-lg-6" :class="{ 'has-error': errors.has('createForm.designation_id') }">
                <label for="input-create-designation">职位</label>
                <select class="form-control" id="input-create-designation" name="designation_id"
                  v-model="createForm.designation_id" v-validate="'required'" data-vv-as="职位">
                  <option disabled style="display:none;" value="">- 选择职位 -</option>
                  <option v-for="designation in designations" :value="designation.id" :key="designation.id">
                    {{ designation.name }}
                  </option>
                </select>
                <div class="help-block with-errors">{{ errors.first('createForm.designation_id') }}</div>
              </div>

            </form>
          </div>

          <div class="box-footer text-center">
            <button @click="createMember" class="btn btn-success"><i class="fa fa-fw fa-check"></i> 提交</button>
            <button @click="showCreate = false; errors.clear('createForm')" class="btn btn-default"><i class="fa fa-fw fa-undo"></i> 取消</button>
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
              <button class="btn btn-success btn-sm" @click="showCreate = true; setEdit(null)" :disabled="showCreate"><i class="fa fa-plus"></i> 添加</button>
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
                    <th class="visible-lg">ID</th>
                    <th>部门</th>
                    <th>职位</th>
                    <th class="visible-lg">NetID</th>
                    <th>姓名</th>
                    <th class="hidden-xs">入社时间</th>
                    <th class="hidden-xs" v-show="showDeletedAt">离社时间</th>
                    <th>操作</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="data in results.data" :key="data.id">
                    <td class="visible-lg">{{ data.id }}</td>
                    <td>{{ data.department.name }}</td>
                    <td>{{ data.designation.name }}</td>
                    <td class="visible-lg">{{ data.user.netid }}</td>
                    <td>{{ data.user.profile.name }}</td>
                    <td class="hidden-xs">{{ data.created_at.substring(0,10) }}</td>
                    <td class="hidden-xs" v-show="showDeletedAt">{{ data.deleted_at ? data.deleted_at.substring(0,10) : '' }}</td>
                    <td>
                      <button @click="setEdit(data)" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> 编辑</button>
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
      form: {
        old_members: 0,
        department_id: '',
        designation_id: '',
        id: '',
        netid: '',
        name: '',
        page: 1,
        perpage: 15,
      },
      loading: false,
      results: null,
      editMember: null,
      showCreate: false,
      createForm: {
        department_id: '',
        designation_id: '',
        netid: '',
      },
    }
  },
  computed: {
    ...mapState([
      'departments',
      'designations',
    ]),
    showDeletedAt () {
      return this.form.old_members === null || this.form.old_members === 1
    },
    updateForm () {
      if (this.editMember === null) return null

      return {
        department_id: this.editMember.department_id,
        designation_id: this.editMember.designation_id,
      }
    },
  },
  watch: {
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
      if (this.form.page !== 1) {
        this.form.page = 1
      } else {
        this.fetchData()
      }
    },
    fetchData () {
      this.loading = true
      this.$axios.get(
        '/api/member',
        { params: this.form }
      ).then((response) => {
        this.setEdit(null)
        this.results = response.data.data
        this.loading = false
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
        preConfirm: () => this.$axios.post(
          '/api/member',
          this.createForm
        ),
      }).then(response => {
        return swal({
          title: response.data.status ? '操作失败！' : '操作成功！',
          text: response.data.message,
          type: response.data.status ? 'error' : 'success',
        })
      }).then(() => {
        this.fetchData()
      }).catch(swal.noop)
    },
    deleteMember () {
      swal({
        title: '确认操作',
        text: '是否确认将社员[' + this.editMember.user.profile.name + ']退社？',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.delete(
          `/api/member/${this.editMember.id}`
        ),
      }).then((response) => {
        return swal({
          title: response.data.status ? '操作失败！' : '操作成功！',
          text: response.data.message,
          type: response.data.status ? 'error' : 'success',
        })
      }).then(() => {
        this.fetchData()
      }).catch(swal.noop)
    },
    forceDeleteMember () {
      swal({
        title: '永久删除？',
        text: '是否确认将社员[' + this.editMember.user.profile.name + ']永久删除？',
        type: 'warning',
        showCancelButton: true,
      }).then(() => {
        return swal({
          title: '真的吗？',
          text: '该操作会被记到小本本里！',
          type: 'question',
          showCancelButton: true,
        })
      }).then(() => {
        return swal({
          title: '重要的事情确认三遍！',
          text: '永久删除社员后不可恢复，请谨慎操作！！',
          type: 'warning',
          showCancelButton: true,
          showLoaderOnConfirm: true,
          preConfirm: () => this.$axios.delete(
            `/api/member/${this.editMember.id}/force`
          ),
        })
      }).then((response) => {
        return swal({
          title: response.data.status ? '操作失败！' : '操作成功！',
          text: response.data.message,
          type: response.data.status ? 'error' : 'success',
        })
      }).then(() => {
        this.fetchData()
      }).catch(swal.noop)
    },
    restoreMember () {
      swal({
        title: '确认操作',
        text: '是否确认将社员[' + this.editMember.user.profile.name + ']回社？',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.post(
          `/api/member/${this.editMember.id}/restore`
        ),
      }).then((response) => {
        return swal({
          title: response.data.status ? '操作失败！' : '操作成功！',
          text: response.data.message,
          type: response.data.status ? 'error' : 'success',
        })
      }).then(() => {
        this.fetchData()
      }).catch(swal.noop)
    },
    updateMemberNetid () {
      swal({
        title: '确认操作',
        text: '是否确认要修改社员[' + this.editMember.user.profile.name + ']的NETID？',
        type: 'warning',
        showCancelButton: true,
      }).then(() => {
        return swal({
          title: '请输入目标NETID',
          text: '当前NETID为：' + this.editMember.user.netid,
          type: 'info',
          showCancelButton: true,
          input: 'text',
          inputValidator: value => {
            return new Promise((resolve, reject) => {
              if (value !== '') {
                resolve()
              } else {
                reject(new Error('请输入NETID'))
              }
            })
          },
        })
      }).then(result => {
        return swal({
          title: '重要的事情确认三遍！',
          html: `
                <p>是否确认将社员[${this.editMember.user.profile.name}]的NETID作如下调整：</p>
                <p><span class="text-danger">${this.editMember.user.netid}</span> => <span class="text-danger">${result}</span></p>
                `,
          type: 'warning',
          showCancelButton: true,
          showLoaderOnConfirm: true,
          preConfirm: () => this.$axios.put(
            `/api/member/${this.editMember.id}/netid`,
            { netid: result }
          ),
        })
      }).then(response => {
        return swal({
          title: response.data.status ? '操作失败！' : '操作成功！',
          text: response.data.message,
          type: response.data.status ? 'error' : 'success',
        })
      }).then(() => {
        this.fetchData()
      }).catch(swal.noop)
    },
    async updateMember () {
      let isValid = await this.$validator.validateAll('updateForm')
      if (!isValid) return false
      swal({
        title: '是否确认提交？',
        text: '编辑社员操作会记入操作日志',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.patch(
          `/api/member/${this.editMember.id}`,
          this.updateForm
        ),
      }).then((response) => {
        return swal({
          title: response.data.status ? '操作失败！' : '操作成功！',
          text: response.data.message,
          type: response.data.status ? 'error' : 'success',
        })
      }).then(() => {
        this.fetchData()
      }).catch(swal.noop)
    },
    exportMember () {
      swal({
        title: '确认操作',
        text: '是否确认将导出当前社团通讯录？',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
      }).then((response) => {
        window.open('/api/member/export/excel')
      }).catch(swal.noop)
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
  },
}
</script>
