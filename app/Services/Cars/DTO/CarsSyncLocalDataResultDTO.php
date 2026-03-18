<?php

declare(strict_types=1);

namespace App\Services\Cars\DTO;

readonly final class CarsSyncLocalDataResultDTO
{
    public function __construct(
        public int $totalFilesProcessed,
        public int $totalImagesProcessed,
    ) {
    }
}
