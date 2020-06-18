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
                <a href="javascript:void(0)" itemprop="url" @if((new \App\Http\Controllers\UserController())->checkPatientSession()) data-type="patient" @elseif((new \App\Http\Controllers\UserController())->checkDentistSession()) data-type="dentist" @endif class="cancel-contract-btn" @if($contract->status != 'pending' && $contract->status != 'awaiting-payment' && !empty($contract->patient_address) && !empty($contract->dentist_address) && !empty($dentist_data) && !empty($patient_data)) data-patient="{{$contract->patient_address}}" data-dentist="{{$contract->dentist_address}}" data-recipe-title="Pay Your Network Fee" data-recipe-subtitle="to cancel this contract" @if($patient_session) data-recipe-checkbox-text="By clicking on the button below you confirm you understand that {{$dentist_data->name}} will no longer be obliged to cover any costs included in your contract. If you don't pay the network fee, your smart contract will still be active and {{$dentist_data->name}} will still be able to withdraw the monthly DCN premium." @elseif($dentist_session) data-recipe-checkbox-text="By clicking on the button below you confirm you understand that both you and {{$patient_data->name}} will no longer be obliged to adhere to the conditions in this contract. If you don't pay the network fee, your smart contract will still be active." @endif @endif data-contract="{{$contract->slug}}" @if($contract->is_processing) data-processing-contract="true" @else data-processing-contract="false" @endif>
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
            {{--@if($currentCheckups < $contract->check_ups_per_year * $yearsActionsToBeExecuted)--}}
            @if($currentCheckups < $contract->check_ups_per_year)
                <li class="inline-block check-up-element">
                    <a href="javascript:void(0);" itemprop="url" class="record-check-up">
                        <span itemprop="name"><i class="fa fa-pencil" aria-hidden="true"></i> Record check-up</span>
                    </a>
                </li>
                <li class="inline-block delimeter check-up-element">|</li>
            @endif

            {{--@if($currentTeethCleanings < $contract->teeth_cleaning_per_year * $yearsActionsToBeExecuted)--}}
            @if($currentTeethCleanings < $contract->teeth_cleaning_per_year)
                <li class="inline-block teeth-cleaning-element">
                    <a href="javascript:void(0);" itemprop="url" class="record-teeth-cleaning">
                        <span itemprop="name"><i class="fa fa-pencil" aria-hidden="true"></i> Record teeth cleaning</span>
                    </a>
                </li>
                <li class="inline-block delimeter teeth-cleaning-element">|</li>
            @endif
        @endif
            <li class="inline-block show-on-records-history scroll-init hide width-100 max-width-170">
                <a href="javascript:void(0);" itemprop="url">
                    <svg class="inline-block max-width-20" version="1.1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 95.1 86.2" style="enable-background:new 0 0 95.1 86.2;" xml:space="preserve"><metadata><sfw xmlns="&ns_sfw;"><slices></slices><sliceSourceBounds bottomLeftOrigin="true" height="86.2" width="95.1" x="2.5" y="31.9"></sliceSourceBounds></sfw></metadata><g id="Layer_2"></g><g id="Layer_1"><g><path d="M22.7,36.8c6.9,0,13.8,2.7,19.1,8c5.1,5.1,7.7,11.7,8,18.4h28.4c5.3,0,9.6-4.3,9.6-9.6V21.7H22.7V36.8z M44.4,31.2c0-1.2,0.9-2.1,2.1-2.1H64c1.2,0,2.1,0.9,2.1,2.1v3.9c0,1.2-0.9,2.1-2.1,2.1H46.5c-1.2,0-2.1-0.9-2.1-2.1V31.2z"/><path d="M91.9,0H18.7c-1.7,0-3.1,1.4-3.1,3.1v9.6c0,1.7,1.4,3.1,3.1,3.1H92c1.7,0,3.1-1.4,3.1-3.1V3.1C95,1.4,93.6,0,91.9,0z"/><path d="M7.4,48L5,45.6c-0.4-0.4-1-0.5-1.6-0.4c-0.4,0.1-0.9,0.6-1,1.2L0,57.8c-0.1,0.5,0,1,0.4,1.4s0.9,0.5,1.4,0.4l11.4-2.4c0.6-0.1,1-0.5,1.2-1c0.1-0.6,0-1.2-0.4-1.5l-2.4-2.4c6.3-5.8,16.2-5.6,22.3,0.5c6.3,6.3,6.3,16.5,0,22.8s-16.5,6.3-22.8,0c-2.5-2.4-4-5.5-4.6-8.8c-0.3-1.6-1.8-2.7-3.4-2.4c-1.6,0.3-2.7,1.8-2.4,3.4c0.8,4.5,2.9,8.7,6.2,11.9c4.3,4.3,10,6.5,15.6,6.5c5.7,0,11.3-2.2,15.6-6.5c8.6-8.6,8.6-22.6,0-31.2C29.6,40.1,16.1,39.9,7.4,48z"/><path d="M22.6,51.8c-1.6,0-3,1.3-3,3v9.3c0,1.1,0.6,2.1,1.5,2.6l5.7,3.2c0.5,0.3,0.9,0.4,1.4,0.4c1,0,2.1-0.6,2.6-1.5c0.8-1.4,0.3-3.2-1.2-4l-4.1-2.4v-7.6C25.5,53.2,24.2,51.8,22.6,51.8z"/></g></g></svg> <span itemprop="name" class="inline-block">Records History</span>
                </a>
            </li>
            <li class="inline-block delimeter show-on-records-history hide">|</li>
            <li class="inline-block">
                <a href="https://account.dentacoin.com/assurance?platform=assurance" itemprop="url">
                    <span itemprop="name"><i class="fa fa-bars" aria-hidden="true"></i> View all contracts</span>
                </a>
            </li>
    </ul>
</nav>