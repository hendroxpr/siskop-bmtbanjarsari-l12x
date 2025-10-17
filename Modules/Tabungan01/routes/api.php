<?php

use Illuminate\Support\Facades\Route;
use Modules\Tabungan01\Http\Controllers\Tabungan01Controller;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('tabungan01s', Tabungan01Controller::class)->names('tabungan01');
});
