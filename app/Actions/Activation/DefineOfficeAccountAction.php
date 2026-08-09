<?php

namespace App\Actions\Activation;

use App\Actions\Audit\RecordAuditLogAction;
use App\Enums\AuditEvent;
use App\Models\ActivationInvite;
use App\Support\ActivationFlowGuard;
use App\Support\OtpIssuer;

final class DefineOfficeAccountAction
{
    public function __construct(
        private readonly ActivationFlowGuard $guard,
        private readonly OtpIssuer $otpIssuer,
        private readonly RecordAuditLogAction $recordAuditLog,
    ) {}

    public function handle(
        ActivationInvite $invite,
        string $mobileNumber,
        string $officialEmail,
        string $termsVersion,
    ): void {
        $this->guard->ensureAccountStepIsAllowed($invite);

        $officeSeason = $invite->officeSeason;

        $officeSeason->update([
            'mobile_number' => $mobileNumber,
            'official_email' => $officialEmail,
            'terms_version' => $termsVersion,
            'terms_accepted_at' => now(),
            'terms_accepted_ip' => request()->ip(),
        ]);

        $this->otpIssuer->issue($invite, $mobileNumber);

        $this->recordAuditLog->handle(
            event: AuditEvent::ActivationAccountDefined,
            subject: $officeSeason,
            officeId: $officeSeason->office_id,
            officeSeasonId: $officeSeason->id,
        );
    }
}
