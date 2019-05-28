<template>
  <div>

    <section class="content">

      <div class="row">

        <!-- 查询开始 -->
        <div class="col-xs-12">

          <div class="box box-primary">

            <div class="box-header with-border">
              <i class="fa fa-search"></i>

              <h3 class="box-title">查询条件</h3>

              <button class="btn btn-sm btn-info pull-right" @click="form.point_filter = !form.point_filter; filter_text = form.point_filter?'显示可买':'显示全部';">
                <i class="fa fa-fw " :class="form.point_filter ? 'fa fa-cube' :'fa fa-cubes'"></i> {{filter_text}}</button>
            </div>

            <div class="box-body">
              <form class="row" @submit.prevent="fetchData">
                <div class="form-group col-md-6 col-lg-3">
                  <label for="input-search-items">物品状态</label>
                  <select class="form-control" name="items" id="input-search-items" v-model="form.items">
                    <option value="">全部</option>
                    <option :value="1">在售</option>
                    <option :value="2">售罄</option>
                  </select>
                </div>

                <div class="form-group col-md-6 col-lg-3">
                  <label class="input-search-price">价格排序</label>
                  <select class="form-control" name="price" id="input-search-price" v-model="form.price">
                    <option value="">--默认--</option>
                    <option :value="0">从高到低</option>
                    <option :value="1">从低到高</option>
                  </select>
                </div>

                <div class="form-group col-md-6 col-lg-3">
                  <label class="input-search-own">拥有者</label>
                  <select class="form-control" name="own" id="input-search-own" v-model="form.own">
                    <option value="">--默认--</option>
                    <option :value="0">系统拥有</option>
                    <option :value="1">个人所有</option>
                  </select>
                </div>

                <div class="form-group col-md-6 col-lg-3">
                  <label class="input-search-time">创建时间</label>
                  <select class="form-control" name="time" id="input-search-time" v-model="form.time">
                    <option value="">--默认--</option>
                    <option :value="0">从新到旧</option>
                    <option :value="1">从旧到新</option>
                  </select>
                </div>

                <div class="form-group col-md-6 col-lg-3">
                  <label for="input-search-name">物品名</label>
                  <input class="form-control" id="input-search-name" name="name" type="text" placeholder="请输入要查询的物品名" v-model="form.name">
                </div>

                <div class="form-group col-md-6 col-lg-3">
                  <label for="input-search-own_name">所有者名</label>
                  <input class="form-control" id="input-search-own_name" name="own_name" type="text" placeholder="请输入要查询的所有者名" v-model="form.own_name">
                </div>

                <div class="form-group col-md-6 col-lg-6">
                  <label for="input-search-details">细节描述</label>
                  <input class="form-control" id="input-search-details" name="details" type="text" placeholder="请输入要查询的细节" v-model="form.details">
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
        <!-- 查询结束 -->

        <!--添加商品开始-->
        <div class="col-xs-12" v-show="showCreate">

          <div class="box box-info">

            <div class="box-header with-border">
              <i class="fa fa-user-plus"></i>

              <h3 class="box-title">添加商品</h3>
            </div>

            <div class="box-body">
              <form @submit.prevent="createItems" data-vv-scope="createForm">

                <div class="form-group col-md-6 col-lg-3" :class="{ 'has-error': errors.has('createForm.name') }">
                  <label for="input-create-name">商品名称</label>
                  <input type="text" class="form-control" id="input-create-name" name="name" placeholder="请输入要添加的商品名" v-model="createForm.name" v-validate="'required'" data-vv-as="商品名">
                  <div class="help-block with-errors">{{ errors.first('createForm.name') }}</div>
                </div>

                <div class="form-group col-md-6 col-lg-3" :class="{ 'has-error': errors.has('createForm.points') }">
                  <label for="input-create-points">需要积分</label>
                  <input class="form-control" id="input-create-points" name="points" type="text" placeholder="请输入需要积分多少" v-validate="'required'" data-vv-as="积分" v-model="createForm.points">
                  <div class="help-block with-errors">{{ errors.first('createForm.points') }}</div>
                </div>

                <div class="form-group col-md-6 col-lg-3" :class="{ 'has-error': errors.has('createForm.number') }">
                  <label for="input-create-number">售卖个数</label>
                  <input class="form-control" id="input-create-number" name="number" type="text" placeholder="请输入售卖个数多少" v-validate="'required'" data-vv-as="售卖个数" v-model="createForm.number">
                  <div class="help-block with-errors">{{ errors.first('createForm.number') }}</div>
                </div>

                <div class="form-group col-md-6 col-lg-3" :class="{ 'has-error': errors.has('createForm.own') }">
                  <label class="input-create-own">拥有者</label>
                  <select class="form-control" name="own" id="input-create-own" v-model="createForm.own" v-validate="'required'" data-vv-as="商品所属">
                    <option :value="user.member.id">个人所有</option>
                    <option v-if="isAdmin" :value="0">系统拥有</option>
                  </select>
                  <div class="help-block with-errors">{{ errors.first('createForm.own') }}</div>
                </div>

                <div class="form-group col-md-6 col-lg-12" :class="{ 'has-error': errors.has('createForm.details') }">
                  <label for="input-create-details">细节描述</label>
                  <input class="form-control" id="input-create-details" name="details" type="text" placeholder="请输入细节描述" v-validate="'required'" data-vv-as="细节描述" v-model="createForm.details">
                  <div class="help-block with-errors">{{ errors.first('createForm.details') }}</div>
                </div>
              </form>
            </div>

            <div class="box-footer text-center">
              <button @click="createItems();" class="btn btn-success">
                <i class="fa fa-fw fa-check"></i> 提交</button>
              <button @click="showCreate = false; setEdit(null);errors.clear('createForm')" class="btn btn-default">
                <i class="fa fa-fw fa-undo"></i> 取消</button>
            </div>
          </div>
        </div>
        <!--添加商品结束-->

        <!-- 修改商品信息开始 -->
        <div class="col-xs-12" v-show="showEdit">

          <div class="box box-warning">

            <div class="box-header with-border">
              <i class="fa fa-user-edit"></i>

              <h3 class="box-title">修改商品</h3>
            </div>

            <div class="box-body">
              <form @submit.prevent="editItems" data-vv-scope="editForm">

                <div class="form-group col-md-6 col-lg-3" :class="{ 'has-error': errors.has('editForm.name') }">
                  <label for="input-edit-name">商品名称</label>
                  <input type="text" class="form-control" id="input-edit-name" name="name" placeholder="请输入要添加的商品名" v-model="editForm.name" v-validate="'required'" data-vv-as="商品名">
                  <div class="help-block with-errors">{{ errors.first('editForm.name') }}</div>
                </div>

                <div class="form-group col-md-6 col-lg-3" :class="{ 'has-error': errors.has('editForm.points') }">
                  <label for="input-create-points">需要积分</label>
                  <input class="form-control" id="input-create-points" name="points" type="text" placeholder="请输入需要积分多少" v-validate="'required'" data-vv-as="积分" v-model="editForm.points">
                  <div class="help-block with-errors">{{ errors.first('editForm.points') }}</div>
                </div>

                <div class="form-group col-md-6 col-lg-3" :class="{ 'has-error': errors.has('editForm.number') }">
                  <label for="input-create-number">售卖个数</label>
                  <input class="form-control" id="input-create-number" name="number" type="text" placeholder="请输入售卖个数多少" v-validate="'required'" data-vv-as="售卖个数" v-model="editForm.number">
                  <div class="help-block with-errors">{{ errors.first('editForm.number') }}</div>
                </div>

                <div class="form-group col-md-6 col-lg-3" :class="{ 'has-error': errors.has('editForm.sold') }">
                  <label for="input-create-sold">已售个数</label>
                  <input class="form-control" id="input-create-sold" name="sold" type="text" placeholder="请输入已售个数多少" v-validate="'required'" data-vv-as="已售个数" v-model="editForm.sold">
                  <div class="help-block with-errors">{{ errors.first('editForm.sold') }}</div>
                </div>

                <div class="form-group col-md-6 col-lg-9" :class="{ 'has-error': errors.has('editForm.details') }">
                  <label for="input-create-details">描述信息</label>
                  <input class="form-control" id="input-create-details" name="details" type="text" placeholder="请输入描述信息" v-validate="'required'" data-vv-as="描述信息" v-model="editForm.details">
                  <div class="help-block with-errors">{{ errors.first('editForm.details') }}</div>
                </div>

                <div class="form-group col-md-6 col-lg-3" :class="{ 'has-error': errors.has('editForm.own') }">
                  <label class="input-create-own">拥有者</label>
                  <select class="form-control" name="own" id="input-create-own" v-model="editForm.own" v-validate="'required'" data-vv-as="商品所属">
                    <option :value="user.member.id">个人所有</option>
                    <option v-if="isAdmin" :value="0">系统拥有</option>
                  </select>
                  <div class="help-block with-errors">{{ errors.first('editForm.own') }}</div>
                </div>
              </form>
            </div>

            <div class="box-footer text-center">
              <button @click="editItems();" class="btn btn-success">
                <i class="fa fa-fw fa-check"></i> 提交</button>
              <button @click="deletItems();" class="btn btn-danger">
                <i class="fa fa-fw fa-close"></i> 删除</button>
              <button @click="showEdit = false; setEditForm(null);errors.clear('editForm')" class="btn btn-default">
                <i class="fa fa-fw fa-undo"></i> 取消</button>
            </div>
          </div>
        </div>
        <!-- 修改商品信息结束 -->

        <!-- 商品列表开始 -->
        <div class="col-xs-12">

          <div class="box box-success">

            <div class="overlay" v-show="loading">
              <i class="fa fa-refresh fa-spin"></i>
            </div>

            <div class="box-header with-border">
              <i class="fa fa-users"></i>

              <h3 class="box-title">商品列表</h3>
              <div class="box-tool pull-right">
                <button class="btn btn-success btn-sm" @click="showCreate = true; setEdit(null);showEdit=false" :disabled="showCreate ">
                  <i class="fa fa-plus"></i> 添加</button>
              </div>
            </div>

            <div class="box-body">

              <div v-if="results && results.data">
                <div class="box-header">
                  <div class="page-info">
                    <div class="text-left page-perpage col-xs-6">每页显示
                      <select v-model="form.perpage">
                        <option value="4">4(推荐手机用户)</option>
                        <option value="8">8</option>
                        <option value="16">16</option>
                        <option value="32">32</option>
                      </select>
                      条</div>
                    <div class="text-right page-current col-xs-6">当前第
                      <select v-model="form.page">
                        <option v-for="i in results.last_page" :value="i" :key="i">{{ i }}</option>
                      </select>
                      页</div>
                  </div>
                </div>

                <div class="box-body">
                  <div class="col-lg-3 col-md-6 col-sm-6" v-for="(data, index) in results.data " :key="data.id">
                    <div class="card card-stats">
                      <div @click="showDetail(index,data)" class="card-header" :style="{backgroundColor: data.sold == data.number ? 'red' : ((data.sold/data.number)<=0.8 && (data.number-data.sold)>=2?'green':'orange')}">
                        <i :class="data.own_id == 0 ? 'fa fa-star' :'fa fa-user'"></i>
                      </div>
                      <div @click="showDetail(index,data)" class="card-content" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        <h3 class="title" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" :title="data.name">{{ data.name }}
                        </h3>
                        <h5 class="category" style="margin-top: 0px;white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{data.points}}分
                          <small>{{data.sold}}/{{data.number}}个</small>
                        </h5>
                        <div class="progress progress-striped active" style="height:8px;margin-bottom: 0px;">
                          <div class="progress-bar" :class=" data.sold == data.number?'progress-bar-danger':((data.sold/data.number)<=0.8 && (data.number-data.sold)>=2?'progress-bar-success':'progress-bar-warning')" role="progressbar" v-bind:aria-valuenow="data.sold" aria-valuemin="0" v-bind:aria-valuemax="data.number" :style="'width:' + (data.sold/data.number)*100 + '%'">
                          </div>
                        </div>
                      </div>
                      <div class="card-footer" style="height: 35px;">
                        <div class="stats" style="width: 100%; height:24px;">

                          <div style="overflow:hidden; white-space:nowrap; text-overflow:ellipsis;padding-top: 2px;padding-bottom: 2px;" :style="((isAdmin || data.own_id == user.member.id)?'width:80%' :'width:100%')">
                            <i :class=" data.sold != data.number?'fa fa-check':'fa fa-close'"></i>
                            已售出{{data.sold}},剩余{{data.number - data.sold}}

                            <b>属于:{{data.own_name}}</b>
                            <div v-if="data.own_id == 0" style="color:red;">
                              <b>系统物品</b>
                            </div>
                          </div>

                          <button v-show="(isAdmin || (toNumber(data.own_id) == user.member.id))" type="button" class="btn btn-xs btn-info pull-right" @click="setEditForm(data);showEdit=true;showCreate = false;">修改</button>
                        </div>

                      </div>
                    </div>

                  </div>
                </div>
                <div class="box-footer">
                  <div :title="'共' + results.total + '条信息'" class="text-left page-total col-xs-2" style="padding-top: 10px;padding-bottom: ‒10;padding-bottom: 10px; white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    共 {{ results.total }} 条信息
                  </div>

                  <div class="text-right page-pagination col-xs-10" style="height: 39px;">
                    <vue-pagination style="margin-top: 3px;margin-bottom: 3px;" v-model="form.page" :total="results.last_page"></vue-pagination>
                  </div>
                </div>
              </div>
              <div v-else class="text-center">
                <p>暂无查询结果</p>
              </div>

            </div>
          </div>

        </div>
        <!-- 商品列表结束 -->
      </div>

    </section>

  </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex'
import { swal } from '@/plugins'
import VuePagination from 'vue-bs-pagination'
export default {
  components: {
    VuePagination,
  },
  computed: {
    ...mapState(['user']),
    ...mapGetters(['isManager', 'isAdmin']),
  },
  data () {
    return {
      filter_text: '显示可买',
      loading: false,
      showCreate: false,
      showEdit: false,
      points: 0,
      form: {
        time: '',
        own_name: '',
        point_filter: false,
        my_points: 0,
        items: '',
        price: '',
        own: '',
        name: '',
        details: '',
        page: 1,
        perpage: 16,
      },
      purchase: {
        id: '',
        name: '',
        own: '',
        purchase: '',
        number: '',
        points: '',
      },
      createForm: {
        name: '',
        own: '',
        points: '',
        number: '',
        details: '',
        own_name: '',
      },
      editForm: {
        id: '',
        name: '',
        own: '',
        own_name: '',
        points: '',
        number: '',
        sold: '',
        details: '',
      },
      results: null,
      data: '',
    }
  },
  watch: {
    'form.items': 'newQuery',
    'form.time': 'newQuery',
    'form.own_name': 'newQuery',
    'form.price': 'newQuery',
    'form.own': 'newQuery',
    'form.name': 'newQuery',
    'form.details': 'newQuery',
    'form.point_filter': 'newQuery',
    'form.perpage': 'fetchData',
    'form.page': 'fetchData',
  },
  created () {
    this.fetchData()
  },
  updated () {
    if (this.user !== null && this.user.member !== null) {
      this.points = this.user.member.points !== null ? this.user.member.points.points : 0
    }
  },
  methods: {
    toNumber (data) {
      return parseInt(data)
    },
    async showDetail (index, data) {
      this.showCreate = false
      this.showEdit = false
      var purchase = {
        id: data.id,
        name: data.name,
        own: data.own_id,
        purchase: this.user.member.id,
        number: '',
        points: data.points,
      }
      var $axios = this.$axios
      var points = this.points
      var buyer = this.user.member.id
      swal({
        title: '<h3>详细信息</h3>',

        html:
          '<small>你想购买这个物品</small>' +
          '<b style="color:green;">' +
          data.name +
          '</b>' +
          '<small>吗</small>' +
          '<p></p>' +
          '<small>它原来的主人说:</small>' +
          '<b style="color:green;">' +
          data.details +
          '</b><p></p> 购买个数',

        input: 'number',
        type: 'success',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '确定购买',
        cancelButtonText: '取消购买',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: false,
        preConfirm: function (number) {
          return new Promise(function (resolve, reject) {
            if (points !== 'null') {
              if (number <= data.number - data.sold) {
                if (parseInt(data.own_id) === buyer) {
                  reject(Error('不能自己买自己的东西'))
                } else {
                  purchase.number = number
                  $axios
                    .post(`/api/shop/${data.id}`, purchase)
                    .then(response => {
                      swal({
                        title: response.data.status ? '购买失败' : '购买成功',
                        text: response.data.message,
                        type: response.data.status ? 'error' : 'success',
                      })
                    })

                  resolve()
                }
              } else {
                reject(Error('数目超过可购买数目'))
              }
            } else {
              reject(Error('有积分才能购买'))
            }
          })
        },
      })
        .then(
          function () {},
          function (dismiss) {
            if (dismiss === 'cancel') {
              swal('已取消购买', '你会后悔的:)', 'error')
            }
          }
        )
        .then(() => {
          this.fetchData()
        })
        .catch(swal.noop)
    },
    setBuy (data) {},
    reset () {
      this.filter_text = '显示可买'
      this.form = {
        point_filter: false,
        my_points: 0,
        items: '',
        price: '',
        own: '',
        name: '',
        details: '',
        page: 4,
        perpage: 16,
      }
    },

    async fetchData () {
      this.showCreate = false
      this.showEdit = false
      this.loading = true
      if (this.form.point_filter) {
        this.form.point_filter = 1
      } else {
        this.form.point_filter = 0
      }
      this.form.my_points = this.points
      this.$axios
        .get('/api/shop', {
          params: this.form,
        })
        .then(response => {
          this.results = response.data.data
          this.loading = false
        })
    },
    async deletItems () {
      swal({
        title: '是否删除物品？',
        text: '是否删除，删除后不可恢复',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.delete(`/api/shop/${this.editForm.id}`),
      })
        .then(response => {
          return swal({
            title: response.data.status ? '删除失败！' : '删除成功！',
            text: response.data.message,
            type: response.data.status ? 'error' : 'success',
          })
        })
        .then(() => {
          // this.setEditForm(null)
          this.showEdit = false
          this.fetchData()
        })
        .catch(swal.noop)
    },
    async editItems () {
      let isValid = await this.$validator.validateAll()
      if (!isValid) return false
      swal({
        title: '是否修改物品？',
        text: '修改物品后可以随时修改，或者删除自己的物品',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () =>
          this.$axios.patch(`/api/shop/${this.editForm.id}`, this.editForm),
      })
        .then(response => {
          return swal({
            title: response.data.status ? '修改失败！' : '修改成功！',
            text: response.data.message,
            type: response.data.status ? 'error' : 'success',
          })
        })
        .then(() => {
          // this.setEditForm(null)
          this.showEdit = false
          this.fetchData()
        })
        .catch(swal.noop)
    },
    async newQuery () {
      if (this.form.page !== 1) {
        this.form.page = 1
      } else {
        this.fetchData()
      }
    },
    setEdit (data) {
      if (data === null) {
        this.createForm = {
          name: '',
          own: this.user.member.id,
          own_name: this.user.profile.name,
          points: '',
          details: '',
          number: '',
        }
      }
    },
    setEditForm (data) {
      if (data === null) {
        this.editForm = {
          id: '',
          name: '',
          own: this.user.member.id,
          own_name: this.user.profile.name,
          points: '',
          number: '',
          details: '',
          sold: '',
        }
      } else {
        this.editForm = {
          id: data.id,
          name: data.name,
          own: this.user.member.id,
          own_name: this.user.profile.name,
          points: data.points,
          number: data.number,
          details: data.details,
          sold: data.sold,
        }
      }
    },
    async createItems () {
      let isValid = await this.$validator.validateAll()
      if (!isValid) return false
      swal({
        title: '是否添加物品？',
        text: '添加物品后可以随时修改，或者删除自己的',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.post(`/api/shop`, this.createForm),
      })
        .then(response => {
          return swal({
            title: response.data.status ? '添加失败！' : '添加成功！',
            text: response.data.message,
            type: response.data.status ? 'error' : 'success',
          })
        })
        .then(() => {
          this.setEdit(null)
          this.showCreate = false
          this.fetchData()
        })
        .catch(swal.noop)
    },
  },
}
</script>
