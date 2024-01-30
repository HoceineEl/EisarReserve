<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
    public static function getBestSeason()
    {
        $season = self::with('season')
            ->select('season_id', DB::raw('count(*) as count'))
            ->groupBy('season_id')
            ->orderByDesc('count')
            ->first();
        return $season->season_id;
    }
}
