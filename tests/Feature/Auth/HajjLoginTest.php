<?php

use App\Enums\MembershipStatus;
use App\Enums\OfficeSeasonStatus;
use App\Models\Office;
use App\Models\OfficeMembership;
use App\Models\OfficeSeason;
use App\Models\Season;
use App\Models\User;

/**
 * Creates an Active office/season/user/membership, with the user set as the
 * season's primary member, and returns them for assertions.
 *
 * @return array{user: User, officeSeason: OfficeSeason}
 */
function activeOfficeSeasonWithPrimaryUser(): array
{
    $season = Season::factory()->active()->create();
    $office = Office::factory()->create();
    $officeSeason = OfficeSeason::factory()->active()->create([
        'office_id' => $office->id,
        'season_id' => $season->id,
        'delegate_number' => 'OFF-1001',
    ]);

    $user = User::factory()->hajjOffice()->create(['email' => $officeSeason->official_email]);

    $membership = OfficeMembership::create([
        'office_id' => $office->id,
        'user_id' => $user->id,
        'status' => MembershipStatus::Active,
        'joined_at' => now(),
    ]);

    $officeSeason->update(['primary_membership_id' => $membership->id]);

    return ['user' => $user, 'officeSeason' => $officeSeason->fresh()];
}

test('office user can authenticate with email', function () {
    ['user' => $user] = activeOfficeSeasonWithPrimaryUser();

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('office user can authenticate with delegate number', function () {
    ['user' => $user, 'officeSeason' => $officeSeason] = activeOfficeSeasonWithPrimaryUser();

    $response = $this->post(route('login.store'), [
        'email' => $officeSeason->delegate_number,
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('the delegate number always authenticates the primary member, never a secondary one', function () {
    ['user' => $primaryUser, 'officeSeason' => $officeSeason] = activeOfficeSeasonWithPrimaryUser();

    // A second member joins the same office. The delegate number is a
    // per-season, per-office shortcut for the ONE primary member — adding
    // another member must not change who it logs in.
    $secondaryUser = User::factory()->hajjOffice()->create();
    OfficeMembership::create([
        'office_id' => $officeSeason->office_id,
        'user_id' => $secondaryUser->id,
        'status' => MembershipStatus::Active,
        'joined_at' => now(),
    ]);

    $this->post(route('login.store'), [
        'email' => $officeSeason->delegate_number,
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($primaryUser);
});

test('wrong password with a valid email fails to authenticate', function () {
    ['user' => $user] = activeOfficeSeasonWithPrimaryUser();

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('wrong password with a valid delegate number fails to authenticate', function () {
    ['officeSeason' => $officeSeason] = activeOfficeSeasonWithPrimaryUser();

    $this->post(route('login.store'), [
        'email' => $officeSeason->delegate_number,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('unknown delegate number fails to authenticate', function () {
    Season::factory()->active()->create();

    $this->post(route('login.store'), [
        'email' => 'OFF-9999',
        'password' => 'password',
    ]);

    $this->assertGuest();
});

test('a suspended office season cannot authenticate with its delegate number', function () {
    ['user' => $user, 'officeSeason' => $officeSeason] = activeOfficeSeasonWithPrimaryUser();
    $officeSeason->update(['status' => OfficeSeasonStatus::Suspended]);

    $this->post(route('login.store'), [
        'email' => $officeSeason->delegate_number,
        'password' => 'password',
    ]);

    $this->assertGuest();

    // The user can still log in by email — suspension of the SEASON participation
    // doesn't delete the account, it only blocks the delegate-number shortcut.
    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);
    $this->assertAuthenticatedAs($user);
});

test('a delegate number is scoped to the currently active season', function () {
    ['officeSeason' => $officeSeason] = activeOfficeSeasonWithPrimaryUser();

    // A second, inactive season reuses the same delegate number — this must
    // never resolve, since delegate numbers are only unique per-season and the
    // login lookup must be scoped to the single active season.
    $otherSeason = Season::factory()->create(['is_active' => false]);
    OfficeSeason::factory()->active()->create([
        'office_id' => $officeSeason->office_id,
        'season_id' => $otherSeason->id,
        'delegate_number' => $officeSeason->delegate_number,
    ]);

    $response = $this->post(route('login.store'), [
        'email' => $officeSeason->delegate_number,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('dashboard', absolute: false));
    $this->assertAuthenticated();
});

test('successful login records an audit log entry', function () {
    ['user' => $user] = activeOfficeSeasonWithPrimaryUser();

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertDatabaseHas('audit_log_entries', [
        'event' => 'user.logged_in',
        'actor_id' => $user->id,
    ]);
});
