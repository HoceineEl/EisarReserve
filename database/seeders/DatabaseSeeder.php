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
        \App\Models\User::factory(20)->create();

        \App\Models\User::factory()->create([
            'name' => 'Hoceine',
            'email' => 'contact@hoceine.com',
            'role' => 'manager',
            'password' => bcrypt('admin'),
        ]);
        $this->call([
            CreateAddOnsSeeder::class,
            CreateSeasonsSeeder::class,
            CreateBuildingsSeeder::class,
            CreateTypesSeeder::class,
            CreateRoomsSeeder::class,
            CreateReservationsSeeder::class,
            CreateReservationAddonsSeeder::class,
        ]);
    }
}
