<template>
<div>
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box box-success">
          <div class="box-header with-border">
            <i class="fa fa-wechat"></i>
            <h3 class="box-title">微信服务号管理</h3>
          </div>
          <div class="box-body">
            <router-link :to="{ name: 'wechat.menu' }" class="btn btn-app"><i class="fa fa-list"></i>自定义菜单</router-link>
            <!--<router-link :to="{ name: 'wechat.template' }" class="btn btn-app"><i class="fa fa-commenting-o"></i>模板消息</router-link>-->
          </div>
        </div>

        <router-view></router-view>
      </div>
    </div>
  </section>
</div>
</template>

<script>
export default {

  components: {
  },

  data () {
    return {

    }
  },

  computed: {
  },

  watch: {
  },

  methods: {
    fetchData () {
      this.loading = true
      this.$axios.get('/api/member', {
        params: this.form,
      }).then((response) => {
        this.setEdit(null)
        this.results = response.data.data
        this.loading = false
      })
    },
    createMember () {
      this.$validator.validateAll('createForm')
      if (this.errors.any('createForm')) {
        return false
      }
      swal({
        title: '是否确认提交？',
        text: '添加社员操作会记入操作日志',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.post(
          '/api/member',
          this.createForm
        ),
      }).then(response => {
        return swal({
          title: response.data.status ? '操作失败！' : '操作成功！',
          text: response.data.message,
          type: response.data.status ? 'error' : 'success',
        })
      }).then(() => {
        this.fetchData()
      }).catch(swal.noop)
    },
  },

  created () {
    // this.fetchData()
  },
}
</script>

<style lang="scss" type="text/css" scoped>

</style>
