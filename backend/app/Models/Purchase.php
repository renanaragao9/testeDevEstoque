<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends BaseModel
{
    protected $table = 'purchases';

    protected $fillable = [
        'invoice_number',
        'purchase_date',
        'total_amount',
        'supplier_id',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function stockMovements()
    {
        return $this->morphMany(StockMovement::class, 'movimentable');
    }
}