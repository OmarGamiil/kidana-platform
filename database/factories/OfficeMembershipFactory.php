<?php

namespace Database\Factories;

use App\Enums\MembershipStatus;
use App\Models\Office;
use App\Models\OfficeMembership;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OfficeMembership>
 */
class OfficeMembershipFactory extends Factory
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
            'user_id' => User::factory(),
            'status' => MembershipStatus::Active,
            'joined_at' => now(),
        ];
    }
}
