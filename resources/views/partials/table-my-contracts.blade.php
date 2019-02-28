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
        <th class="date">Date Signed/Initiated</th>
        <th class="no-sort">Monthly Premium</th>
        <th>Next Payment/Due date</th>
        <th class="no-sort">Contract Details</th>
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
            <tr @if($contract->status != 'active' && $contract->status != 'pending') data-timestamp-signed="{{strtotime($contract->contract_active_at)}}" @endif>
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
                    <a href="{{$url}}" class="{{$contract->status}} alike-btn">{{$status}}</a>
                </td>
                <td class="avatar-and-name">
                    @if($patient_or_not)
                        @if(!empty($contract->patient_id))
                            @php($patient = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->patient_id))
                            <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                                <img alt="Patient avatar" src="{{$patient->avatar_url}}"/>
                            </figure>
                            <span>{{$patient->name}}</span>
                        @else
                            <span>{{$contract->patient_fname}} {{$contract->patient_lname}}</span>
                        @endif
                    @else
                        @php($dentist = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->dentist_id))
                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                            <img alt="Dentist avatar" src="{{$dentist->avatar_url}}"/>
                        </figure>
                        <span>{{$dentist->name}}</span>
                    @endif
                </td>
                <td>
                    @if(!empty($contract->contract_active_at))
                        <span class="hide-this">{{strtotime($contract->contract_active_at)}}</span>
                        {{date('d/m/Y', strtotime($contract->contract_active_at))}}
                    @else
                        <span class="hide-this">0</span>
                        Not signed yet
                    @endif
                </td>
                <td><span class="hide-this">{{round($contract->monthly_premium)}}</span>{{$contract->monthly_premium}} USD</td>
                <td class="next-payment"><span class="hide-this">0</span>Not signed yet</td>
                <td class="contract-details">
                    @if(!empty($contract->document_hash))
                        <div><a href="javascript:void(0)" class="contract-decrypt" data-hash="{{$contract->document_hash}}" @if(!$patient_or_not) data-type="patient" @else data-type="dentist" @endif>Full Contract (pdf)</a></div>
                        <div><a href="https://ipfs.io/ipfs/{{$contract->document_hash}}" target="_blank">Public Proof</a></div>
                    @else
                        Not signed yet
                    @endif
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