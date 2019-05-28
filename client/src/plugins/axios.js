import axios from 'axios'

axios.defaults.xsrfCookieName = 'XSRF-TOKEN'
axios.defaults.xsrfHeaderName = 'X-XSRF-TOKEN'
axios.defaults.headers.post['X-Requested-With'] = 'XMLHttpRequest'

export default axios
