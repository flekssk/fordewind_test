<?php

declare(strict_types=1);

namespace App\Services\Cars\Repositories;

use App\Services\Cars\Models\Car;
use FKS\Repositories\Repository;
use FKS\Search\Repositories\SearchRepository;
use FKS\Search\ValueObjects\SearchConditions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @extends Repository<Car>
 */
class CarsRepository extends SearchRepository
{
    public static function getEntityInstance(): Model
    {
        return Car::make();
    }

    public function searchRandom(SearchConditions $searchConditions): Collection
    {
        return $this->search(
            $searchConditions,
            $this->getQuery()->inRandomOrder()
        );
    }

    public static function getMapAvailableFieldToWith(): array
    {
        return [
            'brand' => [
                'column' => 'car_brand_id',
                'relation' => 'brand',
            ],
            'images' => 'images',
        ];
    }
}
