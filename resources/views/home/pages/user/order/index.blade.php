@extends('home.layouts.jumbotron')

@section('title')
{{trans('userIndex.title')}}
@endsection


@section('main.title')
    <h1><i class="fa fa-history"></i></h1>
    <h2>{{trans('userIndex.head')}}</h2>
    <h4>Support History</h4>
@endsection

@section('main.content')
<div class="content-wrapper">
    <div class="content-wrapper">
        <h3><i class="fa fa-list"></i> {{trans('userIndex.history')}}</h3>
        <hr>
        <table class="table table-hover table-striped text-center">
            @if($orders->isEmpty())
                <tr>
                    <td>{{trans('userIndex.isEmpty')}}</td>
                </tr>
            @else
                <thead>
                    <tr>
                        <th class="hidden-xs">{{trans('userIndex.line_1')}}</th>
                        <th>{{trans('userIndex.line_2')}}</th>
                        <th>{{trans('userIndex.line_3')}}</th>
                        <th>{{trans('userIndex.line_4')}}</th>
                        <th class="hidden-xs">{{trans('userIndex.line_5')}}</th>
                        <th>{{trans('userIndex.line_6')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                        @if (App::getLocale() == "zh")
                            <td class="hidden-xs">{{ $order->id }}</td>
                            <td>{{ $order->created_at }}</td>
                            <td>{{ $order->building->name.'-'.$order->room }}</td>
                            <td>{{ $order->status->name }}</td>
                            <td class="hidden-xs">{{ $order->member ? split_name($order->member->user->profile->name)['lastname'].'同学' : '暂无' }}</td>
                            <td><a href="/{{App::getLocale()}}/user/order/{{$order->id}}">{{trans('userIndex.line_7')}}</a></td>
                        @else
                            <td class="hidden-xs">{{ $order->id }}</td>
                            <td>{{ $order->created_at }}</td>
                            <td>{{ $order->building->name_en.'-'.$order->room }}</td>
                            <td>{{ $order->status->name_en }}</td>
                            <td class="hidden-xs">{{ $order->member ? "Mr ".split_name($order->member->user->profile->name)['lastname'] : 'Null' }}</td>
                            <td><a href="/{{App::getLocale()}}/user/order/{{$order->id}}">{{trans('userIndex.line_7')}}</a></td>
                        @endif
                        </tr>
                    @endforeach
                </tbody>
            @endif
        </table>
    </div>
</div>
@endsection