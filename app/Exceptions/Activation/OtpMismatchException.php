<?php

namespace App\Exceptions\Activation;

final class OtpMismatchException extends ActivationException
{
    public function __construct()
    {
        parent::__construct('activation.otp_mismatch');
    }
}
