<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomResource\Pages;
use App\Filament\Resources\RoomResource\RelationManagers;
use App\Models\Room;
use App\Models\Season;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationGroup = "Building Management";
    protected static ?string $recordTitleAttribute = 'number';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Room Informations')
                        ->schema([
                            Group::make()
                                ->schema([
                                    Select::make('building_id')
                                        ->relationship('building', 'name')
                                        ->label('Room Building')
                                        ->required(),
                                    Select::make('type_id')
                                        ->relationship('type', 'name')
                                        ->label('Room Type')
                                        ->required(),
                                    TextInput::make('number')
                                        ->label('Room Number')
                                        ->numeric()
                                        ->required()
                                ])->columns(3),
                            Group::make()
                                ->schema([
                                    TextInput::make('capacity')
                                        ->label('Capacity')
                                        ->numeric()
                                        ->default(1),
                                    FileUpload::make('image')
                                        ->directory('rooms-images')
                                        ->image()
                                        ->imageEditorMode(2)
                                        ->imageEditor()
                                ])->columns(),
                        ]),
                    Step::make('Room price for each season')
                        ->schema([
                            Repeater::make('prices')
                                ->relationship()
                                ->schema([
                                    Select::make('season_id')
                                        ->relationship('season', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->live()
                                        ->distinct()
                                        ->required(),
                                    TextInput::make('price')
                                        ->numeric()
                                        ->prefix('$')
                                        ->required(),
                                ])
                                ->defaultItems(function () {
                                    return Season::count();
                                })
                                ->columns()
                                ->addable(false)
                                ->deletable(false)
                                ->collapsible()

                        ])
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->square()
                    ->size(50)
                    ->toggleable(),
                TextColumn::make('number')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('capacity')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('building.name')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('type.name')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    DeleteAction::make(),
                ])
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
            RoomResource\RelationManagers\PricesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
        ];
    }
}
