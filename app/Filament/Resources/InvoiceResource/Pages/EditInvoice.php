<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use App\Services\NotaFiscalService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;

class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        $data = $this->record->toArray();
        $actions = [
            ...parent::getFormActions(),
        ];

        if ($data['status'] === 'paid') {
            $actions[] = Action::make('send_integration')
                ->label('Enviar Nota Fiscal')
                ->action(function () {
                    $notaFiscalService = new NotaFiscalService();
                    $notaFiscalService->sendIntegration($this->record->toArray());
                })
                ->icon('heroicon-o-paper-airplane')
                ->color('success');
        }

        return $actions;
    }
}
