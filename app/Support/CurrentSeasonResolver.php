<?php

namespace App\Support;

use App\Models\Season;
use RuntimeException;

/**
 * Delegate numbers are only unique within a season (unique(season_id, delegate_number)),
 * so any lookup keyed by delegate number must first pin down exactly which season is
 * operationally "current" — never resolve it with a bare ->first() query.
 *
 * Business rule for this phase: exactly one Season has is_active = true at a time.
 * Switching seasons is a deliberate future action, never a direct field update.
 */
final class CurrentSeasonResolver
{
    public function resolve(): Season
    {
        $season = Season::query()->where('is_active', true)->first();

        if (! $season) {
            throw new RuntimeException('No active season is configured.');
        }

        return $season;
    }
}
