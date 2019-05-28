<template>
  <TransitionFade appear>
    <div class="wrapper">
      <TransitionFade>
        <div
          v-if="!initialized"
          class="initializing"
        >
          <div class="init-content">
            <i class="fa fa-spin fa-spinner fa-3x"></i>

            <p class="init-text">数据加载中...</p>
          </div>
        </div>
      </TransitionFade>

      <TheHeader />

      <TheSidebar />

        <div class="content-wrapper">
          <TheContentHeader />

          <TransitionFade>
            <keep-alive :include="['OrderIndex', 'MemberIndex']">
              <router-view :key="$route.param"/>
            </keep-alive>
          </TransitionFade>
        </div>

      <TheFooter />

      <TheControlSidebar />

      <div class="control-sidebar-bg"></div>
    </div>
  </TransitionFade>
</template>

<script>
import { mapState } from 'vuex'
import TheHeader from '@/pages/dashboard/components/layouts/TheHeader'
import TheFooter from '@/pages/dashboard/components/layouts/TheFooter'
import TheSidebar from '@/pages/dashboard/components/layouts/TheSidebar'
import TheControlSidebar from '@/pages/dashboard/components/layouts/TheControlSidebar'
import TheContentHeader from '@/pages/dashboard/components/layouts/TheContentHeader'
import TransitionFade from '@/pages/dashboard/components/transitions/TransitionFade'
export default {
  components: {
    TheHeader,
    TheFooter,
    TheSidebar,
    TheControlSidebar,
    TheContentHeader,
    TransitionFade,
  },

  computed: {
    ...mapState([
      'initialized',
    ]),
  },
}
</script>

<style lang="scss" type="text/css">
textarea {
  resize: none;
}
.initializing {
  position: absolute;
  height: 100%;
  width: 100%;
  background-color: black;
  z-index: 999999;
  .init-content {
    text-align: center;
    position: absolute;
    width: 100%;
    top: 15rem;
    color: white;
    .init-text {
      padding: 1rem;
    }
  }
}
</style>
