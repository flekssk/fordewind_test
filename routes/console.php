<?php

use App\Services\Cars\Actions\CarsSyncLocalDataAction;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Lorisleiva\Actions\Facades\Actions;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Actions::registerCommandsForAction(CarsSyncLocalDataAction::class);
