<?php

namespace App\Support;

use App\Exceptions\Activation\ActivationInviteAlreadyUsedException;
use App\Exceptions\Activation\ActivationInviteExpiredException;
use App\Exceptions\Activation\ActivationInviteRevokedException;
use App\Exceptions\Activation\ActivationStepOutOfOrderException;
use App\Exceptions\Activation\ActivationTokenMismatchException;
use App\Models\ActivationInvite;

/**
 * Activation preconditions are real business rules (is the invite usable? is this
 * step allowed yet? is the OTP verified?) so they live in one injectable, testable
 * service — not scattered across controllers or hidden in a trait.
 */
final class ActivationFlowGuard
{
    public function ensureTokenMatches(ActivationInvite $invite, string $token): void
    {
        $expected = hash_hmac('sha256', $token, config('app.key'));

        if (! hash_equals($invite->token_hash, $expected)) {
            // Treated identically to "not found" — no information leak about
            // which part of the link (public_id vs token) was wrong.
            throw new ActivationTokenMismatchException;
        }
    }

    public function ensureInviteIsUsable(ActivationInvite $invite): void
    {
        if ($invite->revoked_at !== null) {
            throw new ActivationInviteRevokedException;
        }

        if ($invite->used_at !== null) {
            throw new ActivationInviteAlreadyUsedException;
        }

        if ($invite->expires_at->isPast()) {
            throw new ActivationInviteExpiredException;
        }
    }

    public function ensureAccountStepIsAllowed(ActivationInvite $invite): void
    {
        $this->ensureInviteIsUsable($invite);
    }

    public function ensureOtpStepIsAllowed(ActivationInvite $invite): void
    {
        $this->ensureInviteIsUsable($invite);

        if ($invite->officeSeason->mobile_number === null) {
            throw new ActivationStepOutOfOrderException;
        }
    }

    public function ensurePasswordStepIsAllowed(ActivationInvite $invite): void
    {
        $this->ensureInviteIsUsable($invite);

        $hasVerifiedOtp = $invite->otpCodes()
            ->whereNotNull('verified_at')
            ->whereNull('consumed_at')
            ->whereNull('invalidated_at')
            ->exists();

        if (! $hasVerifiedOtp) {
            throw new ActivationStepOutOfOrderException;
        }
    }
}
