<!DOCTYPE html>
<html>
<head></head>
<body>
<div style="font-size: 13px;">Dear {{(new \App\Http\Controllers\Controller())->prepareUserName($dentist)}},
    <br><br>
    Your patient {{$patient->name}} said they've visited you for a check-up on {{$check_up_date}} and teeth cleaning on {{$teeth_cleaning_date}}.
    <br><br><br>
    <a href="{{route('dentist-contract-view', ['slug' => $contract_slug])}}" style="font-size: 14px;color: #126585;background-color: white;padding: 8px 10px;text-decoration: none;font-weight: bold;border-radius: 4px;border: 2px solid #126585;" target="_blank">CONFIRM / DECLINE</a>
    <br><br><br>
    Regards,<br><b>Dentacoin Assurance Team</b>
</div>
</body>
</html>