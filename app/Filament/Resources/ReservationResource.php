<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationResource\Pages;
use App\Models\AddOn;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomSeasonPrice;
use App\Models\Season;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request as FacadesRequest;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-finger-print';
    protected static ?string $navigationGroup = "Reservations";
    public static function getNavigationBadgeColor(): string|array|null
    {
        return Color::Yellow;
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }
    public static function form(Form $form): Form
    {
        $currentDate = now()->toDateString();

        $room = request('room');

        return $form
            ->schema([
                Section::make('')
                    ->schema([
                        Select::make('room_id')
                            ->label('Room')
                            ->searchable()
                            ->default($room)
                            ->native(false)
                            ->live()
                            ->reactive()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::calculateSeasonDetails($get, $set);
                            })
                            ->options(function () {
                                $availableRooms = Room::with('building')
                                    ->get()
                                    ->map(function ($room) {
                                        return [$room->id => $room->name];
                                    });

                                return $availableRooms;
                            })
                            ->required(),


                        Select::make('user_id')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                                Forms\Components\TextInput::make('email')
                                    ->required()
                                    ->email(),
                            ])
                            ->prefixIcon('heroicon-m-users')
                            ->searchable()
                            ->preload()
                            ->editOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                                Forms\Components\TextInput::make('email')
                                    ->required()
                                    ->email(),
                            ])
                            ->required(),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'canceled' => 'Cancelled',
                            ])
                            ->hiddenOn(auth()->user()->role == User::ROLE_GUEST)
                            ->required(),
                        TextInput::make('costs')
                            ->label("All that costs ")
                            ->disabled()
                            ->prefix('$')
                            ->default(0.00)
                            ->dehydrated(),
                    ])->columns(),
                Section::make('Dates')
                    ->schema([
                        DateTimePicker::make('created_at')
                            ->label('Reservation Date')
                            ->seconds(false)
                            ->default(now())
                            ->required(),
                        DateTimePicker::make('checkin_date')
                            ->label('Checkin Date')
                            ->rules([
                                fn (Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                    $inputDateTime = Carbon::parse($get('checkin_date'));
                                    $room = Room::find($get('room_id'));


                                    if ($room) {
                                        $reservations = $room->reservations->where('status', '!=', 'canceled')->filter(function ($reservation) use ($inputDateTime) {
                                            // Check if the selected check-in time falls within the range of any existing reservations
                                            return $inputDateTime >= Carbon::parse($reservation->checkin_date) && $inputDateTime < Carbon::parse($reservation->checkout_date);
                                        });

                                        if ($reservations->isNotEmpty()) {
                                            $fail("The selected checkin date is not available for the room.");
                                        }
                                    }
                                }
                            ])
                            ->afterOrEqual('created_at')
                            ->reactive()
                            ->live()
                            ->seconds(false)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::calculateSeasonDetails($get, $set);
                            })
                            ->required(),

                        DateTimePicker::make('checkout_date')
                            ->label('Checkout Date')
                            ->afterOrEqual('checkin_date')
                            ->seconds(false)
                            ->required(),
                    ])->columns(3),
                Section::make('Addons Related')
                    ->schema([
                        Select::make('addons')
                            ->label('Addons')
                            ->relationship('addons', 'name')
                            ->multiple()
                            ->live()
                            ->preload()
                            ->afterStateHydrated(function (Get $get, Set $set) {
                                self::calculateSeasonDetails($get, $set);
                            })
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::calculateSeasonDetails($get, $set);
                            }),
                        TextInput::make('addons_cost')
                            ->label('All addons costs')
                            ->default(0)
                            ->disabled(),
                    ])->columns(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('room.number')
                    ->label('Room')
                    ->description(function (Reservation $record) {
                        return $record->room->name;
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->description(function (Reservation $record) {
                        return $record->user->email;
                    })
                    ->sortable(),
                TextColumn::make('total_price'),
                SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'canceled' => 'Canceled',
                    ])
                    ->selectablePlaceholder(false)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
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
                    ->color(fn () => Arr::random(Color::all()))
                    ->searchable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultPaginationPageOption(25)
            ->reorderable()
            ->searchable()
            ->striped();
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
    /**
     * Calculate season details and update the state.
     *
     * @param Get $get
     * @param Set $set
     */
    public static function calculateSeasonDetails(Get $get, Set $set)
    {
        $addons = $get('addons');
        $addonsPrice = 0;
        foreach ($addons as $id) {
            $addon = AddOn::find($id);
            $addonsPrice += $addon->price;
        }

        $date = $get('checkin_date');
        if ($date) {

            $carbonDate = Carbon::parse($date);

            $season = Reservation::getSeason($carbonDate);

            if ($season) {
                $set('season_id', $season->id);
                $room = $get('room_id');
                $totalPrice = 0;
                $roomPrice = RoomSeasonPrice::where('room_id', $room)->where('season_id', $season->id)->first()?->price;
                $totalPrice = $roomPrice + $addonsPrice;
                $set('costs', $totalPrice);
            } else
                $set('costs', 0);
        }

        $set('addons_cost', $addonsPrice);
    }
    public static function canAccess(): bool
    {
        return !User::find(auth()->id())->isGuest();
    }
}
