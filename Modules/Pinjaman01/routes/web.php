<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use Modules\Pinjaman01\Http\Controllers\laporan\DetailpinjamanController;
use Modules\Pinjaman01\Http\Controllers\laporan\RekappinjamanController;
use Modules\Pinjaman01\Http\Controllers\transaksi\JurnalangsuranController;
use Modules\Pinjaman01\Http\Controllers\transaksi\JurnalpinjamanController;

// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::resource('pinjaman01', Pinjaman01Controller::class)->names('pinjaman01');

// use Modules\Pinjaman01\Http\Controllers\Pinjaman01Controller;

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
//     Route::resource('pinjaman01', Pinjaman01Controller::class)->names('Akuntans101');
// });

Route::prefix('pinjaman01')->group(function() {
    // Route::get('/', 'Pinjaman01Controller@index');
    
    /* transaksi - jurnalpinjaman */
    Route::get('/transaksi/jurnalpinjaman', [JurnalpinjamanController::class, 'index'])->name('pinjaman01.transaksi.jurnalpinjaman.index')->middleware('auth'); /* halaman jurnalpinjaman */
    Route::get('/transaksi/jurnalpinjamanshow', [JurnalpinjamanController::class, 'show'])->name('pinjaman01.transaksi.jurnalpinjaman_show')->middleware('auth'); /* menampilkan data jurnalpinjaman pada datatable javascript */
    Route::get('/transaksi/jurnalpinjamanshowanggota', [JurnalpinjamanController::class, 'showanggota'])->name('pinjaman01.transaksi.jurnalpinjaman_showanggota')->middleware('auth'); /* menampilkan data anggota pada datatable javascript */
    Route::post('/transaksi/jurnalpinjamankirimsyarat', [JurnalpinjamanController::class, 'kirimsyarat'])->name('pinjaman01.transaksi.jurnalpinjaman_kirimsyarat')->middleware('auth'); /* kirim syarat */
    Route::post('/transaksi/jurnalpinjamancariid', [JurnalpinjamanController::class, 'cariid'])->name('pinjaman01.transaksi.jurnalpinjaman_cariid')->middleware('auth'); /* cari data nasabah */
    Route::post('/transaksi/jurnalpinjamannomorbukti', [JurnalpinjamanController::class, 'nomorbukti'])->name('pinjaman01.transaksi.jurnalpinjaman_nomorbukti')->middleware('auth'); /* buat nomorbukti */
    Route::post('/transaksi/jurnalpinjamannomorposting', [JurnalpinjamanController::class, 'nomorposting'])->name('pinjaman01.transaksi.jurnalpinjaman_nomorposting')->middleware('auth'); /* buat nomorposting */
    Route::get('/transaksi/jurnalpinjamanlistanggota', [JurnalpinjamanController::class, 'listanggota'])->name('pinjaman01.transaksi.jurnalpinjaman_listanggota')->middleware('auth'); /* menampilkan list anggota */
    Route::get('/transaksi/jurnalpinjamanlistcoa', [JurnalpinjamanController::class, 'listcoa'])->name('pinjaman01.transaksi.jurnalpinjaman_listcoa')->middleware('auth'); /* menampilkan list coa */
    Route::get('/transaksi/jurnalpinjamanlistsandi', [JurnalpinjamanController::class, 'listsandi'])->name('pinjaman01.transaksi.jurnalpinjaman_listsandi')->middleware('auth'); /* menampilkan list sandi */
    Route::post('/transaksi/jurnalpinjamancreate', [JurnalpinjamanController::class, 'create'])->name('pinjaman01.transaksi.jurnalpinjaman_create')->middleware('auth'); /* menambah data jurnalpinjaman */
    Route::get('/transaksi/jurnalpinjamanedit/{id}', [JurnalpinjamanController::class, 'edit'])->name('pinjaman01.transaksi.jurnalpinjaman_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::get('/transaksi/jurnalpinjamanjenispinjaman/{id}', [JurnalpinjamanController::class, 'jenispinjaman'])->name('pinjaman01.transaksi.jurnalpinjaman_jenispinjaman')->middleware('auth'); /* menampilkan data jenispinjaman yang dipilih */
    Route::post('/transaksi/jurnalpinjamanupdate', [JurnalpinjamanController::class, 'update'])->name('pinjaman01.transaksi.jurnalpinjaman_update')->middleware('auth'); /* update data jurnalpinjaman */
    Route::post('/transaksi/jurnalpinjamanposting', [JurnalpinjamanController::class, 'posting'])->name('pinjaman01.transaksi.jurnalpinjaman_posting')->middleware('auth'); /* posting data jurnalpinjaman */
    Route::get('/transaksi/jurnalpinjamandestroy/{id}', [JurnalpinjamanController::class, 'destroy'])->name('pinjaman01.transaksi.jurnalpinjaman_destroy')->middleware('auth'); /* hapus data jurnalpinjaman */
    Route::get('/transaksi/jurnalpinjamanprintkwitansi', [JurnalpinjamanController::class, 'printkwitansi'])->name('pinjaman01.transaksi.jurnalpinjaman_printkwitansi')->middleware('auth'); /* printkwitansi */
    
    /* transaksi - jurnalangsuran */
    Route::get('/transaksi/jurnalangsuran', [JurnalangsuranController::class, 'index'])->name('pinjaman01.transaksi.jurnalangsuran.index')->middleware('auth'); /* halaman jurnalangsuran */
    Route::get('/transaksi/jurnalangsuranshow', [JurnalangsuranController::class, 'show'])->name('pinjaman01.transaksi.jurnalangsuran_show')->middleware('auth'); /* menampilkan data jurnalangsuran pada datatable javascript */
    Route::get('/transaksi/jurnalangsuranshowanggota', [JurnalangsuranController::class, 'showanggota'])->name('pinjaman01.transaksi.jurnalangsuran_showanggota')->middleware('auth'); /* menampilkan data anggota pada datatable javascript */
    Route::post('/transaksi/jurnalangsurankirimsyarat', [JurnalangsuranController::class, 'kirimsyarat'])->name('pinjaman01.transaksi.jurnalangsuran_kirimsyarat')->middleware('auth'); /* kirim syarat */
    Route::post('/transaksi/jurnalangsurancariid', [JurnalangsuranController::class, 'cariid'])->name('pinjaman01.transaksi.jurnalangsuran_cariid')->middleware('auth'); /* cari data nasabah */
    Route::post('/transaksi/jurnalangsurannomorbukti', [JurnalangsuranController::class, 'nomorbukti'])->name('pinjaman01.transaksi.jurnalangsuran_nomorbukti')->middleware('auth'); /* buat nomorbukti */
    Route::post('/transaksi/jurnalangsurannomorposting', [JurnalangsuranController::class, 'nomorposting'])->name('pinjaman01.transaksi.jurnalangsuran_nomorposting')->middleware('auth'); /* buat nomorposting */
    Route::get('/transaksi/jurnalangsuranlistanggota', [JurnalangsuranController::class, 'listanggota'])->name('pinjaman01.transaksi.jurnalangsuran_listanggota')->middleware('auth'); /* menampilkan list anggota */
    Route::get('/transaksi/jurnalangsuranlistcoa', [JurnalangsuranController::class, 'listcoa'])->name('pinjaman01.transaksi.jurnalangsuran_listcoa')->middleware('auth'); /* menampilkan list coa */
    Route::get('/transaksi/jurnalangsuranlistsandi', [JurnalangsuranController::class, 'listsandi'])->name('pinjaman01.transaksi.jurnalangsuran_listsandi')->middleware('auth'); /* menampilkan list sandi */
    Route::post('/transaksi/jurnalangsurancreate', [JurnalangsuranController::class, 'create'])->name('pinjaman01.transaksi.jurnalangsuran_create')->middleware('auth'); /* menambah data jurnalangsuran */
    Route::get('/transaksi/jurnalangsuranedit/{id}', [JurnalangsuranController::class, 'edit'])->name('pinjaman01.transaksi.jurnalangsuran_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::get('/transaksi/jurnalangsuranjenisangsuran/{id}', [JurnalangsuranController::class, 'jenisangsuran'])->name('pinjaman01.transaksi.jurnalangsuran_jenisangsuran')->middleware('auth'); /* menampilkan data jenisangsuran yang dipilih */
    Route::post('/transaksi/jurnalangsuranupdate', [JurnalangsuranController::class, 'update'])->name('pinjaman01.transaksi.jurnalangsuran_update')->middleware('auth'); /* update data jurnalangsuran */
    Route::post('/transaksi/jurnalangsuranposting', [JurnalangsuranController::class, 'posting'])->name('pinjaman01.transaksi.jurnalangsuran_posting')->middleware('auth'); /* posting data jurnalangsuran */
    Route::get('/transaksi/jurnalangsurandestroy/{id}', [JurnalangsuranController::class, 'destroy'])->name('pinjaman01.transaksi.jurnalangsuran_destroy')->middleware('auth'); /* hapus data jurnalangsuran */
    Route::get('/transaksi/jurnalangsuranprintkwitansi', [JurnalangsuranController::class, 'printkwitansi'])->name('pinjaman01.transaksi.jurnalangsuran_printkwitansi')->middleware('auth'); /* printkwitansi */

    /* laporan - rekappinjaman */
    Route::get('/laporan/rekappinjaman', [RekappinjamanController::class, 'index'])->name('pinjaman01.laporan.rekappinjaman.index')->middleware('auth'); /* halaman rekappinjaman */
    Route::get('/laporan/rekappinjamanshow', [RekappinjamanController::class, 'show'])->name('pinjaman01.laporan.rekappinjaman_show')->middleware('auth'); /* menampilkan data rekappinjaman pada datatable javascript */
    Route::post('/laporan/rekappinjamankirimsyarat', [RekappinjamanController::class, 'kirimsyarat'])->name('pinjaman01.laporan.rekappinjaman_kirimsyarat')->middleware('auth'); /* kirim syarat */
    Route::get('/laporan/rekappinjamanlistanggota', [RekappinjamanController::class, 'listanggota'])->name('pinjaman01.laporan.rekappinjaman_listanggota')->middleware('auth'); /* menampilkan list anggota */
    Route::post('/laporan/rekappinjamancreate', [RekappinjamanController::class, 'create'])->name('pinjaman01.laporan.rekappinjaman_create')->middleware('auth'); /* menambah data rekappinjaman */
    Route::get('/laporan/rekappinjamanedit/{id}', [RekappinjamanController::class, 'edit'])->name('pinjaman01.laporan.rekappinjaman_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::post('/laporan/rekappinjamanupdate', [RekappinjamanController::class, 'update'])->name('pinjaman01.laporan.rekappinjaman_update')->middleware('auth'); /* update data rekappinjaman */
    Route::get('/laporan/rekappinjamandestroy/{id}', [RekappinjamanController::class, 'destroy'])->name('pinjaman01.laporan.rekappinjaman_destroy')->middleware('auth'); /* hapus data rekappinjaman */
    Route::get('/laporan/rekappinjamanprintkwitansi', [RekappinjamanController::class, 'printkwitansi'])->name('pinjaman01.laporan.rekappinjaman_printkwitansi')->middleware('auth'); /* printkwitansi */
    
    /* laporan - detailpinjaman */
    Route::get('/laporan/detailpinjaman', [DetailpinjamanController::class, 'index'])->name('pinjaman01.laporan.detailpinjaman.index')->middleware('auth'); /* halaman detailpinjaman */
    Route::get('/laporan/detailpinjamanshow', [DetailpinjamanController::class, 'show'])->name('pinjaman01.laporan.detailpinjaman_show')->middleware('auth'); /* menampilkan data detailpinjaman pada datatable javascript */
    Route::get('/laporan/detailpinjamanshowanggota', [DetailpinjamanController::class, 'showanggota'])->name('pinjaman01.laporan.detailpinjaman_showanggota')->middleware('auth'); /* menampilkan data pinjaman pada datatable javascript */
    Route::post('/laporan/detailpinjamankirimsyarat', [DetailpinjamanController::class, 'kirimsyarat'])->name('pinjaman01.laporan.detailpinjaman_kirimsyarat')->middleware('auth'); /* kirim syarat */
    Route::get('/laporan/detailpinjamanlistanggota', [DetailpinjamanController::class, 'listanggota'])->name('pinjaman01.laporan.detailpinjaman_listanggota')->middleware('auth'); /* menampilkan list anggota */
    Route::post('/laporan/detailpinjamancreate', [DetailpinjamanController::class, 'create'])->name('pinjaman01.laporan.detailpinjaman_create')->middleware('auth'); /* menambah data detailpinjaman */
    Route::post('/laporan/detailpinjamancariid', [DetailpinjamanController::class, 'cariid'])->name('pinjaman01.laporan.detailpinjaman_cariid')->middleware('auth'); /* cari kode jurnalpinjaman */
    Route::get('/laporan/detailpinjamanedit/{id}', [DetailpinjamanController::class, 'edit'])->name('pinjaman01.laporan.detailpinjaman_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::post('/laporan/detailpinjamanupdate', [DetailpinjamanController::class, 'update'])->name('pinjaman01.laporan.detailpinjaman_update')->middleware('auth'); /* update data detailpinjaman */
    Route::get('/laporan/detailpinjamandestroy/{id}', [DetailpinjamanController::class, 'destroy'])->name('pinjaman01.laporan.detailpinjaman_destroy')->middleware('auth'); /* hapus data detailpinjaman */
    Route::get('/laporan/detailpinjamanprintkwitansi', [DetailpinjamanController::class, 'printkwitansi'])->name('pinjaman01.laporan.detailpinjaman_printkwitansi')->middleware('auth'); /* printkwitansi */

    
});

