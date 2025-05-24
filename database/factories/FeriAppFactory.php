<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\feriApp;
use App\Models\User;
use App\Models\Company;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\feriApp>
 */
class FeriAppFactory extends Factory
{

    protected $model = feriApp::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Assumes a User factory exists
            'transport_mode' => $this->faker->randomElement(['Air', 'Sea', 'Land']),
            'transporter_company' => Company::factory(),
            'entry_border_drc' => $this->faker->city,
            'truck_details' => $this->faker->bothify('Truck-###-???'),
            'arrival_station' => $this->faker->city,
            'final_destination' => $this->faker->city,
            'importer_name' => $this->faker->name,
            'importer_phone' => $this->faker->phoneNumber,
            'importer_email' => $this->faker->safeEmail,
            'fix_number' => $this->faker->numerify('FIX-#####'),
            'exporter_name' => $this->faker->name,
            'exporter_phone' => $this->faker->phoneNumber,
            'exporter_email' => $this->faker->safeEmail,
            'cf_agent' => $this->faker->company,
            'cf_agent_contact' => $this->faker->phoneNumber,
            'cargo_description' => $this->faker->sentence,
            'hs_code' => $this->faker->numerify('HS-####'),
            'package_type' => $this->faker->word,
            'quantity' => $this->faker->randomNumber(3),
            'company_ref' => $this->faker->uuid,
            'cargo_origin' => $this->faker->country,
            'customs_decl_no' => $this->faker->numerify('DECL-#####'),
            'manifest_no' => $this->faker->numerify('MAN-#####'),
            'occ_bivac' => $this->faker->word,
            'instructions' => $this->faker->sentence,
            'fob_currency' => $this->faker->currencyCode,
            'fob_value' => $this->faker->randomFloat(2, 1000, 100000),
            'incoterm' => $this->faker->randomElement(['FOB', 'CIF', 'EXW']),
            'freight_currency' => $this->faker->currencyCode,
            'freight_value' => $this->faker->randomFloat(2, 500, 50000),
            'insurance_currency' => $this->faker->currencyCode,
            'insurance_value' => $this->faker->randomFloat(2, 100, 10000),
            'additional_fees_currency' => $this->faker->currencyCode,
            'additional_fees_value' => $this->faker->randomFloat(2, 50, 5000),
            'status' => $this->faker->randomElement([1, 2, 3, 4, 5]), // Random status
        ];
    }
}