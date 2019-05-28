<template>
  <div class="box box-solid collapsed-box">
    <div class="box-header with-border" data-widget="collapse">
      <i class="fa fa-fw fa-paper-plane"></i>

      <h3 class="box-title">Socks5代理服务</h3>
    </div>

    <div class="box-body">
      <MdShadowsocks />
    </div>

    <div class="box-footer">
      当前状态：

      <HealthLabel
        :loading="loading"
        :healthy="xjtuCheck.ok"
        :ts="xjtuCheck.ts"
        name="SS校内代理"
        style="margin-left: 5px"
      />
    </div>
  </div>
</template>

<script>
import HealthLabel from './HealthLabel'
import MdShadowsocks from '@/assets/md/Shadowsocks'
export default {
  components: {
    HealthLabel,
    MdShadowsocks,
  },
  props: {
    loading: {
      type: Boolean,
      default: true,
    },
    gfwCheck: {
      type: Object,
      required: true,
    },
    xjtuCheck: {
      type: Object,
      required: true,
    },
  },
}
</script>
