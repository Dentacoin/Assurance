<div class="two step">
    <h2 class="text-center calibri-bold fs-30 padding-bottom-25">PATIENT DETAILS</h2>
    <div class="step-fields module padding-top-35">
        <div class="single-row flex-row fs-0">
            <label class="calibri-light light-gray-color fs-16 padding-right-15 margin-bottom-0">First Name:</label>
            <input type="text" maxlength="100" name="fname" class="right-field calibri-regular fs-18 dark-color inline-block pencil-background" @if(!empty($renew_contract)) value="{{$renew_contract->patient_fname}}" @endif/>
        </div>
        <div class="single-row flex-row fs-0">
            <label class="calibri-light light-gray-color fs-16 padding-right-15 margin-bottom-0">Last Name:</label>
            <input type="text" maxlength="100" name="lname" class="right-field calibri-regular fs-18 dark-color inline-block pencil-background" @if(!empty($renew_contract)) value="{{$renew_contract->patient_lname}}" @endif/>
        </div>
        <div class="single-row flex-row fs-0 margin-bottom-40">
            <label class="calibri-light light-gray-color fs-16 padding-right-15 margin-bottom-0">Email Address:</label>
            <input type="text" data-type="email" name="email" maxlength="100" class="right-field calibri-regular fs-18 dark-color inline-block pencil-background" @if(!empty($renew_contract)) value="{{$renew_patient->patient_email}}" @endif/>
        </div>
    </div>
</div>