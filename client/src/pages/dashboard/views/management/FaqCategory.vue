<template>
<div>

  <section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">

      <!--编辑FAQ类别开始-->
      <div class="col-xs-12" v-if="editFaqCategory">

        <div class="box box-danger">

          <div class="box-header with-border">
            <i class="fa fa-edit"></i>

            <h3 class="box-title">编辑FAQ类别</h3>
            <div class="box-tool pull-right">
              <button class="btn btn-danger btn-sm" @click="deleteFaqCategory"><i class="fa fa-trash"></i> 删除类别</button>
            </div>
          </div>

          <div class="box-body">
            <form @submit.prevent="updateFaqCategory" data-vv-scope="updateForm">

              <div class="form-group col-md-12 col-lg-12" :class="{ 'has-error': errors.has('updateForm.name') }">
                <label for="input-create-name">名称</label>
                <input type="text" class="form-control" id="input-create-name" name="name" placeholder="请输入FAQ类别名称"
                  v-model="editFaqCategory.name" v-validate="'required'" data-vv-as="名称">
                <div class="help-block with-errors">{{ errors.first('updateForm.name') }}</div>
              </div>

            </form>
          </div>

          <div class="box-footer text-center">
            <button @click="updateFaqCategory" class="btn btn-success"><i class="fa fa-fw fa-check"></i> 提交</button>
            <button @click="setEdit(null)" class="btn btn-default"><i class="fa fa-fw fa-undo"></i> 取消</button>
          </div>
        </div>
      </div>
      <!--编辑FAQ类别结束-->

      <!--添加FAQ类别开始-->
      <div class="col-xs-12" v-show="showCreate">

        <div class="box box-warning">

          <div class="box-header with-border">
            <i class="fa fa-user-plus"></i>

            <h3 class="box-title">添加FAQ</h3>
          </div>

          <div class="box-body">
            <form @submit.prevent="createFaqCategory" data-vv-scope="createForm">

              <div class="form-group col-md-12 col-lg-12" :class="{ 'has-error': errors.has('createForm.name') }">
                <label for="input-create-name">名称</label>
                <input type="text" class="form-control" id="input-create-name" name="name" placeholder="请输入要添加FAQ类别的名称"
                  v-model="createForm.name" v-validate="'required'" data-vv-as="名称">
                <div class="help-block with-errors">{{ errors.first('createForm.name') }}</div>
              </div>

            </form>
          </div>

          <div class="box-footer text-center">
            <button @click="createFaqCategory" class="btn btn-success"><i class="fa fa-fw fa-check"></i> 提交</button>
            <button @click="showCreate = false; errors.clear('createForm')" class="btn btn-default"><i class="fa fa-fw fa-undo"></i> 取消</button>
          </div>
        </div>
      </div>
      <!--添加FAQ类别结束-->

      <!--FAQ类别列表开始-->
      <div class="col-xs-12">

        <div class="box box-success">

          <div class="overlay" v-show="loading">
            <i class="fa fa-refresh fa-spin"></i>
          </div>

          <div class="box-header with-border">
            <i class="fa fa-tags"></i>

            <h3 class="box-title">FAQ类别</h3>
            <div class="box-tool pull-right">
              <router-link :to="{ name: 'management.faq' }">
                <button class="btn btn-primary btn-sm"><i class="fa fa-question-circle-o"></i> FAQ管理</button>
              </router-link>
            </div>
          </div>

          <div class="box-body">

            <div v-if="results">

              <table class="table table-striped table-bordered table-hover text-center">
                <thead>
                  <tr>
                    <th width="50px">ID</th>
                    <th>名称</th>
                    <th>操作</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="data in results" :key="data.id">
                    <td>{{ data.id }}</td>
                    <td>{{ data.name }}</td>
                    <td>
                      <button @click="setEdit(data)" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> 编辑</button>
                    </td>
                  </tr>
                </tbody>
              </table>

              <div class="text-left page-total">
                共 {{ results.length }} 条信息
              </div>

            </div>

            <div v-else class="text-center">
              <p>暂无查询结果</p>
            </div>

          </div>
          <div class="box-footer text-center">
            <button class="btn btn-success btn-sm" @click="showCreate = true; setEdit(null)" :disabled="showCreate"><i class="fa fa-plus"></i> 添加类别</button>
          </div>
        </div>
      </div>
      <!--FAQ列表结束-->

    </div>
    <!-- /.row (main row) -->

  </section>

</div>
</template>

<script>
export default {
  data () {
    return {
      loading: false,
      results: null,
      editFaqCategory: null,
      showCreate: false,
      createForm: {
        category_id: '',
        index: '',
        question: '',
        answer: '',
      },
    }
  },
  computed: {
    updateForm () {
      if (this.editFaqCategory === null) return null
      return {
        name: this.editFaqCategory.name,
      }
    },
  },
  created () {
    this.fetchData()
  },
  methods: {
    fetchData () {
      this.loading = true
      this.$axios.get(
        '/api/faq_category'
      ).then((response) => {
        this.setEdit(null)
        this.results = response.data.data
        this.loading = false
      })
    },
    async createFaqCategory () {
      let isValid = await this.$validator.validateAll('createForm')
      if (!isValid) return false
      swal({
        title: '是否确认提交？',
        text: '添加FAQ类别操作会记入操作日志',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.post(
          '/api/faq_category',
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
    deleteFaqCategory () {
      swal({
        title: '确认操作',
        html: '<p>是否确认将FAQ类别[' + this.editFaqCategory.name + ']删除？</p><p class="text-muted">注意：若该类别下还有FAQ，则无法被删除</p>',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.delete(
          `/api/faq_category/${this.editFaqCategory.id}`
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
    async updateFaqCategory () {
      let isValid = await this.$validator.validateAll('updateForm')
      if (!isValid) return false
      swal({
        title: '是否确认提交？',
        text: '编辑FAQ类别操作会记入操作日志',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.patch(
          `/api/faq_category/${this.editFaqCategory.id}`,
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
    /* eslint-disable camelcase */
    setEdit (faq_category) {
      /* eslint-disable camelcase */
      if (faq_category === null) {
        this.editFaqCategory = null
        this.errors.clear('updateForm')
      } else {
        this.editFaqCategory = JSON.parse(JSON.stringify(faq_category))
        this.showCreate = false
      }
    },
  },
}
</script>
