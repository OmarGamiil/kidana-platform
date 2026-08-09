<?php

namespace App\Models;

use App\Enums\AuditEvent;
use Carbon\CarbonImmutable;
use Database\Factories\AuditLogEntryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property AuditEvent $event
 * @property int|null $actor_id
 * @property string|null $subject_type
 * @property int|null $subject_id
 * @property int|null $office_id
 * @property int|null $office_season_id
 * @property string|null $request_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property array<string, mixed>|null $old_values
 * @property array<string, mixed>|null $new_values
 * @property array<string, mixed>|null $metadata
 * @property CarbonImmutable|null $created_at
 */
#[Fillable([
    'event',
    'actor_id',
    'subject_type',
    'subject_id',
    'office_id',
    'office_season_id',
    'request_id',
    'ip_address',
    'user_agent',
    'old_values',
    'new_values',
    'metadata',
])]
class AuditLogEntry extends Model
{
    /** @use HasFactory<AuditLogEntryFactory> */
    use HasFactory;

    // Immutable audit rows — created_at only.
    public const ?string UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'event' => AuditEvent::class,
            'old_values' => 'array',
            'new_values' => 'array',
            'metadata' => 'array',
            'created_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo<Office, $this>
     */
    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    /**
     * @return BelongsTo<OfficeSeason, $this>
     */
    public function officeSeason(): BelongsTo
    {
        return $this->belongsTo(OfficeSeason::class);
    }
}
