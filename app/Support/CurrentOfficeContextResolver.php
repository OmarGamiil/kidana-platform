<?php

namespace App\Support;

use App\Enums\MembershipStatus;
use App\Models\OfficeSeason;
use App\Models\User;

final class CurrentOfficeContextResolver
{
    public function __construct(
        private readonly CurrentSeasonResolver $currentSeason,
    ) {}

    public function resolve(User $user): CurrentOfficeContext
    {
        $season = $this->currentSeason->resolve();

        $membership = $user->officeMemberships()
            ->where('status', MembershipStatus::Active)
            ->with('office')
            ->firstOrFail();

        $officeSeason = OfficeSeason::query()
            ->where('office_id', $membership->office_id)
            ->where('season_id', $season->id)
            ->firstOrFail();

        return new CurrentOfficeContext(
            user: $user,
            office: $membership->office,
            membership: $membership,
            season: $season,
            officeSeason: $officeSeason,
        );
    }
}
