<?php

declare(strict_types=1);

namespace App\Services\Cars\Actions;

use App\Services\Cars\Http\Requests\CarsGetListRequest;
use App\Services\Cars\Models\Car;
use App\Services\Cars\Repositories\CarsRepository;
use FKS\Actions\Action;
use FKS\Search\Collections\EntitiesCollection;
use FKS\Search\ValueObjects\SearchConditions;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class CarListAction extends Action
{
    public function __construct(public readonly CarsRepository $carsRepository)
    {
    }

    public function handle(SearchConditions $searchConditions): EntitiesCollection|Builder|bool|Collection|null
    {
        return $this->carsRepository->search($searchConditions);
    }

    public function asController(CarsGetListRequest $request): Response|JsonResponse
    {
        $conditions = $request->getSearchConditions();

        return response()->json($this->handle($conditions)->map(static fn (Car $car) => $car->only($conditions->getAvailableFields())));
    }
}
