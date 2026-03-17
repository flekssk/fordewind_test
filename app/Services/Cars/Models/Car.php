<?php

declare(strict_types=1);

namespace App\Services\Cars\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    public $timestamps = true;
    protected $fillable = ['car_brand_id', 'model', 'year'];

    public function images(): HasMany
    {
        return $this->hasMany(CarImage::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(CarVote::class);
    }
}
