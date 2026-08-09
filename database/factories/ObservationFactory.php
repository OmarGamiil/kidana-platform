<?php

namespace Database\Factories;

use App\Enums\ItemStatus;
use App\Models\Observation;
use App\Models\OfficeSeason;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Observation>
 */
class ObservationFactory extends Factory
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
            'title' => fake()->sentence(4),
            'status' => ItemStatus::Open,
        ];
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ItemStatus::Closed,
        ]);
    }
}
