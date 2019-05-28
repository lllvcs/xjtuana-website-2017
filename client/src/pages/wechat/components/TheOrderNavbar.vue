<template>
  <WeuiNavbar>
    <router-link
      class="weui-navbar__item"
      :to="{ name: 'order' }"
      active-class="weui-bar__item_on"
      tag="div"
      exact
    >
      我的报修
    </router-link>

    <router-link
      class="weui-navbar__item"
      :to="{ name: 'order.create' }"
      active-class="weui-bar__item_on"
      tag="div"
      exact
    >
      新建报修
    </router-link>
  </WeuiNavbar>
</template>

<script>
export default {
}
</script>
