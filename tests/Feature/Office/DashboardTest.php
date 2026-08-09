<?php

use App\Enums\MembershipStatus;
use App\Enums\OfficeSeasonStatus;
use App\Enums\ReadinessTrackType;
use App\Enums\UserAccountType;
use App\Models\City;
use App\Models\Office;
use App\Models\OfficeMembership;
use App\Models\OfficeSeason;
use App\Models\Season;
use App\Models\Station;
use App\Models\User;

/**
 * CurrentSeasonResolver requires exactly one active season — passing the
 * same $season into multiple calls (rather than letting each call create
 * its own "active" one) is what makes the multi-office isolation test below
 * valid instead of accidentally tripping that invariant.
 *
 * @return array{user: User, officeSeason: OfficeSeason}
 */
function activeDashboardOfficeSeason(?Season $season = null): array
{
    $season ??= Season::factory()->active()->create();
    $office = Office::factory()->create();
    $officeSeason = OfficeSeason::factory()->active()->create([
        'office_id' => $office->id,
        'season_id' => $season->id,
    ]);

    $user = User::factory()->hajjOffice()->create();

    $membership = OfficeMembership::create([
        'office_id' => $office->id,
        'user_id' => $user->id,
        'status' => MembershipStatus::Active,
        'joined_at' => now(),
    ]);

    $officeSeason->update(['primary_membership_id' => $membership->id]);

    return ['user' => $user, 'officeSeason' => $officeSeason->fresh()];
}

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('a kidana team account cannot access the office dashboard', function () {
    $user = User::factory()->create(['account_type' => UserAccountType::KidanaTeam]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertForbidden();
});

test('an office user whose season is still pending activation is blocked', function () {
    ['user' => $user, 'officeSeason' => $officeSeason] = activeDashboardOfficeSeason();
    $officeSeason->update(['status' => OfficeSeasonStatus::PendingActivation]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertForbidden();
});

test('an active office user sees the dashboard with seeded stats', function () {
    ['user' => $user, 'officeSeason' => $officeSeason] = activeDashboardOfficeSeason();
    $city = City::factory()->create();

    Station::factory()->count(3)->completed()->create([
        'office_season_id' => $officeSeason->id,
        'city_id' => $city->id,
        'track' => ReadinessTrackType::Camps,
    ]);
    Station::factory()->count(1)->create([
        'office_season_id' => $officeSeason->id,
        'city_id' => $city->id,
        'track' => ReadinessTrackType::Camps,
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('office/Dashboard')
        ->where('officeName', $officeSeason->office->name)
        ->where('workPlan.stationsCompleted', 3)
        ->where('workPlan.stationsTotal', 4)
        ->where('workPlan.completionPercentage', 75)
    );
});

test("a second office's stations never appear in the dashboard", function () {
    $season = Season::factory()->active()->create();
    ['user' => $user] = activeDashboardOfficeSeason($season);
    ['officeSeason' => $otherOfficeSeason] = activeDashboardOfficeSeason($season);

    $city = City::factory()->create();
    Station::factory()->count(5)->create([
        'office_season_id' => $otherOfficeSeason->id,
        'city_id' => $city->id,
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertInertia(fn ($page) => $page
        ->where('workPlan.stationsTotal', 0)
    );
});
