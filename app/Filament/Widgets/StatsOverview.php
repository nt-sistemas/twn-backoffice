<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $customersCount = \App\Models\Customer::count();
        $invoicesCount = \App\Models\Invoice::count();
        $transmissionsCount = \App\Models\Transmission::count();

        return [
            Stat::make('Clientes', $customersCount)
                ->color('success')
                ->icon('heroicon-o-building-office-2'),
            Stat::make('Faturas', $invoicesCount)
                ->color('success')
                ->icon('heroicon-o-banknotes'),
            Stat::make('Nota Fiscais', $transmissionsCount)
                ->color('success')
                ->icon('heroicon-o-bolt'),
        ];
    }
}
