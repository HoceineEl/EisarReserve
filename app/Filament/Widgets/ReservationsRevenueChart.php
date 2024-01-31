<?php

namespace App\Filament\Widgets;

use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class ReservationsRevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';
    protected static ?int $sort = 3;
    protected static string $color = 'primary';
    public ?string $filter = '2024';

    protected function getData(): array
    {
        $data = $this->getTotalRevenuePerMonth($this->filter);
        return [
            'datasets' => [
                [
                    'label' => "Total Revenue per month in " . $this->filter,
                    'data' => $data['TotalRevenuePerMonth'],
                    'backgroundColor' => 'primary',
                    'borderColor' => '#9BD0F5',
                ]
            ],
            'labels' => $data['months'],

        ];
    }
    public static function canView(): bool
    {
        return auth()->user()->role != User::ROLE_GUEST;
    }
    protected function getType(): string
    {
        return 'bar';
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

    public static function getTotalRevenuePerMonth($year)
    {
        // dd($year, now()->year);
        $year = Carbon::create($year);
        $currentYear = $year->year;
        $reservations = Reservation::whereYear('created_at', $currentYear)->where('status', 'confirmed')->get();

        $TotalRevenuePerMonth = $reservations->groupBy(function ($reservation) {
            return Carbon::parse($reservation->created_at)->format('F');
        })->map(function ($group) {
            return $group->sum('total_price');
        });
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December',
        ];
        $TotalRevenuePerMonth = array_merge(array_fill_keys($months, 0), $TotalRevenuePerMonth->toArray());


        return [
            'TotalRevenuePerMonth' => $TotalRevenuePerMonth,
            'months' => $months,
        ];
    }
}
