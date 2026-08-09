<?php

namespace App\Listeners;

use App\Actions\Audit\RecordAuditLogAction;
use App\Enums\AuditEvent;
use App\Models\User;
use Illuminate\Auth\Events\Login;

final class LogSuccessfulLogin
{
    public function __construct(
        private readonly RecordAuditLogAction $recordAuditLog,
    ) {}

    public function handle(Login $event): void
    {
        /** @var User $user */
        $user = $event->user;

        $this->recordAuditLog->handle(
            event: AuditEvent::UserLoggedIn,
            actor: $user,
            subject: $user,
        );
    }
}
