@extends('home.layouts.jumbotron')

@section('title')
{{trans('userHome.title')}}
@endsection

@section('main.title')
    <h1><i class="fa fa-user-o"></i></h1>
    <h2>{{trans('userHome.head')}}</h2>
    <h4>User Center</h4>
@endsection

@section('main.content')
<div class="content-wrapper">
    <div class="col-sm-9">
        <div class="content-wrapper">
            <h3><i class="fa fa-gears"></i> {{trans('userHome.function_1')}}</h3>
            <hr>
            <div class="row">
                <div class="col-md-3 col-sm-4">
                    <user-button fa="fa-bell-o" text="{{trans('userHome.function_2')}}" href="/{{App::getLocale()}}/user/order/create"></user-button>
                </div>
                <div class="col-md-3 col-sm-4">
                    <user-button fa="fa-file-text-o" text="{{trans('userHome.function_3')}}" href="/{{App::getLocale()}}/user/order/"></user-button>
                </div>
                <div class="col-md-3 col-sm-4">
                    <user-button fa="fa-edit" text="{{trans('userHome.function_4')}}" href="{{ route('join-us.home') }}"></user-button>
                </div>
            </div>
        </div>

        <div class="content-wrapper">
            <h3><i class="fa fa-list"></i> {{trans('userHome.support_1')}}</h3>
            <hr>
            <table class="table table-hover table-striped text-center">
                @if($recent_orders->isEmpty())
                    <tr>
                        <td>{{trans('userHome.support_2')}}</td>
                    </tr>
                @else
                    <thead>
                        <tr>
                            <th class="hidden-xs">{{trans('userHome.support_3')}}</th>
                            <th>{{trans('userHome.support_4')}}</th>
                            <th>{{trans('userHome.support_5')}}</th>
                            <th>{{trans('userHome.support_6')}}</th>
                            <th>{{trans('userHome.support_7')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recent_orders as $order)
                            <tr>
                                <td class="hidden-xs">{{ $order->id }}</td>
                                <td><span>{{ substr($order->created_at, 0, 10) }}</span><span class="hidden-xs">{{ substr($order->created_at, 10) }}</span></td>
                                <td>
                                @if (App::getLocale() == "zh")
                                    {{ $order->building->name.'-'.$order->room }}
                                @else
                                    {{ $order->building->name_en.'-'.$order->room }}
                                @endif
                                </td>
                                <td>
                                @if (App::getLocale() == "zh")
                                    {{ $order->status->name }}
                                @else
                                    {{ $order->status->name_en }}
                                @endif
                                </td>
                                <td><a href="/{{App::getLocale()}}/user/order/{{$order->id}}">{{trans('userHome.show')}}</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                @endif
            </table>
        </div>

    </div>
    <div class="col-sm-3">
        <div class="content-wrapper">
            <h3><i class="fa fa-link"></i> {{trans('userHome.link')}}</h3>
            <hr>
            <ul class="nav nav-stacked">
                <li><a href="http://nethelp.xjtu.edu.cn/" target="_blank">校园网信息</a></li>
                <li><a href="http://mirrors.xjtu.edu.cn/" target="_blank">西交开源镜像站</a></li>
                <li><a href="https://www.tiaozhan.com/" target="_blank">挑战网</a></li>
                <li><a href="http://nanyangpt.com/" target="_blank">南洋PT</a></li>
                <li><a href="http://xjtu.so/" target="_blank">西交网络导航</a></li>
            </ul>
        </div>
    </div>
</div>
@endsection
