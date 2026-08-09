<?php

namespace App\Console\Commands;

use App\Enums\OfficeSeasonStatus;
use App\Enums\OfficeStatus;
use App\Models\ActivationInvite;
use App\Models\Office;
use App\Models\OfficeSeason;
use App\Support\CurrentSeasonResolver;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('activation:invite {office_name} {delegate_number}')]
#[Description('Create a pending office + activation invite for manual testing and print the invite URL.')]
class MakeActivationInvite extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(CurrentSeasonResolver $currentSeason): int
    {
        $season = $currentSeason->resolve();

        $office = Office::create(['name' => $this->argument('office_name'), 'status' => OfficeStatus::Active]);

        $officeSeason = OfficeSeason::create([
            'office_id' => $office->id,
            'season_id' => $season->id,
            'delegate_number' => $this->argument('delegate_number'),
            'status' => OfficeSeasonStatus::PendingActivation,
        ]);

        $rawToken = Str::random(64);

        $invite = ActivationInvite::create([
            'office_season_id' => $officeSeason->id,
            'token_hash' => hash_hmac('sha256', $rawToken, config('app.key')),
            'expires_at' => now()->addDays((int) config('activation.invite_expiry_days')),
        ]);

        $url = route('activation.start', ['activationInvite' => $invite->public_id, 'token' => $rawToken]);

        $this->info("Activation URL: {$url}");

        return self::SUCCESS;
    }
}
