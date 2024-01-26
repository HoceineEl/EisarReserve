<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationAddonAssignment extends Model
{
    use HasFactory;
    protected $fillable = ['add_on_id', 'reservation_id'];

    public function addon()
    {
        return $this->belongsTo(AddOn::class, 'add_on_id');
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
    public function getFullNameAttribute()
    {
        $reservation = Reservation::find($this->id);
        $room = $reservation->room;
        $user = $reservation->user;

        $checkinDate = Carbon::parse($reservation->checkin_date);
        $checkoutDate = Carbon::parse($reservation->checkout_date);

        $label = "R: {$room->number},G: {$user->name}, {$checkinDate->toDateString()} -> {$checkoutDate->toDateString()}, D: {$checkinDate->diffInDays($checkoutDate)} days";
        return $label;
    }
    public function getTotalPriceAttribute()
    {
        $checkinDate = Carbon::parse($this->reservation->checkin_date);
        $checkoutDate = Carbon::parse($this->reservation->checkout_date);

        $daysDifference = $checkinDate->diffInDays($checkoutDate);

        return $daysDifference * $this->addon->price;
    }
}
