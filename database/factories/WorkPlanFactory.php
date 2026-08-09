<?php

namespace Database\Factories;

use App\Models\OfficeSeason;
use App\Models\WorkPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkPlan>
 */
class WorkPlanFactory extends Factory
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
            'deadline_at' => now()->addWeeks(3),
        ];
    }
}
