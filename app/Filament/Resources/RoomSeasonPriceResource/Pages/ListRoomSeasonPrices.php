<?php

namespace App\Filament\Resources\RoomSeasonPriceResource\Pages;

use App\Filament\Resources\RoomSeasonPriceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRoomSeasonPrices extends ListRecords
{
    protected static string $resource = RoomSeasonPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
