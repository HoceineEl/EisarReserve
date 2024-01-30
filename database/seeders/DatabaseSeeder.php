<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(130)->create();

        \App\Models\User::factory()->create([
            'name' => 'Manager',
            'email' => 'manager@example.com',
            'role' => 'manager',
            'password' => bcrypt('manager'),
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Reservator',
            'email' => 'reservator@example.com',
            'role' => 'reservator',
            'password' => bcrypt('reservator'),
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Guest',
            'email' => 'guest@example.com',
            'role' => 'guest',
            'password' => bcrypt('guest'),
        ]);
        $this->call([
            CreateAddOnsSeeder::class,
            CreateSeasonsSeeder::class,
            CreateBuildingsSeeder::class,
            CreateTypesSeeder::class,
            CreateRoomsSeeder::class,
            CreateReservationsSeeder::class,
            CreateReservationAddonsSeeder::class,
            CreateRoomSeasonPrice::class,
        ]);
    }
}
