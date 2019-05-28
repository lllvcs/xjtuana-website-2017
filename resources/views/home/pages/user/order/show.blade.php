@extends('home.layouts.jumbotron')

@section('title')
{{trans('show.title')}}
@endsection


@section('main.title')
    <h1><i class="fa fa-file-text-o"></i></h1>
    <h2>{{trans('show.head')}}</h2>
    <h4><strong>Support Details</strong></h4>
@endsection

@section('main.content')
    <user-order-show :order="{{ json_encode($order) }}"></user-order-show> 
@endsection