<?php

namespace App\Support;

use App\Models\Office;
use App\Models\OfficeMembership;
use App\Models\OfficeSeason;
use App\Models\Season;
use App\Models\User;

/**
 * "Which office/season is this authenticated user acting in" resolved in one place.
 * Phase 1 has exactly one membership per user, so this resolves trivially — but every
 * office-scoped read/write should take a CurrentOfficeContext rather than re-deriving
 * it, so multi-office support later only touches CurrentOfficeContextResolver.
 */
final readonly class CurrentOfficeContext
{
    public function __construct(
        public User $user,
        public Office $office,
        public OfficeMembership $membership,
        public Season $season,
        public OfficeSeason $officeSeason,
    ) {}
}
