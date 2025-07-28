<?php

namespace App\Filament\Resources\TransmissionResource\Pages;

use App\Filament\Resources\TransmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTransmissions extends ManageRecords
{
    protected static string $resource = TransmissionResource::class;

    protected static ?string $title = 'Nota Fiscal Eletrônica';

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
