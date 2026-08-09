<?php

namespace App\Actions\Audit;

use App\Enums\AuditEvent;
use App\Models\AuditLogEntry;
use App\Models\User;
use App\Support\AuditPayloadSanitizer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * The single write path for audit_log_entries — every payload passed through
 * here is sanitized so secrets can never end up in the audit log, regardless
 * of what the caller passes in.
 */
final class RecordAuditLogAction
{
    public function __construct(
        private readonly AuditPayloadSanitizer $sanitizer,
    ) {}

    /**
     * @param  array<string, mixed>|null  $oldValues
     * @param  array<string, mixed>|null  $newValues
     * @param  array<string, mixed>|null  $metadata
     */
    public function handle(
        AuditEvent $event,
        ?User $actor = null,
        ?Model $subject = null,
        ?int $officeId = null,
        ?int $officeSeasonId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?array $metadata = null,
        ?Request $request = null,
    ): AuditLogEntry {
        $request ??= request();

        return AuditLogEntry::create([
            'event' => $event,
            'actor_id' => $actor?->id,
            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->getKey(),
            'office_id' => $officeId,
            'office_season_id' => $officeSeasonId,
            'request_id' => $request->header('X-Request-Id'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'old_values' => $this->sanitizer->sanitize($oldValues),
            'new_values' => $this->sanitizer->sanitize($newValues),
            'metadata' => $this->sanitizer->sanitize($metadata),
        ]);
    }
}
