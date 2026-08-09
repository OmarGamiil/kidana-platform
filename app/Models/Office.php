<?php

namespace App\Models;

use App\Enums\OfficeStatus;
use Carbon\CarbonImmutable;
use Database\Factories\OfficeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property OfficeStatus $status
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['name', 'status'])]
class Office extends Model
{
    /** @use HasFactory<OfficeFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => OfficeStatus::class,
        ];
    }

    /**
     * @return HasMany<OfficeSeason, $this>
     */
    public function officeSeasons(): HasMany
    {
        return $this->hasMany(OfficeSeason::class);
    }

    /**
     * @return HasMany<OfficeMembership, $this>
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(OfficeMembership::class);
    }
}
