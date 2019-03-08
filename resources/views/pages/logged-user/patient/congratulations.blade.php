@extends("layout")
@section("content")
    <section class="congratulation-and-time-section padding-top-100" data-time-left-next-transfer="{{strtotime($contract->contract_active_at)}}">
        <div class="absolute-white-background-line"></div>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="Check inside shield" src="/assets/uploads/shield-check.svg" class="max-width-70"/>
                    </figure>
                    <h1 class="lato-bold fs-30 padding-top-15">CONGRATULATION!</h1>
                    <div class="fs-20 padding-top-20 padding-bottom-20">Your Assurance Contract .pdf file was successfully created! </div>
                    <div class="padding-bottom-40"><a href="{{route('patient-contract-view', ['slug' => $contract->slug])}}" class="blue-green-white-btn min-width-220">SEE CONTRACT</a></div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                    <div class="timer-wrapper text-center padding-top-30 padding-bottom-50 padding-left-50 padding-right-50">
                        <h2 class="fs-30 lato-bold white-color padding-bottom-20">YOUR FIRST PAYMENT IS DUE IN:</h2>
                        <div class="clock"></div>
                        <div class="flip-clock-message"></div>
                        <div class="fs-20 white-color padding-top-20">You should charge your wallet with <span class="calibri-bold">{{$contract->monthly_premium}} USD in DCN until <span class="converted-date"></span></span>.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('partials.patient-ready-to-purchase-with-external-api')
@endsection
