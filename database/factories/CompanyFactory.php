<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\feriApp;
use App\Models\User;
use App\Models\Company;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\feriApp>
 */
class CompanyFactory extends Factory
{

    protected $model = Company::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'type' => $this->faker->randomElement(['Air', 'Sea', 'Land']),
            'email' =>  $this->faker->email,
            'address' => $this->faker->city,
        ];
    }
}