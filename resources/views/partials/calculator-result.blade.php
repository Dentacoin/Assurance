<div class="lato-regular fs-40 text-center padding-bottom-50 fs-xs-26 padding-bottom-xs-20 padding-top-xs-20 padding-top-30">Your income will increase with</div>
<div class="price-container">
    <div class="result">
        <span class="amount" data-result="{{$result}}">0</span><span class="symbol">{{$currency_symbol}} / MONTH</span>
    </div>
</div>
<div class="text-center fs-16 padding-top-50 padding-top-xs-30 padding-bottom-50 width-70 width-xs-90 padding-bottom-xs-30 margin-0-auto">* The result is calculated based on the suggested average Assurance premiums for your country. Premiums may vary depending on your personal agreement with your patients.</div>
@if((new \App\Http\Controllers\UserController())->checkSession())
    <div class="text-center padding-bottom-40">
        <a href="javascript:void(0)" class="fs-20 lato-regular blue-green-color calculate-again">calculate again</a>
    </div>
@else
    <div class="text-center">
        <a href="javascript:void(0)" class="white-blue-green-btn min-width-250 open-dentacoin-gateway dentist-login">ENROLL DENTACOIN ASSURANCE</a>
    </div>
    <div class="text-center padding-top-30 padding-bottom-40">
        <a href="javascript:void(0)" class="fs-20 lato-regular blue-green-color calculate-again">or calculate again</a>
    </div>
@endif