<!DOCTYPE html>
<html>
<head></head>
<body style="font-size: 13px;">
<div>Dear {{$patient_fname}} {{$patient_lname}},<br><br><br>I have created an
    individualized Assurance Contract for you. It entitles you to prevention-focused dental services against an
    affordable monthly premium in Dentacoin (DCN) currency*.<br><br>It’s very easy to start: just click on the button
    below, sign up, check my proposal and follow the instructions if you are interested:<br><br><br><a
            href="{{route('contract-proposal', ['slug' => $temporally_contract->slug])}}"
            style="font-size: 14px;color: #126585;background-color: white;padding: 8px 10px;text-decoration: none;font-weight: bold;border-radius: 4px;border: 2px solid #126585;"
            target="_blank">SEE CONTRACT</a><br><br><br>Looking forward to seeing you onboard!<br><br>Regards,<br><b>{{$sender}}</b><br><br><br><i
            style="font-size: 11px;">* Dentacoin is the first dental cryptocurrency which can be earned through the
        Dentacoin tools, used as a means of payment for dental services and assurance fees, and exchanged to any other
        crypto or traditional currency.</i></div>
</body>
</html>