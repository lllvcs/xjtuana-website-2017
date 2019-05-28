<template>
<div class="content-wrapper">

  <div class="content-wrapper" id="order-status">
    <h3><i class="fa fa-fw fa-lightbulb-o"></i> {{ $t("show.title") }}</h3>
    <hr>
    <div class="content-wrapper">
      <p><strong> {{ $t("show.status") }}</strong>{{ ($i18n.locale == "zh")? order.status.name:order.status.name_en }}</p>
      <p><strong> {{ $t("show.Responsibility") }}</strong>{{ order.member ?  `${order.member.department.name} - ${order.member.user.profile.name}` : ($i18n.locale == "zh")? '暂无':'Null' }}</p>
      <p><strong> {{ $t("show.created") }}</strong>{{ order.created_at }}</p>
      <p v-if="order.received_at"><strong> {{ $t("show.received") }}</strong>{{ order.received_at }}</p>
      <p v-if="order.feedback_member"><strong> {{ $t("show.member") }}</strong>{{ order.feedback_member.updated_at ? order.feedback_member.updated_at : order.feedback_member.created_at }}</p>
      <p v-if="order.completed_at"><strong> {{ $t("show.completed") }}</strong>{{ order.completed_at }}</p>
      <p v-if="order.feedback_user"><strong> {{ $t("show.user") }}</strong>{{ order.feedback_user.created_at }}</p>
      <p v-if="order.status.id > 98 && order.status.id < 101"><strong> {{ $t("show.Cancel") }}</strong>{{ order.deleted_at }}</p>
    </div>
  </div>

  <div class="content-wrapper" id="order-operation" v-if="order.status_id === 1">
    <h3><i class="fa fa-fw fa-gears"></i> {{ $t("show.Operation") }}</h3>
    <hr>
    <div class="content-wrapper">
      <p>
        <button class="btn btn-warning" :disabled="order.status.id !== 1" @click="orderUrge()"><i class="fa fa-fw fa-bullhorn"></i> {{ $t("show.Urge") }}&nbsp;</button>
        <button class="btn btn-primary" v-if="false"><i class="fa fa-fw fa-edit"></i> {{ $t("show.edit") }}&nbsp;</button>
        <button class="btn btn-danger" :disabled="order.status.id !== 1" @click="orderCancel()"><i class="fa fa-fw fa-ban"></i> {{ $t("show.Cancel_1") }}&nbsp;</button>
      </p>
    </div>
  </div>

  <div class="content-wrapper" id="order-report" v-if="order.feedback_member">
    <h3><i class="fa fa-fw fa-flag-o"></i> {{ $t("show.feedback_member") }}</h3>
    <hr>
    <div class="content-wrapper">
      <p>{{ order.feedback_member.detail }}</p>
    </div>
  </div>

  <div class="content-wrapper" id="order-rate" v-if="order.status.id === 3 || order.status.id === 4">
    <h3>
      <i class="fa fa-fw fa-heart-o"></i> {{ $t("show.order_rate") }}
      <small v-if="!order.feedback_user"><span class="text-danger">{{ $t("show.order_rate_1") }}</span></small>
      <small v-else class="text-muted"><span class="text-muted">{{ $t("show.order_rate_2") }}</span></small>
    </h3>
    <hr>
    <div class="content-wrapper">
      <form id="orderFeedback" autocomplete="off" data-toggle="validator">
        <div class="form-group">
          <label class="h4" for="feedback-score"><i class="fa fa-fw fa-star-o"></i> {{ $t("show.orderFeedback") }}</label>
          <div class="well">
            <label class="radio-inline">
              <input type="radio" name="score" value="1" v-model="feedback_user.score" :disabled="order.feedback_user" required> {{ $t("show.score_1") }}
            </label>
            <label class="radio-inline">
              <input type="radio" name="score" value="2" v-model="feedback_user.score" :disabled="order.feedback_user" required> {{ $t("show.score_2") }}
            </label>
            <label class="radio-inline">
              <input type="radio" name="score" value="3" v-model="feedback_user.score" :disabled="order.feedback_user" required> {{ $t("show.score_3") }}
            </label>
            <label class="radio-inline">
              <input type="radio" name="score" value="4" v-model="feedback_user.score" :disabled="order.feedback_user" required> {{ $t("show.score_4") }}
            </label>
            <label class="radio-inline">
              <input type="radio" name="score" value="5" v-model="feedback_user.score" :disabled="order.feedback_user" required> {{ $t("show.score_5") }}
            </label>
          </div>
        </div>

        <div class="form-group">
          <label class="h4" for="feedback-detail"><i class="fa fa-fw fa-flag-o"></i> {{ $t("show.details_fed") }}</label>
          <textarea
            class="form-control"
            id="feedback-detail"
            name="detail"
            rows="5"
            v-model.trim="feedback_user.detail"
            :readonly="order.feedback_user"
            required>
          </textarea>
          <div class="help-block with-errors"></div>
        </div>
      </form>
        <p>
          <button class="btn btn-success" v-if="!order.feedback_user" :disabled="order.status.id !== 3" @click="orderFeedback()"><i class="fa fa-fw fa-check"></i> {{ $t("show.submit") }}&nbsp;</button>
        </p>
      </div>
    </div>

  <div class="content-wrapper" id="order-detail">

    <h3><i class="fa fa-fw fa-file-text-o"></i>{{ $t("show.order_detail") }}</h3>
    <hr>

    <div class="content-wrapper" id="base-info">
        <h4><strong>{{ $t("show.info") }}</strong></h4>
        <hr>

        <div class="form-group col-sm-4">
          <label class="h4" for="form-name"><i class="fa fa-fw fa-user-circle"></i> {{ $t("show.name") }}</label>
          <input type="text" class="form-control" id="form-name" :value="order.user.profile.name" readonly>
        </div>

        <div class="form-group col-sm-4">
          <label class="h4" for="form-stuid"><i class="fa fa-fw fa-vcard"></i> {{ $t("show.stuid") }}</label>
          <input type="text" class="form-control" id="form-stuid" :value="order.user.profile.stuid" readonly>
        </div>

        <div class="form-group col-sm-4">
          <label class="h4" for="form-gender"><i class="fa fa-fw fa-transgender"></i> {{ $t("show.gender") }}</label>
          <input type="text" class="form-control" id="form-gender" :value="order.user.profile.gender" readonly>
        </div>
    </div>

    <div class="content-wrapper" id="contact-info">
        <h4><strong>{{ $t("show.contact") }}</strong></h4>
        <hr>

        <div class="form-group col-md-4">
          <label class="h4" for="form-mobile"><i class="fa fa-fw fa-mobile"></i> {{ $t("show.mobile") }}</label>
          <input type="text" class="form-control" id="form-mobile" name="mobile" :value="order.mobile" readonly>
        </div>

        <div class="form-group col-md-4">
          <label class="h4" for="form-qq"><i class="fa fa-fw fa-qq"></i> QQ</label>
          <input type="text" class="form-control" id="form-qq" name="qq" :value="order.qq" readonly>
        </div>

        <div class="form-group col-md-4">
          <label class="h4" for="form-wechat"><i class="fa fa-fw fa-wechat"></i> Wechat</label>
          <input type="text" class="form-control" id="form-wechat" name="wechat" :value="order.wechat" readonly>
        </div>
    </div>

    <div class="content-wrapper" id="repair-info">
      <h4><strong>{{ $t("show.wrapper") }}</strong></h4>
      <hr>

      <div class="form-group col-md-4">
        <label class="h4" for="form-campus"><i class="fa fa-fw fa-map-marker"></i> {{ $t("show.campus") }}</label>
        <input type="text" class="form-control" id="form-campus" name="campus" :value="($i18n.locale == 'zh')? order.building.campus.name : order.building.campus.name_en" readonly>
        <div class="help-block with-errors"></div>
      </div>

      <div class="form-group col-md-4">
        <label class="h4" for="form-building"><i class="fa fa-fw fa-building"></i> {{ $t("show.building") }}</label>
        <input type="text" class="form-control" id="form-building" name="building" :value="($i18n.locale == 'zh')? order.building.name : order.building.name_en" readonly>
        <div class="help-block with-errors"></div>
      </div>

      <div class="form-group col-md-4">
        <label class="h4" for="form-room"><i class="fa fa-fw fa-map-signs"></i> {{ $t("show.signs") }}</label>
        <input type="text" class="form-control" id="form-room" name="room" :value="order.room" readonly>
        <div class="help-block with-errors"></div>
      </div>

      <div v-if="order.service" class="form-group col-xs-12">
        <label class="h4" for="form-detail"><i class="fa fa-fw fa-stethoscope"></i> {{ $t("show.service") }}</label>
        <input type="text" class="form-control" id="form-service" name="service" :value="order.service ? ($i18n.locale == 'zh')? order.service.name : order.service.name_en : null" readonly>
        <div class="help-block with-errors"></div>
      </div>

      <div class="form-group col-xs-12">
        <label class="h4" for="form-detail"><i class="fa fa-fw fa-file-text"></i> {{ $t("show.file_text") }}</label>
        <textarea class="form-control" id="form-detail" name="detail" rows="5" :value="order.detail" readonly></textarea>
        <div class="help-block with-errors"></div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import { axios, swal } from '@/plugins'

export default {
  props: {
    order: {
      type: Object,
      required: true,
    },
  },
  data () {
    return {
      feedback_user: {
        score: 5,
        detail: '',
        herf: window.location.href.split('/'),
      },
    }
  },
  updated () {
    // if (Array.isArray(this.herf) && (this.herf['3'] === 'en' || this.herf['3'] === 'zh')) { this.$i18n.locale = this.herf[3] }
  },
  created () {
    // if (Array.isArray(this.herf) && (this.herf['3'] === 'en' || this.herf['3'] === 'zh')) { this.$i18n.locale = this.herf[3] }
    if (this.order.feedback_user) {
      this.feedback_user = this.order.feedback_user
    }
    this.$i18n.locale = window.localStorage.getItem('locale')
  },
  methods: {
    orderUrge () {
      swal({
        title: this.$t('show.orderUrge_title'),
        text: this.$t('show.orderUrge_text'),
        type: 'warning',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        confirmButtonText: this.$t('show.confirmButtonText'),
        cancelButtonText: this.$t('show.cancelButtonText'),
        preConfirm: () => axios.post(`/api/order/${this.order.id}/urge`),
      }).then(response => {
        swal({
          title: response.data.status ? this.$t('show.error') : this.$t('show.success'),
          text: response.data.message,
          type: response.data.status ? 'error' : 'success',
        })
      })
        .catch(swal.noop)
    },

    orderCancel () {
      swal({
        title: this.$t('show.orderCancel_title'),
        text: this.$t('show.orderCancel_text'),
        type: 'warning',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        confirmButtonText: this.$t('show.confirmButtonText'),
        cancelButtonText: this.$t('show.cancelButtonText'),
        preConfirm: () => axios.delete(`/api/order/${this.order.id}/cancel`),
      }).then(response => {
        swal({
          title: response.data.status ? this.$t('show.error') : this.$t('show.success'),
          text: response.data.message,
          type: response.data.status ? 'error' : 'success',
        })
          .then(() => {
            window.location.reload(true)
          })
      })
        .catch(swal.noop)
    },

    orderFeedback () {
      if ($('#orderFeedback').data('bs.validator').validate().hasErrors()) {
        return false
      }
      swal({
        title: this.$t('show.orderFeedback_title'),
        text: '',
        type: 'warning',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        confirmButtonText: this.$t('show.confirmButtonText'),
        cancelButtonText: this.$t('show.cancelButtonText'),
        preConfirm: () => axios.post(`/api/order/${this.order.id}/rate`, this.feedback_user),
      }).then((response) => {
        swal({
          title: response.data.status ? this.$t('show.error') : this.$t('show.success'),
          text: response.data.message,
          type: response.data.status ? 'error' : 'success',
        })
          .then(() => {
            window.location.reload(true)
          })
      })
        .catch(swal.noop)
    },
  },
}
</script>
