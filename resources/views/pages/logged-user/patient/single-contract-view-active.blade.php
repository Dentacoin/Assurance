@extends("layout")
@section("content")
    @php($dentist = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->dentist_id))
    @php($patient = (new \App\Http\Controllers\APIRequestsController())->getUserData(session('logged_user')['id']))
    @if(is_object($contract->contract_active_at))
        @php($contract_active_at = $contract->contract_active_at->getTimestamp())
    @else
        @php($contract_active_at = strtotime($contract->contract_active_at))
    @endif
    <section class="padding-top-100 padding-top-xs-30 padding-top-sm-50 patient-contract-single-page-section" data-monthly-premium="{{$contract->monthly_premium}}" data-date-start-contract="{{$contract_active_at}}" data-patient-address="{{$contract->patient_address}}" data-dentist-address="{{$contract->dentist_address}}">
        <div class="container">
            <div class="row">
                <div class="col-xs-12"><h1 class="lato-bold text-center fs-45 fs-xs-30">Dentacoin Assurance Contract</h1></div>
            </div>
            <div class="row">
                @include('partials.contract-single-page-nav', ['dentist_data' => $dentist, 'patient_data' => $patient])
            </div>
        </div>
        <div class="container single-contract-tile module text-center padding-top-20">
            <div class="row fs-0 flex-xs">
                <div class="col-xs-4 col-md-3 contract-participant text-center inline-block padding-top-35 padding-bottom-35 white-color-background padding-left-xs-5 padding-right-xs-5 padding-top-xs-15 padding-bottom-xs-15">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="Dentist avatar" src="{{$dentist->avatar_url}}"/>
                    </figure>
                    <div class="fs-22 fs-xs-18 calibri-bold padding-top-15 padding-bottom-5">Dr. {{$dentist->name}}</div>
                    <div class="calibri-light">
                        <a href="mailto:{{$dentist->email}}" class="light-gray-color fs-18 fs-xs-16 word-break">{{$dentist->email}}</a>
                    </div>
                </div>
                <div class="col-xs-4 inline-block contract-body padding-bottom-10 padding-bottom-xs-0">
                    <div class="contract-header text-center lato-bold fs-20 white-color padding-top-15 padding-bottom-15 active">ACTIVE</div>
                    <div class="wrapper">
                        <div class="lato-bold fs-20 padding-top-15 padding-bottom-10 padding-left-10 padding-right-10 timer-label"></div>
                        <div class="clock"></div>
                        <div class="flip-clock-message"></div>
                    </div>
                </div>
                <div class="col-xs-4 col-md-3 contract-participant text-center inline-block padding-top-35 padding-bottom-35 white-color-background padding-left-xs-5 padding-right-xs-5 padding-top-xs-15 padding-bottom-xs-15">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="Dentist avatar" src="{{$patient->avatar_url}}"/>
                    </figure>
                    <div class="fs-22 fs-xs-18 calibri-bold padding-top-15 padding-bottom-5">{{$patient->name}}</div>
                    <div class="calibri-light fs-18 fs-xs-16 light-gray-color word-break">{{$patient->email}}</div>
                </div>
            </div>
            @if(isset($mobile) && $mobile)
                <div class="row contract-footer">
                    <div class="col-xs-12 col-sm-8 col-sm-offset-2 padding-top-30 padding-bottom-40 padding-left-50 padding-right-50 text-center fs-20 wrapper padding-top-xs-20 padding-bottom-xs-0 padding-left-xs-15 padding-right-xs-15">
                        <div class="show-on-xs">
                            <div class="lato-bold fs-20 padding-bottom-5">YOUR FIRST PAYMENT IS DUE IN:</div>
                            <div class="clock"></div>
                            <div class="flip-clock-message"></div>
                        </div>
                        {{--<div class="timer-text"></div>--}}
                    </div>
                </div>
            @endif
            <div class="row camping-for-popups"></div>
        </div>
    </section>
    @include('partials.patient-ready-to-purchase-with-external-api')
@endsection

