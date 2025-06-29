<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cobranca>
 */
class CobrancaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $valor = $this->faker->randomFloat(2, 10, 1000);
        return [

            'descricao' => $this->faker->sentence,
            'valor' => $this->faker->randomFloat(2, 10, 1000),
            'data_vencimento' => $this->faker->dateTimeBetween('now', '+1 month'),
            'status' => $this->faker->randomElement(['pendente', 'pago', 'atrasado']),

            'tipo' => $this->faker->randomElement(['mensalidade', 'anuidade', 'servico', 'produto']),
            'mes_referencia' => $this->faker->monthName,
            'ano_referencia' => $this->faker->year,
            'data_pagamento' => $this->faker->optional()->dateTimeBetween('now', '+10 days'),
            'valor_pago' => $this->faker->optional()->randomFloat(2, 10, 1000),
            'status_nota_fiscal' => $this->faker->randomElement(['pendente', 'emitida', 'falha_emissao', 'cancelada']),
            'metodo_pagamento' => $this->faker->randomElement(['boleto', 'pix', 'cartao_credito', 'transferencia_bancaria']),
            'referencia' => $this->faker->optional()->word,
            'nota_fiscal' => $this->faker->optional()->word,
            'recibo' => $this->faker->optional()->word,
            'boleto' => $this->faker->optional()->word,
            'pix' => $this->faker->optional()->word,
        ];
    }
}
