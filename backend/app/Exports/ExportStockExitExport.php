<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportStockExitExport implements FromArray, WithHeadings, WithTitle, ShouldAutoSize
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'ID do Produto',
            'Nome do Produto',
            'Marca',
            'Tipo de Produto',
            'Preço Unitário',
            'Quantidade Total',
            'Valor Total',
            'Primeira Venda',
            'Última Venda'
        ];
    }

    public function title(): string
    {
        return 'Relatório de Saídas de Estoque';
    }
}