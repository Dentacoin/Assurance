@php($patient_session = (new \App\Http\Controllers\UserController())->checkPatientSession())
@php($dentist_session = (new \App\Http\Controllers\UserController())->checkDentistSession())
<nav class="col-xs-12 text-center contract-single-page-nav module">
    @if(isset($mobile) && $mobile)
        <div class="open-mobile-single-page-nav text-center"><a href="javascript:void(0);" class="nav-btn inline-block">···</a></div>
    @endif
    <ul itemscope="" itemtype="http://schema.org/SiteNavigationElement">
        @if(!empty($contract->document_hash))
            <li class="inline-block">
                <a href="javascript:void(0);" class="contract-decrypt" data-hash="{{$contract->document_hash}}" @if($patient_session) data-type="patient" @elseif($dentist_session) data-type="dentist" @endif itemprop="url">
                    @if($contract->status != 'pending')
                        <span itemprop="name">Contract (.pdf)</span>
                    @else
                        <span itemprop="name">Contract sample (.pdf)</span>
                    @endif
                </a>
                <i class="fa fa-info-circle popover-el" data-toggle="popover" data-placement="bottom" data-content="Your contract is stored and encrypted in the <a href='https://ipfs.io/' target='_blank'>IPFS</a>. Reading it might take some time, if it takes too long please try again later."></i>
                <form target="_blank" method="POST" action="{{route('render-pdf')}}" id="render-pdf">
                    <input type="hidden" name="pdf_data"/>
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                </form>
            </li>
            <li class="inline-block delimeter">|</li>
            <li class="inline-block">
                <a href="https://ipfs.io/ipfs/{{$contract->document_hash}}" target="_blank" itemprop="url">
                    <span itemprop="name">Public Proof</span>
                </a>
                <i class="fa fa-info-circle popover-el" data-toggle="popover" data-placement="bottom" data-content="Your contract is stored and encrypted in the <a href='https://ipfs.io/' target='_blank'>IPFS</a>. Reading it might take some time, if it takes too long please try again later."></i>
            </li>
            <li class="inline-block delimeter">|</li>
        @endif
        @if($contract->status != 'cancelled')
            <li class="inline-block">
                <a href="javascript:void(0)" itemprop="url" @if((new \App\Http\Controllers\UserController())->checkPatientSession()) data-type="patient" @elseif((new \App\Http\Controllers\UserController())->checkDentistSession()) data-type="dentist" @endif class="cancel-contract-btn" @if($contract->status != 'pending' && $contract->status != 'awaiting-payment' && !empty($contract->patient_address) && !empty($contract->dentist_address) && !empty($dentist_data) && !empty($patient_data)) data-patient="{{$contract->patient_address}}" data-dentist="{{$contract->dentist_address}}" data-recipe-title="Pay Your Network Fee" data-recipe-subtitle="to cancel this contract" @if($patient_session) data-recipe-checkbox-text="By clicking on the button below you confirm you understand that {{$dentist_data->name}} will no longer be obliged to cover any costs included in your contract. If you don't pay the network fee, your smart contract will still be active and {{$dentist_data->name}} will still be able to withdraw the monthly DCN premium." @elseif($dentist_session) data-recipe-checkbox-text="By clicking on the button below you confirm you understand that both you and {{$patient_data->name}} will no longer be obliged to adhere to the conditions in this contract. If you don't pay the network fee, your smart contract will still be active." @endif @endif data-contract="{{$contract->slug}}">
                    <span itemprop="name"><i class="fa fa-times" aria-hidden="true"></i> Cancel Contract</span>
                </a>
            </li>
            <li class="inline-block delimeter">|</li>
        @elseif($contract->status == 'cancelled' && (new \App\Http\Controllers\UserController())->checkDentistSession())
            <li class="inline-block">
                <a href="{{route('create-contract')}}?renew-contract={{$contract->slug}}" itemprop="url" class="renew-contract-btn">
                    <span itemprop="name"><i class="fa fa-undo" aria-hidden="true"></i> Renew Contract</span>
                </a>
            </li>
            <li class="inline-block delimeter">|</li>
        @endif
        @if($contract->status == 'active' && (new \App\Http\Controllers\UserController())->checkPatientSession() && !empty($contract_active_at))
            @php($timeSinceContractSigning = (new \App\Http\Controllers\Controller())->convertMS(time() - $contract_active_at))
            @php($yearsActionsToBeExecuted = 1)
            @if(array_key_exists('days', $timeSinceContractSigning) && $timeSinceContractSigning['days'] >= 365)
                @php($yearsActionsToBeExecuted += floor($timeSinceContractSigning['days'] / 365))
            @endif

            @php($currentCheckups = (new \App\Http\Controllers\PatientController())->getCheckUpOrTeethCleaning('check-up', $contract->slug))
            @php($currentTeethCleanings = (new \App\Http\Controllers\PatientController())->getCheckUpOrTeethCleaning('teeth-cleaning', $contract->slug))

            {{var_dump($currentCheckups)}}
            {{var_dump($currentTeethCleanings)}}

            @if($currentCheckups < $contract->check_ups_per_year * $yearsActionsToBeExecuted)
                <li class="inline-block">
                    <a href="javascript:void(0);" itemprop="url" class="record-check-up">
                        <span itemprop="name"><i class="fa fa-pencil" aria-hidden="true"></i> Record check-up</span>
                    </a>
                </li>
                <li class="inline-block delimeter">|</li>
            @endif

            @if($currentTeethCleanings < $contract->teeth_cleaning_per_year * $yearsActionsToBeExecuted)
                <li class="inline-block">
                    <a href="javascript:void(0);" itemprop="url" class="record-teeth-cleaning">
                        <span itemprop="name"><i class="fa fa-pencil" aria-hidden="true"></i> Record teeth cleaning</span>
                    </a>
                </li>
                <li class="inline-block delimeter">|</li>
            @endif
        @endif
            <li class="inline-block">
                <a href="https://account.dentacoin.com/assurance?platform=assurance" itemprop="url">
                    <span itemprop="name"><i class="fa fa-bars" aria-hidden="true"></i> View all contracts</span>
                </a>
            </li>
    </ul>
</nav>