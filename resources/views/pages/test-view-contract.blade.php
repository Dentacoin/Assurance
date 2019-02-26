<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Calibri;
        }
        .wrapper {
            width: 1200px;
            margin: 0 auto;
        }
        .main-title {
            color: #126585;
            font-size: 30px;
            text-align: center;
            font-weight: bold;
            padding-top: 20px;
        }
        .section-title {
            border-bottom: 1px dashed black;
            padding-bottom: 10px;
            font-size: 30px;
            font-weight: bold;
            color: #212121;
            padding-top: 50px;
        }
        .row {
            border-bottom: 1px solid #888888;
            font-size: 0;
            padding-top: 20px;
            padding-bottom: 20px;
        }
        .inline-block {
            display: inline-block;
            vertical-align: middle;
        }
        label {
            width: 40%;
            font-size: 16px;
            color: #888888;
            padding-right: 15px;
        }
        .right-field {
            color: #212121;
            font-size: 18px;
        }
        .right-field.bolded {
            font-weight: bold;
            font-size: 25px;
        }
        a {
            color: #337ab7;
        }
    </style>
</head>
<body>
@php($dentist = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->dentist_id))
@php($patient = (new \App\Http\Controllers\APIRequestsController())->getUserData($contract->patient_id))
    <div>
        <div class="wrapper">
            <div class="main-title">ASSURANCE CONTRACT</div>
            <div class="section-title">DENTIST DETAILS</div>
            <div class="row">
                <label class="inline-block">Name:</label>
                <div class="right-field bolded inline-block">{{$dentist->name}}</div>
            </div>
            <div class="row">
                <label class="inline-block">Professional / Company Registration Number:</label>
                <div class="right-field inline-block">{{$contract->professional_company_number}}</div>
            </div>
            <div class="row">
                <label class="inline-block">Country:</label>
                <div class="right-field inline-block">{{$contract->professional_company_number}}</div>
            </div>
            <div class="row">
                <label class="inline-block">Phone:</label>
                <div class="right-field inline-block">{{$dentist->phone}}</div>
            </div>
            <div class="row">
                <label class="inline-block">Website:</label>
                <div class="right-field inline-block">
                    <a href="{{$dentist->website}}" target="_blank">{{$dentist->website}}</a>
                </div>
            </div>
            <div class="row">
                <label class="inline-block">Wallet Address:</label>
                <div class="right-field bolded inline-block">
                    <a href="http://etherscan.io/address/{{$dentist->dcn_address}}" target="_blank">{{$dentist->dcn_address}}</a>
                </div>
            </div>
            <div class="section-title">PATIENT DETAILS</div>
            <div class="row">
                <label class="inline-block">Name:</label>
                <div class="right-field bolded inline-block">{{$patient->name}}</div>
            </div>
            <div class="row">
                <label class="inline-block">Email Address:</label>
                <div class="right-field inline-block">{{$patient->email}}</div>
            </div>
            <div class="row">
                <label class="inline-block">ID Number:</label>
                <div class="right-field inline-block">{{$contract->patient_id_number}}</div>
            </div>
            <div class="row">
                <label class="inline-block">Postal Address:</label>
                <div class="right-field inline-block">{{$patient->address}}</div>
            </div>
            <div class="row">
                <label class="inline-block">Country:</label>
                <div class="right-field inline-block">{{$patient->address}}</div>
            </div>
            <div class="row">
                <label class="inline-block">Wallet Address:</label>
                <div class="right-field inline-block">
                    <a href="http://etherscan.io/address/{{$patient->dcn_address}}" target="_blank">{{$patient->dcn_address}}</a>
                </div>
            </div>
            <div class="section-title">CONTRACT CONDITIONS</div>
        </div>
    </div>
</body>
</html>