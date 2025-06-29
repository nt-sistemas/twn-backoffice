<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cobrancas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->enum('tipo', ['mensalidade', 'anuidade', 'servico', 'produto'])->default('mensalidade');
            $table->text('descricao')->nullable();
            $table->string('mes_referencia')->nullable();
            $table->string('ano_referencia')->nullable();
            $table->decimal('valor', 10, 2)->default(0.00);
            $table->date('data_vencimento')->nullable();
            $table->enum('status', ['pendente', 'pago', 'atrasado', 'cancelada'])->default('pendente');
            $table->date('data_pagamento')->nullable();
            $table->decimal('valor_pago', 10, 2)->nullable();
            $table->enum('status_nota_fiscal', ['pendente', 'emitida', 'falha_emissao', 'cancelada'])->default('pendente')->nullable();
            $table->string('metodo_pagamento')->nullable();
            $table->string('referencia')->nullable();
            $table->string('nota_fiscal')->nullable();
            $table->string('recibo')->nullable();
            $table->string('boleto')->nullable();
            $table->string('pix')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cobrancas');
    }
};
