<template>
  <div class="col-xs-12">
    <TheShadowsocksBox :loading="loading" :gfw-check="porxyGfwCheck" :xjtu-check="proxyXjtuCheck" />

    <TheKmsBox :loading="loading" :kms-check="kmsCheck" />

    <TheJetbrainsBox :loading="loading" :jetbrains-check="jetbrainsCheck" />
  </div>
</template>

<script>
import TheShadowsocksBox from './components/TheShadowsocksBox'
import TheKmsBox from './components/TheKmsBox'
import TheJetbrainsBox from './components/TheJetbrainsBox'

export default {
  components: {
    TheShadowsocksBox,
    TheKmsBox,
    TheJetbrainsBox,
  },
  data () {
    return {
      loading: true,
      results: {
        shadowsocks_gfw: {},
        shadowsocks_xjtu: {},
        kms: {},
        jetbrains: {},
      },
    }
  },
  computed: {
    porxyGfwCheck () {
      return this.results['shadowsocks_gfw']
    },
    proxyXjtuCheck () {
      return this.results['shadowsocks_xjtu']
    },
    kmsCheck () {
      return this.results['kms']
    },
    jetbrainsCheck () {
      return this.results['jetbrains']
    },
  },
  created () {
    this.healthCheck()
  },
  methods: {
    healthCheck () {
      this.loading = true
      this.$axios.get(
        '/api/benifit/healthcheck', {
          headers: {},
        }
      ).then((response) => {
        this.results = response.data.data
        this.loading = false
      })
    },
  },
}
</script>
