@extends("layout")
@section("content")
    @php($dentist = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->dentist_id))
    @php($patient = (new \App\Http\Controllers\APIRequestsController())->getUserData(session('logged_user')['id']))
    <section class="padding-top-100 padding-top-xs-30 padding-top-sm-50 patient-contract-single-page-section margin-bottom-20" data-monthly-premium="{{$contract->monthly_premium}}" data-patient-address="{{$contract->patient_address}}" data-dentist-address="{{$contract->dentist_address}}" data-date-start-contract="{{strtotime($contract->contract_active_at)}}" data-contract-ipfs="{{$contract->document_hash}}">
        @if(isset($congratulations))
            <div class="contract-response-message module container margin-bottom-50">
                <div class="row">
                    <div class="col-xs-12 col-sm-10 col-sm-offset-1 wrapper text-center">
                        <div class="close-btn">×</div>
                        <figure itemscope="" itemtype="http://schema.org/ImageObject">
                            <img alt="Check inside shield" src="/assets/uploads/shield-check.svg" class="max-width-70"/>
                        </figure>
                        <h1 class="lato-bold fs-30 padding-top-15">CONGRATULATION!</h1>
                        <div class="fs-20 fs-xs-18 padding-top-10">Your Assurance Contract .pdf file was successfully created! </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="container">
            <div class="row">
                <div class="col-xs-12"><h1 class="lato-bold text-center fs-45 fs-xs-30">Dentacoin Assurance Contract</h1></div>
            </div>
            <div class="row">
                @include('partials.contract-single-page-nav')
            </div>
        </div>
        <div class="container single-contract-tile module pending text-center padding-top-20">
            <div class="row fs-0 flex-xs">
                <div class="col-xs-4 col-md-3 contract-participant text-center inline-block-bottom padding-top-35 padding-bottom-35 white-color-background padding-left-xs-5 padding-right-xs-5 padding-top-xs-15 padding-bottom-xs-15">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="Dentist avatar" src="{{$dentist->avatar_url}}" class="max-width-120"/>
                    </figure>
                    <div class="fs-22 fs-xs-18 calibri-bold padding-top-15 padding-bottom-5">Dr. {{$dentist->name}}</div>
                    <div class="calibri-light">
                        <a href="mailto:{{$dentist->email}}" class="light-gray-color fs-18 fs-xs-16 word-break">{{$dentist->email}}</a>
                    </div>
                </div>
                <div class="col-xs-4 inline-block-bottom blue-green-color-background contract-body" data-time-left-next-transfer="{{strtotime($contract->contract_active_at)}}">
                    <div class="contract-header text-center lato-bold fs-20 white-color padding-top-15 padding-bottom-15 awaiting-payment">@if(isset($mobile) && !$mobile)ACTIVE -@endif AWAITING PAYMENT</div>
                    <div class="wrapper">
                        <div class="lato-bold fs-20 white-color padding-top-25 padding-bottom-15">YOUR FIRST PAYMENT IS DUE IN:</div>
                        <div class="clock"></div>
                        <div class="flip-clock-message"></div>
                    </div>
                </div>
                <div class="col-xs-4 col-md-3 contract-participant text-center inline-block-bottom padding-top-35 padding-bottom-35 white-color-background padding-left-xs-5 padding-right-xs-5 padding-top-xs-15 padding-bottom-xs-15">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="Dentist avatar" src="{{$patient->avatar_url}}" class="max-width-120"/>
                    </figure>
                    <div class="fs-22 fs-xs-18 calibri-bold padding-top-15 padding-bottom-5">{{$patient->name}}</div>
                    <div class="calibri-light fs-18 fs-xs-16 light-gray-color word-break">{{$patient->email}}</div>
                </div>
            </div>
            <div class="row contract-footer">
                <div class="col-xs-12 col-sm-8 col-sm-offset-2 padding-top-30 padding-bottom-40 padding-left-50 padding-right-50 text-center white-color blue-green-color-background fs-20 wrapper padding-top-xs-20 padding-bottom-xs-20 padding-left-xs-15 padding-right-xs-15">
                    @if(isset($mobile) && $mobile)
                        <div class="show-on-xs">
                            <div class="lato-bold fs-20 white-color padding-bottom-15">YOUR FIRST PAYMENT IS DUE IN:</div>
                            <div class="clock"></div>
                            <div class="flip-clock-message"></div>
                        </div>
                    @endif
                    <div class="timer-text">You should charge your wallet with <span class="calibri-bold">{{$contract->monthly_premium}} USD in DCN</span> <span class="calibri-bold">until <span class="converted-date"></span></span>.</div>
                </div>
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
    @include('partials.patient-ready-to-purchase-with-external-api')
@endsection

