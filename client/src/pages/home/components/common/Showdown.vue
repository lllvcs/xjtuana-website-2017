<template>
  <div v-html="html"></div>
</template>

<script>
import showdown from 'showdown'
const converter = new showdown.Converter()
export default {
  props: {

  },
  computed: {
    html () {
      return converter.makeHtml(this.slotMarkdown)
    },
    slotMarkdown () {
      return this.$slots.default[0].text
    },
  },
  data () {
    return {
    }
  },
}
</script>

<style lang="scss" type="text/css" scoped>

</style>
