<?php

namespace App\Listeners;

use App\Actions\Audit\RecordAuditLogAction;
use App\Enums\AuditEvent;
use App\Models\User;
use Illuminate\Auth\Events\Logout;

final class LogSuccessfulLogout
{
    public function __construct(
        private readonly RecordAuditLogAction $recordAuditLog,
    ) {}

    public function handle(Logout $event): void
    {
        /** @var User|null $user */
        $user = $event->user;

        if ($user === null) {
            return;
        }

        $this->recordAuditLog->handle(
            event: AuditEvent::UserLoggedOut,
            actor: $user,
            subject: $user,
        );
    }
}
