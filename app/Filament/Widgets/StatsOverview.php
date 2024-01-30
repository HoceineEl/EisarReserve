<?php

namespace App\Filament\Widgets;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $pendingReservations = Reservation::where('status', 'pending')->count();
        $guests = User::where('role', 'guest')->count();
        $rooms = Room::count();



        return [
            Stat::make('Pending Reservations', $pendingReservations)
                ->description('Total pending reservations')
                ->descriptionIcon('heroicon-o-clipboard-document-check')
                ->chart([10, 4, 3, 5, 2, $pendingReservations])
                ->color('warning'),

            Stat::make('Total Guests', $guests)
                ->description('Increase in guests')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->descriptionColor('success')
                ->chart([10, 4, 3, 5, 2, $guests])
                ->chartColor('success'),

            Stat::make('Total Rooms', $rooms)
                ->description('The total number of rooms')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->chart([30, 14, 7, 16, 2, $rooms])
                ->color('info'),


        ];
    }
}
