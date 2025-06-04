<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Ensure the company exists (or create a default one)
        $company = Company::firstOrCreate(
            ['name' => 'Ferix io'],
            [
                'type' => 'admin',
                'email' => 'jawadcharls@gmail.com',
                'address' => 'Kinondoni, Dar es Salaam',
            ]
        );

        // Create the super admin user
        User::updateOrCreate(
            ['email' => 'jawadcharls@gmail.com'],
            [
                'name' => 'Jawad Charles',
                'role' => 'admin',
                'user_auth' => 1,
                'company' => $company->id,
                'password' => Hash::make('11111111'), // Change this!
                'email_verified_at' => now(),
            ]
        );
    }
}