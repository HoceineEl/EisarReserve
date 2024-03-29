<?php

namespace Database\Seeders;

use App\Models\Season;
use Illuminate\Database\Seeder;

class CreateSeasonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Season::create([
            'name' => 'Summer',
            'start_at' => '06/21',
            'end_at' => '09/22',
        ]);

        Season::create([
            'name' => 'Winter',
            'start_at' => '12/21',
            'end_at' => '03/20',
        ]);

        Season::create([
            'name' => 'Spring',
            'start_at' => '03/21',
            'end_at' => '06/20',
        ]);

        Season::create([
            'name' => 'Autumn',
            'start_at' => '09/23',
            'end_at' => '12/20',
        ]);
    }
}
