@extends("layout")
@section("content")
    <section class="padding-top-110 padding-top-xs-30 padding-bottom-40 padding-bottom-xs-20 beige-background">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h1 class="text-center fs-45 fs-xs-30 lato-bold">Support Guide</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="padding-bottom-50 padding-bottom-xs-10 beige-background section-support-guide-slider-section">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-10 col-md-offset-1">
                    <div class="support-guide-slider second-type-arrows">
                        @foreach($posts as $post)
                            <div class="single-slide">
                                @if(!empty($post->media))
                                    <figure itemscope="" itemtype="http://schema.org/ImageObject">
                                        <img alt="{{$post->media->alt}}" itemprop="contentUrl" src="/assets/uploads/{{$post->media->name}}"/>
                                    </figure>
                                @endif
                                <div class="number">
                                    @php($num = $post->order_id + 1)
                                    @if($num < 10)
                                        0{{$num}}
                                    @else
                                        {{$num}}
                                    @endif
                                </div>
                                <div class="description">
                                    <div class="custom-triangle"></div>
                                    <div class="custom-circle"></div>
                                    <div class="wrapper">{!! $post->text !!}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="padding-top-50 padding-top-xs-30 padding-bottom-50 section-support-guide-list">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="text-center fs-45 fs-xs-30 lato-bold">Frequently Asked Questions</h2>
                    <section class="section-row">
                        <div class="fs-30 fs-xs-20 section-title padding-top-30 padding-bottom-20 lato-bold">GENERAL</div>
                        <ul>
                            <li>
                                <a href="javascript:void(0);" class="fs-20 fs-xs-18 question"><span class="lato-black fs-20">01</span>What is Dentacoin Assurance?</a>
                                <div class="fs-18 fs-xs-16 calibri-light padding-bottom-30 padding-top-10 padding-left-20 padding-right-20 question-content">
                                    Dentacoin Assurance is the first blockchain-based dental assurance program that shifts the focus from treatment to prevention and brings the financial interests of patients and dentists into complete alignment without any intermediaries.
                                    <br><br>
                                    Patients are entitled to lifelong, preventive dental care against affordable monthly premiums in Dentacoin (DCN) currency.
                                    <br><br>
                                    Dentists receive a stable basic income while simultaneously establish strong, lasting bonds with their patients.
                                </div>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="fs-20 fs-xs-18 question"><span class="lato-black fs-20">02</span>How does it work?</a>
                                <div class="fs-18 fs-xs-16 calibri-light padding-bottom-30 padding-top-10 padding-left-20 padding-right-20 question-content">
                                    Dentists register on assurance.dentacoin.com and wait for approval. Once approved, they can create a contract sample and send it to their Patient. The contract sample includes the chosen services to cover, as well as a monthly premium proposal. The Dentist recommends the number of required check-ups and tooth cleanings per year (those prophylaxis visits are always covered by Dentacoin Assurance).
                                    <br><br>
                                    Patients receive a contract sample via email, register on assurance.dentacoin.com and agree (or negotiate) on the terms proposed. Once agreed and signed by the Patient, a legally binding Assurance contract in .pdf and a public proof of parts thereof is automatically created.
                                    <br><br>
                                    Upon the first payment of the Patient, a smart contract is generated as well to handle automatically all future monthly payments in Dentacoin (DCN).
                                    <br><br>
                                    Patients and Dentists benefit from an intelligent notification and payment system and can step into a smart agreement without any intermediates.
                                    <br><br>
                                    <a href="//dentacoin.com/assets/uploads/dentacoin-assurance-process.pdf" target="_blank">How it Works: Process Visualization</a>
                                </div>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="fs-20 fs-xs-18 question"><span class="lato-black fs-20">03</span>Why should a Dentist give up regular insurances and choose Dentacoin Assurance?</a>
                                <div class="fs-18 fs-xs-16 calibri-light padding-bottom-30 padding-top-10 padding-left-20 padding-right-20 question-content">
                                    There is no need of that. Dentacoin Assurance does not compete with other public and private health insurance plans. Dentists are free to decide which insurance/ assurance programs to work with. They can either offer Dentacoin Assurance as a complementary plan which will cover/reduce their patients’ out-of-pocket costs or as a stand-alone plan in case other options are not available.
                                    <br><br>
                                    However, Dentacoin Assurance provides various additional benefits:<br>
                                    <ul>
                                        <li>Focus on prevention and lasting Patient-Dentist relations;</li>
                                        <li>Simple plans, easy to understand and follow conditions;</li>
                                        <li>No intermediates - direct agreements between a Patient and a Dentist;</li>
                                        <li>Affordable premiums;</li>
                                        <li>No long approval procedures;</li>
                                        <li>Fully automated payment and notification system.</li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="fs-20 fs-xs-18 question"><span class="lato-black fs-20">04</span>Are all Patients suitable for Dentacoin Assurance?</a>
                                <div class="fs-18 fs-xs-16 calibri-light padding-bottom-30 padding-top-10 padding-left-20 padding-right-20 question-content">
                                    There are no general limitations. However, there are ideal use cases such as:<br>
                                    <ul>
                                        <li>Children - to ensure proper hygiene habits, optimal dental health and long-term relations;</li>
                                        <li>Post-treatment - in this case it’s offered as a lifelong guarantee plan;</li>
                                        <li>No private insurance - due to the affordable monthly premiums.</li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </section>
                    <section class="section-row">
                        <div class="fs-30 fs-xs-20 section-title padding-top-30 padding-bottom-20 lato-bold">CONTRACT CONDITIONS</div>
                        <ul>
                            <li>
                                <a href="javascript:void(0);" class="fs-20 fs-xs-18 question"><span class="lato-black fs-20">01</span>What are the obligations of Dentists and Patients?</a>
                                <div class="fs-18 fs-xs-16 calibri-light padding-bottom-30 padding-top-10 padding-left-20 padding-right-20 question-content">Dentists provide Patients with individualized advice on how to take the best possible care of their teeth, incl. how to form and maintain general dental care habits (through the Dentacare Health Training App), and how often to come for check-up and tooth cleaning.
                                    <br><br>
                                    Patients are encouraged (and incentivized in DCN) to take responsibility for their own dental health by adopting these recommendations into their daily lives and consistently following them.
                                    <br><br>
                                    Dentists are obliged to take care of them, only if Patients adhere to these recommendations and pay their monthly premium on a regular base. In this case, Dentacoin Assurance covers all applicable prevention/ treatment costs for the services included in the Assurance plan.
                                    <br><br>
                                    If the hard conditions in the contract are violated, e.g. a monthly premium is not paid or a patient hasn’t come for check-ups and tooth cleanings, the contract is automatically terminated.</div>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="fs-20 fs-xs-18 question"><span class="lato-black fs-20">02</span>What services are covered by Dentacoin Assurance?</a>
                                <div class="fs-18 fs-xs-16 calibri-light padding-bottom-30 padding-top-10 padding-left-20 padding-right-20 question-content">Every dentist alone decides which category/categories of services to cover. The recommended number of check-ups/ tooth cleanings are always included in the package.
                                    <br><br>
                                    General Dentistry:
                                    <ul>
                                        <li>Fillings</li>
                                        <li>Caries infiltration</li>
                                        <li>Dental sealants for children</li>
                                        <li>Root canal treatment</li>
                                        <li>Periodontal treatment</li>
                                        <li>Tooth extraction</li>
                                    </ul>
                                    <br>
                                    Cosmetic Dentistry:
                                    <ul>
                                        <li>Composite bonding</li>
                                        <li>Porcelain veneers <i>(material & laboratory costs - not covered)</i></li>
                                        <li>Composite veneers <i>(material & laboratory costs - not covered)</i></li>
                                        <li>Inlays & onlays <i>(material & laboratory costs - not covered)</i></li>
                                        <li>Crowns <i>(material & laboratory costs - not covered)</i></li>
                                        <li>Bridges <i>(material & laboratory costs - not covered)</i></li>
                                        <li>Dentures <i>(material & laboratory costs - not covered)</i></li>
                                    </ul>
                                    <br>
                                    Implant Dentistry:
                                    <ul>
                                        <li>Implant placement <i>(implants and abutments - not covered)</i></li>
                                        <li>Bone augmentation <i>(bone replacement material - not covered)</i></li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="fs-20 fs-xs-18 question"><span class="lato-black fs-20">03</span>How can a Dentacoin Assurance contract be ended?</a>
                                <div class="fs-18 fs-xs-16 calibri-light padding-bottom-30 padding-top-10 padding-left-20 padding-right-20 question-content">
                                    The contract is automatically cancelled if a payment is not made on time. The Dentist has the right to cancel the contract if the Patient does not come for the recommended number of check-ups and tooth cleanings. The Patient can also cancel the contract anytime if s/he is not satisfied with the service provided.
                                </div>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="fs-20 fs-xs-18 question"><span class="lato-black fs-20">04</span>How does Dentacoin Foundation regulate the agreements? </a>
                                <div class="fs-18 fs-xs-16 calibri-light padding-bottom-30 padding-top-10 padding-left-20 padding-right-20 question-content">
                                    We don’t. Dentacoin Assurance is based on direct contracts between Patients and Dentists. Dentacoin Foundation just provides the infrastructure for this to happen more easily.
                                </div>
                            </li>
                        </ul>
                    </section>
                    <section class="section-row">
                        <div class="fs-30 fs-xs-20 section-title padding-top-30 padding-bottom-20 lato-bold">DENTACOIN PAYMENTS</div>
                        <ul>
                            <li>
                                <a href="javascript:void(0);" class="fs-20 fs-xs-18 question"><span class="lato-black fs-20">01</span>What is the monthly assurance premium?</a>
                                <div class="fs-18 fs-xs-16 calibri-light padding-bottom-30 padding-top-10 padding-left-20 padding-right-20 question-content">
                                    Dentacoin Foundation shows average monthly premiums to Dentists when creating a new contract. Those vary greatly between USD 2.5 and USD 50 in DCN depending on location, average prices for dental services, and the scope of services covered by the Dentacoin Assurance contract. However, the Dentist alone decides what monthly premium to offer.
                                </div>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="fs-20 fs-xs-18 question"><span class="lato-black fs-20">02</span>How is the assurance premium paid?</a>
                                <div class="fs-18 fs-xs-16 calibri-light padding-bottom-30 padding-top-10 padding-left-20 padding-right-20 question-content">
                                    All assurance premiums are paid in Dentacoin (DCN). Patients should just ensure that they have the needed amount of DCN in their wallets on the payment due date. The payments are then processed automatically.
                                </div>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="fs-20 fs-xs-18 question"><span class="lato-black fs-20">03</span>What is Dentacoin (DCN)?</a>
                                <div class="fs-18 fs-xs-16 calibri-light padding-bottom-30 padding-top-10 padding-left-20 padding-right-20 question-content">
                                    Dentacoin (DCN) is a cryptocurrency you can use as a means of payment for dental services and products <a href="//dentacoin.com/partner-network/" target="_blank">within the Dentacoin Network</a> . Dentacoin can be stored in a crypto wallet for future value multiplication or exchanged to other (crypto and standard) currencies on multiple international exchange platforms.
                                    <br><br>
                                    Dentacoin has also developed several other Blockchain-based applications, incentivizing users for their contribution. <a href="//dentacoin.com/" target="_blank">Learn more...</a>
                                </div>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="fs-20 fs-xs-18 question"><span class="lato-black fs-20">04</span>I don’t have a Dentacoin wallet yet. How to create one?</a>
                                <div class="fs-18 fs-xs-16 calibri-light padding-bottom-30 padding-top-10 padding-left-20 padding-right-20 question-content">
                                    Please follow our <a href="//dentacoin.com/how-to-create-wallet" target="_blank">Wallet Instructions</a>.
                                </div>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="fs-20 fs-xs-18 question"><span class="lato-black fs-20">05</span>How can I get Dentacoin (DCN)?</a>
                                <div class="fs-18 fs-xs-16 calibri-light padding-bottom-30 padding-top-10 padding-left-20 padding-right-20 question-content">
                                    You can always easily <a href="//wallet.dentacoin.com/buy" target="_blank">purchase DCN with a bank card</a>.
                                    <br><br>
                                    You can also earn Dentacoin (DCN) currency by using the Dentacoin Tools:
                                    <ul>
                                        <li><a href="//reviews.dentacoin.com/" target="_blank">Trusted Reviews</a>: Dentists invite Patients to send them valuable, detailed feedback. Both parties are rewarded in DCN. </li>
                                        <li><a href="//dentavox.dentacoin.com/" target="_blank">DentaVox</a>: Users take surveys on various dental health topics and are incentivized in DCN. Referrals are also rewarded.</li>
                                        <li><a href="//dentacare.dentacoin.com/" target="_blank">Dentacare</a>: Users are guided and incentivizes towards maintaining proper oral hygiene for at least 90 days.</li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="fs-20 fs-xs-18 question"><span class="lato-black fs-20">06</span>Is Dentacoin (DCN) currency only used to pay for assurance premiums?</a>
                                <div class="fs-18 fs-xs-16 calibri-light padding-bottom-30 padding-top-10 padding-left-20 padding-right-20 question-content">
                                    No. Except for being embedded as a reward in all Dentacoin tools described above, Dentacoin (DCN) is also accepted as a means of payment by dental clinics, suppliers, manufacturers, labs, service providers in over 20 countries globally. <a href="//dentacoin.com/partner-network" target="_blank">Dentacoin Partner Network</a>
                                </div>
                            </li>
                        </ul>
                    </section>
                </div>
            </div>
        </div>
    </section>
    <section class="blue-green-color-background padding-top-15 padding-bottom-20">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center white-color fs-45 lato-bold">WIN - WIN</div>
            </div>
        </div>
    </section>
    <section class="padding-top-50 padding-bottom-50">
        <div class="container">
            <div class="row">
                <figure itemscope="" itemtype="http://schema.org/ImageObject" class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
                    <img alt="Faq bottom image" itemprop="contentUrl" src="/assets/uploads/faq-bottom-img.svg"/>
                </figure>
            </div>
        </div>
    </section>
@endsection