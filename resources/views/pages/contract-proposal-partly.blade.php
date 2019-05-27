@extends("layout")
@section("content")
    @php($dentist = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->dentist_id))
    @php($general_dentistry = unserialize($contract->general_dentistry))
    <section class="padding-top-100 padding-bottom-50 contract-proposal module">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h1 class="lato-bold fs-45 text-center padding-bottom-50 blue-green-color">REVIEW ASSURANCE CONTRACT</h1>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                    <div class="wrapper padding-top-70 padding-bottom-60">
                        <div class="top-right-page-alike"></div>
                        <h3 class="calibri-bold fs-30 dark-color">DENTIST DETAILS</h3>
                        <div class="step-fields module padding-top-20">
                            <div class="single-row fs-0">
                                <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Name:</label>
                                <div class="right-extra-field calibri-bold fs-25 dark-color inline-block">{{$dentist->name}}</div>
                            </div>
                            <div class="single-row fs-0">
                                <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Postal Address:</label>
                                <div class="right-extra-field calibri-regular fs-18 dark-color inline-block">{{$dentist->address}}</div>
                            </div>
                            <div class="single-row fs-0">
                                <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Phone:</label>
                                <div class="right-extra-field calibri-regular fs-18 dark-color inline-block">{{$dentist->phone}}</div>
                            </div>
                            <div class="single-row fs-0">
                                <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Website:</label>
                                <div class="right-extra-field calibri-regular fs-18 dark-color inline-block">
                                    <a href="{{$dentist->website}}" target="_blank">{{$dentist->website}}</a>
                                </div>
                            </div>
                            <h3 class="calibri-bold fs-30 dark-color padding-top-70">CONTRACT CONDITIONS</h3>
                            <div class="single-row fs-0 padding-top-10">
                                <label class="calibri-light light-gray-color fs-16 padding-right-15 padding-top-0 margin-bottom-0 inline-block">Services Covered:</label>
                                <div class="right-extra-field checkboxes-right-container calibri-regular fs-18 dark-color inline-block">
                                    @if(in_array('param_gd', $general_dentistry))
                                        <div class="pretty margin-bottom-5 p-svg p-curve on-white-background">
                                            <input type="checkbox" disabled checked/>
                                            <div class="state p-success">
                                                <!-- svg path -->
                                                <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                    <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                </svg>
                                                <label class="fs-18 calibri-light">General Dentistry</label>
                                            </div>
                                        </div>
                                    @endif
                                    @if(in_array('param_cd', $general_dentistry))
                                        <div class="pretty margin-bottom-5 p-svg p-curve on-white-background">
                                            <input type="checkbox" disabled checked/>
                                            <div class="state p-success">
                                                <!-- svg path -->
                                                <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                    <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                </svg>
                                                <label class="fs-18 calibri-light">Cosmetic Dentistry</label>
                                            </div>
                                        </div>
                                    @endif
                                    @if(in_array('param_id', $general_dentistry))
                                        <div class="pretty margin-bottom-5 p-svg p-curve on-white-background">
                                            <input type="checkbox" disabled checked/>
                                            <div class="state p-success">
                                                <!-- svg path -->
                                                <svg class="svg svg-icon" viewBox="0 0 20 20">
                                                    <path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white;fill:white;"></path>
                                                </svg>
                                                <label class="fs-18 calibri-light">Implant Dentistry</label>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="single-row fs-0">
                                <label class="calibri-light inline-block light-gray-color fs-16 padding-right-15 margin-bottom-0">Monthly Premium:</label>
                                <div class="right-extra-field calibri-bold fs-25 dark-color inline-block">{{$contract->monthly_premium}} USD</div>
                            </div>
                            <div class="blurred-contract-terms-container">
                                <figure itemscope="" itemtype="http://schema.org/ImageObject" class="padding-top-40 padding-bottom-40">
                                    <img alt="Blurred contract terms" itemprop="contentUrl" src="/assets/uploads/blurred-contract-terms.png"/>
                                </figure>
                                <button class="show-login-signin reload-here white-blue-green-btn" data-route="{{}}" data-slug="">SIGN UP TO SEE DETAILS</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
