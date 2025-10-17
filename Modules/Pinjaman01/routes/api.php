<?php

use Illuminate\Support\Facades\Route;
use Modules\Pinjaman01\Http\Controllers\Pinjaman01Controller;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('pinjaman01s', Pinjaman01Controller::class)->names('pinjaman01');
});
