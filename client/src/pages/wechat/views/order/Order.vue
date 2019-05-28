<template>
  <router-view v-if="my_orders"/>
</template>

<script>
import { mapState, mapActions } from 'vuex'

export default {
  computed: {
    ...mapState('order', [
      'my_orders',
    ]),
  },
  data () {
    return {
    }
  },
  methods: {
    ...mapActions('order', [
      'getMyOrders',
    ]),
  },
  created () {
    this.getMyOrders()
  },
}
</script>
