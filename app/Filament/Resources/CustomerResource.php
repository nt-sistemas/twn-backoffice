<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationLabel = 'Clientes';

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Razão Social')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('document')
                    ->label('CNPJ')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('title')
                    ->label('Cliente')
                    ->maxLength(255),
                Forms\Components\TextInput::make('registration')
                    ->label('Matrícula')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('E-mail Financeiro')
                    ->email()
                    ->maxLength(255),
                Forms\Components\Toggle::make('implementation_fee')
                    ->label('Taxa de Implementação')
                    ->required(),
                Forms\Components\Toggle::make('monthly_fee')
                    ->label('Taxa Mensal')
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->label('Status')
                    ->required(),
                Forms\Components\Textarea::make('observation')
                    ->label('Observação')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Cliente')
                    ->searchable(),

                Tables\Columns\TextColumn::make('document')
                    ->label('CNPJ')
                    ->searchable(),

                Tables\Columns\TextColumn::make('registration')
                    ->label('Matrícula')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail Financeiro')
                    ->searchable(),

                Tables\Columns\IconColumn::make('status')
                    ->icon(fn (string $state): string => match ($state) {
                        'active' => 'heroicon-o-check-circle',
                        'inactive' => 'heroicon-o-trash',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                    }),

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
            'index' => Pages\ManageCustomers::route('/'),
        ];
    }
}
