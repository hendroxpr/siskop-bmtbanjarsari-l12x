<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Modules\Tabungan01\Http\Controllers\laporan\RekeningkoranController;
use Modules\Tabungan01\Http\Controllers\master\NasabahtabunganController;
use Modules\Tabungan01\Http\Controllers\master\UangController;
use Modules\Tabungan01\Http\Controllers\transaksi\TsetorController;

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
    Route::get('/master/nasabahtabunganshowanggota', [NasabahtabunganController::class, 'showanggota'])->name('tabungan01.master.nasabahtabungan_showanggota')->middleware('auth'); /* menampilkan data anggota pada datatable javascript */
    Route::post('/master/nasabahtabungankirimsyarat', [NasabahtabunganController::class, 'kirimsyarat'])->name('tabungan01.master.nasabahtabungan_kirimsyarat')->middleware('auth'); /* kirim syarat */
    Route::post('/master/nasabahtabungancreate', [NasabahtabunganController::class, 'create'])->name('tabungan01.master.nasabahtabungan_create')->middleware('auth'); /* menambah nasabahtabungan */
    Route::post('/master/nasabahtabunganimport', [NasabahtabunganController::class, 'importdata'])->name('tabungan01.master.nasabahtabungan_import')->middleware('auth'); /* import nasabahtabungan */
    Route::get('/master/nasabahtabunganedit/{id}', [NasabahtabunganController::class, 'edit'])->name('tabungan01.master.nasabahtabungan_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::post('/master/nasabahtabunganupdate', [NasabahtabunganController::class, 'update'])->name('tabungan01.master.nasabahtabungan_update')->middleware('auth'); /* update data nasabahtabungan */
    Route::get('/master/nasabahtabungandestroy/{id}', [NasabahtabunganController::class, 'destroy'])->name('tabungan01.master.nasabahtabungan_destroy')->middleware('auth'); /* hapus data nasabahtabungan */
    Route::get('/master/nasabahtabunganlistproduktabungan', [NasabahtabunganController::class, 'listproduktabungan'])->name('tabungan01.master.nasabahtabungan_listproduktabungan')->middleware('auth'); /* menampilkan list produktabungan */
    Route::get('/master/nasabahtabunganprintcover/{norek}', [NasabahtabunganController::class, 'printcover'])->name('tabungan01.master.nasabahtabungan_printcover')->middleware('auth'); /* print cover */    
    Route::get('/master/nasabahtabunganprintheader/{norek}', [NasabahtabunganController::class, 'printheader'])->name('tabungan01.master.nasabahtabungan_printheader')->middleware('auth'); /* print header */    

    /* transaksi - tsetor */
    Route::get('/transaksi/tsetor', [TsetorController::class, 'index'])->name('tabungan01.transaksi.tsetor.index')->middleware('auth'); /* halaman tsetor */
    Route::get('/transaksi/tsetorshow', [TsetorController::class, 'show'])->name('tabungan01.transaksi.tsetor_show')->middleware('auth'); /* menampilkan data tsetor pada datatable javascript */
    Route::get('/transaksi/tsetorshownasabah', [TsetorController::class, 'shownasabah'])->name('tabungan01.transaksi.tsetor_shownasabah')->middleware('auth'); /* menampilkan data nasabah pada datatable javascript */
    Route::post('/transaksi/tsetorkirimsyarat', [TsetorController::class, 'kirimsyarat'])->name('tabungan01.transaksi.tsetor_kirimsyarat')->middleware('auth'); /* kirim syarat */
    Route::post('/transaksi/tsetorcariid', [TsetorController::class, 'cariid'])->name('tabungan01.transaksi.tsetor_cariid')->middleware('auth'); /* cari data nasabah */
    Route::post('/transaksi/tsetornomorbukti', [TsetorController::class, 'nomorbukti'])->name('tabungan01.transaksi.tsetor_nomorbukti')->middleware('auth'); /* buat nomorbukti */
    Route::post('/transaksi/tsetornomorposting', [TsetorController::class, 'nomorposting'])->name('tabungan01.transaksi.tsetor_nomorposting')->middleware('auth'); /* buat nomorposting */
    Route::get('/transaksi/tsetorlistnasabah', [TsetorController::class, 'listnasabah'])->name('tabungan01.transaksi.tsetor_listnasabah')->middleware('auth'); /* menampilkan list nasabah */
    Route::post('/transaksi/tsetorcreate', [TsetorController::class, 'create'])->name('tabungan01.transaksi.tsetor_create')->middleware('auth'); /* menambah data tsetor */
    Route::get('/transaksi/tsetoredit/{id}', [TsetorController::class, 'edit'])->name('tabungan01.transaksi.tsetor_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::post('/transaksi/tsetorupdate', [TsetorController::class, 'update'])->name('tabungan01.transaksi.tsetor_update')->middleware('auth'); /* update data tsetor */
    Route::post('/transaksi/tsetorposting', [TsetorController::class, 'posting'])->name('tabungan01.transaksi.tsetor_posting')->middleware('auth'); /* posting data tsetor */
    Route::get('/transaksi/tsetordestroy/{id}', [TsetorController::class, 'destroy'])->name('tabungan01.transaksi.tsetor_destroy')->middleware('auth'); /* hapus data tsetor */
    
    /* laporan - rekeningkoran */
    Route::get('/laporan/rekeningkoran', [RekeningkoranController::class, 'index'])->name('tabungan01.laporan.rekeningkoran.index')->middleware('auth'); /* halaman rekeningkoran */
    Route::get('/laporan/rekeningkoranshow', [RekeningkoranController::class, 'show'])->name('tabungan01.laporan.rekeningkoran_show')->middleware('auth'); /* menampilkan data rekeningkoran pada datatable javascript */
    Route::get('/laporan/rekeningkoranshownasabah', [RekeningkoranController::class, 'shownasabah'])->name('tabungan01.laporan.rekeningkoran_shownasabah')->middleware('auth'); /* menampilkan data nasabah pada datatable javascript */
    Route::post('/laporan/rekeningkorankirimsyarat', [RekeningkoranController::class, 'kirimsyarat'])->name('tabungan01.laporan.rekeningkoran_kirimsyarat')->middleware('auth'); /* kirim syarat */
    Route::post('/laporan/rekeningkorancariid', [RekeningkoranController::class, 'cariid'])->name('tabungan01.laporan.rekeningkoran_cariid')->middleware('auth'); /* cari data nasabah */
    Route::post('/laporan/rekeningkorannomorbukti', [RekeningkoranController::class, 'nomorbukti'])->name('tabungan01.laporan.rekeningkoran_nomorbukti')->middleware('auth'); /* buat nomorbukti */
    Route::post('/laporan/rekeningkorannomorposting', [RekeningkoranController::class, 'nomorposting'])->name('tabungan01.laporan.rekeningkoran_nomorposting')->middleware('auth'); /* buat nomorposting */
    Route::get('/laporan/rekeningkoranlistnasabah', [RekeningkoranController::class, 'listnasabah'])->name('tabungan01.laporan.rekeningkoran_listnasabah')->middleware('auth'); /* menampilkan list nasabah */
    Route::post('/laporan/rekeningkorancreate', [RekeningkoranController::class, 'create'])->name('tabungan01.laporan.rekeningkoran_create')->middleware('auth'); /* menambah data rekeningkoran */
    Route::get('/laporan/rekeningkoranedit/{id}', [RekeningkoranController::class, 'edit'])->name('tabungan01.laporan.rekeningkoran_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::post('/laporan/rekeningkoranupdate', [RekeningkoranController::class, 'update'])->name('tabungan01.laporan.rekeningkoran_update')->middleware('auth'); /* update data rekeningkoran */
    Route::post('/laporan/rekeningkoranposting', [RekeningkoranController::class, 'posting'])->name('tabungan01.laporan.rekeningkoran_posting')->middleware('auth'); /* posting data rekeningkoran */
    Route::get('/laporan/rekeningkorandestroy/{id}', [RekeningkoranController::class, 'destroy'])->name('tabungan01.laporan.rekeningkoran_destroy')->middleware('auth'); /* hapus data rekeningkoran */
    Route::get('/laporan/rekeningkoranprintdetail', [RekeningkoranController::class, 'printdetail'])->name('tabungan01.laporan.rekeningkoran_printdetail')->middleware('auth'); /* print detail */


});
