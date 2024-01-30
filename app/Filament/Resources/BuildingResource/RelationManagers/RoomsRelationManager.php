<?php

namespace App\Filament\Resources\BuildingResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoomsRelationManager extends RelationManager
{
    protected static string $relationship = 'rooms';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        TextInput::make('number')
                            ->label('Room number')
                            ->numeric()
                            ->required(),
                        Select::make('type_id')
                            ->relationship('type', 'name')
                            ->label('Room Type')
                            ->required(),
                    ]),
                Group::make()
                    ->schema([
                        TextInput::make('capacity')
                            ->label('Capacity')
                            ->numeric()
                            ->required()
                            ->default(1),
                        FileUpload::make('image')
                            ->directory('rooms-images')
                            ->image()
                            ->imageEditorMode(2)
                            ->imageEditor()
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('number')
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
                TextColumn::make('type.name')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
