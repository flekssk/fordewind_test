<?php

declare(strict_types=1);

namespace App\Services\Cars\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarImage extends Model
{
    public $timestamps = true;
    protected $fillable = ['car_id', 'path'];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
}
