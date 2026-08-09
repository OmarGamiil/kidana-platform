<?php

namespace Database\Factories;

use App\Enums\OfficeSeasonStatus;
use App\Models\Office;
use App\Models\OfficeSeason;
use App\Models\Season;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OfficeSeason>
 */
class OfficeSeasonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'office_id' => Office::factory(),
            'season_id' => Season::factory(),
            'delegate_number' => fake()->unique()->numerify('OFF-####'),
            'status' => OfficeSeasonStatus::PendingActivation,
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OfficeSeasonStatus::Active,
            'official_email' => fake()->companyEmail(),
            'mobile_number' => '9665'.fake()->numerify('########'),
            'mobile_verified_at' => now(),
            'activated_at' => now(),
            'terms_version' => (string) config('activation.current_terms_version'),
            'terms_accepted_at' => now(),
        ]);
    }
}
