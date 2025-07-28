<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceTypeResource\Pages;
use App\Filament\Resources\InvoiceTypeResource\RelationManagers;
use App\Models\InvoiceType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceTypeResource extends Resource
{
    protected static ?string $model = InvoiceType::class;
    protected static ?string $navigationGroup = 'Ajustes';

    protected static ?string $navigationLabel = 'Tipos de Fatura';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->label('Descrição')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->label('Nome')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descrição')
                    ->searchable(),

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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageInvoiceTypes::route('/'),
        ];
    }
}
