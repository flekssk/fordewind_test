<?php

declare(strict_types=1);

namespace App\Services\Cars\Actions;

use App\Services\Cars\Http\Requests\CarVoteRequest;
use App\Services\Cars\Repositories\CarVoteRepository;
use FKS\Actions\Action;
use Illuminate\Http\Response;

class CarVoteAction extends Action
{
    public function __construct(public readonly CarVoteRepository $carVoteRepository)
    {
    }

    public function handle(int $carId): void
    {
        $this->carVoteRepository->create(['car_id' => $carId]);
    }

    public function asController(CarVoteRequest $request): Response
    {
        $this->handle($request->input('car_id'));

        return response()->noContent();
    }
}
