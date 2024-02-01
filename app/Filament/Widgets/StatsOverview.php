<?php

namespace App\Filament\Widgets;

use App\Models\AddOn;
use App\Models\Reservation;
use App\Models\ReservationAddonAssignment;
use App\Models\Room;
use App\Models\RoomSeasonPrice;
use App\Models\Season;
use App\Models\User;
use Faker\Core\Color;
use Filament\Support\Colors\Color as ColorsColor;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Termwind\Enums\Color as EnumsColor;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $pendingReservations = Reservation::where('status', 'pending')->count();
        $guests = User::where('role', 'guest')->count();
        $rooms = Room::count();


        return [
            Stat::make('Pending Reservations', $pendingReservations)
                ->description('Total pending reservations')
                ->descriptionIcon('heroicon-o-clipboard-document-check')
                ->chart([600, 300, 100, 400, 200, $pendingReservations])
                ->color('warning')
                ->chartColor('warning'),

            Stat::make('Total Guests', $guests)
                ->description('Increase in guests')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color('success')
                ->chartColor('success')
                ->chart([500, 300, 400, 40, 50, $guests]),

            Stat::make('Total Rooms', $rooms)
                ->description('The total number of rooms')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->chart([100, 500, 1500, 400, 200, $rooms])
                ->color('info')
                ->chartColor('info'),
            Stat::make('Most Popular Addon This Month', ReservationAddonAssignment::getPopularAddonInThisMonth())
                ->description('The most demanded addon this month')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color(ColorsColor::Indigo)
                ->chart([50, 300, 400, 500, 4000]),
            Stat::make('Top Season  ', Reservation::getMostVisitedSeason())
                ->description('This is the most popular season.')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color(ColorsColor::Indigo)
                ->chart([50, 300, 400, 500, 4000]),
            Stat::make('This Month\'s Revenue', Reservation::whereMonth('created_at', now()->month)->where('status', 'paid')->get()->sum('total_price') . '$')
                ->description('This is the total revenue for the month.')
                ->descriptionIcon('heroicon-o-arrow-trending-up')
                ->color(ColorsColor::Indigo)
                ->chart([50, 300, 400, 500, 4000])



        ];
    }

    public static function canView(): bool
    {
        Reservation::getMostVisitedSeason();
        return auth()->user()->role != User::ROLE_GUEST;
    }
    public  function getBestSeason()
    {
        $season = self::with('season')
            ->select('season_id', DB::raw('count(*) as count'))
            ->groupBy('season_id')
            ->orderByDesc('count')
            ->first();
        return $season->season_id;
    }
}
