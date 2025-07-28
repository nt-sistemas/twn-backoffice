<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;

use App\Services\NotaFiscalService;
use Filament\Forms\Components\Actions;
use Filament\Resources\Pages\CreateRecord;



class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;


}
