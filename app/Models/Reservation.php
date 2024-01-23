<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable = ['room_id', 'season_id', 'user_id', 'reservation_date', 'checkin_date', 'checkout_date', 'total_base_price', 'status'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function addons()
    {
        return $this->belongsToMany(AddOn::class, 'room_addon_assignments');
    }
}