<?php

declare(strict_types=1);

namespace App\Services\Cars\Actions;

use App\Services\Cars\Http\Requests\CarsGetListRequest;
use App\Services\Cars\Models\Car;
use App\Services\Cars\Repositories\CarsRepository;
use FKS\Actions\Action;
use FKS\Search\ValueObjects\SearchConditions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class CarGetRandomAction extends Action
{
    public function __construct(public readonly CarsRepository $carsRepository)
    {
    }

    public function handle(SearchConditions $searchConditions): Collection
    {
        return $this->carsRepository->searchRandom($searchConditions);
    }

    public function asController(CarsGetListRequest $request): Response|JsonResponse
    {
        $conditions = $request->getSearchConditions();

        return response()->json($this->handle($conditions)->map(static fn (Car $car) => $car->only($conditions->getAvailableFields())));
    }
}
