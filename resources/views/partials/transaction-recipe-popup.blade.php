<div class="lato-regular fs-40 fs-xs-30 text-center padding-bottom-10 popup-title"><svg version="1.1" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 63.2 59.1" style="enable-background:new 0 0 63.2 59.1;" xml:space="preserve"><style type="text/css"> .st0{fill-rule:evenodd;clip-rule:evenodd;fill:#126585;stroke:#126585;stroke-width:3;stroke-miterlimit:10;}.st1{fill-rule:evenodd;clip-rule:evenodd;fill:#126585;}</style> <metadata> <sfw xmlns="&ns_sfw;"> <slices></slices> <sliceSourceBounds bottomLeftOrigin="true" height="59" width="63" x="-1.5" y="12.5"></sliceSourceBounds> </sfw> </metadata> <title>65 -Send Money- (Outline)</title> <desc>Created with Sketch.</desc> <g> <g transform="translate(-599.000000, -1067.000000)"> <g transform="translate(599.000000, 1067.000000)"> <path class="st0" d="M3.6,55.6h56v-29L32.2,47.4c-0.4,0.3-0.9,0.3-1.2,0L3.6,26.6V55.6z M60.6,57.6h-58c-0.6,0-1-0.4-1-1v-32c0-0.4,0.2-0.7,0.6-0.9s0.7-0.1,1,0.1l28.4,21.5L60,23.8c0.3-0.2,0.7-0.3,1-0.1c0.3,0.2,0.6,0.5,0.6,0.9v32C61.6,57.2,61.2,57.6,60.6,57.6L60.6,57.6z"/> <path class="st1" d="M31.6,37.6c-2.8,0-5-2.2-5-5c0-0.6,0.4-1,1-1s1,0.4,1,1c0,1.7,1.3,3,3,3c1.7,0,3-1.3,3-3s-1.3-3-3-3c-2.8,0-5-2.2-5-5s2.2-5,5-5c2.8,0,5,2.2,5,5c0,0.6-0.4,1-1,1c-0.6,0-1-0.4-1-1c0-1.7-1.3-3-3-3c-1.7,0-3,1.3-3,3s1.3,3,3,3c2.8,0,5,2.2,5,5S34.4,37.6,31.6,37.6"/> <path class="st1" d="M31.6,39.6c-0.6,0-1-0.4-1-1v-20c0-0.6,0.4-1,1-1c0.6,0,1,0.4,1,1v20C32.6,39.2,32.2,39.6,31.6,39.6"/> <path class="st1" d="M49.6,24.1h-4c-0.6,0-1-0.4-1-1s0.4-1,1-1h4c0.6,0,1,0.4,1,1S50.2,24.1,49.6,24.1"/> <path class="st0" d="M56.6,28.6c-0.6,0-1-0.4-1-1v-11h-48v11c0,0.6-0.4,1-1,1s-1-0.4-1-1v-12c0-0.6,0.4-1,1-1h50c0.6,0,1,0.4,1,1v12C57.6,28.2,57.2,28.6,56.6,28.6"/> <path class="st0" d="M60.6,25.6c-0.2,0-0.4-0.1-0.6-0.2l-4-3c-0.4-0.3-0.5-1-0.2-1.4c0.3-0.4,1-0.5,1.4-0.2l4,3c0.4,0.3,0.5,1,0.2,1.4C61.2,25.5,60.9,25.6,60.6,25.6"/> <path class="st0" d="M48.8,16.6c-0.2,0-0.4-0.1-0.6-0.2L31.6,3.9L15.1,16.4c-0.4,0.3-1.1,0.2-1.4-0.2c-0.3-0.4-0.2-1.1,0.2-1.4L31,1.8c0.4-0.3,0.9-0.3,1.2,0l17.1,13c0.4,0.3,0.5,1,0.2,1.4C49.4,16.5,49.1,16.6,48.8,16.6"/> <path class="st0" d="M2.6,25.6c-0.3,0-0.6-0.1-0.8-0.4c-0.3-0.4-0.2-1.1,0.2-1.4l4-3c0.4-0.3,1.1-0.2,1.4,0.2c0.3,0.4,0.2,1.1-0.2,1.4l-4,3C3.1,25.5,2.8,25.6,2.6,25.6"/> </g> </g> </g></svg> {{$recipe_title}}</div>
<div class="text-center padding-bottom-20 fs-20">{{$recipe_subtitle}}</div>
@if(filter_var($show_dcn_bar, FILTER_VALIDATE_BOOLEAN))
    <div class="section-blue-green-background text-center">
        <div class="usd_val">$<span></span></div>
        <div class="dcn_val">= <span></span> DCN</div>
    </div>
@elseif (!empty($withdrawableDCN))
    @php($dentacoin_data = (new \App\Http\Controllers\APIRequestsController())->getDentacoinDataByExternalProvider())
    <div class="section-blue-green-background text-center">
        <div class="usd_val">$<span>{{number_format($dentacoin_data['USD'] * $withdrawableDCN, 2, '.', '')}}</span></div>
        <div class="dcn_val">= <span>{{$withdrawableDCN}}</span> DCN</div>
    </div>
@endif
{{--<div class="input-row fs-0 margin-top-15">
    <label class="inline-block">To:</label>
    <div class="field inline-block">{{$to}}</div>
</div>
<div class="input-row fs-0">
    <label class="inline-block">From:</label>
    <div class="field inline-block">{{$current_logged_user->dcn_address}}</div>
</div>--}}
@if(!isset($sent_eth_to_dentist))
    <div class="input-row fs-0 ether-fee">
        <label class="inline-block">Ether Fee: <i class="fa fa-info-circle" data-toggle="popover" data-placement="bottom" data-content="Ether (ETH) is a currency that is used for covering your transaction costs. Don't have ETH? <a href='//wallet.dentacoin.com/buy' target='_blank'>Buy some with a card here</a>."></i></label>
        <div class="field inline-block"></div>
    </div>
@endif
<div class="extra-recipe-html"></div>
<div class="padding-top-30 fs-18 padding-bottom-20 text-center additional-text">{{$recipe_checkbox_text}}</div>
<div class="checkbox-container text-center">
    <div class="pretty p-svg p-curve on-white-background inline-block-important">
        <input type="checkbox" id="understand-and-agree"/>
        <div class="state p-success">
            <svg class="svg svg-icon" viewBox="0 0 20 20">
                <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
            </svg>
            <label class="fs-18">I understand and agree</label>
        </div>
    </div>
</div>
<div class="text-center execute-transaction padding-top-20">
    <div class="change-on-options-selected">
        <div class="hide-on-options-selected">
            @if(!empty($qr_scan) && $qr_scan == true)
                <a href="javascript:void(0)" class="blue-green-white-btn min-width-250 generate-qr-code-for-wallet-scanning width-xs-100 max-width-300"><svg version="1.1" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 24 24" style="enable-background:new 0 0 24 24;max-width: 25px;" xml:space="preserve" class="width-100 inline-block margin-right-10"><metadata><sfw xmlns="&ns_sfw;"><slices></slices><sliceSourceBounds bottomLeftOrigin="true" height="24" width="24" x="0" y="0"></sliceSourceBounds></sfw></metadata><path d="M19,2c1.7,0,3,1.3,3,3v14c0,1.7-1.3,3-3,3H5c-1.7,0-3-1.3-3-3V5c0-1.7,1.3-3,3-3H19z M19,0H5C2.2,0,0,2.2,0,5v14c0,2.8,2.2,5,5,5h14c2.8,0,5-2.2,5-5V5C24,2.2,21.8,0,19,0z M11,8h-1V6h1v1h2v1h-1v1h-1V8z M13,20v-1h-1v1H13z M12,5V4h-2v1h1v1h1V5z M20,4v6h-1V9h-4V4H20z M19,8V5h-3v3H19z M5,10H4v1h2v-1H5z M5,13h1v1h1v-3H6v1H4v2h1V13z M10,14v2h1v-2H10z M14,4h-1v3h1V4z M14,9V8h-1v1H14z M17,7h1V6h-1V7z M7,6H6v1h1V6z M9,4v5H4V4H9z M8,5H5v3h3V5z M17,10v1h-1v-1h-2v1h-1v-1h-3V9H9v1H8v1h1v2h1v-1h1v2h1v-2h3v1h-2v1h2v1h1v-3h1v1h1v2h1v-1h1v-1h-1v-1h-1v-1h1v-1H17z M6,18h1v-1H6V18z M4,15h5v5H4V15z M5,19h3v-3H5V19z M17,16v-1h-1v1H17z M17,17h-1v1h-1v-1h-1v-1h1v-1h-2v-1h-1v2h-1v1h-1v3h1v-1h1v-1h2v2h1v-1h1v1h2v-1h1v-1h-2V17z M8,14h1v-1H8V14z M18,16v1h1v1h1v-3h-1v1H18z M20,20v-1h-1v1H20z M20,12v-1h-1v1H20z"/></svg> CONTINUE IN WALLET</a>
                <span class="inline-block padding-left-15 padding-right-15 padding-top-10 padding-bottom-10 text-center calibri-bold fs-20 display-block-xs">or</span>
            @endif
            <a href="javascript:void(0)" class="white-blue-green-btn min-width-250 width-xs-100 max-width-300 continue-with-onsite-transaction-signing">CONTINUE HERE</a>
        </div>
    </div>
    <div class="onsite-transaction-signing">
        @if(filter_var($cached_key, FILTER_VALIDATE_BOOLEAN) == false)
            <div class="margin-top-30">
                @include('partials.address-validation-or-remember-me', ['cache' => false])
            </div>
        @endif
        <div class="camp-for-keystore-password" data-btn-label="{{$btn_label}}"></div>
        <div class="proof-success no-transition fs-20 calibri-bold text-center">
            Successful address and key verification.
            <div class="padding-top-20 text-center">
                <a href="javascript:void(0)" class="white-blue-green-btn min-width-250 margin-bottom-10 width-xs-100 max-width-400 fire-blockchain-transaction">{{$btn_label}}</a>
            </div>
        </div>
    </div>
</div>