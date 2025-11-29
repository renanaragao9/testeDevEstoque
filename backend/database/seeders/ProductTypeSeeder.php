<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTypeSeeder extends Seeder
{
    public function run(): void
    {
        $productTypes = [
            [
                'name' => 'Smartphone',
                'description' => 'Dispositivos móveis inteligentes para comunicação e entretenimento',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'SmartWatch',
                'description' => 'Relógios inteligentes conectados para monitoramento de saúde e notificações',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Acessórios',
                'description' => 'Acessórios para smartphones e smartwatches, como capas, carregadores e pulseiras',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('product_types')->insert($productTypes);
    }
}