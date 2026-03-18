<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Vote');
});

Route::get('rating', function () {
    return Inertia::render('Rating');
});
