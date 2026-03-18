<?php

declare(strict_types=1);

namespace App\Services\Cars\Actions;

use App\Services\Cars\DTO\CarBrandCreateDTO;
use App\Services\Cars\DTO\CarCreateDTO;
use FKS\Actions\Action;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use JsonException;
use League\Flysystem\StorageAttributes;

class CarsSyncLocalDataAction extends Action
{
    public $commandSignature = 'cars:sync-local-data';

    public function handle(): void
    {
        $brandsIds = [];
        $dataStorage = config('services.cars.data_storage');
        $dataPath = config('services.cars.data_path');

        $listing = Storage::disk($dataStorage)->listContents($dataPath);

        $processed = 0;

        foreach ($listing as $item) {
            if (!$item instanceof StorageAttributes || !$item->isFile()) {
                continue;
            }

            $path = $item->path();

            if (strtolower(pathinfo($path, PATHINFO_EXTENSION)) !== 'json') {
                continue;
            }

            $stream = Storage::disk($dataStorage)->readStream($path);

            try {
                $contents = stream_get_contents($stream);
            } finally {
                if (is_resource($stream)) {
                    fclose($stream);
                }
            }

            if ($contents === false) {
                Log::error("Invalid stream contents: storage $dataStorage, path $path");
                continue;
            }

            try {
                $data = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException) {
                Log::error("Invalid json contents: storage $dataStorage, path $path");

                continue;
            }

            $validator = Validator::make(
                $data,
                [
                    'Make' => 'required|string',
                    'Model' => 'required|string',
                    'Year' => 'required|integer',
                    'Image' => 'required|string',
                ]
            );

            if ($validator->fails()) {
                Log::error("Invalid data contents: storage $dataStorage, path $path");

                continue;
            }

            if (!isset($brandsIds[$data['Make']])) {
                $brandsIds[$data['Make']] = CarBrandCreateAction::run(new CarBrandCreateDTO($data['Make']))->id;
            }

            CarCreateAction::run(new CarCreateDTO(
                $brandsIds[$data['Make']],
                $data['Model'],
                $data['Year'],
                [$dataPath . $data['Image']],
                $dataStorage,
                'public',
            ));

            unset($contents, $data);
            $processed++;

            if ($processed % 1000 === 0) {
                gc_collect_cycles();
            }
        }
    }
}
