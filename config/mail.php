<?php

return array(
    'driver' => 'smtp',
    'host' => 'smtp.sendgrid.net',
    'port' => 587,
    'from' => array(
        'address' => 'admin@dentacoin.com',
        'name' => 'Dentacoin Assurance Platform'
    ),
    'encryption' => 'tls',
    'username' => env('SENDGRID_USERNAME'),
    'password' => env('SENDGRID_PASSWORD'),
);