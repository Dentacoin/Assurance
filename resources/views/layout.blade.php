<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <link rel="shortcut icon" href="{{URL::asset('assets/images/favicon.png') }}" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5" />
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    @if(!empty($meta_data))
        <title>{{$meta_data->title}}</title>
        <meta name="description" content="{{$meta_data->description}}" />
        <meta name="keywords" content="{{$meta_data->keywords}}" />
        <meta property="og:url" content="{{Request::url()}}"/>
        <meta property="og:title" content="{{$meta_data->social_title}}"/>
        <meta property="og:description" content="{{$meta_data->social_description}}"/>
        @if(!empty($meta_data->media))
            <meta property="og:image" content="{{URL::asset('assets/uploads/'.$meta_data->media->name)}}"/>
            <meta property="og:image:width" content="1200"/>
            <meta property="og:image:height" content="630"/>
        @endif
    @endif
    @if(!empty(Route::current()) && Route::current()->getName() == 'home')
        <link rel="canonical" href="{{route('home')}}" />
    @endif
    <style>

    </style>
    <link rel="stylesheet" type="text/css" href="/dist/css/front-libs-style.css">
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css">
    <script>
        var HOME_URL = '{{ route("home") }}';
    </script>
</head>
<body class="@if(!empty(Route::current())) {{Route::current()->getName()}} @else class-404 @endif @if(\App\Http\Controllers\UserController::instance()->checkSession()) logged-in @endif" @if(isset($gas_estimation)) data-current-gas-estimation="{{$gas_estimation}}" @endif>
<header>
    <div class="container">
        <div class="row fs-0">
            <figure itemscope="" itemtype="http://schema.org/Organization" class="col-xs-3 logo-container inline-block">
                <a itemprop="url" href="{{ route('home') }}" @if(!empty(Route::current())) @if(Route::current()->getName() == "home") tabindex="=-1" @endif @endif>
                    <img src="{{URL::asset('assets/images/logo.svg') }}" itemprop="logo" alt="Dentacoin logo" class="max-width-50 max-width-xs-40"/>
                </a>
            </figure>
            @if(!\App\Http\Controllers\UserController::instance()->checkSession())
                <nav class="col-xs-9 inline-block">
                    <ul itemscope="" itemtype="http://schema.org/SiteNavigationElement">
                        <li class="inline-block @if(!empty(Route::current())) @if(Route::current()->getName() == "home") active @endif @endif"><a href="{{route('home')}}" itemprop="url"><span itemprop="name">Dentists</span></a></li>
                        <li class="inline-block">|</li>
                        <li class="inline-block @if(!empty(Route::current())) @if(Route::current()->getName() == "patient-access") active @endif @endif"><a href="{{route('patient-access')}}" itemprop="url"><span itemprop="name">Patients</span></a></li>
                        <li class="inline-block">
                            <a href="javascript:void(0)" itemprop="url" class="blue-green-white-btn show-login-signin @if(!empty(Route::current())) @if(Route::current()->getName() == "home") dentist-side @endif @endif"><span itemprop="name">SIGN IN</span></a>
                        </li>
                    </ul>
                </nav>
            @else
                <div class="col-xs-9 inline-block text-right logged-user">
                    <a href="javascript:void(0)">
                        <span>{{(new \App\Http\Controllers\APIRequestsController())->getUserData(session('logged_user')['id'])->name}}</span>
                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                            @php($avatar_url = (new \App\Http\Controllers\APIRequestsController())->getUserData(session('logged_user')['id'])->avatar_url)
                            @if(!empty($avatar_url))
                                <img alt="" itemprop="contentUrl" src="{{$avatar_url}}"/>
                            @else
                                <img alt="" itemprop="contentUrl" src="/assets/images/avatar-icon.svg"/>
                            @endif
                        </figure>
                    </a>
                    <div class="hidden-box">
                        <div class="container-fluid text-center">
                            <div class="row">
                                <div class="col-xs-6 inline-block">
                                    <a href="{{ route('user-logout') }}" class="logout"><i class="fa fa-power-off" aria-hidden="true"></i> Log out</a>
                                </div>
                                <div class="col-xs-6 inline-block">
                                    <a href="{{ route('my-profile') }}" class="white-blue-green-btn">My profile</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</header>
<main>@yield("content")</main>
<footer class="padding-bottom-20">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <hr/>
            </div>
        </div>
        @if(!empty(Route::current()))
            @php($footer_menu = \App\Http\Controllers\Controller::instance()->getMenu('footer'))
        @endif
        @if(!empty($footer_menu) && sizeof($footer_menu) > 0)
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                    <nav class="row fs-0">
                        <ul itemscope="" itemtype="http://schema.org/SiteNavigationElement">
                            @foreach($footer_menu as $menu_el)
                                @if((isset($mobile) && $mobile && $menu_el->mobile_visible) || (isset($mobile) && !$mobile && $menu_el->desktop_visible))
                                    <li class="inline-block-top col-xs-4"><a @if($menu_el->new_window) target="_blank" @endif itemprop="url" href="{{$menu_el->url}}"><span itemprop="name">{!! $menu_el->name !!}</span></a></li>
                                @endif
                            @endforeach
                        </ul>
                    </nav>
                </div>
            </div>
        @endif
        <div class="row padding-bottom-50 padding-top-50 text-center fs-14 bottom-text">
            <div class="col-xs-12">© 2018 Dentacoin Foundation. All rights reserved.</div>
            <div class="col-xs-12">
                <a href="//dentacoin.com/assets/uploads/dentacoin-foundation.pdf" class="inline-block dark-color" target="_blank">Verify Dentacoin Foundation</a>
                <li class="inline-block separator padding-left-5 padding-right-5">|</li>
                <a href="//dentacoin.com/privacy-policy" target="_blank" class="inline-block dark-color">Privacy Policy</a>
            </div>
        </div>
    </div>
</footer>
{{--Show the sticky calculate button only for dentists--}}
@if(!empty(Route::current()) && Route::current()->getName() == 'home')
    <figure class="fixed-calculate-button" itemscope="" itemtype="http://schema.org/ImageObject">
        <a href="javascript:void(0);" class="open-calculator">
            <img alt="Sticky calculator button" itemprop="contentUrl" src="/assets/uploads/sticky-calculator-button.svg"/>
        </a>
    </figure>
@endif

<div class="response-layer">
    <div class="wrapper">
        <figure itemscope="" itemtype="http://schema.org/ImageObject">
            <img src="/assets/images/loader.gif" class="max-width-160" alt="Loader">
        </figure>
    </div>
</div>

@if(\App\Http\Controllers\UserController::instance()->checkSession())
    <div class="contract-response-layer">
        <div class="wrapper">
            <figure itemscope="" itemtype="http://schema.org/ImageObject">
                <img src="/assets/images/contract-assurance-loading.gif" class="max-width-440" alt="Loader">
            </figure>
        </div>
    </div>
    <div class="contract-response-success-layer">
        <div class="wrapper">
            <figure itemscope="" itemtype="http://schema.org/ImageObject">
                <img src="/assets/images/contract-assurance-loading-success.gif" class="max-width-440" alt="Loader">
            </figure>
        </div>
    </div>
@endif

{{--/Show the sticky calculate button only for dentists--}}
<script src="/assets/js/basic.js"></script>
<script src="/dist/js/front-libs-script.js?v=1.0.14"></script>
{{--<script src="/dist/js/front-script.js?v=1.0.13"></script>--}}
<script src="/assets/js/index-compiled.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCaVeHq_LOhQndssbmw-aDnlMwUG73yCdk&libraries=places&language=en"></script>
@yield('script_block')

{{--Multiple errors from laravel validation--}}
@if(!empty($errors) && count($errors) > 0)
    <script>
        var errors = '';
        @foreach($errors->all() as $error)
            errors+="{{ $error }}" + '<br>';
        @endforeach
        basic.showAlert(errors, '', true);
    </script>
@endif

{{--Single error from controller response--}}
@if (session('error'))
    <script>
        basic.showAlert("{!! session('error') !!}", '', true);
    </script>
@endif

{{--Multiple errors from controller response--}}
@if(session('errors_response') && count(session('errors_response')) > 0)
    <script>
        var errors = '';
        @foreach(session('errors_response') as $error)
            errors+="{{ $error }}" + '<br>';
        @endforeach
        basic.showAlert(errors, '', true);
    </script>
@endif

{{--Success from controller response--}}
@if (session('success'))
    @if(session('popup-html'))
        <script>
            basic.showDialog("{!! session('popup-html') !!}", 'popup-html', null, true);
            $('.close-popup').click(function() {
                basic.closeDialog();
            });
        </script>
    @else
        <script>
            basic.showAlert("{{ session('success') }}", '', true);
        </script>
    @endif
@endif

</body>
</html>