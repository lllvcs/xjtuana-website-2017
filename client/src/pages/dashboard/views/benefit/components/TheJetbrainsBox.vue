<template>
  <div class="box box-solid collapsed-box">
    <div class="box-header with-border" data-widget="collapse">
      <i class="fa fa-fw fa-fighter-jet"></i>

      <h3 class="box-title">Jetbrains激活服务</h3>
    </div>

    <div class="box-body">
      <MdJetbrains />
    </div>

    <div class="box-footer">
      当前状态：
      <HealthLabel
        :loading="loading"
        :healthy="jetbrainsCheck.ok"
        :ts="jetbrainsCheck.ts"
        name="Jetbrains激活服务"
      />
    </div>
  </div>
</template>

<script>
import HealthLabel from './HealthLabel'
import MdJetbrains from '@/assets/md/Jetbrains'
export default {
  components: {
    HealthLabel,
    MdJetbrains,
  },
  props: {
    loading: {
      type: Boolean,
      default: true,
    },
    jetbrainsCheck: {
      type: Object,
      required: true,
    },
  },
}
</script>
