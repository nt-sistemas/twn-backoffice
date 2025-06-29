<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'nome_razao_social' => $this->faker->company,
            'tipo' => $this->faker->randomElement(['fisica', 'juridica']),
            'id_gestor' => $this->faker->uuid,
            'nome_fantasia' => $this->faker->companySuffix,
            'cpf_cnpj' => $this->faker->unique()->numerify('##############'),
            'email' => $this->faker->unique()->safeEmail,
            'telefone' => $this->faker->phoneNumber,
            'endereco' => $this->faker->streetAddress,
            'cidade' => $this->faker->city,
            'estado' => $this->faker->stateAbbr,
            'cep' => $this->faker->postcode,
            'pais' => 'Brasil',
            'tipo_contrato' => $this->faker->randomElement(['mensal', 'anual', 'demonstracao']),
            'data_inicio_contrato' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'data_fim_contrato' => $this->faker->dateTimeBetween('now', '+1 year'),
            'valor_contrato' => $this->faker->randomFloat(2, 100, 10000),
            'valor_desconto' => $this->faker->randomFloat(2, 0, 1000),
            'valor_total' => function (array $attributes) {
                return $attributes['valor_contrato'] - $attributes['valor_desconto'];
            },
            'status' => $this->faker->randomElement(['ativo', 'inativo', 'pendente']),
            'observacoes' => $this->faker->optional()->paragraph,   
        ];
    }
}
