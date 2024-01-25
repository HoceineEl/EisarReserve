<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomSeasonPrice extends Model
{
    use HasFactory;
    protected $fillable = ['room_id', 'season_id', 'price'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function season()
    {
        return $this->belongsTo(Season::class);
    }
}
