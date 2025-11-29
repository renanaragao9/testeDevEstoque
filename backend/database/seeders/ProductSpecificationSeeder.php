<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSpecificationSeeder extends Seeder
{
    public function run(): void
    {
        $iphone15 = DB::table('products')->where('name', 'iPhone 15')->first();
        $galaxyS24 = DB::table('products')->where('name', 'Samsung Galaxy S24')->first();
        $appleWatch9 = DB::table('products')->where('name', 'Apple Watch Series 9')->first();
        $galaxyWatch6 = DB::table('products')->where('name', 'Samsung Galaxy Watch 6')->first();
        $specs = DB::table('specifications')->pluck('id', 'name');

        $productSpecifications = [
            [
                'product_id' => $iphone15->id,
                'specification_id' => $specs['Tamanho da Tela'],
                'value' => '6.1 polegadas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $iphone15->id,
                'specification_id' => $specs['Capacidade da Bateria'],
                'value' => '3349 mAh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $iphone15->id,
                'specification_id' => $specs['Sistema Operacional'],
                'value' => 'iOS 17',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $iphone15->id,
                'specification_id' => $specs['Armazenamento Interno'],
                'value' => '128GB',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $iphone15->id,
                'specification_id' => $specs['RAM'],
                'value' => '6GB',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $iphone15->id,
                'specification_id' => $specs['Câmera Principal'],
                'value' => '48MP',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'product_id' => $galaxyS24->id,
                'specification_id' => $specs['Tamanho da Tela'],
                'value' => '6.2 polegadas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $galaxyS24->id,
                'specification_id' => $specs['Capacidade da Bateria'],
                'value' => '4000 mAh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $galaxyS24->id,
                'specification_id' => $specs['Sistema Operacional'],
                'value' => 'Android 14',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $galaxyS24->id,
                'specification_id' => $specs['Armazenamento Interno'],
                'value' => '256GB',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $galaxyS24->id,
                'specification_id' => $specs['RAM'],
                'value' => '8GB',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $galaxyS24->id,
                'specification_id' => $specs['Câmera Principal'],
                'value' => '50MP',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'product_id' => $appleWatch9->id,
                'specification_id' => $specs['Tamanho da Tela'],
                'value' => '1.9 polegadas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $appleWatch9->id,
                'specification_id' => $specs['Capacidade da Bateria'],
                'value' => '308 mAh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $appleWatch9->id,
                'specification_id' => $specs['Sistema Operacional'],
                'value' => 'watchOS 10',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $appleWatch9->id,
                'specification_id' => $specs['Armazenamento Interno'],
                'value' => '64GB',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'product_id' => $galaxyWatch6->id,
                'specification_id' => $specs['Tamanho da Tela'],
                'value' => '1.5 polegadas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $galaxyWatch6->id,
                'specification_id' => $specs['Capacidade da Bateria'],
                'value' => '425 mAh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $galaxyWatch6->id,
                'specification_id' => $specs['Sistema Operacional'],
                'value' => 'Wear OS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $galaxyWatch6->id,
                'specification_id' => $specs['Armazenamento Interno'],
                'value' => '16GB',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('products_specifications')->insert($productSpecifications);
    }
}