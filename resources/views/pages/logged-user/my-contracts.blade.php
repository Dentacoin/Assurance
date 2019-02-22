@extends("layout")
@section("content")
    <section class="my-contracts padding-top-100">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 fs-0">
                    @include('partials.my-profile-menu')
                    <div class="my-profile-page-content inline-block-top">
                        <div class="profile-page-title">
                            <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                                <img alt="Contracts list" src="/assets/uploads/contracts-list.svg"/>
                            </figure>
                            <h2 class="fs-24 lato-bold inline-block">My contracts</h2>
                            @if(count($contracts) > 0)
                                <div class="table-container fs-16">
                                    <table class="table table-without-reorder table-striped text-left my-contracts">
                                        <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>{{$other_side_label}}</th>
                                            <th>Date Signed/Initiated</th>
                                            <th>Monthly Premium</th>
                                            <th>Next Payment/Due date</th>
                                            <th>Contract Details</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($contracts as $contract)
                                            @php($dentist = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->dentist_id))
                                            @if($contract->status == 'pending')
                                                @php($url = route('contract-proposal', ['slug' => $contract->slug]))
                                            @else
                                                @php($url = route('patient-contract-view', ['slug' => $contract->slug]))
                                            @endif
                                            <tr @if($contract->status != 'active' && $contract->status != 'pending') data-timestamp-signed="{{strtotime($contract->contract_active_at)}}" @endif onclick="window.location='{{$url}}'">
                                                <td class="status">
                                                    @switch($contract->status)
                                                        @case('active')
                                                            @php($status = 'Active')
                                                        @break
                                                        @case('pending')
                                                            @php($status = 'Pending')
                                                        @break
                                                        @case('awaiting-payment')
                                                            @php($status = 'Awaiting Payment')
                                                        @break
                                                        @case('awaiting-approval')
                                                            @php($status = 'Awaiting Approval')
                                                        @break
                                                        @case('cancelled')
                                                            @php($status = 'Cancelled')
                                                        @break
                                                    @endswitch
                                                    <div class="{{$contract->status}} alike-btn">{{$status}}</div>
                                                </td>
                                                <td class="avatar-and-name">
                                                    <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                                                        <img alt="{{$other_side_label}} avatar" src="{{$dentist->avatar_url}}"/>
                                                    </figure>
                                                    <span>{{$dentist->name}}</span>
                                                </td>
                                                <td>{{date('d/m/Y', strtotime($contract->contract_active_at))}}</td>
                                                <td>{{$contract->monthly_premium}} USD</td>
                                                <td class="next-payment"></td>
                                                <td>FIX THIS</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection