<template>
  <div>
    <section class="content">
      <div class="row">

        <!-- 订单列表开始 -->
        <div class="col-xs-12" v-show="1">

          <div class="box box-info">

            <div class="overlay" v-show="loading">
              <i class="fa fa-refresh fa-spin"></i>
            </div>

            <div class="box-header with-border">
              <i class="fa fa-users"></i>

              <h3 class="box-title">订单列表</h3>

            </div>

            <div class="box-body">
              <div class="col-lg-12 col-md-12">
                <div class="card">
                  <div class="card-header" data-background-color="blue">
                    <button data-background-color="blue" class="btn btn-sm btn-info pull-right" @click="ShopReset()">
                      <i class="fa fa-fw fa-times"></i>
                    </button>
                    <button data-background-color="blue" class="btn btn-sm btn-info pull-right" @click="fetchShoping()">
                      <i class="fa fa-fw fa-undo"></i>
                    </button>
                    <h4 class="title">进行中的购买</h4>
                    <p class="category">我未完成支付的订单</p>

                  </div>
                  <div class="card-content table-responsive" v-if="!shopping && results && results.data">
                    <table class="table table-hover text-center">
                      <thead class="text-warning">
                        <th :class="edit?'col-xs-2':'col-xs-2'">卖家</th>
                        <th :class="edit?'col-xs-2':'col-xs-2'">商品</th>
                        <th :class="edit?'col-xs-2':'col-xs-2'">价格</th>
                        <th :class="edit?'col-xs-2':'col-xs-2'" v-show="edit">数量</th>
                        <th :class="edit?'col-xs-2':'col-xs-2'" v-show="edit">买家</th>
                        <th :class="edit?'col-xs-2':'col-xs-2'">操作</th>
                      </thead>
                      <tbody>
                        <tr v-for="data in results.data" :key="data.id">
                          <td :class="edit?'col-xs-2':'col-xs-2'">{{data.seller_id==0?'系统物品':data.sell_member.user.profile.name}}</td>
                          <td :class="edit?'col-xs-2':'col-xs-2'">{{data.name}}</td>
                          <td :class="edit?'col-xs-2':'col-xs-2'">{{data.points}}</td>
                          <td :class="edit?'col-xs-2':'col-xs-2'" v-show="edit">{{data.number}}</td>
                          <td :class="edit?'col-xs-2':'col-xs-2'" v-show="edit">{{data.buy_member.user.profile.name}}</td>
                          <td>
                            <button @click="payItems(data.id)" class="btn btn-xs btn-primary">
                              <i class="fa fa-cart-arrow-down"></i> 支付</button>
                            <button @click="payCancel(data.id)" class="btn btn-xs btn-danger">
                              <i class="fa fa-fire" aria-hidden="true"></i> 取消</button>
                          </td>
                        </tr>
                      </tbody>
                    </table>

                    <div class="box-footer">
                      <div :title="'共' + results.total + '条信息'" class="text-left page-total col-xs-2" style="padding-top: 10px;padding-bottom: ‒10;padding-bottom: 10px; white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        共 {{ results.total }} 条信息
                      </div>

                      <div class="text-right page-pagination col-xs-10" style="height: 39px;">
                        <vue-pagination style="margin-top: 3px;margin-bottom: 3px;" v-model="form.page" :total="results.last_page"></vue-pagination>
                      </div>
                    </div>
                  </div>

                  <div @click="fetchShoping()" v-else class="card-content table-responsive">
                    <i class="fa fa-bars pull-right"></i>
                  </div>

                </div>
              </div>
              <div class="col-lg-12 col-md-12">
                <div class="card">
                  <div class="card-header" data-background-color="purple">
                    <button data-background-color="purple" class="btn btn-sm btn-inverse pull-right" @click="ShopReset()">
                      <i class="fa fa-fw fa-times"></i>
                    </button>
                    <button data-background-color="purple" class="btn btn-sm btn-inverse pull-right" @click="fetchMyShoping()">
                      <i class="fa fa-fw fa-undo"></i>
                    </button>

                    <h4 class="title">我售卖的物品</h4>
                    <p class="category">买家未完成支付的订单</p>
                  </div>
                  <div class="card-content table-responsive" v-if="(!my_items) && results && results.data">
                    <table class="table table-hover text-center">
                      <thead class="text-warning">
                        <th :class="edit?'col-xs-2':'col-xs-2'">买家</th>
                        <th :class="edit?'col-xs-2':'col-xs-2'">商品</th>
                        <th :class="edit?'col-xs-2':'col-xs-2'">价格</th>
                        <th :class="edit?'col-xs-2':'col-xs-2'" v-show="edit">数量</th>
                        <th :class="edit?'col-xs-2':'col-xs-2'" v-show="edit">卖家</th>
                        <th :class="edit?'col-xs-2':'col-xs-2'">操作</th>
                      </thead>
                      <tbody>
                        <tr v-for="data in results.data" :key="data.id">
                          <td :class="edit?'col-xs-2':'col-xs-2'">{{data.buy_member.user.profile.name}}</td>
                          <td :class="edit?'col-xs-2':'col-xs-2'">{{data.name}}</td>
                          <td :class="edit?'col-xs-2':'col-xs-2'">{{data.points}}</td>
                          <td :class="edit?'col-xs-2':'col-xs-2'" v-show="edit">{{data.number}}</td>
                          <td :class="edit?'col-xs-2':'col-xs-2'" v-show="edit">{{data.seller_id==0?'系统物品':data.sell_member.user.profile.name}}</td>
                          <td>
                            <button v-show="data.buying_state != 1" @click="sellItems(data.id)" class="btn btn-xs btn-primary">
                              <i class="fa fa-edit"></i>确认</button>
                            <button @click="sellCancel(data.id)" class="btn btn-xs btn-danger">
                              <i class="fa fa-fire" aria-hidden="true"></i>关闭</button>
                          </td>
                        </tr>
                      </tbody>
                    </table>

                    <div class="box-footer">
                      <div :title="'共' + results.total + '条信息'" class="text-left page-total col-xs-2" style="padding-top: 10px;padding-bottom: ‒10;padding-bottom: 10px; white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        共 {{ results.total }} 条信息
                      </div>

                      <div class="text-right page-pagination col-xs-10" style="height: 39px;">
                        <vue-pagination style="margin-top: 3px;margin-bottom: 3px;" v-model="form.page" :total="results.last_page"></vue-pagination>
                      </div>
                    </div>

                  </div>
                  <div @click="fetchMyShoping()" v-else class="card-content table-responsive">
                    <i class="fa fa-bars pull-right"></i>
                  </div>

                </div>
              </div>
              <div class="col-lg-12 col-md-12">
                <div class="card">
                  <div class="card-header" data-background-color="green">
                    <button data-background-color="green" class="btn btn-sm btn-success pull-right" @click="ShopReset()">
                      <i class="fa fa-fw fa-times"></i>
                    </button>
                    <button data-background-color="green" class="btn btn-sm btn-success pull-right" @click="fetchShoped()">
                      <i class="fa fa-fw fa-undo"></i>
                    </button>
                    <h4 class="title">已完成的购买</h4>
                    <p class="category">已完成支付的历史记录</p>
                  </div>
                  <div class="card-content table-responsive" v-if="(!shopped) && results && results.data">
                    <table class="table table-hover text-center">
                      <thead class="text-warning">
                        <th :class="edit?'col-xs-1':'col-xs-1'">买家</th>
                        <th :class="edit?'col-xs-1':'col-xs-1'">商品</th>
                        <th :class="edit?'col-xs-1':'col-xs-1'">价格</th>
                        <th :class="edit?'col-xs-1':'col-xs-1'" v-show="edit">数量</th>
                        <th :class="edit?'col-xs-1':'col-xs-1'">卖家</th>
                        <th :class="edit?'col-xs-3':'col-xs-3'">操作</th>
                      </thead>
                      <tbody>
                        <tr v-for="data in results.data" :key="data.id" :class="{ 'text-muted': data.buying_state === 2 && data.purchase_id === user.member.id  , 'text-danger': data.buying_state === 4 && data.seller_id === user.member.id , 'text-warning': data.buying_state === 4 && data.purchase_id === user.member.id, 'text-info': data.buying_state === 3 && data.purchase_id === user.member.id, 'text-primary': data.buying_state === 3 && data.seller_id === user.member.id, 'text-success': data.buying_state === 5 }">
                          <td :class="edit?'col-xs-1':'col-xs-1'">{{data.buy_member.user.profile.name}}</td>
                          <td :class="edit?'col-xs-1':'col-xs-1'">{{data.name}}</td>
                          <td :class="edit?'col-xs-1':'col-xs-1'">{{data.points}}</td>
                          <td :class="edit?'col-xs-1':'col-xs-1'" v-show="edit">{{data.number}}</td>
                          <td :class="edit?'col-xs-1':'col-xs-1'">{{data.seller_id==0?'系统物品':data.sell_member.user.profile.name}}</td>
                          <td :class="edit?'col-xs-3':'col-xs-3'">
                            <button v-show="canOperate(data) && data.buying_state !== 5 && canAgreeBack(data) " @click="data.buying_state === 3?payBack(data.id):(data.buying_state === 4?agreeBack(data.id):'取消退款')" class="btn btn-xs btn-primary">
                              <i class="fa fa-edit"></i>{{shoped_edit_text}}</button>
                            <button v-show="canOperate(data)" @click="data.buying_state === 4 ? cancelBack(data.id) :deleteList(data.id)" class="btn btn-xs btn-danger">
                              <i class="fa fa-fire" aria-hidden="true"></i>{{shoped_del_text}}</button>
                            <div v-show="!canOperate(data)">{{shoped_text}}</div>
                          </td>
                        </tr>
                      </tbody>
                    </table>

                    <div class="box-footer">
                      <div :title="'共' + results.total + '条信息'" class="text-left page-total col-xs-2" style="padding-top: 10px;padding-bottom: ‒10;padding-bottom: 10px; white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        共 {{ results.total }} 条信息
                      </div>

                      <div class="text-right page-pagination col-xs-10" style="height: 39px;">
                        <vue-pagination style="margin-top: 3px;margin-bottom: 3px;" v-model="form.page" :total="results.last_page"></vue-pagination>
                      </div>
                    </div>

                  </div>
                  <div @click="fetchShoped()" v-else class="card-content table-responsive">
                    <i class="fa fa-bars pull-right"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- 订单列表结束 -->

      </div>
    </section>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import VuePagination from 'vue-bs-pagination'
import { swal } from '@/plugins'
export default {
  name: 'MyItemsIndex',
  components: {
    VuePagination,
  },
  data () {
    return {
      shoped_edit_text: '退款',
      shoped_del_text: '删除',
      shoped_text: '无法操作',
      edit: false,
      loading: false,
      my_items: true,
      shopping: true,
      shopped: true,
      ing_results: null,
      ed_results: null,
      my_results: null,
      results: null,
      data: '',
      form: {
        id: '',
        purchase_id: '',
        name: '',
        buying_state: '',
        seller: '',
        buying_number: '',
        point: '',
        page: 1,
        perpage: 15,
      },
    }
  },
  computed: {
    ...mapState(['user']),
  },
  watch: {
    'form.page': 'fetchData',
  },
  created () {},
  methods: {
    canAgreeBack (data) {
      if (data.buying_state === 4) { return (data.seller_id === this.user.member.id) } else { return 1 }
    },
    canDelete (data) {
      return (data.buying_state === 5 && data.purchase_id === this.user.member.id)
    },
    canBack (data) {
      return (data.buying_state === 3 && data.purchase_id === this.user.member.id)
    },
    canCancelBack (data) {
      return (data.buying_state === 4)
    },
    canOperate (data) {
      var flag = this.edit || this.canDelete(data) || this.canBack(data) || this.canCancelBack(data)
      if (flag === false) {
        this.shoped_text = data.shop_status.status
      } else {
        this.shoped_edit_text = data.buying_state === 4 ? '确认退款' : '申请退款'
        this.shoped_del_text = data.buying_state === 4 ? '取消退款' : '删除订单'
      }
      return flag
    },
    deleteList (id) {
      swal({
        title: '是否删除记录？',
        text: '删除记录后只有管理员可以恢复',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.delete(`/api/shop_list/del/${id}`),
      })
        .then(response => {
          return swal({
            title: response.data.status ? '删除失败！' : '删除成功！',
            text: response.data.message,
            type: response.data.status ? 'error' : 'success',
          })
        })
        .then(() => {
          this.fetchShoped()
        })
        .catch(swal.noop)
    },
    agreeBack (id) {
      swal({
        title: '是否确认申请退款？',
        text: '确认申请退款后可以只能管理员撤回',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.post(`/api/shop_list/back_agree/${id}`),
      })
        .then(response => {
          return swal({
            title: response.data.status ? '退款失败！' : '退款成功！',
            text: response.data.message,
            type: response.data.status ? 'error' : 'success',
          })
        })
        .then(() => {
          this.fetchShoped()
        })
        .catch(swal.noop)
    },
    cancelBack (id) {
      swal({
        title: '是否取消申请退款？',
        text: '取消申请退款后可以重新申请',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.delete(`/api/shop_list/back/${id}`),
      })
        .then(response => {
          return swal({
            title: response.data.status ? '退款失败！' : '退款成功！',
            text: response.data.message,
            type: response.data.status ? 'error' : 'success',
          })
        })
        .then(() => {
          this.fetchShoped()
        })
        .catch(swal.noop)
    },
    payBack (id) {
      swal({
        title: '是否申请退款？',
        text: '申请退款后可以取消',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.post(`/api/shop_list/back/${id}`),
      })
        .then(response => {
          return swal({
            title: response.data.status ? '退款失败！' : '退款成功！',
            text: response.data.message,
            type: response.data.status ? 'error' : 'success',
          })
        })
        .then(() => {
          this.fetchShoped()
        })
        .catch(swal.noop)
    },
    payItems (id) {
      swal({
        title: '是否购买？',
        text: '购买了物品后只有管理员和卖家能取消，请谨慎购买',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.post(`/api/shop_list/buy/${id}`),
      })
        .then(response => {
          return swal({
            title: response.data.status ? '购买失败！' : '购买成功！',
            text: response.data.message,
            type: response.data.status ? 'error' : 'success',
          })
        })
        .then(() => {
          this.fetchShoping()
        })
        .catch(swal.noop)
    },
    sellItems (id) {
      swal({
        title: '是否出售？',
        text: '购买了物品后只有管理员和买家能取消，请谨慎出售',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.post(`/api/shop_list/sell/${id}`),
      })
        .then(response => {
          return swal({
            title: response.data.status ? '出售失败！' : '出售成功！',
            text: response.data.message,
            type: response.data.status ? 'error' : 'success',
          })
        })
        .then(() => {
          this.fetchMyShoping()
        })
        .catch(swal.noop)
    },
    payCancel (id) {
      swal({
        title: '是否取消？',
        text: '取消了之后要重新购买',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.delete(`/api/shop_list/buy/${id}`),
      })
        .then(response => {
          return swal({
            title: response.data.status ? '取消失败！' : '取消成功！',
            text: response.data.message,
            type: response.data.status ? 'error' : 'success',
          })
        })
        .then(() => {
          this.fetchShoping()
        })
        .catch(swal.noop)
    },
    sellCancel (id) {
      swal({
        title: '是否取消？',
        text: '取消了之后要重新购买',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.delete(`/api/shop_list/sell/${id}`),
      })
        .then(response => {
          return swal({
            title: response.data.status ? '取消失败！' : '取消成功！',
            text: response.data.message,
            type: response.data.status ? 'error' : 'success',
          })
        })
        .then(() => {
          this.fetchMyShoping()
        })
        .catch(swal.noop)
    },
    ShopReset () {
      this.shopped = true
      this.my_items = true
      this.shopping = true
      this.form = {
        id: '',
        purchase_id: '',
        name: '',
        buying_state: '',
        seller: '',
        seller_id: '',
        buying_number: '',
        point: '',
        page: 1,
        perpage: 15,
      }
    },
    fetchMyShoping () {
      this.ShopReset()
      this.form.buying_state = 2
      this.results = null
      this.my_items = true
      if (!this.edit) {
        this.form.seller_id = this.user.member.id
      }
      this.fetchData()
      this.my_items = false
    },
    fetchShoped () {
      this.ShopReset()
      this.form.buying_state = 3
      this.results = null
      this.shopped = true
      if (!this.edit) {
        this.form.seller_id = this.user.member.id
        this.form.purchase_id = this.user.member.id
      }
      this.fetchData()
      this.shopped = false
    },
    fetchShoping () {
      this.ShopReset()
      this.results = null
      if (!this.edit) {
        this.form.purchase_id = this.user.member.id
      }
      this.form.buying_state = 1
      this.shopping = true
      this.fetchData()
      this.shopping = false
    },
    fetchData () {
      this.$axios
        .get('/api/shop_list', {
          params: this.form,
        })
        .then(response => {
          this.results = response.data.data
        })
    },
  },
}
</script>
