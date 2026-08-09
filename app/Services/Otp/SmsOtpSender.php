<?php

namespace App\Services\Otp;

use App\Contracts\OtpSender;
use App\DTOs\OtpMessageData;
use App\Exceptions\OtpProviderNotConfiguredException;

/**
 * Production driver (OTP_DRIVER=sms). No real SMS provider is wired yet — this
 * is documented as a production-release blocker (not a development blocker,
 * since OTP_DRIVER=local covers the full flow for demos/tests). Fails loudly
 * rather than silently "succeeding" with nothing delivered.
 */
final class SmsOtpSender implements OtpSender
{
    public function send(OtpMessageData $message): void
    {
        if (! config('otp.drivers.sms.provider')) {
            throw new OtpProviderNotConfiguredException;
        }

        throw new OtpProviderNotConfiguredException;
    }
}
