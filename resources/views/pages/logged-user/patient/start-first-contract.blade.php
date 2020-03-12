@extends("layout")
@section("content")
    <section class="section-logged-patient-form start-first-contract">
        <h1 class="fs-45 fs-xs-30 lato-bold text-center padding-top-110 padding-top-xs-50">Let's Start with a First Contract!</h1>
        <div class="container">
            <div class="row">
                @if(!empty($clinics))
                    <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 padding-top-20 padding-bottom-40">
                        <select class="combobox custom-input green-arrow-background dropdown-with-clinics" data-current-route="{{Route::current()->getName()}}">
                            <option></option>
                            @foreach($clinics as $clinic)
                                <option value="{{$clinic->id}}">{{$clinic->name}}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2  col-lg-6 col-lg-offset-3 form padding-top-50" id="invite-form">
                    <div class="padding-top-50 padding-bottom-50 padding-left-40 padding-right-40 padding-top-xs-30 padding-bottom-xs-30 padding-right-xs-15 padding-left-xs-15 padding-top-sm-30 padding-bottom-sm-30 padding-right-sm-30 padding-left-sm-30 white-color-background form-wrapper">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                                    <h2 class="fs-40 fs-xs-30 lato-bold text-center">Invite Your Dentist. Earn 20,000 DCN!</h2>
                                    <h3 class="fs-20 fs-xs-18 padding-top-20 padding-bottom-40 text-center">Help us change dentistry to the better by inviting new dentists. For each accepted invitation, you will receive a reward!</h3>
                                    @include('partials.invite-dentists-form', ['redirect' => 'patient-access'])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="white-bottom-background"></div>
    </section>
    <section class="logged-patient-your-benefits text-center">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="lato-bold fs-50 fs-xs-30 padding-top-40 padding-bottom-30 padding-bottom-xs-20">Your Benefits</h2>
                </div>
            </div>
        </div>
        <div class="custom-grid fs-0">
            <div class="cell inline-block">
                <div class="cell-wrapper solid-gray-color-background">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="" itemprop="contentUrl" src="/assets/uploads/patient-benefit-1.svg" class="max-width-80"/>
                        <figcaption class="fs-18 line-height-20 padding-top-20">Affordable monthly payments</figcaption>
                    </figure>
                </div>
            </div>
            <div class="cell inline-block">
                <div class="cell-wrapper solid-gray-color-background">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="" itemprop="contentUrl" src="/assets/uploads/patient-benefit-2.svg" class="max-width-80"/>
                        <figcaption class="fs-18 line-height-20 padding-top-20">Prevention-focused dental care</figcaption>
                    </figure>
                </div>
            </div>
            <div class="cell inline-block">
                <div class="cell-wrapper solid-gray-color-background">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="" itemprop="contentUrl" src="/assets/uploads/patient-benefit-3.svg" class="max-width-80"/>
                        <figcaption class="fs-18 line-height-20 padding-top-20">Wide range of services covered</figcaption>
                    </figure>
                </div>
            </div>
            <div class="cell inline-block">
                <div class="cell-wrapper solid-gray-color-background">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="" itemprop="contentUrl" src="/assets/uploads/patient-benefit-4.svg" class="max-width-80"/>
                        <figcaption class="fs-18 line-height-20 padding-top-15">Personalized recommendation</figcaption>
                    </figure>
                </div>
            </div>
            <div class="cell inline-block">
                <div class="cell-wrapper solid-gray-color-background">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="" itemprop="contentUrl" src="/assets/uploads/patient-benefit-5.svg" class="max-width-80"/>
                        <figcaption class="fs-18 line-height-20 padding-top-25">Compatible with other insurances</figcaption>
                    </figure>
                </div>
            </div>
            <div class="cell inline-block">
                <div class="cell-wrapper solid-gray-color-background">
                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                        <img alt="" itemprop="contentUrl" src="/assets/uploads/patient-benefit-6.svg" class="max-width-80"/>
                        <figcaption class="fs-18 line-height-20 padding-top-20">Free and easy cancellation</figcaption>
                    </figure>
                </div>
            </div>
        </div>
    </section>
@endsection

