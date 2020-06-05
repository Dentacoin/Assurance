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
    <link rel="stylesheet" type="text/css" href="/dist/css/front-libs-style.css?v=1.0.63">
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css?v=1.0.63">
    @if((new \App\Http\Controllers\UserController())->checkSession())
        <link rel="stylesheet" type="text/css" href="https://dentacoin.com/assets/libs/dentacoin-mini-hub/css/style.css?v=1.0.63">
    @else
        <link rel="stylesheet" type="text/css" href="https://dentacoin.com/assets/libs/dentacoin-login-gateway/css/dentacoin-login-gateway-style.css?v=1.0.63"/>
@endif

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-108398439-4"></script>
    <script id="google-analytics-script">
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        @if(empty($_COOKIE['performance_cookies']))
            gtag('config', 'UA-108398439-4', {'anonymize_ip': true});
        @else
            gtag('config', 'UA-108398439-4');
        @endif
    </script>

    <!-- Facebook Pixel Code -->
    <script id="facebook-pixel-script">
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window,document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        @if(empty($_COOKIE['marketing_cookies']))
            fbq('consent', 'revoke');
        @else
            fbq('consent', 'grant');
        @endif
        fbq('init', '2366034370318681');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1"
             src="https://www.facebook.com/tr?id=2366034370318681&ev=PageView
&noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->

    <script>
        var HOME_URL = '{{ route("home") }}';
    </script>
</head>
<body class="@if(!empty(Route::current())) {{Route::current()->getName()}} @else class-404 @endif @if(\App\Http\Controllers\UserController::instance()->checkSession()) logged-in @if(\App\Http\Controllers\UserController::instance()->checkPatientSession()) patient-side logged-patient @elseif(\App\Http\Controllers\UserController::instance()->checkDentistSession()) dentist-side logged-dentist @endif @endif">
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
                        <a href="javascript:void(0)" itemprop="url" class="white-blue-green-btn open-dentacoin-gateway @if(!empty(Route::current())) @if(Route::current()->getName() == "home") dentist-login @elseif(Route::current()->getName() == "patient-access") patient-login @else dentist-login @endif @endif"><span itemprop="name">SIGN IN</span></a>
                    </li>
                </ul>
            </div>
        </nav>
    @endif

    {{--@if(\App\Http\Controllers\UserController::instance()->checkSession())
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
                        <a href="//account.dentacoin.com/" itemprop="url">
                            <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                                <img alt="Wallet icon" src="/assets/uploads/wallet-icon.svg"/>
                            </figure>
                            <span itemprop="name">My Wallet</span>
                        </a>
                    </li>
                    <li>
                        <a href="//account.dentacoin.com/edit-account" itemprop="url">
                            <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                                <img alt="Edit account icon" src="/assets/uploads/edit-account-icon.svg"/>
                            </figure>
                            <span itemprop="name">Edit Account</span>
                        </a>
                    </li>
                    <li>
                        <a href="//account.dentacoin.com/manage-privacy" itemprop="url">
                            <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                                <img alt="Privacy icon" src="/assets/uploads/privacy-icon.svg"/>
                            </figure>
                            <span itemprop="name">Manage Privacy</span>
                        </a>
                    </li>
                    <li>
                        <a href="//account.dentacoin.com/assurance" itemprop="url">
                            <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                                <img alt="Contracts list" src="/assets/uploads/contracts-list.svg"/>
                            </figure>
                            <span itemprop="name">My contracts</span>
                        </a>
                    </li>
                    @if(session('logged_user')['type'] == 'patient')
                        <li>
                            <a href="{{ route('patient-access') }}" @if(!empty(Route::current()) && Route::current()->getName() == 'invite-dentists') class="active" @endif itemprop="url">
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
                    <li> <a href="https://account.dentacoin.com/dentavox" itemprop="url"> <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block"> <img alt="Dentavox logo" src="//account.dentacoin.com/assets/uploads/dentavox--surveys.svg"> </figure> <span itemprop="name">DentaVox Surveys</span> </a> </li> <li> <a href="https://account.dentacoin.com/trusted-reviews" itemprop="url"> <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block"> <img alt="Trusted reviews logo" src="//account.dentacoin.com/assets/uploads/trusted-reviews-icon.svg"> </figure> <span itemprop="name">Trusted Reviews</span> </a> </li> <li> <a href="https://account.dentacoin.com/assurance" itemprop="url"> <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block"> <img alt="Assurance logo" src="//account.dentacoin.com/assets/uploads/assurance.svg"> </figure> <span itemprop="name">Dentacoin Assurance</span> </a> </li> <li> <a href="//dentacare.dentacoin.com" target="_blank" itemprop="url"> <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block"> <img alt="Dentacare logo" src="//account.dentacoin.com/assets/uploads/dc-icon.svg"> </figure> <span itemprop="name">Dentacare</span> </a> </li>
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
    @endif--}}

    <header>
        <div class="container">
            <div class="row fs-0">
                <figure itemscope="" itemtype="http://schema.org/Organization" class="col-xs-3 logo-container inline-block">
                    <a itemprop="url" href="{{ route('home') }}" @if(!empty(Route::current())) @if(Route::current()->getName() == "home") tabindex="=-1" @endif @endif>
                        <img src="{{URL::asset('assets/uploads/assurance-logo.svg') }}" itemprop="logo" alt="Dentacoin logo" class="max-width-50 max-width-xs-40"/>
                    </a>
                </figure>
                @if(!\App\Http\Controllers\UserController::instance()->checkSession())
                    @if(isset($mobile))
                        @if(!$mobile)
                            <nav class="col-xs-9 inline-block">
                                <ul itemscope="" itemtype="http://schema.org/SiteNavigationElement">
                                    <li class="inline-block @if(!empty(Route::current())) @if(Route::current()->getName() == "home") active @endif @endif"><a href="{{route('home')}}" itemprop="url"><span itemprop="name">Dentists</span></a></li>
                                    <li class="inline-block">|</li>
                                    <li class="inline-block @if(!empty(Route::current())) @if(Route::current()->getName() == "patient-access") active @endif @endif"><a href="{{route('patient-access')}}" itemprop="url"><span itemprop="name">Patients</span></a></li>
                                    <li class="inline-block">
                                        <a href="javascript:void(0)" itemprop="url" class="blue-green-white-btn open-dentacoin-gateway @if(!empty(Route::current())) @if(Route::current()->getName() == "home") dentist-login @elseif(Route::current()->getName() == "patient-access") patient-login @else dentist-login @endif @endif"><span itemprop="name">SIGN IN</span></a>
                                    </li>
                                </ul>
                            </nav>
                        @else
                            <div class="col-xs-9 inline-block text-right">
                                <a href="javascript:void(0)" class="hamburger"><i class="fa fa-bars fs-32 dark-color" aria-hidden="true"></i></a>
                            </div>
                        @endif
                    @endif
                @else
                    @include('partials.logged-user-desktop-header-menu')
                @endif
            </div>
        </div>
    </header>
    <main>@yield("content")</main>
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                    <hr/>
                </div>
            </div>
            @if(!empty(Route::current()))
                @php($footer_menu = \App\Http\Controllers\Controller::instance()->getMenu('footer'))
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
            @endif
            <div class="row padding-bottom-100 padding-top-50 text-center fs-14 bottom-text">
                <div class="col-xs-12">© {{date('Y')}} Dentacoin Foundation. All rights reserved.</div>
                <div class="col-xs-12">
                    <a href="//dentacoin.com/assets/uploads/dentacoin-foundation.pdf" class="inline-block dark-color" target="_blank">Verify Dentacoin Foundation</a>
                    <li class="inline-block separator padding-left-5 padding-right-5">|</li>
                    <a href="//dentacoin.com/privacy-policy" target="_blank" class="inline-block dark-color">Privacy Policy</a>
                </div>
            </div>
        </div>
    </footer>
    <div class="camping-loader"></div>
    @php($crossLogin = \Illuminate\Support\Facades\Input::get('cross-login'))
    @if(\App\Http\Controllers\UserController::instance()->checkSession() && !empty($crossLogin))
        @php($slug = (new \App\Http\Controllers\Controller())->encrypt(session('logged_user')['id'], getenv('API_ENCRYPTION_METHOD'), getenv('API_ENCRYPTION_KEY')))
        @php($type = (new \App\Http\Controllers\Controller())->encrypt(session('logged_user')['type'], getenv('API_ENCRYPTION_METHOD'), getenv('API_ENCRYPTION_KEY')))
        @php($token = (new \App\Http\Controllers\Controller())->encrypt(session('logged_user')['token'], getenv('API_ENCRYPTION_METHOD'), getenv('API_ENCRYPTION_KEY')))
        <img src="//dentacoin.com/custom-cookie?slug={{ urlencode($slug) }}&type={{ urlencode($type) }}&token={{ urlencode($token) }}" class="hide"/>
        <img src="//dentists.dentacoin.com/custom-cookie?slug={{ urlencode($slug) }}&type={{ urlencode($type) }}&token={{ urlencode($token) }}" class="hide"/>
        <img src="//reviews.dentacoin.com/custom-cookie?slug={{ urlencode($slug) }}&type={{ urlencode($type) }}&token={{ urlencode($token) }}" class="hide"/>
        <img src="//dentavox.dentacoin.com/custom-cookie?slug={{ urlencode($slug) }}&type={{ urlencode($type) }}&token={{ urlencode($token) }}" class="hide"/>
        <img src="//account.dentacoin.com/custom-cookie?slug={{ urlencode($slug) }}&type={{ urlencode($type) }}&token={{ urlencode($token) }}" class="hide"/>
    @else
        @if(!empty(session('logout_token')))
            <img src="//dentacoin.com/custom-cookie?logout-token={{ urlencode(session('logout_token')) }}" class="hide"/>
            <img src="//dentists.dentacoin.com/custom-cookie?logout-token={{ urlencode(session('logout_token')) }}" class="hide"/>
            <img src="//reviews.dentacoin.com/custom-cookie?logout-token={{ urlencode(session('logout_token')) }}" class="hide"/>
            <img src="//dentavox.dentacoin.com/custom-cookie?logout-token={{ urlencode(session('logout_token')) }}" class="hide"/>
            <img src="//account.dentacoin.com/custom-cookie?logout-token={{ urlencode(session('logout_token')) }}" class="hide"/>
        @endif
    @endif
    @if(!empty(Route::current()) && Route::current()->getName() == 'home')
        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="text-center sticky-open-calculator-container">
            <a href="javascript:void(0);" class="open-calculator">
                <img alt="Sticky calculator button" itemprop="contentUrl" src="/assets/uploads/sticky-calculator-button.svg"/>
            </a>
        </figure>
    @endif
    <div class="bottom-fixed-container">
        {{--Show the sticky calculate button only for dentists--}}
        {{--<a href="https://dentacoin.com/holiday-calendar-2019" target="_blank" class="display-block banner">
            <picture itemscope="" itemtype="http://schema.org/ImageObject">
                <source media="(max-width: 992px)" srcset="//dentacoin.com/assets/uploads/mobile-christmas-banner-small.gif"/>
                <img src="//dentacoin.com/assets/uploads/christmas-banner.gif" alt="Holiday calendar banner" class="width-100" itemprop="contentUrl"/>
            </picture>
        </a>--}}
        @if(empty($_COOKIE['performance_cookies']) && empty($_COOKIE['functionality_cookies']) && empty($_COOKIE['marketing_cookies']) && empty($_COOKIE['strictly_necessary_policy']))
            <div class="privacy-policy-cookie">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="text inline-block">This site uses cookies. Find out more on how we use cookies in our <a href="https://dentacoin.com/privacy-policy" class="link" target="_blank">Privacy Policy</a>. | <a href="javascript:void(0);" class="link adjust-cookies">Adjust cookies</a></div>
                            <div class="button inline-block"><a href="javascript:void(0);" class="white-blue-green-btn  accept-all">Accept all cookies</a></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{--/Show the sticky calculate button only for dentists--}}
    <script src="https://dentacoin.com/assets/js/basic.js?v=1.0.63"></script>
    <script src="/dist/js/front-libs-script.js?v=1.0.63"></script>
    {{--<script src="/dist/js/front-script.js?v=1.0.13"></script>--}}
    @if((new \App\Http\Controllers\UserController())->checkSession())
        <script src="https://dentacoin.com/assets/libs/dentacoin-mini-hub/js/init.js?v=1.0.63"></script>
    @else
        <script src="https://dentacoin.com/assets/libs/dentacoin-login-gateway/js/init.js?v=1.0.63"></script>
    @endif
    <script src="https://dentacoin.com/assets/js/address-combined-login.js?v=1.0.63"></script>

    <script src="/assets/js/index-bundled.js?v=1.0.63"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCaVeHq_LOhQndssbmw-aDnlMwUG73yCdk&libraries=places&language=en"></script>

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
    @if(session('success'))
        @if(session('popup-html'))
            <script>
                basic.showDialog('{!! session('popup-html') !!}', 'popup-html', null, true);
            </script>
        @else
            <script>
                basic.showAlert("{!! session('success') !!}", '', true);
            </script>
        @endif
    @endif
</body>
</html>