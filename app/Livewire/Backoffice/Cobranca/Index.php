<?php

namespace App\Livewire\Backoffice\Cobranca;

use App\Models\Cobranca;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Index extends Component
{
    public $pagas;
    public $pendentes;
    public $canceladas;
    public $search = '';

    public function render()
    {
        $this->pagas = Cobranca::where('status', 'pago')->count();
        $this->pendentes = Cobranca::where('status', 'pendente')->count();
        $this->canceladas = Cobranca::where('status', 'atrasado')->count();
        return view('livewire.backoffice.cobranca.index');
    }

    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => 'ID', 'sortable' => true],
            ['key' => 'cliente.nome_razao_social', 'label' => 'Cliente', 'sortable' => true],

            ['key' => 'valor', 'label' => 'Valor', 'sortable' => true],
            ['key' => 'data_vencimento', 'label' => 'Data de Vencimento', 'sortable' => true],
            ['key' => 'status', 'label' => 'Status', 'sortable' => true],

        ];
    }

    #[Computed]
    public function getData(): LengthAwarePaginator
    {
        return Cobranca::with('cliente')
            ->when($this->search, function ($query) {
                $query->where('id', 'like', '%' . $this->search . '%')
                    ->orWhereHas('cliente', function ($q) {
                        $q->where('nome_razao_social', 'like', '%' . $this->search . '%');
                        $q->orWhere('nome_fantasia', 'like', '%' . $this->search . '%');
                        $q->orWhere('cpf_cnpj', 'like', '%' . $this->search . '%');
                        $q->orWhere('email', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }
}
