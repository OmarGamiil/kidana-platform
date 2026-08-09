<?php

namespace App\Actions\Activation;

use App\Actions\Audit\RecordAuditLogAction;
use App\Enums\AuditEvent;
use App\Exceptions\Activation\OtpAttemptsExceededException;
use App\Exceptions\Activation\OtpExpiredException;
use App\Exceptions\Activation\OtpMismatchException;
use App\Models\ActivationInvite;
use App\Models\OtpCode;
use App\Support\ActivationFlowGuard;
use Illuminate\Support\Facades\DB;

final class VerifyOtpAction
{
    public function __construct(
        private readonly ActivationFlowGuard $guard,
        private readonly RecordAuditLogAction $recordAuditLog,
    ) {}

    public function handle(ActivationInvite $invite, string $code): void
    {
        $this->guard->ensureOtpStepIsAllowed($invite);

        // The transaction must always COMMIT here — including a failed attempt's
        // incremented counter — so we return a status and throw AFTER the
        // transaction closes, rather than throwing inside it (which would roll
        // back the attempts increment along with everything else).
        $result = DB::transaction(function () use ($invite, $code) {
            $otp = OtpCode::query()
                ->where('activation_invite_id', $invite->id)
                ->whereNull('consumed_at')
                ->whereNull('invalidated_at')
                ->lockForUpdate()
                ->latest('id')
                ->first();

            if (! $otp || $otp->isExpired()) {
                return 'expired';
            }

            $otp->attempts++;
            $otp->last_attempt_at = now();

            if ($otp->attempts > (int) config('activation.otp_max_attempts')) {
                $otp->invalidated_at = now();
                $otp->save();

                return 'attempts_exceeded';
            }

            if (! $otp->matches($code)) {
                $otp->save();

                return 'mismatch';
            }

            $otp->verified_at = now();
            $otp->save();

            return 'verified';
        });

        match ($result) {
            'expired' => throw new OtpExpiredException,
            'attempts_exceeded' => throw new OtpAttemptsExceededException,
            'mismatch' => throw new OtpMismatchException,
            default => null,
        };

        $officeSeason = $invite->officeSeason;

        $this->recordAuditLog->handle(
            event: AuditEvent::ActivationOtpVerified,
            officeId: $officeSeason->office_id,
            officeSeasonId: $officeSeason->id,
        );
    }
}
