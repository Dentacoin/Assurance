@extends('layout')
@section('content')
    @php($dentist = (new \App\Http\Controllers\APIRequestsController())->getUserData(session('logged_user')['id']))
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
    <section class="padding-top-100 single-contract-view-section cancelled" data-created-at="{{strtotime($contract->contract_active_at)}}">
        <div class="container">
            <div class="row">
                <div class="col-xs-12"><h1 class="lato-bold text-center fs-45">Dentacoin Assurance Contract</h1></div>
            </div>
            <div class="row">
                <nav class="col-xs-12 text-center contract-single-page-nav module">
                    <ul itemscope="" itemtype="http://schema.org/SiteNavigationElement">
                        @if(!empty($contract->document_hash))
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
                        @endif
                        <li class="inline-block">
                            <a href="{{route('create-contract')}}?renew-contract={{$contract->slug}}" itemprop="url" class="renew-contract-btn">
                                <span itemprop="name"><i class="fa fa-undo" aria-hidden="true"></i> Renew Contract</span>
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
        </div>
        <div class="container single-contract-tile module pending text-center padding-top-20">
            <div class="row fs-0">
                <div class="col-xs-3 contract-participant text-center inline-block-top padding-top-35 padding-bottom-35 white-color-background">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="Dentist avatar" src="{{$dentist->avatar_url}}" class="max-width-120"/>
                    </figure>
                    <div class="fs-22 calibri-bold padding-top-15 padding-bottom-5">Dr. {{$dentist->name}}</div>
                    <div class="calibri-light fs-18 light-gray-color">{{$dentist->email}}</div>
                </div>
                <div class="col-xs-3 inline-block-top margin-top-40 contract-body text-center" data-time-left-next-transfer="{{strtotime($contract->contract_active_at)}}">
                    <div class="contract-header text-center lato-bold fs-20 white-color padding-top-10 padding-bottom-15 cancelled">CANCELLED</div>
                    <div class="padding-left-15 padding-right-15">
                        <div class="cancelled-color fs-20 calibri-bold padding-top-15">Date Cancelled:</div>
                        <time class="display-block calibri-light fs-20">{{date('d/m/Y', strtotime($contract->cancelled_at))}}</time>
                        <div class="cancelled-color fs-20 calibri-bold padding-top-10">Cancellation Reason:</div>
                        <div class="calibri-light fs-20">Overdue payments by the Patient</div>
                    </div>
                </div>
                <div class="col-xs-3 contract-participant text-center inline-block-top padding-top-35 padding-bottom-35 white-color-background">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="Patient avatar" src="{{$avatar_url}}" class="max-width-120"/>
                    </figure>
                    <div class="fs-22 calibri-bold padding-top-15 padding-bottom-5">{{$patient_name}}</div>
                    <div class="calibri-light fs-18">
                        <a href="mailto:{{$patient_email}}" class="light-gray-color">{{$patient_email}}</a>
                    </div>
                </div>
            </div>
            <div class="row fa-0 padding-top-40 row-with-bottom-squares text-center">
                <div class="col-sm-3 col-xs-12 inline-block padding-top-15 padding-bottom-15 border-right-light-gray">
                    <h3 class="fs-20 calibri-bold">Date Signed: {{var_dump($contract->contract_active_at)}}</h3>
                    <time class="display-block padding-top-10 calibri-light fs-20">{{date('d/m/Y', strtotime($contract->contract_active_at))}}</time>
                </div>
                <div class="col-sm-3 col-xs-12 inline-block padding-top-15 padding-bottom-15">
                    <h3 class="fs-20 calibri-bold">Monthly Premium:</h3>
                    <div class="display-block padding-top-10 calibri-light fs-20">{{$contract->monthly_premium}} USD</div>
                </div>
            </div>
        </div>
    </section>
@endsection