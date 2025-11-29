<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        $warehouses = [
            [
                'name' => 'Prateleira A',
                'location' => 'Proxima à entrada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Prateleira B',
                'location' => 'Próxima ao corredor central',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gaveta A',
                'location' => 'Primeira gaeveta à esquerda',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gaveta B',
                'location' => 'Segunda gaveta à direita',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vitrine Externa',
                'location' => 'Área de exposição na entrada',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('warehouses')->insert($warehouses);
    }
}