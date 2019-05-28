<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>西交网管会 - 控制台</title>
  
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <meta name="csrf-token" content="{{ csrf_token() }}">
    
  <link rel="stylesheet" href="{{ asset('css/vendor/font-awesome.css') }}">
  <link rel="stylesheet" href="{{ asset('css/vendor/sweetalert2.css') }}">
  <link rel="stylesheet" href="{{ asset('css/vendor/bootstrap.css') }}">
  <link rel="stylesheet" href="{{ asset('css/vendor/bootstrap-daterangepicker.css') }}">
  <link rel="stylesheet" href="{{ asset('css/vendor/admin-lte.css') }}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
      <script src="//cdn.bootcss.com/html5shiv/r29/html5.min.js"></script>
      <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
  <div id="dashboard"></div>
  <script src="{{ asset('js/vendor/jquery.js') }}"></script>
  @if(false === env('APP_DEBUG'))
      <script src="{{ asset('js/vendor/vue.js') }}"></script>
  @else
      <script src="https://cdn.bootcss.com/vue/2.5.13/vue.js"></script>
  @endif
  <script src="{{ asset('js/vendor/vuex.js') }}"></script>
  <script src="{{ asset('js/vendor/vue-router.js') }}"></script>
  <script src="{{ asset('js/vendor/axios.js') }}"></script>
  <script src="{{ asset('js/vendor/moment.js') }}"></script>
  <script src="{{ asset('js/vendor/echarts.js') }}"></script>
  <script src="{{ asset('js/vendor/sweetalert2.js') }}"></script>
  <script src="{{ asset('js/dashboard/manifest.js') }}"></script>
  <script src="{{ asset('js/dashboard/vendor.js') }}"></script>
  <script src="{{ asset('js/dashboard/app.js') }}"></script>
</body>
</html>
