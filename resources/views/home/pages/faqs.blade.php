@extends('home.layouts.jumbotron')

@section('title')
{{trans('faq.title')}}
@endsection


@section('main.title')
    <h1>{{trans('faq.FAQ')}}</h1>
    <h4>{{trans('faq.Asked')}}</h4>
    <p>{{trans('faq.line_1')}}</p>
    <p><a class="btn btn-primary" href="/{{App::getLocale()}}/user/order/create" role="button">
        <i class="fa fa-edit"></i> {{trans('faq.line_2')}}
    </a></p>
@endsection


@section('main.content')
<div class="content-wrapper">
    {{-- <h2><i class="fa fa-list"></i> {{trans('faq.index')}}</h2>
    <ul class="list-group">
        @foreach($faq_categories as $faq_category)
        <a href="#category-{{ $faq_category->id }}">
            <li class="list-group-item">
                <i class="fa fa-link"></i>
                {{trans('index.faq_category')}}
            </li>
        </a>
        @endforeach
    </ul> --}}

    @foreach($faq_categories as $faq_category)
        <section class="content-wrapper link-black">
            <a href="#category-{{ $faq_category->id }}">
                <h3 id="category-{{ $faq_category->id }}">
                    <i class="fa fa-link"></i> 
                    @if (App::getLocale() == "zh")
                        {{ $faq_category->name }}
                    @else
                        {{ $faq_category->name_en }}
                    @endif
                </h3>
            </a>
            <hr>
            <div class="panel-group" id="group-{{ $faq_category->name }}" role="tablist" aria-multiselectable="false">
                @foreach($faq_category->faqs as $faq)
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="question-{{ $faq->id }}">
                        <a role="button" 
                            data-toggle="collapse" 
                            data-parent="#group-{{ $faq_category->name }}" 
                            href="#answer-{{ $faq->id }}" 
                            aria-expanded="false" 
                            aria-controls="answer-{{ $faq->id }}">
                            
                                <h4 class="panel-title">
                                    <i class="fa fa-question-circle-o"></i>
                                    @if (App::getLocale() == "zh")
                                        {{ $faq->question }}
                                    @else
                                        {{ $faq->question_en }}
                                    @endif
                                </h4>
                            
                        </a>
                        </div>
                        <div id="answer-{{ $faq->id }}" class="panel-collapse collapse" role="tabpanel"
                            aria-labelledby="question-{{ $faq->id }}">
                            <div class="panel-body">
                            @if (App::getLocale() == "zh")
                                <showdown>{{ $faq->answer }}</showdown>
                            @else
                                <showdown>{{ $faq->answer_en }}</showdown>
                            @endif
                            </div>
                            <div class="panel-footer text-right text-muted">
                                {{trans('faq.footer')}}{!! $faq->updated_at or $faq->created_at !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endforeach
</div>
@endsection