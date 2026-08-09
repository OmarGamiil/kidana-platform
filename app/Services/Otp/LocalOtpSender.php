<?php

namespace App\Services\Otp;

use App\Contracts\OtpSender;
use App\DTOs\OtpMessageData;
use Illuminate\Support\Facades\Session;

/**
 * Local/dev driver (OTP_DRIVER=local). The code is never written to any log
 * channel — it is session-flashed so the Verify page can show it in a dev-only
 * banner, which is the one place it is allowed to be surfaced.
 */
final class LocalOtpSender implements OtpSender
{
    public function send(OtpMessageData $message): void
    {
        Session::flash('otp_debug_code', $message->code);
    }
}
