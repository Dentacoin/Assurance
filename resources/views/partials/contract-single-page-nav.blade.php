<nav class="col-xs-12 text-center contract-single-page-nav module">
    @if(isset($mobile) && $mobile)
        <div class="open-mobile-single-page-nav text-center"><a href="javascript:void(0);" class="nav-btn inline-block">···</a></div>
    @endif
    <ul itemscope="" itemtype="http://schema.org/SiteNavigationElement">
        @if(!empty($contract->document_hash))
            <li class="inline-block">
                <a href="javascript:void(0);" class="contract-decrypt" data-hash="{{$contract->document_hash}}" data-type="patient" itemprop="url" target="_blank">
                    <span itemprop="name">Contract sample (pdf)</span>
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
                <a href="javascript:void(0)" onclick="return confirm('Are you sure you want to cancel this contract?')"  itemprop="url" class="cancel-contract-btn" data-contract="{{$contract->slug}}">
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
        <li class="inline-block">
            <a href="{{route('my-contracts')}}" itemprop="url">
                <span itemprop="name"><i class="fa fa-bars" aria-hidden="true"></i> List view all contracts</span>
            </a>
        </li>
    </ul>
</nav>