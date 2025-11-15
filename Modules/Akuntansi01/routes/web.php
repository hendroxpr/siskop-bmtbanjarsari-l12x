<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;
use Modules\Akuntansi01\Http\Controllers\laporan\HistoricoaController;
use Modules\Akuntansi01\Http\Controllers\laporan\NeracalajurController;
use Modules\Akuntansi01\Http\Controllers\master\CoaController;
use Modules\Akuntansi01\Http\Controllers\master\JenispinjamanController;
use Modules\Akuntansi01\Http\Controllers\master\ProduktabunganController;
use Modules\Akuntansi01\Http\Controllers\pengaturan\SetingjurnalController;
use Modules\Akuntansi01\Http\Controllers\transaksi\JurnalumumController;
use Modules\Akuntansi01\Http\Controllers\master\JenissimpananController;

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
    
    /* master - jenissimpanan */
    Route::get('/master/jenissimpanan', [JenissimpananController::class, 'index'])->name('akuntansi01.master.jenissimpanan.index')->middleware('auth'); /* halaman jenissimpanan */
    Route::get('/master/jenissimpananshow', [JenissimpananController::class, 'show'])->name('akuntansi01.master.jenissimpanan_show')->middleware('auth'); /* menampilkan data jenissimpanan pada datatable javascript */
    Route::post('/master/jenissimpanancreate', [JenissimpananController::class, 'create'])->name('akuntansi01.master.jenissimpanan_create')->middleware('auth'); /* menambah jenissimpanan */
    Route::get('/master/jenissimpananedit/{id}', [JenissimpananController::class, 'edit'])->name('akuntansi01.master.jenissimpanan_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::get('/master/jenissimpanandestroy/{id}', [JenissimpananController::class, 'destroy'])->name('akuntansi01.master.jenissimpanan_destroy')->middleware('auth'); /* hapus data jenissimpanan */
    Route::get('/master/jenissimpananlistcoa', [JenissimpananController::class, 'listcoa'])->name('akuntansi01.master.jenissimpanan_listcoa')->middleware('auth'); /* menampilkan list coa */
    Route::get('/master/jenissimpananlistjenisjurnal', [JenissimpananController::class, 'listjenisjurnal'])->name('akuntansi01.master.jenissimpanan_listjenisjurnal')->middleware('auth'); /* menampilkan list jenisjurnal */
    
    /* master - jenispinjaman */
    Route::get('/master/jenispinjaman', [JenispinjamanController::class, 'index'])->name('akuntansi01.master.jenispinjaman.index')->middleware('auth'); /* halaman jenispinjaman */
    Route::get('/master/jenispinjamanshow', [JenispinjamanController::class, 'show'])->name('akuntansi01.master.jenispinjaman_show')->middleware('auth'); /* menampilkan data jenispinjaman pada datatable javascript */
    Route::post('/master/jenispinjamancreate', [JenispinjamanController::class, 'create'])->name('akuntansi01.master.jenispinjaman_create')->middleware('auth'); /* menambah jenispinjaman */
    Route::get('/master/jenispinjamanedit/{id}', [JenispinjamanController::class, 'edit'])->name('akuntansi01.master.jenispinjaman_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::get('/master/jenispinjamandestroy/{id}', [JenispinjamanController::class, 'destroy'])->name('akuntansi01.master.jenispinjaman_destroy')->middleware('auth'); /* hapus data jenispinjaman */
    Route::get('/master/jenispinjamanlistcoa', [JenispinjamanController::class, 'listcoa'])->name('akuntansi01.master.jenispinjaman_listcoa')->middleware('auth'); /* menampilkan list coa */
    Route::get('/master/jenispinjamanlistjenisjurnal', [JenispinjamanController::class, 'listjenisjurnal'])->name('akuntansi01.master.jenispinjaman_listjenisjurnal')->middleware('auth'); /* menampilkan list jenisjurnal */
    
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

    /* transaksi - jurnalumum */
    Route::get('/transaksi/jurnalumum', [JurnalumumController::class, 'index'])->name('akuntansi01.transaksi.jurnalumum.index')->middleware('auth'); /* halaman jurnalumum */
    Route::get('/transaksi/jurnalumumshow', [JurnalumumController::class, 'show'])->name('akuntansi01.transaksi.jurnalumum_show')->middleware('auth'); /* menampilkan data jurnalumum pada datatable javascript */
    Route::get('/transaksi/jurnalumumshowanggota', [JurnalumumController::class, 'showanggota'])->name('akuntansi01.transaksi.jurnalumum_showanggota')->middleware('auth'); /* menampilkan data anggota pada datatable javascript */
    Route::post('/transaksi/jurnalumumkirimsyarat', [JurnalumumController::class, 'kirimsyarat'])->name('akuntansi01.transaksi.jurnalumum_kirimsyarat')->middleware('auth'); /* kirim syarat */
    Route::post('/transaksi/jurnalumumcariid', [JurnalumumController::class, 'cariid'])->name('akuntansi01.transaksi.jurnalumum_cariid')->middleware('auth'); /* cari data nasabah */
    Route::post('/transaksi/jurnalumumnomorbukti', [JurnalumumController::class, 'nomorbukti'])->name('akuntansi01.transaksi.jurnalumum_nomorbukti')->middleware('auth'); /* buat nomorbukti */
    Route::post('/transaksi/jurnalumumnomorposting', [JurnalumumController::class, 'nomorposting'])->name('akuntansi01.transaksi.jurnalumum_nomorposting')->middleware('auth'); /* buat nomorposting */
    Route::get('/transaksi/jurnalumumlistanggota', [JurnalumumController::class, 'listanggota'])->name('akuntansi01.transaksi.jurnalumum_listanggota')->middleware('auth'); /* menampilkan list anggota */
    Route::post('/transaksi/jurnalumumcreate', [JurnalumumController::class, 'create'])->name('akuntansi01.transaksi.jurnalumum_create')->middleware('auth'); /* menambah data jurnalumum */
    Route::get('/transaksi/jurnalumumedit/{id}', [JurnalumumController::class, 'edit'])->name('akuntansi01.transaksi.jurnalumum_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::post('/transaksi/jurnalumumupdate', [JurnalumumController::class, 'update'])->name('akuntansi01.transaksi.jurnalumum_update')->middleware('auth'); /* update data jurnalumum */
    Route::post('/transaksi/jurnalumumposting', [JurnalumumController::class, 'posting'])->name('akuntansi01.transaksi.jurnalumum_posting')->middleware('auth'); /* posting data jurnalumum */
    Route::get('/transaksi/jurnalumumdestroy/{id}', [JurnalumumController::class, 'destroy'])->name('akuntansi01.transaksi.jurnalumum_destroy')->middleware('auth'); /* hapus data jurnalumum */
    Route::get('/transaksi/jurnalumumprintkwitansi', [JurnalumumController::class, 'printkwitansi'])->name('akuntansi01.transaksi.jurnalumum_printkwitansi')->middleware('auth'); /* printkwitansi */

    /* laporan - historicoa */
    Route::get('/laporan/historicoa', [HistoricoaController::class, 'index'])->name('akuntansi01.laporan.historicoa.index')->middleware('auth'); /* halaman historicoa */
    Route::get('/laporan/historicoashow', [HistoricoaController::class, 'show'])->name('akuntansi01.laporan.historicoa_show')->middleware('auth'); /* menampilkan data historicoa pada datatable javascript */
    Route::post('/laporan/historicoakirimsyarat', [HistoricoaController::class, 'kirimsyarat'])->name('akuntansi01.laporan.historicoa_kirimsyarat')->middleware('auth'); /* kirim syarat */
    Route::get('/laporan/historicoalistcoa', [HistoricoaController::class, 'listcoa'])->name('akuntansi01.laporan.historicoa_listcoa')->middleware('auth'); /* list coa */

    /* laporan - neracalajur */
    Route::get('/laporan/neracalajur', [NeracalajurController::class, 'index'])->name('akuntansi01.laporan.neracalajur.index')->middleware('auth'); /* halaman neracalajur */
    Route::get('/laporan/neracalajurshow', [NeracalajurController::class, 'show'])->name('akuntansi01.laporan.neracalajur_show')->middleware('auth'); /* menampilkan data neracalajur pada datatable javascript */
    Route::post('/laporan/neracalajurkirimsyarat', [NeracalajurController::class, 'kirimsyarat'])->name('akuntansi01.laporan.neracalajur_kirimsyarat')->middleware('auth'); /* kirim syarat */
    Route::post('/laporan/neracalajurcariid', [NeracalajurController::class, 'cariid'])->name('akuntansi01.laporan.neracalajur_cariid')->middleware('auth'); /* cari data buku */
    Route::get('/laporan/neracalajurlistbulan', [NeracalajurController::class, 'listbulan'])->name('akuntansi01.laporan.neracalajur_listbulan')->middleware('auth'); /* menampilkan list bulan */

    /* pengaturan - setingjurnal */
    Route::get('/pengaturan/setingjurnal', [SetingjurnalController::class, 'index'])->name('akuntansi01.pengaturan.setingjurnal.index')->middleware('auth'); /* halaman setingjurnal */
    Route::get('/pengaturan/setingjurnalshow', [SetingjurnalController::class, 'show'])->name('akuntansi01.pengaturan.setingjurnal_show')->middleware('auth'); /* menampilkan data setingjurnal pada datatable javascript */
    Route::post('/pengaturan/setingjurnalcreate', [SetingjurnalController::class, 'create'])->name('akuntansi01.pengaturan.setingjurnal_create')->middleware('auth'); /* menambah setingjurnal */
    Route::get('/pengaturan/setingjurnaledit/{id}', [SetingjurnalController::class, 'edit'])->name('akuntansi01.pengaturan.setingjurnal_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::get('/pengaturan/setingjurnaldestroy/{id}', [SetingjurnalController::class, 'destroy'])->name('akuntansi01.pengaturan.setingjurnal_destroy')->middleware('auth'); /* hapus data setingjurnal */
    Route::get('/pengaturan/setingjurnallistcoa', [SetingjurnalController::class, 'listcoa'])->name('akuntansi01.pengaturan.setingjurnal_listcoa')->middleware('auth'); /* menampilkan list coa */
    Route::get('/pengaturan/setingjurnallistjenisjurnal', [SetingjurnalController::class, 'listjenisjurnal'])->name('akuntansi01.pengaturan.setingjurnal_listjenisjurnal')->middleware('auth'); /* menampilkan list jenisjurnal */
   

});

