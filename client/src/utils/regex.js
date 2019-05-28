const wechat = /^([a-zA-Z][a-zA-Z\d_-]{5,19})|(1[3578]\d{9})|([1-9]\d{4,9})$/
const qq = /^[1-9]\d{4,9}$/
const mobile = /^1[3-9]\d{9}$/

export default {
  mobile,
  qq,
  wechat,
}
