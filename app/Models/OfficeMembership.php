<?php

namespace App\Models;

use App\Enums\MembershipStatus;
use Carbon\CarbonImmutable;
use Database\Factories\OfficeMembershipFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $office_id
 * @property int $user_id
 * @property MembershipStatus $status
 * @property string|null $job_title
 * @property CarbonImmutable|null $joined_at
 * @property CarbonImmutable|null $left_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['office_id', 'user_id', 'status', 'job_title', 'joined_at', 'left_at'])]
class OfficeMembership extends Model
{
    /** @use HasFactory<OfficeMembershipFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => MembershipStatus::class,
            'joined_at' => 'datetime',
            'left_at' => 'datetime',
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
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
