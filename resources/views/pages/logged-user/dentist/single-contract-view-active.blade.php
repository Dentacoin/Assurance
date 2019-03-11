@extends('layout')
@section('content')
    @php($dentist = (new \App\Http\Controllers\APIRequestsController())->getUserData(session('logged_user')['id']))
    @php($patient = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->patient_id))
    <section class="padding-top-100 single-contract-view-section active" data-created-at="{{strtotime($contract->contract_active_at)}}" data-timestamp-signed="{{strtotime($contract->contract_active_at)}}">
        <section class="container">
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
                            <form target="_blank" method="POST" action="{{route('render-pdf')}}" id="render-pdf">
                                <input type="hidden" name="pdf_data"/>
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                            </form>
                        </li>
                        <li class="inline-block">|</li>
                        <li class="inline-block">
                            <a href="https://ipfs.io/ipfs/{{$contract->document_hash}}" target="_blank" itemprop="url">
                                <span itemprop="name">Public Proof</span>
                            </a>
                        </li>
                        <li class="inline-block">|</li>
                        <li class="inline-block">
                            <a href="javascript:void(0)" onclick="return confirm('Are you sure you want to cancel this contract?')"  itemprop="url" class="cancel-contract-btn" data-contract="{{$contract->slug}}">
                                <span itemprop="name"><i class="fa fa-times" aria-hidden="true"></i> Cancel Contract</span>
                            </a>
                        </li>
                        <li class="inline-block">|</li>
                        <li class="inline-block">
                            <a href="{{route('my-contracts')}}" itemprop="url">
                                <span itemprop="name"><i class="fa fa-bars" aria-hidden="true"></i> List view all contracts</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </section>
        <section class="container single-contract-tile module pending text-center padding-top-20">
            <div class="row fs-0">
                <div class="col-xs-3 contract-participant text-center inline-block-top padding-top-35 padding-bottom-35 white-color-background">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="Dentist avatar" src="{{$dentist->avatar_url}}" class="max-width-120"/>
                    </figure>
                    <div class="fs-22 calibri-bold padding-top-15 padding-bottom-5">Dr. {{$dentist->name}}</div>
                    <div class="calibri-light fs-18 light-gray-color">{{$dentist->email}}</div>
                </div>
                <div class="col-xs-3 inline-block-top margin-top-40 contract-body" data-time-left-next-transfer="{{strtotime($contract->contract_active_at)}}">
                    <div class="contract-header text-center lato-bold fs-20 white-color padding-top-15 padding-bottom-15 active">ACTIVE</div>
                    <figure itemscope="" itemtype="http://schema.org/ImageObject" class="absolute-hands">
                        <img alt="Dentist avatar" src="/assets/uploads/active-hands.svg"/>
                    </figure>
                </div>
                <div class="col-xs-3 contract-participant text-center inline-block-top padding-top-35 padding-bottom-35 white-color-background">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="Patient avatar" src="{{$patient->avatar_url}}" class="max-width-120"/>
                    </figure>
                    <div class="fs-22 calibri-bold padding-top-15 padding-bottom-5">{{$patient->name}}</div>
                    <div class="calibri-light fs-18">
                        <a href="mailto:{{$patient->email}}" class="light-gray-color">{{$patient->email}}</a>
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
        <section class="container contract-details">
            <div class="row text-center">
                <div class="col-xs-12 col-lg-10 col-lg-offset-1">
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