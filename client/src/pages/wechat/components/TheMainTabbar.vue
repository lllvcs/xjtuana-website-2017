<template>
  <WeuiTabbar>
    <router-link
      :to="{ name: 'order.index' }"
      tag="weui-tabbar-item"
      active-class="weui-bar__item_on"
    >
      <WeuiTabbarIcon>
        <i class="fa fa-file-text-o"></i>
      </WeuiTabbarIcon>

      <WeuiTabbarLabel>报修记录</WeuiTabbarLabel>
    </router-link>

    <router-link
      :to="{ name: 'order.create' }"
      tag="weui-tabbar-item"
      active-class="weui-bar__item_on"
      exact
    >
      <WeuiTabbarIcon>
        <i class="fa fa-wrench"></i>
      </WeuiTabbarIcon>

      <WeuiTabbarLabel>新建报修</WeuiTabbarLabel>
    </router-link>

    <router-link
      :to="{ name: 'user' }"
      tag="weui-tabbar-item"
      active-class="weui-bar__item_on"
      exact
    >
      <WeuiTabbarIcon>
        <i class="fa fa-user"></i>
      </WeuiTabbarIcon>

      <WeuiTabbarLabel>我</WeuiTabbarLabel>
    </router-link>
  </WeuiTabbar>
</template>

<script>
export default {
}
</script>
