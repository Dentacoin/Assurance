@extends("layout")
@section("content")
    @php($dentist = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->dentist_id))
    @php($patient = (new \App\Http\Controllers\APIRequestsController())->getUserData(session('logged_user')['id']))
    @if(is_object($contract->contract_active_at))
        @php($contract_active_at = $contract->contract_active_at->getTimestamp())
    @else
        @php($contract_active_at = strtotime($contract->contract_active_at))
    @endif
    <section class="padding-top-100 padding-top-xs-30 padding-top-sm-50 patient-contract-single-page-section margin-bottom-20" data-monthly-premium="{{$contract->monthly_premium}}" data-patient-address="{{$contract->patient_address}}" data-dentist-address="{{$contract->dentist_address}}" data-date-start-contract="{{$contract_active_at}}" data-contract-ipfs="{{$contract->document_hash}}">
        <div class="container">
            <div class="row">
                <div class="col-xs-12"><h1 class="lato-bold text-center fs-45 fs-xs-30">Dentacoin Assurance Contract</h1></div>
            </div>
            <div class="row">
                @include('partials.contract-single-page-nav')
            </div>
        </div>
        <div class="container single-contract-tile module text-center padding-top-20">
            <div class="row fs-0 flex-sm">
                <div class="col-xs-4 col-md-3 contract-participant text-center inline-block-bottom padding-top-35 padding-bottom-35 white-color-background padding-left-xs-5 padding-right-xs-5 padding-top-xs-15 padding-bottom-xs-15">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="Dentist avatar" src="{{$dentist->avatar_url}}"/>
                    </figure>
                    <div class="fs-22 fs-xs-18 calibri-bold padding-top-15 padding-bottom-5">Dr. {{$dentist->name}}</div>
                    <div class="calibri-light">
                        <a href="mailto:{{$dentist->email}}" class="light-gray-color fs-18 fs-xs-16 word-break">{{$dentist->email}}</a>
                    </div>
                </div>
                <div class="col-xs-4 inline-block-bottom contract-body">
                    <div class="contract-header text-center lato-bold fs-20 white-color padding-top-15 padding-bottom-15 awaiting-payment">@if(isset($mobile) && !$mobile)ACTIVE -@endif AWAITING PAYMENT</div>
                    <div class="wrapper">
                        <div class="lato-bold fs-20 padding-top-15 padding-bottom-10 padding-left-10 padding-right-10 timer-label"></div>
                        <div class="clock"></div>
                        <div class="flip-clock-message"></div>
                    </div>
                    <div class="steps-navigation fs-0">
                        <a href="javascript:void(0);" data-step="popup-step-one" class="fs-14 active"><figure itemscope="" itemtype="http://schema.org/ImageObject" class="white-check margin-right-5 width-100 max-width-20 inline-block"><img alt="White check" src="/assets/uploads/white-check.svg"/></figure><figure itemscope="" itemtype="http://schema.org/ImageObject" class="gray-check margin-right-5 width-100 max-width-20 inline-block"><img alt="Gray check" src="/assets/uploads/gray-check.svg"/></figure>STEP 1: FUND</a>
                        <a href="javascript:void(0);" data-step="popup-step-two" class="fs-14 disabled">STEP 2: ACTIVATE</a>
                    </div>
                </div>
                <div class="col-xs-4 col-md-3 contract-participant text-center inline-block-bottom padding-top-35 padding-bottom-35 white-color-background padding-left-xs-5 padding-right-xs-5 padding-top-xs-15 padding-bottom-xs-15">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="Dentist avatar" src="{{$patient->avatar_url}}"/>
                    </figure>
                    <div class="fs-22 fs-xs-18 calibri-bold padding-top-15 padding-bottom-5">{{$patient->name}}</div>
                    <div class="calibri-light fs-18 fs-xs-16 light-gray-color word-break">{{$patient->email}}</div>
                </div>
            </div>
            @if(isset($mobile) && $mobile)
                <div class="row contract-footer show-on-sm">
                    <div class="col-xs-12 col-sm-8 col-sm-offset-2 padding-top-30 padding-bottom-40 padding-left-50 padding-right-50 text-center fs-20 wrapper padding-top-xs-20 padding-bottom-xs-0 padding-left-xs-0 padding-right-xs-0">
                        <div class="padding-left-15 padding-right-15">
                            <div class="lato-bold fs-20 padding-bottom-5">YOUR FIRST PAYMENT IS DUE IN:</div>
                            <div class="clock"></div>
                            <div class="flip-clock-message"></div>
                        </div>
                        <div class="steps-navigation fs-0">
                            <a href="javascript:void(0);" data-step="popup-step-one" class="fs-14 active"><figure itemscope="" itemtype="http://schema.org/ImageObject" class="white-check margin-right-5 width-100 max-width-20 inline-block"><img alt="White check" src="/assets/uploads/white-check.svg"/></figure><figure itemscope="" itemtype="http://schema.org/ImageObject" class="gray-check margin-right-5 width-100 max-width-20 inline-block"><img alt="Gray check" src="/assets/uploads/gray-check.svg"/></figure>STEP 1: FUND</a>
                            <a href="javascript:void(0);" data-step="popup-step-two" class="fs-14 disabled">STEP 2: ACTIVATE</a>
                        </div>
                        {{--<div class="timer-text"></div>--}}
                    </div>
                </div>
            @endif
            <div class="row camping-for-popups">
                {{--@if(session('congratulations'))
                    @php($until_timestamp = strtotime('+'.(DAYS_CONTRACT_WITHDRAWAL_PERIOD - 1).' days', strtotime($contract->contract_active_at)))
                    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center fs-20 contract-response-message module">
                        <div class="wrapper text-center">
                            <div class="close-btn">×</div>
                            <figure itemscope="" itemtype="http://schema.org/ImageObject">
                                <img alt="Check inside shield" src="/assets/uploads/shield-check.svg" class="max-width-70"/>
                            </figure>
                            <h1 class="lato-bold fs-20 padding-top-15">CONGRATULATIONS!</h1>
                            <div class="fs-18 fs-xs-16 calibri-light padding-top-10">Your .pdf contract was successfully created. <br>You should buy DCN equivalent to <b class="blue-green-color">{{$contract->monthly_premium}} USD</b> until <b class="blue-green-color">{{date('d/m/Y', $until_timestamp)}}</b>.</div>
                        </div>
                    </div>
                @endif--}}
            </div>
        </div>
    </section>
    <section class="init-contract-section" data-contract="{{$contract->slug}}">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 camp"></div>
            </div>
        </div>
    </section>
    <section class="container contract-details no-gutter-xs">
        <div class="row text-center margin-left-xs-0 margin-right-xs-0">
            <div class="col-xs-12 col-lg-10 col-lg-offset-1 no-gutter-xs">
                <div class="contract-details-container module fs-16 text-left padding-top-40 padding-bottom-60">
                    @include('partials.contract-details', ['type' => 'single-page', 'contract' => $contract, 'dentist' => $dentist, 'patient' => $patient, 'calculator_proposals' => $calculator_proposals])
                </div>
                <div class="padding-top-40">
                    <a href="javascript:void(0)" class="open-contract-details fs-20 calibri-bold blue-green-color" data-label-closed="See details" data-label-opened="Hide details" data-hidden-details="true">See details</a>
                </div>
            </div>
        </div>
    </section>
    @include('partials.patient-ready-to-purchase-with-external-api')
@endsection

