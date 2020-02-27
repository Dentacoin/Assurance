<!DOCTYPE html>
<html>
<head></head>
<body>
<div style="font-size: 13px;">Dear {{$user_name}},
    <br><br>
    Your contract was cancelled due to the following reason: {{$reason}}.
    <br><br><br>
    @if($user_type == 'patient')
        <a href="{{route('patient-contract-view', ['slug' => $contract_slug])}}" style="font-size: 14px;color: #126585;background-color: white;padding: 8px 10px;text-decoration: none;font-weight: bold;border-radius: 4px;border: 2px solid #126585;" target="_blank">SEE DETAILS</a>
    @elseif($user_type == 'dentist')
        <a href="{{route('dentist-contract-view', ['slug' => $contract_slug])}}" style="font-size: 14px;color: #126585;background-color: white;padding: 8px 10px;text-decoration: none;font-weight: bold;border-radius: 4px;border: 2px solid #126585;" target="_blank">SEE DETAILS</a>
    @endif
    <br><br><br>
    Regards,<br><b>Dentacoin Assurance Team</b>
</div>
</body>
</html>