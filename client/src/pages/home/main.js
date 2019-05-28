import Vue from 'vue'
import VueI18n from 'vue-i18n'
import '@/assets/styles/home/app.scss'
import '@/assets/styles/vendor/font-awesome.scss'
import '@/assets/styles/vendor/sweetalert2.scss'

import CommonModal from './components/common/Modal.vue'
import CommonOverlay from './components/common/Overlay.vue'
import Showdown from './components/common/Showdown.vue'
import UserOrderShow from './components/user/order/Show.vue'
import UserButton from './components/user/UserButton.vue'
import IndexSwiper from './components/index/IndexSwiper.vue'
import IndexLinks from './components/index/IndexLinks.vue'

require('./bootstrap')
require('bootstrap-validator')
Vue.use(VueI18n)

Vue.component('CommonModal', CommonModal)
Vue.component('CommonOverlay', CommonOverlay)
Vue.component('Showdown', Showdown)
Vue.component('UserOrderShow', UserOrderShow)
Vue.component('UserButton', UserButton)
Vue.component('IndexSwiper', IndexSwiper)
Vue.component('IndexLinks', IndexLinks)

const i18n = new VueI18n({
  locale: 'zh',
  messages: {
    'zh': require('./components/common/lang/zh'),
    'en': require('./components/common/lang/en'),
  },
})

/* eslint-disable no-new */
new Vue({
  i18n,
//   el: '#app',
}).$mount('#app')
