@extends('home.layouts.master')

@section('title')
{{trans('index.title')}}
@endsection

@section('main')

<index-swiper></index-swiper>


<div class="main-container">
    <index-links></index-links>

    <div class="content-wrapper">
        <div class="content-wrapper" id="ana-intro">
            <h3><i class="fa fa-fw fa-file-text-o"></i> {{trans('index.Home')}}</h3>
            <hr>
            <div class="content-wrapper">
                <p> {{trans('index.introduction_1')}}</p>
                <p>{{trans('index.introduction_2_1')}}<a href="http://nic.xjtu.edu.cn">{{trans('index.introduction_2_2')}}</a>{{trans('index.introduction_2_3')}}</p>
                <p>{{trans('index.introduction_3_1')}}{{ $member_count }}{{trans('index.introduction_3_2')}}</p>
            </div>
        </div>

        <div class="content-wrapper" id="department-intro">
            <h3><i class="fa fa-fw fa-group"></i> {{trans('index.Department_1')}}</h3>
            <hr>

            <div class="content-wrapper">
                <h4><i class="fa fa-fw fa-wrench"></i> <strong>{{trans('index.Operation_1')}}</strong></h4>
                <p>{{trans('index.Operation_2')}}</p>
                <p>{{trans('index.Operation_3')}}</p>
                <p>{{trans('index.Operation_4')}}</p>
                <p>{{trans('index.Operation_5')}}</p>
                <p>{{trans('index.Operation_6')}}</p>
                <br>

                <h4><i class="fa fa-fw fa-lightbulb-o"></i> <strong>{{trans('index.R_1')}}</strong></h4>
                <p>{{trans('index.R_2')}}</p>
                <p>{{trans('index.R_3')}}</p>
                <p>{{trans('index.R_4')}}</p>
                <p>{{trans('index.R_5')}}</p>
                <br>

                <h4><i class="fa fa-fw fa-star-o"></i> <strong>{{trans('index.Propaganda_1')}}</strong></h4>
                <p>{{trans('index.Propaganda_2')}}</p>
                <p>{{trans('index.Propaganda_3')}}</p>
                <p>{{trans('index.Propaganda_4')}}</p>
                <p>{{trans('index.Propaganda_5')}}</p>
                <p>{{trans('index.Propaganda_6')}}</p>
                <p>{{trans('index.Propaganda_7')}}</p>

            </div>
        </div>

        <div class="content-wrapper" id="achievement-intro">
            <h3><i class="fa fa-fw fa-thumbs-up"></i> {{trans('index.achievement_1')}}</h3>
            <hr>
            
            <div class="content-wrapper">
                {{-- <h4><i class="fa fa-fw fa-bar-chart"></i> {{trans('index.achievement_2')}}</h4> --}}
                <h4><i class="fa fa-fw fa-bar-chart"></i> <strong>{{trans('index.achievement_3')}}</strong></h4>
                <p>{{trans('index.achievement_4')}}</p>
                <p>{{trans('index.achievement_5')}}</p>
                <p>{{trans('index.achievement_6')}}：{{ date('Y-m-d H:i:s', time()) }}</p>
                <p>{{trans('index.achievement_7')}}：{{ $order_count }}</p>

                <br>
                <h4><i class="fa fa-fw fa-refresh"></i> <strong>{{trans('index.R_1')}}</strong></h4>
                <p>{{trans('index.achievement_8')}}</p>
                <p>{{trans('index.achievement_9')}}</p>
            </div>
        </div>
    </div>
</div>

@endsection 






@section('script')
{{-- <script src="{{ asset('js/swiper.animate.min.js')}}"></script> --}}
{{-- <script src="//cdn.bootcss.com/particles.js/2.0.0/particles.min.js"></script> --}}
{{--  <script>
     $(function() {

    }) 
</script>  --}}
@endsection