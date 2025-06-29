<?php

namespace App\Livewire\Backoffice\Clientes;

use Livewire\Component;
use Mary\Traits\Toast;

class Create extends Component
{
    use Toast;


    public $nome_razao_social;
    public $tipo;
    public $id_gestor;
    public $nome_fantasia;
    public $cpf_cnpj;
    public $email;
    public $telefone;
    public $endereco;
    public $cidade;
    public $estado;
    public $cep;
    public $pais;
    public $tipo_contrato;
    public $data_inicio_contrato;
    public $data_fim_contrato;
    public $valor_contrato;
    public $valor_desconto;
    public $valor_total;
    public $status;
    public $observacoes;

    public $tipoContratoOptions = [
        [
            'id' => 'mensal',
            'name' => 'Mensal',
        ],
        [
            'id' => 'anual',
            'name' => 'Anual',
        ],
        [
            'id' => 'demonstracao',
            'name' => 'Demonstração',
        ],
    ];

    public $tipoOptions = [
        [
            'id' => 'fisica',
            'name' => 'Pessoa Física',
        ],
        [
            'id' => 'juridica',
            'name' => 'Pessoa Jurídica',
        ],
    ];

    public $statusOptions = [
        [
            'id' => 'ativo',
            'name' => 'Ativo',
        ],
        [
            'id' => 'pendente',
            'name' => 'Pendente',
        ],
        [
            'id' => 'inativo',
            'name' => 'Inativo',
        ],

    ];

    public function render()
    {
        return view('livewire.backoffice.clientes.create');
    }

    public function save()
    {
        // Validate the input data
        $this->validate([
            'nome_razao_social' => 'required|string|max:255',
            'tipo' => 'required|string|max:50',
            'id_gestor' => 'required|string',
            'nome_fantasia' => 'nullable|string|max:255',
            'cpf_cnpj' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'telefone' => 'required|string|max:20',
            'endereco' => 'nullable|string|max:255',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:50',
            'cep' => 'nullable|string|max:10',
            'pais' => 'nullable|string|max:50',
            'tipo_contrato' => 'nullable|string|max:50',
            'data_inicio_contrato' => 'nullable|date',
            'data_fim_contrato' => 'nullable|date',
            'valor_contrato' => 'nullable|numeric|min:0',
            'valor_desconto' => 'nullable|numeric|min:0',
            'valor_total' => 'nullable|numeric|min:0',
            'status' => 'required|string|in:' . implode(',', array_column($this->statusOptions, 'id')),
            'observacoes' => 'nullable|string|max:500',
        ]);

        // Create the client record
        \App\Models\Cliente::create([
            'nome_razao_social' => $this->nome_razao_social,
            'nome_fantasia' => $this->nome_fantasia,
            'tipo' => $this->tipo,
            'id_gestor' => $this->id_gestor,
            'nome_fantasia' => $this->nome_fantasia,
            'cpf_cnpj' => $this->cpf_cnpj,
            'email' => $this->email,
            'telefone' => $this->telefone,
            'endereco' => $this->endereco,
            'cidade' => $this->cidade,
            'estado' => $this->estado,
            'cep' => $this->cep,
            'pais' => 'Brasil', // Assuming 'Brasil' as default country
            'tipo_contrato' => $this->tipo_contrato,
            'data_inicio_contrato' => $this->data_inicio_contrato,
            'data_fim_contrato' => $this->data_fim_contrato,
            'valor_contrato' => $this->valor_contrato,
            'valor_desconto' => $this->valor_desconto,
            'valor_total' => $this->valor_contrato - $this->valor_desconto,
            'status' => $this->status,
            'observacoes' => $this->observacoes,
            // Add other fields as necessary
        ]);


        $this->toast(
            type: 'success',
            title: 'Cliente Criado com Sucesso', // optional (text)
            description: null,                  // optional (text)
            position: 'toast-top toast-end',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-success',                  // Optional (daisyUI classes)
            timeout: 3000,                      // optional (ms)
            redirectTo: route('clientes.index')                    // optional (uri)
        );
    }
}
