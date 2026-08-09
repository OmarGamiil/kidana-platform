<?php

namespace App\Actions\Auth;

use App\Enums\MembershipStatus;
use App\Enums\OfficeSeasonStatus;
use App\Models\OfficeSeason;
use App\Models\User;
use App\Support\CurrentSeasonResolver;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Full credential verification for Fortify::authenticateUsing() — resolving a
 * candidate user is not enough, this must also check the password, exactly as
 * Fortify's own contract expects. Named generically (not Office-specific)
 * since it already handles the "identifier looks like an email" branch for
 * any account type; only the delegate-number branch is Office-specific today.
 */
final class AuthenticatePlatformUserAction
{
    public function __construct(
        private readonly CurrentSeasonResolver $currentSeason,
    ) {}

    public function handle(string $identifier, string $password): ?User
    {
        $identifier = trim($identifier);

        $user = str_contains($identifier, '@')
            ? User::query()->where('email', $identifier)->first()
            : $this->resolveByDelegateNumber($identifier);

        if (! $user || ! Hash::check($password, $user->password)) {
            return null;
        }

        return $user;
    }

    /**
     * Compares case-insensitively via LOWER() rather than relying on the
     * database connection's collation — Fortify's CanonicalizeUsername
     * already lowercases the incoming identifier, but MySQL's default
     * collation is case-insensitive while SQLite's `=` is not, so matching
     * must not depend on which database is running underneath.
     */
    private function resolveByDelegateNumber(string $delegateNumber): ?User
    {
        $season = $this->currentSeason->resolve();

        /** @var OfficeSeason|null $officeSeason */
        $officeSeason = OfficeSeason::query()
            ->where('season_id', $season->id)
            ->whereRaw('LOWER(delegate_number) = ?', [Str::lower($delegateNumber)])
            ->where('status', OfficeSeasonStatus::Active)
            ->first();

        $membership = $officeSeason?->primaryMembership;

        if (! $membership || $membership->status !== MembershipStatus::Active) {
            return null;
        }

        return $membership->user;
    }
}
