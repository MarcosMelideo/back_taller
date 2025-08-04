<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Vehicle;
use App\Models\Payment;
use App\Models\Paymentitem;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        Client::factory(50)->create();
        Vehicle::factory(50)->create();
        Payment::factory(50)->create();
        Paymentitem::factory(50)->create();
    }
}
