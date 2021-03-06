<div class="dark-color fs-18">
    <div class="text-center fs-40 fs-xs-24 lato-regular padding-bottom-35 padding-bottom-xs-10"><i class="fa fa-envelope blue-green-color" aria-hidden="true"></i> {{$mail_title}}</div>
    <div class="single-line padding-top-10 padding-bottom-5 calibri-bold"><label>Receiver:</label> {{$receiver}}</div>
    <div class="single-line padding-top-10 padding-bottom-5"><label class="calibri-bold">Subject:</label> {{$mail_subject}}</div>
    <div class="email-body">{!! $mail_body !!}</div>
    <div class="padding-bottom-40 padding-bottom-xs-20">
        Regards,
        <div class="calibri-bold">{{$sender->name}}</div>
    </div>
    <div class="fs-14 calibri-light padding-bottom-30">* Blockchain is just a new technology used for secure storage and exchange of value and data. </div>
    <div class="text-center padding-bottom-15 send-mail"><a href="javascript:void(0)" class="white-blue-green-btn min-width-250">SEND</a></div>
</div>