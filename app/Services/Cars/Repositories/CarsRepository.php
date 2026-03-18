<?php

declare(strict_types=1);

namespace App\Services\Cars\Repositories;

use App\Services\Cars\Models\Car;
use FKS\Repositories\Repository;
use FKS\Search\Repositories\SearchRepository;
use FKS\Search\ValueObjects\SearchConditions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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

    public static function getMapAvailableFieldToSelect(): array
    {
        return [
            'votes_count' => DB::raw('(SELECT COUNT(*) FROM car_votes WHERE car_votes.car_id = cars.id) AS votes_count'),
            'brand_votes_count' => DB::raw('(SELECT COUNT(*) FROM car_votes WHERE car_votes.car_id IN (SELECT car_id FROM cars sc WHERE sc.car_brand_id = cars.car_brand_id)) AS brand_votes_count')
        ];
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
