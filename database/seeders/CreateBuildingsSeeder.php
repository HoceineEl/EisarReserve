<?php

namespace Database\Seeders;

use App\Models\Building;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateBuildingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Building::create([
            'name' => 'Atlas Towers',
            'address' => '123 Atlas Street, Casablanca',
        ]);

        Building::create([
            'name' => 'Rif Residences',
            'address' => '456 Rif Avenue, Tangier',
        ]);

        Building::create([
            'name' => 'Marrakech Oasis',
            'address' => '789 Oasis Road, Marrakech',
        ]);

        Building::create([
            'name' => 'Sahara Heights',
            'address' => '101 Sahara Boulevard, Erfoud',
        ]);

        Building::create([
            'name' => 'Medina Apartments',
            'address' => '202 Medina Lane, Fes',
        ]);

        Building::create([
            'name' => 'High Atlas Towers',
            'address' => '303 Atlas Highlands, Ouarzazate',
        ]);

        Building::create([
            'name' => 'Casablanca Skyline',
            'address' => '404 Skyline Avenue, Casablanca',
        ]);

        Building::create([
            'name' => 'Essaouira Retreat',
            'address' => '505 Coastal Road, Essaouira',
        ]);

        Building::create([
            'name' => 'Chefchaouen Views',
            'address' => '606 Blue Street, Chefchaouen',
        ]);

        Building::create([
            'name' => 'Fez Heritage Homes',
            'address' => '707 Heritage Lane, Fes',
        ]);
    }
}
