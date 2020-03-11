<div class="two step">
    <h2 class="text-center calibri-bold fs-30 padding-bottom-25 padding-bottom-xs-0">PATIENT DETAILS</h2>
    <div class="step-fields module padding-top-35">
        <div class="single-row flex-row fs-0">
            <label class="calibri-light light-gray-color fs-16 padding-right-15 margin-bottom-0 cursor-pointer" for="patient-email">Email Address:</label>
            @php($patient_email_from_get_param = \Illuminate\Support\Facades\Input::get('patient-email'))
            <input autocomplete="off" readonly type="text" data-type="email" name="email" id="patient-email" maxlength="100" class="right-field calibri-regular fs-18 dark-color inline-block pencil-background" @if(!empty($patient_email)) value="{{$patient_email}}" @elseif(isset($patient_email_from_get_param)) value="{{$patient_email_from_get_param}}" @endif/>
        </div>
        <div class="single-row flex-row fs-0">
            <label class="calibri-light light-gray-color fs-16 padding-right-15 margin-bottom-0 cursor-pointer" for="fname">First Name:</label>
            <input type="text" maxlength="100" name="fname" id="fname" class="right-field calibri-regular fs-18 dark-color inline-block pencil-background" @if(!empty($renew_contract)) value="{{$renew_contract->patient_fname}}" @endif/>
        </div>
        <div class="single-row flex-row fs-0 margin-bottom-40 margin-bottom-xs-10">
            <label class="calibri-light light-gray-color fs-16 padding-right-15 margin-bottom-0 cursor-pointer" for="lname">Last Name:</label>
            <input type="text" maxlength="100" name="lname" id="lname" class="right-field calibri-regular fs-18 dark-color inline-block pencil-background" @if(!empty($renew_contract)) value="{{$renew_contract->patient_lname}}" @endif/>
        </div>
    </div>
</div>