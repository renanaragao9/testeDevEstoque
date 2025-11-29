<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends BaseModel
{
    protected $table = 'stock_movements';

    protected $fillable = [
        'type',
        'status',
        'stock_id',
        'movimentable_type',
        'movimentable_id',
    ];

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function movimentable(): MorphTo
    {
        return $this->morphTo();
    }
}