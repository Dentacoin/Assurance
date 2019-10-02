@extends("layout")
@section("content")
    <section class="dentist-no-contracts-section padding-top-120 padding-top-xs-30 padding-top-sm-30">
        <div class="absolute-white-background-line"></div>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3 padding-bottom-50 padding-bottom-xs-30">
                    <h1 class="lato-bold fs-45 fs-xs-30 text-center padding-left-30 padding-right-30">Deliver Better Care & Get Paid for Prevention</h1>
                </div>
                <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
                    <div class="wrapper">
                        <div class="container-fluid">
                            <div class="row fs-0">
                                <div class="col-xs-12 col-sm-6 inline-block">
                                    <div class="single-container text-center">
                                        <a href="/support-guide" target="_blank" class="display-block">
                                            <figure itemscope="" itemtype="http://schema.org/ImageObject">
                                                <img alt="" class="max-width-90" itemprop="contentUrl" src="/assets/uploads/support-guide.svg"/>
                                                <figcaption class="fs-20 padding-top-15">Check Your Support Guide</figcaption>
                                            </figure>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 inline-block">
                                    <div class="single-container text-center">
                                        <a href="/assurance-demo" target="_blank" class="display-block">
                                            <figure itemscope="" itemtype="http://schema.org/ImageObject">
                                                <img alt="" class="max-width-90" itemprop="contentUrl" src="/assets/uploads/assurance-demo.svg"/>
                                                <figcaption class="fs-20 padding-top-15">Go Through Assurance Demo</figcaption>
                                            </figure>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 inline-block">
                                    <div class="single-container text-center">
                                        <a href="//dentacoin.com/how-to-create-wallet" target="_blank" class="display-block">
                                            <figure itemscope="" itemtype="http://schema.org/ImageObject">
                                                <img alt="" class="max-width-90" itemprop="contentUrl" src="/assets/uploads/wallet-instructions.svg"/>
                                                <figcaption class="fs-20 padding-top-15">Open Your Dentacoin Wallet</figcaption>
                                            </figure>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-6 inline-block">
                                    <div class="single-container text-center">
                                        <a href="{{BASE_URL}}dentist/create-contract" class="display-block track-event-dentist-sign-first-contract">
                                            <figure itemscope="" itemtype="http://schema.org/ImageObject">
                                                <img alt="" class="max-width-90" itemprop="contentUrl" src="/assets/uploads/sign-your-first-contract.svg"/>
                                                <figcaption class="fs-20 padding-top-15">Create Your First Contract</figcaption>
                                            </figure>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

