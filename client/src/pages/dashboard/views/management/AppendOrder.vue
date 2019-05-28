<template>
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <i class="fa fa-file-text-o"></i>

            <h3 class="box-title">添加报修单</h3>
          </div>

          <div class="box-body">
            <form class="row" @submit.prevent="createOrder">
              <div class="form-group col-lg-4">
                <label for="form-department_for_buildings">所属运维部</label>

                <select
                   id="form-department_for_buildings"
                   class="form-control"
                   @change="form.building = ''"
                   v-model="department_for_buildings"
                 >
                  <option value="" style="display: none" disabled>- 选择运维部 -</option>

                  <option v-for="department in departmentsOfService" :value="department" :key="department.id">
                    {{ department.name }}
                  </option>
                </select>
              </div>

              <div class="form-group col-lg-4" :class="{ 'has-error': errors.has('building') }">
                <label for="form-building">宿舍楼</label>

                <select
                  id="form-building"
                  class="form-control"
                  name="building"
                  data-vv-as="宿舍楼"
                  data-vv-validate-on="blur"
                  v-validate="{ required: true }"
                  @focus="errors.remove('building')"
                  v-model="form.building"
                >
                  <option value="" style="display: none" disabled>- 选择宿舍楼 -</option>

                  <option v-for="building in department_for_buildings.buildings" :value="building.id" :key="building.id">
                    {{ building.name }}
                  </option>
                </select>

                <div class="help-block with-errors">{{ errors.first('building') }}</div>
              </div>

              <div class="form-group col-lg-4" :class="{ 'has-error': errors.has('room') }">
                <label for="form-room">房间号</label>

                <input
                  id="form-room"
                  class="form-control"
                  name="room"
                  type="text"
                  placeholder="请输入报修房间号"
                  data-vv-as="房间号"
                  data-vv-validate-on="blur"
                  v-validate="{ required: true }"
                  @focus="errors.remove('room')"
                  v-model="form.room"
                >

                <div class="help-block with-errors">{{ errors.first('room') }}</div>
              </div>

              <div class="form-group col-lg-4" :class="{ 'has-error': errors.has('mobile') }">
                <label for="form-mobile">手机号</label>

                <input
                  id="form-mobile"
                  class="form-control"
                  name="mobile"
                  type="text"
                  placeholder="请输入报修人手机号"
                  data-vv-as="手机号"
                  data-vv-validate-on="blur"
                  v-validate="{ required: true, regex: regex.mobile }"
                  @focus="errors.remove('mobile')"
                  v-model="form.mobile"
                >

                <div class="help-block with-errors">{{ errors.first('mobile') }}</div>
              </div>

              <div class="form-group col-lg-4" :class="{ 'has-error': errors.has('qq') }">
                <label for="form-qq">QQ号</label>

                <input
                  id="form-qq"
                  class="form-control"
                  name="qq"
                  type="text"
                  placeholder="请输入报修人QQ号(选填)"
                  data-vv-as="QQ号"
                  data-vv-validate-on="blur"
                  v-validate="{ required: false, regex: regex.qq }"
                  @focus="errors.remove('qq')"
                  v-model="form.qq"
                />

                <div class="help-block with-errors">{{ errors.first('qq') }}</div>
              </div>

              <div class="form-group col-lg-4" :class="{ 'has-error': errors.has('wechat') }">
                <label for="form-wechat">微信号</label>

                <input
                  id="form-wechat"
                  class="form-control"
                  name="wechat"
                  type="text"
                  placeholder="请输入报修人微信号(选填)"
                  data-vv-as="微信号"
                  data-vv-validate-on="blur"
                  v-validate="{ required: false, regex: regex.wechat }"
                  @focus="errors.remove('wechat')"
                  v-model="form.wechat"
                >

                <div class="help-block with-errors">{{ errors.first('wechat') }}</div>
              </div>

              <div class="form-group col-lg-12" :class="{ 'has-error': errors.has('service') }">
                <label for="form-service">优先处理方式</label>

                <select
                  id="form-service"
                  class="form-control"
                  name="service"
                  data-vv-as="优先处理方式"
                  data-vv-validate-on="blur"
                  v-validate="{ required: true}"
                  @focus="errors.remove('service')"
                  v-model="form.service"
                >
                  <option v-for="service in order_services" :value="service.id" :key="service.id">
                    {{ service.name }}
                  </option>
                </select>

                <div class="help-block with-errors">{{ errors.first('service') }}</div>
              </div>

              <div class="form-group col-lg-12" :class="{ 'has-error': errors.has('detail') }">
                <label for="form-detail">报修详情</label>

                <textarea
                  id="form-detail"
                  class="form-control"
                  name="detail"
                  placeholder="请输入短信内容"
                  rows=5
                  data-vv-as="报修详情"
                  data-vv-validate-on="blur"
                  v-validate="'required'"
                  @focus="errors.remove('detail')"
                  v-model="form.detail"
                ></textarea>

                <div class="help-block with-errors">{{ errors.first('detail') }}</div>
              </div>

              <div class="form-group col-md-6 col-lg-6">
                <label for="form-department_for_members">接单部门</label>

                <select
                   id="form-department_for_members"
                   class="form-control"
                   @change="form.member = ''"
                   v-model="department_for_members"
                 >
                  <option value="" style="display: none" disabled>- 选择部门 -</option>

                  <option v-for="department in departments" :value="department" :key="department.id">
                    {{ department.name }}
                  </option>
                </select>
              </div>

              <div class="form-group col-md-6 col-lg-6" :class="{ 'has-error': errors.has('member') }">
                <label for="form-member">负责网管</label>

                <select
                  id="form-member"
                  class="form-control"
                  name="member"
                  data-vv-as="负责网管"
                  data-vv-validate-on="blur"
                  v-validate="{ required: true }"
                  @focus="errors.remove('member')"
                  v-model="form.member"
                >
                  <option value="" style="display: none" disabled>- 选择负责网管 -</option>

                  <option v-for="member in department_for_members.members" :value="member.id" :key="member.id">
                    {{ member.user.profile.name }}
                  </option>
                </select>

                <div class="help-block with-errors">{{ errors.first('member') }}</div>
              </div>
            </form>

            <button class="btn btn-sm btn-info pull-right" @click="reset"><i class="fa fa-fw fa-undo"></i> 重置</button>
          </div>

          <div class="box-footer text-center">
            <button
              class="btn btn-success"
              @click="createOrder"
            >
              <i class="fa fa-fw fa-plus"></i> 提交
            </button>
          </div>
        </div>
      </div>
    </div><!-- /.row (main row) -->
  </section>
</template>

<script>
import { mapState, mapGetters } from 'vuex'
import { swal } from '@/plugins'
import { regex } from '@/utils'
export default {
  data () {
    return {
      form: {
        building: '',
        room: '',
        mobile: '',
        qq: '',
        wechat: '',
        service: 1,
        detail: '【部长加单】',
        member: '',
      },
      department_for_buildings: '',
      department_for_members: '',
      regex,
    }
  },
  computed: {
    ...mapState([
      'user',
      'departments',
      'order_services',
    ]),

    ...mapGetters([
      'departmentsOfService',
    ]),
  },
  methods: {
    reset () {
      this.form = {
        building: '',
        room: '',
        mobile: '',
        qq: '',
        wechat: '',
        service: 1,
        detail: '【部长加单】',
      }
      this.errors.clear()
    },
    async createOrder () {
      let isValid = await this.$validator.validateAll()
      if (!isValid) return false
      swal({
        title: '是否确认提交？',
        text: '手动加单不会发送短信提醒，记得提醒部员结单喔~',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.post('/api/order/append', this.form),
      }).then(response => {
        return swal({
          title: response.data.status ? '操作失败！' : '操作成功！',
          text: response.data.message,
          type: response.data.status ? 'error' : 'success',
        })
      }).then(() => {
        this.reset()
      }).catch(swal.noop)
    },
  },
}
</script>
