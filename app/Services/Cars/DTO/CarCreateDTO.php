<?php

declare(strict_types=1);

namespace App\Services\Cars\DTO;

readonly class CarCreateDTO
{
    public function __construct(
        public int $brandId,
        public string $model,
        public int $year,
        public array $images,
        public string $imagesSourceStorage,
        public string $imagesStorage,
    ) {
    }
}
