<?php

namespace App\Exceptions\Activation;

final class OtpExpiredException extends ActivationException
{
    public function __construct()
    {
        parent::__construct('activation.otp_expired');
    }
}
