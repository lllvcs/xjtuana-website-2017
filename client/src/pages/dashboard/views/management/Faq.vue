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
              <router-link :to="{ name: 'management.faq_category' }">
                <button class="btn btn-danger btn-sm"><i class="fa fa-tags"></i> 类别管理</button>
              </router-link>
            </div>

          </div>

          <div class="box-body">
            <form class="row" @submit.prevent="newQuery">
              <div class="form-group col-md-6 col-lg-3">
                <label for="input-search-is_valid">状态</label>
                <select class="form-control" id="input-search-is_valid" name="is_valid" v-model="form.is_valid">
                  <option :value="1">展示</option>
                  <option :value="0">不展示</option>
                </select>
              </div>

              <div class="form-group col-md-6 col-lg-3">
                <label for="input-search-category">FAQ类别</label>
                <select class="form-control" id="input-search-category" name="category_id" v-model="form.category_id">
                  <option value="">- 查看全部 -</option>
                  <template v-if="categories">
                    <option v-for="category in categories" :value="category.id" :key="category.id">
                      {{ category.name }}
                    </option>
                  </template>
                </select>
              </div>

              <div class="form-group col-md-6 col-lg-3">
                <label for="input-search-question">问题</label>
                <input class="form-control" id="input-search-question" name="question" type="text" placeholder="请输入要查询的FAQ问题" v-model="form.question">
              </div>

              <div class="form-group col-md-6 col-lg-3">
                <label for="input-search-answer">答案</label>
                <input class="form-control" id="input-search-answer" name="answer" type="text" placeholder="请输入要查询的FAQ答案" v-model="form.answer">
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

      <!--编辑FAQ开始-->
      <div class="col-xs-12" v-if="editFaq">

        <div class="box box-danger">

          <div class="box-header with-border">
            <i class="fa fa-edit"></i>

            <h3 class="box-title">编辑FAQ</h3>
            <div class="box-tool pull-right">
              <button class="btn btn-danger btn-sm" @click="deleteFaq" v-if="!editFaq.deleted_at"><i class="fa fa-sign-out"></i> 取消展示</button>
              <button class="btn btn-success btn-sm" @click="restoreFaq" v-if="editFaq.deleted_at"><i class="fa fa-sign-in"></i> 恢复展示</button>
              <button class="btn btn-default btn-sm" @click="forceDeleteFaq" v-if="editFaq.deleted_at"><i class="fa fa-trash"></i> 永久删除</button>
            </div>
          </div>

          <div class="box-body">
            <form @submit.prevent="updateFaq" data-vv-scope="updateForm">
              <div class="form-group col-md-6 col-lg-6" :class="{ 'has-error': errors.has('updateForm.category_id') }">
                <label for="input-update-category">FAQ类别</label>
                <select class="form-control" id="input-update-category" name="category_id"
                  v-model="editFaq.category_id" v-validate="'required'" data-vv-as="FAQ类别">
                  <template v-if="categories">
                    <option v-for="category in categories" :value="category.id" :key="category.id">
                      {{ category.name }}
                    </option>
                  </template>
                </select>
                <div class="help-block with-errors">{{ errors.first('updateForm.category_id') }}</div>
              </div>

              <div class="form-group col-md-6 col-lg-6" :class="{ 'has-error': errors.has('updateForm.index') }">
                <label for="input-update-index">展示次序</label>
                <input type="text" class="form-control" id="input-create-index" name="index" placeholder="请输入展示次序"
                  v-model="editFaq.index" v-validate="'required'" data-vv-as="展示次序">
                <div class="help-block with-errors">{{ errors.first('updateForm.index') }}</div>
              </div>

              <div class="form-group col-md-12 col-lg-12" :class="{ 'has-error': errors.has('updateForm.question') }">
                <label for="input-create-question">问题</label>
                <input type="text" class="form-control" id="input-create-question" name="question" placeholder="请输入要添加FAQ的问题"
                  v-model="editFaq.question" v-validate="'required'" data-vv-as="问题">
                <div class="help-block with-errors">{{ errors.first('updateForm.question') }}</div>
              </div>

              <div class="form-group col-md-12 col-lg-12" :class="{ 'has-error': errors.has('updateForm.answer') }">
                <label for="input-create-answer">回答</label>
                <textarea class="form-control" id="input-create-answer" name="answer" placeholder="请输入要添加FAQ的问题" rows="5"
                  v-model="editFaq.answer" v-validate="'required'" data-vv-as="回答"></textarea>
                <div class="help-block with-errors">{{ errors.first('updateForm.answer') }}</div>
              </div>
            </form>
          </div>

          <div class="box-footer text-center">
            <button @click="updateFaq" class="btn btn-success"><i class="fa fa-fw fa-check"></i> 提交</button>
            <button @click="setEdit(null)" class="btn btn-default"><i class="fa fa-fw fa-undo"></i> 取消</button>
          </div>
        </div>
      </div>
      <!--编辑FAQ结束-->

      <!--添加FAQ开始-->
      <div class="col-xs-12" v-show="showCreate">

        <div class="box box-warning">

          <div class="box-header with-border">
            <i class="fa fa-user-plus"></i>

            <h3 class="box-title">添加FAQ</h3>
          </div>

          <div class="box-body">
            <form @submit.prevent="createFaq" data-vv-scope="createForm">

              <div class="form-group col-md-6 col-lg-6" :class="{ 'has-error': errors.has('createForm.category_id') }">
                <label for="input-update-category">FAQ类别</label>
                <select class="form-control" id="input-update-category" name="category_id"
                  v-model="createForm.category_id" v-validate="'required'" data-vv-as="FAQ类别">
                  <option disabled value="" style="display:none;">- 请选择类别 -</option>
                  <template v-if="categories">
                    <option v-for="category in categories" :value="category.id" :key="category.id">
                      {{ category.name }}
                    </option>
                  </template>
                </select>
                <div class="help-block with-errors">{{ errors.first('createForm.category_id') }}</div>
              </div>

              <div class="form-group col-md-6 col-lg-6" :class="{ 'has-error': errors.has('createForm.index') }">
                <label for="input-update-index">展示次序</label>
                <input type="text" class="form-control" id="input-create-index" name="index" placeholder="请输入展示次序"
                  v-model="createForm.index" v-validate="'required|numeric'" data-vv-as="展示次序">
                <div class="help-block with-errors">{{ errors.first('createForm.index') }}</div>
              </div>

              <div class="form-group col-md-12 col-lg-12" :class="{ 'has-error': errors.has('createForm.question') }">
                <label for="input-create-question">问题</label>
                <input type="text" class="form-control" id="input-create-question" name="question" placeholder="请输入要添加FAQ的问题"
                  v-model="createForm.question" v-validate="'required'" data-vv-as="问题">
                <div class="help-block with-errors">{{ errors.first('createForm.question') }}</div>
              </div>

              <div class="form-group col-md-12 col-lg-12" :class="{ 'has-error': errors.has('createForm.answer') }">
                <label for="input-create-answer">回答</label>
                <textarea class="form-control" id="input-create-answer" name="answer" placeholder="请输入要添加FAQ的问题" rows="5"
                  v-model="createForm.answer" v-validate="'required'" data-vv-as="回答"></textarea>
                <div class="help-block with-errors">{{ errors.first('createForm.answer') }}</div>
              </div>

            </form>
          </div>

          <div class="box-footer text-center">
            <button @click="createFaq" class="btn btn-success"><i class="fa fa-fw fa-check"></i> 提交</button>
            <button @click="showCreate = false; errors.clear('createForm')" class="btn btn-default"><i class="fa fa-fw fa-undo"></i> 取消</button>
          </div>
        </div>
      </div>
      <!--添加FAQ结束-->

      <!--FAQ列表开始-->
      <div class="col-xs-12">

        <div class="box box-success">

          <div class="overlay" v-show="loading">
            <i class="fa fa-refresh fa-spin"></i>
          </div>

          <div class="box-header with-border">
            <i class="fa fa-rss"></i>

            <h3 class="box-title">FAQ列表</h3>
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
                    <th>类别</th>
                    <th>次序</th>
                    <th>问题</th>
                    <th class="hidden-xs">回答</th>
                    <th>操作</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="data in results.data" :key="data.id">
                    <td>{{ data.category.name }}</td>
                    <td>{{ data.index }}</td>
                    <td>{{ data.question.length > 20 ?  data.question.substring(0,20) + '...' : data.question }}</td>
                    <td class="hidden-xs">{{ data.answer.length > 20 ?  data.answer.substring(0,20) + '...' : data.answer }}</td>
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
      <!--FAQ列表结束-->

    </div>
    <!-- /.row (main row) -->

  </section>

</div>
</template>

<script>
import VuePagination from 'vue-bs-pagination'
export default {
  components: {
    VuePagination,
  },
  data () {
    return {
      form: {
        is_valid: 1,
        category_id: '',
        question: '',
        answer: '',
        page: 1,
        perpage: 15,
      },
      categories: null,
      loading: false,
      results: null,
      editFaq: null,
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
      if (this.editFaq === null) return null
      return {
        category_id: this.editFaq.category_id,
        index: this.editFaq.index,
        question: this.editFaq.question,
        answer: this.editFaq.answer,
      }
    },
  },
  watch: {
    'form.is_valid': 'newQuery',
    'form.category_id': 'newQuery',
    'form.perpage': 'newQuery',
    'form.page': 'fetchData',
  },
  created () {
    this.fetchCategory()
    this.fetchData()
  },
  methods: {
    reset () {
      this.form = {
        is_valid: 1,
        category_id: '',
        question: '',
        answer: '',
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
        '/api/faq',
        { params: this.form }
      ).then((response) => {
        this.setEdit(null)
        this.results = response.data.data
        this.loading = false
      })
    },
    fetchCategory () {
      this.$axios.get(
        '/api/faq_category'
      ).then((response) => {
        this.categories = response.data.data
      })
    },
    async createFaq () {
      let isValid = await this.$validator.validateAll('createForm')
      if (!isValid) return false
      swal({
        title: '是否确认提交？',
        text: '添加FAQ操作会记入操作日志',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.post(
          '/api/faq',
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
    deleteFaq () {
      swal({
        title: '确认操作',
        text: '是否确认将FAQ[' + this.editFaq.question + ']取消展示？',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.delete(
          `/api/faq/${this.editFaq.id}`
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
    forceDeleteFaq () {
      swal({
        title: '永久删除？',
        text: '是否确认将FAQ[' + this.editFaq.question + ']永久删除？',
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
          text: '永久删除FAQ后不可恢复，请谨慎操作！！',
          type: 'warning',
          showCancelButton: true,
          showLoaderOnConfirm: true,
          preConfirm: () => this.$axios.delete(
            `/api/faq/${this.editFaq.id}/force`
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
    restoreFaq () {
      swal({
        title: '确认操作',
        text: '是否确认将FAQ[' + this.editFaq.question + ']恢复展示？',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.post(
          `/api/faq/${this.editFaq.id}/restore`
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
    async updateFaq () {
      let isValid = await this.$validator.validateAll('updateForm')
      if (!isValid) return false
      swal({
        title: '是否确认提交？',
        text: '编辑FAQ操作会记入操作日志',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.patch(
          `/api/faq/${this.editFaq.id}`,
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
    setEdit (faq) {
      if (faq === null) {
        this.editFaq = null
        this.errors.clear('updateForm')
      } else {
        this.editFaq = JSON.parse(JSON.stringify(faq))
        this.showCreate = false
      }
    },
  },
}
</script>
