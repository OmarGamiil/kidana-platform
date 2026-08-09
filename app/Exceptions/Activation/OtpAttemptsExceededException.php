<?php

namespace App\Exceptions\Activation;

final class OtpAttemptsExceededException extends ActivationException
{
    public function __construct()
    {
        parent::__construct('activation.otp_attempts_exceeded');
    }
}
