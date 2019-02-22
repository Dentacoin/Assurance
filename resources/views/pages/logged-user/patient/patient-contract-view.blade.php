@extends("layout")
@section("content")
    @php($dentist = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->dentist_id))
    @php($patient = (new \App\Http\Controllers\APIRequestsController())->getUserData(session('logged_user')['id']))
    <section class="padding-top-100 patient-contract-single-page-section">
        <div class="container">
            <div class="row">
                <div class="col-xs-12"><h1 class="lato-bold text-center fs-45">Dentacoin Assurance Contract</h1></div>
            </div>
            <div class="row">
                <nav class="col-xs-12 text-center contract-single-page-nav module">
                    <ul itemscope="" itemtype="http://schema.org/SiteNavigationElement">
                        <li class="inline-block">
                            <a href="javascript:void(0);" class="contract-decrypt" data-hash="{{$contract->document_hash}}" data-type="patient" itemprop="url" target="_blank">
                                <span itemprop="name">Contract sample (pdf)</span>
                            </a>
                        </li>
                        @if($contract->status != 'cancelled')
                            <li class="inline-block">|</li>
                            <li class="inline-block">
                                <a href="javascript:void(0)" onclick="return confirm('Are you sure you want to cancel this contract?')"  itemprop="url" class="cancel-contract-btn" data-contract="{{$contract->slug}}">
                                    <span itemprop="name"><i class="fa fa-times" aria-hidden="true"></i> Cancel Contract</span>
                                </a>
                            </li>
                        @endif
                        <li class="inline-block">|</li>
                        <li class="inline-block">
                            <a href="javascript:void(0)" itemprop="url">
                                <span itemprop="name"><i class="fa fa-bars" aria-hidden="true"></i> List view all contracts</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="container single-contract-tile module pending text-center padding-top-20">
            <div class="row fs-0">
                <div class="col-xs-3 contract-participant text-center inline-block-bottom padding-top-35 padding-bottom-35 white-color-background">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="Dentist avatar" src="{{$dentist->avatar_url}}" class="max-width-120"/>
                    </figure>
                    <div class="fs-22 calibri-bold padding-top-15 padding-bottom-5">Dr. {{$dentist->name}}</div>
                    <div class="calibri-light fs-18">
                        <a href="mailto:{{$dentist->email}}" class="light-gray-color">{{$dentist->email}}</a>
                    </div>
                </div>
                <div class="col-xs-4 inline-block-bottom blue-green-color-background contract-body" data-time-left-next-transfer="{{strtotime($contract->contract_active_at)}}">
                    <div class="contract-header text-center lato-bold fs-20 white-color padding-top-15 padding-bottom-15 {{$contract->status}}">
                        @switch($contract->status)
                            @case('active')
                                ACTIVE
                                @break
                            @case('pending')
                                PENDING
                                @break
                            @case('awaiting-payment')
                                ACTIVE - AWAITING PAYMENT
                                @break
                            @case('awaiting-approval')
                                ACTIVE - AWAITING APPROVAL
                                @break
                            @case('cancelled')
                                CANCELLED
                                @break
                        @endswitch
                    </div>
                    <div class="lato-bold fs-20 white-color padding-top-25 padding-bottom-15">YOUR FIRST PAYMENT IS DUE IN:</div>
                    <div class="clock"></div>
                    <div class="flip-clock-message"></div>
                </div>
                <div class="col-xs-3 contract-participant text-center inline-block-bottom padding-top-35 padding-bottom-35 white-color-background">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="Dentist avatar" src="{{$patient->avatar_url}}" class="max-width-120"/>
                    </figure>
                    <div class="fs-22 calibri-bold padding-top-15 padding-bottom-5">{{$patient->name}}</div>
                    <div class="calibri-light fs-18 light-gray-color">{{$patient->email}}</div>
                </div>
            </div>
            <div class="row contract-footer">
                <div class="col-xs-12 col-sm-8 col-sm-offset-2 padding-top-30 padding-bottom-40 padding-left-50 padding-right-50 text-center white-color blue-green-color-background fs-20 wrapper">You should charge your wallet with <span class="calibri-bold">{{$contract->monthly_premium}} USD in DCN</span> (the monthly premium amount) <span class="calibri-bold">until <span class="converted-date"></span></span>. (one day before the due date)</div>
            </div>
        </div>
    </section>
    @include('partials.patient-ready-to-purchase-with-external-api')
@endsection

