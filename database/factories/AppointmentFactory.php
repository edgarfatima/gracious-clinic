<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => \App\Models\User::factory(),
            'appointment_date' => $this->faker->dateTimeBetween('2024-10-01', '2024-10-31')->format('Y-m-d'),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled']),
            'remarks' => $this->faker->sentence,
        ];
    }
}
