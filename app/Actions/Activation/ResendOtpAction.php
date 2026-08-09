<?php

namespace App\Actions\Activation;

use App\Exceptions\Activation\OtpResendTooSoonException;
use App\Models\ActivationInvite;
use App\Models\OtpCode;
use App\Support\ActivationFlowGuard;
use App\Support\OtpIssuer;

final class ResendOtpAction
{
    public function __construct(
        private readonly ActivationFlowGuard $guard,
        private readonly OtpIssuer $otpIssuer,
    ) {}

    public function handle(ActivationInvite $invite): void
    {
        $this->guard->ensureOtpStepIsAllowed($invite);

        $latest = OtpCode::query()
            ->where('activation_invite_id', $invite->id)
            ->whereNull('consumed_at')
            ->whereNull('invalidated_at')
            ->latest('id')
            ->first();

        if ($latest) {
            $cooldownSeconds = (int) config('activation.otp_resend_cooldown_seconds');

            if ($latest->sent_at->diffInSeconds(now()) < $cooldownSeconds) {
                throw new OtpResendTooSoonException;
            }
        }

        // Resending invalidates every prior un-consumed code — only the newest
        // code is ever valid.
        OtpCode::query()
            ->where('activation_invite_id', $invite->id)
            ->whereNull('consumed_at')
            ->whereNull('invalidated_at')
            ->update(['invalidated_at' => now()]);

        $this->otpIssuer->issue($invite, $invite->officeSeason->mobile_number);
    }
}
