@php($dentist = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->dentist_id))
@php($patient = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->patient_id))
@php($services = unserialize($contract->general_dentistry))
<div>
    <div class="wrapper">
        <div class="main-title">ASSURANCE CONTRACT</div>
        <div class="sub-title">Signed on {{$contract->created_at->format('d/m/Y')}}</div>
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
            <div class="right-field inline-block">{{$countries[$dentist->country_id - 1]->name}}</div>
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
            <div class="right-field inline-block">
                <a href="http://etherscan.io/address/{{$contract->dentist_address}}" target="_blank">{{$contract->dentist_address}}</a>
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
            <div class="right-field inline-block">{{$countries[$patient->country_id - 1]->name}}</div>
        </div>
        <div class="row">
            <label class="inline-block">Wallet Address:</label>
            <div class="right-field inline-block">
                <a href="http://etherscan.io/address/{{$contract->patient_address}}" target="_blank">{{$contract->patient_address}}</a>
            </div>
        </div>
        <div class="section-title">CONTRACT CONDITIONS</div>
        <div class="row">
            <label class="inline-block">Services Covered:</label>
            <div class="right-field inline-block">
                @if(in_array('param_gd', $services))
                    <div class="svg-row">General Dentistry</div>
                @endif
                @if(in_array('param_cd', $services))
                    <div class="svg-row">Cosmetic Dentistry</div>
                @endif
                @if(in_array('param_id', $services))
                    <div class="svg-row">Implant Dentistry</div>
                @endif
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
padding-bottom:60px;">@include('partials.contract-terms-and-conditions', ['dont_show_terms_signatures' => true])</div>
        <div class="signs-container clearfix">
            <div class="doctor">
                <img src="{{CONTRACTS.$contract->slug.'/dentist-signature.png'}}"/>
                <div class="name">/ Dr. {{$dentist->name}} /</div>
            </div>
            <div class="patient">
                <img src="{{CONTRACTS.$contract->slug.'/patient-signature.png'}}"/>
                <div class="name">/ {{$patient->name}} /</div>
            </div>
        </div>
    </div>
</div>