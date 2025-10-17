<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin01\Http\Controllers\Admin01Controller;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('admin01s', Admin01Controller::class)->names('admin01');
});
