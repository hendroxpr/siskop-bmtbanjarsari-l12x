<?php

use Illuminate\Support\Facades\Route;
use Modules\Akuntansi01\Http\Controllers\Akuntansi01Controller;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('akuntansi01s', Akuntansi01Controller::class)->names('akuntansi01');
});
