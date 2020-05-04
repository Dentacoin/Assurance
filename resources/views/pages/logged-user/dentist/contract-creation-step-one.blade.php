<div class="one step">
    <h2 class="text-center calibri-bold fs-30 padding-bottom-25">{{$current_logged_dentist->name}}</h2>
    <div class="avatar text-center">
        @if(!$current_logged_dentist->hasimage)
            <div class="inline-block-top avatar module upload-file">
                <input type="file" class="visualise-image inputfile" id="custom-upload-avatar" name="image" accept=".jpg,.png,.jpeg,.svg,.bmp"/>
                <input type="hidden" id="hidden-image" name="hidden-image"/>
                <div class="btn-wrapper"></div>
                <div id="cropper-container"></div>
                <div class="avatar-name"><span class="dcn-gateway-gray-color"></span><button class="destroy-croppie" type="button">×</button></div>
            </div>
        @else
            <figure itemscope="" itemtype="http://schema.org/ImageObject" class="text-center">
                <img alt="Dentist avatar" itemprop="contentUrl" src="{{$current_logged_dentist->avatar_url}}"/>
            </figure>
        @endif
    </div>
    <div class="step-fields module padding-top-35 padding-top-xs-20">
        <div class="single-row flex-row fs-0">
            <label class="calibri-light light-gray-color fs-16 padding-right-15 margin-bottom-0 cursor-pointer" for="professional-company-number">Professional/Company Registration Number:</label>
            <input type="text" maxlength="50" name="professional-company-number" id="professional-company-number" class="right-field calibri-regular fs-18 dark-color inline-block pencil-background " @if(!empty($renew_contract)) value="{{$renew_contract->professional_company_number}}" @else value="{{$dentist_registration_number}}" @endif/>
        </div>
        <div class="single-row flex-row fs-0">
            <label class="calibri-light light-gray-color fs-16 padding-right-15 margin-bottom-0">Postal Address:</label>
            <div class="right-field calibri-regular fs-18 dark-color" name="postal-address">{{$current_logged_dentist->address}}</div>
        </div>
        <div class="single-row flex-row fs-0">
            <label class="calibri-light light-gray-color fs-16 padding-right-15 margin-bottom-0">Country:</label>
            <div class="right-field calibri-regular fs-18 dark-color" name="country">{{$countries[$current_logged_dentist->country_id - 1]->name}}</div>
        </div>
        <div class="single-row flex-row fs-0">
            <label class="calibri-light light-gray-color fs-16 padding-right-15 margin-bottom-0">Phone:</label>
            @if(!empty($current_logged_dentist->phone))
                <div class="right-field calibri-regular fs-18 dark-color" name="phone">{{$current_logged_dentist->phone}}</div>
            @else
                <input type="number" data-type="phone" name="phone" maxlength="50" class="right-field calibri-regular fs-18 dark-color inline-block pencil-background"/>
            @endif
        </div>
        <div class="single-row flex-row fs-0">
            <label class="calibri-light light-gray-color fs-16 padding-right-15 margin-bottom-0">Website:</label>
            @if(!empty($current_logged_dentist->website))
                <div class="right-field calibri-regular fs-18 dark-color break-word" name="website"><a href="{{$current_logged_dentist->website}}" target="_blank">{{$current_logged_dentist->website}}</a></div>
            @else
                <input type="text" data-type="website" name="website" maxlength="250" class="right-field break-word calibri-regular fs-18 dark-color inline-block pencil-background"/>
            @endif
        </div>
        <div class="single-row flex-row fs-0 dcn-address-row">
            <label class="calibri-light light-gray-color fs-16 padding-right-15 margin-bottom-0 ">Wallet Address:</label>
            @php($addresses = (new \App\Http\Controllers\APIRequestsController())->getAddresses())
            <div class="right-extra-field no-padding break-word position-relative" id="search-result-parent">
                <input autocomplete="off" readonly type="text" data-type="address" id="dcn_address" name="address" maxlength="42" class="right-field width-100 calibri-regular fs-18 dark-color inline-block pencil-background search-input" placeholder="Select Wallet Address" @if(!empty($addresses) && !empty($addresses->data) && sizeof($addresses->data) == 1) value="{{$addresses->data[0]->dcn_address}}" @endif/>
                <div class="search-result module">
                    <div class="search-body">
                        <ul class="addresses-list" id="addresses-list">
                            @if(!empty($addresses) && !empty($addresses->data))
                                @foreach($addresses->data as $address)
                                    <li class="removeable-element fs-0" data-id="{{$address->id}}">
                                        <a href="javascript:void(0);" class="inline-block" data-value="{{$address->dcn_address}}">
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
                        <a href="javascript:void(0)" class="add-to-address-book lato-bold fs-18 fs-xs-16">+ Add to Address Book</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="camping-for-validation module">
            {{--RARE CASE - if user have address, but not from wallet.dentacoin.com--}}
            {{--@if(!empty($current_logged_dentist->dcn_address) && !(new \App\Http\Controllers\UserController())->checkIfWeHavePublicKeyOfAddress($current_logged_dentist->dcn_address))
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
                <div class="single-row proof-success no-transition padding-top-20 padding-bottom-20 fs-20 calibri-bold text-center">
                    Successful address verification.
                </div>
            @endif--}}
        </div>
        <div class="fs-14 calibri-light light-gray-color padding-top-10">This is the wallet where you will receive your monthly premiums. Please double-check if everything is correct.</div>
        <div class="fs-14 calibri-light light-gray-color">You don’t have a wallet? <a href="https://wallet.dentacoin.com" class="calibri-bold track-event-dentist-create-wallet" target="_blank">Create one here.</a></div>
    </div>
</div>