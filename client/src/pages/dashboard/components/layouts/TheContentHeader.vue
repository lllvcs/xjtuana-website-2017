<template>
  <TransitionFade>
    <section class="content-header" :key="title">
      <h1>
        <span>{{ title }}</span>

        <small>西交网管会 - 控制台</small>
      </h1>

      <ol class="breadcrumb">
        <router-link :to="{ name: 'home'}" tag="li">
          <i class="fa fa-dashboard"></i> <a>控制台</a>
        </router-link>

        <li><a disabled>{{ title }}</a></li>

        <li><a href="#" @click="$router.go(-1)"> 后退</a></li>
      </ol>
    </section>
  </TransitionFade>
</template>

<script>
import TransitionFade from '@/pages/dashboard/components/transitions/TransitionFade'
export default {
  name: 'TheContentHeader',
  components: {
    TransitionFade,
  },
  computed: {
    title () {
      return this.$route.meta.title
    },
  },
  watch: {
    title () {
      this.updateTitle()
    },
  },
  methods: {
    updateTitle () {
      document.title = this.title + ' - 西交网管会 - 控制台'
    },
  },
  created () {
    this.updateTitle()
  },
}
</script>

<style lang="scss" type="text/css" scoped>

</style>
