<div class="text-center padding-top-30">
    <svg class="max-width-50" version="1.1" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;"xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 82"style="enable-background:new 0 0 64 82;" xml:space="preserve"><style type="text/css">.st0{fill:#126585;}  .st1{fill-rule:evenodd;clip-rule:evenodd;fill:#126585;}</style><metadata><sfw  xmlns="&ns_sfw;"><slices></slices><sliceSourceBounds  bottomLeftOrigin="true" height="82" width="64" x="18" y="34"></sliceSourceBounds></sfw></metadata><g transform="translate(0,-952.36218)"><g><path class="st0" d="M31.7,952.4c-0.1,0-0.3,0.1-0.4,0.1l-30,11c-0.8,0.3-1.3,1-1.3,1.9v33c0,7.8,4.4,14.3,10.3,20c5.9,5.7,13.5,10.7,20.5,15.7c0.7,0.5,1.6,0.5,2.3,0c7-5,14.6-10,20.5-15.7c5.9-5.7,10.3-12.2,10.3-20v-33c0-0.8-0.5-1.6-1.3-1.9l-30-11C32.4,952.4,32,952.3,31.7,952.4z M32,956.5l28,10.3v31.6c0,6.3-3.5,11.8-9.1,17.1c-5.2,5-12.2,9.7-18.9,14.4c-6.7-4.7-13.7-9.4-18.9-14.4c-5.5-5.3-9.1-10.8-9.1-17.1v-31.6L32,956.5z"/></g></g><g><g><path class="st1" d="M50.3,25.9c0.6,0.6,1.2,1.2,1.8,1.8c0.9,0.9,0.9,2.5,0,3.4C45.6,37.5,39.1,44,32.6,50.5c-3.3,3.3-3.5,3.3-6.8,0c-3.3-3.3-6.7-6.7-10-10c-0.9-0.9-0.9-2.5,0-3.4c0.6-0.6,1.2-1.2,1.8-1.8c0.9-0.9,2.5-0.9,3.4,0c2.7,2.7,5.4,5.4,8.2,8.2c5.9-5.9,11.7-11.7,17.6-17.6C47.8,25,49.3,25,50.3,25.9z"/></g></g></svg>
    <h2 class="lato-bold fs-30">DENTIST PROFILE VERIFICATION</h2>
    <div class="padding-top-20 padding-bottom-15 fs-20">Thank you for submitting your registration form!  Our onboarding team will now assess the data you have provided to ensure that you are a real dentist.</div>
    <div class="padding-bottom-25 fs-20 {{--border-bottom--}}">You will receive an email notification as soon as the verification process is completed.</div>
    <h2 class="lato-bold fs-30 padding-top-25 padding-bottom-25 lato-bold fs-30">While waiting, why don’t you enrich your profile?</h2>
    <form method="POST" action="{{ route('enrich-profile') }}" id="enrich-profile">
        <div class="form-row">
            <textarea rows="4" name="description" maxlength="200" placeholder="Add your short description (140 characters):"></textarea>
            <div class="fs-14 calibri-light">This will improve your positions in Google and help more patients find you.</div>
        </div>
        <div class="btn-container text-center padding-bottom-30 padding-top-30">
            <input type="submit" class="white-blue-green-btn min-width-200" value="SAVE"/>
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="user" value="{{$user}}">
        </div>
    </form>
</div>