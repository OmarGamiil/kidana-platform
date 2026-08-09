<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\OtpCodeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;

/**
 * @property int $id
 * @property int $activation_invite_id
 * @property string $purpose
 * @property string $channel
 * @property string $recipient
 * @property string $code_hash
 * @property CarbonImmutable $sent_at
 * @property CarbonImmutable $expires_at
 * @property CarbonImmutable|null $last_attempt_at
 * @property int $attempts
 * @property CarbonImmutable|null $verified_at
 * @property CarbonImmutable|null $consumed_at
 * @property CarbonImmutable|null $invalidated_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable([
    'activation_invite_id',
    'purpose',
    'channel',
    'recipient',
    'code_hash',
    'sent_at',
    'expires_at',
    'last_attempt_at',
    'attempts',
    'verified_at',
    'consumed_at',
    'invalidated_at',
])]
class OtpCode extends Model
{
    /** @use HasFactory<OtpCodeFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'expires_at' => 'datetime',
            'last_attempt_at' => 'datetime',
            'verified_at' => 'datetime',
            'consumed_at' => 'datetime',
            'invalidated_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<ActivationInvite, $this>
     */
    public function activationInvite(): BelongsTo
    {
        return $this->belongsTo(ActivationInvite::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function matches(string $plainCode): bool
    {
        return Hash::check($plainCode, $this->code_hash);
    }
}
