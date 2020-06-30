@extends('layout')
@section('content')
    @php($patient = (new \App\Http\Controllers\APIRequestsController())->getUserData(session('logged_user')['id']))
    @php($dentist = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->dentist_id))
    @php($cancellation_reason = unserialize($contract->cancellation_reason))
    @if(is_object($contract->contract_active_at))
        @php($contract_active_at = $contract->contract_active_at->getTimestamp())
    @else
        @php($contract_active_at = strtotime($contract->contract_active_at))
    @endif
    <section class="padding-top-100 padding-top-xs-30 padding-top-sm-50 patient-contract-single-page-section cancelled" data-date-start-contract="{{$contract_active_at}}" data-patient="{{$contract->patient_address}}">
        <div class="container">
            <div class="row">
                @include('partials.contract-single-page-title')
            </div>
            <div class="row">
                @include('partials.contract-single-page-nav', ['type' => 'desktop'])
            </div>
        </div>
        <div class="container single-contract-tile module text-center padding-top-20 @if(isset($mobile) && $mobile) mobile @endif">
            <div class="row fs-0 patient-dentist-data">
                <div class="col-xs-4 col-md-3 contract-participant text-center inline-block-top padding-top-35 padding-bottom-35 white-color-background padding-left-xs-5 padding-right-xs-5 padding-top-xs-15 padding-bottom-xs-15 dentist">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="Dentist avatar" src="{{$dentist->avatar_url}}"/>
                    </figure>
                    <div class="fs-22 fs-xs-18 calibri-bold padding-top-15 padding-bottom-5">{{(new \App\Http\Controllers\Controller())->prepareUserName($dentist)}}</div>
                    <div class="calibri-light">
                        <a href="mailto:{{$dentist->email}}" class="light-gray-color fs-18 fs-xs-16 word-break">{{$dentist->email}}</a>
                    </div>
                </div>
                <div class="col-xs-4 col-md-3 inline-block-top margin-top-40 margin-top-xs-0 contract-body text-center">
                    <div class="contract-header text-center lato-bold fs-20 white-color padding-top-10 padding-bottom-15 cancelled">CANCELLED</div>
                    @if(isset($mobile) && !$mobile)
                        <div class="padding-left-15 padding-right-15 wrapper padding-bottom-15">
                            <div class="cancelled-color fs-20 calibri-bold padding-top-15">Date Cancelled:</div>
                            <time class="display-block calibri-light fs-20">{{date('d/m/Y', strtotime($contract->cancelled_at))}}</time>
                            <div class="cancelled-color fs-20 calibri-bold padding-top-10">Cancellation Reason:</div>
                            <div class="calibri-light fs-20">{{$cancellation_reason['reason']}}</div>
                            @if(!empty($cancellation_reason['comments']))
                                <div class="cancelled-color fs-20 calibri-bold padding-top-10">Cancellation Comments:</div>
                                <div class="calibri-light fs-20">{{$cancellation_reason['comments']}}</div>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="col-xs-4 col-md-3 contract-participant text-center inline-block-top padding-top-35 padding-bottom-35 white-color-background padding-left-xs-5 padding-right-xs-5 padding-top-xs-15 padding-bottom-xs-15">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="Patient avatar" src="{{$patient->avatar_url}}"/>
                    </figure>
                    <div class="fs-22 fs-xs-18 calibri-bold padding-top-15 padding-bottom-5">{{$patient->name}}</div>
                    <div class="calibri-light fs-18 fs-xs-16 light-gray-color word-break">{{$patient->email}}</div>
                </div>
            </div>
            <div class="row fa-0 padding-top-40 padding-top-xs-10 row-with-bottom-squares text-center">
                @if(isset($mobile) && $mobile)
                    <div class="col-xs-12 padding-top-15 padding-bottom-15 border-right-light-gray">
                        <div class="cancelled-color fs-20 calibri-bold padding-top-15">Date Cancelled:</div>
                        <time class="display-block calibri-light fs-18">{{date('d/m/Y', strtotime($contract->cancelled_at))}}</time>
                        <div class="cancelled-color fs-20 calibri-bold padding-top-10">Cancellation Reason:</div>
                        <div class="calibri-light fs-18">{{$cancellation_reason['reason']}}</div>
                        <div class="cancelled-color fs-20 calibri-bold padding-top-10">Cancellation Comments:</div>
                        @if(!empty($cancellation_reason['comments']))
                            <div class="cancelled-color fs-18 calibri-bold padding-top-10">Cancellation Comments:</div>
                            <div class="calibri-light fs-18">{{$cancellation_reason['comments']}}</div>
                        @endif
                    </div>
                @endif
                <div class="col-sm-3 col-xs-12 inline-block padding-top-15 padding-bottom-15 border-right-light-gray">
                    <h3 class="fs-20 calibri-bold">Date Signed:</h3>
                    @if(!empty($contract->contract_active_at))
                        <time class="display-block padding-top-10 calibri-light fs-20">{{date('d/m/Y', strtotime($contract->contract_active_at))}}</time>
                    @else
                        <div class="cancelled-color fs-20 fs-xs-18 calibri-light padding-top-10">You have cancelled the contract before signing it.</div>
                    @endif
                </div>
                <div class="col-sm-3 col-xs-12 inline-block padding-top-15 padding-bottom-15">
                    <h3 class="fs-20 calibri-bold">Monthly Premium:</h3>
                    <div class="display-block padding-top-10 calibri-light fs-20 fs-xs-18">{{$contract->monthly_premium}} USD</div>
                </div>
            </div>
        </div>
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
        <section class="container">
            <div class="row">@include('partials.contract-single-page-nav', ['type' => 'mobile'])</div>
        </section>
        @if(!empty($recordsHistory))
            @include('partials.records-history', ['contract' => $contract])
        @endif
    </section>
@endsection