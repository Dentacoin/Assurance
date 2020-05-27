<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <link rel="shortcut icon" href="{{URL::asset('assets/images/favicon.png') }}" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5" />
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="stylesheet" type="text/css" href="/dist/css/front-libs-style.css?v=1.0.3">
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css?v=1.0.3">
    <script>
        var HOME_URL = '{{ route("home") }}';
    </script>
</head>
<body class="@if(\App\Http\Controllers\UserController::instance()->checkSession()) logged-in @endif">
    <div class="my-contracts-container my-contracts-iframe">
        <div class="profile-page-title">
            <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                <img alt="Contracts list" src="/assets/uploads/contracts-list.svg"/>
            </figure>
            <h2 class="fs-24 lato-bold inline-block">My contracts</h2>
        </div>
        @if((new \App\Http\Controllers\UserController())->checkDentistSession())
            <div class="padding-top-20 text-center">
                <a href="{{ route('create-contract') }}" target="_top" class="blue-green-white-btn">CREATE CONTRACT</a>
            </div>
        @endif
        <div class="table-container fs-16 padding-top-15">
            @include('partials.table-my-contracts')
        </div>
        @if(isset($mobile) && $mobile)
            <figure itemscope="" itemtype="http://schema.org/ImageObject" class="mobile-slide">
                <img src="/assets/uploads/slide.gif" alt="Slide gif"/>
            </figure>
        @endif
    </div>
    {{--/Show the sticky calculate button only for dentists--}}
    <script src="https://dentacoin.com/assets/js/basic.js?v=1.0.3"></script>
    <script src="/dist/js/front-libs-script.js?v=1.0.3"></script>
    {{--<script src="/dist/js/front-script.js?v=1.0.33"></script>--}}
    <script src="/assets/js/index-bundled.js?v=1.0.3"></script>
</body>
</html>