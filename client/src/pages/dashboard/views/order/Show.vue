<template>
<div>
  <section class="content" v-if="loading">加载中...</section>
  <section class="content" v-else-if="order">
    <div class="row">
      <!--左侧col开始-->
      <div class="col-md-8 col-lg-9">
        <!--填写维修报告开始-->
        <div v-if="canReport && showReport" class="box box-info">

          <div class="box-header with-border">
            <i class="fa fa-flag-o"></i>

            <h3 class="box-title">填写维修报告</h3>

          </div>

          <div class="box-body">
            <form id="feedbackForm" autocomplete="off">
              <div class="form-group" :class="{ 'has-error': errors.has('score') }">
                <label for="input-score">报修人评分 <span class="text-muted">（仅供内部查看）</span></label>
                <div>
                  <label class="radio-inline">
                    <input type="radio" name="score" value="1"
                      v-model="reportForm.score" v-validate="'required'" data-vv-as="报修人评分"> 差评
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="score" value="2"
                      v-model="reportForm.score" v-validate="'required'" data-vv-as="报修人评分"> 不太好
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="score" value="3"
                      v-model="reportForm.score" v-validate="'required'" data-vv-as="报修人评分"> 一般般
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="score" value="4"
                      v-model="reportForm.score" v-validate="'required'" data-vv-as="报修人评分"> 还不错
                  </label>
                  <label class="radio-inline">
                    <input type="radio" name="score" value="5"
                      v-model="reportForm.score" v-validate="'required'" data-vv-as="报修人评分"> 好评
                  </label>
                </div>
                <div class="help-block with-errors">{{ errors.first('score') }}</div>
              </div>

              <div class="form-group" :class="{ 'has-error': errors.has('service_id') }">
                <label for="input-service_id">处理方式</label>
                <select class="form-control" id="input-service_id" name="service_id"
                  v-model="reportForm.service_id" v-validate="'required'" data-vv-as="处理方式">
                  <option disable value="" style="display:none">- 选择处理方式 -</option>
                  <option v-for="service in this.$store.state.order_services" :value="service.id" :key="service.id">
                    {{ service.name }}
                  </option>
                </select>
                <div class="help-block with-errors">{{ errors.first('service_id') }}</div>
              </div>

              <div class="form-group" :class="{ 'has-error': errors.has('detail') }">
                <label for="input-detail">报告详情</label>
                <textarea class="form-control" id="input-detail" name="detail" placeholder="请输入维修报告详细内容" rows=3
                  v-model="reportForm.detail" v-validate="'required'" data-vv-as="报告详情">
                </textarea>
                <div class="help-block with-errors">{{ errors.first('detail') }}</div>
              </div>
            </form>

          </div>

          <div class="box-footer text-center">
            <button
              class="btn btn-success"
              @click="reportOrder">
              <i class="fa fa-fw fa-flag-o"></i> 提交报告
            </button>

            <button
              class="btn btn-default"
              @click="showReport = false">
              <i class="fa fa-fw fa-undo"></i> 取消
            </button>
          </div>

        </div>
        <!--填写维修报告结束-->

        <!--报修单状态开始-->
        <div class="box box-warning">

          <div class="overlay" v-show="loading">
            <i class="fa fa-refresh fa-spin"></i>
          </div>

          <div class="box-header with-border">
            <i class="fa fa-compass"></i>

            <h3 class="box-title">报修单状态</h3>

          </div>

          <div class="box-body">
            <table class="table table-striped table-hover text-center">
              <tbody>
                <tr>
                  <th>报修单号</th>
                  <td>{{ order.id }}</td>
                </tr>
                <tr>
                  <th>报修时间</th>
                  <td>{{ order.created_at }}</td>
                </tr>
                <tr>
                  <th>报修单状态</th>
                  <td>{{ order.status.name }}</td>
                </tr>
                <tr v-if="order.urged_at">
                  <th>最近催促</th>
                  <td>{{ order.urged_at }}</td>
                </tr>
                <tr v-if="order.received_at">
                  <th>接单时间</th>
                  <td>{{ order.received_at }}</td>
                </tr>
                <tr v-if="order.feedback_member">
                  <th>网管报告时间</th>
                  <td>{{ order.feedback_member.updated_at ? order.feedback_member.updated_at : order.feedback_member.created_at }}</td>
                </tr>
                <tr v-if="order.completed_at">
                  <th>标记完成时间</th>
                  <td>{{ order.completed_at }}</td>
                </tr>
                <tr v-if="order.feedback_user">
                  <th>用户评价时间</th>
                  <td>{{ order.feedback_user.created_at }}</td>
                </tr>
                <tr v-if="order.deleted_at">
                  <th>撤销/删除时间</th>
                  <td>{{ order.deleted_at }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="box-footer text-center">

            <button
              v-if="canReceive"
              class="btn btn-success"
              @click="receiveOrder">
              <i class="fa fa-fw fa-check-square-o"></i> 接单
            </button>

            <button
              v-if="canReport"
              class="btn btn-primary"
              @click="enterReport"
              :disabled="showReport">
              <i class="fa fa-fw fa-pencil"></i> <span v-if="order.feedback_member">修改</span><span v-else>填写</span>报告
            </button>

            <button
              v-if="canComplete"
              class="btn btn-success"
              @click="completeOrder"
              :disabled="showReport">
              <i class="fa fa-fw fa-star"></i> 标记完成
            </button>

          </div>
        </div>
        <!--报修单状态结束-->

        <!--报修单详情开始-->
        <div class="box box-primary">

          <div class="box-header with-border">
            <i class="fa fa-file-text-o"></i>

            <h3 class="box-title">报修单详情</h3>

          </div>

          <div class="box-body">
            <table class="table table-striped table-hover table-bordered text-center">
              <thead>
                <tr>
                  <th colspan=2><i class="fa fa-fw fa-wrench"></i> 报修内容</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th>宿舍楼</th>
                  <td>{{ order.building.name }}</td>
                </tr>
                <tr>
                  <th>房间号</th>
                  <td>{{ order.room }}</td>
                </tr>
                <tr v-if="order.service">
                  <th>优先处理方式</th>
                  <td>{{ order.service.name }}</td>
                </tr>
                <tr>
                  <th>故障描述</th>
                  <td>{{ order.detail }}</td>
                </tr>
              </tbody>
              <thead>
                <tr>
                  <th colspan=2><i class="fa fa-fw fa-phone"></i> 联系方式</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th>手机</th>
                  <td>{{ order.mobile }}</td>
                </tr>
                <tr v-if="order.qq">
                  <th>QQ</th>
                  <td>{{ order.qq }}</td>
                </tr>
                <tr v-if="order.wechat">
                  <th>微信</th>
                  <td>{{ order.wechat }}</td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>
        <!--报修单详情结束-->

        <!--网管报告开始-->
        <div v-if="order.feedback_member" class="box box-info">

          <div class="box-header with-border">
            <i class="fa fa-flag-o"></i>

            <h3 class="box-title">网管报告</h3>
            <div class="box-tool pull-right" v-if="canGivePoints">
              <button class="btn btn-success btn-sm" @click="showGivePoints = true" :disabled="showGivePoints"><i class="fa fa-plus"></i> 评分</button>
            </div>
          </div>

          <div class="box-body">
            <table class="table table-striped table-hover text-center">
              <tbody>
                <tr>
                  <th>报告时间</th>
                  <td>{{ order.feedback_member.updated_at ? order.feedback_member.updated_at : order.feedback_member.created_at }}</td>
                </tr>
                <tr v-if="order.feedback_member.score">
                  <th>报修人评分</th>
                  <td>{{ order.feedback_member.score }}</td>
                </tr>
                <tr v-if="order.feedback_member.service">
                  <th>处理方式</th>
                  <td>{{ order.feedback_member.service.name }}</td>
                </tr>
                <tr>
                  <th>报告内容</th>
                  <td>{{ order.feedback_member.detail }}</td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>
        <!--网管报告结束-->

        <!--用户评价开始-->
        <div v-if="order.feedback_user" class="box box-info">

          <div class="box-header with-border">
            <i class="fa fa-star-o"></i>

            <h3 class="box-title">用户评价</h3>

          </div>

          <div class="box-body">
            <table class="table table-striped table-hover text-center">
              <tbody>
                <tr>
                  <th>评价时间</th>
                  <td>{{ order.feedback_user.created_at }}</td>
                </tr>
                <tr v-if="order.feedback_member.score">
                  <th>评分</th>
                  <td>{{ order.feedback_user.score }}</td>
                </tr>
                <tr>
                  <th>评价内容</th>
                  <td>{{ order.feedback_user.detail }}</td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>
        <!--用户评价结束-->

        <!--填写评分报告开始-->
        <div v-if="canGivePoints && showGivePoints" class="box box-info">

          <div class="box-header with-border">
            <i class="fa fa-flag-o"></i>

            <h3 class="box-title">填写维修报告</h3>

          </div>

          <div class="box-body">
            <form id="feedbackFormPoints" autocomplete="off">
              <div class="col-md-12 col-lg-12" :class="{ 'has-error': errors.has('score') }">
                <label for="input-score">对网管评分 <span class="text-muted">(积分系统/最大为5)</span></label>
                <div>
                  <div class="col-md-12 col-lg-6 col-xs-12">
                  <label class="radio-inline col-md-2 col-lg-2">
                    <input type="radio" name="scorePoints" value="-2"
                      v-model="reportPointsForm.points" v-validate="'required'" data-vv-as="报修人评分"> 差评
                  </label>
                  <label class="radio-inline col-md-2 col-lg-2">
                    <input type="radio" name="scorePoints" value="-1"
                      v-model="reportPointsForm.points" v-validate="'required'" data-vv-as="报修人评分"> 不太好
                  </label>
                  <label class="radio-inline col-md-2 col-lg-2">
                    <input type="radio" name="scorePoints" value="0"
                      v-model="reportPointsForm.points" v-validate="'required'" data-vv-as="报修人评分"> 一般般
                  </label>
                  <label class="radio-inline col-md-2 col-lg-2">
                    <input type="radio" name="scorePoints" value="1"
                      v-model="reportPointsForm.points" v-validate="'required'" data-vv-as="报修人评分"> 还不错
                  </label>
                  <label class="radio-inline col-md-2 col-lg-2">
                    <input type="radio" name="scorePoints" value="2"
                      v-model="reportPointsForm.points" v-validate="'required'" data-vv-as="报修人评分"> 好评
                  </label>
                  </div>
                  <div class="col-md-12 col-lg-6 col-xs-12">
                    <input class="col-md-12 col-lg-12 col-xs-12" id="input-text" name="text" placeholder="请输入积分"
                    v-model="reportPointsForm.points" v-validate="'required'" data-vv-as="积分">
                  </div>
                </div>
                <div class="help-block with-errors">{{ errors.first('score') }}</div>
              </div>

              <div class="col-md-12 col-lg-12" :class="{ 'has-error': errors.has('detail') }">
                <label for="input-detail">评分描述</label>
                <textarea class="form-control" id="input-detail" name="detail" placeholder="请输入维修报告详细内容" rows=3
                  v-model="reportPointsFormOfdesc" v-validate="'required'" data-vv-as="报告详情">
                </textarea>
                <div class="help-block with-errors">{{ errors.first('detail') }}</div>
              </div>
            </form>

          </div>

          <div class="box-footer text-center">
            <button
              class="btn btn-success"
              @click="updatePoints">
              <i class="fa fa-fw fa-flag-o"></i> 提交评分
            </button>

            <button
              class="btn btn-default"
              @click="showGivePoints = false">
              <i class="fa fa-fw fa-undo"></i> 取消
            </button>
          </div>

        </div>
        <!--填写评分报告结束-->

      </div>
      <!--左侧col结束-->

      <!--右侧col开始-->
      <div class="col-md-4 col-lg-3">

        <!--报修人信息开始-->
        <div class="box box-success">

          <div class="box-header with-border">
            <i class="fa fa-user-circle-o"></i>

            <h3 class="box-title">报修人信息 </h3>

          </div>

          <div class="box-body">
            <table class="table table-striped table-hover text-center">
              <tbody>
                <tr>
                  <th>NETID</th>
                  <td>{{ order.user.netid }}</td>
                </tr>
                <tr>
                  <th>学号</th>
                  <td>{{ order.user.profile.stuid }}</td>
                </tr>
                <tr>
                  <th>姓名</th>
                  <td>{{ order.user.profile.name }}</td>
                </tr>
                <tr>
                  <th>性别</th>
                  <td>{{ order.user.profile.gender }}</td>
                </tr>
                <tr>
                  <th>班级</th>
                  <td>{{ order.user.profile.class }}</td>
                </tr>
                <tr>
                  <th>宿舍</th>
                  <td>{{ order.user.profile.dorm_building }} - {{ order.user.profile.dorm_room }}</td>
                </tr>
                <tr>
                  <th>手机</th>
                  <td>{{ order.user.profile.mobile }}</td>
                </tr>
                <tr>
                  <th>微信</th>
                  <td>{{ order.user.profile.wechat }}</td>
                </tr>
                <tr>
                  <th>QQ</th>
                  <td>{{ order.user.profile.qq }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="box-footer">
            <p class="text-muted">注：仅供参考，以报修单中的联系方式为准</p>
          </div>

        </div>
        <!--报修人信息结束-->

        <!--网管信息开始-->
        <div class="box box-danger">

          <div class="box-header with-border">
            <i class="fa fa-user-secret"></i>

            <h3 class="box-title">网管信息</h3>

          </div>

          <div class="box-body">
            <div v-if="order && order.member">
              <table class="table table-striped table-hover text-center">
                <tbody>
                  <tr>
                    <th>社团职位</th>
                    <td>{{ order.member.department.name }} - {{ order.member.designation.name }}</td>
                  </tr>
                  <!--<tr>-->
                  <!--  <th>NETID</th>-->
                  <!--  <td>{{ order.member.user.netid }}</td>-->
                  <!--</tr>-->
                  <!--<tr>-->
                  <!--  <th>学号</th>-->
                  <!--  <td>{{ order.member.user.profile.stuid }}</td>-->
                  <!--</tr>-->
                  <tr>
                    <th>姓名</th>
                    <td>{{ order.member.user.profile.name }}</td>
                  </tr>
                  <!--<tr>-->
                  <!--  <th>性别</th>-->
                  <!--  <td>{{ order.member.user.profile.gender }}</td>-->
                  <!--</tr>-->
                  <!--<tr>-->
                  <!--  <th>班级</th>-->
                  <!--  <td>{{ order.member.user.profile.class }}</td>-->
                  <!--</tr>-->
                  <tr>
                    <th>宿舍</th>
                    <td>{{ order.member.user.profile.dorm_building }} - {{ order.member.user.profile.dorm_room }}</td>
                  </tr>
                  <tr>
                    <th>手机</th>
                    <td>{{ order.member.user.profile.mobile }}</td>
                  </tr>
                  <tr v-if="order.member.user.profile.wechat">
                    <th>微信</th>
                    <td>{{ order.member.user.profile.wechat }}</td>
                  </tr>
                  <tr v-if="order.member.user.profile.qq">
                    <th>QQ</th>
                    <td>{{ order.member.user.profile.qq }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div v-else>
              <p>暂无接单网管</p>

              <div v-if="building_members != false">
                <p>{{ order.building.name }} 负责网管：</p>
                <table class="table table-striped table-hover text-center">
                  <thead>
                    <tr>
                      <th>部门</th>
                      <th>姓名</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="member in building_members" data-toggle="tooltip" data-placement="top" :title="member.user.profile.mobile" :key="member.key">
                      <td>{{ member.department.name }}</td>
                      <td>{{ member.user.profile.name }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

            </div>
          </div>

        </div>
        <!--网管信息结束-->

        <!--敏感操作开始-->
        <div class="box box-default" v-if="canDelete || canUpdate">

          <div class="box-header with-border">
            <i class="fa fa-warning"></i>

            <h3 class="box-title">敏感操作</h3>

          </div>

          <div class="box-body">
            <button
              v-if="canUpdate"
              class="btn btn-warning pull-left"
              @click="enterUpdate"
              :disabled="showUpdate">
              <i class="fa fa-fw fa-edit"></i> 编辑
            </button>
            <button
              v-if="canDelete"
              class="btn btn-danger pull-right"
              @click="deleteOrder">
              <i class="fa fa-fw fa-trash-o"></i> 删除
            </button>
          </div>
        </div>
        <!--敏感操作结束-->

        <!--编辑报修单开始-->
        <div v-if="canUpdate && showUpdate" class="box box-danger">

          <div class="box-header with-border">
            <i class="fa fa-edit"></i>

            <h3 class="box-title">编辑报修单</h3>

          </div>

          <div class="box-body">
            <form id="updateForm" autocomplete="off">

              <div class="form-group col-md-6 col-lg-6">
                <label for="input-search-department">网管所属部门</label>
                <select class="form-control" id="input-search-department" name="department_id" v-model="updateForm.department_id">
                  <option v-for="department in departments" :value="department.id" :key="department.id">
                    {{ department.name }}
                  </option>
                </select>
              </div>

              <div class="form-group col-md-6 col-lg-6" :class="{ 'has-error': errors.has('member_id') }">
                <label for="input-member_id">负责网管</label>
                <select class="form-control" id="input-member_id" name="member_id"
                  v-model="updateForm.member_id" v-validate="'required'" data-vv-as="负责网管">
                  <template v-for="department in departments">
                    <template v-if="department.id === updateForm.department_id">
                      <option v-for="member in department.members" :value="member.id" :key="member.id">
                        {{ member.user.profile.name }}
                      </option>
                    </template>
                  </template>
                </select>
                <div class="help-block with-errors">{{ errors.first('member_id') }}</div>
              </div>

            </form>

          </div>

          <div class="box-footer text-center">
            <button
              class="btn btn-success"
              @click="updateOrder">
              <i class="fa fa-fw fa-check"></i> 提交
            </button>

            <button
              class="btn btn-default"
              @click="showUpdate = false">
              <i class="fa fa-fw fa-undo"></i> 取消
            </button>
          </div>

        </div><!--编辑报修单结束-->
      </div><!--右侧col结束-->
    </div><!-- /.row (main row) -->
  </section>
</div>
</template>

<script>
import { mapState, mapGetters } from 'vuex'
import { swal } from '@/plugins'
export default {
  name: 'OrderShow',
  data () {
    return {
      reportPointsFormOfdesc: '',
      showGivePoints: false,
      loading: true,
      order: null,
      building_members: null,

      showReport: false,
      reportForm: {
        score: 5,
        detail: '',
        service_id: '',
      },

      reportPointsForm: {
        points: 0,
        desc: '',
        order_id: '',
        id: '',
        editor: '',
      },

      showUpdate: false,
      updateForm: {
        department_id: '',
        member_id: '',
      },
    }
  },
  computed: {
    ...mapState([
      'departments',
      'user',
    ]),
    ...mapGetters([
      'isManager',
      'isAdmin',
    ]),
    orderId () {
      return this.$route.params.id
    },
    canReceive () {
      return this.order && this.order.status_id === 1
    },
    canReport () {
      return this.order && this.order.status_id === 2 && this.order.member_id === this.user.member.id
    },
    canComplete () {
      return this.order && this.order.status_id === 2 && this.order.feedback_member && (this.order.member_id === this.user.member.id || this.isAdmin || (this.isManager && this.order.building.department_id === this.user.member.department_id))
    },
    canUpdate () {
      return this.order && !this.order.deleted_at && this.order.status_id === 2 && (this.order.building.department_id === this.user.member.department_id || this.user.member.department_id < 3) && this.user.member.designation_id < 5
    },
    canDelete () {
      return this.order && !this.order.deleted_at && this.order.status_id < 3 && (this.order.building.department_id === this.user.member.department_id || this.user.member.department_id < 3) && this.user.member.designation_id < 5
    },
    canGivePoints () {
      return this.order && !this.order.deleted_at && (this.order.status_id === 4 || this.order.status_id === 5) && (this.order.building.department_id === this.user.member.department_id || this.user.member.department_id < 3) && this.user.member.designation_id < 5
    },
  },
  created () {
    this.getOrder()
  },
  beforeRouteUpdate (to, from, next) {
    this.getOrder()
    next()
  },
  methods: {
    getOrder () {
      this.showReport = false
      this.showUpdate = false
      this.loading = true
      this.$axios.get(
        `/api/order/${this.orderId}`
      ).then(response => {
        this.order = response.data.data
        this.loading = false
        if (this.order.member === null) {
          this.getBuildingMembers()
        }
      })
    },

    getBuildingMembers () {
      this.$axios.get(
        `/api/building_member/${this.order.building_id}`
      ).then(response => {
        this.building_members = response.data.data.members
      })
    },

    receiveOrder () {
      if (!this.canReceive) return false
      swal({
        title: '是否确认接单？',
        text: '',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.post(
          `/api/order/${this.orderId}/receive`
        ),
      }).then(response => {
        return swal({
          title: response.data.status ? '操作失败！' : '操作成功！',
          text: response.data.message,
          type: response.data.status ? 'error' : 'success',
        })
      }).then(() => {
        this.getOrder()
      }).catch(swal.noop)
    },

    async reportOrder () {
      if (!this.canReport) return false
      let isValid = await this.$validator.validateAll()
      if (!isValid) return false
      swal({
        title: '是否确认提交？',
        text: '提交报告后，标记完成前可以随时修改',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.post(
          `/api/order/${this.orderId}/report`,
          this.reportForm
        ),
      }).then(response => {
        return swal({
          title: response.data.status ? '操作失败！' : '操作成功！',
          text: response.data.message,
          type: response.data.status ? 'error' : 'success',
        })
      }).then(() => {
        this.showReport = false
        this.getOrder()
      }).catch(swal.noop)
    },
    completeOrder () {
      if (!this.canComplete) return false
      swal({
        title: '是否确认标记完成？',
        text: '',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.post(
          `/api/order/${this.orderId}/complete`
        ),
      }).then(response => {
        return swal({
          title: response.data.status ? '操作失败！' : '操作成功！',
          text: response.data.message,
          type: response.data.status ? 'error' : 'success',
        })
      }).then(() => {
        this.getOrder()
      }).catch(swal.noop)
    },
    deleteOrder () {
      if (!this.canDelete) return false
      swal({
        title: '是否确认删除？',
        text: '删除后不可恢复，请谨慎操作！',
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
          text: '请确认删除！',
          type: 'warning',
          showCancelButton: true,
          showLoaderOnConfirm: true,
          preConfirm: () => this.$axios.delete(
            `/api/order/${this.orderId}`
          ),
        })
      }).then(response => {
        return swal({
          title: response.data.status ? '操作失败！' : '操作成功！',
          text: response.data.message,
          type: response.data.status ? 'error' : 'success',
        })
      }).then(() => {
        this.getOrder()
      }).catch(swal.noop)
    },

    async updateOrder () {
      if (!this.canUpdate) return false
      let isValid = await this.$validator.validateAll()
      if (!isValid) return false

      swal({
        title: '是否确认提交？',
        text: '编辑报修单操作会被记录日志',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.patch(
          `/api/order/${this.orderId}`,
          this.updateForm
        ),
      }).then((response) => {
        return swal({
          title: response.data.status ? '操作失败！' : '操作成功！',
          text: response.data.message,
          type: response.data.status ? 'error' : 'success',
        })
      }).then(() => {
        this.getOrder()
      })
        .catch(swal.noop)
    },
    enterReport () {
      this.showUpdate = false
      this.showReport = true
      if (this.order.feedback_member) {
        this.reportForm.score = this.order.feedback_member.score
        this.reportForm.service_id = this.order.feedback_member.service_id
        this.reportForm.detail = this.order.feedback_member.detail
      } else {
        this.reportForm.score = 5
        this.reportForm.service_id = ''
        this.reportForm.detail = ''
      }
    },
    enterUpdate () {
      this.showUpdate = true
      this.showReport = false
      this.updateForm.department_id = this.order.member.department_id
      this.updateForm.member_id = this.order.member_id
    },
    async updatePoints () {
      let isValid = await this.$validator.validateAll('reportPointsForm')
      if (!isValid) return false
      if (this.reportPointsFormOfdesc.length !== 0) {
        this.reportPointsFormOfdesc.length = this.reportPointsFormOfdesc.length + '  '
      }
      this.reportPointsForm.netid = this.order.member.user.netid
      this.reportPointsForm.id = this.order.member_id
      this.reportPointsForm.editor = this.user.profile.name
      this.reportPointsForm.desc = this.reportPointsFormOfdesc + '由 ' + this.user.profile.name + ' 通过订单 ' + this.order.id + ' 加 ' + this.reportPointsForm.points + ' 分'
      this.reportPointsForm.order_id = this.order.id
      swal({
        title: '是否确认提交？',
        text: '编辑社员积分操作会记入操作日志',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.patch(
          `/api/points/${this.order.member_id}`,
          this.reportPointsForm
        ),
      }).then(response => {
        return swal({
          title: response.data.status ? '操作失败！' : '操作成功！',
          text: response.data.message,
          type: response.data.status ? 'error' : 'success',
        })
      }).then(() => {
        this.reportPointsFormOfdesc = ''
        this.showGivePoints = false
      }).catch(swal.noop)
    },
  },
}
</script>
