import axios from '@/plugins/axios'
import weui from './weui'

const errMsgMap = new Map([
  [401, '登录超时，请重新登录！'],
  [403, '权限不足，无法进行该操作！'],
  [404, '请求的资源不存在！'],
  [500, '服务器错误，请联系统管理员处理！'],
  [504, '响应超时，请稍后再试！'],
])
let weuiLoading

axios.interceptors.request.use(config => {
  weuiLoading = weui.loading('处理中...')
  return config
}, error => {
  weui.topTips('出错了！')
  weuiLoading.hide()
  return Promise.reject(error)
})

axios.interceptors.response.use(response => {
  weuiLoading.hide()
  return response
}, error => {
  let errcode = error.response.status
  let tips = errMsgMap.has(errcode) ? errMsgMap.get(errcode) : `服务器错误，请稍后再试！${errcode}`
  weui.topTips(tips)
  weuiLoading.hide()
  return Promise.reject(error)
})

export default axios
