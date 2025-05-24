<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\feriApp;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the specific user named Jawad
        User::factory()->jawad()->create();

        // Create 10 random users
        User::factory()->count(2)->create();
    }
}