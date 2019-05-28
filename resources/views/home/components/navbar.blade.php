<nav class="navbar navbar-default" style="min-height:20px;height:20px;font-size: 12px;background-color:rgb(33, 100, 147)">
  <div class="container">
    <p class="navbar-text navbar-right" style="margin-top: 0px;margin-bottom: 0px;">
        @if(App::getLocale() == "en")
            <a class="navbar-link" href="{{ str_ireplace(['/en','/zh'],'/zh',request()->getRequestUri())}}" onclick='setzh()' data-value="zh">中文版</a>
        @else
            <a class="navbar-link" href="{{ str_ireplace(['/zh','/en'],'/en',request()->getRequestUri())}}" onclick='seten()' data-value="en">English Version</a>
        @endif
        </p>
  </div>
</nav>

<nav class="navbar navbar-default">
    <div class="container">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/{{App::getLocale()}}/">
                <img class="logo" src="{{ asset('static/img/logo_nav.png') }}" alt="">
            </a>
            
        </div>

        {{-- 小屏幕时下拉菜单开始 --}}
        <div class="collapse navbar-collapse text-center" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li id="navbar-home"><a class="hvr-underline-from-center" href="/{{App::getLocale()}}/">{{trans('navbar.home')}}</a></li>
                <li id="navbar-faqs"><a class="hvr-underline-from-center" href="/{{App::getLocale()}}/faqs">{{trans('navbar.faq')}}</a></li>
                <li id="navbar-order-create"><a class="hvr-underline-from-center" href="/{{App::getLocale()}}/user/order/create">{{trans('navbar.support')}}</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                @if(Auth::check())
                    @if(Auth::user()->isMember())
                        <li><a class="hvr-underline-from-center" href="/dashboard/home">
                            <i class="fa fa-fw fa-dashboard"></i>
                            {{trans('navbar.dashboard')}}
                        </a></li>
                    @endif
                    <li><a class="hvr-underline-from-center" href="/{{App::getLocale()}}/user/">
                        <i class="fa fa-fw fa-user"></i>
                        {{ Auth::id() }}
                    </a></li>
                    <li><a class="hvr-underline-from-center" href="/logout">
                        <i class="fa fa-fw fa-sign-out"></i>
                        {{trans('navbar.logout')}}
                    </a></li>
                @else
                    <li><a class="hvr-underline-from-center" href="/login">
                        <i class="fa fa-fw fa-sign-in"></i>
                        {{trans('navbar.login')}}
                    </a></li>
                @endif
                
            </ul>
        </div>
        {{-- 小屏幕时下拉菜单结束 --}}
          
    </div>
</nav>

@if(true === env('XJTUANA_GLOBAL_ALERT_ENABLE'))
<div class="alert alert-warning alert-dismissible" style="margin: 0;">
    <div class="container">
        <span>{{ env('XJTUANA_GLOBAL_ALERT_NOTICE') }}</span>
    </div>
</div>
@endif


@section('script')
<script>
function setzh()
{
    window.localStorage.setItem('locale', 'zh');
    return true;
}
function seten()
{
    window.localStorage.setItem('locale', 'en');
    return true;
}
</script>
@endsection