<?php

namespace App\Filament\Resources\BookNowResource\Pages;

use App\Filament\Resources\BookNowResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookNows extends ListRecords
{
    protected static string $resource = BookNowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
