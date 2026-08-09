<?php

namespace App\Support;

use App\Contracts\OtpSender;
use App\DTOs\OtpMessageData;
use App\Models\ActivationInvite;
use App\Models\OtpCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Single use-case: generate, persist, and dispatch an activation OTP code.
 * Shared by DefineOfficeAccountAction (first send) and ResendOtpAction (resend)
 * so the generation/hashing/dispatch rule lives in exactly one place.
 */
final class OtpIssuer
{
    public function __construct(
        private readonly OtpSender $otpSender,
    ) {}

    public function issue(ActivationInvite $invite, string $recipient): OtpCode
    {
        $length = (int) config('activation.otp_length');
        $code = Str::padLeft((string) random_int(0, (10 ** $length) - 1), $length, '0');

        $otpCode = OtpCode::create([
            'activation_invite_id' => $invite->id,
            'purpose' => 'activation_phone_verification',
            'channel' => 'sms',
            'recipient' => $recipient,
            'code_hash' => Hash::make($code),
            'sent_at' => now(),
            'expires_at' => now()->addMinutes((int) config('activation.otp_expiry_minutes')),
        ]);

        $this->otpSender->send(new OtpMessageData(
            recipient: $recipient,
            code: $code,
            purpose: $otpCode->purpose,
        ));

        return $otpCode;
    }
}
