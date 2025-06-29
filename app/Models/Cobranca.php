<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cobranca extends Model
{
    /** @use HasFactory<\Database\Factories\CobrancaFactory> */
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'tipo',
        'descricao',
        'mes_referencia',
        'ano_referencia',
        'valor',
        'data_vencimento',
        'status',
        'data_pagamento',
        'valor_pago',
        'status_nota_fiscal',
        'metodo_pagamento',
        'referencia',
        'nota_fiscal',
        'recibo',
        'boleto',
        'pix'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
