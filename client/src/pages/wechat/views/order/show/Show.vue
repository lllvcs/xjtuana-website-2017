<template>
<div v-if="order">
  <WeuiPreview>
    <!--报修单状态-->
    <WeuiPreviewHeader>
      <WeuiPreviewLabel>报修单状态</WeuiPreviewLabel>
      <WeuiPreviewValue>{{ order.status.name }}</WeuiPreviewValue>
    </WeuiPreviewHeader>

    <!--报修时间-->
    <WeuiPreviewBody>
      <p>
        <WeuiPreviewLabel>报修单号</WeuiPreviewLabel>
        <WeuiPreviewValue>{{ order.id }}</WeuiPreviewValue>
      </p>
      <p>
        <WeuiPreviewLabel>报修时间</WeuiPreviewLabel>
        <WeuiPreviewValue>{{ order.created_at }}</WeuiPreviewValue>
      </p>
      <p v-if="order.received_at">
        <WeuiPreviewLabel>接单时间</WeuiPreviewLabel>
        <WeuiPreviewValue>{{ order.received_at }}</WeuiPreviewValue>
      </p>
      <p v-if="order.completed_at">
        <WeuiPreviewLabel>完成时间</WeuiPreviewLabel>
        <WeuiPreviewValue>{{ order.completed_at }}</WeuiPreviewValue>
      </p>
      <p v-if="order.feedback_user">
        <WeuiPreviewLabel>评价时间</WeuiPreviewLabel>
        <WeuiPreviewValue>{{ order.feedback_user.created_at }}</WeuiPreviewValue>
      </p>
      <p v-if="order.deleted_at">
        <WeuiPreviewLabel>删除/撤销时间</WeuiPreviewLabel>
        <WeuiPreviewValue>{{ order.deleted_at }}</WeuiPreviewValue>
      </p>
    </WeuiPreviewBody>

    <!--报修详情-->
    <WeuiPreviewBody>
      <p>
        <WeuiPreviewLabel>宿舍楼</WeuiPreviewLabel>
        <WeuiPreviewValue>{{ order.building.name }}</WeuiPreviewValue>
      </p>
      <p>
        <WeuiPreviewLabel>房间号</WeuiPreviewLabel>
        <WeuiPreviewValue>{{ order.room }}</WeuiPreviewValue>
      </p>
      <p>
        <WeuiPreviewLabel>故障描述</WeuiPreviewLabel>
        <WeuiPreviewValue>{{ order.detail }}</WeuiPreviewValue>
      </p>
      <p v-if="order.service">
        <WeuiPreviewLabel>优先处理方式</WeuiPreviewLabel>
        <WeuiPreviewValue>{{ order.service.name }}</WeuiPreviewValue>
      </p>
    </WeuiPreviewBody>

    <!--联系方式-->
    <WeuiPreviewBody>
      <p>
        <WeuiPreviewLabel>手机号</WeuiPreviewLabel>
        <WeuiPreviewValue>{{ order.mobile }}</WeuiPreviewValue>
      </p>
      <p v-if="order.qq">
        <WeuiPreviewLabel>QQ号</WeuiPreviewLabel>
        <WeuiPreviewValue>{{ order.qq }}</WeuiPreviewValue>
      </p>
      <p v-if="order.wechat">
        <WeuiPreviewLabel>微信号</WeuiPreviewLabel>
        <WeuiPreviewValue>{{ order.wechat }}</WeuiPreviewValue>
      </p>
    </WeuiPreviewBody>

    <!--网管报告-->
    <WeuiPreviewBody v-if="order.feedback_member">
      <p>
        <WeuiPreviewLabel>网管报告</WeuiPreviewLabel>
        <WeuiPreviewValue>{{ order.feedback_member.detail }}</WeuiPreviewValue>
      </p>
    </WeuiPreviewBody>

    <!--用户评价-->
    <WeuiPreviewBody v-if="order.feedback_user">
      <p>
        <WeuiPreviewLabel>用户评分</WeuiPreviewLabel>
        <WeuiPreviewValue>{{ order.feedback_user.score }}</WeuiPreviewValue>
      </p>
      <p>
        <WeuiPreviewLabel>用户评价详情</WeuiPreviewLabel>
        <WeuiPreviewValue>{{ order.feedback_user.detail }}</WeuiPreviewValue>
      </p>
    </WeuiPreviewBody>

    <!--操作-->
    <WeuiPreviewFooter>
      <WeuiPreviewBtn
        v-if="canCancel"
        type="primary"
        @click="onCancel"
      >
        撤销
      </WeuiPreviewBtn>
      <WeuiPreviewBtn
        v-if="canRate"
        type="primary"
        @click="showRate = true"
      >
        评价
      </WeuiPreviewBtn>
      <WeuiPreviewBtn
        type="default"
        @click="$router.push({ name: 'order.index' })"
      >
        返回
      </WeuiPreviewBtn>
    </WeuiPreviewFooter>
  </WeuiPreview>

  <Rate v-if="showRate" @rate="fetchData" />
</div>
</template>

<script>
import { mapState } from 'vuex'
import Rate from './Rate'
export default {
  components: {
    Rate,
  },
  data () {
    return {
      order: null,
      showRate: false,
    }
  },
  computed: {
    ...mapState([
      'user',
    ]),
    canCancel () {
      return this.order.user.netid === this.user.netid && this.order.status_id === 1 && this.order.member === null
    },
    canRate () {
      return this.order.user.netid === this.user.netid && this.order.status_id === 3 && this.order.feedback_user === null
    },
  },
  created () {
    this.fetchData()
  },
  methods: {
    async fetchData () {
      let response = await this.$axios.get(`/api/order/${this.$route.params.id}`)
      this.order = response.data.data
      this.showRate = false
    },
    onCancel () {
      let dialog = this.$weui.dialog({
        title: '是否确认撤销？',
        content: '撤销报修后，30分钟内不能再次报修',
        buttons: [{
          label: '取消',
          type: 'default',
          onClick: () => null,
        }, {
          label: '确定',
          type: 'primary',
          onClick: () => { dialog.hide(this.cancelOrder) },
        }],
      })
    },
    async cancelOrder () {
      let response = await this.$axios.delete(`/api/order/${this.$route.params.id}/cancel`)
      if (response.data.status) {
        this.$weui.alert(response.data.message)
      } else {
        this.$weui.toast(response.data.message)
        this.fetchData()
      }
    },
  },
}
</script>
