<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <title>@yield('title', '出错啦') - XJTUANA</title>

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .code {
            font-size: 40px;
        }

        .message {
            font-size: 70px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="flex-center position-ref full-height">
       @section('main')
        
        <div class="top-right links">
            @section('top-links')
                <a href="/{{App::getLocale()}}/">返回首页</a>
            @show
        </div>
        

        <div class="content">
            <div class="message m-b-md">
                @section('message')
                    {{ $message or '非常抱歉' }}
                @show
            </div>

            <div class="links">
                @section('links')
                    <a href="/{{App::getLocale()}}/">社团主页</a>
                    <a href="javascript:history.go(-1);">返回上一页</a>
                @show
            </div>
        </div>
        @show
    </div>
</body>
</html>
