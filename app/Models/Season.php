<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'start_at', 'end_at'];
    public function prices()
    {
        return $this->hasMany(RoomSeasonPrice::class);
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
