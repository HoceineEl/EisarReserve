<?php

namespace Database\Seeders;

use App\Models\AddOn;
use App\Models\Reservation;
use App\Models\ReservationAddonAssignment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class CreateReservationAddonsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function checkAddon($addon_id, $reservation_id)
    {
        return !ReservationAddonAssignment::where('reservation_id', $reservation_id)
            ->where('add_on_id', $addon_id)
            ->exists();
    }

    public function run(): void
    {
        $reservations = Reservation::pluck('id')->toArray();
        $addons = AddOn::pluck('id')->toArray();
        foreach ($reservations  as $reservation) {
            for ($i = 0; $i < rand(0, AddOn::count()); $i++) {
                $addon = Arr::random($addons);
                if ($this->checkAddon($addon, $reservation)) {
                    ReservationAddonAssignment::create([
                        'add_on_id' => $addon,
                        'reservation_id' => $reservation,
                    ]);
                }
            }
        }
    }
}
