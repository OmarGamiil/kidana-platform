<?php

namespace Database\Factories;

use App\Models\ActivationInvite;
use App\Models\OtpCode;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<OtpCode>
 */
class OtpCodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'activation_invite_id' => ActivationInvite::factory(),
            'purpose' => 'activation_phone_verification',
            'channel' => 'sms',
            'recipient' => fake()->numerify('9665########'),
            'code_hash' => Hash::make('123456'),
            'sent_at' => now(),
            'expires_at' => now()->addMinutes(5),
        ];
    }
}
