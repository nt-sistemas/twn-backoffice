<?php

namespace App\Livewire\Backoffice;

use App\Models\Cliente;
use Livewire\Component;

class Index extends Component
{
    public array $chartsClientes;
    public array $chartsCobrancas;

    public function mount()
    {
        $this->chartsClientes = $this->getChartsClientes();
        $this->chartsCobrancas = $this->getChartsCobrancas();
    }

    public function render()
    {

        return view('livewire.backoffice.index');
    }

    public function getChartsClientes()
    {
        $ativos = Cliente::where('status', 'ativo')->count();
        $demonstracao = Cliente::where('status', 'pendente')->count();
        $inativos = Cliente::where('status', 'inativo')->count();

        return [
            'type' => 'bar',
            'data' => [
                'labels' => ['Ativos', 'Pendente', 'Inativos'],
                'datasets' => [
                    [

                        'data' => [$ativos, $demonstracao, $inativos],
                        'backgroundColor' => [

                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(201, 203, 207, 0.2)'
                        ],
                        'borderColor' => [

                            'rgb(75, 192, 192)',

                            'rgb(153, 102, 255)',
                            'rgb(201, 203, 207)'
                        ],
                        'borderWidth' => 1


                    ],
                ],


            ]
        ];
    }

    public function getChartsCobrancas()
    {
        return [
            'labels' => ['Pagas', 'Pendentes', 'Atrasadas'],
            'datasets' => [
                [
                    'label' => 'Cobranças',
                    'data' => [200, 150, 50],
                    'backgroundColor' => ['#4CAF50', '#FF9800', '#F44336'],
                ],
            ],
        ];
    }
}
