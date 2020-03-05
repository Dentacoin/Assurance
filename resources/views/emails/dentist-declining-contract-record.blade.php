<!DOCTYPE html>
<html>
<head>
</head>
<body>
<div style="font-size: 13px;">Dear {{$patient_name}},
    <br><br>
    {{$dentist->title}} {{$dentist->name}} declined the {{$type}} you marked in the system. Thus, this entry will not be recorded.
    <br><br><br>
    Do you believe they made a mistake? Get in touch with them and add a <a href="{{route('patient-contract-view', ['slug' => $slug])}}?type={{$recordGetTypeParam}}" style="font-size: 14px;color: #126585;font-weight: bold;">new entry</a> after solving the misunderstanding.
    <br><br><br>
    Regards,<br><b>Dentacoin Assurance Team</b>
</div>
</body>
</html>