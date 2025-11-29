<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('customers')->insert([
            [
                'name' => 'Cliente A',
                'email' => 'contato@clienteA.com',
                'phone' => '123456789',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cliente B',
                'email' => 'contato@clienteB.com',
                'phone' => '987654321',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}