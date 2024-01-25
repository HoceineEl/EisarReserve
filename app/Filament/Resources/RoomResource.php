<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomResource\Pages;
use App\Filament\Resources\RoomResource\RelationManagers;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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

    public static function form(Form $form): Form
    {
        return $form
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
                    ]),
                Group::make()
                    ->schema([
                        TextInput::make('capacity')
                            ->label('Capacity')
                            ->numeric()
                            ->default(1),
                        FileUpload::make('image')
                            ->directory('rooms-images')
                            ->image()
                            ->imageEditor()
                    ]),
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
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
        ];
    }
}
