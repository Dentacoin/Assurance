@extends("layout")
@section("content")
    <section class="dentist-contracts-section padding-top-200 padding-top-xs-50 padding-top-sm-50">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-lg-6 col-lg-offset-3 text-center padding-bottom-120 padding-bottom-xs-50">
                    <h1 class="lato-bold fs-45 fs-xs-32 padding-bottom-50">Deliver Better Care & Get Paid for Prevention</h1>
                    <a href="/dentist/create-contract" class="white-blue-green-btn min-width-300 min-width-xs-250">CREATE A CONTRACT</a>
                </div>
            </div>
            @if(sizeof($active_contracts) > 0)
                <div class="row">
                    <div class="col-xs-12 padding-bottom-50">
                        <div class="contracts-list active-contracts slider" data-slides-number="4">
                            @php($counter = 0)
                            @foreach($active_contracts as $contract)
                                @php($counter+=1)
                                @if ($counter == 6)
                                    @break
                                @endif
                                <div class="module contract-tile padding-bottom-10 {{$contract->status}}">
                                    @php($patient = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->patient_id))
                                    @php($url = route('dentist-contract-view', ['slug' => $contract->slug]))
                                    <a href="{{$url}}" class="tile-wrapper fs-0">
                                        <div class="inline-block-top figure-container">
                                            <figure itemscope="" itemtype="http://schema.org/ImageObject">
                                                <img alt="Patient avatar" src="{{$patient->avatar_url}}"/>
                                                <figcaption class="fs-14 fs-xs-12 calibri-light text-center padding-left-5 padding-right-5">
                                                    @php($btn_label = '')
                                                    @switch($contract->status)
                                                        @case('active')
                                                        Active
                                                        @php($btn_label = 'Details')
                                                        @break
                                                        @case('awaiting-payment')
                                                        Awaiting Payment
                                                        @php($btn_label = 'Details')
                                                        @break
                                                        @case('awaiting-approval')
                                                        Awaiting Approval
                                                        @php($btn_label = 'Details and approve')
                                                        @break
                                                    @endswitch
                                                </figcaption>
                                            </figure>
                                        </div>
                                        <div class="contract-info inline-block-top">
                                            <div class="calibri-bold fs-18 title">{{$patient->name}}</div>
                                            <time class="display-block fs-14 calibri-light">Signed on: {{date('d/m/Y', strtotime($contract->contract_active_at))}}</time>
                                            <div class="lato-semibold fs-24 line-height-24">{{$contract->monthly_premium}}$</div>
                                            <div class="btn-container">
                                                <a href="javascript:void(0)" class="white-blue-green-btn">{{$btn_label}}</a>
                                            </div>
                                        </div>
                                    </a>
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
                                        @php($url = route('dentist-contract-view', ['slug' => $contract->slug]))
                                        <a href="{{$url}}" class="tile-wrapper fs-0">
                                            <div class="inline-block-top figure-container">
                                                <figure itemscope="" itemtype="http://schema.org/ImageObject">
                                                    <img alt="Patient avatar" src="/assets/images/no-avatar.png"/>
                                                    <figcaption class="fs-14 fs-xs-12 calibri-light text-center padding-left-5 padding-right-5">
                                                        @switch($contract->status)
                                                            @case('active')
                                                            Active
                                                            @break
                                                            @case('pending')
                                                            Pending
                                                            @break
                                                            @case('awaiting-payment')
                                                            Awaiting Payment
                                                            @break
                                                            @case('awaiting-approval')
                                                            Awaiting Approval
                                                            @break
                                                            @case('cancelled')
                                                            Cancelled
                                                            @break
                                                        @endswitch
                                                    </figcaption>
                                                </figure>
                                            </div>
                                            <div class="contract-info inline-block-top">
                                                <div class="calibri-bold fs-18 title">{{$contract->patient_fname}} {{$contract->patient_lname}}</div>
                                                <time class="display-block fs-14 calibri-light">Received on: {{$contract->created_at->format('d/m/Y')}}</time>
                                                <div class="lato-semibold fs-24 line-height-24">{{$contract->monthly_premium}}$</div>
                                                <div class="btn-container">
                                                    <a href="javascript:void(0)" class="white-blue-green-btn">Details</a>
                                                </div>
                                            </div>
                                        </a>
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
                                        @php($url = route('dentist-contract-view', ['slug' => $contract->slug]))
                                        <a href="{{$url}}" class="tile-wrapper fs-0">
                                            <div class="inline-block-top figure-container">
                                                <figure itemscope="" itemtype="http://schema.org/ImageObject">
                                                    <img alt="Patient avatar" src="{{$avatar_url}}"/>
                                                    <figcaption class="fs-14 fs-xs-12 calibri-light text-center padding-left-5 padding-right-5">
                                                        @switch($contract->status)
                                                            @case('active')
                                                            Active
                                                            @break
                                                            @case('pending')
                                                            Pending
                                                            @break
                                                            @case('awaiting-payment')
                                                            Awaiting Payment
                                                            @break
                                                            @case('awaiting-approval')
                                                            Awaiting Approval
                                                            @break
                                                            @case('cancelled')
                                                            Cancelled
                                                            @break
                                                        @endswitch
                                                    </figcaption>
                                                </figure>
                                            </div>
                                            <div class="contract-info inline-block-top">
                                                <div class="calibri-bold fs-18 title">{{$patient_name}}</div>
                                                <time class="display-block fs-14 calibri-light">Signed on: {{date('d/m/Y', strtotime($contract->contract_active_at))}}</time>
                                                <time class="display-block fs-14 calibri-light">Cancelled on: {{date('d/m/Y', strtotime($contract->cancelled_at))}}</time>
                                                <div class="lato-semibold fs-24 line-height-24">{{$contract->monthly_premium}}$</div>
                                                <div class="btn-container">
                                                    <a href="javascript:void(0)" class="white-blue-green-btn">Details</a>
                                                </div>
                                            </div>
                                        </a>
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
                        @if((isset($mobile) && !$mobile))
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
                        @endif
                        <div class="col-xs-12 col-sm-6 padding-bottom-xs-20">
                            @if((isset($mobile) && $mobile))
                                <h2 class="text-center lato-bold fs-20 padding-bottom-20">PENDING CONTRACTS</h2>
                            @endif
                            <div class="contracts-list pendings slider" data-slides-number="2">
                                @php($counter = 0)
                                @foreach($pending_contracts as $contract)
                                    @php($counter+=1)
                                    @if ($counter == 3)
                                        @break
                                    @endif
                                    <div class="module contract-tile padding-bottom-10 {{$contract->status}}">
                                        @php($patient = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->patient_id))
                                        @php($url = route('dentist-contract-view', ['slug' => $contract->slug]))
                                        <a href="{{$url}}" class="tile-wrapper fs-0">
                                            <div class="inline-block-top figure-container">
                                                <figure itemscope="" itemtype="http://schema.org/ImageObject">
                                                    <img alt="Patient avatar" src="/assets/images/no-avatar.png"/>
                                                    <figcaption class="fs-14 fs-xs-12 calibri-light text-center padding-left-5 padding-right-5">
                                                        @switch($contract->status)
                                                            @case('active')
                                                            Active
                                                            @break
                                                            @case('pending')
                                                            Pending
                                                            @break
                                                            @case('awaiting-payment')
                                                            Awaiting Payment
                                                            @break
                                                            @case('awaiting-approval')
                                                            Awaiting Approval
                                                            @break
                                                            @case('cancelled')
                                                            Cancelled
                                                            @break
                                                        @endswitch
                                                    </figcaption>
                                                </figure>
                                            </div>
                                            <div class="contract-info inline-block-top">
                                                <div class="calibri-bold fs-18 title">{{$contract->patient_fname}} {{$contract->patient_lname}}</div>
                                                <time class="display-block fs-14 calibri-light">Received on: {{$contract->created_at->format('d/m/Y')}}</time>
                                                <div class="display-block fs-14 calibri-light hide-this">&nbsp;</div>
                                                <div class="lato-semibold fs-24 line-height-24">{{$contract->monthly_premium}}$</div>
                                                <div class="btn-container">
                                                    <a href="javascript:void(0)" class="white-blue-green-btn">Details</a>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            @if(sizeof($pending_contracts) > 2)
                                <div class="padding-top-10">
                                    <a href="/my-contracts?status=pending" class="black-light-gray-btn margin-left-10">See all</a>
                                </div>
                            @endif
                        </div>
                        <div class="col-xs-12 col-sm-6 border-delimeter">
                            @if((isset($mobile) && $mobile))
                                <h2 class="text-center lato-bold fs-20 padding-bottom-20">CANCELLED CONTRACTS</h2>
                            @endif
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
                                        @php($url = route('dentist-contract-view', ['slug' => $contract->slug]))
                                        <a href="{{$url}}" class="tile-wrapper fs-0">
                                            <div class="inline-block-top figure-container">
                                                <figure itemscope="" itemtype="http://schema.org/ImageObject">
                                                    <img alt="Patient avatar" src="{{$avatar_url}}"/>
                                                    <figcaption class="fs-14 fs-xs-12 calibri-light text-center padding-left-5 padding-right-5">
                                                        @switch($contract->status)
                                                            @case('active')
                                                            Active
                                                            @break
                                                            @case('pending')
                                                            Pending
                                                            @break
                                                            @case('awaiting-payment')
                                                            Awaiting Payment
                                                            @break
                                                            @case('awaiting-approval')
                                                            Awaiting Approval
                                                            @break
                                                            @case('cancelled')
                                                            Cancelled
                                                            @break
                                                        @endswitch
                                                    </figcaption>
                                                </figure>
                                            </div>
                                            <div class="contract-info inline-block-top">
                                                <div class="calibri-bold fs-18 title">{{$patient_name}}</div>
                                                <time class="display-block fs-14 calibri-light">Signed on: {{date('d/m/Y', strtotime($contract->contract_active_at))}}</time>
                                                <time class="display-block fs-14 calibri-light">Cancelled on: {{date('d/m/Y', strtotime($contract->cancelled_at))}}</time>
                                                <div class="lato-semibold fs-24 line-height-24">{{$contract->monthly_premium}}$</div>
                                                <div class="btn-container">
                                                    <a href="javascript:void(0)" class="white-blue-green-btn">Details</a>
                                                </div>
                                            </div>
                                        </a>
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