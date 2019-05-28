import axios from '@/plugins/axios'

export default {
  namespaced: true,
  state: {
    my_orders: null,
  },
  mutations: {
    setMyOrders (state, payload) {
      state.my_orders = payload
    },
  },
  actions: {
    async getMyOrders ({ commit }) {
      let response = await axios.get('/api/order/mine')
      commit('setMyOrders', response.data.data)
    },
  },
  getters: {
  },
}
