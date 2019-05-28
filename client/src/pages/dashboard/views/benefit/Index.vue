<template>
<div>
  <section class="content">
    <div class="row">
      <div class="col-sm-6 col-lg-4">
        <router-link :to="{ name: 'benefit.service' }">
          <div class="info-box">
            <span class="info-box-icon bg-purple">
              <i class="fa fa-cloud"></i>
            </span>
            <div class="info-box-content">
              <p class="h4"><strong>常用服务</strong></p>
              <p>提供代理、激活等服务</p>
            </div>
          </div>
        </router-link>
      </div>

      <div class="col-sm-6 col-lg-4">
        <router-link :to="{ name: 'benefit.software' }">
          <div class="info-box">
            <span class="info-box-icon bg-maroon">
              <i class="fa fa-download"></i>
            </span>
            <div class="info-box-content">
              <p class="h4"><strong>软件下载</strong></p>
              <p>提供常用软件资源下载</p>
            </div>
          </div>
        </router-link>
      </div>

      <div class="col-sm-6 col-lg-4">
        <router-link :to="{ name: 'benefit.accounts' }">
          <div class="info-box">
            <span class="info-box-icon bg-green">
              <i class="fa fa-group"></i>
            </span>
            <div class="info-box-content">
              <p class="h4"><strong>会员帐号</strong></p>
              <p>各大网站公用会员帐号</p>
            </div>
          </div>
        </router-link>
      </div>
    </div>

    <div class="row">
      <TransitionFade appear>
        <router-view />
      </TransitionFade>
    </div>
  </section>
</div>
</template>

<script>
import TransitionFade from '@/pages/dashboard/components/transitions/TransitionFade'
export default {
  components: {
    TransitionFade,
  },
}
</script>

<style lang="scss" type="text/css">
.box-header[data-widget=collapse] {
  cursor: pointer;
}
</style>
