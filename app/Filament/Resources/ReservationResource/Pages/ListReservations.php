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
        $now = now()->format('Y-m-d H:i:s');
        return [
            'all' => Tab::make(),
            'current' => Tab::make()->label('Current Reservations')
                ->modifyQueryUsing(function (Builder $query) use ($now) {

                    $query->whereDate('checkin_date', '<=', $now)
                        ->whereDate('checkout_date', '>', $now);
                }),
            'completed' => Tab::make()->label('Completed Reservations')
                ->modifyQueryUsing(function (Builder $query) use ($now) {
                    $query->where('checkout_date', '<', $now);
                }),
        ];
    }
    public function getDefaultActiveTab(): string | int | null
    {
        return 'all';
    }
}
