<?php

namespace Database\Seeders;

use App\Models\Reservation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class CreateReservationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all room, season, and user IDs
        $roomIds = \App\Models\Room::pluck('id')->toArray();
        $seasonIds = \App\Models\Season::pluck('id')->toArray();
        $userIds = \App\Models\User::pluck('id')->toArray();

        // Generating 10 reservations
        for ($i = 1; $i <= 10; $i++) {
            Reservation::create([
                'room_id' => Arr::random($roomIds),
                'season_id' => Arr::random($seasonIds),
                'user_id' => Arr::random($userIds),
                'reservation_date' => now(),
                'checkin_date' => now()->addDays(rand(1, 10)),
                'checkout_date' => now()->addDays(rand(11, 20)),
                'status' => 'confirmed', // You can change this as needed
            ]);
        }
    }
}
