<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Single', 'Double', 'Suite', 'Studio', 'Apartment',
            'Penthouse', 'Duplex', 'Loft', 'Villa', 'Chalet',
        ];

        foreach ($types as $typeName) {
            Type::create(['name' => $typeName]);
        }
    }
}
