@extends('wechat.layouts.master')

@section('title', '解除绑定NetID')

@section('body')
<div class="weui-msg">
  <!--顶部图标-->
  <div class="weui-msg__icon-area">
    <i class="weui-icon-warn weui-icon_msg"></i>
  </div>
  
  <!--文字提示-->
  <div class="weui-msg__text-area">
      <h1 class="weui-msg__title">解除绑定NetID</h1>
      <p class="weui-msg__desc">NetID: {{ $user['netid'] or '' }}</p>
      <p class="weui-msg__desc">姓名: {{ $user['name'] or '' }}</p>
  </div>
  
  <!--操作按钮-->
  <div class="weui-msg__opr-area">
      <form class="weui-btn-area" action="{{ route('wechat.unbind.confirm') }}" method="post">
          {{ csrf_field() }}
          <button type="submit" class="weui-btn weui-btn_primary">解除绑定</button>
      </form>
  </div>
  
  <!--文字提示-->
  <div class="weui-msg__text-area">
      <p class="weui-msg__desc">解除绑定后，通过NetID登录仍可正常使用网管会提供的功能，但无法收到相应服务的微信通知。</p>
  </div>
  
  <!--底部链接-->
  <div class="weui-msg__extra-area">
      <div class="weui-footer">
          <p class="weui-footer__links">
              <a href="https://ana.xjtu.edu.cn" class="weui-footer__link">西交网管会</a>
          </p>
          <p class="weui-footer__text">Copyright &copy; 2014-2017 XJTUANA</p>
      </div>
  </div>
</div>
@endsection
