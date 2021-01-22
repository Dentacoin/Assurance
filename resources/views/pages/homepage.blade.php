@extends("layout")
@section("content")
    <section class="module intro-section">
        <picture itemscope="" itemtype="http://schema.org/ImageObject">
            <img alt="Two dentists" itemprop="contentUrl" data-defer-src="/assets/uploads/assurance-home-img.jpg"/>
        </picture>
        <div class="absolute-container container">
            <div class="row">
                <div class="col-xs-12 col-md-6 col-md-offset-6 text-center-xs text-center-sm padding-bottom-xs-40 padding-top-xs-40 padding-bottom-sm-60 padding-top-sm-60">
                    <h1 class="lato-black fs-40 fs-xs-30">DENTACOIN ASSURANCE</h1>
                    <div class="lato-regular fs-40 fs-xs-30 line-height-45 padding-top-20 padding-bottom-30">Maximize Your Income and Build Lifelong Patient Relations!</div>
                    <div>
                        <a href="javascript:void(0)" class="white-blue-green-btn open-calculator">CALCULATE YOUR INCOME INCREASE</a>
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
                        <img alt="I'm dentist background" itemprop="contentUrl" data-defer-src="/assets/uploads/dentist-big.jpg"/>
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
                        <img alt="I'm patient background" itemprop="contentUrl" data-defer-src="/assets/uploads/patient-big.jpg"/>
                    </picture>
                    <span class="hidden-container">
                        <a href="/patient" class="white-transparent-btn">I'm a Patient</a>
                        <div class="hidden-label white-transparent-btn">I'm a Patient</div>
                    </span>
                </div>
            </div>
        </div>
    </section>
    <section class="module easy-fast-steps-section padding-top-80 padding-bottom-50 padding-bottom-xs-30">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="text-center lato-bold fs-45">EASY. FAST. EFFICIENT.</h2>
                    <div class="text-center lato-regular fs-30 padding-bottom-50 padding-top-10">Deliver Better Care & Get Paid for Prevention</div>
                </div>
            </div>
            <div class="row fs-0 flex no-flex-xs">
                <figure itemscope="" itemtype="http://schema.org/ImageObject" class="col-xs-12 col-sm-3 col-sm-offset-1 col-md-offset-0 col-lg-1 col-lg-offset-2 inline-block text-center">
                    <img alt="Sign up icon" class="max-width-100" itemprop="contentUrl" data-defer-src="/assets/uploads/sign-up.svg"/>
                </figure>
                <div class="inline-block col-xs-12 col-sm-6 col-md-4 text-center-xs">
                    <div class="fs-26 lato-bold padding-bottom-10">Sign Up</div>
                    <div class="fs-20 padding-bottom-40">You enroll Dentacoin Assurance for free and sign a contract with a patient.</div>
                </div>
                <div class="inline-block col-xs-4 col-sm-1 col-md-3 hide-xs">
                    <div class="line"><div class="bullet"></div></div>
                </div>
            </div>
            <div class="row fs-0 flex no-flex-xs">
                <figure itemscope="" itemtype="http://schema.org/ImageObject" class="col-xs-12 col-sm-3 col-sm-offset-1 col-md-offset-0 col-lg-1 col-lg-offset-2 inline-block text-center">
                    <img alt="Get paid icon" class="max-width-100" itemprop="contentUrl" data-defer-src="/assets/uploads/get-paid.svg"/>
                </figure>
                <div class="inline-block col-xs-12 col-sm-6 col-md-4 text-center-xs">
                    <div class="fs-26 lato-bold padding-bottom-10">Get Paid</div>
                    <div class="fs-20 padding-bottom-40">You receive the agreed monthly premium in Dentacoin cryptocurrency.</div>
                </div>
                <div class="inline-block col-xs-4 col-sm-1 col-md-3 hide-xs">
                    <div class="line"><div class="bullet"></div></div>
                </div>
            </div>
            <div class="row fs-0 flex no-flex-xs">
                <figure itemscope="" itemtype="http://schema.org/ImageObject" class="col-xs-12 col-sm-3 col-sm-offset-1 col-md-offset-0 col-lg-1 col-lg-offset-2 inline-block text-center">
                    <img alt="Prevent icon" class="max-width-100" itemprop="contentUrl" data-defer-src="/assets/uploads/prevent.svg"/>
                </figure>
                <div class="inline-block col-xs-12 col-sm-6 col-md-4 text-center-xs">
                    <div class="fs-26 lato-bold padding-bottom-10">Prevent</div>
                    <div class="fs-20 padding-bottom-40">You provide 3 check-ups/year, teeth cleanings and occuring treatments.</div>
                </div>
                <div class="inline-block col-xs-4 col-sm-1 col-md-3 hide-xs">
                    <div class="line"><div class="bullet"></div></div>
                </div>
            </div>
            <div class="row fs-0 flex no-flex-xs">
                <figure itemscope="" itemtype="http://schema.org/ImageObject" class="col-xs-12 col-sm-3 col-sm-offset-1 col-md-offset-0 col-lg-1 col-lg-offset-2 inline-block text-center">
                    <img alt="Automate icon" class="max-width-100" itemprop="contentUrl" data-defer-src="/assets/uploads/automate.svg"/>
                </figure>
                <div class="inline-block col-xs-12 col-sm-6 col-md-4 text-center-xs">
                    <div class="fs-26 lato-bold padding-bottom-10">Automate</div>
                    <div class="fs-20 padding-bottom-40">You benefit from a fully automated payment and notification system.</div>
                </div>
                <div class="inline-block col-xs-4 col-sm-1 col-md-3 hide-xs">
                    <div class="line"><div class="bullet"></div></div>
                </div>
            </div>
            <div class="row fs-0 flex no-flex-xs">
                <figure itemscope="" itemtype="http://schema.org/ImageObject" class="col-xs-12 col-sm-3 col-sm-offset-1 col-md-offset-0 col-lg-1 col-lg-offset-2 inline-block text-center">
                    <img alt="Maximize icon" class="max-width-100" itemprop="contentUrl" data-defer-src="/assets/uploads/maximize.svg"/>
                </figure>
                <div class="inline-block col-xs-12 col-sm-6 col-md-4 text-center-xs">
                    <div class="fs-26 lato-bold padding-bottom-10">Maximize</div>
                    <div class="fs-20 padding-bottom-40">You maximize your income by keep accepting Public/ Private Insurances.</div>
                </div>
                <div class="inline-block col-xs-4 col-sm-1 col-md-3 hide-xs">
                    <div class="line"><div class="bullet"></div></div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 padding-top-50 padding-top-xs-20 text-center"><a href="//dentacoin.com/assets/uploads/dentacoin-assurance-process.pdf" class="white-blue-green-btn track-event-download-brochure" target="_blank">DOWNLOAD BROCHURE</a></div>
            </div>
        </div>
    </section>
    <section class="module section-ideal-use-cases background padding-top-200 padding-top-xs-80 padding-bottom-100">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
                    <h2 class="text-center lato-bold fs-45 fs-xs-30">DUAL COVERAGE</h2>
                    <div class="fs-20 padding-top-40 padding-bottom-200 padding-top-xs-20 padding-bottom-xs-60 fs-xs-16">Dentacoin Assurance program does not compete with other public and private health insurance plans. You are free to decide which insurance / assurance programs to work with. You can either offer Dentacoin Assurance as a complementary plan which will cover/reduce your Patients’ out-of-pocket costs or as a stand-alone plan in case other options are not available.</div>
                    <h2 class="text-center lato-bold fs-45 fs-xs-30 padding-bottom-xs-10 padding-bottom-80">IDEAL USE CASES</h2>
                </div>
            </div>
        </div>
        <div class="custom-container">
            <div class="custom-row fs-0 flex no-flex-xs">
                <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block">
                    <img alt="Children" itemprop="contentUrl" data-defer-src="/assets/uploads/children.jpg"/>
                </figure>
                <div class="line-and-bullet inline-block"><div class="line"><div class="bullet"></div></div></div>
                <div class="inline-block content">
                    <div class="wrapper">
                        <div class="lato-bold fs-30 fs-xs-20">Children</div>
                        <div class="fs-20 fs-xs-16 padding-top-15 after-xs">Proper oral hygiene since young age ensures optimum dental health. Offer parents to sign an Assurance contract for their children and build lifelong patient relations.</div>
                    </div>
                </div>
            </div>
            <div class="custom-row fs-0 flex no-flex-xs">
                <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block order-2">
                    <img alt="Post-treatment" itemprop="contentUrl" data-defer-src="/assets/uploads/post-treatment.jpg"/>
                </figure>
                <div class="inline-block content order-1">
                    <div class="wrapper">
                        <div class="lato-bold fs-30 fs-xs-20">Post-treatment</div>
                        <div class="fs-20 fs-xs-16 padding-top-15 after-xs">After finalizing a treatment, offer your Patients to enroll Dentacoin Assurance as a lifelong guarantee plan.</div>
                    </div>
                </div>
                <div class="line-and-bullet inline-block"><div class="line"><div class="bullet"></div></div></div>
            </div>
        </div>
    </section>
    @if(!empty($testimonials))
        <section class="testimonials-section padding-bottom-xs-30">
            <div class="container-fluid">
                <div class="row fs-0">
                    <picture class="inline-block col-sm-4 col-lg-offset-1 hide-xs" itemscope="" itemtype="http://schema.org/ImageObject">
                        <source media="(max-width: 768px)" srcset="/assets/uploads/dentist-testimonial-section-small.png" />
                        <img alt="Two dentists" itemprop="contentUrl" data-defer-src="/assets/uploads/dentist-testimonial-section-big.png"/>
                    </picture>
                    <div class="col-xs-12 col-sm-8 col-lg-6 inline-block">
                        <div class="testimonials-slider">
                            @foreach($testimonials as $testimonial)
                                <div class="single-testimonial">
                                    <div class="description">{!! $testimonial->text !!}</div>
                                    <div class="img-title-job fs-0">
                                        <figure itemscope="" itemtype="http://schema.org/ImageObject" class="inline-block-top">
                                            @if(empty($testimonial->media_name))
                                                <img data-defer-src="/assets/images/avatar-icon.svg" alt="Avatar icon" itemprop="contentUrl"/>
                                            @else
                                                <img data-defer-src="{{$testimonial->media_name}}" alt="{{$testimonial->media_alt}}" itemprop="contentUrl"/>
                                            @endif
                                        </figure>
                                        <div class="title-job inline-block-top">
                                            <div class="title color-black">{{explode(',', $testimonial->name_job)[0]}}</div>
                                            @if(!empty(explode(',', $testimonial->name_job)[1]))
                                                <div class="job">{{explode(',', $testimonial->name_job)[1]}}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <section class="start-smart-today-section beige-background padding-top-80 padding-bottom-80 padding-top-xs-40 padding-bottom-xs-40">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <h2 class="lato-bold fs-45 fs-xxs-26 fs-xs-30 dark-color">START <span class="blue-green-color">(SMART)</span> TODAY</h2>
                    <div class="lato-regular fs-30 fs-xs-20 padding-top-xs-10 padding-bottom-xs-30 padding-top-20 padding-bottom-50">Register for Free & Get Full Onboarding Support</div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 text-center">
                    <a href="javascript:void(0)" class="white-blue-green-btn min-width-220 open-dentacoin-gateway dentist-register show-signing">SIGN UP</a>
                </div>
            </div>
        </div>
    </section>
@endsection