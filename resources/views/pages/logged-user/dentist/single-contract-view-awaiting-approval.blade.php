@extends('layout')
@section('content')
    @php($patient = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->patient_id))
    @if(is_object($contract->contract_active_at))
        @php($contract_active_at = $contract->contract_active_at->getTimestamp())
    @else
        @php($contract_active_at = strtotime($contract->contract_active_at))
    @endif
    <section class="padding-top-100 padding-top-xs-30 padding-top-sm-50 single-contract-view-section awaiting-approval dentist-contract-single-page-section" data-created-at="{{strtotime($contract->contract_active_at)}}" data-date-start-contract="{{$contract_active_at}}" data-patient="{{$contract->patient_address}}" data-dentist="{{$contract->dentist_address}}" data-contract="{{$contract->slug}}" @if($contract->is_processing) data-processing-contract="true" @else data-processing-contract="false" @endif>
        <section class="container">
            <div class="row">
                @include('partials.contract-single-page-title')
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
                <div class="col-xs-4 col-md-3 contract-participant text-center inline-block padding-top-35 padding-bottom-35 white-color-background padding-left-xs-5 padding-right-xs-5 padding-top-xs-15 padding-bottom-xs-15 dentist">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="Dentist avatar" src="{{$current_logged_dentist->avatar_url}}"/>
                    </figure>
                    <div class="fs-22 fs-x-18 calibri-bold padding-top-15 padding-bottom-5">{{(new \App\Http\Controllers\Controller())->prepareUserName($current_logged_dentist)}}</div>
                    <div class="calibri-light fs-18 fs-xs-16 light-gray-color word-break">{{$current_logged_dentist->email}}</div>
                </div>
                {{--<div class="col-xs-3 inline-block-top margin-top-40 margin-top-xs-0 contract-body" data-time-left-next-transfer="{{strtotime($contract->contract_active_at)}}">
                    <div class="contract-header text-center lato-bold fs-20 fs-sm-18 white-color padding-top-15 padding-bottom-15 awaiting-approval">@if(isset($mobile) && !$mobile)ACTIVE -@endif AWAITING APPROVAL</div>
                    <div class="wrapper">
                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="absolute-hands">
                            <img alt="Dentist avatar" src="/assets/uploads/pending-hands.svg"/>
                        </figure>
                        <figure class="inline-block rotate-animation" itemscope="" itemtype="http://schema.org/ImageObject">
                            <img src="/assets/uploads/rotating-icon.png" alt="Loading icon" itemprop="contentUrl">
                        </figure>
                    </div>
                </div>--}}
                <div class="col-xs-4 inline-block contract-body">
                    @if ($contract->is_processing && isset($mobile) && !$mobile)
                        @include('partials.processing-contract-desktop')
                    @endif
                    <div class="contact-body-wrapper">
                        <div class="contract-header text-center lato-bold fs-20 white-color padding-top-15 padding-bottom-15 awaiting-approval">@if(isset($mobile) && !$mobile)ACTIVE -@endif AWAITING APPROVAL</div>
                        {{--@if(isset($mobile) && !$mobile && !$contract->is_processing)
                            <div class="wrapper">
                                <div class="lato-bold fs-20 padding-top-15 padding-bottom-10 padding-left-10 padding-right-10 timer-label"></div>
                                <div class="clock"></div>
                                <div class="flip-clock-message"></div>
                            </div>
                        @endif--}}
                    </div>
                </div>
                <div class="col-xs-4 col-md-3 contract-participant text-center inline-block padding-top-35 padding-bottom-35 white-color-background padding-left-xs-5 padding-right-xs-5 padding-top-xs-15 padding-bottom-xs-15">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="Patient avatar" src="{{$patient->avatar_url}}"/>
                    </figure>
                    <div class="fs-22 fs-xs-18 calibri-bold padding-top-15 padding-bottom-5">{{$patient->name}}</div>
                    <div class="calibri-light">
                        <a href="mailto:{{$patient->email}}" class="light-gray-color fs-18 fs-xs-16 word-break">{{$patient->email}}</a>
                    </div>
                </div>
            </div>
            {{--@if(isset($mobile) && $mobile && !$contract->is_processing)
                <div class="row contract-footer">
                    <div class="col-xs-12 col-sm-8 col-sm-offset-2 padding-top-30 padding-bottom-40 padding-left-50 padding-right-50 text-center fs-20 wrapper padding-top-xs-20 padding-bottom-xs-0 padding-left-xs-15 padding-right-xs-15">
                        <div class="lato-bold fs-20 padding-bottom-5 timer-label"></div>
                        <div class="clock"></div>
                        <div class="flip-clock-message"></div>
                        --}}{{--<div class="timer-text"></div>--}}{{--
                    </div>
                </div>
            @endif--}}
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
                    <h3 class="fs-20 calibri-bold">First Payment:</h3>
                    <time class="display-block padding-top-10 calibri-light fs-20 first-payment"></time>
                </div>
            </div>
        </section>
        <section class="container contract-details init-contract-section" data-contract="{{$contract->slug}}">
            <div class="row text-center">
                <div class="col-xs-12">
                    <h2 class="fs-35 fs-xs-30 lato-bold padding-top-50 padding-top-xs-40 padding-bottom-20">Time for Your Final Approval</h2>
                </div>
                <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
                    <div class="fs-20 fs-xs-18 calibri-regular">Now you can check all details and give your approval in order to activate your contract on the blockchain as well.</div>
                </div>
                <div class="col-xs-12 text-center padding-top-30 padding-bottom-20">
                    <a href="javascript:void(0)" class="white-green-btn min-width-250 width-xs-100 max-width-400 scroll-to-begging-of-contact">CHECK & APPROVE</a>
                </div>
                <div class="col-xs-12 col-lg-10 col-lg-offset-1 no-gutter-xs">
                    <div class="contract-details-container show-this module fs-16 text-left padding-top-40 padding-bottom-60" id="begging-of-contact">
                        @include('partials.contract-details', ['type' => 'single-page', 'contract' => $contract, 'dentist' => $current_logged_dentist, 'patient' => $patient, 'calculator_proposals' => $calculator_proposals, 'subtitle' => 'Please read carefully, check all details and approve.<br>There is no way to change anything in the contract later.'])
                    </div>
                    <div class="padding-top-50 padding-left-15 padding-right-15 approve-button-container">
                        <div class="checkbox-container text-center padding-bottom-20">
                            <div class="pretty p-svg p-curve on-white-background inline-block-important">
                                <input type="checkbox" id="read-the-contract-details"/>
                                <div class="state p-success">
                                    <svg class="svg svg-icon" viewBox="0 0 20 20" style="border-color: #126585 !important;top: 0;">
                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                    </svg>
                                    <label class="fs-16 calibri-bold cursor-pointer text-left line-height-24" for="read-the-contract-details">I have read the contract carefully and confirm that all details and conditions are correct. I am informed that I cannot reverse this action.</label>
                                </div>
                            </div>
                        </div>
                        <a href="javascript:void(0)" class="white-green-btn min-width-250 approve-contract-recipe margin-bottom-10 width-xs-100 max-width-400" {{--@if(isset($sent_eth_to_dentist) && $sent_eth_to_dentist) data-sent-eth-to-dentist="true" @endif--}}>APPROVE & ACTIVATE</a>
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
        <div class="external-api-crypto-provider hide margin-top-50">
            @if (!$contract->is_processing)
                @include('partials.patient-ready-to-purchase-with-external-api', ['contract' => $contract, 'type' => 'dentist'])
            @endif
        </div>
    </section>
@endsection