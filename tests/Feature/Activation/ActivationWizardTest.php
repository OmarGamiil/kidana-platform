<?php

use App\Enums\OfficeSeasonStatus;
use App\Models\ActivationInvite;
use App\Models\Office;
use App\Models\OfficeSeason;
use App\Models\OtpCode;
use App\Models\Season;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;

/**
 * Creates a PendingActivation office/season + invite, and returns both the
 * model and the raw (unhashed) token needed to visit the activation URL —
 * the raw token is never persisted, so it must be generated here.
 *
 * @return array{invite: ActivationInvite, token: string}
 */
function pendingActivationInvite(): array
{
    Season::factory()->active()->create();
    $officeSeason = OfficeSeason::factory()->create([
        'office_id' => Office::factory(),
        'status' => OfficeSeasonStatus::PendingActivation,
    ]);

    $token = Str::random(64);

    $invite = ActivationInvite::create([
        'office_season_id' => $officeSeason->id,
        'token_hash' => hash_hmac('sha256', $token, config('app.key')),
        'expires_at' => now()->addDays(7),
    ]);

    return ['invite' => $invite, 'token' => $token];
}

function startActivationUrl(ActivationInvite $invite, string $token): string
{
    return route('activation.start', ['activationInvite' => $invite->public_id, 'token' => $token]);
}

/**
 * Walks the wizard through step 1 (account) and returns the OTP code that
 * was issued, read via the LocalOtpSender's session-flashed debug value —
 * never by reversing the stored hash, which is by design impossible.
 */
function completeAccountStep(): string
{
    ['invite' => $invite, 'token' => $token] = pendingActivationInvite();

    test()->get(startActivationUrl($invite, $token));

    test()->post(route('activation.account.store'), [
        'mobile_number' => '966500000000',
        'official_email' => 'office@example.com',
        'terms_accepted' => true,
    ]);

    return session('otp_debug_code');
}

// --- Step 1: start / account -------------------------------------------------

test('a valid invite redirects to the account step and establishes a session', function () {
    ['invite' => $invite, 'token' => $token] = pendingActivationInvite();

    $response = test()->get(startActivationUrl($invite, $token));

    $response->assertRedirect(route('activation.account.show'));
    expect(session('activation_invite_id'))->toBe($invite->id);
});

test('an expired invite is rejected', function () {
    ['invite' => $invite, 'token' => $token] = pendingActivationInvite();
    $invite->update(['expires_at' => now()->subDay()]);

    $response = test()->get(startActivationUrl($invite, $token));

    $response->assertRedirect(route('home'));
    expect(session('activation_invite_id'))->toBeNull();
});

test('a revoked invite is rejected', function () {
    ['invite' => $invite, 'token' => $token] = pendingActivationInvite();
    $invite->update(['revoked_at' => now()]);

    test()->get(startActivationUrl($invite, $token))->assertRedirect(route('home'));
});

test('an already-used invite is rejected', function () {
    ['invite' => $invite, 'token' => $token] = pendingActivationInvite();
    $invite->update(['used_at' => now()]);

    test()->get(startActivationUrl($invite, $token))->assertRedirect(route('home'));
});

test('a token that does not match the invite is rejected', function () {
    ['invite' => $invite] = pendingActivationInvite();

    test()->get(startActivationUrl($invite, 'the-wrong-token'))->assertRedirect(route('home'));
    expect(session('activation_invite_id'))->toBeNull();
});

test('the account step cannot be reached without an activation session', function () {
    test()->get(route('activation.account.show'))->assertNotFound();
});

test('step 1 saves office mobile/email and issues an otp code', function () {
    ['invite' => $invite, 'token' => $token] = pendingActivationInvite();
    test()->get(startActivationUrl($invite, $token));

    $response = test()->post(route('activation.account.store'), [
        'mobile_number' => '966500000000',
        'official_email' => 'office@example.com',
        'terms_accepted' => true,
    ]);

    $response->assertRedirect(route('activation.verify.show'));
    expect($invite->officeSeason->fresh()->mobile_number)->toBe('966500000000');
    expect(OtpCode::where('activation_invite_id', $invite->id)->exists())->toBeTrue();
});

test('step 1 rejects an official_email already used by an existing user', function () {
    User::factory()->create(['email' => 'taken@example.com']);
    ['invite' => $invite, 'token' => $token] = pendingActivationInvite();
    test()->get(startActivationUrl($invite, $token));

    $response = test()->post(route('activation.account.store'), [
        'mobile_number' => '966500000000',
        'official_email' => 'taken@example.com',
        'terms_accepted' => true,
    ]);

    $response->assertSessionHasErrors('official_email');
});

test('step 1 rejects unaccepted terms', function () {
    ['invite' => $invite, 'token' => $token] = pendingActivationInvite();
    test()->get(startActivationUrl($invite, $token));

    $response = test()->post(route('activation.account.store'), [
        'mobile_number' => '966500000000',
        'official_email' => 'office@example.com',
        'terms_accepted' => false,
    ]);

    $response->assertSessionHasErrors('terms_accepted');
});

// --- Step 2: verify -----------------------------------------------------------

test('the verify step rejects being reached before step 1 is completed', function () {
    ['invite' => $invite, 'token' => $token] = pendingActivationInvite();
    test()->get(startActivationUrl($invite, $token));

    test()->get(route('activation.verify.show'))->assertRedirect(route('home'));
});

test('step 2 verifies a correct otp code and advances to the password step', function () {
    $code = completeAccountStep();

    $response = test()->post(route('activation.verify.store'), ['code' => $code]);

    $response->assertRedirect(route('activation.password.show'));
});

test('step 2 rejects an incorrect otp code', function () {
    completeAccountStep();

    $response = test()->post(route('activation.verify.store'), ['code' => '000000']);

    $response->assertRedirect(route('home'));
});

test('step 2 rejects an otp code after the max attempts are exceeded', function () {
    completeAccountStep();

    for ($i = 0; $i < (int) config('activation.otp_max_attempts'); $i++) {
        test()->post(route('activation.verify.store'), ['code' => '000000']);
    }

    $response = test()->post(route('activation.verify.store'), ['code' => '000000']);
    $response->assertRedirect(route('home'));

    // The otp row should be invalidated once attempts are exhausted, not just
    // sitting there available to keep guessing against.
    $otp = OtpCode::latest('id')->first();
    expect($otp->invalidated_at)->not->toBeNull();
});

test('a resend invalidates the prior code and issues a new one', function () {
    $firstCode = completeAccountStep();

    // Force past the cooldown so resend is allowed in this test.
    OtpCode::latest('id')->first()->update(['sent_at' => now()->subMinutes(10)]);

    test()->post(route('activation.verify.resend'));

    $secondCode = session('otp_debug_code');

    expect($secondCode)->not->toBe($firstCode);

    // The old code must no longer verify, even though it was correct a moment ago.
    $response = test()->post(route('activation.verify.store'), ['code' => $firstCode]);
    $response->assertRedirect(route('home'));
});

test('a resend before the cooldown has elapsed is rejected', function () {
    completeAccountStep();

    $response = test()->post(route('activation.verify.resend'));

    $response->assertRedirect(route('home'));
});

// --- Step 3: password / completion --------------------------------------------

test('the password step rejects being reached before otp verification', function () {
    completeAccountStep();

    test()->get(route('activation.password.show'))->assertRedirect(route('home'));
});

test('the password step exposes the active server password policy', function () {
    $code = completeAccountStep();
    test()->post(route('activation.verify.store'), ['code' => $code]);

    test()->get(route('activation.password.show'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('office/activation/Password')
            ->where('passwordRules', 'minlength: 8;'));
});

test('full happy path: account -> otp -> password activates the office and logs the user in', function () {
    $code = completeAccountStep();
    test()->post(route('activation.verify.store'), ['code' => $code]);

    $officeSeason = OfficeSeason::query()->latest('id')->first();
    $sessionIdBeforeLogin = session()->getId();

    $response = test()->post(route('activation.password.store'), [
        'password' => 'Sup3r-Secret!',
        'password_confirmation' => 'Sup3r-Secret!',
    ]);

    $response->assertRedirect(route('dashboard', absolute: false));
    test()->assertAuthenticated();

    expect(session()->getId())->not->toBe($sessionIdBeforeLogin);

    $officeSeason->refresh();
    expect($officeSeason->status)->toBe(OfficeSeasonStatus::Active);
    expect($officeSeason->primary_membership_id)->not->toBeNull();

    $invite = ActivationInvite::where('office_season_id', $officeSeason->id)->first();
    expect($invite->used_at)->not->toBeNull();

    test()->assertDatabaseHas('audit_log_entries', ['event' => 'activation.completed']);
});

test('completing activation twice is rejected the second time', function () {
    $code = completeAccountStep();
    test()->post(route('activation.verify.store'), ['code' => $code]);

    test()->post(route('activation.password.store'), [
        'password' => 'Sup3r-Secret!',
        'password_confirmation' => 'Sup3r-Secret!',
    ]);

    test()->assertAuthenticated();
    $userCountAfterFirst = User::count();

    // Simulate a second submit by re-establishing the same activation session
    // (a real double-submit would race on the same request, but this proves
    // the invite-level guard rejects a repeat completion attempt).
    $invite = ActivationInvite::latest('id')->first();
    session()->put('activation_invite_id', $invite->id);

    test()->post(route('activation.password.store'), [
        'password' => 'Another-Secret!',
        'password_confirmation' => 'Another-Secret!',
    ]);

    expect(User::count())->toBe($userCountAfterFirst);
});

test('the local otp driver never writes to the application log', function () {
    Log::spy();

    completeAccountStep();

    // LocalOtpSender surfaces the code only via a session-flashed debug value
    // (read by the tests above) — it must never touch a log channel at all.
    Log::shouldNotHaveReceived('info');
    Log::shouldNotHaveReceived('debug');
});
