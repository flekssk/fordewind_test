<?php

declare(strict_types=1);

namespace App\Services\Cars\Repositories;

use App\Services\Cars\Models\CarBrand;
use FKS\Repositories\Repository;
use FKS\Search\Repositories\SearchRepository;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Repository<CarBrand>
 */
class CarsBrandsRepository extends SearchRepository
{
    public static function getEntityInstance(): Model
    {
        return CarBrand::make();
    }
}
