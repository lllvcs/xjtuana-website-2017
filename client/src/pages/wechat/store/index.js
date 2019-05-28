import Vue from 'vue'
import Vuex from 'vuex'
import axios from '@/plugins/axios'

import orderModule from './order'

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    order: orderModule,
  },
  state: {
    initialized: false,
    user: null,
    campuses: null,
    services: null,
  },
  mutations: {
    initialized (state) {
      state.initialized = true
    },
    setUser (state, payload) {
      state.user = payload
    },
    setCampuses (state, payload) {
      state.campuses = payload
    },
    setServices (state, payload) {
      state.services = payload
    },
  },
  actions: {
    async init ({ dispatch, commit }) {
      await Promise.all([
        dispatch('getUser'),
        dispatch('getCampuses'),
        dispatch('getServices'),
      ])
      commit('initialized')
    },
    async getUser ({ commit }) {
      let response = await axios.get('/api/user')
      commit('setUser', response.data.data)
    },
    async getCampuses ({ commit }) {
      let response = await axios.get('/api/campus')
      commit('setCampuses', response.data.data)
    },
    async getServices ({ commit }) {
      let response = await axios.get('/api/order_service')
      commit('setServices', response.data.data)
    },
  },
  getters: {
  },
})
