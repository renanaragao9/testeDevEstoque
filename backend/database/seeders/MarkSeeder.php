<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MarkSeeder extends Seeder
{
    public function run(): void
    {
        $marks = [
            [
                'name' => 'Apple',
                'description' => 'Empresa americana de tecnologia conhecida por seus produtos inovadores',
                'country' => 'Estados Unidos',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Samsung',
                'description' => 'Empresa sul-coreana líder em eletrônicos e smartphones',
                'country' => 'Coreia do Sul',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Xiaomi',
                'description' => 'Empresa chinesa especializada em smartphones e dispositivos inteligentes',
                'country' => 'China',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Huawei',
                'description' => 'Empresa chinesa de telecomunicações e equipamentos de rede',
                'country' => 'China',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'OnePlus',
                'description' => 'Marca chinesa conhecida por smartphones de alta performance',
                'country' => 'China',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Genérico',
                'description' => 'Marca genérica para produtos diversos',
                'country' => 'Brasil',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('marks')->insert($marks);
    }
}