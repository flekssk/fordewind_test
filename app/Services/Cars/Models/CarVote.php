<?php

declare(strict_types=1);

namespace App\Services\Cars\Models;

use Illuminate\Database\Eloquent\Model;

class CarVote extends Model
{
    public $timestamps = true;
    protected $fillable = ['car_id'];
}
