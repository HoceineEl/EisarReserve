<?php

namespace Database\Seeders;

use App\Models\Building;
use App\Models\Room;
use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class CreateRoomsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function getRandomBuildingId()
    {
        $buildingIds = Building::pluck('id')->toArray();
        $randomBuildingId = Arr::random($buildingIds);
        return $randomBuildingId;
    }

    public function getRandomTypeId()
    {
        $typeIds = Type::pluck('id')->toArray();
        $randomTypeId = Arr::random($typeIds);
        return $randomTypeId;
    }

    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Room::create([
                'building_id' => $this->getRandomBuildingId(),
                'type_id' => $this->getRandomTypeId(),
                'image' => asset('storage/rooms-images/' . rand(1, 4) . '.jpg'),
                'number' => $i * rand(12, 140),
                'capacity' => rand(2, 4),
            ]);
        }
    }
}
