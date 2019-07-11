<!DOCTYPE html>
<html>
<head></head>
<body>
<div style="font-size: 13px;">Dear {{$patient_name}},
    <br><br>
    {{$dentist_name}} has cancelled your contract due to the following reason: {{$reason}}.
    <br><br><br>
    <a href="{{route('patient-contract-view', ['slug' => $contract_slug])}}" style="font-size: 14px;color: #126585;background-color: white;padding: 8px 10px;text-decoration: none;font-weight: bold;border-radius: 4px;border: 2px solid #126585;" target="_blank">SEE DETAILS</a>
    <br><br><br>
    Regards,<br><b>Dentacoin Assurance Team</b>
</div>
</body>
</html>