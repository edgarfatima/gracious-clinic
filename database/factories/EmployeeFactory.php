<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{

    protected static ?string $password;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'number' => Fake()->unique()->phoneNumber(),
            'role' => $this->faker->randomElement(['admin', 'doctor', 'staff']),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'password' => static::$password ??= Hash::make('password')
        ];
    }
}
