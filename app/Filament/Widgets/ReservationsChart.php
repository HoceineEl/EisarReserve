<?php

namespace App\Filament\Widgets;

use App\Models\AddOn;
use App\Models\Reservation;
use App\Models\ReservationAddonAssignment;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class ReservationsChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';
    protected static ?int $sort = 3;
    protected static string $color = 'success';
    public ?string $filter = '2024';

    protected function getData(): array
    {
        $data = $this->getReservationsPerMonth($this->filter);
        return [
            'datasets' => [
                [
                    'label' => "Reservations per month in " . $this->filter,
                    'data' => $data['reservationsPerMonth'],
                ]
            ],
            'labels' => $data['months'],

        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
    protected function getFilters(): ?array
    {
        $years = [];
        for ($i = 0; $i <= 10; $i++) {
            $year = Carbon::now()->subYears($i)->year;
            $years[$year] = "$year";
        }
        return $years;
    }
    public static function getReservationsPerMonth($year)
    {
        // dd($year, now()->year);
        $year = Carbon::create($year);
        $currentYear = $year->year;
        $reservations = Reservation::whereYear('created_at', $currentYear)->get();

        $reservationsPerMonth = $reservations->groupBy(function ($reservation) {
            return Carbon::parse($reservation->created_at)->format('F');
        })->map->count();
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December',
        ];
        $reservationsPerMonth = array_merge(array_fill_keys($months, 0), $reservationsPerMonth->toArray());

        // dd($reservationsPerMonth);
        return [
            'reservationsPerMonth' => $reservationsPerMonth,
            'months' => $months,
        ];
    }
    public static function canView(): bool
    {
        return auth()->user()->role != User::ROLE_GUEST;
    }
}
