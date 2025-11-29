<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $smartphoneType = DB::table('product_types')->where('name', 'Smartphone')->first();
        $smartwatchType = DB::table('product_types')->where('name', 'SmartWatch')->first();
        $accessoriesType = DB::table('product_types')->where('name', 'Acessórios')->first();

        $apple = DB::table('marks')->where('name', 'Apple')->first();
        $samsung = DB::table('marks')->where('name', 'Samsung')->first();
        $generic = DB::table('marks')->where('name', 'Genérico')->first();

        $products = [
            [
                'name' => 'iPhone 15',
                'description' => 'Smartphone Apple iPhone 15 com câmera avançada e processador A17',
                'price_sale' => 5999.99,
                'product_type_id' => $smartphoneType->id,
                'mark_id' => $apple->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Samsung Galaxy S24',
                'description' => 'Smartphone Samsung Galaxy S24 com tela AMOLED e bateria de longa duração',
                'price_sale' => 4999.99,
                'product_type_id' => $smartphoneType->id,
                'mark_id' => $samsung->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Apple Watch Series 9',
                'description' => 'Smartwatch Apple com monitoramento de saúde e GPS integrado',
                'price_sale' => 3999.99,
                'product_type_id' => $smartwatchType->id,
                'mark_id' => $apple->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Samsung Galaxy Watch 6',
                'description' => 'Smartwatch Samsung com design elegante e recursos fitness',
                'price_sale' => 2499.99,
                'product_type_id' => $smartwatchType->id,
                'mark_id' => $samsung->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Capa para iPhone',
                'description' => 'Capa protetora de silicone para iPhone 15',
                'price_sale' => 99.99,
                'product_type_id' => $accessoriesType->id,
                'mark_id' => $apple->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Carregador Wireless',
                'description' => 'Carregador sem fio rápido para smartphones',
                'price_sale' => 149.99,
                'product_type_id' => $accessoriesType->id,
                'mark_id' => $generic->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pulseira para Apple Watch',
                'description' => 'Pulseira de couro para Apple Watch Series 9',
                'price_sale' => 299.99,
                'product_type_id' => $accessoriesType->id,
                'mark_id' => $apple->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('products')->insert($products);
    }
}