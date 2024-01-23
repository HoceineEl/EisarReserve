<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomAddonAssignment extends Model
{
    use HasFactory;
    protected $fillable = ['addon_id', 'reservation_id', 'quantity'];

    public function addon()
    {
        return $this->belongsTo(AddOn::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}