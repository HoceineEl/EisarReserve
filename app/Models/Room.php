<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $fillable = ['building_id', 'type_id', 'number', 'capacity', 'price', 'image'];

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
}
