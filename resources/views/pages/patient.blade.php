@extends("layout")
@section("content")
    <section class="module intro-section">
        <picture itemscope="" itemtype="http://schema.org/ImageObject">
            <img alt="Two dentists" itemprop="contentUrl" src="/assets/uploads/assurance-home-img.jpg"/>
        </picture>
        <div class="absolute-container container">
            <div class="row">
                <div class="col-xs-12 col-md-6 col-md-offset-6 text-center-xs text-center-sm padding-bottom-xs-40 padding-top-xs-40 padding-bottom-sm-60 padding-top-sm-60">
                    <h1 class="lato-black fs-40 fs-xs-30">DENTACOIN ASSURANCE</h1>
                    <div class="lato-regular fs-40 fs-xs-30 line-height-45 padding-top-20 padding-bottom-30">Get Lifelong Dental Care for Low Monthly Premiums!
                    </div>
                    <div>
                        <a href="javascript:void(0)" class="white-blue-green-btn ask-your-dentist-for-assurance">ASK YOUR DENTIST FOR ASSURANCE</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="module dentist-or-patient-section">
        <div class="container-fluid">
            <div class="row fs-0">
                <div class="col-xs-6 padding-left-0 padding-right-xs-5 inline-block-top left">
                    <picture itemscope="" itemtype="http://schema.org/ImageObject">
                        <source media="(max-width: 450px)" srcset="/assets/uploads/mobile-male-dentist.jpg" />
                        <source media="(max-width: 768px)" srcset="/assets/uploads/dentist-small.jpg" />
                        <img alt="Two dentists" itemprop="contentUrl" src="/assets/uploads/dentist-big.jpg"/>
                    </picture>
                    <span class="hidden-container">
                        <a href="/" class="white-transparent-btn">I'm a Dentist</a>
                        <div class="hidden-label white-transparent-btn">I'm a Dentist</div>
                    </span>
                </div>
                <div class="col-xs-6 padding-right-0 padding-left-xs-5 inline-block-top right">
                    <picture itemscope="" itemtype="http://schema.org/ImageObject">
                        <source media="(max-width: 450px)" srcset="/assets/uploads/mobile-female-dentist.jpg" />
                        <source media="(max-width: 768px)" srcset="/assets/uploads/patient-small.jpg" />
                        <img alt="Two dentists" itemprop="contentUrl" src="/assets/uploads/patient-big.jpg"/>
                    </picture>
                    <span class="hidden-container">
                        <a href="/patient" class="white-transparent-btn">I'm a Patient</a>
                        <div class="hidden-label white-transparent-btn">I'm a Patient</div>
                    </span>
                </div>
            </div>
        </div>
    </section>
    <section class="module easy-fast-steps-section padding-top-80 padding-top-xs-40 padding-bottom-50 padding-bottom-xs-30">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="text-center lato-bold fs-45 fs-xs-30">EASY. FAST. AFFORDABLE.</h2>
                    <div class="text-center lato-regular fs-30 fs-xs-25 padding-bottom-50 padding-top-10">Let’s Reshape Dental Care Together!</div>
                </div>
            </div>
            <div class="row fs-0 flex no-flex-xs">
                <figure itemscope="" itemtype="http://schema.org/ImageObject" class="col-xs-12 col-sm-3 col-sm-offset-1 col-md-offset-0 col-lg-1 col-lg-offset-2 inline-block text-center">
                    <img alt="Sign up" class="max-width-100" itemprop="contentUrl" src="/assets/uploads/sign-up.svg"/>
                </figure>
                <div class="inline-block col-xs-12 col-sm-6 col-md-4 text-center-xs">
                    <div class="fs-26 fs-xs-20 lato-bold padding-bottom-10 padding-top-10">Sign Up</div>
                    <div class="fs-20 fs-xs-16 padding-bottom-40 padding-left-15 padding-right-15">You enroll Dentacoin Assurance and sign a contract with your dentist. If your dentist hasn’t joined yet, you can invite them and earn DCN!</div>
                </div>
                <div class="inline-block col-xs-4 col-sm-1 col-md-3 hide-xs">
                    <div class="line"><div class="bullet"></div></div>
                </div>
            </div>
            <div class="row fs-0 flex no-flex-xs">
                <figure itemscope="" itemtype="http://schema.org/ImageObject" class="col-xs-12 col-sm-3 col-sm-offset-1 col-md-offset-0 col-lg-1 col-lg-offset-2 inline-block text-center">
                    <img alt="Pay monthly" class="max-width-100" itemprop="contentUrl" src="/assets/uploads/pay-monthly.svg"/>
                </figure>
                <div class="inline-block col-xs-12 col-sm-6 col-md-4 text-center-xs">
                    <div class="fs-26 fs-xs-20 lato-bold padding-bottom-10 padding-top-10">Pay monthly</div>
                    <div class="fs-20 fs-xs-16 padding-bottom-40 padding-left-15 padding-right-15">You receive lifelong, prevention-oriented dental care against accessible monthly payments in Dentacoin cryptocurrency.</div>
                </div>
                <div class="inline-block col-xs-4 col-sm-1 col-md-3 hide-xs">
                    <div class="line"><div class="bullet"></div></div>
                </div>
            </div>
            <div class="row fs-0 flex no-flex-xs">
                <figure itemscope="" itemtype="http://schema.org/ImageObject" class="col-xs-12 col-sm-3 col-sm-offset-1 col-md-offset-0 col-lg-1 col-lg-offset-2 inline-block text-center">
                    <img alt="Get covered" class="max-width-100" itemprop="contentUrl" src="/assets/uploads/get-covered.svg"/>
                </figure>
                <div class="inline-block col-xs-12 col-sm-6 col-md-4 text-center-xs">
                    <div class="fs-26 fs-xs-20 lato-bold padding-bottom-10 padding-top-10">Get covered</div>
                    <div class="fs-20 fs-xs-16 padding-bottom-40 padding-left-15 padding-right-15">You go to 3 checkups/year, you use the Dentacare App to form and maintain oral hygiene habits and get all occurring treatments for free.</div>
                </div>
                <div class="inline-block col-xs-4 col-sm-1 col-md-3 hide-xs">
                    <div class="line"><div class="bullet"></div></div>
                </div>
            </div>
            <div class="row fs-0 flex no-flex-xs">
                <figure itemscope="" itemtype="http://schema.org/ImageObject" class="col-xs-12 col-sm-3 col-sm-offset-1 col-md-offset-0 col-lg-1 col-lg-offset-2 inline-block text-center">
                    <img alt="Take control" class="max-width-100" itemprop="contentUrl" src="/assets/uploads/take-control.svg"/>
                </figure>
                <div class="inline-block col-xs-12 col-sm-6 col-md-4 text-center-xs">
                    <div class="fs-26 fs-xs-20 lato-bold padding-bottom-10 padding-top-10">Take control</div>
                    <div class="fs-20 fs-xs-16 padding-bottom-40 padding-left-15 padding-right-15">You keep track of your transactions and follow your dentist’s tips from a fully automated online system.</div>
                </div>
                <div class="inline-block col-xs-4 col-sm-1 col-md-3 hide-xs">
                    <div class="line"><div class="bullet"></div></div>
                </div>
            </div>
        </div>
    </section>
    <section class="module section-ideal-use-cases padding-top-70 padding-top-xs-0 padding-bottom-100 padding-bottom-xs-50">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
                    <h2 class="text-center lato-bold fs-45 fs-xs-30 padding-bottom-xs-10 padding-bottom-80">IDEAL USE CASES</h2>
                </div>
            </div>
        </div>
        <div class="custom-container">
            <div class="custom-row fs-0 flex no-flex-xs">
                <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                    <img alt="Children" itemprop="contentUrl" src="/assets/uploads/no-private-insurance.jpg"/>
                </figure>
                <div class="line-and-bullet inline-block"><div class="line"><div class="bullet"></div></div></div>
                <div class="inline-block content">
                    <div class="wrapper">
                        <div class="lato-bold fs-30 fs-xs-20">No private insurance</div>
                        <div class="fs-20 fs-xs-16 padding-top-15 after-xs">If you cannot afford a private insurance, you can benefit from Dentacoin Assurance and get all your treatments covered against low monthly payments.</div>
                    </div>
                </div>
            </div>
            <div class="custom-row fs-0 flex no-flex-xs">
                <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block order-2">
                    <img alt="Children" itemprop="contentUrl" src="/assets/uploads/children.jpg"/>
                </figure>
                <div class="inline-block content order-1">
                    <div class="wrapper">
                        <div class="lato-bold fs-30 fs-xs-20">Kids Care</div>
                        <div class="fs-20 fs-xs-16 padding-top-15 after-xs">Establishing good oral hygiene habits early on helps encourage lifetime patterns. Sign an Assurance contract for your children and protect their teeth.</div>
                    </div>
                </div>
                <div class="line-and-bullet inline-block"><div class="line"><div class="bullet"></div></div></div>
            </div>
            <div class="custom-row fs-0 flex no-flex-xs">
                <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                    <img alt="Children" itemprop="contentUrl" src="/assets/uploads/post-treatment.jpg"/>
                </figure>
                <div class="line-and-bullet inline-block"><div class="line"><div class="bullet"></div></div></div>
                <div class="inline-block content">
                    <div class="wrapper">
                        <div class="lato-bold fs-30 fs-xs-20">Post-treatment</div>
                        <div class="fs-20 fs-xs-16 padding-top-15 after-xs">Ask your dentist to enroll Dentacoin Assurance and take advantage of a lifelong guarantee plan. No intermediaries. Clear rules.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if(!empty($clinics))
        <section class="beige-background padding-top-100 padding-top-xs-30 padding-bottom-100 padding-bottom-xs-50" id="find-your-dentist">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <h2 class="lato-bold fs-45 fs-xs-30 fs-xxs-28 padding-bottom-50">FIND YOUR DENTIST</h2>
                    </div>
                    <div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 text-center">
                        <select class="combobox custom-input green-arrow-background">
                            <option></option>
                            @foreach($clinics as $clinic)
                                <option value="{{$clinic->id}}">{{$clinic->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <section class="cant-find-your-dentist-section">
        <picture class="two-dentists" itemscope="" itemtype="http://schema.org/ImageObject">
            <source media="(max-width: 768px)" srcset="/assets/uploads/dentist-800-not-loged-in-patients-page.png" />
            <source media="(max-width: 1400px)" srcset="/assets/uploads/dentist-1366-not-loged-in-patients-page.png" />
            <img alt="Dentist" itemprop="contentUrl" src="/assets/uploads/dentist-1920-not-loged-in-patients-page.png"/>
        </picture>
        <div class="container absolute-container">
            <div class="row">
                <div class="col-xs-7 col-xs-offset-5 col-sm-8 padding-left-sm-40 col-sm-offset-4 col-md-6 col-md-offset-5 text-center">
                    <h3 class="fs-40 lato-bold black-color padding-bottom-20 padding-bottom-sm-10 padding-bottom-xs-10 fs-sm-30 fs-xs-28 fs-xxs-18">CAN’T FIND YOUR DENTIST?</h3>
                    <div class="fs-30 line-height-35 line-height-sm-25 lato-regular fs-sm-25 fs-xs-20 line-height-xs-25 fs-xxs-16 line-height-xxs-18">Send them an invitation to <span class="blue-green-color">earn 20,000 DCN</span> and help us change dentistry to the better!</div>
                </div>
            </div>
        </div>
        <div class="container second-absolute-container">
            <div class="row">
                <div class="col-xs-12 col-sm-5 col-sm-offset-6 col-md-6 col-md-offset-6 text-center">
                    <h3 class="lato-regular fs-30 fs-xs-22 fs-sm-20 white-color padding-bottom-30 padding-bottom-xs-10 padding-bottom-sm-10 text-center">SIGN UP TO GET STARTED</h3>
                    <div class="padding-bottom-10">
                        <a href="javascript:void(0)" class="facebook-custom-btn social-login-btn calibri-regular fs-20" data-url="//api.dentacoin.com/api/register" data-platform="assurance" data-type="patient">with Facebook</a>
                    </div>
                    <div>
                        <a href="javascript:void(0)"  class="civic-custom-btn social-login-btn calibri-regular fs-20" data-url="//api.dentacoin.com/api/register" data-platform="assurance" data-type="patient">with Civic</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

