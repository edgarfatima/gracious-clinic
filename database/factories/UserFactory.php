<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Process\FakeProcessResult;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\City;
use App\Models\Province;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomCityId = City::inRandomOrder()->first()->id;
        $randomProvinceId = Province::inRandomOrder()->first()->id;

        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->email(),
            'email_verified' => fake()->randomElement([false, true]),
            'number' => fake()->unique()->phoneNumber(),
            'street_address' => fake()->address(),
            'city_id' => $randomCityId, // Assign random City ID
            'province_id' => $randomProvinceId, // Assign random Province ID
            'status' => fake()->randomElement(['active', 'inactive']),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }
    /**
     * Indicate that the model's email address should be unverified.
     */
}
