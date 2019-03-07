@extends("layout")
@section("content")
    <section class="dentist-contracts-section padding-top-200">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-offset-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center padding-bottom-120">
                    <h1 class="lato-bold fs-45 padding-bottom-50">Deliver Better Care & Get Paid for Prevention</h1>
                    <a href="/dentist/create-contract" class="white-blue-green-btn min-width-300">CREATE A CONTRACT</a>
                </div>
            </div>
            @if(sizeof($active_contracts) > 0)
                <div class="row">
                    <div class="col-xs-12 padding-bottom-50">
                        <div class="contracts-list slider">
                            @php($counter = 0)
                            @foreach($active_contracts as $contract)
                                @php($counter+=1)
                                @if ($counter == 6)
                                    @break
                                @endif
                                <div class="module contract-tile padding-bottom-10 {{$contract->status}}">
                                    @php($patient = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->patient_id))
                                    <div class="tile-wrapper fs-0">
                                        <div class="inline-block-top figure-container">
                                            <figure itemscope="" itemtype="http://schema.org/ImageObject">
                                                <img alt="Patient avatar" src="{{$patient->avatar_url}}"/>
                                            </figure>{{--
                                            <div class="status fs-14 blue-green-color calibri-light text-center padding-left-5 padding-right-5">
                                                @switch($contract->status)
                                                    @case('active')
                                                    Active
                                                    @break
                                                    @case('awaiting-payment')
                                                    Active - Awaiting Payment
                                                    @break
                                                    @case('awaiting-approval')
                                                    Active - Awaiting Approval
                                                    @break
                                                @endswitch
                                            </div>--}}
                                        </div>
                                        <div class="contract-info inline-block-top">
                                            <div class="calibri-bold fs-18">{{$patient->name}}</div>
                                            <time class="display-block fs-14 calibri-light">Signed on: {{date('d/m/Y', strtotime($contract->contract_active_at))}}</time>
                                            <div class="lato-semibold fs-24 padding-top-5 padding-bottom-5">{{$contract->monthly_premium}}$</div>
                                            <div class="btn-container">
                                                <a href="{{route('dentist-contract-view', ['slug' => $contract->slug])}}" class="white-blue-green-btn">Details</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if(sizeof($active_contracts) > 6)
                            <div class="padding-top-10 text-right">
                                <a href="/my-contracts" class="black-light-gray-btn margin-right-25">See all</a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </section>
    @if(sizeof($pending_contracts) != 0 || sizeof($cancelled_contracts) != 0)
        <section class="pending-and-cancelled-contacts-section padding-top-20">
            <div class="container">
                <div class="row">
                    @if(sizeof($pending_contracts) != 0 && sizeof($cancelled_contracts) == 0)
                        <h2 class="text-center fs-20 padding-top-10 padding-bottom-30 lato-bold">PENDING CONTRACTS</h2>
                        <div class="col-xs-12">
                            <div class="contracts-list pendings slider">
                                @php($counter = 0)
                                @foreach($pending_contracts as $contract)
                                    @php($counter+=1)
                                    @if ($counter == 4)
                                        @break
                                    @endif
                                    <div class="module contract-tile padding-bottom-10 {{$contract->status}}">
                                        <div class="tile-wrapper fs-0">
                                            <div class="inline-block-top figure-container">
                                                <figure itemscope="" itemtype="http://schema.org/ImageObject">
                                                    <img alt="Patient avatar" src="/assets/images/no-avatar.png"/>
                                                </figure>
                                                {{--<div class="status fs-14 blue-green-color calibri-light text-center padding-left-5 padding-right-5">Pending</div>--}}
                                            </div>
                                            <div class="contract-info inline-block-top">
                                                <div class="calibri-bold fs-18">{{$contract->patient_fname}} {{$contract->patient_lname}}</div>
                                                <time class="display-block fs-14 calibri-light">Received on: {{$contract->created_at->format('d/m/Y')}}</time>
                                                <div class="lato-semibold fs-24 padding-top-5 padding-bottom-5">{{$contract->monthly_premium}}$</div>
                                                <div class="btn-container">
                                                    <a href="{{route('dentist-contract-view', ['slug' => $contract->slug])}}" class="white-blue-green-btn">Details and Sign</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if(sizeof($pending_contracts) > 3)
                                <div class="padding-top-10">
                                    <a href="/my-contracts?status=pending" class="black-light-gray-btn margin-left-10">See all</a>
                                </div>
                            @endif
                        </div>
                    @elseif(sizeof($pending_contracts) == 0 && sizeof($cancelled_contracts) != 0)
                        <h2 class="text-center fs-20 padding-top-10 padding-bottom-30 lato-bold">CANCELLED CONTRACTS</h2>
                        <div class="col-xs-12">
                            <div class="contracts-list cancelleds slider">
                                @php($counter = 0)
                                @foreach($cancelled_contracts as $contract)
                                    @if(!empty($contract->patient_id))
                                        @php($patient = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->patient_id))
                                        @php($patient_name = $patient->name)
                                        @php($avatar_url = $patient->avatar_url)
                                    @else
                                        @php($patient_name = $contract->patient_fname . ' ' . $contract->patient_lname)
                                        @php($avatar_url = '/assets/images/no-avatar.png')
                                    @endif

                                    @php($counter+=1)
                                    @if ($counter == 4)
                                        @break
                                    @endif
                                    <div class="module contract-tile padding-bottom-10 {{$contract->status}}">
                                        <div class="tile-wrapper fs-0">
                                            <div class="inline-block-top figure-container">
                                                <figure itemscope="" itemtype="http://schema.org/ImageObject">
                                                    <img alt="Patient avatar" src="{{$avatar_url}}"/>
                                                </figure>
                                                {{--<div class="status fs-14 blue-green-color calibri-light text-center padding-left-5 padding-right-5">Cancelled</div>--}}
                                            </div>
                                            <div class="contract-info inline-block-top">
                                                <div class="calibri-bold fs-18">{{$patient_name}}</div>
                                                <time class="display-block fs-14 calibri-light">Signed on: {{date('d/m/Y', strtotime($contract->contract_active_at))}}</time>
                                                <time class="display-block fs-14 calibri-light">Cancelled on: {{date('d/m/Y', strtotime($contract->cancelled_at))}}</time>
                                                <div class="lato-semibold fs-24 padding-top-5 padding-bottom-5">{{$contract->monthly_premium}}$</div>
                                                <div class="btn-container">
                                                    <a href="{{route('dentist-contract-view', ['slug' => $contract->slug])}}" class="white-blue-green-btn">Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if(sizeof($cancelled_contracts) > 3)
                                <div class="padding-top-10 text-right">
                                    <a href="/my-contracts?status=cancelled" class="black-light-gray-btn margin-right-10">See all</a>
                                </div>
                            @endif
                        </div>
                    @elseif(sizeof($pending_contracts) != 0 && sizeof($cancelled_contracts) != 0)
                        <div class="padding-bottom-30 fs-0">
                            <div class="col-xs-6 title inline-block">
                                <div class="inner-wrapper">
                                    <h2 class="text">PENDING CONTRACTS</h2><span class="border first"></span>
                                </div>
                            </div>
                            <div class="col-xs-6 title inline-block text-right">
                                <div class="inner-wrapper">
                                    <h2 class="text">CANCELLED CONTRACTS</h2><span class="border second"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <div class="contracts-list pendings slider" data-slides-number="2">
                                @php($counter = 0)
                                @foreach($pending_contracts as $contract)
                                    @php($counter+=1)
                                    @if ($counter == 3)
                                        @break
                                    @endif
                                    <div class="module contract-tile padding-bottom-10 {{$contract->status}}">
                                        @php($patient = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->patient_id))
                                        <div class="tile-wrapper fs-0">
                                            <div class="inline-block-top figure-container">
                                                <figure itemscope="" itemtype="http://schema.org/ImageObject">
                                                    <img alt="Patient avatar" src="/assets/images/no-avatar.png"/>
                                                </figure>
                                                {{--<div class="status fs-14 blue-green-color calibri-light text-center padding-left-5 padding-right-5">Pending</div>--}}
                                            </div>
                                            <div class="contract-info inline-block-top">
                                                <div class="calibri-bold fs-18">{{$contract->patient_fname}} {{$contract->patient_lname}}</div>
                                                <time class="display-block fs-14 calibri-light">Received on: {{$contract->created_at->format('d/m/Y')}}</time>
                                                <div class="display-block fs-14 calibri-light hide-this">&nbsp;</div>
                                                <div class="lato-semibold fs-24 padding-top-5 padding-bottom-5">{{$contract->monthly_premium}}$</div>
                                                <div class="btn-container">
                                                    <a href="{{route('dentist-contract-view', ['slug' => $contract->slug])}}" class="white-blue-green-btn">Details and Sign</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if(sizeof($pending_contracts) > 2)
                                <div class="padding-top-10">
                                    <a href="/my-contracts?status=pending" class="black-light-gray-btn margin-left-10">See all</a>
                                </div>
                            @endif
                        </div>
                        <div class="col-xs-12 col-xs-6 border-delimeter">
                            <div class="contracts-list cancelleds slider" data-slides-number="2">
                                @php($counter = 0)
                                @foreach($cancelled_contracts as $contract)
                                    @if(!empty($contract->patient_id))
                                        @php($patient = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->patient_id))
                                        @php($patient_name = $patient->name)
                                        @php($avatar_url = $patient->avatar_url)
                                    @else
                                        @php($patient_name = $contract->patient_fname . ' ' . $contract->patient_lname)
                                        @php($avatar_url = '/assets/images/no-avatar.png')
                                    @endif

                                    @php($counter+=1)
                                    @if ($counter == 3)
                                        @break
                                    @endif
                                    <div class="module contract-tile padding-bottom-10 {{$contract->status}}">
                                        <div class="tile-wrapper fs-0">
                                            <div class="inline-block-top figure-container">
                                                <figure itemscope="" itemtype="http://schema.org/ImageObject">
                                                    <img alt="Patient avatar" src="{{$avatar_url}}"/>
                                                </figure>
                                                {{--<div class="status fs-14 blue-green-color calibri-light text-center padding-left-5 padding-right-5">Cancelled</div>--}}
                                            </div>
                                            <div class="contract-info inline-block-top">
                                                <div class="calibri-bold fs-18">{{$patient_name}}</div>
                                                <time class="display-block fs-14 calibri-light">Signed on: {{date('d/m/Y', strtotime($contract->contract_active_at))}}</time>
                                                <time class="display-block fs-14 calibri-light">Cancelled on: {{date('d/m/Y', strtotime($contract->cancelled_at))}}</time>
                                                <div class="lato-semibold fs-24 padding-top-5 padding-bottom-5">{{$contract->monthly_premium}}$</div>
                                                <div class="btn-container">
                                                    <a href="{{route('dentist-contract-view', ['slug' => $contract->slug])}}" class="white-blue-green-btn">Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if(sizeof($cancelled_contracts) > 2)
                                <div class="padding-top-10 text-right">
                                    <a href="/my-contracts?status=cancelled" class="black-light-gray-btn margin-right-10">See all</a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif
@endsection