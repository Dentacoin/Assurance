@php($user_data = (new \App\Http\Controllers\APIRequestsController())->getUserData(session('logged_user')['id']))
@php($dentacoin_data = (new \App\Http\Controllers\APIRequestsController())->getDentacoinDataByExternalProvider())
<section class="ready-to-purchase-with-external-api" id="ready-to-purchase-with-external-api" data-dcn-for-one-usd="{{$dcn_for_one_usd}}" data-usd-for-one-dcn="{{$dentacoin_data['USD']}}" data-eth-for-one-usd="{{$eth_for_one_usd}}">
    @if (!isset($type))
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <h2 class="lato-bold fs-45 fs-xs-30 padding-top-60 padding-top-xs-30">Ready to do it now?</h2>
                    <div class="fs-20 fs-xs-18 padding-top-15 padding-bottom-20">Follow the steps below to easily buy Dentacoin (DCN) with your bank card and charge your account.</div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-sm-offset-2 border-light-gray"></div>
            </div>
        </div>
    @endif
    <div class="form-container">
        <nav>
            <ul>
                <li class="inline-block"><a href="javascript:void(0)" class="active" data-currency="dcn">BUY DENTACOIN</a></li>
                <li class="inline-block"><a href="javascript:void(0)" data-currency="eth">BUY ETHER</a></li>
            </ul>
        </nav>
        <div class="container padding-top-40">
            <div class="row">
                <div class="col-xs-12 col-sm-5 col-sm-offset-1 col-md-4 col-md-offset-2 padding-right-50 padding-right-xs-15 padding-bottom-xs-20">
                    <div class="custom-google-label-style module fs-0 flex">
                        <div class="inline-block left-side">
                            <label class="active-label" for="usd-value">Pay with:</label>
                            <input type="number" id="usd-value" value="10"/>
                        </div>
                        <span class="inline-block">USD</span>
                    </div>
                    <div class="fs-16 padding-left-15 padding-left-xs-0 calibri-light light-gray-color">First transaction: $10 to $6,000. Daily limit: $20,000</div>
                </div>
                <div class="col-xs-12 col-sm-5 col-md-4 padding-left-50 padding-left-xs-15 padding-bottom-xs-20">
                    <div class="custom-google-label-style module fs-0 flex">
                        <div class="inline-block left-side">
                            <label for="crypto-amount" class="active-label">You get:</label>
                            <input type="number" id="crypto-amount"/>
                        </div>
                        <span class="inline-block crypto-label">DCN</span>
                    </div>
                    <div class="fs-16 padding-left-15 padding-left-xs-0 calibri-light light-gray-color">The exchange rate may change in the process.</div>
                </div>
            </div>
            <div class="row padding-top-50 padding-top-xs-0 padding-bottom-20">
                <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                    <div class="custom-google-label-style module" data-input-blue-green-border="true">
                        <label for="dcn_address" class="active-label">Address to receive DCN:</label>
                        <input type="url" id="dcn_address" maxlength="42" readonly class="full-rounded" value="@if (!empty($type) && $type == 'dentist') {{$contract->dentist_address}} @else {{$contract->patient_address}} @endif"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                    <div class="custom-google-label-style module" data-input-blue-green-border="true">
                        <label for="email" class="active-label">Email:</label>
                        <input type="email" id="email" maxlength="100" class="full-rounded" @if(!empty($user_data) && !empty($user_data->email)) value="{{$user_data->email}}" @endif/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 text-center">
                    <div class="checkbox-container padding-top-20">
                        <div class="pretty p-svg p-curve on-white-background inline-block-important">
                            <input type="checkbox" id="privacy-policy-agree"/>
                            <div class="state p-success">
                                <svg class="svg svg-icon" viewBox="0 0 20 20">
                                    <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                </svg>
                                <label class="fs-16 calibri-light light-gray-color">I have read and accept the <a href="//dentacoin.com/privacy-policy" target="_blank" class="blue-green-color">Privacy Policy</a></label>
                            </div>
                        </div>
                    </div>
                    <div class="padding-top-20 text-center">
                        Powered by
                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="padding-bottom-10">
                            <img alt="Indacoin logo" itemprop="contentUrl" src="/assets/images/indacoin-logo.svg" class="width-100 max-width-150"/>
                        </figure>
                        <div>All transactions are solely handled by Indacoin Limited.</div>
                        <a href="https://indacoin.com/" target="_blank" class="text-decoration-underline blue-green-color">www.indacoin.com</a>
                    </div>
                    <div class="btn-container padding-top-30">
                        <a href="javascript:void(0)" class="white-blue-green-btn min-width-220 buy-crypto-btn">BUY</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


