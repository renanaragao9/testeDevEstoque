<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends BaseModel
{
    protected $table = 'customers';

    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}