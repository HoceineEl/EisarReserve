<?php

namespace App\Filament\Resources\BookNowResource\Pages;

use App\Filament\Resources\BookNowResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBookNow extends EditRecord
{
    protected static string $resource = BookNowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
