<?php

namespace Database\Seeders;

use App\Models\AddOn;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateAddOnsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $addons = [
            ['name' => 'WiFi', 'description' => 'High-speed internet access', 'price' => 5.99],
            ['name' => 'Breakfast', 'description' => 'Delicious morning meal', 'price' => 9.99],
            ['name' => 'Parking', 'description' => 'Secure parking space', 'price' => 7.99],
            ['name' => 'Gym Access', 'description' => 'Fitness center entry', 'price' => 12.99],
            ['name' => 'Airport Shuttle', 'description' => 'Transport to/from airport', 'price' => 15.99],
            ['name' => 'Room Service', 'description' => 'Convenient in-room dining', 'price' => 8.99],
            ['name' => 'Laundry Service', 'description' => 'Professional laundry cleaning', 'price' => 14.99],
            ['name' => 'Late Checkout', 'description' => 'Extended stay option', 'price' => 10.99],
            ['name' => 'Spa Access', 'description' => 'Relaxing spa facilities', 'price' => 19.99],
            ['name' => 'Bike Rental', 'description' => 'Explore the surroundings on wheels', 'price' => 6.99],
        ];

        foreach ($addons as $addonData) {
            AddOn::create($addonData);
        }
    }
}
