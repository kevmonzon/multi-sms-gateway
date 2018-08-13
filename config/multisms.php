<?php
return [

    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'token' => env('TWILIO_TOKEN'),
    ],
    'plivo' => [
        'sid' => env('PLIVO_SID'),
        'token' => env('PLIVO_TOKEN'),

    ],
    'nexmo' => [
        'key' => env('NEXMO_KEY'),
        'token' => env('NEXMO_SECRET'),

    ],

    'envayasms' => [],
    'smsgatewayme' => [],
    'smssync' => [],

    'settings' => []

];