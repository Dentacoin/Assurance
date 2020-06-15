@extends("layout")
@section("content")
    @php($general_dentistry = unserialize($contract->general_dentistry))
    @php($created_at = $contract->created_at->format('d-m-Y'))
    <section class="padding-top-100 padding-top-xs-30 padding-top-sm-50 padding-bottom-50 contract-proposal section module" data-created-at-timestamp="{{strtotime($created_at)}}" @if((time() - strtotime($created_at)) / (60 * 60 * 24) > DAYS_ACTIVE_CONTRACT_PROPOSAL) data-expired="true" @endif>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h1 class="lato-bold fs-45 fs-xs-30 text-center padding-bottom-50 padding-bottom-xs-0">Review Assurance Contract</h1>
                </div>
            </div>
        </div>
        <div class="container padding-top-40">
            <div class="row">
                <div class="col-xs-12 col-lg-10 col-lg-offset-1 no-gutter-xs">
                    <div class="wrapper padding-top-50 padding-bottom-60">
                        <div class="top-right-page-alike"></div>
                        <h2 class="text-center blue-green-color fs-30 fs-xs-22 lato-bold padding-bottom-20">ASSURANCE CONTRACT SAMPLE</h2>
                        <div class="fs-18 padding-bottom-50 text-center blue-green-color">( This contract proposal will be active until <span class="active-until">{{date('d/m/Y', strtotime('+'.DAYS_ACTIVE_CONTRACT_PROPOSAL.' days', strtotime($created_at))) . PHP_EOL}}</span>. )</div>
                        <div class="step-fields module padding-top-20">
                            <form method="POST" enctype="multipart/form-data" action="{{route('update-and-sign-contract')}}" id="patient-update-and-sign-contract" class="address-suggester-wrapper">
                                <h3 class="calibri-bold fs-30 dark-color">DENTIST DETAILS</h3>
                                <div class="single-row fs-0">
                                    <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Name:</label>
                                    <div class="right-extra-field calibri-bold fs-25 dark-color inline-block">{{$contract->dentist_name}}</div>
                                </div>
                                <div class="single-row fs-0">
                                    <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Professional / Company Registration Number:</label>
                                    <div class="right-extra-field calibri-regular fs-18 dark-color inline-block">{{$contract->professional_company_number}}</div>
                                </div>
                                <div class="single-row fs-0">
                                    <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Postal Address:</label>
                                    <div class="right-extra-field calibri-regular fs-18 dark-color inline-block">{{$contract->dentist_street_address}}</div>
                                </div>
                                <div class="single-row fs-0">
                                    <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Country:</label>
                                    <div class="right-extra-field calibri-regular fs-18 dark-color inline-block">{{$contract->dentist_country}}</div>
                                </div>
                                <div class="single-row fs-0">
                                    <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Phone:</label>
                                    <div class="right-extra-field calibri-regular fs-18 dark-color inline-block">{{$contract->dentist_phone}}</div>
                                </div>
                                <div class="single-row fs-0">
                                    <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Website:</label>
                                    <div class="right-extra-field calibri-regular fs-18 dark-color inline-block break-word">
                                        <a href="{{$contract->dentist_website}}" target="_blank">{{$contract->dentist_website}}</a>
                                    </div>
                                </div>
                                <div class="single-row fs-0">
                                    <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Wallet Address:</label>
                                    <div class="right-extra-field calibri-regular fs-18 dark-color inline-block break-word">
                                        <a href="//etherscan.io/address/{{$contract->dentist_address}}" target="_blank">{{$contract->dentist_address}}</a>
                                    </div>
                                </div>
                                <div class="fs-14 calibri-light light-gray-color padding-top-5">This is the wallet where you will automatically transfer your monthly premiums to.</div>
                                <h3 class="calibri-bold fs-30 dark-color padding-top-70">PATIENT DETAILS</h3>
                                <div class="single-row fs-0">
                                    <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">First Name:</label>
                                    <div class="right-extra-field calibri-bold fs-25 dark-color inline-block">{{$contract->patient_fname}}</div>
                                </div>
                                <div class="single-row fs-0">
                                    <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Last Name:</label>
                                    <div class="right-extra-field calibri-bold fs-25 dark-color inline-block">{{$contract->patient_lname}}</div>
                                </div>
                                <div class="single-row fs-0">
                                    <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Email Address:</label>
                                    <div class="right-extra-field calibri-regular fs-18 dark-color inline-block break-word">{{$current_logged_patient->email}}</div>
                                </div>
                                <div class="single-row fs-0">
                                    <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0 padding-top-0 padding-bottom-0 cursor-pointer" for="patient-id-number">ID Number:</label>
                                    <input type="text" maxlength="20" id="patient-id-number" name="patient-id-number" class="right-field required-field calibri-regular fs-18 dark-color inline-block pencil-background" value="{{$patient_id_number}}"/>
                                </div>
                                <div class="single-row fs-0 country-select">
                                    <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0 @if(empty($patient->country_id)) padding-top-0 padding-bottom-0 cursor-pointer @endif" @if(empty($patient->country_id)) for="country" @endif >Country:</label>
                                    @if(!empty($patient->country_id))
                                        <div class="right-extra-field calibri-regular fs-18 dark-color inline-block" data-country-code="{{$countries[$patient->country_id - 1]->code}}">{{$countries[$patient->country_id - 1]->name}}</div>
                                    @else
                                        <select class="inline-block fs-18 right-field required-field" id="country" name="country">
                                            <option disabled selected>Select country</option>
                                            @foreach($countries as $country)
                                                <option value="{{$country->code}}" data-code="{{$country->phone_code}}">{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                                <div class="single-row fs-0">
                                    @if(empty($patient->address))
                                        <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0 padding-top-0 padding-bottom-0 cursor-pointer" for="address">Postal Address:</label>
                                        <div class="suggester-parent module inline-block">
                                            <input type="text" name="address" id="address" maxlength="250" class="address-suggester init-address-suggester calibri-regular fs-18 dark-color pencil-background required-field" autocomplete="off">
                                            <div class="fs-14 padding-top-5">Ex: 49 Pembroke Square, Kensington, London</div>
                                            <div class="suggester-map-div margin-top-15 margin-bottom-10"></div>
                                            <div class="alert alert-notice geoip-confirmation margin-top-10 margin-bottom-10 hide-this">Please check the map to make sure we got your correct address. If you're not happy - please drag the map to adjust it.</div>
                                            <div class="alert alert-warning geoip-hint margin-top-10 margin-bottom-10">Please enter a valid address for your practice (including street name and number).</div>
                                            <div class="alert alert-warning different-country-hint margin-top-10 margin-bottom-10">Unable to proceed. Please, choose address from your country.</div>
                                        </div>
                                    @else
                                        <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0 padding-top-0 padding-bottom-0 cursor-pointer">Postal Address:</label>
                                        <div class="right-extra-field calibri-regular fs-18 dark-color inline-block">{{$patient->address}}</div>
                                    @endif
                                </div>
                                <div class="single-row fs-0 dcn-address-row">
                                    <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0 cursor-pointer padding-top-0 padding-bottom-0" for="dcn_address">Wallet Address:</label>
                                    @php($addresses = (new \App\Http\Controllers\APIRequestsController())->getAddresses())
                                    <div class="right-extra-field no-padding position-relative inline-block break-word" id="search-result-parent">
                                        <input autocomplete="off" readonly type="text" maxlength="42" id="dcn_address" name="dcn_address" class="right-field width-100 required-field calibri-regular fs-18 dark-color search-input" placeholder="Select Wallet Address" @if(!empty($addresses) && !empty($addresses->data) && sizeof($addresses->data) == 1) value="{{$addresses->data[0]->dcn_address}}" @endif/>
                                        <div class="search-result module">
                                            <div class="search-body">
                                                <ul class="addresses-list" id="addresses-list">
                                                    @if(!empty($addresses) && !empty($addresses->data))
                                                        @foreach($addresses->data as $address)
                                                            <li class="platform-color removeable-element fs-0" data-id="{{$address->id}}">
                                                                <a href="javascript:void(0);" class="platform-background-on-hover inline-block" data-value="{{$address->dcn_address}}">
                                                                    @if(empty($address->dcn_address_label))
                                                                        {{$address->dcn_address}}
                                                                    @else
                                                                        {{$address->dcn_address_label}} ({{$address->dcn_address}})
                                                                    @endif
                                                                </a>
                                                                <button type="button" class="remove-address-book-element inline-block">×</button>
                                                            </li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                            <div class="search-footer">
                                                <a href="javascript:void(0)" class="platform-color add-to-address-book lato-bold fs-18 fs-xs-16">+ Add New Wallet Address</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="camping-for-validation module">
                                    {{--RARE CASE - if user have address, but not from wallet.dentacoin.com--}}
                                    {{--@if(!empty($patient->dcn_address) && !(new \App\Http\Controllers\UserController())->checkIfWeHavePublicKeyOfAddress($patient->dcn_address))
                                        <div class="single-row proof-of-address padding-bottom-20">
                                            <div class="text-center calibri-bold fs-18 padding-top-20 padding-bottom-15">PLEASE VERIFY YOU OWN THIS ADDRESS</div>
                                            <div class="container-fluid">
                                                <div class="row fs-0">
                                                    <div class="col-xs-12 col-sm-5 inline-block padding-left-30 padding-left-xs-15">
                                                        <a href="javascript:void(0)" class="blue-green-white-btn text-center enter-private-key display-block-important fs-18 line-height-18"><span>Enter your Private Key<div class="fs-16">(not recommended)</div></span></a>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-2 text-center calibri-bold fs-20 inline-block">or</div>
                                                    <div class="col-xs-12 col-sm-5 inline-block padding-right-30">
                                                        <div class="upload-file-container" data-id="upload-keystore-file" data-label="Upload your Keystore file">
                                                            <input type="file" id="upload-keystore-file" class="custom-upload-file hide-input"/>
                                                            <div class="btn-wrapper"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row on-change-result"></div>
                                            </div>
                                        </div>
                                        <div class="single-row proof-success no-transition padding-top-20 padding-bottom-20 fs-20 calibri-bold text-center">Successful address verification.</div>
                                    @endif--}}
                                </div>
                                <div class="light-gray-color fs-14 padding-top-5">This is the wallet where you will send your monthly premiums from and collect your rewards from all Dentacoin tools. Please double-check if everything is correct. You don’t have a wallet? <a href="//wallet.dentacoin.com" class="blue-green-color calibri-bold" target="_blank">Create one here.</a></div>
                                <h3 class="calibri-bold fs-30 dark-color padding-top-70">CONTRACT CONDITIONS</h3>
                                <div class="single-row fs-0 padding-top-10">
                                    <label class="calibri-light light-gray-color fs-16 padding-right-15 padding-top-0 margin-bottom-0 inline-block">Services Covered:</label>
                                    <div class="right-extra-field checkboxes-right-container calibri-regular fs-18 dark-color inline-block">
                                        @if(in_array('param_gd', $general_dentistry))
                                            <div class="pretty margin-bottom-5 p-svg p-curve on-white-background">
                                                <input type="checkbox" disabled checked/>
                                                <div class="state p-success">
                                                    <!-- svg path -->
                                                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                    </svg>
                                                    <label class="fs-18 calibri-light">General Dentistry</label>
                                                </div>
                                            </div>
                                        @endif
                                        @if(in_array('param_cd', $general_dentistry))
                                            <div class="pretty margin-bottom-5 p-svg p-curve on-white-background">
                                                <input type="checkbox" disabled checked/>
                                                <div class="state p-success">
                                                    <!-- svg path -->
                                                    <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                        <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                    </svg>
                                                    <label class="fs-18 calibri-light">Cosmetic Dentistry</label>
                                                </div>
                                            </div>
                                        @endif
                                        @if(in_array('param_id', $general_dentistry))
                                            <div class="pretty margin-bottom-5 p-svg p-curve on-white-background">
                                                <input type="checkbox" disabled checked/>
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
                                    <div class="right-extra-field calibri-bold fs-25 dark-color inline-block">{{$contract->monthly_premium}} USD</div>
                                </div>
                                <div class="single-row fs-14 light-gray-color calibri-light padding-top-10 padding-bottom-40">You are not satisfied with the rate offered? <a href="javascript:void(0)" class="calibri-bold blue-green-color contact-your-dentist">Contact your dentist.</a> </div>
                                <div class="single-row flex-row fs-0">
                                    <label class="calibri-light light-gray-color fs-16 padding-right-15 margin-bottom-0">Required Check-ups per Year:</label>
                                    <div class="right-extra-field calibri-regular fs-18 dark-color inline-block">{{$contract->check_ups_per_year}}</div>
                                </div>
                                <div class="single-row flex-row fs-0">
                                    <label class="calibri-light light-gray-color fs-16 padding-right-15 margin-bottom-0">Required Teeth Cleaning per Year:</label>
                                    <div class="right-extra-field calibri-regular fs-18 dark-color inline-block">{{$contract->teeth_cleaning_per_year}}</div>
                                </div>
                                <div class="single-row flex-row fs-0">
                                    <label class="calibri-light light-gray-color fs-16 padding-right-15 margin-bottom-0">Required Successful Dentacare Journeys:</label>
                                    <div class="right-extra-field calibri-regular fs-18 dark-color inline-block">1 (90 days)</div>
                                </div>
                                <h3 class="calibri-bold fs-30 dark-color padding-top-70">CONTRACT DETAILS</h3>
                                <div class="terms-and-conditions-long-list margin-top-30 margin-bottom-60 margin-bottom-xs-30">
                                    @include('partials.contract-terms-and-conditions', ['contract' => $contract])
                                </div>
                                <div class="singatures-row fs-0">
                                    <div class="dentist-sign inline-block">
                                        <figure itemscope="" itemtype="http://schema.org/ImageObject">
                                            <img src="/assets/contracts/{{$contract->slug}}/dentist-signature.png" alt="Dentist signature"/>
                                            <figcaption class="fs-16 calibri-light">/{{$contract->dentist_name}}/</figcaption>
                                        </figure>
                                    </div>
                                    <div class="signature-wrapper inline-block module">
                                        <div class="calibri-bold fs-26 fs-xs-20 text-center">Sign below</div>
                                        <div class="calibri-light fs-14 text-center light-gray-color padding-bottom-5">Use your mouse or touch screen to sign.</div>
                                        <canvas id="signature-pad" class="signature-pad"></canvas>
                                        <a href="javascript:void(0)" class="blue-green-color calibri-bold fs-18 clear-signature">Clear</a>
                                    </div>
                                </div>
                                <div class="checkbox-container">
                                    <div class="pretty p-svg p-curve on-white-background inline-block-important">
                                        <input type="checkbox" id="terms"/>
                                        <div class="state p-success">
                                            <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                            </svg>
                                            <label class="fs-16 calibri-bold">I have read and agree to the Contract Details above</label>
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
                                            <label class="fs-16 calibri-bold">I have read and accept the <a href="//dentacoin.com/privacy-policy" target="_blank" class="blue-green-color">Privacy Policy</a></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center btns-container padding-top-50">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <input type="hidden" name="patient_signature"/>
                                    <input type="hidden" name="contract" value="{{$contract->slug}}"/>
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-xs-6 padding-left-0 padding-right-5">
                                                <a href="javascript:void(0)" class="white-red-btn min-width-220 min-width-xs-0 cancel-contract-btn track-event-patient-rejecting-pending-contract" data-type="patient-rejecting" data-contract="{{$contract->slug}}">REJECT</a>
                                            </div>
                                            <div class="col-xs-6 padding-right-0 padding-left-5">
                                                <input type="submit" value="SIGN CONTRACT" class="white-blue-green-btn min-width-220 min-width-xs-0"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
