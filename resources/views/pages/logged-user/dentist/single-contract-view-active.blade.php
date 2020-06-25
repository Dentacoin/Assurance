@extends('layout')
@section('content')
    @php($patient = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->patient_id))
    <section class="padding-top-100 padding-top-xs-30 padding-top-sm-50 single-contract-view-section active" data-created-at="{{strtotime($contract->contract_active_at)}}" data-contract="{{$contract->slug}}" data-patient="{{$contract->patient_address}}" data-dentist="{{$contract->dentist_address}}" @if($contract->is_processing) data-processing-contract="true" @else data-processing-contract="false" @endif>
        <section class="container">
            <div class="row">
                <div class="col-xs-12"><h1 class="lato-bold text-center fs-45 fs-xs-30">Dentacoin Assurance Contract</h1></div>
            </div>
            <div class="row">
                @include('partials.contract-single-page-nav', ['dentist_data' => $current_logged_dentist, 'patient_data' => $patient, 'type' => 'desktop'])
            </div>
        </section>
        <section class="container single-contract-tile module pending text-center padding-top-20 @if(isset($mobile) && $mobile) mobile @endif">
            @if ($contract->is_processing && isset($mobile) && $mobile)
                @include('partials.processing-contract-mobile')
            @endif
            <div class="row fs-0 patient-dentist-data">
                <div class="col-xs-4 col-md-3 contract-participant text-center inline-block-top padding-top-35 padding-bottom-35 white-color-background padding-left-xs-5 padding-right-xs-5 padding-top-xs-15 padding-bottom-xs-15 dentist">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="Dentist avatar" src="{{$current_logged_dentist->avatar_url}}"/>
                    </figure>
                    <div class="fs-22 fs-xs-18 calibri-bold padding-top-15 padding-bottom-5">{{(new \App\Http\Controllers\Controller())->prepareUserName($current_logged_dentist)}}</div>
                    <div class="calibri-light fs-18 fs-xs-16 light-gray-color word-break">{{$current_logged_dentist->email}}</div>
                </div>
                <div class="col-xs-3 inline-block-top margin-top-40 margin-top-xs-0 contract-body" data-time-left-next-transfer="{{strtotime($contract->contract_active_at)}}">
                    @if ($contract->is_processing && isset($mobile) && !$mobile)
                        @include('partials.processing-contract-desktop')
                    @endif
                    <div class="contact-body-wrapper">
                        <div class="contract-header text-center lato-bold fs-20 white-color padding-top-15 padding-bottom-15 active">ACTIVE</div>
                        @if(isset($mobile) && !$mobile && !$contract->is_processing)
                            <div class="wrapper">
                                <div class="lato-bold fs-20 padding-top-15 padding-bottom-10 padding-left-10 padding-right-10 timer-label"></div>
                                <div class="clock"></div>
                                <div class="flip-clock-message"></div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-xs-4 col-md-3 contract-participant text-center inline-block-top padding-top-35 padding-bottom-35 white-color-background padding-left-xs-5 padding-right-xs-5 padding-top-xs-15 padding-bottom-xs-15">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="Patient avatar" src="{{$patient->avatar_url}}"/>
                    </figure>
                    <div class="fs-22 fs-xs-18 calibri-bold padding-top-15 padding-bottom-5">{{$patient->name}}</div>
                    <div class="calibri-light fs-18">
                        <a href="mailto:{{$patient->email}}" class="light-gray-color fs-18 fs-xs-16 word-break">{{$patient->email}}</a>
                    </div>
                </div>
            </div>
            @if(isset($mobile) && $mobile && !$contract->is_processing)
                <div class="row contract-footer">
                    <div class="col-xs-12 col-sm-8 col-sm-offset-2 padding-top-30 padding-bottom-40 padding-left-50 padding-right-50 text-center fs-20 wrapper padding-top-xs-20 padding-bottom-xs-0 padding-left-xs-15 padding-right-xs-15">
                        <div class="lato-bold fs-20 padding-bottom-5 timer-label"></div>
                        <div class="clock"></div>
                        <div class="flip-clock-message"></div>
                        {{--<div class="timer-text"></div>--}}
                    </div>
                </div>
            @endif
            <div class="row camping-for-popups" data-patient-name="{{$patient->name}}"></div>
            <div class="row fs-0 padding-top-40 row-with-bottom-squares text-center">
                <div class="col-sm-3 col-xs-12 inline-block padding-top-15 padding-bottom-15 border-right-light-gray">
                    <h3 class="fs-20 calibri-bold">Date Signed:</h3>
                    <time class="display-block padding-top-10 calibri-light fs-20">{{date('d/m/Y', strtotime($contract->contract_active_at))}}</time>
                </div>
                <div class="col-sm-3 col-xs-12 inline-block padding-top-15 padding-bottom-15 border-right-light-gray">
                    <h3 class="fs-20 calibri-bold">Monthly Premium:</h3>
                    <div class="display-block padding-top-10 calibri-light fs-20">{{$contract->monthly_premium}} USD</div>
                </div>
                <div class="col-sm-3 col-xs-12 inline-block padding-top-15 padding-bottom-15">
                    <h3 class="fs-20 calibri-bold">Next Withdraw:</h3>
                    <time class="display-block padding-top-10 calibri-light fs-20 next-payment"></time>
                </div>
            </div>
        </section>
        {{--<section class="container camping-withdraw-time-left-section"></section>--}}
        <section class="container contract-details">
            <div class="row text-center">
                <div class="col-xs-12 col-lg-10 col-lg-offset-1 no-gutter-xs">
                    <div class="contract-details-container module fs-16 text-left padding-top-40 padding-bottom-60">
                        @include('partials.contract-details', ['type' => 'single-page', 'contract' => $contract, 'dentist' => $current_logged_dentist, 'patient' => $patient, 'calculator_proposals' => $calculator_proposals])
                    </div>
                    <div class="padding-top-40">
                        <a href="javascript:void(0)" class="open-contract-details fs-20 calibri-bold blue-green-color" data-label-closed="See details" data-label-opened="Hide details" data-hidden-details="true">See details</a>
                    </div>
                </div>
            </div>
        </section>
        <section class="container">
            <div class="row">@include('partials.contract-single-page-nav', ['dentist_data' => $current_logged_dentist, 'patient_data' => $patient, 'type' => 'mobile'])</div>
        </section>
        @if(!empty($recordsHistory))
            @include('partials.records-history', ['contract' => $contract])
        @endif
    </section>
@endsection