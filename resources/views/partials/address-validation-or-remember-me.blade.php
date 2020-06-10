<div class="camping-for-validation module">
    <div class="single-row proof-of-address no-background" data-id="{{session('logged_user')['id']}}">
        <div class="container-fluid">
            <div class="row fs-0">
                <div class="min-width-260 inline-block padding-left-30 padding-left-xs-0 width-xs-100 max-width-300">
                    <a href="javascript:void(0)" class="blue-green-white-btn text-center enter-private-key display-block-important fs-18 line-height-18"><span>Enter your Private Key<div class="fs-16">(not recommended)</div></span></a>
                </div>
                <div class="padding-left-xs-15 padding-right-15 padding-top-10 padding-bottom-10 text-center calibri-bold fs-20 inline-block display-block-xs">or</div>
                <div class="min-width-260 inline-block padding-right-30 padding-right-xs-0 width-xs-100 max-width-300">
                    <div class="upload-file-container" data-id="upload-keystore-file" data-label="Upload your Keystore file">
                        <input type="file" id="upload-keystore-file" class="custom-upload-file @if(filter_var($cache, FILTER_VALIDATE_BOOLEAN)) caching @endif hide-input"/>
                        <div class="btn-wrapper"></div>
                    </div>
                </div>
            </div>
            <div class="row on-change-result"></div>
        </div>
    </div>
</div>