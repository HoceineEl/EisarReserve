<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ReservationResource;
use App\Models\Reservation;
use App\Models\User;
use Dotenv\Util\Str;
use Filament\Forms\Components\Builder;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Arr;
use SebastianBergmann\Type\NullType;

class GuestReservationsTable extends BaseWidget
{
    protected static ?int $sort = 5;

    protected  int | string | array $columnSpan = 'full';
    public function table(Table $table): Table
    {

        return $table
            ->query(ReservationResource::getEloquentQuery()->where('user_id', auth()->id()))
            ->heading('Your Latest Reservations')
            ->searchable()
            ->defaultSort('reservation_date', 'desc')
            ->columns([
                TextColumn::make('room.number')
                    ->label('Room')
                    ->description(function (Reservation $record) {
                        return $record->room->name;
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_price'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'canceled' => 'danger',
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('reservation_date')
                    ->label('Reservation Date')
                    ->searchable()
                    ->dateTime()
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->sortable(),
                TextColumn::make('checkin_date')
                    ->label('Checkin Date')
                    ->searchable()
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('checkout_date')
                    ->label('Checkout Date')
                    ->searchable()
                    ->dateTime()
                    ->size(TextColumn\TextColumnSize::ExtraSmall)
                    ->sortable(),

                TextColumn::make('addons.name')
                    ->label('Addons')
                    ->badge()
                    ->weight(FontWeight::Light)
                    ->color(fn (string $state) => Arr::random([Color::Blue, Color::Amber, Color::Cyan, Color::Fuchsia, Color::Lime, Color::Orange, Color::Green, Color::Sky]))
                    ->searchable()
            ]);
    }
    public static function canView(): bool
    {
        return auth()->user()->role == User::ROLE_GUEST;
    }
}
