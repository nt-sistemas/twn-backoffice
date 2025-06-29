<?php

namespace App\Livewire\Backoffice\Clientes;

use App\Models\Cliente;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Index extends Component
{
    use WithPagination, Toast;

    public $search = '';
    public $ativos = 0;
    public $pendentes = 0;
    public $inativos = 0;

    public bool $showDrawer = false;

    public Cliente $cliente;

    public $confirmDelete = false;

    public function render()
    {
        $this->ativos = \App\Models\Cliente::where('status', 'ativo')->count();
        $this->pendentes = \App\Models\Cliente::where('status', 'pendente')->count();
        $this->inativos = \App\Models\Cliente::where('status', 'inativo')->count();
        return view('livewire.backoffice.clientes.index');
    }

    #[Computed]
    public function getData(): LengthAwarePaginator
    {
        return \App\Models\Cliente::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nome_razao_social', 'like', '%' . $this->search . '%')
                        ->orWhere('nome_fantasia', 'like', '%' . $this->search . '%')
                        ->orWhere('cpf_cnpj', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function headers()
    {
        return [
            ['key' => 'nome_razao_social', 'label' => 'Nome/Razão Social'],
            ['key' => 'tipo', 'label' => 'Tipo'],
            ['key' => 'tipo_contrato', 'label' => 'Tipo de Contrato'],
            ['key' => 'valor_total', 'label' => 'Valor Contrato'],
            ['key' => 'status',     'label'     =>     'Status'],

        ];
    }

    public function showClientesDrawer($clienteId)
    {
        $this->cliente = \App\Models\Cliente::find($clienteId);



        $this->showDrawer = true;
    }

    public function openModalDelete($clienteId)
    {
        $this->cliente = \App\Models\Cliente::find($clienteId);

        $this->confirmDelete = true;
    }

    public function deleteCliente($clienteId)
    {
        $cliente = \App\Models\Cliente::find($clienteId);
        if ($cliente) {
            $cliente->delete();
            $this->toast(
                type: 'success',
                title: 'Cliente Excluído com Sucesso',
                description: null,
                position: 'toast-top toast-end',
                icon: 'o-information-circle',
                css: 'alert-success',
                timeout: 3000,
                redirectTo: route('clientes.index')
            );
        } else {
            $this->toast(
                type: 'error',
                title: 'Erro ao Excluir Cliente',
                description: 'Cliente não encontrado.',
                position: 'toast-top toast-end',
                icon: 'o-exclamation-triangle',
                css: 'alert-error',
                timeout: 3000
            );
        }
    }
}
