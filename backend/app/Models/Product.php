<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends BaseModel
{
    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'price_sale',
        'product_type_id',
        'mark_id',
    ];

    protected $casts = [
        'price_sale' => 'decimal:2',
    ];

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function mark(): BelongsTo
    {
        return $this->belongsTo(Mark::class);
    }

    public function specifications(): BelongsToMany
    {
        return $this->belongsToMany(Specification::class, 'products_specifications')->withPivot('value')->withTimestamps();
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }
}