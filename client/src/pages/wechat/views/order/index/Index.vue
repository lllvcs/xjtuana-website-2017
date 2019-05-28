<template>
<div>
  <WeuiCellsTitle><i class="fa fa-history"></i> 报修历史</WeuiCellsTitle>

  <WeuiCells>
    <template v-if="my_orders.length > 0">
      <router-link
        v-for="order in my_orders"
        :key="order.id"
        :to="{ name: 'order.show', params: { id: order.id } }"
        tag="weui-cell"
      >
        <WeuiCellHeader>
          <WeuiIcon :type="orderIcon(order.status_id)" />
        </WeuiCellHeader>

        <WeuiCellBody>{{ $moment(order.created_at).format('YYYY年MM月DD日') }}</WeuiCellBody>

        <WeuiCellFooter>{{ order.building.name }}-{{ order.room }}</WeuiCellFooter>
      </router-link>
    </template>

    <WeuiCell v-else>
      <WeuiCellHeader>
        <i class="fa fa-circle-thin"></i>
      </WeuiCellHeader>

      <WeuiCellBody>暂无报修记录</WeuiCellBody>
    </WeuiCell>
  </WeuiCells>
</div>
</template>

<script>
import { mapState } from 'vuex'
export default {
  components: {
  },
  data () {
    return {
    }
  },
  computed: {
    ...mapState('order', [
      'my_orders',
    ]),
  },
  methods: {
    orderIcon (status) {
      let type = 'circle'
      switch (status) {
        case 1:
        case 2:
          type = 'waiting'
          break
        case 3:
          type = 'info'
          break
        case 4:
          type = 'success'
          break
        default:
          type = 'cancel'
      }
      return type
    },
  },
  created () {
  },
}
</script>
