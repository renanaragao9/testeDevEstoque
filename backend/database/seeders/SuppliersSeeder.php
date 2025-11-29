<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuppliersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('suppliers')->insert([
            [
                'name' => 'Fornecedor A',
                'email' => 'contato@fornecedorA.com',
                'phone' => '123456789',
                'address' => 'Rua Exemplo, 123',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fornecedor B',
                'email' => 'contato@fornecedorB.com',
                'phone' => '987654321',
                'address' => 'Avenida Teste, 456',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}