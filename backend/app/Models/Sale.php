<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends BaseModel
{
    protected $table = 'sales';

    protected $fillable = [
        'invoice_number',
        'sale_date',
        'total_amount',
        'customer_id',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function stockMovements()
    {
        return $this->morphMany(StockMovement::class, 'movimentable');
    }
}