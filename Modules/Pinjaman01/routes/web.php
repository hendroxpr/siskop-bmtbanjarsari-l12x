<?php

use Illuminate\Support\Facades\Route;
use Modules\Pinjaman01\Http\Controllers\Pinjaman01Controller;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('pinjaman01s', Pinjaman01Controller::class)->names('pinjaman01');
});
