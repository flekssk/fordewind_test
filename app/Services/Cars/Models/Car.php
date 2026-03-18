<?php

declare(strict_types=1);

namespace App\Services\Cars\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    public static array $availableFields = ['id', 'model', 'year', 'brand', 'images'];
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

    public function brand(): BelongsTo
    {
        return $this->belongsTo(CarBrand::class, 'car_brand_id', 'id');
    }
}
