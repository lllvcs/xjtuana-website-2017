import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    initialized: false,
    user: null,
    tasks: null,

    order_statistics: null,

    departments: null,
    designations: null,
    order_status: null,
    order_services: null,
  },
  mutations: {
    initialized (state) {
      state.initialized = true
    },
    setUser (state, payload) {
      state.user = payload
    },
    setTasks (state, payload) {
      state.tasks = payload
    },
    setOrderStatistics (state, payload) {
      state.order_statistics = payload
    },
    setDepartments (state, payload) {
      state.departments = payload
    },
    setDesignations (state, payload) {
      state.designations = payload
    },
    setOrderStatus (state, payload) {
      state.order_status = payload
    },
    setOrderServices (state, payload) {
      state.order_services = payload
    },
  },
  actions: {
    // 初始化，获取全部所需数据
    init ({ commit, dispatch }) {
      return Promise.all([
        dispatch('getUser'),
        dispatch('getTasks'),
        dispatch('getOrderStatistics'),
        dispatch('getDepartments'),
        dispatch('getDesignations'),
        dispatch('getOrderStatus'),
        dispatch('getOrderServices'),
      ]).then(() => {
        commit('initialized')
      })
    },

    // 刷新，只刷新部分数据
    refresh ({ dispatch }) {
      dispatch('getUser')
      dispatch('getTasks')
      dispatch('getOrderStatistics')
      dispatch('getDepartments')
    },

    getUser ({ commit }) {
      return axios
        .get('/api/user')
        .then((response) => {
          commit('setUser', response.data.data)
        })
    },
    getTasks ({ commit }) {
      return axios
        .get('/api/user/task')
        .then((response) => {
          commit('setTasks', response.data.data)
        })
    },
    getOrderStatistics ({ commit }) {
      return axios
        .get('/api/order_statistics')
        .then((response) => {
          commit('setOrderStatistics', response.data.data)
        })
    },
    getDepartments ({ commit }) {
      return axios
        .get('/api/department')
        .then((response) => {
          commit('setDepartments', response.data.data)
        })
    },
    getDesignations ({ commit }) {
      return axios
        .get('/api/designation')
        .then((response) => {
          commit('setDesignations', response.data.data)
        })
    },
    getOrderStatus ({ commit }) {
      return axios
        .get('/api/order_status')
        .then((response) => {
          commit('setOrderStatus', response.data.data)
        })
    },
    getOrderServices ({ commit }) {
      return axios.get('/api/order_service')
        .then((response) => {
          commit('setOrderServices', response.data.data)
        })
    },
  },

  getters: {
    // 判断用户是否为部长团
    isManager: (state) => state.user ? state.user.member.designation_id < 5 : false,

    // 判断用户是否为社长团/研发部长
    isAdmin: (state, getter) => getter.isManager ? state.user.member.department_id === 1 || state.user.member.department_id === 2 : false,

    // 待办事项数量
    tasksCount: (state) => state.tasks ? state.tasks.todoOrders.length : 0,

    // 运维部
    departmentsOfService: (state) => state.departments === null ? [] : state.departments.filter(department => department.name.startsWith('运维')),

  },
})
