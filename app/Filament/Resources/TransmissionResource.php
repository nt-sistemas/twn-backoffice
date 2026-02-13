<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransmissionResource\Pages;
use App\Models\Transmission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TransmissionResource extends Resource
{
    protected static ?string $model = Transmission::class;

    protected static ?string $navigationLabel = 'Nota Fiscal Eletrônica';

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('invoice_id')
                    ->label('Tipo de Fatura')
                    ->relationship('invoice', 'reference')
                    ->required()
                    ->preload(),
                Forms\Components\Select::make('customer_id')
                    ->label('Cliente')
                    ->relationship('customer', 'name')
                    ->preload()
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->label('Valor da Nota Fiscal')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\Textarea::make('response_message')
                    ->label('Resposta WebService')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice.invoiceType.name')
                    ->label('Faturas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice.reference')
                    ->label('Referência')
                    ->searchable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Cliente')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Valor')
                    ->money('BRL')

                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->icon(fn (string $state): string => match ($state) {
                        'transmitting' => 'heroicon-o-clock',
                        'transmitted' => 'heroicon-o-check-circle',
                        'error' => 'heroicon-o-exclamation-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'transmitting' => 'warning',
                        'transmitted' => 'success',
                        'error' => 'danger',
                    })
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTransmissions::route('/'),
        ];
    }
}
