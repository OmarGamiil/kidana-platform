<?php

namespace Database\Seeders;

use App\Enums\OfficeSeasonStatus;
use App\Enums\OfficeStatus;
use App\Enums\ReadinessTrackType;
use App\Enums\ReportStatus;
use App\Enums\UserAccountType;
use App\Models\ActivationInvite;
use App\Models\City;
use App\Models\Observation;
use App\Models\Office;
use App\Models\OfficeMembership;
use App\Models\OfficeSeason;
use App\Models\Report;
use App\Models\Season;
use App\Models\Station;
use App\Models\User;
use App\Models\WorkPlan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    // Deliberately NOT using WithoutModelEvents: ActivationInvite relies on a
    // `creating` model event to generate its public_id (route key), which that
    // trait would silently suppress.

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Existing starter-kit account — has no office, so it represents Kidana's
        // internal team rather than an office user.
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ])->update(['account_type' => UserAccountType::KidanaTeam]);

        $season = Season::factory()->active()->create(['name' => '1448هـ']);

        $makkah = City::factory()->create(['code' => 'makkah', 'name_ar' => 'مكة المكرمة', 'name_en' => 'Makkah']);
        $madinah = City::factory()->create(['code' => 'madinah', 'name_ar' => 'المدينة المنورة', 'name_en' => 'Madinah']);

        $this->seedActiveOffice($season, $makkah, $madinah);
        $this->seedPendingInvite($season);
    }

    private function seedActiveOffice(Season $season, City $makkah, City $madinah): void
    {
        $office = Office::factory()->create(['name' => 'مكتب شؤون حجاج بيت الله الأول', 'status' => OfficeStatus::Active]);

        $officeSeason = OfficeSeason::factory()->active()->create([
            'office_id' => $office->id,
            'season_id' => $season->id,
            'delegate_number' => 'OFF-1001',
        ]);

        $user = User::factory()->hajjOffice()->create([
            'name' => $office->name,
            'email' => $officeSeason->official_email,
            'password' => Hash::make('password'),
        ]);

        $membership = OfficeMembership::create([
            'office_id' => $office->id,
            'user_id' => $user->id,
            'joined_at' => now(),
        ]);

        $officeSeason->update(['primary_membership_id' => $membership->id]);

        WorkPlan::factory()->create([
            'office_season_id' => $officeSeason->id,
            'deadline_at' => now()->addDays(17),
        ]);

        // 13 stations across the 4 tracks / 2 cities, ~68% complete overall to
        // roughly match the Figma dashboard mockup's numbers.
        $tracks = ReadinessTrackType::cases();
        $cities = [$makkah, $madinah];
        $stationCount = 13;
        $completedTarget = 9;

        for ($i = 0; $i < $stationCount; $i++) {
            Station::factory()->create([
                'office_season_id' => $officeSeason->id,
                'track' => $tracks[$i % count($tracks)],
                'city_id' => $cities[$i % count($cities)]->id,
                'is_completed' => $i < $completedTarget,
                'completed_at' => $i < $completedTarget ? now()->subDays($stationCount - $i) : null,
                'due_at' => $i >= $completedTarget ? now()->addDays($i - $completedTarget + 1) : null,
            ]);
        }

        Observation::factory()->count(3)->create(['office_season_id' => $officeSeason->id]);
        Observation::factory()->closed()->count(2)->create(['office_season_id' => $officeSeason->id]);

        Report::factory()->count(2)->create(['office_season_id' => $officeSeason->id, 'status' => ReportStatus::Pending]);
        Report::factory()->create(['office_season_id' => $officeSeason->id, 'status' => ReportStatus::Approved]);

        $this->command->info("Seeded active office login: {$user->email} / OFF-1001 (password: password)");
    }

    private function seedPendingInvite(Season $season): void
    {
        $office = Office::factory()->create(['name' => 'مكتب شؤون حجاج بيت الله الثاني']);

        $officeSeason = OfficeSeason::factory()->create([
            'office_id' => $office->id,
            'season_id' => $season->id,
            'status' => OfficeSeasonStatus::PendingActivation,
            'delegate_number' => 'OFF-2002',
        ]);

        $rawToken = Str::random(64);

        $invite = ActivationInvite::create([
            'office_season_id' => $officeSeason->id,
            'token_hash' => hash_hmac('sha256', $rawToken, config('app.key')),
            'expires_at' => now()->addDays((int) config('activation.invite_expiry_days')),
        ]);

        $url = route('activation.start', ['activationInvite' => $invite->public_id, 'token' => $rawToken]);

        $this->command->info("Seeded pending activation invite: {$url}");
    }
}
