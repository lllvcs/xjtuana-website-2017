<template>
  <WeuiTab
    v-if="initialized"
    id="app"
  >
    <WeuiTabPanel>
      <TransitionFade>
        <router-view :key="$route.path"/>
      </TransitionFade>
    </WeuiTabPanel>

    <TheMainTabbar />
  </WeuiTab>
</template>

<script>
import { mapState } from 'vuex'
import TheMainTabbar from '@/pages/wechat/components/TheMainTabbar'
import TransitionFade from '@/pages/wechat/components/transitions/TransitionFade'
export default {
  components: {
    TheMainTabbar,
    TransitionFade,
  },
  computed: {
    ...mapState([
      'initialized',
      'user',
    ]),
  },
  created () {
    this.$store.dispatch('init')
  },
}
</script>

<style lang="scss" type="text/css">
html, body{
  height: 100%;
}
</style>
