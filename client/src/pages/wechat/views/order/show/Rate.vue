<template>
<div>
  <form id="form">
    <WeuiCellsTitle>
      <i class="fa fa-star"></i>
      报修评价
    </WeuiCellsTitle>

    <WeuiCells>
      <WeuiSelect
        v-model="form.score"
        place="after"
        required
        emptyTips="请选择评分"
      >
        <label
          slot="before"
          class="weui-label"
        >
          网管评分
        </label>

        <option
          v-for="n in 5"
          :value="n"
          :key="n"
        >
          <span
            v-for="i in n"
            :key="i"
          >
            ★
          </span>
        </option>
      </WeuiSelect>
    </WeuiCells>

    <WeuiCellsTitle>
      <i class="fa fa-file-text"></i>
      评价详情
    </WeuiCellsTitle>

    <WeuiCells type="form">
      <WeuiTextarea
        v-model="form.detail"
        placeholder="请输入对本次网络维修服务的评价。您的评价是我们改进服务和考核网管的重要参考，谢谢您的支持！"
        emptyTips="请输入评价详情"
        rows="6"
        required
      />
    </WeuiCells>
  </form>
  <WeuiButtonArea>
    <WeuiButton type="primary" @click="onSubmit">提交评价</WeuiButton>
  </WeuiButtonArea>
</div>
</template>

<script>
export default {
  data () {
    return {
      form: {
        score: 5,
        detail: null,
      },
    }
  },
  mounted () {
    this.$weui.form.checkIfBlur('#form')
  },
  methods: {
    onSubmit () {
      this.$weui.form.validate('#form', error => {
        if (error === null) {
          let dialog = this.$weui.dialog({
            title: '是否确认提交？',
            content: '您的评价是我们改进服务和考核网管的重要参考',
            buttons: [{
              label: '取消',
              type: 'default',
              onClick: () => null,
            }, {
              label: '确定',
              type: 'primary',
              onClick: () => { dialog.hide(this.rateOrder) },
            }],
          })
        }
      })
    },
    async rateOrder () {
      let response = await this.$axios.post(
        `/api/order/${this.$route.params.id}/rate`,
        this.form
      )
      if (response.data.status) {
        this.$weui.alert(response.data.message)
      } else {
        this.$weui.toast(response.data.message, () => {
          this.$emit('rate')
        })
      }
    },
  },
}
</script>
