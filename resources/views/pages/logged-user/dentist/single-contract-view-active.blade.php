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
                        <a href="javascript:void(0)" class="dentist-withdraw white-blue-green-btn display-block-important margin-0-auto max-width-280"><svg class="max-width-30 inline-block margin-right-5" version="1.1" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 75.7 74.3" style="enable-background:new 0 0 75.7 74.3;" xml:space="preserve"><style type="text/css">.st0{fill:#FFFFFF;}</style><metadata><sfw  xmlns="&ns_sfw;"><slices></slices><sliceSourceBounds bottomLeftOrigin="true" height="74.3" width="75.7" x="12.2" y="37.8"></sliceSourceBounds></sfw></metadata><path class="st0" d="M29.7,32.2h-7.5l0,0c-0.1,0-0.2,0-0.3,0h-0.1c-0.1,0-0.1,0-0.2,0.1c-0.1,0-0.1,0-0.2,0.1c-0.1,0-0.1,0.1-0.2,0.1c-0.1,0-0.1,0.1-0.2,0.1l-0.1,0.1c-0.1,0-0.1,0.1-0.2,0.2l0,0L20.6,33c0,0.1-0.1,0.1-0.1,0.2s-0.1,0.1-0.1,0.2c0,0.1-0.1,0.1-0.1,0.2s0,0.1-0.1,0.2c0,0.1,0,0.1,0,0.2s0,0.1,0,0.2v0.1l0,0c0,0.1,0,0.2,0,0.2c0,0.1,0,0.1,0,0.2c0,0.1,0,0.1,0.1,0.2c0,0.1,0,0.1,0.1,0.2c0,0.1,0.1,0.1,0.1,0.2s0.1,0.1,0.1,0.2l0.1,0.1c0,0.1,0.1,0.1,0.1,0.2l0,0l15.4,14.5c0.4,0.4,0.9,0.5,1.4,0.5s1-0.2,1.4-0.5l15.4-14.5c0.8-0.8,0.8-2,0.1-2.8c-0.4-0.5-1-0.7-1.6-0.6h-0.1h-7.5v-2.9c0-1.1-0.9-2-2-2s-2,0.9-2,2v4.9c0,1.1,0.9,2,2,2H48l-10.4,9.8l-10.4-9.8h4.4c1.1,0,2-0.9,2-2v-4.9c0-1.1-0.9-2-2-2c-1.1,0-2,0.9-2,2L29.7,32.2L29.7,32.2z"/><path class="st0" d="M29.7,23.3c0,1.1,0.9,2,2,2c1.1,0,2-0.9,2-2v-3.2c0-1.1-0.9-2-2-2c-1.1,0-2,0.9-2,2V23.3z"/><path class="st0" d="M41.3,23.3c0,1.1,0.9,2,2,2s2-0.9,2-2v-3.2c0-1.1-0.9-2-2-2s-2,0.9-2,2V23.3z"/><path class="st0" d="M29.7,12.8c0,1.1,0.9,2,2,2c1.1,0,2-0.9,2-2v-1.4c0-1.1-0.9-2-2-2c-1.1,0-2,0.9-2,2V12.8z"/><path class="st0" d="M41.3,12.8c0,1.1,0.9,2,2,2s2-0.9,2-2v-1.4c0-1.1-0.9-2-2-2s-2,0.9-2,2V12.8z"/><path class="st0" d="M31.7,4.1c1.1,0,2-0.9,2-2V2c0-1.1-0.9-2-2-2c-1.1,0-2,0.9-2,2v0.1C29.7,3.2,30.6,4.1,31.7,4.1z"/><path class="st0" d="M43.3,4.1c1.1,0,2-0.9,2-2V2c0-1.1-0.9-2-2-2s-2,0.9-2,2v0.1C41.3,3.2,42.2,4.1,43.3,4.1z"/><path class="st0" d="M75.7,51.4V37.2c0-9.4-9.2-17.7-23.4-21.2c-1.1-0.3-2.2,0.4-2.4,1.5c-0.3,1.1,0.4,2.2,1.5,2.4c12.4,3,20.4,9.8,20.4,17.3c0,10.2-15.5,18.9-33.9,18.9S4,47.5,4,37.2c0-7.3,7.8-14.1,19.9-17.2c1.1-0.3,1.7-1.4,1.4-2.4c-0.3-1.1-1.4-1.7-2.4-1.4C9,19.8,0,28,0,37.2v14.2c0,12.8,16.6,22.9,37.9,22.9S75.7,64.2,75.7,51.4z M71.7,47.6v3.9c0,2.9-1.2,5.7-3.5,8.1V51C69.5,49.9,70.7,48.8,71.7,47.6z M11.2,53.6c1.4,0.8,2.9,1.6,4.6,2.3v9.7c-1.7-0.8-3.2-1.7-4.6-2.7V53.6zM19.8,57.4c1.8,0.6,3.6,1,5.5,1.4v10.1c-1.9-0.4-3.8-1-5.5-1.6V57.4z M29.3,59.5c2.1,0.3,4.3,0.5,6.5,0.5v10.2c-2.2-0.1-4.4-0.3-6.5-0.6V59.5z M39.8,60c2.2-0.1,4.3-0.2,6.3-0.5v10.2c-2,0.3-4.2,0.5-6.3,0.6V60z M50.1,58.8c1.9-0.4,3.8-0.9,5.5-1.4v9.9c-1.7,0.6-3.6,1.1-5.5,1.6C50.1,68.9,50.1,58.8,50.1,58.8z M59.7,56c1.6-0.7,3.2-1.4,4.6-2.3v9.4c-1.4,1-2.9,1.8-4.6,2.6V56z M3.9,51.4v-3.9c1,1.2,2.1,2.3,3.3,3.3v8.6C5.1,57,3.9,54.3,3.9,51.4z"/></svg> WITHDRAW NOW</a>
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