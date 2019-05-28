<template>
<div class="box">
  <div class="overlay" v-show="loading">
    <i class="fa fa-refresh fa-spin"></i>
  </div>

  <div class="box-header">
    <slot name="header"></slot>
  </div>

  <div class="box-body">
    <slot name="body"></slot>
  </div>

  <div class="box-footer">
    <slot name="footer"></slot>
  </div>

</div>
</template>

<script>
export default {
  props: {
    loading: {
      default: false,
      type: Boolean,
    },
  },
  computed: {

  },
  data () {
    return {

    }
  },
}
</script>

<style lang="scss" type="text/css" scoped>

</style>
