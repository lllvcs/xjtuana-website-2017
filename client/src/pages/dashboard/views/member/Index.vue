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
            <form class="row" @submit.prevent="fetchData">
              <div class="form-group col-md-6 col-lg-3">
                <label for="input-search-old_members">社员状态</label>
                <select class="form-control" id="input-search-old_members" name="old_members" v-model="form.old_members">
                  <option :value="0">现役社员</option>
                  <option :value="1">退役社员</option>
                  <option :value="null">全部</option>
                </select>
              </div>

              <div class="form-group col-md-6 col-lg-3">
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

              <div class="form-group col-md-6 col-lg-3">
                <label for="input-update-designation">职位</label>
                <select class="form-control" id="input-update-designation" name="designation_id" v-model="form.designation_id">
                  <option value="">- 查看全部 -</option>
                  <option
                    v-for="designation in designations"
                    :key="designation.id"
                    :value="designation.id">
                    {{ designation.name }}
                  </option>
                </select>
              </div>

              <div class="form-group col-md-6 col-lg-3">
                <label for="input-search-name">姓名</label>
                <input class="form-control" id="input-search-name" name="name" type="text" placeholder="请输入要查询的社员姓名" v-model="form.name">
              </div>

            </form>
            <button class="btn btn-sm btn-info pull-right" @click="reset"><i class="fa fa-fw fa-undo"></i> 重置</button>
          </div>

          <div class="box-footer text-center">
            <button class="btn btn-primary" @click="newQuery" :disabled="loading"><i class="fa fa-fw fa-search"></i> 查询</button>
          </div>
        </div>
      </div><!--查询条件结束-->

      <div class="col-xs-12"><!--社员列表开始 -->
        <div class="box box-success">
          <div class="overlay" v-show="loading">
            <i class="fa fa-refresh fa-spin"></i>
          </div>

          <div class="box-header with-border">
            <i class="fa fa-users"></i>

            <h3 class="box-title">社员列表</h3>
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
                    <option
                      v-for="i in results.last_page"
                      :key="i"
                      :value="i">
                      {{ i }}
                    </option>
                  </select>
                页</div>
              </div>

              <table class="table table-striped table-bordered table-hover text-center">
                <thead>
                  <tr>
                    <th>部门</th>
                    <th>职位</th>
                    <th>姓名</th>
                    <th class="hidden-xs">手机号</th>
                    <th class="hidden-xs">入社时间</th>
                    <th class="hidden-xs">最近登录</th>
                    <th class="hidden-xs" v-show="showDeletedAt">离社时间</th>
                    <th>操作</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="data in results.data" :key="data.id">
                    <td>{{ data.department.name }}</td>
                    <td>{{ data.designation.name }}</td>
                    <td>{{ data.user.profile.name }}</td>
                    <td class="hidden-xs">{{ data.user.profile.mobile }}</td>
                    <td class="hidden-xs">{{ data.created_at.substring(0,10) }}</td>
                    <td class="hidden-xs">{{ data.user.logged_in_at ? data.user.logged_in_at.substring(0,10) : '' }}</td>
                    <td class="hidden-xs" v-show="showDeletedAt">{{ data.deleted_at ? data.deleted_at.substring(0,10) : '' }}</td>
                    <td>
                      <router-link :to="{ name : 'member.show', params : { id: data.id } }">查看</router-link>
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
      </div><!--社员列表结束-->
    </div><!-- /.row (main row) -->
  </section>
</template>

<script>
import { mapState } from 'vuex'
import VuePagination from 'vue-bs-pagination'
export default {
  name: 'MemberIndex',
  components: {
    VuePagination,
  },
  data () {
    return {
      form: {
        old_members: 0,
        department_id: '',
        designation_id: '',
        name: '',
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
      'designations',
    ]),
    showDeletedAt () {
      return this.form.old_members === null || this.form.old_members === 1
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
      ).then(response => {
        this.results = response.data.data
        this.loading = false
      })
    },
  },
}
</script>
