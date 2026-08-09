<?php

namespace Database\Factories;

use App\Models\ActivationInvite;
use App\Models\OfficeSeason;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<ActivationInvite>
 *
 * The raw token is never stored (only its HMAC), so tests that need to
 * actually hit the `/activate/{public_id}/{token}` URL should generate their
 * own token and pass a matching `token_hash` explicitly, e.g.:
 * $token = Str::random(64);
 * ActivationInvite::factory()->create(['token_hash' => hash_hmac('sha256', $token, config('app.key'))]);
 */
class ActivationInviteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'office_season_id' => OfficeSeason::factory(),
            'token_hash' => hash_hmac('sha256', Str::random(64), config('app.key')),
            'expires_at' => now()->addDays((int) config('activation.invite_expiry_days')),
        ];
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->subDay(),
        ]);
    }

    public function revoked(): static
    {
        return $this->state(fn (array $attributes) => [
            'revoked_at' => now(),
        ]);
    }

    public function used(): static
    {
        return $this->state(fn (array $attributes) => [
            'used_at' => now(),
        ]);
    }
}
