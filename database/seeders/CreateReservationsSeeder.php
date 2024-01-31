<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
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
        $userIds = \App\Models\User::where('role', User::ROLE_GUEST)->pluck('id')->toArray();

        // Generating 10 reservations
        for ($i = 1; $i <= 300; $i++) {
            $roomId = Arr::random($roomIds);
            $userId = Arr::random($userIds);
            $checkinDate = now()->subDays(rand(0, 300));
            $reservationDate = $checkinDate->subDays(rand(1, 5));
            $checkoutDate = now()->addDays(rand(-299, 15));

            // Check if the selected room is available for the given date range
            $isRoomAvailable = $this->isRoomAvailable($roomId,);

            if ($isRoomAvailable) {
                Reservation::create([
                    'room_id' => $roomId,
                    'user_id' => $userId,
                    'created_at' => $reservationDate,
                    'checkin_date' => $checkinDate,
                    'checkout_date' => $checkoutDate,
                    'status' => Arr::random(array_keys(Reservation::STATUSES)),
                ]);
            }
        }
    }

    /**
     * Check if the room is available for the given date range.
     *
     * @param int $roomId
     * @param \Carbon\Carbon $checkinDate
     * @param \Carbon\Carbon $checkoutDate
     * @return bool
     */
    private function isRoomAvailable(int $roomId): bool
    {
        return Room::find($roomId)->reservations->isEmpty();
    }
}
