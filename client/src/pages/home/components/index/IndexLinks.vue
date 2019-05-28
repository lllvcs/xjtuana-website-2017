<template>
  <div id="links-wrapper">
    <div class="row" v-if="$i18n.locale == 'zh'">
      <div class="col-sm-6 no-padding">
        <a class="button button-giant button-block button-primary"  id="link-faqs" href="/zh/faqs">
          <i class="fa fa-fw fa-question-circle-o" aria-hidden="true"></i>
           {{ $t("links.button_11") }}<span class="hidden-sm"> | <small> {{ $t("links.button_12") }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</small></span>
        </a>
      </div>

      <div class="col-sm-6 no-padding">
        <a class="button button-giant button-block button-caution" id="link-order-create" href="/zh/user/order/create">
          <i class="fa fa-fw fa-bell-o" aria-hidden="true"></i>
           {{ $t("links.button_21") }}<span class="hidden-sm"> | <small> {{ $t("links.button_22") }}&nbsp;&nbsp;&nbsp;&nbsp;</small></span>
        </a>
      </div>

      <div class="col-sm-6 no-padding">
        <a class="button button-giant button-block button-highlight" id="link-follow-us" href="" data-toggle="modal" data-target="#follow-us">
          <i class="fa fa-fw fa-weixin" aria-hidden="true"></i>
           {{ $t("links.button_31") }}<span class="hidden-sm"> | <small> {{ $t("links.button_32") }}</small></span>
        </a>
      </div>
      <div class="col-sm-6 no-padding">
        <a class="button button-giant button-block button-royal" id="link-join-us" href="/join-us">
          <i class="fa fa-fw fa-user-plus" aria-hidden="true"></i>
           {{ $t("links.button_41") }}<span class="hidden-sm"> | <small> {{ $t("links.button_42") }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</small></span>
        </a>
      </div>
    </div>

    <div class="row" v-else>
      <div class="col-sm-6 no-padding">
        <a class="button button-giant button-block button-primary" id="link-faqs" href="/en/faqs">
          <i class="fa fa-fw fa-question-circle-o" aria-hidden="true"></i>
          <span class="hidden-sm">{{ $t("links.button_12") }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </a>
      </div>

      <div class="col-sm-6 no-padding">
        <a class="button button-giant button-block button-caution" id="link-order-create" href="/en/user/order/create">
          <i class="fa fa-fw fa-bell-o" aria-hidden="true"></i>
          <span class="hidden-sm">{{ $t("links.button_22") }}&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </a>
      </div>

      <div class="col-sm-6 no-padding">
        <a class="button button-giant button-block button-highlight" id="link-follow-us" href="" data-toggle="modal" data-target="#follow-us">
          <i class="fa fa-fw fa-weixin" aria-hidden="true"></i>
          <span class="hidden-sm">{{ $t("links.button_32") }}</span>
        </a>
      </div>
      <div class="col-sm-6 no-padding">
        <a class="button button-giant button-block button-royal" id="link-join-us" href="/join-us">
          <i class="fa fa-fw fa-user-plus" aria-hidden="true"></i>
          <span class="hidden-sm">{{ $t("links.button_42") }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </a>
      </div>
    </div>

    <CommonModal id="follow-us" :has-cancel="true">
      <div class="text-center">
          <h3><i class="fa fa-fw fa-weixin"></i> {{ $t("links.footer") }}</h3>
          <img src="~@/assets/img/qrcode.jpg" alt=""  title="XJTUANA">
      </div>
    </CommonModal>
  </div>
</template>

<script>
export default {
  name: 'IndexLinks',
  data () {
    return {
      herf: window.location.href.split('/'),
    }
  },
  created () {
    if (this.herf['3'] === 'en' || this.herf['3'] === 'zh') {
      this.$i18n.locale = this.herf['3']
      window.localStorage.setItem('locale', this.herf['3'])
    }
  },
}
</script>

<style lang="scss" type="text/css" scoped>
 #links-wrapper {
  border-radius:0;
  background-color: #fff;
  .link-item {
    display: block;
    height: 70px;
    line-height: 70px;
    padding: {
      top: 10px;
      bottom: 10px;
    }
    .link-title {
      font-size: 20px;
      // font-weight: bold;
      margin: {
          top: 10px;
      }
    }
  }
}
</style>
