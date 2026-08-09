<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\WorkPlanFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $office_season_id
 * @property CarbonImmutable|null $deadline_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['office_season_id', 'deadline_at'])]
class WorkPlan extends Model
{
    /** @use HasFactory<WorkPlanFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'deadline_at' => 'date',
        ];
    }

    /**
     * @return BelongsTo<OfficeSeason, $this>
     */
    public function officeSeason(): BelongsTo
    {
        return $this->belongsTo(OfficeSeason::class);
    }
}
