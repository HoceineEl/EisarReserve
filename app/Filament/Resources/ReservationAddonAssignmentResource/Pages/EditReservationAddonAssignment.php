<?php

namespace App\Filament\Resources\ReservationAddonAssignmentResource\Pages;

use App\Filament\Resources\ReservationAddonAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReservationAddonAssignment extends EditRecord
{
    protected static string $resource = ReservationAddonAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
