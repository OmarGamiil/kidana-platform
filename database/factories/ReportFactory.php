<?php

namespace Database\Factories;

use App\Enums\ReportStatus;
use App\Models\OfficeSeason;
use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Report>
 */
class ReportFactory extends Factory
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
            'status' => ReportStatus::Pending,
        ];
    }
}
