<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductType extends BaseModel
{
    protected $table = 'product_types';

    protected $fillable = [
        'name',
        'description',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}