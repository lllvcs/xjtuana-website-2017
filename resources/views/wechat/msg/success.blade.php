@extends('wechat.layouts.master')

@section('title', '西交网管会')

@section('body')
<div class="weui-msg">
  <!--顶部图标-->
  <div class="weui-msg__icon-area icon_msg ">
    <i class="weui-icon-success weui-icon_msg"></i>
  </div>
  
  <!--文字提示-->
  <div class="weui-msg__text-area">
    <h1 class="weui-msg__title">{{ $title or '成功' }}</h1>
    <p class="weui-msg__desc">{{ $message or '请求成功' }}</p>
  </div>
  
  <!--操作按钮-->
  <div class="weui-msg__opr-area">
    <p class="weui-btn-area">
      @if(isset($showButton) && $showButton)
      <a href="javascript:history.back();" class="weui-btn weui-btn_default">返回</a>
      @endif
    </p>
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
