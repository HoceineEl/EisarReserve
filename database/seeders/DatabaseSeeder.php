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
        \App\Models\User::factory(6)->create();

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
