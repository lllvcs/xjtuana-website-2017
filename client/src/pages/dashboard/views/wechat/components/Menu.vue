<template>
<div>
  <div class="box box-primary">

    <div class="box-header with-border">
      <i class="fa fa-list"></i>

      <h3 class="box-title">自定义菜单</h3>

    </div>

    <div class="box-body">
      <table class="table table-striped table-bordered table-hover text-center" style="word-break: break-all;">
        <thead>
          <tr>
            <th>名称</th>
            <th>URL</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>微信绑定NETID</td>
            <td>{{ url.bind }}</td>
          </tr>
          <tr>
            <td>微信解绑NETID</td>
            <td>{{ url.unbind }}</td>
          </tr>
          <tr>
            <td>微信报修</td>
            <td>{{ url.order }}</td>
          </tr>
          <tr>
            <td>网管控制台</td>
            <td>{{ url.dashboard }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!--<div class="box-footer text-center">-->
    <!--  <button class="btn btn-success" @click="updateMenu"><i class="fa fa-save"></i> 保存</button>-->
    <!--</div>-->

  </div>
</div>
</template>

<script>
export default {
  data () {
    return {
      button: {
      },
      url: {
        bind: null,
        unbind: null,
        order: null,
        dashboard: null,
      },
    }
  },
  methods: {
    fetchUrls () {
      this.fetchBindUrl()
      this.fetchUnbindUrl()
      this.fetchOrderUrl()
      this.fetchDashboardUrl()
    },
    async fetchBindUrl () {
      let response = await this.$axios.get('/wechat/api/url/bind')
      this.url.bind = response.data.data
    },
    async fetchUnbindUrl () {
      let response = await this.$axios.get('/wechat/api/url/unbind')
      this.url.unbind = response.data.data
    },
    async fetchOrderUrl () {
      let response = await this.$axios.get('/wechat/api/url/order')
      this.url.order = response.data.data
    },
    async fetchDashboardUrl () {
      let response = await this.$axios.get('/wechat/api/url/dashboard')
      this.url.dashboard = response.data.data
    },
    // updateMenu () {
    //   swal({
    //     title: '是否确认保存？',
    //     text: '保存后需要一段时间刷新显示',
    //     type: 'info',
    //     showCancelButton: true,
    //     showLoaderOnConfirm: true,
    //     preConfirm: () => this.$axios.post(
    //       '/wechat/api/menu',
    //       this.button
    //     )
    //   }).then( response => {
    //     return swal({
    //       title: response.data.status ? '操作失败！' : '操作成功！',
    //       text: response.data.message,
    //       type: response.data.status ? 'error' : 'success',
    //     })
    //   }).then( () => {

    //   }).catch(swal.noop)
    // },
  },
  created () {
    this.fetchUrls()
  },
}
</script>

<style lang="scss" type="text/css" scoped>

</style>
