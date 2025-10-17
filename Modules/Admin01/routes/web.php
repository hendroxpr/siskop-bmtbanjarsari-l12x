<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Modules\Admin01\Http\Controllers\master\AnggotaController;
use Modules\Admin01\Http\Controllers\master\DesaController;
use Modules\Admin01\Http\Controllers\master\KabupatenController;
use Modules\Admin01\Http\Controllers\master\KecamatanController;
use Modules\Admin01\Http\Controllers\master\PropinsiController;

// use Modules\Admin01\Http\Controllers\master\MaminController;


// use Modules\Admin01\Http\Controllers\master\AnggotaController;
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
//     Route::resource('admin01', Admin01Controller::class)->names('Admin01');
// });

Route::prefix('admin01')->group(function() {
    // Route::get('/', 'Admin01Controller@index');

    /* master - propinsi */
    Route::get('/master/propinsi', [PropinsiController::class, 'index'])->name('admin01.master.propinsi.index')->middleware('auth'); /* halaman propinsi */
    Route::get('/master/propinsishow', [PropinsiController::class, 'show'])->name('admin01.master.propinsi_show')->middleware('auth'); /* menampilkan data propinsi pada datatable javascript */
    Route::post('/master/propinsicreate', [PropinsiController::class, 'create'])->name('admin01.master.propinsi_create')->middleware('auth'); /* menambah propinsi */
    Route::get('/master/propinsiedit/{id}', [PropinsiController::class, 'edit'])->name('admin01.master.propinsi_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::get('/master/propinsidestroy/{id}', [PropinsiController::class, 'destroy'])->name('admin01.master.propinsi_destroy')->middleware('auth'); /* hapus data propinsi */

    /* master - kabupaten */
    Route::get('/master/kabupaten', [KabupatenController::class, 'index'])->name('admin01.master.kabupaten.index')->middleware('auth'); /* halaman kabupaten */
    Route::get('/master/kabupatenshow', [KabupatenController::class, 'show'])->name('admin01.master.kabupaten_show')->middleware('auth'); /* menampilkan data kabupaten pada datatable javascript */
    Route::post('/master/kabupatencreate', [KabupatenController::class, 'create'])->name('admin01.master.kabupaten_create')->middleware('auth'); /* menambah kabupaten */
    Route::post('/master/kabupatenkirimsyarat', [KabupatenController::class, 'kirimsyarat'])->name('admin01.master.kabupaten_kirimsyarat')->middleware('auth'); /* kirimsyarat */
    Route::get('/master/kabupatenedit/{id}', [KabupatenController::class, 'edit'])->name('admin01.master.kabupaten_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::get('/master/kabupatendestroy/{id}', [KabupatenController::class, 'destroy'])->name('admin01.master.kabupaten_destroy')->middleware('auth'); /* hapus data kabupaten */
    
    /* master - kecamatan */
    Route::get('/master/kecamatan', [KecamatanController::class, 'index'])->name('admin01.master.kecamatan.index')->middleware('auth'); /* halaman kecamatan */
    Route::get('/master/kecamatanshow', [KecamatanController::class, 'show'])->name('admin01.master.kecamatan_show')->middleware('auth'); /* menampilkan data kecamatan pada datatable javascript */
    Route::post('/master/kecamatancreate', [KecamatanController::class, 'create'])->name('admin01.master.kecamatan_create')->middleware('auth'); /* menambah kecamatan */
    Route::post('/master/kecamatankirimsyarat', [KecamatanController::class, 'kirimsyarat'])->name('admin01.master.kecamatan_kirimsyarat')->middleware('auth'); /* kirimsyarat */
    Route::post('/master/kecamatankirimsyarat2', [KecamatanController::class, 'kirimsyarat2'])->name('admin01.master.kecamatan_kirimsyarat2')->middleware('auth'); /* kirimsyarat */
    Route::get('/master/kecamatanedit/{id}', [KecamatanController::class, 'edit'])->name('admin01.master.kecamatan_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::get('/master/kecamatandestroy/{id}', [KecamatanController::class, 'destroy'])->name('admin01.master.kecamatan_destroy')->middleware('auth'); /* hapus data kecamatan */
    
    /* master - desa */
    Route::get('/master/desa', [DesaController::class, 'index'])->name('admin01.master.desa.index')->middleware('auth'); /* halaman desa */
    Route::get('/master/desashow', [DesaController::class, 'show'])->name('admin01.master.desa_show')->middleware('auth'); /* menampilkan data desa pada datatable javascript */
    Route::post('/master/desacreate', [DesaController::class, 'create'])->name('admin01.master.desa_create')->middleware('auth'); /* menambah desa */
    Route::post('/master/desakirimsyarat', [DesaController::class, 'kirimsyarat'])->name('admin01.master.desa_kirimsyarat')->middleware('auth'); /* kirimsyarat */
    Route::post('/master/desakirimsyarat2', [DesaController::class, 'kirimsyarat2'])->name('admin01.master.desa_kirimsyarat2')->middleware('auth'); /* kirimsyarat */
    Route::get('/master/desaedit/{id}', [DesaController::class, 'edit'])->name('admin01.master.desa_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::get('/master/desadestroy/{id}', [DesaController::class, 'destroy'])->name('admin01.master.desa_destroy')->middleware('auth'); /* hapus data desa */

    /* master - anggota */
    Route::get('/master/anggota', [AnggotaController::class, 'index'])->name('admin01.master.anggota.index')->middleware('auth'); /* halaman anggota */
    Route::get('/master/anggotashow', [AnggotaController::class, 'show'])->name('admin01.master.anggota_show')->middleware('auth'); /* menampilkan data anggota pada datatable javascript */
    Route::get('/master/anggotashowdesa', [AnggotaController::class, 'showdesa'])->name('admin01.master.anggota_showdesa')->middleware('auth'); /* menampilkan data desa pada datatable javascript */
    Route::post('/master/anggotacreate', [AnggotaController::class, 'create'])->name('admin01.master.anggota_create')->middleware('auth'); /* menambah anggota */
    Route::get('/master/anggotaedit/{id}', [AnggotaController::class, 'edit'])->name('admin01.master.anggota_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::get('/master/anggotadestroy/{id}', [AnggotaController::class, 'destroy'])->name('admin01.master.anggota_destroy')->middleware('auth'); /* hapus data anggota */
    Route::post('/master/anggotania', [AnggotaController::class, 'nia'])->name('admin01.master.anggota_nia')->middleware('auth'); /* generate nia */

});