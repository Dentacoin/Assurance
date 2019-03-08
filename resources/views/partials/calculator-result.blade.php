<div class="lato-regular fs-40 text-center padding-bottom-50 padding-top-30">Your income will increase with</div>
<div class="price-container">
    <div class="result">
        <span class="amount" data-result="{{$result}}">0</span><span class="symbol">{{$currency_symbol}}</span>
    </div>
</div>
<div class="text-center fs-16 padding-top-50 padding-bottom-50 width-70 margin-0-auto">* The result is calculated based on the suggested average Assurance premiums for your country. Premiums may vary depending on your personal agreement with your patients.</div>
@if((new \App\Http\Controllers\UserController())->checkSession())
    <div class="text-center padding-bottom-40">
        <a href="javascript:void(0)" class="fs-20 lato-regular blue-green-color calculate-again">calculate again</a>
    </div>
@else
    <div class="text-center">
        <a href="javascript:void(0)" class="white-blue-green-btn min-width-250 show-login-signin dentist-side">ENROLL DENTACOIN ASSURANCE</a>
    </div>
    <div class="text-center padding-top-30 padding-bottom-40">
        <a href="javascript:void(0)" class="fs-20 lato-regular blue-green-color calculate-again">or calculate again</a>
    </div>
@endif