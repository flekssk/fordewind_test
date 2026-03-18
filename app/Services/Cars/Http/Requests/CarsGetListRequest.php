<?php

declare(strict_types=1);

namespace App\Services\Cars\Http\Requests;

use App\Services\Cars\Models\Car;
use FKS\Search\Requests\SearchRequest;

class CarsGetListRequest extends SearchRequest
{
    public static function getAvailableFields(): array
    {
        return Car::$availableFields;
    }

    public static function getSortingDefinitions(): array
    {
        return [];
    }
}
