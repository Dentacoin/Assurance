<div class="contract-details module">
    <div class="top-right-page-alike"></div>
    <h2 class="text-center lato-bold fs-30 fs-xs-24 padding-bottom-30 padding-bottom-xs-20 blue-green-color">ASSURANCE CONTRACT SAMPLE</h2>
    @if(!empty($subtitle))
        <p class="calibri-bold fs-18 fs-xs-16 dark-color padding-top-10 padding-bottom-40 text-center">{!! $subtitle !!}</p>
    @endif
    @if(!empty($contract))
        <h3 class="calibri-bold fs-30 fs-xs-22 dark-color">DENTIST DETAILS</h3>
    @else
        <h3 class="calibri-bold fs-30 fs-xs-22 dark-color">YOUR DETAILS</h3>
    @endif
    <div class="step-fields module padding-top-20">
        <div class="single-row fs-0">
            <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Name:</label>
            <div class="right-extra-field calibri-bold fs-25 dark-color inline-block">
                @if(!empty($contract))
                    {{$contract->dentist_name}}
                @elseif(!empty($current_logged_dentist))
                    @if (!empty($sender->title))
                        @php($api_enums = (new \App\Http\Controllers\APIRequestsController())->getAllEnums())
                        @foreach ($api_enums->titles as $key => $title)
                            @if ($sender->title == $key)
                                {{$title}} {{$current_logged_dentist->name}}
                                @break
                            @endif
                        @endforeach
                    @else
                        {{$current_logged_dentist->name}}
                    @endif
                @endif
            </div>
        </div>
        @if(!empty($contract) || !empty($current_logged_dentist))
            <div class="single-row fs-0">
                <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Email Address:</label>
                <div class="right-extra-field calibri-regular fs-18 dark-color inline-block break-word">
                    @if(!empty($contract))
                        <a href="mailto:{{$contract->dentist_email}}">{{$contract->dentist_email}}</a>
                    @elseif(!empty($current_logged_dentist))
                        <a href="mailto:{{$current_logged_dentist->email}}">{{$current_logged_dentist->email}}</a>
                    @endif
                </div>
            </div>
        @endif
        <div class="single-row fs-0">
            <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Professional/Company Registration Number:</label>
            <div class="right-extra-field calibri-regular fs-18 dark-color inline-block" id="professional-company-number">@if(!empty($contract)){{$contract->professional_company_number}}@endif</div>
        </div>
        <div class="single-row fs-0">
            <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Postal Address:</label>
            <div class="right-extra-field calibri-regular fs-18 dark-color inline-block" id="postal-address">@if(!empty($contract)){{$contract->dentist_street_address}}@endif</div>
        </div>
        <div class="single-row fs-0">
            <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Country:</label>
            <div class="right-extra-field calibri-regular fs-18 dark-color inline-block" id="country">@if(!empty($contract)){{$contract->dentist_country}}@endif</div>
        </div>
        <div class="single-row fs-0">
            <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Phone:</label>
            <div class="right-extra-field calibri-regular fs-18 dark-color inline-block" id="phone">@if(!empty($contract)) {{$contract->dentist_phone}}@endif</div>
        </div>
        <div class="single-row fs-0">
            <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Website:</label>
            <div class="right-extra-field calibri-regular fs-18 dark-color inline-block break-word" id="website">@if(!empty($contract))<a href="{{$contract->dentist_website}}" target="_blank">{{$contract->dentist_website}}</a>@endif</div>
        </div>
        <div class="single-row fs-0">
            <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Wallet Address:</label>
            <div class="right-extra-field calibri-regular fs-18 dark-color inline-block break-word" id="address">@if(!empty($contract))<a href="//etherscan.io/address/{{$contract->dentist_address}}" target="_blank">{{$contract->dentist_address}}</a>@endif</div>
        </div>
        @if(!empty($contract) && (!empty($contract->patient_full_name) || (!empty($contract->patient_fname) && !empty($contract->patient_lname))))
            <h3 class="calibri-bold fs-30 fs-xs-22 dark-color padding-top-50">PATIENT DETAILS</h3>
            <div class="single-row fs-0">
                <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Name:</label>
                <div class="right-extra-field calibri-bold fs-25 dark-color inline-block">
                    @if (!empty($contract->patient_full_name))
                        {{$contract->patient_full_name}}
                    @else
                        {{$contract->patient_fname}} {{$contract->patient_lname}}
                    @endif
                </div>
            </div>
        @else
            <div class="fs-14 calibri-light light-gray-color padding-top-10">This is the wallet where you will receive your monthly premiums. Please double-check if everything is correct.</div>
            <div class="fs-14 calibri-light light-gray-color">You don’t have a wallet? <a href="//wallet.dentacoin.com" target="_blank">Create one here.</a></div>
            <h3 class="calibri-bold fs-30 fs-xs-22 dark-color padding-top-50">PATIENT DETAILS</h3>
            <div class="single-row fs-0">
                <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">First Name:</label>
                <div class="right-extra-field calibri-bold fs-25 dark-color inline-block" id="fname"></div>
            </div>
            <div class="single-row fs-0">
                <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Last Name:</label>
                <div class="right-extra-field calibri-bold fs-25 dark-color inline-block" id="lname"></div>
            </div>
        @endif
        <div class="single-row fs-0">
            <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Email Address:</label>
            <div class="right-extra-field calibri-regular fs-18 dark-color inline-block break-word" id="email">
                @if(!empty($contract))
                    <a href="mailto:{{$contract->patient_email}}">{{$contract->patient_email}}</a>
                @endif</div>
        </div>
        @if(!empty($contract) && !empty($contract->patient_street_address) && !empty($contract->patient_country))
            <div class="single-row fs-0">
                <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Postal Address:</label>
                <div class="right-extra-field calibri-regular fs-18 dark-color inline-block break-word">{{$contract->patient_street_address}}</div>
            </div>
            <div class="single-row fs-0">
                <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Country:</label>
                <div class="right-extra-field calibri-regular fs-18 dark-color inline-block break-word">{{$contract->patient_country}}</div>
            </div>
        @endif
        @if(!empty($contract) && !empty($contract->patient_address))
            <div class="single-row fs-0">
                <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Wallet Address:</label>
                <div class="right-extra-field calibri-regular fs-18 dark-color inline-block break-word">
                    <a href="//etherscan.io/address/{{$contract->patient_address}}" target="_blank">{{$contract->patient_address}}</a>
                </div>
            </div>
        @endif
        <h3 class="calibri-bold fs-30 fs-xs-22 dark-color padding-top-50">CONTRACT CONDITIONS</h3>
        <h3 class="lato-semibold light-gray-color fs-16 padding-top-35 padding-bottom-10 light-gray-bottom-border">Required Prophylaxis <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" title="Prophylaxis visits are always covered by Dentacoin Assurance."></i> :</h3>
        <div class="single-row fs-0 no-border">
            <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Required Check-ups per Year:</label>
            <<div class="right-extra-field calibri-regular fs-18 dark-color inline-block" id="check-ups-per-year">@if(!empty($contract)){{$contract->check_ups_per_year}}@endif</div>
        </div>
        <div class="single-row fs-0 no-border">
            <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Required Teeth Cleaning per Year:</label>
            <div class="right-extra-field calibri-regular fs-18 dark-color inline-block" id="teeth-cleaning-per-year">@if(!empty($contract)){{$contract->teeth_cleaning_per_year}}@endif</div>
        </div>
        <div class="single-row fs-0 margin-bottom-15">
            <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Required Successful <a href="https://dentacare.dentacoin.com/" target="_blank" class="text-decoration-underline blue-green-color lato-semibold">Dentacare Journeys</a>:</label>
            <div class="right-extra-field calibri-regular fs-18 dark-color inline-block">1 (90 days)</div>
        </div>
        @if(!empty($contract) && !empty($calculator_proposals))
            @php($general_dentistry_arr = unserialize($contract->general_dentistry))

            @if(in_array('param_gd', $general_dentistry_arr) && in_array('param_cd', $general_dentistry_arr) && in_array('param_id', $general_dentistry_arr))
                @php($price = $calculator_proposals['param_gd_cd_id'])
            @elseif(in_array('param_gd', $general_dentistry_arr) && in_array('param_cd', $general_dentistry_arr))
                @php($price = $calculator_proposals['param_gd_cd'])
            @elseif(in_array('param_gd', $general_dentistry_arr) && in_array('param_id', $general_dentistry_arr))
                @php($price = $calculator_proposals['param_gd_id'])
            @elseif(in_array('param_cd', $general_dentistry_arr) && in_array('param_id', $general_dentistry_arr))
                @php($price = $calculator_proposals['param_cd_id'])
            @elseif(in_array('param_gd', $general_dentistry_arr))
                @php($price = $calculator_proposals['param_gd'])
            @elseif(in_array('param_cd', $general_dentistry_arr))
                @php($price = $calculator_proposals['param_cd'])
            @elseif(in_array('param_id', $general_dentistry_arr))
                @php($price = $calculator_proposals['param_id'])
            @endif
        @endif
        <div class="single-row fs-0">
            <label class="calibri-light light-gray-color fs-16 padding-right-15 padding-top-0 margin-bottom-0 inline-block">Services Covered:</label>
            <div class="right-extra-field checkboxes-right-container calibri-regular fs-18 dark-color inline-block">
                @php($show_gd = false)
                @if(!empty($contract))
                    @if(in_array('param_gd', $general_dentistry_arr))
                        @php($show_gd = true)
                    @endif
                @else
                    @php($show_gd = true)
                @endif
                @if($show_gd)
                    <div class="pretty margin-bottom-5 p-svg p-curve on-white-background">
                        <input type="checkbox" id="param_gd" checked disabled/>
                        <div class="state p-success">
                            <!-- svg path -->
                            <svg class="svg svg-icon" viewBox="0 0 20 20">
                                <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                            </svg>
                            <label class="fs-18 calibri-light">General Dentistry</label>
                        </div>
                    </div>
                @endif

                @php($show_cd = false)
                @if(!empty($contract))
                    @if(in_array('param_cd', $general_dentistry_arr))
                        @php($show_cd = true)
                    @endif
                @else
                    @php($show_cd = true)
                @endif
                @if($show_cd)
                    <div class="pretty margin-bottom-5 p-svg p-curve on-white-background">
                        <input type="checkbox" id="param_cd" checked disabled/>
                        <div class="state p-success">
                            <!-- svg path -->
                            <svg class="svg svg-icon" viewBox="0 0 20 20">
                                <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                            </svg>
                            <label class="fs-18 calibri-light">Cosmetic Dentistry</label>
                        </div>
                    </div>
                @endif

                @php($show_id = false)
                @if(!empty($contract))
                    @if(in_array('param_id', $general_dentistry_arr))
                        @php($show_id = true)
                    @endif
                @else
                    @php($show_id = true)
                @endif
                @if($show_id)
                    <div class="pretty margin-bottom-5 p-svg p-curve on-white-background">
                        <input type="checkbox" id="param_id" checked disabled/>
                        <div class="state p-success">
                            <!-- svg path -->
                            <svg class="svg svg-icon" viewBox="0 0 20 20">
                                <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                            </svg>
                            <label class="fs-18 calibri-light">Implant Dentistry</label>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="single-row fs-0">
            <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Monthly Premium:</label>
            <div class="right-extra-field calibri-bold fs-25 dark-color inline-block"><span id="monthly-premium">@if(!empty($contract)){{$contract->monthly_premium}}@endif</span> USD</div>
        </div>
        <div class="single-row fs-0">
            <div class="fs-14 calibri-light light-gray-color padding-top-5">Based on the services covered, the average monthly rate in your country is <span class="calibri-bold blue-green-color"><span id="suggested-price">@if(!empty($price)) {{$price}} @endif</span> USD</span> in Dentacoin (DCN).</div>
            <div class="fs-14 calibri-light light-gray-color padding-bottom-25">You are free to propose a different rate to your patient.</div>
        </div>
        <div class="single-row fs-0 margin-bottom-15">
            <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Contract Validity:</label>
            <div class="right-extra-field calibri-regular fs-18 dark-color inline-block">Valid until canceled</div>
        </div>
        <h3 class="calibri-bold fs-30 fs-xs-22 dark-color padding-top-50">CONTRACT DETAILS</h3>
        <div class="terms-and-conditions-long-list margin-top-30 margin-bottom-60 margin-bottom-xs-30">
            @if(!empty($contract))
                @include('partials.contract-terms-and-conditions', ['contract' => $contract])
            @else
                @include('partials.contract-terms-and-conditions')
            @endif
        </div>
        @if($type == 'contract-creation-step-four')
            <div class="signature-and-checkboxes">
                <div class="calibri-bold fs-26 fs-xs-20 text-center">Sign below</div>
                <div class="calibri-light fs-16 text-center light-gray-color padding-bottom-15">Use your mouse or touch screen to sign.</div>
                <div class="signature-wrapper module">
                    <canvas id="signature-pad" class="signature-pad"></canvas>
                </div>
                <div class="text-right">
                    <a href="javascript:void(0)" class="blue-green-color calibri-bold fs-18 clear-signature">Clear</a>
                </div>
                <div class="checkbox-container padding-top-15">
                    <div class="pretty p-svg p-curve on-white-background inline-block-important">
                        <input type="checkbox" id="terms"/>
                        <div class="state p-success">
                            <svg class="svg svg-icon" viewBox="0 0 20 20">
                                <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                            </svg>
                            <label class="fs-16 calibri-bold">I have read and agree to the Contract Details above.</label>
                        </div>
                    </div>
                </div>
                <div class="checkbox-container">
                    <div class="pretty p-svg p-curve on-white-background inline-block-important">
                        <input type="checkbox" id="privacy-policy"/>
                        <div class="state p-success">
                            <svg class="svg svg-icon" viewBox="0 0 20 20">
                                <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                            </svg>
                            <label class="fs-16 calibri-bold">I have read and accept the <a href="https://dentacoin.com/privacy-policy" target="_blank" class="blue-green-color">Privacy Policy</a></label>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>