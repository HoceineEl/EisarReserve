<?php

namespace App\Filament\Pages;

use App\Models\Room;
use Filament\Pages\Page;

class ChoosePage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-bookmark';
    protected static ?string $title = 'Book Now';
    protected static ?string $slug = 'book-now';
    protected ?string $heading = 'Explore Our Rooms';
    protected static string $view = 'filament.pages.choose-page';
}
