<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable = ['room_id', 'user_id', 'reservation_date', 'checkin_date', 'checkout_date', 'status'];
    const STATUSES = [
        'pending' => "Pending",
        'confirmed' => "Confirmed",
        'canceled' => "Canceled",
    ];
    public function room()
    {
        return $this->belongsTo(Room::class);
    }



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function addons()
    {
        return $this->belongsToMany(AddOn::class, 'reservation_addon_assignments')->withPivot(['reservation_id', 'add_on_id']);
    }
    public function getTotalPriceAttribute()
    {
        $carbonDate = Carbon::parse($this->checkin_date);
        $season = Season::all()
            ->filter(function ($item) use ($carbonDate) {
                $startAt = Carbon::createFromFormat('m/d', $item->start_at);
                $endAt = Carbon::createFromFormat('m/d', $item->end_at);
                // Add a year to startat if startAt is greater 
                if ($startAt->format('md') > $endAt->format('md')) {
                    $startAt->subYear(1);
                    return $carbonDate->isBetween($startAt, $endAt);
                } else {
                    return $startAt->format('md') <= $carbonDate->format('md') && $carbonDate->format('md') <= $endAt->format('md');
                }
            })
            ->last();
        $roomSeasonPrice = $this->room->prices()->where('season_id', $season->id)->first();
        $roomPrice = $roomSeasonPrice ? $roomSeasonPrice->price : 0;

        $addonPrices = $this->addons->sum('price');

        return $roomPrice + $addonPrices;
    }
    public function avgDuration(): float
    {
        $totalDuration = $this->reservations()->sum(function ($reservation) {
            $checkinDate = Carbon::parse($reservation->checkin_date);
            $checkoutDate = Carbon::parse($reservation->checkout_date);
            return $checkinDate->diffInDays($checkoutDate);
        });

        $totalReservations = $this->reservations()->count();

        return $totalReservations > 0 ? $totalDuration / $totalReservations : 0;
    }
}
