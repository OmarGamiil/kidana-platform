<?php

namespace Database\Factories;

use App\Enums\ReadinessTrackType;
use App\Models\City;
use App\Models\OfficeSeason;
use App\Models\Station;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Station>
 */
class StationFactory extends Factory
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
            'track' => fake()->randomElement(ReadinessTrackType::cases()),
            'city_id' => City::factory(),
            'name' => fake()->sentence(3),
            'is_completed' => false,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_completed' => true,
            'completed_at' => now(),
        ]);
    }

    public function dueThisWeek(): static
    {
        return $this->state(fn (array $attributes) => [
            'due_at' => now()->addDays(fake()->numberBetween(1, 6)),
        ]);
    }

    public function track(ReadinessTrackType $track): static
    {
        return $this->state(fn (array $attributes) => [
            'track' => $track,
        ]);
    }
}
