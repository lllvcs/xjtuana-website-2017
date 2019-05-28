<template>
  <div class="overlay" v-show="loading">
    <i class="fa fa-spinner fa-pulse fa-fw"></i>
    <span class="sr-only">Loading...</span>
  </div>
</template>

<script>
export default {
  props: {
    loading: {
      type: Boolean,
      required: true,
    },
  },
  data () {
    return {
    }
  },
}
</script>

<style lang="scss" type="text/css" scoped>
  // 注意，.overlay的父元素必须要有position:relative，否则.overlay的absolute定位将相对于整个浏览器窗口
  .overlay {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    background: rgba(255,255,255,0.7);
    z-index: 50;
    &>.fa{
      position: absolute;
      top: 50%;
      left: 50%;
      margin-left: -15px;
      margin-top: -15px;
      color: #000;
      font-size: 30px;
    }
  }
</style>
