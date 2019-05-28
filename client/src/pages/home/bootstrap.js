import axios from '@/plugins/axios'

try {
  window.$ = window.jQuery = require('jquery')

  require('bootstrap-sass')
} catch (e) {}

window.axios = axios

window.swal = require('sweetalert2')
window.swal.setDefaults({
  confirmButtonText: '确认',
  cancelButtonText: '取消',
})

window.moment = require('moment')
window.moment.locale('zh-cn')

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });
