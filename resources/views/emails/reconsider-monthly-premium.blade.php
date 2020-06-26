<!DOCTYPE html>
<html>
<head></head>
<body>
<div style="font-size: 13px;">
    Dear {{$dentist_name}},
    <br><br><br>
    My name is <b>{{$sender_name}}</b> and I have successfully received my Assurance Contract Sample, but I’d like to
    suggest a monthly premium of {{$usd_proposal}} USD in Dentacoin (DCN). Please send me an amended contract sample.
    <br><br><br>
    <a href="{{route('dentist-contract-view', ['slug' => $contract->slug])}}" style="font-size: 14px;color: #126585;background-color: white;padding: 8px 10px;text-decoration: none;font-weight: bold;border-radius: 4px;border: 2px solid #126585;" target="_blank">SEE CONTRACT</a>
    <br><br><br>
    I hope you will reconsider your proposal.
    <br><br>Regards,<br><b>{{$sender_name}}</b></div>
</body>
</html>