{{var_dump($cache)}}
<div class="camping-for-validation module">
    <div class="single-row proof-of-address no-background" data-address="{{$current_logged_user_dcn_address}}">
        <div class="container-fluid">
            <div class="row fs-0">
                <div class="col-xs-12 col-sm-5 inline-block padding-left-30">
                    <a href="javascript:void(0)" class="blue-green-white-btn text-center enter-private-key display-block-important fs-18 line-height-18"><span>Enter your Private Key<div class="fs-16">(not recommended)</div></span></a>
                </div>
                <div class="col-xs-12 col-sm-2 text-center calibri-bold fs-20 inline-block">or</div>
                <div class="col-xs-12 col-sm-5 inline-block padding-right-30">
                    <div class="upload-file-container" data-id="upload-keystore-file" data-label="Upload your Keystore file">
                        <input type="file" id="upload-keystore-file" class="custom-upload-file caching hide-input"/>
                        <button type="button" class="display-block"></button>
                    </div>
                </div>
            </div>
            <div class="row on-change-result"></div>
        </div>
    </div>
</div>