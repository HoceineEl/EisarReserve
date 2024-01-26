<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationResource\Pages;
use App\Filament\Resources\ReservationResource\RelationManagers;
use App\Models\Reservation;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Arr;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-finger-print';
    protected static ?string $navigationGroup = "Reservations";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('room_id')
                    ->label('Room')
                    ->relationship('room', 'number')
                    ->required(),
                Select::make('season_id')
                    ->label('Season')
                    ->relationship('season', 'name')
                    ->required(),
                Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->required(),
                DateTimePicker::make('reservation_date')
                    ->label('Reservation Date')
                    ->required(),
                DateTimePicker::make('checkin_date')
                    ->label('Checkin Date')
                    ->required(),
                DateTimePicker::make('checkout_date')
                    ->label('Checkout Date')
                    ->required(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),
                Select::make('addons')
                    ->label('Addons')
                    ->relationship('addons', 'name')
                    ->multiple()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('room.number')
                    ->label('Room')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('season.name')
                    ->label('Season')
                    ->searchable()
                    ->sortable(),
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
                    ->sortable(),
                TextColumn::make('checkin_date')
                    ->label('Checkin Date')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('checkout_date')
                    ->label('Checkout Date')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('addons.name')
                    ->label('Addons')
                    ->badge()
                    ->color(fn (string $state) => Arr::random([Color::Blue, Color::Amber, Color::Cyan, Color::Fuchsia, Color::Lime, Color::Orange, Color::Green, Color::Sky]))
                    ->searchable()
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
        ];
    }
}
