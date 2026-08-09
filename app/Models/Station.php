<?php

namespace App\Models;

use App\Enums\ReadinessTrackType;
use Carbon\CarbonImmutable;
use Database\Factories\StationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $office_season_id
 * @property ReadinessTrackType $track
 * @property int $city_id
 * @property string $name
 * @property bool $is_completed
 * @property CarbonImmutable|null $completed_at
 * @property CarbonImmutable|null $due_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['office_season_id', 'track', 'city_id', 'name', 'is_completed', 'completed_at', 'due_at'])]
class Station extends Model
{
    /** @use HasFactory<StationFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'track' => ReadinessTrackType::class,
            'is_completed' => 'boolean',
            'completed_at' => 'datetime',
            'due_at' => 'date',
        ];
    }

    /**
     * @return BelongsTo<OfficeSeason, $this>
     */
    public function officeSeason(): BelongsTo
    {
        return $this->belongsTo(OfficeSeason::class);
    }

    /**
     * @return BelongsTo<City, $this>
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
