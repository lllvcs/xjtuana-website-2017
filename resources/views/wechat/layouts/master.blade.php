<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
  <title>@yield('title', '西交网管会')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <link rel="stylesheet" href="{{ asset('css/vendor/font-awesome.css') }}">
  <link rel="stylesheet" href="{{ asset('css/vendor/weui.css') }}">

  @yield('head')
  
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    <script src="//cdn.bootcss.com/html5shiv/r29/html5.min.js"></script>
    <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
  @yield('body')
  @yield('script')
</body>
</html>