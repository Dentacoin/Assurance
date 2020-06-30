@extends("layout")
@section("content")
    @php($dentist = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->dentist_id))
    @php($patient = (new \App\Http\Controllers\APIRequestsController())->getUserData(session('logged_user')['id']))
    @if(is_object($contract->contract_active_at))
        @php($contract_active_at = $contract->contract_active_at->getTimestamp())
    @else
        @php($contract_active_at = strtotime($contract->contract_active_at))
    @endif

    @php($timeSinceContractSigning = (new \App\Http\Controllers\Controller())->convertMS(time() - $contract_active_at))
    @php($yearsActionsToBeExecuted = 1)
    @if(array_key_exists('days', $timeSinceContractSigning) && $timeSinceContractSigning['days'] >= 365)
        @php($yearsActionsToBeExecuted += floor($timeSinceContractSigning['days'] / 365))
    @endif

    @php($periodBegin = date('Y-m-d', strtotime(' + ' . (365 * ($yearsActionsToBeExecuted - 1)) . ' days', $contract_active_at)))
    @php($periodEnd = date('Y-m-d', strtotime(' + ' . (365 * $yearsActionsToBeExecuted) . ' days', $contract_active_at)))

    @php($currentCheckups = (new \App\Http\Controllers\PatientController())->getCheckUpOrTeethCleaning('check-up', $contract->slug, $periodBegin, $periodEnd, array('sent', 'approved')))
    @php($currentTeethCleanings = (new \App\Http\Controllers\PatientController())->getCheckUpOrTeethCleaning('teeth-cleaning', $contract->slug, $periodBegin, $periodEnd, array('sent', 'approved')))
    <section class="padding-top-100 padding-top-xs-30 padding-top-sm-50 patient-contract-single-page-section" data-date-start-contract="{{$contract_active_at}}" data-patient="{{$contract->patient_address}}" data-dentist="{{$contract->dentist_address}}" data-checkups="{{$contract->check_ups_per_year}}" data-teeth-cleanings="{{$contract->teeth_cleaning_per_year}}" data-contract="{{$contract->slug}}" @if($contract->is_processing) data-processing-contract="true" @else data-processing-contract="false" @endif>
        <div class="container">
            <div class="row">
                @include('partials.contract-single-page-title')
            </div>
            <div class="row">
                @include('partials.contract-single-page-nav', ['dentist_data' => $dentist, 'patient_data' => $patient, 'contract_active_at' => $contract_active_at, 'currentCheckups' => $currentCheckups, 'currentTeethCleanings' => $currentTeethCleanings, 'type' => 'desktop'])
            </div>
        </div>
        <div class="container single-contract-tile module text-center padding-top-20 @if(isset($mobile) && $mobile) mobile @endif">
            @if ($contract->is_processing && isset($mobile) && $mobile)
                @include('partials.processing-contract-mobile')
            @endif
            <div class="row fs-0 patient-dentist-data">
                <div class="col-xs-4 col-md-3 contract-participant text-center inline-block padding-top-35 padding-bottom-35 white-color-background padding-left-xs-5 padding-right-xs-5 padding-top-xs-15 padding-bottom-xs-15 dentist">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="Dentist avatar" src="{{$dentist->avatar_url}}"/>
                    </figure>
                    <div class="fs-22 fs-xs-18 calibri-bold padding-top-15 padding-bottom-5">{{(new \App\Http\Controllers\Controller())->prepareUserName($dentist)}}</div>
                    <div class="calibri-light">
                        <a href="mailto:{{$dentist->email}}" class="light-gray-color fs-18 fs-xs-16 word-break">{{$dentist->email}}</a>
                    </div>
                </div>
                <div class="col-xs-4 inline-block contract-body">
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
                <div class="col-xs-4 col-md-3 contract-participant text-center inline-block padding-top-35 padding-bottom-35 white-color-background padding-left-xs-5 padding-right-xs-5 padding-top-xs-15 padding-bottom-xs-15">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="Dentist avatar" src="{{$patient->avatar_url}}"/>
                    </figure>
                    <div class="fs-22 fs-xs-18 calibri-bold padding-top-15 padding-bottom-5">{{$patient->name}}</div>
                    <div class="calibri-light fs-18 fs-xs-16 light-gray-color word-break">{{$patient->email}}</div>
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

            @php($months = ['two_months' => 5184000, 'four_months' => 10368000, 'seven_months' => 18144000, 'eight_months' => 20736000, 'ten_months' => 25920000])

            @php($show = array())
            @if($currentCheckups < $contract->check_ups_per_year)
                @if($contract->check_ups_per_year == 1)
                    @if(time() > strtotime($periodBegin) + $months['ten_months'])
                        @php(array_push($show, 'check-up'))
                    @endif
                @elseif($contract->check_ups_per_year == 2)
                    @if(time() > strtotime($periodBegin) + $months['four_months'] && $currentCheckups < 1)
                        @php(array_push($show, 'check-up'))
                    @elseif(time() > strtotime($periodBegin) + $months['ten_months'] && $currentCheckups < 2)
                        @php(array_push($show, 'check-up'))
                    @endif
                @elseif($contract->check_ups_per_year == 3)
                    @if(time() > strtotime($periodBegin) + $months['two_months'] && $currentCheckups < 1)
                        @php(array_push($show, 'check-up'))
                    @elseif(time() > strtotime($periodBegin) + $months['seven_months'] && $currentCheckups < 2)
                        @php(array_push($show, 'check-up'))
                    @elseif(time() > strtotime($periodBegin) + $months['ten_months'] && $currentCheckups < 3)
                        @php(array_push($show, 'check-up'))
                    @endif
                @elseif($contract->check_ups_per_year == 4)
                    @if(time() > strtotime($periodBegin) + $months['two_months'] && $currentCheckups < 1)
                        @php(array_push($show, 'check-up'))
                    @elseif(time() > strtotime($periodBegin) + $months['four_months'] && $currentCheckups < 2)
                        @php(array_push($show, 'check-up'))
                    @elseif(time() > strtotime($periodBegin) + $months['eight_months'] && $currentCheckups < 3)
                        @php(array_push($show, 'check-up'))
                    @elseif(time() > strtotime($periodBegin) + $months['ten_months'] && $currentCheckups < 4)
                        @php(array_push($show, 'check-up'))
                    @endif
                @endif
            @endif

            @if($currentTeethCleanings < $contract->teeth_cleaning_per_year)
                @if($contract->teeth_cleaning_per_year == 1)
                    @if(time() > strtotime($periodBegin) + $months['ten_months'])
                        @php(array_push($show, 'teeth-cleaning'))
                    @endif
                @elseif($contract->teeth_cleaning_per_year == 2)
                    @if(time() > strtotime($periodBegin) + $months['four_months'] && $currentTeethCleanings < 1)
                        @php(array_push($show, 'teeth-cleaning'))
                    @elseif(time() > strtotime($periodBegin) + $months['ten_months'] && $currentTeethCleanings < 2)
                        @php(array_push($show, 'teeth-cleaning'))
                    @endif
                @elseif($contract->teeth_cleaning_per_year == 3)
                    @if(time() > strtotime($periodBegin) + $months['two_months'] && $currentTeethCleanings < 1)
                        @php(array_push($show, 'teeth-cleaning'))
                    @elseif(time() > strtotime($periodBegin) + $months['seven_months'] && $currentTeethCleanings < 2)
                        @php(array_push($show, 'teeth-cleaning'))
                    @elseif(time() > strtotime($periodBegin) + $months['ten_months'] && $currentTeethCleanings < 3)
                        @php(array_push($show, 'teeth-cleaning'))
                    @endif
                @elseif($contract->teeth_cleaning_per_year == 4)
                    @if(time() > strtotime($periodBegin) + $months['two_months'] && $currentTeethCleanings < 1)
                        @php(array_push($show, 'teeth-cleaning'))
                    @elseif(time() > strtotime($periodBegin) + $months['four_months'] && $currentTeethCleanings < 2)
                        @php(array_push($show, 'teeth-cleaning'))
                    @elseif(time() > strtotime($periodBegin) + $months['eight_months'] && $currentTeethCleanings < 3)
                        @php(array_push($show, 'teeth-cleaning'))
                    @elseif(time() > strtotime($periodBegin) + $months['ten_months'] && $currentTeethCleanings < 4)
                        @php(array_push($show, 'teeth-cleaning'))
                    @endif
                @endif
            @endif
            <div class="row camping-for-popups @if(!empty($show)) @foreach($show as $showItem) {{$showItem . ' '}} @endforeach @endif"></div>
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
    <section class="container">
        <div class="row">@include('partials.contract-single-page-nav', ['type' => 'mobile'])</div>
    </section>
    @if(!empty($recordsHistory))
        @include('partials.records-history', ['contract' => $contract])
    @endif
    <div class="external-api-crypto-provider hide">
        @if (!$contract->is_processing)
            @include('partials.patient-ready-to-purchase-with-external-api', ['contract' => $contract])
        @endif
    </div>
@endsection

