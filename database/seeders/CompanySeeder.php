<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Company::insert([
            [
                'name' => 'Ferix io',
                'type' => 'admin',
                'email' => 'jawadcharls@gmail.com',
                'address' => 'Kinondoni, Dar es Salaam',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Presis Consultancy',
                'type' => 'vendor',
                'email' => 'giraldinen@presisfinace.co.tz',
                'address' => 'P.O BOX 75391, Dar es Salaam, Avocado Street, Kawe',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Alistair James Company Ltd',
                'type' => 'transporter',
                'email' => 'trading@alistairgroup.com',
                'address' => 'P.O BOX 4543, Dar es Salaam, Kurasini Temeke',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more companies as needed...
        ]);
    }
}