<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeasonResource\Pages;
use App\Filament\Resources\SeasonResource\RelationManagers;
use App\Models\Season;
use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
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
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SeasonResource extends Resource
{
    protected static ?string $model = Season::class;
    protected static ?string $navigationGroup = "Pricing and Seasons";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $months = [
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Name')
                            ->schema([
                                TextInput::make('name')->required()
                                    ->label(''),
                            ]),
                        Section::make('Start Date')
                            ->schema([
                                TextInput::make('start_day')
                                    ->numeric()
                                    ->maxValue(31)
                                    ->minValue(1)
                                    ->default(1)
                                    ->required()
                                    ->label('Start Day'),
                                Select::make('start_month')
                                    ->options($months)
                                    ->default(1)
                                    ->live()
                                    ->required()
                                    ->afterStateUpdated(function (string $operation, $state, Set $set, Get $get) {
                                        $set('start_at', $state . '/' . $get('start_day'));
                                    })
                                    ->label('Start Month'),
                            ])
                            ->columns(2),
                        Section::make('End Date')
                            ->schema([
                                TextInput::make('end_day')
                                    ->numeric()
                                    ->maxValue(31)
                                    ->minValue(1)
                                    ->required()
                                    ->default(1)
                                    ->label('End Day'),
                                Select::make('end_month')
                                    ->options($months)
                                    ->default(1)
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(function (string $operation, $state, Set $set, Get $get) {
                                        $set('end_at', $state . '/' . $get('end_day'));
                                    })
                                    ->label('End Month'),
                            ])
                            ->columns(2),

                    ]),
                Group::make()
                    ->schema([
                        Section::make('Dates')
                            ->schema([
                                TextInput::make('start_at')
                                    ->required()
                                    ->dehydrated()
                                    ->disabled(),
                                TextInput::make('end_at')
                                    ->required()
                                    ->dehydrated()
                                    ->disabled(),
                            ])
                    ])


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('start_at')
                    ->formatStateUsing(fn (string $state): string => __(Carbon::createFromFormat('m/d', $state)->format('F jS')))
                    ->label("Start Date"),
                TextColumn::make('end_at')
                    ->formatStateUsing(fn (string $state): string => __(Carbon::createFromFormat('m/d', $state)->format('F jS')))
                    ->label("End Date"),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListSeasons::route('/'),
            'create' => Pages\CreateSeason::route('/create'),
            'edit' => Pages\EditSeason::route('/{record}/edit'),
        ];
    }
}
