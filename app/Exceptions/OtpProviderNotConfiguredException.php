<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Thrown when OTP_DRIVER=sms but no real SMS provider has been configured.
 * Fails loudly by design — an activation flow that appears to succeed while
 * no code was actually delivered is worse than an obvious error.
 */
final class OtpProviderNotConfiguredException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('OTP SMS provider is not configured. Set OTP_SMS_PROVIDER in .env or use OTP_DRIVER=local for development.');
    }
}
