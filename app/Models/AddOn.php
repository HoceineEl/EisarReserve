<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddOn extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'price'];

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'room_addon_assignments');
    }
}
