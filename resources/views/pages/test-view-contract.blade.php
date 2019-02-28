<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Calibri;
        }
        .wrapper {
            width: 1000px;
            margin: 0 auto;
            padding-bottom: 100px;
        }
        .main-title {
            color: #126585;
            font-size: 30px;
            text-align: center;
            font-weight: bold;
            padding-top: 20px;
        }
        .section-title {
            border-bottom: 1px dashed black;
            padding-bottom: 10px;
            font-size: 30px;
            font-weight: bold;
            color: #212121;
            padding-top: 50px;
        }
        .row {
            border-bottom: 1px solid #888888;
            font-size: 0;
            padding-top: 20px;
            padding-bottom: 20px;
        }
        .inline-block {
            display: inline-block;
            vertical-align: middle;
        }
        label {
            width: 40%;
            font-size: 16px;
            color: #888888;
            padding-right: 15px;
        }
        .right-field {
            color: #212121;
            font-size: 18px;
        }
        .right-field.bolded {
            font-weight: bold;
            font-size: 25px;
        }
        a {
            color: #337ab7;
        }
        .svg-row {
            padding-bottom: 15px;
        }
        svg {
            width: 20px;
            height: 20px;
            vertical-align: middle;
            margin-right: 5px;
        }
        .signs-container .doctor {
            width: 300px;
            float: left;
        }
        .signs-container .patient {
            width: 300px;
            float: right;
        }
        img {
            width: 100%;
        }
        .signs-container .name {
            text-align: right;
            font-style: italic;
            font-size: 16px;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
@php($dentist = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->dentist_id))
@php($patient = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->patient_id))
@php($services = unserialize($contract->general_dentistry))
    <div>
        <div class="wrapper">
            <div class="main-title">ASSURANCE CONTRACT</div>
            <div class="section-title">DENTIST DETAILS</div>
            <div class="row">
                <label class="inline-block">Name:</label>
                <div class="right-field bolded inline-block">{{$dentist->name}}</div>
            </div>
            <div class="row">
                <label class="inline-block">Professional / Company Registration Number:</label>
                <div class="right-field inline-block">{{$contract->professional_company_number}}</div>
            </div>
            <div class="row">
                <label class="inline-block">Country:</label>
                <div class="right-field inline-block">{{$contract->professional_company_number}}</div>
            </div>
            <div class="row">
                <label class="inline-block">Phone:</label>
                <div class="right-field inline-block">{{$dentist->phone}}</div>
            </div>
            <div class="row">
                <label class="inline-block">Website:</label>
                <div class="right-field inline-block">
                    <a href="{{$dentist->website}}" target="_blank">{{$dentist->website}}</a>
                </div>
            </div>
            <div class="row">
                <label class="inline-block">Wallet Address:</label>
                <div class="right-field bolded inline-block">
                    <a href="http://etherscan.io/address/{{$dentist->dcn_address}}" target="_blank">{{$dentist->dcn_address}}</a>
                </div>
            </div>
            <div class="section-title">PATIENT DETAILS</div>
            <div class="row">
                <label class="inline-block">Name:</label>
                <div class="right-field bolded inline-block">{{$patient->name}}</div>
            </div>
            <div class="row">
                <label class="inline-block">Email Address:</label>
                <div class="right-field inline-block">{{$patient->email}}</div>
            </div>
            <div class="row">
                <label class="inline-block">ID Number:</label>
                <div class="right-field inline-block">{{$contract->patient_id_number}}</div>
            </div>
            <div class="row">
                <label class="inline-block">Postal Address:</label>
                <div class="right-field inline-block">{{$patient->address}}</div>
            </div>
            <div class="row">
                <label class="inline-block">Country:</label>
                <div class="right-field inline-block">{{$patient->address}}</div>
            </div>
            <div class="row">
                <label class="inline-block">Wallet Address:</label>
                <div class="right-field inline-block">
                    <a href="http://etherscan.io/address/{{$patient->dcn_address}}" target="_blank">{{$patient->dcn_address}}</a>
                </div>
            </div>
            <div class="section-title">CONTRACT CONDITIONS</div>
            <div class="row">
                <label class="inline-block">Services Covered:</label>
                <div class="right-field inline-block">
                    <div class="svg-row">
                        @if(in_array('param_gd', $services))
                            <svg version="1.1" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 68.3 68.3" style="enable-background:new 0 0 68.3 68.3;" xml:space="preserve"><style type="text/css">.st0{fill:#136585;}</style><metadata><sfw  xmlns="&ns_sfw;"><slices></slices><sliceSourceBounds  bottomLeftOrigin="true" height="68.3" width="68.3" x="15.9" y="40.8"></sliceSourceBounds></sfw></metadata><path class="st0" d="M60.9,0H7.3C3.3,0,0,3.3,0,7.3V61c0,4,3.3,7.3,7.3,7.3H61c4,0,7.3-3.3,7.3-7.3V7.3C68.2,3.2,65,0,60.9,0z
	 M16.2,32.1L16.2,32.1c1.1-1.1,2.9-1.1,4,0l7.7,7.7c0.3,0.3,0.7,0.3,1,0L48,20.7c1.1-1.1,2.9-1.1,4,0l0,0c1.1,1.1,1.1,2.9,0,4
	L29.5,47.2c-0.6,0.6-1.6,0.6-2.3,0l-11-11.1C15.1,35,15.1,33.2,16.2,32.1z"/></svg>
                        @else
                            <svg version="1.1" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 18 18" style="enable-background:new 0 0 18 18;" xml:space="preserve"><style type="text/css">.st0{fill:#136585;}</style><metadata><sfw  xmlns="&ns_sfw;"><slices></slices><sliceSourceBounds  bottomLeftOrigin="true" height="18" width="18" x="3" y="9"></sliceSourceBounds></sfw></metadata><title>checkbox</title><desc>Created with Sketch.</desc><g><g><path class="st0" d="M16,1H2C1.5,1,1,1.5,1,2v14c0,0.5,0.5,1,1,1h14c0.5,0,1-0.5,1-1V2C17,1.5,16.5,1,16,1z M16,0c1.1,0,2,0.9,2,2
			v14c0,1.1-0.9,2-2,2H2c-1.1,0-2-0.9-2-2V2c0-1.1,0.9-2,2-2H16z"/></g></g></svg>
                        @endif
                        General Dentistry
                    </div>
                    <div class="svg-row">
                        @if(in_array('param_cd', $services))
                            <svg version="1.1" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 68.3 68.3" style="enable-background:new 0 0 68.3 68.3;" xml:space="preserve"><style type="text/css">.st0{fill:#136585;}</style><metadata><sfw  xmlns="&ns_sfw;"><slices></slices><sliceSourceBounds  bottomLeftOrigin="true" height="68.3" width="68.3" x="15.9" y="40.8"></sliceSourceBounds></sfw></metadata><path class="st0" d="M60.9,0H7.3C3.3,0,0,3.3,0,7.3V61c0,4,3.3,7.3,7.3,7.3H61c4,0,7.3-3.3,7.3-7.3V7.3C68.2,3.2,65,0,60.9,0z
	 M16.2,32.1L16.2,32.1c1.1-1.1,2.9-1.1,4,0l7.7,7.7c0.3,0.3,0.7,0.3,1,0L48,20.7c1.1-1.1,2.9-1.1,4,0l0,0c1.1,1.1,1.1,2.9,0,4
	L29.5,47.2c-0.6,0.6-1.6,0.6-2.3,0l-11-11.1C15.1,35,15.1,33.2,16.2,32.1z"/></svg>
                        @else
                            <svg version="1.1" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 18 18" style="enable-background:new 0 0 18 18;" xml:space="preserve"><style type="text/css">.st0{fill:#136585;}</style><metadata><sfw  xmlns="&ns_sfw;"><slices></slices><sliceSourceBounds  bottomLeftOrigin="true" height="18" width="18" x="3" y="9"></sliceSourceBounds></sfw></metadata><title>checkbox</title><desc>Created with Sketch.</desc><g><g><path class="st0" d="M16,1H2C1.5,1,1,1.5,1,2v14c0,0.5,0.5,1,1,1h14c0.5,0,1-0.5,1-1V2C17,1.5,16.5,1,16,1z M16,0c1.1,0,2,0.9,2,2
			v14c0,1.1-0.9,2-2,2H2c-1.1,0-2-0.9-2-2V2c0-1.1,0.9-2,2-2H16z"/></g></g></svg>
                        @endif
                        Cosmetic Dentistry
                    </div>
                    <div class="svg-row">
                        @if(in_array('param_id', $services))
                            <svg version="1.1" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 68.3 68.3" style="enable-background:new 0 0 68.3 68.3;" xml:space="preserve"><style type="text/css">.st0{fill:#136585;}</style><metadata><sfw  xmlns="&ns_sfw;"><slices></slices><sliceSourceBounds  bottomLeftOrigin="true" height="68.3" width="68.3" x="15.9" y="40.8"></sliceSourceBounds></sfw></metadata><path class="st0" d="M60.9,0H7.3C3.3,0,0,3.3,0,7.3V61c0,4,3.3,7.3,7.3,7.3H61c4,0,7.3-3.3,7.3-7.3V7.3C68.2,3.2,65,0,60.9,0z
	 M16.2,32.1L16.2,32.1c1.1-1.1,2.9-1.1,4,0l7.7,7.7c0.3,0.3,0.7,0.3,1,0L48,20.7c1.1-1.1,2.9-1.1,4,0l0,0c1.1,1.1,1.1,2.9,0,4
	L29.5,47.2c-0.6,0.6-1.6,0.6-2.3,0l-11-11.1C15.1,35,15.1,33.2,16.2,32.1z"/></svg>
                        @else
                            <svg version="1.1" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 18 18" style="enable-background:new 0 0 18 18;" xml:space="preserve"><style type="text/css">.st0{fill:#136585;}</style><metadata><sfw  xmlns="&ns_sfw;"><slices></slices><sliceSourceBounds  bottomLeftOrigin="true" height="18" width="18" x="3" y="9"></sliceSourceBounds></sfw></metadata><title>checkbox</title><desc>Created with Sketch.</desc><g><g><path class="st0" d="M16,1H2C1.5,1,1,1.5,1,2v14c0,0.5,0.5,1,1,1h14c0.5,0,1-0.5,1-1V2C17,1.5,16.5,1,16,1z M16,0c1.1,0,2,0.9,2,2
			v14c0,1.1-0.9,2-2,2H2c-1.1,0-2-0.9-2-2V2c0-1.1,0.9-2,2-2H16z"/></g></g></svg>
                        @endif
                        Implant Dentistry
                    </div>
                </div>
            </div>
            <div class="row">
                <label class="inline-block">Required Check-ups per Year:</label>
                <div class="right-field inline-block">{{$contract->check_ups_per_year}}</div>
            </div>
            <div class="row">
                <label class="inline-block">Required Teeth Cleaning per Year:</label>
                <div class="right-field inline-block">{{$contract->teeth_cleaning_per_year}}</div>
            </div>
            <div class="row">
                <label class="inline-block">Required Successful Dentacare Journeys:</label>
                <div class="right-field inline-block">1 (90 days)</div>
            </div>
            <div class="section-title">TERMS AND CONDITIONS</div>
            <div style="font-size: 14px;padding-top: 30px;
padding-bottom:60px;">@include('partials.contract-terms-and-conditions')</div>
            <div class="signs-container clearfix">
                <div class="doctor">
                    <img src="/assets/contracts/{{$contract->slug}}/dentist-signature.png"/>
                    <div class="name">/ Dr. {{$dentist->name}} /</div>
                </div>
                <div class="patient">
                    <img src="/assets/contracts/{{$contract->slug}}/patient-signature.png"/>
                    <div class="name">/ {{$patient->name}} /</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>