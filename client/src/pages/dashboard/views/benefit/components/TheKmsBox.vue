<template>
  <div class="box box-solid collapsed-box">
    <div class="box-header with-border" data-widget="collapse">
      <i class="fa fa-fw fa-windows"></i>

      <h3 class="box-title">KMS激活服务</h3>
    </div>

    <div class="box-body">
      <MdKms />
    </div>

    <div class="box-footer">
      当前状态：
      <HealthLabel
        :loading="loading"
        :healthy="kmsCheck.ok"
        :ts="kmsCheck.ts"
        name="KMS激活服务"
      />
    </div>
  </div>
</template>

<script>
import HealthLabel from './HealthLabel'
import MdKms from '@/assets/md/KMS'
export default {
  components: {
    HealthLabel,
    MdKms,
  },
  props: {
    loading: {
      type: Boolean,
      default: true,
    },
    kmsCheck: {
      type: Object,
      required: true,
    },
  },
}
</script>
