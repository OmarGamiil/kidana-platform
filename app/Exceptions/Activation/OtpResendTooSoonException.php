<?php

namespace App\Exceptions\Activation;

final class OtpResendTooSoonException extends ActivationException
{
    public function __construct()
    {
        parent::__construct('activation.otp_resend_too_soon');
    }
}
