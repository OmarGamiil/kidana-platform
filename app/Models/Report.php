<?php

namespace App\Models;

use App\Enums\ReportStatus;
use Carbon\CarbonImmutable;
use Database\Factories\ReportFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $office_season_id
 * @property string $title
 * @property ReportStatus $status
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['office_season_id', 'title', 'status'])]
class Report extends Model
{
    /** @use HasFactory<ReportFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => ReportStatus::class,
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
