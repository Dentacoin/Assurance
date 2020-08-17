<!DOCTYPE html>
<html>
<head></head>
<body>
<div style="font-size: 13px;">Dear {{$dentist_name}},<br><br><br>My name is <b>{{$sender->name}}</b>
    and I as a patient of yours I would like to invite you to join <b>Dentacoin Assurance</b> - the first blockchain*
    dental assurance that entitles patients to preventive dental care against affordable monthly premiums in Dentacoin
    (DCN) currency.<br><br>It’s very easy to start: Just sign up, wait for approval and create your first contract. <a
            href="{{$base_url}}" style="color: #126585;font-weight: bold; text-decoration: none;" target="_blank">See how
        it works.</a> After/ if I agree to the conditions offered, we will get into a trustful agreement benefiting from
    an automated payment & notification system.<br><br>Affordable, preventive care for me - regular income and loyal
    patients for you!<br><br><br><a href="{{$base_url}}support-guide"
                                    style="font-size: 14px;color: #126585;background-color: white;padding: 8px 10px;text-decoration: none;font-weight: bold;border-radius: 4px;border: 2px solid #126585;"
                                    target="_blank">LEARN MORE</a><br><br><br>Looking forward to seeing you onboard! If
    you need any further information, do not hesitate to contact the Dentacoin Assurance team at <a
            href="mailto:assurance@dentacoin.com" style="color: #126585;font-weight: bold; text-decoration: none;">assurance@dentacoin.com</a>.<br><br>Regards,<br><b>{{$sender->name}}</b><br><br><br><i
            style="font-size: 11px;">* Blockchain is just a new technology used for secure storage and exchange of value
        and data.</i></div>
</body>
</html>