<?php

namespace Database\Factories;

use App\Enums\AuditEvent;
use App\Models\AuditLogEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AuditLogEntry>
 */
class AuditLogEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'event' => AuditEvent::UserLoggedIn,
        ];
    }
}
