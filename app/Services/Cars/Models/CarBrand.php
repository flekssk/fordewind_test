<?php

declare(strict_types=1);

namespace App\Services\Cars\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarBrand extends Model
{
    public $timestamps = true;
    protected $fillable = ['name', 'slug'];

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }
}
