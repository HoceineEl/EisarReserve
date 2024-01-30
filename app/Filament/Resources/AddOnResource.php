<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AddOnResource\Pages;
use App\Filament\Resources\AddOnResource\RelationManagers;
use App\Models\AddOn;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use League\CommonMark\Input\MarkdownInput;

class AddOnResource extends Resource
{
    protected static ?string $model = AddOn::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = "AddOns";
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Name & Price')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Add-On Name')
                                    ->required(),
                                TextInput::make('price')
                                    ->prefixIcon('heroicon-o-currency-dollar')
                                    ->required()
                                    ->stripCharacters(',')
                                    ->numeric()
                            ]),
                    ]),
                Group::make()
                    ->schema([
                        Section::make('Description')
                            ->schema([
                                MarkdownEditor::make('description')
                                    ->label('Description')
                                    ->required(),
                            ]),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->description(function (AddOn $record) {
                        return $record->description;
                    }),
                TextColumn::make('price')
                    ->searchable()
                    ->suffix("$"),
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
            'index' => Pages\ListAddOns::route('/'),
            'create' => Pages\CreateAddOn::route('/create'),
            'edit' => Pages\EditAddOn::route('/{record}/edit'),
        ];
    }
}
