<?php

namespace App\Filament\Widgets;

use App\Models\Transmission;
use Filament\Widgets\ChartWidget;

class TotalEmissionChart extends ChartWidget
{
    protected static ?string $heading = 'Gráfico de Emissão Notas';
    protected int | string | array $columnSpan = 'full';
    //protected string $color = 'blue';
    //protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = Transmission::query()
            ->selectRaw('SUM(amount) as total')
            ->selectRaw("DATE_FORMAT(transmission_date, '%Y-%m') as month")
            ->where('status', 'transmitted')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();


        $chart = [];

        foreach ($data as $month => $total) {
            $chart['labels'][] = $month;
            $chart['datasets'][] = $total;
        }


        return [
            'datasets' => [
                [
                    'label' => 'Valor total emitido',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'data' => $chart['datasets'],
                ],
            ],
            'labels' => $chart['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
