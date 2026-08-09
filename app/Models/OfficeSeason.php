<?php

namespace App\Models;

use App\Enums\OfficeSeasonStatus;
use Carbon\CarbonImmutable;
use Database\Factories\OfficeSeasonFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $office_id
 * @property int $season_id
 * @property string $delegate_number
 * @property OfficeSeasonStatus $status
 * @property string|null $official_email
 * @property string|null $mobile_number
 * @property CarbonImmutable|null $mobile_verified_at
 * @property CarbonImmutable|null $activated_at
 * @property string|null $terms_version
 * @property CarbonImmutable|null $terms_accepted_at
 * @property string|null $terms_accepted_ip
 * @property int|null $primary_membership_id
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable([
    'office_id',
    'season_id',
    'delegate_number',
    'status',
    'official_email',
    'mobile_number',
    'mobile_verified_at',
    'activated_at',
    'terms_version',
    'terms_accepted_at',
    'terms_accepted_ip',
    'primary_membership_id',
])]
class OfficeSeason extends Model
{
    /** @use HasFactory<OfficeSeasonFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => OfficeSeasonStatus::class,
            'mobile_verified_at' => 'datetime',
            'activated_at' => 'datetime',
            'terms_accepted_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Office, $this>
     */
    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    /**
     * @return BelongsTo<Season, $this>
     */
    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    /**
     * The member allowed to log in with this season's delegate number.
     * Primary is a per-season fact — a different membership may be
     * primary in a different season.
     *
     * @return BelongsTo<OfficeMembership, $this>
     */
    public function primaryMembership(): BelongsTo
    {
        return $this->belongsTo(OfficeMembership::class, 'primary_membership_id');
    }

    /**
     * @return HasMany<ActivationInvite, $this>
     */
    public function activationInvites(): HasMany
    {
        return $this->hasMany(ActivationInvite::class);
    }

    /**
     * @return HasMany<WorkPlan, $this>
     */
    public function workPlans(): HasMany
    {
        return $this->hasMany(WorkPlan::class);
    }

    /**
     * @return HasMany<Station, $this>
     */
    public function stations(): HasMany
    {
        return $this->hasMany(Station::class);
    }

    /**
     * @return HasMany<Observation, $this>
     */
    public function observations(): HasMany
    {
        return $this->hasMany(Observation::class);
    }

    /**
     * @return HasMany<Report, $this>
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    /**
     * @return HasMany<AuditLogEntry, $this>
     */
    public function auditLogEntries(): HasMany
    {
        return $this->hasMany(AuditLogEntry::class);
    }
}
