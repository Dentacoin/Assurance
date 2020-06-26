@extends("layout")
@section("content")
    <section class="padding-top-100 padding-top-xs-30 padding-top-sm-50 single-contract-view-section pending" data-contract="{{$contract->slug}}" data-dentist="{{$contract->dentist_address}}">
        <div class="container">
            <div class="row">
                @include('partials.contract-single-page-title')
            </div>
            <div class="row">
                @include('partials.contract-single-page-nav', ['type' => 'desktop'])
            </div>
        </div>
        <div class="container single-contract-tile module pending text-center padding-top-20 @if(isset($mobile) && $mobile) mobile @endif">
            <div class="row fs-0 patient-dentist-data">
                <div class="col-xs-4 col-md-3 contract-participant text-center inline-block-top padding-top-35 padding-bottom-35 white-color-background padding-left-xs-5 padding-right-xs-5 padding-top-xs-15 padding-bottom-xs-15 dentist">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="Dentist avatar" src="{{$current_logged_dentist->avatar_url}}"/>
                    </figure>
                    <div class="fs-22 fs-xs-18 calibri-bold padding-top-15 padding-bottom-5">{{(new \App\Http\Controllers\Controller())->prepareUserName($current_logged_dentist)}}</div>
                    <div class="calibri-light fs-18 fs-xs-16 light-gray-color word-break">{{$current_logged_dentist->email}}</div>
                </div>
                <div class="col-xs-3 inline-block-top margin-top-40 margin-top-xs-0 contract-body" data-time-left-next-transfer="{{strtotime($contract->contract_active_at)}}">
                    <div class="contract-header text-center lato-bold fs-20 white-color padding-top-15 padding-bottom-15 pending">PENDING</div>
                    <div class="wrapper">
                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="absolute-hands">
                            <img alt="Dentist avatar" src="/assets/uploads/pending-hands.svg"/>
                        </figure>
                        <figure class="inline-block rotate-animation" itemscope="" itemtype="http://schema.org/ImageObject">
                            <img src="/assets/uploads/rotating-icon.png" alt="Loading icon" itemprop="contentUrl">
                        </figure>
                    </div>
                </div>
                <div class="col-xs-4 col-md-3 contract-participant text-center inline-block-top padding-top-35 padding-bottom-35 white-color-background padding-left-xs-5 padding-right-xs-5 padding-top-xs-15 padding-bottom-xs-15">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="Patient avatar" src="/assets/images/no-avatar.png"/>
                    </figure>
                    <div class="fs-22 fs-xs-18 calibri-bold padding-top-15 padding-bottom-5">{{$contract->patient_fname}} {{$contract->patient_lname}}</div>
                    <div class="calibri-light">
                        <a href="mailto:{{$contract->patient_email}}" class="light-gray-color fs-18 fs-xs-16 word-break">{{$contract->patient_email}}</a>
                    </div>
                </div>
            </div>
            <div class="row camping-for-popups">
                <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center fs-20 contract-response-message module">
                    <div class="wrapper text-center">
                        <div class="fs-90 line-height-90 blue-green-color">!</div>
                        <h1 class="lato-bold fs-20 padding-top-15">WARNING</h1>
                        <div class="fs-18 fs-xs-16 calibri-light padding-top-10">Your patient has received their contract sample but has not yet signed it. The proposal will be valid for one month after the sample sending date.</div>
                    </div>
                </div>
            </div>
            <div class="row fs-0 padding-top-40 row-with-bottom-squares text-center">
                <div class="col-sm-3 col-xs-12 inline-block padding-top-15 padding-bottom-15 border-right-light-gray">
                    <h3 class="fs-20 calibri-bold">Date Initiated:</h3>
                    <time class="display-block padding-top-10 calibri-light fs-20">{{$contract->created_at->format('d/m/Y')}}</time>
                </div>
                <div class="col-sm-3 col-xs-12 inline-block padding-top-15 padding-bottom-15">
                    <h3 class="fs-20 calibri-bold">Suggested Monthly Premium:</h3>
                    <div class="display-block padding-top-10 calibri-light fs-20">{{$contract->monthly_premium}} USD</div>
                </div>
                <div class="padding-top-xs-20 padding-top-sm-20 padding-bottom-xs-20 padding-bottom-sm-20">@include('partials.contract-single-page-nav', ['type' => 'mobile'])</div>
                </section>
                <div class="col-xs-12 col-lg-10 col-lg-offset-1 no-gutter-xs">
                    <div class="contract-details-container fs-16 show-this module text-left padding-top-40 padding-bottom-60">
                        @include('partials.contract-details', ['type' => 'single-page', 'contract' => $contract, 'dentist' => $current_logged_dentist, 'calculator_proposals' => $calculator_proposals])
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

