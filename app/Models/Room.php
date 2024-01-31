<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $fillable = ['building_id', 'type_id', 'number', 'capacity', 'image'];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }
    public function prices()
    {
        return $this->hasMany(RoomSeasonPrice::class);
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    public function getNameAttribute()
    {
        return $this->building->name . "-" . $this->number;
    }
    public function avgDuration(): float
    {
        $totalDuration = $this->reservations->sum(function ($reservation) {
            $checkinDate = Carbon::parse($reservation->checkin_date);
            $checkoutDate = Carbon::parse($reservation->checkout_date);
            return $checkinDate->diffInDays($checkoutDate);
        });

        $totalReservations = $this->reservations->count();

        return $totalReservations > 0 ? $totalDuration / $totalReservations : 0;
    }
    public function isReservedNow()
    {
        $inputDateTime = Carbon::parse(now());
        $room = $this;
        $reservations = $room->reservations->filter(function ($reservation) use ($inputDateTime) {
            return $inputDateTime >= Carbon::parse($reservation->checkin_date) && $inputDateTime < Carbon::parse($reservation->checkout_date);
        });
        return $reservations->isNotEmpty();
    }
}
