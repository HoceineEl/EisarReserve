<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomSeasonPriceResource\Pages;
use App\Filament\Resources\RoomSeasonPriceResource\RelationManagers;
use App\Models\RoomSeasonPrice;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoomSeasonPriceResource extends Resource
{
    protected static ?string $model = RoomSeasonPrice::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('room_id')
                    ->relationship('room', 'id')
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->building->name} -{$record->number}"),
                Select::make('season_id')
                    ->relationship('season', 'name'),
                TextInput::make('price')
                    ->numeric()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('room.name'),
                TextColumn::make('season.name'),
                TextColumn::make('price')
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
            'index' => Pages\ListRoomSeasonPrices::route('/'),
            'create' => Pages\CreateRoomSeasonPrice::route('/create'),
            'edit' => Pages\EditRoomSeasonPrice::route('/{record}/edit'),
        ];
    }
}
