<!DOCTYPE html>
<html>
<head></head>
<body>
<div style="font-size: 13px;">Dear {{$patient_name}},
    <br><br>
    Your dentist {{$dentist_name}} has successfully received your monthly Dentacoin Assurance payment.
    <br><br>
    <a href="https://rinkeby.etherscan.io/tx/{{$transaction_hash}}" style="font-size: 16px;color: #126585;background-color: white;padding: 10px 20px;text-decoration: none;font-weight: bold;border-radius: 4px;border: 2px solid #126585;" target="_blank">SEE PAYMENT PROOF</a>
    <br><br><br>
    Regards,<br><b>Dentacoin Assurance Team</b>
</div>
</body>
</html>