<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends BaseModel
{
    protected $table = 'warehouses';

    protected $fillable = [
        'name',
        'location',
    ];

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }
}