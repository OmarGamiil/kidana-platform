<?php

use App\Services\Otp\LocalOtpSender;
use App\Services\Otp\SmsOtpSender;

return [

    'driver' => env('OTP_DRIVER', 'local'),

    'drivers' => [
        'local' => [
            'class' => LocalOtpSender::class,
        ],
        'sms' => [
            'class' => SmsOtpSender::class,
            'provider' => env('OTP_SMS_PROVIDER'),
        ],
    ],

];
