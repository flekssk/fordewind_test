<?php

declare(strict_types=1);

use App\Services\Cars\Actions\CarGetRandomAction;
use App\Services\Cars\Actions\CarVoteAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(static function () {
    Route::prefix('cars')->group(static function () {
        Route::post('random', CarGetRandomAction::class);
        Route::post('vote', CarVoteAction::class);
    });
});
