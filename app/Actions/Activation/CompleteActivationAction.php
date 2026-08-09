<?php

namespace App\Actions\Activation;

use App\Actions\Audit\RecordAuditLogAction;
use App\Enums\AuditEvent;
use App\Enums\MembershipStatus;
use App\Enums\OfficeSeasonStatus;
use App\Enums\UserAccountType;
use App\Exceptions\Activation\ActivationAlreadyCompletedException;
use App\Exceptions\Activation\ActivationStepOutOfOrderException;
use App\Models\ActivationInvite;
use App\Models\OfficeMembership;
use App\Models\OfficeSeason;
use App\Models\OtpCode;
use App\Models\User;
use App\Support\ActivationFlowGuard;
use Illuminate\Support\Facades\DB;

final class CompleteActivationAction
{
    public function __construct(
        private readonly ActivationFlowGuard $guard,
        private readonly RecordAuditLogAction $recordAuditLog,
    ) {}

    /**
     * The activation wizard never collects an individual person's name (the
     * Figma screens only ask for phone/email/OTP/password) — the created
     * user's name defaults to the office's name. Individual member names are
     * expected to be editable later from the (out-of-scope this phase)
     * Office Members screen.
     *
     * Returns the newly created User. Logging that user in (Auth::login +
     * session regeneration) is deliberately left to the caller — it is an
     * HTTP/session concern, not a database transaction concern, and must
     * happen only after this transaction has actually committed.
     */
    public function handle(ActivationInvite $invite, string $password): User
    {
        $this->guard->ensurePasswordStepIsAllowed($invite);

        return DB::transaction(function () use ($invite, $password) {
            /** @var ActivationInvite $lockedInvite */
            $lockedInvite = ActivationInvite::query()->lockForUpdate()->findOrFail($invite->id);

            if ($lockedInvite->used_at !== null) {
                throw new ActivationAlreadyCompletedException;
            }

            /** @var OfficeSeason $officeSeason */
            $officeSeason = OfficeSeason::query()->lockForUpdate()->findOrFail($lockedInvite->office_season_id);

            // Re-confirm the OTP is verified-and-unconsumed UNDER the lock — the
            // guard clause above already checked this before the transaction
            // opened, but that check alone is not race-safe against a concurrent
            // request completing the same invite first.
            $otp = OtpCode::query()
                ->where('activation_invite_id', $lockedInvite->id)
                ->whereNotNull('verified_at')
                ->whereNull('consumed_at')
                ->whereNull('invalidated_at')
                ->lockForUpdate()
                ->latest('id')
                ->first();

            if (! $otp) {
                throw new ActivationStepOutOfOrderException;
            }

            $user = User::create([
                'name' => $officeSeason->office->name,
                'email' => $officeSeason->official_email,
                'password' => $password,
                'account_type' => UserAccountType::HajjOffice,
            ]);

            $membership = OfficeMembership::create([
                'office_id' => $officeSeason->office_id,
                'user_id' => $user->id,
                'status' => MembershipStatus::Active,
                'joined_at' => now(),
            ]);

            $officeSeason->update([
                'status' => OfficeSeasonStatus::Active,
                'activated_at' => now(),
                'mobile_verified_at' => now(),
                'primary_membership_id' => $membership->id,
            ]);

            $lockedInvite->update(['used_at' => now()]);
            $otp->update(['consumed_at' => now()]);

            $this->recordAuditLog->handle(
                event: AuditEvent::ActivationCompleted,
                actor: $user,
                subject: $user,
                officeId: $officeSeason->office_id,
                officeSeasonId: $officeSeason->id,
            );

            return $user;
        });
    }
}
