<!DOCTYPE html>
<html>
<head>
</head>
<body>
<div style="font-size: 13px;">Dear {{$patient_name}},
    <br><br>
    {{(new \App\Http\Controllers\Controller())->prepareUserName($dentist)}} has officially approved and activated your smart Assurance contract.
    <br><br>
    Please take care to charge your account every month with the amount of Dentacoin equivalent to your monthly premium: {{$amount}} USD.
    <br><br><br>
    <a href="{{route('patient-contract-view', ['slug' => $contract_slug])}}" style="font-size: 14px;color: #126585;background-color: white;padding: 8px 10px;text-decoration: none;font-weight: bold;border-radius: 4px;border: 2px solid #126585;" class="anchor-link" target="_blank">SEE CONTRACT</a>
    <br><br><br>
    Regards,<br><b>Dentacoin Assurance Team</b>
</div>
</body>
</html>