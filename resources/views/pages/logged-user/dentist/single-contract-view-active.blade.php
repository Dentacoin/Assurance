@extends('layout')
@section('content')
    @php($dentist = (new \App\Http\Controllers\APIRequestsController())->getUserData(session('logged_user')['id']))
    @php($patient = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->patient_id))
    <section class="padding-top-100 padding-top-xs-30 padding-top-sm-50 single-contract-view-section active" data-created-at="{{strtotime($contract->contract_active_at)}}" data-timestamp-signed="{{strtotime($contract->contract_active_at)}}">
        <section class="container">
            <div class="row">
                <div class="col-xs-12"><h1 class="lato-bold text-center fs-45 fs-xs-30">Dentacoin Assurance Contract</h1></div>
            </div>
            <div class="row">
                @include('partials.contract-single-page-nav', ['dentist_data' => $dentist, 'patient_data' => $patient])
            </div>
        </section>
        <section class="container single-contract-tile module pending text-center padding-top-20">
            <div class="row fs-0 flex-xs">
                <div class="col-xs-4 col-md-3 contract-participant text-center inline-block-top padding-top-35 padding-bottom-35 white-color-background padding-left-xs-5 padding-right-xs-5 padding-top-xs-15 padding-bottom-xs-15">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="Dentist avatar" src="{{$dentist->avatar_url}}" class="max-width-120"/>
                    </figure>
                    <div class="fs-22 fs-xs-18 calibri-bold padding-top-15 padding-bottom-5">Dr. {{$dentist->name}}</div>
                    <div class="calibri-light fs-18 fs-xs-16 light-gray-color word-break">{{$dentist->email}}</div>
                </div>
                <div class="col-xs-3 inline-block-top margin-top-40 margin-top-xs-0 contract-body" data-time-left-next-transfer="{{strtotime($contract->contract_active_at)}}">
                    <div class="contract-header text-center lato-bold fs-20 white-color padding-top-15 padding-bottom-15 active">ACTIVE</div>
                    <div class="wrapper">
                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="absolute-hands">
                            <img alt="Dentist avatar" src="/assets/uploads/active-hands.svg"/>
                        </figure>
                    </div>
                </div>
                <div class="col-xs-4 col-md-3 contract-participant text-center inline-block-top padding-top-35 padding-bottom-35 white-color-background padding-left-xs-5 padding-right-xs-5 padding-top-xs-15 padding-bottom-xs-15">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="Patient avatar" src="{{$patient->avatar_url}}" class="max-width-120"/>
                    </figure>
                    <div class="fs-22 fs-xs-18 calibri-bold padding-top-15 padding-bottom-5">{{$patient->name}}</div>
                    <div class="calibri-light fs-18">
                        <a href="mailto:{{$patient->email}}" class="light-gray-color fs-18 fs-xs-16 word-break">{{$patient->email}}</a>
                    </div>
                </div>
            </div>
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
                    <h3 class="fs-20 calibri-bold">Next Payment:</h3>
                    <time class="display-block padding-top-10 calibri-light fs-20 next-payment"></time>
                </div>
            </div>
        </section>
        <section class="container withdraw-section padding-top-30 padding-bottom-30">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <div>Your money is waiting for you.</div>
                    <div class="padding-bottom-20">Withdraw the Dentacoin tokens collected by Emilly Newman.</div>
                    <div>
                        <a href="javascript:void(0)" class="dentist-withdraw white-blue-green-btn display-block margin-0-auto max-width-280"><img src="/assets/images/withdraw.svg" alt="" class="max-width-30 margin-right-5"/> WITHDRAW NOW</a>
                    </div>
                </div>
            </div>
        </section>
        <section class="container contract-details">
            <div class="row text-center">
                <div class="col-xs-12 col-lg-10 col-lg-offset-1 no-gutter-xs">
                    <div class="padding-top-40">
                        <a href="javascript:void(0)" class="open-contract-details fs-20 calibri-bold blue-green-color">See details</a>
                    </div>
                    <div class="contract-details-container module fs-16 text-left padding-top-40 padding-bottom-60">
                        @include('partials.contract-details', ['type' => 'single-page', 'contract' => $contract, 'dentist' => $dentist, 'patient' => $patient, 'calculator_proposals' => $calculator_proposals])
                    </div>
                </div>
            </div>
        </section>
    </section>
@endsection