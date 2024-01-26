<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationAddonAssignmentResource\Pages;
use App\Filament\Resources\ReservationAddonAssignmentResource\RelationManagers;
use App\Models\AddOn;
use App\Models\Reservation;
use App\Models\ReservationAddonAssignment;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReservationAddonAssignmentResource extends Resource
{
    protected static ?string $model = ReservationAddonAssignment::class;
    protected static ?string $navigationGroup = "Reservations";
    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('reservation_id')
                    ->label('Reservation')
                    ->options(function () {
                        $reservations = Reservation::query()
                            ->pluck('id')
                            ->map(function ($reservationId) {
                                $reservation = Reservation::find($reservationId);
                                $room = $reservation->room;
                                $user = $reservation->user;

                                $checkinDate = Carbon::parse($reservation->checkin_date);
                                $checkoutDate = Carbon::parse($reservation->checkout_date);

                                $label = "Room: {$room->number}, User: {$user->name}, From: {$checkinDate->toDateString()}, To: {$checkoutDate->toDateString()}, Duration: {$checkinDate->diffInDays($checkoutDate)} days";
                                return [$reservationId => $label];
                            });

                        return $reservations;
                    })
                    ->live()
                    ->afterStateUpdated(function ($operation, $state, Set $set, Get $get) {
                        $reservation = Reservation::find($state);
                        if (!$reservation) {
                            $set('quantity', 0);
                            return;
                        }
                        $checkinDate = Carbon::parse($reservation->checkin_date);
                        $checkoutDate = Carbon::parse($reservation->checkout_date);


                        $set('quantity', $checkinDate->diffInDays($checkoutDate));

                        if ($get('addon_id')) {
                            $set('cost', $get('quantity') * AddOn::find($get('addon_id'))->price);
                        }
                    })
                    ->searchable()
                    ->columnSpanFull(),
                Select::make('add_on_id')
                    ->relationship('addon', 'name')
                    ->live()
                    ->afterStateUpdated(function ($operation, $state, Set $set, Get $get) {
                        $reservationId = $get('reservation_id');
                        $reservation = Reservation::find($reservationId);

                        if (!$state || !$reservation) {
                            $set('cost', 0);
                            return;
                        }
                        $checkinDate = Carbon::parse($reservation->checkin_date);
                        $checkoutDate = Carbon::parse($reservation->checkout_date);
                        $addOn = AddOn::find($state);

                        if (!$addOn) {
                            $set('cost', 0);
                            return;
                        }

                        $set('cost', $checkinDate->diffInDays($checkoutDate)
                            * $addOn->price);
                    })->required(),
                TextInput::make('cost')
                    ->prefixIcon('heroicon-o-currency-dollar')
                    ->stripCharacters(',')
                    ->numeric()
                    ->disabled()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label('Reservation')
                    ->searchable(),
                TextColumn::make('addon.name')
                    ->label('AddOn Name')
                    ->searchable(),
                TextColumn::make('total_price')
                    ->money('USD')
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
            'index' => Pages\ListReservationAddonAssignments::route('/'),
            'create' => Pages\CreateReservationAddonAssignment::route('/create'),
            'edit' => Pages\EditReservationAddonAssignment::route('/{record}/edit'),
        ];
    }
}
