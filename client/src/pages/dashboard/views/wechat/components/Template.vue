<template>
<div>
  <div class="box box-primary">

    <div class="box-header with-border">
      <i class="fa fa-list"></i>

      <h3 class="box-title">模板消息</h3>

    </div>

    <div class="box-body">

    </div>

    <div class="box-footer text-center">
      <button class="btn btn-success" @click="sendTemplateMessage"><i class="fa fa-save"></i> 发送</button>
    </div>

  </div>
</div>
</template>

<script>
export default {
  components: {
  },
  data () {
    return {
      templateMessage: {
      },
    }
  },
  computed: {
  },
  watch: {
  },
  methods: {
    sendTemplateMessage () {
      swal({
        title: '是否确认发送？',
        text: '发送骚扰/广告可能导致被封号',
        type: 'info',
        showCancelButton: true,
        showLoaderOnConfirm: true,
        preConfirm: () => this.$axios.post(
          '/wechat/api/template/send',
          this.templateMessage
        ),
      }).then(response => {
        return swal({
          title: response.data.status ? '操作失败！' : '操作成功！',
          text: response.data.message,
          type: response.data.status ? 'error' : 'success',
        })
      }).then(() => {

      }).catch(swal.noop)
    },
  },
  created () {
  },
}
</script>

<style lang="scss" type="text/css" scoped>

</style>
