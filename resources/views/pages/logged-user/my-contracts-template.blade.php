<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <link rel="shortcut icon" href="{{URL::asset('assets/images/favicon.png') }}" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5" />
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="stylesheet" type="text/css" href="/dist/css/front-libs-style.css?v=1.0.1">
    <link rel="stylesheet" type="text/css" href="/assets/css/style.css?v=1.0.1">
    <script>
        var HOME_URL = '{{ route("home") }}';
    </script>
</head>
<body class="@if(\App\Http\Controllers\UserController::instance()->checkSession()) logged-in @endif">
    <div class="my-profile-page-content my-contracts-container inline-block-top">
        <div class="profile-page-title">
            <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                <img alt="Contracts list" src="/assets/uploads/contracts-list.svg"/>
            </figure>
            <h2 class="fs-24 lato-bold inline-block">My contracts</h2>
        </div>
        <div class="table-container fs-16">
            @include('partials.table-my-contracts')
        </div>
        @if(isset($mobile) && $mobile)
            <figure itemscope="" itemtype="http://schema.org/ImageObject" class="mobile-slide">
                <img src="/assets/uploads/slide.gif" alt="Slide gif"/>
            </figure>
        @endif
    </div>
    {{--/Show the sticky calculate button only for dentists--}}
    <script src="/assets/js/basic.js?v=1.0.1"></script>
    <script src="/dist/js/front-libs-script.js?v=1.0.1"></script>
    {{--<script src="/dist/js/front-script.js?v=1.0.13"></script>--}}
    <script src="/assets/js/address.js?v=1.0.1"></script>
    <script src="/assets/js/index-bundled.js?v=1.0.1"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCaVeHq_LOhQndssbmw-aDnlMwUG73yCdk&libraries=places&language=en"></script>
</body>
</html>