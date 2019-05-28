<template>
<div class="shadow" id="user-order">

  <common-overlay :loading="loading"></common-overlay>

  <div id="order-title">
    <h2 class="text-center"><i class="fa fa-file-text-o"></i> 报修单详情</h2>
    <hr>
  </div>

  <h3 class="text-center" v-show="loading">加载中……</h3>

  <div id="order-content" v-show="!loading">
    <div class="content-wrapper" id="base-info">
        <h3>个人信息</h3>
        <hr>

        <div class="form-group col-sm-4">
            <label class="h4" for="form-name"><i class="fa fa-fw fa-user-circle"></i> 姓名</label>
            <input type="text" class="form-control" id="form-name" v-model="this.order.user.profile.name" readonly>
        </div>

        <div class="form-group col-sm-4">
            <label class="h4" for="form-stuid"><i class="fa fa-fw fa-vcard"></i> 学号</label>
            <input type="text" class="form-control" id="form-stuid" v-model="this.order.user.profile.stuid" readonly>
        </div>

        <div class="form-group col-sm-4">
            <label class="h4" for="form-gender"><i class="fa fa-fw fa-transgender"></i> 性别</label>
            <input type="text" class="form-control" id="form-gender" v-model="this.order.user.profile.gender" readonly>
        </div>
    </div>

    <div class="content-wrapper" id="contact-info">
        <h3>报修联系方式</h3>
        <hr>

        <div class="form-group col-md-4">
            <label class="h4" for="form-mobile"><i class="fa fa-fw fa-mobile"></i> 手机</label>
            <input type="text" class="form-control" id="form-mobile" name="mobile" v-model="this.order.user.profile.mobile" readonly>
        </div>

        <div class="form-group col-md-4">
            <label class="h4" for="form-qq"><i class="fa fa-fw fa-qq"></i> QQ</label>
            <input type="text" class="form-control" id="form-qq" name="qq" v-model="this.order.user.profile.qq" readonly>
        </div>

        <div class="form-group col-md-4">
            <label class="h4" for="form-wechat"><i class="fa fa-fw fa-wechat"></i> 微信</label>
            <input type="text" class="form-control" id="form-wechat" name="wechat" v-model="this.order.user.profile.wechat" readonly>
        </div>
    </div>

    <div class="content-wrapper" id="repair-info">
        <h3>报修详情</h3>
        <hr>

        <div class="form-group col-md-4">
            <label class="h4" for="form-campus"><i class="fa fa-fw fa-map-marker"></i> 校区</label>
            <input type="text" class="form-control" id="form-campus" name="campus" v-model="this.order.building.campus.name" readonly>
            <div class="help-block with-errors"></div>
        </div>

        <div class="form-group col-md-4">
            <label class="h4" for="form-building"><i class="fa fa-fw fa-building"></i> 宿舍楼</label>
            <input type="text" class="form-control" id="form-building" name="building" v-model="this.order.building.name" readonly>
            <div class="help-block with-errors"></div>
        </div>

        <div class="form-group col-md-4">
            <label class="h4" for="form-room"><i class="fa fa-fw fa-map-signs"></i> 房间号</label>
            <input type="text" class="form-control" id="form-room" name="room" v-model="this.order.user.profile.dorm_room" readonly>
            <div class="help-block with-errors"></div>
        </div>

        <div class="form-group col-xs-12">
            <label class="h4" for="form-detail"><i class="fa fa-fw fa-stethoscope"></i> 优先处理方式</label>
            <input type="text" class="form-control" id="form-service" name="service" v-model="this.order.service.name" readonly>
            <div class="help-block with-errors"></div>
        </div>

        <div class="form-group col-xs-12">
            <label class="h4" for="form-detail"><i class="fa fa-fw fa-file-text"></i> 故障描述</label>
            <textarea class="form-control" id="form-detail" name="detail" rows="5" readonly v-model="this.order.detail"></textarea>
            <div class="help-block with-errors"></div>
        </div>
    </div>
  </div>
</div>
</template>

<script>
import axios from '@/plugins/axios'
export default {
  props: {
    orderId: {
      type: Number,
      required: true,
    },
  },
  data () {
    return {
      loading: true,
      order: {},
    }
  },
  created () {
    this.getOrder()
  },
  methods: {
    async getOrder () {
      this.order = await axios.get(`/api/order/${this.orderId}`)
      this.loading = false
    },
  },
}
</script>

<style lang="scss" type="text/css" scoped>
  #user-order {
    margin: 10px;
    padding: 20px;
  }
</style>
