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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome_razao_social')->unique();
            $table->enum('tipo', ['fisica', 'juridica'])->default('juridica');
            $table->string('id_gestor')->nullable();
            $table->string('nome_fantasia')->nullable();
            $table->string('cpf_cnpj')->unique();
            $table->string('email')->unique();
            $table->string('telefone')->nullable();
            $table->string('endereco')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado')->nullable();
            $table->string('cep')->nullable();
            $table->string('pais')->default('Brasil');
            $table->enum('tipo_contrato', ['mensal', 'anual','demonstracao'])->default('mensal');
            $table->date('data_inicio_contrato')->nullable();
            $table->date('data_fim_contrato')->nullable();
            $table->decimal('valor_contrato', 10, 2)->default(0.00);
            $table->decimal('valor_desconto', 10, 2)->default(0.00);
            $table->decimal('valor_total', 10, 2)->default(0.00);
            $table->enum('status', ['ativo', 'inativo', 'pendente'])->default('ativo');
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
