<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Season;
use App\Models\RoomSeasonPrice;

class CreateRoomSeasonPrice extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Clear existing records from the pivot table
        RoomSeasonPrice::truncate();

        // Retrieve all rooms and seasons
        $rooms = Room::all();
        $seasons = Season::all();

        // Seed the pivot table with prices for each combination of room and season
        foreach ($rooms as $room) {
            foreach ($seasons as $season) {
                RoomSeasonPrice::create([
                    'room_id' => $room->id,
                    'season_id' => $season->id,
                    'price' => rand(220, 800),
                ]);
            }
        }
    }
}
