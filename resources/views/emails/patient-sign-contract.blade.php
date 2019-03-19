<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <div style="font-size: 16px;">Dear Dr. {{$dentist->name}},
            <br><br>
            Your patient {{$patient->name}} has just signed their contract.
            <br><br>
            <a href="{{route('dentist-contract-view', ['slug' => $contract->slug])}}" style="font-size: 20px;color: #126585;background-color: white;padding: 10px 20px;text-decoration: none;font-weight: bold;border-radius: 4px;border: 2px solid #126585;" target="_blank">SEE CONTRACT</a>
            <br><br>
            Regards,<br><b>Dentacoin Assurance Team</b>
        </div>
    </body>
</html>