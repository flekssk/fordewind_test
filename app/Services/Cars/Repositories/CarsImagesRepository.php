<?php

declare(strict_types=1);

namespace App\Services\Cars\Repositories;

use App\Services\Cars\Models\CarImage;
use FKS\Repositories\Repository;
use FKS\Search\Repositories\SearchRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Repository<CarImage>
 */
class CarsImagesRepository extends SearchRepository
{
    public static function getEntityInstance(): Model
    {
        return CarImage::make();
    }
}
