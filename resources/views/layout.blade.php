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
    <link rel="stylesheet" type="text/css" href="/dist/css/front-libs-style.css{{--?v=1.0.18--}}">
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css{{--?v=1.0.18--}}">
    <script>
        var HOME_URL = '{{ route("home") }}';
    </script>
</head>
<body class="@if(!empty(Route::current())) {{Route::current()->getName()}} @else class-404 @endif @if(\App\Http\Controllers\UserController::instance()->checkSession()) logged-in @endif" @if(isset($gas_estimation)) data-current-gas-estimation="{{$gas_estimation}}" @endif>
@if(isset($mobile) && $mobile)
    <nav class="sidenav">
        <div class="wrapper">
            <a href="javascript:void(0)" class="close-btn"><i class="fa fa-times" aria-hidden="true"></i></a>
            <ul itemscope="" itemtype="http://schema.org/SiteNavigationElement">
                <li class="lato-semibold @if(!empty(Route::current())) @if(Route::current()->getName() == "home") active @endif @endif"><a href="{{route('home')}}" itemprop="url"><span itemprop="name">Dentists</span></a>
                </li>
                <li class="lato-semibold @if(!empty(Route::current())) @if(Route::current()->getName() == "patient-access") active @endif @endif"><a href="{{route('patient-access')}}" itemprop="url"><span itemprop="name">Patients</span></a>
                </li>
                <li>
                    <a href="javascript:void(0)" itemprop="url" class="white-blue-green-btn show-login-signin @if(!empty(Route::current())) @if(Route::current()->getName() == "home") dentist-side @endif @endif"><span itemprop="name">SIGN IN</span></a>
                </li>
            </ul>
        </div>
    </nav>
@endif

@if(\App\Http\Controllers\UserController::instance()->checkSession())
    <div class="logged-mobile-profile-menu">
        <nav class="profile-menu module">
            <a href="javascript:void(0)" class="close-logged-mobile-profile-menu"><i class="fa fa-times" aria-hidden="true"></i></a>
            <ul itemscope="" itemtype="http://schema.org/SiteNavigationElement">
                <li>
                    <a href="{{ route('home') }}" itemprop="url">
                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                            <img alt="Home icon" src="/assets/uploads/home.svg"/>
                        </figure>
                        <span itemprop="name">Home</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('my-profile') }}" @if(!empty(Route::current()) && Route::current()->getName() == 'my-profile') class="active" @endif itemprop="url">
                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                            <img alt="Wallet icon" src="/assets/uploads/wallet-icon.svg"/>
                        </figure>
                        <span itemprop="name">My Wallet</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('edit-account') }}" @if(!empty(Route::current()) && Route::current()->getName() == 'edit-account') class="active" @endif itemprop="url">
                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                            <img alt="Edit account icon" src="/assets/uploads/edit-account-icon.svg"/>
                        </figure>
                        <span itemprop="name">Edit Account</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('manage-privacy') }}" @if(!empty(Route::current()) && Route::current()->getName() == 'manage-privacy') class="active" @endif itemprop="url">
                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                            <img alt="Privacy icon" src="/assets/uploads/privacy-icon.svg"/>
                        </figure>
                        <span itemprop="name">Manage Privacy</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('my-contracts') }}" @if(!empty(Route::current()) && Route::current()->getName() == 'my-contracts') class="active" @endif itemprop="url">
                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                            <img alt="Contracts list" src="/assets/uploads/contracts-list.svg"/>
                        </figure>
                        <span itemprop="name">My contracts</span>
                    </a>
                </li>
                @if(session('logged_user')['type'] == 'patient')
                    <li>
                        <a href="{{ route('invite-dentists') }}" @if(!empty(Route::current()) && Route::current()->getName() == 'invite-dentists') class="active" @endif itemprop="url">
                            <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                                <img alt="Add dentist" src="/assets/uploads/add-dentist.svg"/>
                            </figure>
                            <span itemprop="name">Invite Dentists</span>
                        </a>
                    </li>
                @elseif(session('logged_user')['type'] == 'dentist')
                    <li>
                        <a href="{{ route('create-contract') }}" @if(!empty(Route::current()) && Route::current()->getName() == 'create-contract') class="active" @endif itemprop="url">
                            <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                                <img alt="Create new contract" src="/assets/uploads/create-new-contract.svg"/>
                            </figure>
                            <span itemprop="name">Create contract</span>
                        </a>
                    </li>
                @endif
                <li>
                    <a href="{{ route('user-logout') }}" itemprop="url">
                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                            <img alt="Logout icon" src="/assets/uploads/logout-icon.svg"/>
                        </figure>
                        <span itemprop="name">Log out</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
@endif

<header>
    <div class="container">
        <div class="row fs-0">
            <figure itemscope="" itemtype="http://schema.org/Organization" class="col-xs-3 logo-container inline-block">
                <a itemprop="url" href="{{ route('home') }}" @if(!empty(Route::current())) @if(Route::current()->getName() == "home") tabindex="=-1" @endif @endif>
                    <img src="{{URL::asset('assets/uploads/assurance-logo.svg') }}" itemprop="logo" alt="Dentacoin logo" class="max-width-50 max-width-xs-40"/>
                </a>
            </figure>
            @if(!\App\Http\Controllers\UserController::instance()->checkSession())
                @if(isset($mobile) && !$mobile)
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
                    <div class="col-xs-9 inline-block text-right">
                        <a href="javascript:void(0)" class="hamburger"><i class="fa fa-bars fs-32 dark-color" aria-hidden="true"></i></a>
                    </div>
                @endif
            @else
                @if(!empty(Route::current()) && in_array(Route::current()->getName(), array('my-profile', 'edit-account', 'manage-privacy', 'my-contracts')))
                    <div class="col-xs-9 inline-block text-right show-on-mobile">
                        <a href="javascript:void(0)" class="logged-user-hamburger"><i class="fa fa-bars fs-32 dark-color" aria-hidden="true"></i></a>
                    </div>
                    @include('partials.logged-user-desktop-header-menu', ['class' => 'hide-on-mobile'])
                @else
                    @include('partials.logged-user-desktop-header-menu')
                @endif
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
                <img src="/assets/images/contract-assurance-loading.gif" class="max-width-440 min-width-300" alt="Loader">
            </figure>
        </div>
    </div>
    <div class="contract-response-success-layer">
        <div class="wrapper">
            <figure itemscope="" itemtype="http://schema.org/ImageObject">
                <img src="/assets/images/contract-assurance-loading-success.gif" class="max-width-440 min-width-300" alt="Loader">
            </figure>
        </div>
    </div>
@endif

{{--/Show the sticky calculate button only for dentists--}}
<script src="/assets/js/basic.js{{--?v=1.0.18--}}"></script>
<script src="/dist/js/front-libs-script.js{{--?v=1.0.18--}}"></script>
{{--<script src="/dist/js/front-script.js?v=1.0.13"></script>--}}
<script src="/assets/js/address.js{{--?v=1.0.18--}}"></script>
<script src="/assets/js/index-compiled.js{{--?v=1.0.18--}}"></script>
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
            basic.showDialog('{!! session('popup-html') !!}', 'popup-html', null, true);
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