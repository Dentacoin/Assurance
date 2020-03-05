<!DOCTYPE html>
<html>
<head>
</head>
<body>
<div style="font-size: 13px;">Dear {{$patient_name}},
    <br><br>
    {{$dentist->title}} {{$dentist->name}} confirmed the {{$type}} you marked in the system. Good job taking care of your oral health!
    @if(!empty($approvedRecordRecordsLeft))
        {!! $approvedRecordRecordsLeft !!}
    @endif
    <br><br><br>
    Regards,<br><b>Dentacoin Assurance Team</b>
</div>
</body>
</html>