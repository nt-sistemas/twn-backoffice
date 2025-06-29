<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        /*
        Cliente::factory(100)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/

        $clientes = Cliente::all();
        foreach ($clientes as $cliente) {
            $cliente->cobrancas()->createMany(
                \App\Models\Cobranca::factory(rand(1, 3))->make()->toArray()
            );
        }
    }
}
