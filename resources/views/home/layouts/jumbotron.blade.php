@extends('home.layouts.master')

@section('main')
<div class="jumbotron" id="page-header">
    <div class="container text-center">
        @section('main.title')
        
        @show
    </div>
</div>
<div class="main-container">
    @section('main.content')
        
    @show
</div>
@endsection
