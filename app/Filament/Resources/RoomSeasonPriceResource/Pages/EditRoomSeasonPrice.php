<?php

namespace App\Filament\Resources\RoomSeasonPriceResource\Pages;

use App\Filament\Resources\RoomSeasonPriceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRoomSeasonPrice extends EditRecord
{
    protected static string $resource = RoomSeasonPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
