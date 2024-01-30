<?php

namespace App\Filament\Resources\ReservationResource\Pages;

use App\Filament\Resources\ReservationResource;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;

use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListReservations extends ListRecords
{
    protected static string $resource = ReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'pending' => Tab::make()->label('Pending Reservations')
                ->modifyQueryUsing(function (Builder $query) {

                    $query->where('status', 'pending');
                }),
            'confirmed' => Tab::make()->label('Confirmed Reservations')
                ->modifyQueryUsing(function (Builder $query) {
                    $query->where('status', 'confirmed');
                }),
            'canceled' => Tab::make()->label('Cancelled Reservations')
                ->modifyQueryUsing(function (Builder $query) {
                    $query->where('status', 'canceled');
                }),
        ];
    }
    public function getDefaultActiveTab(): string | int | null
    {
        return 'all';
    }
}
