@php($services = unserialize($contract->general_dentistry))
@if(is_object($contract->contract_active_at))
    @php($active_timestamp = $contract->contract_active_at->getTimestamp())
@else
    @php($active_timestamp = strtotime($contract->contract_active_at))
@endif
<div>
    <div class="wrapper">
        <div class="main-title">ASSURANCE CONTRACT</div>
        <div class="sub-title">Signed on {{date('d/m/Y', $active_timestamp)}}</div>
        <div class="section-title">DENTIST DETAILS</div>
        <div class="row">
            <label class="inline-block">Name:</label>
            <div class="right-field bolded inline-block">{{$contract->dentist_name}}</div>
        </div>
        <div class="row">
            <label class="inline-block">Professional / Company Registration Number:</label>
            <div class="right-field inline-block">{{$contract->professional_company_number}}</div>
        </div>
        <div class="row">
            <label class="inline-block">Postal Address:</label>
            <div class="right-field inline-block">{{$contract->dentist_street_address}}</div>
        </div>
        <div class="row">
            <label class="inline-block">Country:</label>
            <div class="right-field inline-block">{{$contract->dentist_country}}</div>
        </div>
        <div class="row">
            <label class="inline-block">Phone:</label>
            <div class="right-field inline-block">{{$contract->dentist_phone}}</div>
        </div>
        <div class="row">
            <label class="inline-block">Website:</label>
            <div class="right-field inline-block">
                <a href="{{$contract->dentist_website}}" target="_blank">{{$contract->dentist_website}}</a>
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
            <div class="right-field bolded inline-block">{{$contract->patient_full_name}}</div>
        </div>
        <div class="row">
            <label class="inline-block">Email Address:</label>
            <div class="right-field inline-block">{{$contract->patient_email}}</div>
        </div>
        <div class="row">
            <label class="inline-block">ID Number:</label>
            <div class="right-field inline-block">{{$contract->patient_id_number}}</div>
        </div>
        <div class="row">
            <label class="inline-block">Postal Address:</label>
            <div class="right-field inline-block">{{$contract->patient_street_address}}</div>
        </div>
        <div class="row">
            <label class="inline-block">Country:</label>
            <div class="right-field inline-block">{{$contract->patient_country}}</div>
        </div>
        <div class="row">
            <label class="inline-block">Wallet Address:</label>
            <div class="right-field inline-block">
                <a href="http://etherscan.io/address/{{$contract->patient_address}}" target="_blank">{{$contract->patient_address}}</a>
            </div>
        </div>
        <div class="section-title">CONTRACT CONDITIONS</div>
        <div class="row">
            <label class="inline-block">Required Check-ups per Year:</label>
            <div class="right-field inline-block-top">{{$contract->check_ups_per_year}}</div>
        </div>
        <div class="row">
            <label class="inline-block">Required Teeth Cleaning per Year:</label>
            <div class="right-field inline-block-top">{{$contract->teeth_cleaning_per_year}}</div>
        </div>
        <div class="row">
            <label class="inline-block">Required Successful <a href="https://dentacare.dentacoin.com/" target="_blank" class="text-decoration-underline blue-green-color">Dentacare Journeys</a>:</label>
            <div class="right-field inline-block-top">1 (90 days)</div>
        </div>
        <div class="row">
            <label class="inline-block">Services Covered:</label>
            <div class="right-field inline-block">
                @if(in_array('param_gd', $services))
                    <div class="svg-row">General Dentistry</div>
                    <ul class="services-list">
                        <li>- Fillings</li>
                        <li>- Caries infiltration</li>
                        <li>- Dental sealants for children</li>
                        <li>- Root canal treatment</li>
                        <li>- Periodontal treatment</li>
                        <li>- Tooth extraction</li>
                    </ul>
                @endif
                @if(in_array('param_cd', $services))
                    <div class="svg-row">Cosmetic Dentistry</div>
                    <ul class="services-list">
                        <li>- Composite bonding</li>
                        <li>- Porcelain veneers (material & laboratory costs - not covered)</li>
                        <li>- Composite veneers (material & laboratory costs - not covered)</li>
                        <li>- Inlays & onlays (material & laboratory costs - not covered)</li>
                        <li>- Crowns (material & laboratory costs - not covered)</li>
                        <li>- Bridges (material & laboratory costs - not covered)</li>
                        <li>- Dentures (material & laboratory costs - not covered)</li>
                    </ul>
                @endif
                @if(in_array('param_id', $services))
                    <div class="svg-row">Implant Dentistry</div>
                    <ul class="services-list">
                        <li>- Implant placement (implants and abutments - not covered)</li>
                        <li>- Porcelain veneers (material & laboratory costs - not covered)</li>
                        <li>- Composite veneers (material & laboratory costs - not covered)</li>
                        <li>- Inlays & onlays (material & laboratory costs - not covered)</li>
                        <li>- Crowns (material & laboratory costs - not covered)</li>
                        <li>- Bridges (material & laboratory costs - not covered)</li>
                        <li>- Bone augmentation (bone replacement material - not covered)</li>
                    </ul>
                @endif
            </div>
        </div>
        <div class="row">
            <label class="inline-block">Monthly Premium:</label>
            <div class="right-field bolded inline-block">{{$contract->monthly_premium}} USD</div>
        </div>
        <div class="row">
            <label class="inline-block">Contract Validity:</label>
            <div class="right-field inline-block">Valid until canceled</div>
        </div>
        <div class="section-title">TERMS AND CONDITIONS</div>
        <div style="font-size: 14px;padding-top: 30px;
padding-bottom:60px;">@include('partials.contract-terms-and-conditions', ['dont_show_terms_signatures' => true, 'show_dentist_patient_data' => true])</div>
        <div class="signs-container clearfix">
            <div class="doctor">
                <img src="{{CONTRACTS.$contract->slug.'/dentist-signature.png'}}"/>
                <div class="name">/ {{$contract->dentist_name}} /</div>
            </div>
            <div class="patient">
                <img src="{{CONTRACTS.$contract->slug.'/patient-signature.png'}}"/>
                <div class="name">/ {{$contract->patient_full_name}} /</div>
            </div>
        </div>
    </div>
</div>