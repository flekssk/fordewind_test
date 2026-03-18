<?php

declare(strict_types=1);

namespace App\Services\Cars\Actions;

use App\Services\Cars\DTO\CarCreateDTO;
use App\Services\Cars\Models\Car;
use App\Services\Cars\Repositories\CarsImagesRepository;
use App\Services\Cars\Repositories\CarsRepository;
use FKS\Actions\Action;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * @method static Car run(CarCreateDTO $dto)
 */
class CarCreateAction extends Action
{
    public function __construct(
        public readonly CarsRepository $carRepository,
        public readonly CarsImagesRepository $carsImagesRepository,
    ) {
    }

    public function handle(CarCreateDTO $dto): Car
    {
        return DB::transaction(function () use ($dto) {
            foreach ($dto->images as $image) {
                if (!Storage::disk($dto->imagesSourceStorage)->exists($image)) {
                    throw new \Exception("Image {$image} not found in {$dto->imagesSourceStorage}");
                }
            }

            $car = $this->carRepository->create([
                'car_brand_id' => $dto->brandId,
                'model' => $dto->model,
                'year' => $dto->year,
            ]);

            $this->carsImagesRepository->createBatch(
                array_map(static fn ($image) => ['car_id' => $car->id, 'path' => 'storage/cars-images/' . basename($image), 'disk' => $dto->imagesStorage], $dto->images)
            );

            foreach ($dto->images as $image) {
                Storage::disk($dto->imagesStorage)->put(
                    'cars-images/' . basename($image),
                    Storage::disk($dto->imagesSourceStorage)->get($image),
                );
            }

            return $car;
        });
    }
}
