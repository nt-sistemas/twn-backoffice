<?php

namespace App\Filament\Resources\InvoiceTypeResource\Pages;

use App\Filament\Resources\InvoiceTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageInvoiceTypes extends ManageRecords
{
    protected static string $resource = InvoiceTypeResource::class;
    protected static ?string $title = 'Tipos de Fatura';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Novo Tipo de Fatura')
                ->icon('heroicon-o-plus')
                ->color('primary'),
        ];
    }
}