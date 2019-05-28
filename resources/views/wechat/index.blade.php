@extends('wechat.layouts.master')

@section('body')
<div id="app"></div>
@endsection

@section('script')
<script src="{{ asset('js/vendor/axios.js') }}"></script>
@if(false === env('APP_DEBUG'))
    <script src="{{ asset('js/vendor/vue.js') }}"></script>
@else
    <script src="https://cdn.bootcss.com/vue/2.5.13/vue.js"></script>
@endif
<script src="{{ asset('js/vendor/vuex.js') }}"></script>
<script src="{{ asset('js/vendor/vue-router.js') }}"></script>
<script src="{{ asset('js/vendor/moment.js') }}"></script>
<script src="{{ asset('js/wechat/app.js') }}"></script>
@endsection