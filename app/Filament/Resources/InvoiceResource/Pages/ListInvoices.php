<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInvoices extends ListRecords
{
    protected static string $resource = InvoiceResource::class;

    protected static ?string $title = 'Faturas';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Nova Fatura')
                ->icon('heroicon-o-plus')
                ->color('primary'),
        ];
    }
}
