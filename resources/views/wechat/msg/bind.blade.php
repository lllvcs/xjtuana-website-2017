@extends('wechat.layouts.master')

@section('title', '微信绑定NetID')

@section('body')
<div class="weui-msg">
  <!--顶部图标-->
  <div class="weui-msg__icon-area">
    <img src="{{ $wechat['avator'] or '' }}" class="weui-icon_msg" width="93px" height="93px" style="border-radius: 50%">
    <p>{{ $wechat['nickname'] or '' }}</p>
  </div>
  
  <!--文字提示-->
  <div class="weui-msg__text-area">
    <h1 class="weui-msg__title">微信绑定NetID</h1>
    <p class="weui-msg__desc">NetID: {{ $user['netid'] or '' }}</p>
    <p class="weui-msg__desc">姓名: {{ $user['name'] or '' }}</p>
  </div>
  
  <!--操作按钮-->
  <div class="weui-msg__opr-area">
    <form class="weui-btn-area" action="{{ route('wechat.bind.confirm') }}" method="post">
        {{ csrf_field() }}
        <button type="submit" class="weui-btn weui-btn_primary">确认绑定</button>
    </form>
  </div>
  <!--文字提示-->
  <div class="weui-msg__text-area">
    <p class="weui-msg__desc">请核对你的NetID信息是否正确</p>
    <p class="weui-msg__desc">如果不是你的NetID，请<a href="{{ route('logout') }}">退出登录</a>然后重新绑定</p>
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
