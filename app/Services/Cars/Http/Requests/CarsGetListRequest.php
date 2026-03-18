<?php

declare(strict_types=1);

namespace App\Services\Cars\Http\Requests;

use App\Services\Cars\Models\Car;
use FKS\Search\Requests\FilteringDefinitions;
use FKS\Search\Requests\SearchRequest;

class CarsGetListRequest extends SearchRequest
{
    public static function getAvailableFields(): array
    {
        return Car::$availableFields;
    }

    public static function getFilteringDefinitions(): FilteringDefinitions
    {
        return FilteringDefinitions::create(static function (FilteringDefinitions $definitions) {
            $definitions->numeric('year')->nullable();
            $definitions->search('model')->nullable();
        });
    }

    public static function getSortingDefinitions(): array
    {
        return [
            'id',
            'votes_count',
            'created_at',
            'model',
            'year',
        ];
    }
}
