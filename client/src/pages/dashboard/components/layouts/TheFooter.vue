<template>
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>西交网管会 控制台 Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2014-{{year}} <a href="https://ana.xjtu.edu.cn">XJTUANA</a> 研发部.</strong>
  </footer>
</template>

<script>
export default {
  name: 'TheFooter',
  data () {
    return {
      year: 2018,
      data: new Date(),
    }
  },
  created () {
    this.data = new Date()
    this.year = this.data.getFullYear()
  },
  methods: {
  },
}
</script>

<style lang="scss" type="text/css" scoped>

</style>
