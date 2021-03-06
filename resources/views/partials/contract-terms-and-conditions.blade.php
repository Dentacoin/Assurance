<h2 class="text-center fs-32 fs-xs-22 padding-bottom-10 calibri-bold">DENTACOIN ASSURANCE CONTRACT</h2>
@if(!empty($contract->contract_active_at) && !empty($contract->document_hash) || (isset($show_dentist_patient_data) && $show_dentist_patient_data))
    @if(is_object($contract->contract_active_at))
        @php($active_timestamp = $contract->contract_active_at->getTimestamp())
    @else
        @php($active_timestamp = strtotime($contract->contract_active_at))
    @endif
    @php($countries = (new \App\Http\Controllers\APIRequestsController())->getAllCountries())
    <div>This present Dentacoin Assurance Contract Agreement was reached on {{date('d/m/Y', $active_timestamp)}}</div>
    <br>
    <div><span class="calibri-bold fs-18">BETWEEN:</span></div>
    <br>
    <div>
        <div>Name - {{$contract->dentist_name}} (the "Dentist")</div>
        <div>Email - {{$contract->dentist_email}}</div>
        <div>Address - {{$contract->dentist_street_address}}</div>
        <div>Country - {{$contract->dentist_country}}</div>
        <div>Phone - {{$contract->dentist_phone}}</div>
        <div>Website - {{$contract->dentist_website}}</div>
        <div>Wallet Address - {{$contract->dentist_address}}</div>
    </div>
    <br>
    <div><span class="calibri-bold fs-18">and</span></div>
    <br>
    <div>
        <div>Name - {{$contract->patient_full_name}} (the "Patient")</div>
        <div>Email - {{$contract->patient_email}}</div>
        <div>Address - {{$contract->patient_street_address}}</div>
        <div>Country - {{$contract->patient_country}}</div>
        <div>Wallet Address - {{$contract->patient_address}}</div>
    </div>
    <br>
@endif
<h3 class="fs-22 fs-xs-18 calibri-bold padding-top-30 padding-bottom-15">1. DENTIST RIGHTS AND OBLIGATIONS</h3>
<div class="padding-bottom-15">This present Dentacoin Assurance Contract obliges the Dentist to provide free of charge to the Patient <span class="terms-check-ups-per-year"></span> check-ups per year and <span class="terms-teeth-cleaning-per-year"></span> professional tooth cleanings per year for the duration of the contract.</div>
<div class="padding-bottom-15">The Dentist is also obliged to cover the costs for their work for the predefined set of possibly occurring treatments described in 3. Service Coverage.</div>
<div class="padding-bottom-15">The Dentist has the right to withdraw the agreed monthly premium amount in Dentacoin (DCN) currency on the payment due date or later once the Patient has given their initial permission.</div>
<div class="padding-bottom-15">The Dentist has the right to cancel the contract and/ or refuse a treatment in case the Patient has not visit the Dentist for the required number of check-ups and tooth cleanings per year. </div>
<h3 class="fs-22 fs-xs-18 calibri-bold padding-top-30 padding-bottom-15">2. PATIENT RIGHTS AND OBLIGATIONS</h3>
<div class="padding-bottom-15">This present Dentacoin Assurance Contract obliges the Patient to charge their wallet every month with the agreed assurance premium amount in Dentacoin (DCN) currency, namely an equivalent to <span class="terms-monthly-premium"></span> USD. The Patient must ensure that they have the full amount needed in their wallet latest on the payment due date.</div>
<div class="padding-bottom-15">The Patient is also obliged to visit the Dentist for <span class="terms-check-ups-per-year"></span> check-ups per year and <span class="terms-teeth-cleaning-per-year"></span> professional tooth cleanings per year, as well as to use Dentacare Mobile app consistently for at least 90 days.</div>
<div class="padding-bottom-15">In case the above-described recommendations are followed, the Patient has the right to have their occurring treatment costs covered for the predefined set of possibly occurring treatments described in 3. Service Coverage.</div>
<div class="padding-bottom-15">The Patient has the right to cancel the contract at any given moment.</div>
<h3 class="fs-22 fs-xs-18 calibri-bold padding-top-30 padding-bottom-15">3. DENTAL SERVICES COVERAGE</h3>
<div class="fs-18 calibri-bold">Required Prophylaxis visits:</div>
@if(!empty($contract))
    @php($general_dentistry_arr = unserialize($contract->general_dentistry))
    @php($prophylaxis_list = '')
    @if(in_array('param_gd', $general_dentistry_arr))
        @php($prophylaxis_list .= '<div class="param_gd"><div class="fs-18 calibri-bold padding-top-15 prophylaxis-title">General Dentistry</div><ul class="inner-list"><li>Fillings</li><li>Caries infiltration</li><li>Dental sealants for children</li><li>Root canal treatment</li><li>Periodontal treatment</li><li>Tooth extraction</li></ul></div>')
    @endif
    @if(in_array('param_cd', $general_dentistry_arr))
        @php($prophylaxis_list .= '<div class="param_cd"><div class="fs-18 calibri-bold padding-top-15 prophylaxis-title">Cosmetic Dentistry</div><ul class="inner-list"><li>Composite bonding</li><li>Porcelain veneers (material & laboratory costs - not covered)</li><li>Composite veneers (material & laboratory costs - not covered)</li><li>Inlays & onlays (material & laboratory costs - not covered)</li><li>Crowns (material & laboratory costs - not covered)</li><li>Bridges (material & laboratory costs - not covered)</li><li>Dentures (material & laboratory costs - not covered)</li></ul></div>')
    @endif
    @if(in_array('param_id', $general_dentistry_arr))
        @php($prophylaxis_list .= '<div class="param_id"><div class="fs-18 calibri-bold padding-top-15 prophylaxis-title">Implant Dentistry</div><ul class="inner-list"><li>Implant placement (implants and abutments - not covered)</li><li>Porcelain veneers (material & laboratory costs - not covered)</li><li>Composite veneers (material & laboratory costs - not covered)</li><li>Inlays & onlays (material & laboratory costs - not covered)</li><li>Crowns (material & laboratory costs - not covered)</li><li>Bridges (material & laboratory costs - not covered)</li><li>Bone augmentation (bone replacement material - not covered)</li></ul></div>')
    @endif
    <ul>
        <li>{{$contract->check_ups_per_year}} check-ups per year;</li>
        <li>{{$contract->teeth_cleaning_per_year}} professional tooth cleanings per year.</li>
    </ul>
    <div class="prophylaxis-list">{!! $prophylaxis_list !!}</div>
@else
    <ul>
        <li><span class="terms-check-ups-per-year"></span> check-ups per year;</li>
        <li><span class="terms-teeth-cleaning-per-year"></span> professional tooth cleanings per year.</li>
    </ul>
    <div class="prophylaxis-list"></div>
@endif
<h3 class="fs-22 fs-xs-18 calibri-bold padding-top-30 padding-bottom-15">4. PAYMENTS</h3>
<div class="padding-bottom-15">The agreed monthly assurance premium between the Dentist and the Patient is equivalent to <span class="terms-monthly-premium"></span> USD in Dentacoin (DCN) currency and must be paid every month on the agreed payment due date.</div>
<div class="padding-bottom-15">The Patient must ensure that they have the full amount needed in their wallet on the payment due date. There is a grace period of 21 (twenty one) days.</div>
<div class="padding-bottom-15">The Dentist has the right to withdraw the agreed monthly premium amount in Dentacoin (DCN) currency on the payment due date or later once the Patient has given their initial permission.</div>
<div class="padding-bottom-15">Both parties must only used the wallets explicitly specified in this contract.</div>
<h3 class="fs-22 fs-xs-18 calibri-bold padding-top-30 padding-bottom-15">5. COMPATIBILITY WITH OTHER DENTAL PLANS</h3>
<div class="padding-bottom-15">Dentacoin Assurance does not compete with other public and private health insurance, assurance or membership plans. The Dentist is free to decide which plans to work with. They can either offer Dentacoin Assurance as a complementary plan which will cover/ reduce the Patient’s out-of-pocket costs or as a stand-alone plan in case other options are not available.</div>
<h3 class="fs-22 fs-xs-18 calibri-bold padding-top-30 padding-bottom-15">6. CANCELLATION POLICY</h3>
<div class="padding-bottom-15">The Dentist has the right to cancel the contract and/ or refuse a treatment in case the Patient has not visit the Dentist for the required number of check-ups and tooth cleanings per year.</div>
<div class="padding-bottom-15">The Patient has the right to cancel the contract at any given moment.</div>
<div class="padding-bottom-15">In case the Patient has not paid the agreed monthly premium, the contract is automatically cancelled.</div>
<h3 class="fs-22 fs-xs-18 calibri-bold padding-top-30 padding-bottom-15">7. COMPLAINTS AND THIRD-PARTY REGULATIONS</h3>
<div class="padding-bottom-15">This present Agreement is concluded directly between the Patient and the Dentist.</div>
<div class="padding-bottom-15">Dentacoin B.V. only provides the infrastructure to help both parties communicate and exchange value easier. If any technical difficulties are faced when using the website, Dentacoin B.V. can be contacted at <a href="mailto:assurance@dentacoin.com">assurance@dentacoin.com</a>.</div>
<div class="padding-bottom-15">It is not Dentacoin B.V.’s responsibility to handle any complaints in regards to the violation of this contract.</div>
<h3 class="fs-22 fs-xs-18 calibri-bold padding-top-30 padding-bottom-15">8. DATA PRIVACY</h3>
<div class="padding-bottom-15">Dentacoin B.V. takes the protection of personal data very seriously. Personal data are treated as confidential and in accordance with the statutory data protection regulations and this Privacy Policy: <a href="https://dentacoin.com/privacy-policy" target="_blank">https://dentacoin.com/privacy-policy</a>.</div>
@if(!empty($contract->contract_active_at) && !isset($dont_show_terms_signatures))
    <div class="container-fluid contract-terms-and-conditions-signatures">
        <div class="row text-right-xs">
            <div class="col-xs-12 col-sm-6">
                <img src="/assets/contracts/{{$contract->slug.'/dentist-signature.png'}}"/>
                <div class="name">/ {{$contract->dentist_name}} /</div>
            </div>
            <div class="col-xs-12 col-sm-6 text-right padding-top-xs-30">
                <img src="/assets/contracts/{{$contract->slug.'/patient-signature.png'}}"/>
                <div class="name">/ {{$contract->patient_full_name}} /</div>
            </div>
        </div>
    </div>
@endif