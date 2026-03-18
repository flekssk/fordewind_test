<?php

declare(strict_types=1);

namespace App\Services\Cars\Actions;

use App\Services\Cars\DTO\CarBrandCreateDTO;
use App\Services\Cars\Models\CarBrand;
use App\Services\Cars\Repositories\CarsBrandsRepository;
use FKS\Actions\Action;
use Illuminate\Support\Str;

/**
 * @method static CarBrand run(CarBrandCreateDTO $dto)
 */
class CarBrandCreateAction extends Action
{
    public function __construct(
        public readonly CarsBrandsRepository $carsBrandsRepository,
    ) {
    }

    public function handle(CarBrandCreateDTO $dto): CarBrand
    {
        return $this->carsBrandsRepository->createOrFirst(
            ['slug' => Str::slug($dto->name)],
            ['name' => $dto->name],
        );
    }
}
