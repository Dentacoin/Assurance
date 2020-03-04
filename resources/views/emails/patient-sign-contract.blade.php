<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <div style="font-size: 13px;">Dear {{$dentist->title}} {{$dentist->name}},
            <br><br>
            Your patient {{$patient->name}} has just signed their contract.
            <br><br><br>
            <a href="{{route('dentist-contract-view', ['slug' => $contract->slug])}}" style="font-size: 14px;color: #126585;background-color: white;padding: 8px 10px;text-decoration: none;font-weight: bold;border-radius: 4px;border: 2px solid #126585;" target="_blank">SEE CONTRACT</a>
            <br><br><br>
            Regards,<br><b>Dentacoin Assurance Team</b>
        </div>
    </body>
</html>