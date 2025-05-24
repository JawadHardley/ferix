<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'), // Default password
            'company' => fake()->randomElement([1, 2]),
            'role' => fake()->randomElement(['user', 'admin', 'transporter']),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Define a specific user named Jawad.
     */
    public function jawad(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Jawad',
            'email' => 'jawad@gmail.com',
            'company' => 'Ferix io',
            'role' => 'admin',
            'password' => Hash::make('11111111'), // Hashed password
        ]);
    }
}