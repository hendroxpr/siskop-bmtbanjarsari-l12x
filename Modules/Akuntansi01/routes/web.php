<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use Modules\Akuntansi01\Http\Controllers\master\CoaController;
use Modules\Akuntansi01\Http\Controllers\master\ProduktabunganController;

// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::resource('akuntansi01', Akuntansi01Controller::class)->names('akuntansi01');

// use Modules\Tabungan01\Http\Controllers\Tabungan01Controller;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::group([], function () {
//     Route::resource('akuntansi01', Akuntansi01Controller::class)->names('Akuntans101');
// });

Route::prefix('akuntansi01')->group(function() {
    // Route::get('/', 'Akuntansi01Controller@index');

    /* master - produktabungan */
    Route::get('/master/produktabungan', [ProduktabunganController::class, 'index'])->name('akuntansi01.master.produktabungan.index')->middleware('auth'); /* halaman produktabungan */
    Route::get('/master/produktabunganshow', [ProduktabunganController::class, 'show'])->name('akuntansi01.master.produktabungan_show')->middleware('auth'); /* menampilkan data produktabungan pada datatable javascript */
    Route::post('/master/produktabungancreate', [ProduktabunganController::class, 'create'])->name('akuntansi01.master.produktabungan_create')->middleware('auth'); /* menambah produktabungan */
    Route::get('/master/produktabunganedit/{id}', [ProduktabunganController::class, 'edit'])->name('akuntansi01.master.produktabungan_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::get('/master/produktabungandestroy/{id}', [ProduktabunganController::class, 'destroy'])->name('akuntansi01.master.produktabungan_destroy')->middleware('auth'); /* hapus data produktabungan */
    Route::get('/master/produktabunganlistcoa', [ProduktabunganController::class, 'listcoa'])->name('akuntansi01.master.produktabungan_listcoa')->middleware('auth'); /* menampilkan list coa */
    Route::get('/master/produktabunganlistjenisjurnal', [ProduktabunganController::class, 'listjenisjurnal'])->name('akuntansi01.master.produktabungan_listjenisjurnal')->middleware('auth'); /* menampilkan list jenisjurnal */
    
    /* master - coa */
    Route::get('/master/coa', [CoaController::class, 'index'])->name('akuntansi01.master.coa.index')->middleware('auth'); /* halaman coa */
    Route::get('/master/coashow', [CoaController::class, 'show'])->name('akuntansi01.master.coa_show')->middleware('auth'); /* menampilkan data coa pada datatable javascript */
    Route::post('/master/coakirimsyarat', [CoaController::class, 'kirimsyarat'])->name('akuntansi01.master.coa_kirimsyarat')->middleware('auth'); /* kirim syarat */
    Route::post('/master/coacreate', [CoaController::class, 'create'])->name('akuntansi01.master.coa_create')->middleware('auth'); /* menambah coa */
    Route::get('/master/coaedit/{id}', [CoaController::class, 'edit'])->name('akuntansi01.master.coa_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::post('/master/coaupdate', [CoaController::class, 'update'])->name('akuntansi01.master.coa_update')->middleware('auth'); /* update data coa */
    Route::get('/master/coadestroy/{id}', [CoaController::class, 'destroy'])->name('akuntansi01.master.coa_destroy')->middleware('auth'); /* hapus data coa */
    Route::get('/master/coalistkelompok', [CoaController::class, 'listkelompok'])->name('akuntansi01.master.coa_listkelompok')->middleware('auth'); /* menampilkan list kelompok */
    Route::get('/master/coalistkategori/{id}', [CoaController::class, 'listkategori'])->name('akuntansi01.master.coa_listkategori')->middleware('auth'); /* menampilkan list kategori */
    Route::get('/master/coalistkategori2', [CoaController::class, 'listkategori2'])->name('akuntansi01.master.coa_listkategori2')->middleware('auth'); /* menampilkan list kategori */


});

