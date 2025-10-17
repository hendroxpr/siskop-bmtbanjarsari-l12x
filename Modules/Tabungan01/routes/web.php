<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Modules\Tabungan01\Http\Controllers\master\NasabahtabunganController;
use Modules\Tabungan01\Http\Controllers\master\UangController;

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
//     Route::resource('tabungan01', Tabungan01Controller::class)->names('Tabungan01');
// });

Route::prefix('tabungan01')->group(function() {
    // Route::get('/', 'Tabungan01Controller@index');

    /* master - uang */
    Route::get('/master/uang', [UangController::class, 'index'])->name('tabungan01.master.uang.index')->middleware('auth'); /* halaman uang */
    Route::get('/master/uangshow', [UangController::class, 'show'])->name('tabungan01.master.uang_show')->middleware('auth'); /* menampilkan data uang pada datatable javascript */
    Route::post('/master/uangcreate', [UangController::class, 'create'])->name('tabungan01.master.uang_create')->middleware('auth'); /* menambah uang */
    Route::get('/master/uangedit/{id}', [UangController::class, 'edit'])->name('tabungan01.master.uang_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::get('/master/uangdestroy/{id}', [UangController::class, 'destroy'])->name('tabungan01.master.uang_destroy')->middleware('auth'); /* hapus data uang */
    Route::get('/master/uanglistbahan', [UangController::class, 'listbahan'])->name('tabungan01.master.uang_listbahan')->middleware('auth'); /* menampilkan list bahan */

    /* master - nasabahtabungan */
    Route::get('/master/nasabahtabungan', [NasabahtabunganController::class, 'index'])->name('tabungan01.master.nasabahtabungan.index')->middleware('auth'); /* halaman nasabahtabungan */
    Route::get('/master/nasabahtabunganshow', [NasabahtabunganController::class, 'show'])->name('tabungan01.master.nasabahtabungan_show')->middleware('auth'); /* menampilkan data nasabahtabungan pada datatable javascript */
    Route::post('/master/nasabahtabungankirimsyarat', [NasabahtabunganController::class, 'kirimsyarat'])->name('tabungan01.master.nasabahtabungan_kirimsyarat')->middleware('auth'); /* kirim syarat */
    Route::post('/master/nasabahtabungancreate', [NasabahtabunganController::class, 'create'])->name('tabungan01.master.nasabahtabungan_create')->middleware('auth'); /* menambah nasabahtabungan */
    Route::post('/master/nasabahtabunganimport', [NasabahtabunganController::class, 'importdata'])->name('tabungan01.master.nasabahtabungan_import')->middleware('auth'); /* import nasabahtabungan */
    Route::get('/master/nasabahtabunganedit/{id}', [NasabahtabunganController::class, 'edit'])->name('tabungan01.master.nasabahtabungan_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::post('/master/nasabahtabunganupdate', [NasabahtabunganController::class, 'update'])->name('tabungan01.master.nasabahtabungan_update')->middleware('auth'); /* update data nasabahtabungan */
    Route::get('/master/nasabahtabungandestroy/{id}', [NasabahtabunganController::class, 'destroy'])->name('tabungan01.master.nasabahtabungan_destroy')->middleware('auth'); /* hapus data nasabahtabungan */
    Route::get('/master/nasabahtabunganlistproduk', [NasabahtabunganController::class, 'listproduk'])->name('tabungan01.master.nasabahtabungan_listproduk')->middleware('auth'); /* menampilkan list produk */
    Route::get('/master/nasabahtabunganprintcover/{norek}', [NasabahtabunganController::class, 'printcover'])->name('tabungan01.master.nasabahtabungan_printcover')->middleware('auth'); /* print cover */    
    Route::get('/master/nasabahtabunganprintheader/{norek}', [NasabahtabunganController::class, 'printheader'])->name('tabungan01.master.nasabahtabungan_printheader')->middleware('auth'); /* print header */    



});
