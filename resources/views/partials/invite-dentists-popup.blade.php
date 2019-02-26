<div class="dark-color fs-18">
    @php($current_patient = (new \App\Http\Controllers\APIRequestsController())->getUserData(session('logged_user')['id']))
    <div class="text-center fs-40 lato-regular padding-bottom-35"><i class="fa fa-envelope blue-green-color" aria-hidden="true"></i> Invite Your Dentist</div>
    <div class="single-line padding-top-10 padding-bottom-5 calibri-bold"><label>Receiver:</label> {{$data['title']}} {{$data['dentist-name']}} ({{$data['email']}})</div>
    <div class="single-line padding-top-10 padding-bottom-5"><label class="calibri-bold">Subject:</label> Subject:  Invitation to join Dentacoin Assurance</div>
    <div class="padding-bottom-25 padding-top-15">My name is <span class="calibri-bold">{{$current_patient->name}}</span> and I as a patient of yours I would like to invite you to join <span class="calibri-bold">Dentacoin Assurance</span> - the first blockchain* dental assurance that entitles patients to preventive dental care against affordable monthly premiums in Dentacoin (DCN) currency.</div>
    <div class="padding-bottom-25">It’s very easy to start: Just sign up, wait for approval and create your first contract. <a href="{{BASE_URL}}" target="_blank" class="blue-green-color calibri-bold">See how it works.</a> After/ if I agree to the conditions offered, we will get into a trustful agreement benefiting from an automated payment & notification system.</div>
    <div class="padding-bottom-20">Affordable, preventive care for me - regular income and loyal patients for you!</div>
    <div class="padding-bottom-20"><a href="{{BASE_URL}}support-guide" target="_blank" class="blue-green-white-btn">LEARN MORE</a></div>
    <div class="padding-bottom-30">Looking forward to seeing you onboard! If you need any further information, do not hesitate to contact the Dentacoin Assurance team at <a href="mailto:assurance@dentacoin.com" class="blue-green-color calibri-bold">assurance@dentacoin.com</a>.</div>
    <div class="padding-bottom-40">
        Regards,
        <div class="calibri-bold">{{$current_patient->name}}</div>
    </div>
    <div class="fs-14 calibri-light padding-bottom-30">* Blockchain is just a new technology used for secure storage and exchange of value and data. </div>
    <div class="text-center padding-bottom-15 send-mail-invite-dentists"><a href="javascript:void(0)" class="white-blue-green-btn min-width-250">SEND</a></div>
</div>