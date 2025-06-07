<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('rates')->insert([
            ['currency' => 'EUR to USD', 'amount' => 1.14, 'created_at' => now(), 'updated_at' => now()],
            ['currency' => 'USD to TZS', 'amount' => 2725, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}