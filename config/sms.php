<?php

return [
    'smsc_address' => env('SMS_SMSC_ADDRESS', 'smpp.example.com'),
    'smsc_port' => env('SMS_SMSC_PORT', 12345),
    'login' => env('SMS_SMSC_LOGIN', 'your_login'),
    'password' => env('SMS_SMSC_PASSWORD', 'your_password'),
    'timeout' => 10000,
    'debug' => false,
];
