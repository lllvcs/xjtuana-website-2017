import axios from '@/plugins/axios'
import swal from '@/plugins/swal'

const errMsgMap = new Map([
  [401, ['登录超时', '请重新登录！']],
  [403, ['无权操作', '权限不足，无法进行该操作！']],
  [500, ['服务器错误', '服务器错误，请联系管理员！']],
  [504, ['响应超时', '服务器响应超时，可能是学校的接口又挂了！']],
])

axios.interceptors.response.use(response => {
  return response
}, error => {
  let errcode = error.response.status
  let [title, text] = errMsgMap.has(errcode) ? errMsgMap.get(errcode) : ['出错啦', `Code ${errcode}`]
  swal({
    title,
    text,
    type: 'error',
  }).then(() => {
    if (errcode === 401) {
      window.location.href = '/dashboard'
    }
  })
  return Promise.reject(error)
})

export default axios
