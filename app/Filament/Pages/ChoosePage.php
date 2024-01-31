<?php

namespace App\Filament\Pages;

use App\Filament\Resources\ReservationResource\Pages\CreateReservation;
use App\Models\Room;
use App\Models\User;
use Filament\Pages\Page;

class ChoosePage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-bookmark';
    protected static ?string $title = 'Book Now';
    protected static ?string $slug = 'book-now';
    protected ?string $heading = 'Explore Our Rooms';
    protected static string $view = 'filament.pages.choose-page';

    public static function canAccess(): bool
    {
        return User::find(auth()->id())->isGuest();
    }
}
