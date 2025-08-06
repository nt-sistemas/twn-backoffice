<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                ->label('Nome ou Razão Social')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('document')
                ->label('CPF/CNPJ')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('title')
                ->label('Nome Fantasia')
                    ->maxLength(255),
                Forms\Components\TextInput::make('registration')
                    ->label('Matrícula HS')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->label('Telefone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->label('Endereço')
                    ->maxLength(255),
                Forms\Components\TextInput::make('neighborhood')
                    ->label('Bairro')
                    ->maxLength(255),
                Forms\Components\TextInput::make('number')
                    ->label('Número')
                    ->maxLength(255),
                Forms\Components\TextInput::make('complement')
                    ->label('Complemento')
                    ->maxLength(255),
                Forms\Components\TextInput::make('city')
                    ->label('Cidade')
                    ->maxLength(255),
                Forms\Components\TextInput::make('state')
                    ->label('Estado')
                    ->maxLength(255),
                Forms\Components\TextInput::make('postal_code')
                    ->label('CEP')
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_active')
                    ->label('Ativo')
                    ->required(),                
                Forms\Components\Toggle::make('implementation_fee')
                    ->label('Taxa de Implementação')
                    ->required(),
                Forms\Components\Toggle::make('monthly_fee')
                    ->label('Taxa Mensal')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Ativo',
                        'inactive' => 'Inativo',
                        'pending' => 'Pendente',
                        'suspended' => 'Suspenso',
                    ])
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
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome ou Razão Social')
                    ->searchable(),
                Tables\Columns\TextColumn::make('document')
                    ->label('CPF/CNPJ')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Nome Fantasia')
                    ->searchable(),
                Tables\Columns\TextColumn::make('registration')
                    ->label('Matrícula HS')
                    ->searchable(),                
                Tables\Columns\TextColumn::make('status'),
                
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
