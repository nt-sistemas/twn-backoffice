<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    /** @use HasFactory<\Database\Factories\ClienteFactory> */
    use HasFactory;

    protected $fillable = [
        'nome_razao_social',
        'tipo',
        'id_gestor',
        'nome_fantasia',
        'cpf_cnpj',
        'email',
        'telefone',
        'endereco',
        'cidade',
        'estado',
        'cep',
        'pais',
        'tipo_contrato',
        'data_inicio_contrato',
        'data_fim_contrato',
        'valor_contrato',
        'valor_desconto',
        'valor_total',
        'status',
        'observacoes'
    ];

    public function cobrancas()
    {
        return $this->hasMany(Cobranca::class);
    }
}
