<?php

use Illuminate\Support\Facades\Route;
use Modules\Simpanan01\Http\Controllers\Simpanan01Controller;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('simpanan01s', Simpanan01Controller::class)->names('simpanan01');
});
