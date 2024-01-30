<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];
    const ROLE_MANAGER = "manager";
    const ROLE_RESERVATOR = "reservator";
    const ROLE_GUEST = "guest";
    const ROLES = [
        self::ROLE_MANAGER => 'Manager',
        self::ROLE_RESERVATOR => 'Reservator',
        self::ROLE_GUEST => 'Guest',
    ];
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isManager() || $this->isReservator() || $this->isGuest();
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    public function isManager(): bool
    {
        return $this->role == self::ROLE_MANAGER;
    }
    public function isReservator(): bool
    {
        return $this->role == self::ROLE_RESERVATOR;
    }
    public function isGuest(): bool
    {
        return $this->role == self::ROLE_GUEST;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
