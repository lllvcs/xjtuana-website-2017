import Vue from 'vue'
import store from './store'
import router from './router'
import App from './App'
import swal from '@/plugins/swal'
import { axios, VeeValidate } from '@/pages/dashboard/plugins'

import '@/assets/styles/vendor/material-dashboard.scss'
import '@/assets/styles/vendor/font-awesome.scss'
import '@/assets/styles/vendor/sweetalert2.scss'
import '@/assets/styles/vendor/bootstrap.scss'
import '@/assets/styles/vendor/bootstrap-daterangepicker.scss'
import '@/assets/styles/vendor/admin-lte.scss'

window.$ = window.jQuery = require('jquery')
window.swal = swal
require('bootstrap-sass')
require('admin-lte')
require('bootstrap-daterangepicker')

Vue.prototype.$axios = axios
Vue.use(VeeValidate)

Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App),
})
