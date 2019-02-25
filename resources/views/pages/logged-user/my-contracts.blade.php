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
                            <div class="table-container fs-16">
                                <table class="table table-without-reorder table-striped text-left my-contracts">
                                    <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>
                                            @if($patient_or_not)
                                                Patient
                                            @else
                                                Dentist
                                            @endif
                                        </th>
                                        <th>Date Signed/Initiated</th>
                                        <th>Monthly Premium</th>
                                        <th>Next Payment/Due date</th>
                                        <th>Contract Details</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($contracts) > 0)
                                        @foreach($contracts as $contract)
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
                                                    @if($patient_or_not)
                                                        @php($patient = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->patient_id))
                                                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                                                            <img alt="Patient avatar" src="{{$patient->avatar_url}}"/>
                                                        </figure>
                                                        <span>{{$patient->name}}</span>
                                                    @else
                                                        @php($dentist = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->dentist_id))
                                                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                                                            <img alt="Dentist avatar" src="{{$dentist->avatar_url}}"/>
                                                        </figure>
                                                        <span>{{$dentist->name}}</span>
                                                    @endif
                                                </td>
                                                <td>{{date('d/m/Y', strtotime($contract->contract_active_at))}}</td>
                                                <td>{{$contract->monthly_premium}} USD</td>
                                                <td class="next-payment"></td>
                                                <td class="contract-details">
                                                    <div><a href="">Fill Contract (pdf)</a></div>
                                                    <div><a href="">Public Proof</a></div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center padding-top-20 padding-bottom-20">You don't have any contracts at the moment.</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection