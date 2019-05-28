<template>
<div>
  <PageHeader />

  <WeuiCellsTitle>
    <i class="fa fa-fw fa-user-circle"></i>
    个人信息
  </WeuiCellsTitle>

  <WeuiCells>
    <WeuiCell>
      <WeuiCellHeader>
        <span class="weui-label">
          姓名
        </span>
      </WeuiCellHeader>
      <WeuiCellBody>
        {{ user.profile.name }}
      </WeuiCellBody>
    </WeuiCell>

    <WeuiCell>
      <WeuiCellHeader>
        <span class="weui-label">
          学号
        </span>
      </WeuiCellHeader>
      <WeuiCellBody>
        {{ user.profile.stuid }}
      </WeuiCellBody>
    </WeuiCell>

    <WeuiCell>
      <WeuiCellHeader>
        <span class="weui-label">
          班级
        </span>
      </WeuiCellHeader>
      <WeuiCellBody>
        {{ user.profile.class }}
      </WeuiCellBody>
    </WeuiCell>
  </WeuiCells>

  <WeuiCellsTitle>
    <i class="fa fa-fw fa-mobile"></i>
    联系方式
  </WeuiCellsTitle>

  <WeuiCells>
    <WeuiCell>
      <WeuiCellHeader>
        <span class="weui-label">
          手机
        </span>
      </WeuiCellHeader>
      <WeuiCellBody>
        {{ user.profile.mobile }}
      </WeuiCellBody>
    </WeuiCell>

    <WeuiCell v-if="user.profile.qq">
      <WeuiCellHeader>
        <span class="weui-label">
          QQ
        </span>
      </WeuiCellHeader>
      <WeuiCellBody>
        {{ user.profile.qq }}
      </WeuiCellBody>
    </WeuiCell>

    <WeuiCell v-if="user.profile.wechat">
      <WeuiCellHeader>
        <span class="weui-label">
          微信
        </span>
      </WeuiCellHeader>
      <WeuiCellBody>
        {{ user.profile.wechat }}
      </WeuiCellBody>
    </WeuiCell>
  </WeuiCells>

</div>
</template>

<script>
import { mapState } from 'vuex'
import PageHeader from '@/pages/wechat/components/PageHeader'
export default {
  components: {
    PageHeader,
  },
  computed: {
    ...mapState([
      'user',
    ]),
  },
}
</script>

<style lang="scss" type="text/css" scoped>

</style>
