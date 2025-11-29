<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Purchase;

class Supplier extends BaseModel
{
    protected $table = 'suppliers';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }
}