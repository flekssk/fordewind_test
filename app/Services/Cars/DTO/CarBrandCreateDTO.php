<?php

declare(strict_types=1);

namespace App\Services\Cars\DTO;

readonly class CarBrandCreateDTO
{
    public function __construct(
        public string $name,
    ) {
    }
}
