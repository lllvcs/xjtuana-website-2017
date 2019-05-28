<template>
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <i class="fa fa-search"></i>

            <h3 class="box-title">查询条件</h3>
          </div>

          <div class="box-body">
            <form class="row" @submit.prevent="fetchData">
              <div class="form-group col-xs-12">
                <label for="input-type">查询方式</label>
                <div style="padding:10px">
                  <label class="radio-inline">
                    <input type="radio" name="type" id="input-type-1" value="netid" v-model="form.type"> NETID
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="type" id="input-type-2" value="userno" v-model="form.type"> 学工号
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="type" id="input-type-3" value="name" v-model="form.type"> 姓名
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="type" id="input-type-4" value="mobile" v-model="form.type"> 手机号
                  </label>
                </div>
              </div>

              <div class="form-group col-xs-12" :class="{ 'has-error': errors.has('condition') }">
                <label for="input-condition">查询条件</label>
                <input class="form-control" id="input-condition" name="condition" type="text" placeholder="请输入查询条件"
                  v-model="form.condition" v-validate="'required'" data-vv-as="查询条件">
                <div class="help-block with-errors">{{ errors.first('condition') }}</div>
              </div>
            </form>
            <button class="btn btn-info btn-sm pull-right" @click="reset"><i class="fa fa-fw fa-undo"></i> 重置</button>
          </div>

          <div class="box-footer text-center">
            <button class="btn btn-primary" @click="fetchData" :disabled="loading">
              <i class="fa fa-fw fa-search" v-show="!loading"></i><i class="fa fa-refresh fa-spin" v-show="loading"></i> 查询<span v-show="loading">中...</span>
            </button>

            <div v-show="!loading && results && results.length === 0">
              <hr>
              <p class="text-danger">没有查询到相关信息</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row" v-if="displayResult">
      <div class="col-md-8 col-lg-9">
        <div class="box box-success">
          <div class="overlay" v-show="loading">
            <i class="fa fa-refresh fa-spin"></i>
          </div>

          <div class="box-header with-border">
            <i class="fa fa-vcard-o"></i>

            <h3 class="box-title">学生信息 </h3>
          </div>

          <div class="box-body">
            <div class="row page-info">
              <div class="text-left page-perpage col-xs-6">共查询到 {{ results.length }} 条信息</div>
              <div class="text-right page-current col-xs-6">当前第
                <select v-model="page">
                  <option v-for="i in results.length" :value="i" :key="i">{{ i }}</option>
                </select>
              条</div>
            </div>

            <table v-if="displayResult" class="table table-striped table-bordered table-hover text-center">
              <tbody>
                <tr>
                  <th>NETID</th>
                  <td>{{ displayResult.userid }}</td>
                  <th>学号</th>
                  <td>{{ displayResult.userno }}</td>
                </tr>
                <tr>
                  <th>姓名</th>
                  <td>{{ displayResult.username }}</td>
                  <th>性别</th>
                  <td>{{ displayResult.gender }}</td>
                </tr>
                <tr>
                  <th>专业</th>
                  <td>{{ displayResult.speciality }}</td>
                  <th>班级</th>
                  <td>{{ displayResult.classid }}</td>
                </tr>
                <tr>
                  <th>学院/部门</th>
                  <td>{{ displayResult.dep }}</td>
                  <th>类别</th>
                  <td>{{ displayResult.usertype == 1 ? '学生' : '教工' }}</td>
                </tr>
                <tr>
                  <th>学生类型</th>
                  <td>{{ displayResult.studenttype }}</td>
                  <th>导师</th>
                  <td>{{ displayResult.tutoremployeeid }}</td>
                </tr>
                <tr>
                  <th>宿舍</th>
                  <td>{{ displayResult.roomnumber }}</td>
                  <th>民族</th>
                  <td>{{ displayResult.nation }}</td>
                </tr>
                <tr>
                  <th>手机</th>
                  <td>{{ displayResult.mobile }}</td>
                  <th>邮箱</th>
                  <td>{{ displayResult.email }}</td>
                </tr>
                <tr>
                  <th>家乡</th>
                  <td>{{ displayResult.nationplace }}</td>
                  <th>生日</th>
                  <td>{{ displayResult.birthday }}</td>
                </tr>
                <tr>
                  <th>政治面貌</th>
                  <td>{{ displayResult.polity }}</td>
                  <th>婚否</th>
                  <td>{{ displayResult.marriage }}</td>
                </tr>
              </tbody>
            </table>

            <div class="row page-info text-center">
              <vue-pagination v-model="page" :total="results.length" :each-side="2"></vue-pagination>
            </div>

          </div>
        </div>
      </div>

      <div class="col-md-4 col-lg-3"><!--用户照片开始-->
        <div class="box box-danger">
          <div class="overlay" v-show="loadingPhoto">
            <i class="fa fa-refresh fa-spin"></i>
          </div>

          <div class="box-header with-border">
            <i class="fa fa-photo"></i>

            <h3 class="box-title">用户照片</h3>
          </div>

          <div class="box-body" v-if="displayResult.photo">
            <img v-if=" displayResult.photo.length !== 0 " :src="'data:image/png;base64,' + displayResult.photo" class="img-responsive center-block"/>
            <p v-else>没有照片信息</p>

          </div>
          <div class="box-footer">
            <button @click="fetchPhoto" class="btn btn-success" :disabled="loadingPhoto || displayResult.photo"><i class="fa fa-photo"></i> 查看照片</button>
          </div>
        </div>
      </div><!--用户照片结束-->
    </div><!-- /.row (main row) -->
  </section>
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
        type: 'netid',
        condition: '',
      },
      loading: false,
      results: null,
      loadingPhoto: false,
      photo: null,
      page: 1,
    }
  },
  computed: {
    displayIndex () {
      return this.page - 1
    },
    displayResult () {
      if (this.results === null || this.results.length === 0) return null
      return this.results[this.displayIndex]
    },
  },
  methods: {
    reset () {
      this.form.condition = ''
      this.$nextTick(() => {
        this.errors.clear()
      })
    },
    async fetchData () {
      try {
        let isValid = await this.$validator.validateAll()
        if (!isValid) return false
        this.loading = true
        let response = await this.$axios.get('/api/tool/userinfo', {
          params: this.form,
        })
        this.results = response.data.data
        this.page = 1
      } catch (e) {
        throw e
      } finally {
        this.loading = false
      }
    },
    async fetchPhoto () {
      try {
        let index = this.displayIndex
        this.loadingPhoto = true
        let response = await this.$axios.get('/api/tool/userphoto', {
          params: {
            condition: this.displayResult.userno,
          },
        })
        this.results[index].photo = response.data.data
      } catch (e) {
        throw e
      } finally {
        this.loadingPhoto = false
      }
    },
  },
}
</script>

<style lang="scss" type="text/css" scoped>
.page-info {
  padding: 10px;
}
</style>
