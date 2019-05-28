/**
 * Load Vue app
 */
import Vue from 'vue'
import store from './store'
import router from './router'
import App from './App'

import axios from '@/pages/wechat/plugins/axios'
import weui from '@/pages/wechat/plugins/weui'
import moment from '@/plugins/moment'
import swal from '@/plugins/swal'
import VueWeui from 'vue-weui-components'

import '@/assets/styles/vendor/font-awesome.scss'
import '@/assets/styles/vendor/weui.scss'

Vue.prototype.$axios = axios
Vue.prototype.$weui = weui
Vue.prototype.$moment = moment
Vue.use(VueWeui)

window.swal = swal

/* eslint-disable-next-line no-new */
new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App),
})
