<template>
  <a class="btn btn-default full-size hvr-back-pulse" :href="href">
    <div class="h4">
      <i v-if="fa" class="fa fa-fw" :class="fa"></i>
      <span v-if="text">
        {{ text }}
      </span>
    </div>
  </a>
</template>

<script>
export default {
  props: {
    href: {
      type: String,
      required: true,
    },
    fa: {
      type: [String, Boolean],
      default: false,
    },
    text: {
      type: [String, Boolean],
      default: false,
    },
  },
  data () {
    return {
    }
  },
}
</script>

<style lang="scss" type="text/css" scoped>
  .user-link {
    width: 150px;
    height: 115px;
    padding: 15px;
    text-align: center;
    &:not(:hover) {
      color: #222;
      background-color: #ddd;
    }
  }
</style>
