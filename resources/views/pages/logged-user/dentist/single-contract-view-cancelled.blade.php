@extends('layout')
@section('content')
    @if(is_object($contract->contract_active_at))
        @php($active_timestamp = strtotime($contract->contract_active_at->getTimestamp()))
    @else
        @php($active_timestamp = strtotime($contract->contract_active_at))
    @endif
    @if(is_object($contract->cancelled_at))
        @php($cancelled_timestamp = strtotime($contract->cancelled_at->getTimestamp()))
    @else
        @php($cancelled_timestamp = strtotime($contract->cancelled_at))
    @endif

    @php($cancellation_reason = unserialize($contract->cancellation_reason))
    @if(!empty($contract->patient_id))
        @php($patient = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->patient_id))
        @php($patient_name = $patient->name)
        @php($patient_email = $patient->email)
        @php($avatar_url = $patient->avatar_url)
    @else
        @php($patient_name = $contract->patient_fname . ' ' . $contract->patient_lname)
        @php($patient_email = $contract->patient_email)
        @php($avatar_url = '/assets/images/no-avatar.png')
    @endif
    <section class="padding-top-100 padding-top-xs-30 padding-top-sm-50 single-contract-view-section cancelled" data-created-at="{{strtotime($active_timestamp)}}">
        {{var_dump($cancelled_timestamp)}}
        <div class="container">
            <div class="row">
                <div class="col-xs-12"><h1 class="lato-bold text-center fs-45 fs-xs-30">Dentacoin Assurance Contract</h1></div>
            </div>
            <div class="row">
                @include('partials.contract-single-page-nav')
            </div>
        </div>
        <div class="container single-contract-tile module pending text-center padding-top-20 @if(isset($mobile) && $mobile) mobile @endif">
            <div class="row fs-0 patient-dentist-data">
                <div class="col-xs-4 col-md-3 contract-participant text-center inline-block-top padding-top-35 padding-bottom-35 white-color-background padding-left-xs-5 padding-right-xs-5 padding-top-xs-15 padding-bottom-xs-15 dentist">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="Dentist avatar" src="{{$current_logged_dentist->avatar_url}}"/>
                    </figure>
                    <div class="fs-22 fs-xs-18 calibri-bold padding-top-15 padding-bottom-5">Dr. {{$current_logged_dentist->name}}</div>
                    <div class="calibri-light fs-18 fs-xs-16 light-gray-color word-break">{{$current_logged_dentist->email}}</div>
                </div>
                <div class="col-xs-3 inline-block-top margin-top-40 margin-top-xs-0 contract-body text-center" data-time-left-next-transfer="{{$active_timestamp}}">
                    <div class="contract-header text-center lato-bold fs-20 white-color padding-top-10 padding-bottom-15 cancelled">CANCELLED</div>
                    @if(isset($mobile) && !$mobile)
                        <div class="padding-left-15 padding-right-15 wrapper padding-bottom-15">
                            <div class="cancelled-color fs-20 calibri-bold padding-top-15">Date Cancelled:</div>
                            <time class="display-block calibri-light fs-20">{{date('d/m/Y', $cancelled_timestamp)}}</time>
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
                        <img alt="Patient avatar" src="{{$avatar_url}}"/>
                    </figure>
                    <div class="fs-22 fs-xs-18 calibri-bold padding-top-15 padding-bottom-5">{{$patient_name}}</div>
                    <div class="calibri-light">
                        <a href="mailto:{{$patient_email}}" class="light-gray-color fs-18 fs-xs-16 word-break">{{$patient_email}}</a>
                    </div>
                </div>
            </div>
            <div class="row fa-0 padding-top-40 padding-top-xs-10 row-with-bottom-squares text-center">
                @if(isset($mobile) && $mobile)
                    <div class="col-xs-12 padding-top-15 padding-bottom-15 border-right-light-gray">
                        <div class="cancelled-color fs-20 calibri-bold padding-top-15">Date Cancelled:</div>
                        <time class="display-block calibri-light fs-18">{{date('d/m/Y', $cancelled_timestamp)}}</time>
                        <div class="cancelled-color fs-20 calibri-bold padding-top-10">Cancellation Reason:</div>
                        <div class="calibri-light fs-18">{{$cancellation_reason['reason']}}</div>
                        <div class="cancelled-color fs-20 calibri-bold padding-top-10">Cancellation Comments:</div>
                        <div class="calibri-light fs-18">{{$cancellation_reason['comments']}}</div>
                    </div>
                @endif
                <div class="col-sm-3 col-xs-12 inline-block padding-top-15 padding-bottom-15 border-right-light-gray">
                    <h3 class="fs-20 calibri-bold">Date Signed:</h3>
                    @if(!empty($active_timestamp))
                        <time class="display-block padding-top-10 calibri-light fs-20">{{date('d/m/Y', $active_timestamp)}}</time>
                    @else
                        <div class="cancelled-color fs-20 calibri-light padding-top-10">Cancelled before patient signing the contract.</div>
                    @endif
                </div>
                <div class="col-sm-3 col-xs-12 inline-block padding-top-15 padding-bottom-15">
                    <h3 class="fs-20 calibri-bold">Monthly Premium:</h3>
                    <div class="display-block padding-top-10 calibri-light fs-20">{{$contract->monthly_premium}} USD</div>
                </div>
            </div>
        </div>
        <section class="container contract-details">
            <div class="row text-center">
                <div class="col-xs-12 col-lg-10 col-lg-offset-1 no-gutter-xs">
                    <div class="contract-details-container module fs-16 text-left padding-top-40 padding-bottom-60">
                        @php($contract_details_params = ['type' => 'single-page', 'contract' => $contract, 'dentist' => $current_logged_dentist, 'calculator_proposals' => $calculator_proposals])
                        @if(!empty($patient))
                            @php($contract_details_params['patient'] = $patient)
                        @endif
                        @include('partials.contract-details', $contract_details_params)
                    </div>
                    <div class="padding-top-40">
                        <a href="javascript:void(0)" class="open-contract-details fs-20 calibri-bold blue-green-color" data-label-closed="See details" data-label-opened="Hide details" data-hidden-details="true">See details</a>
                    </div>
                </div>
            </div>
        </section>
    </section>
@endsection