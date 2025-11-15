<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Modules\Simpanan01\Http\Controllers\laporan\DetailsimpananController;
use Modules\Simpanan01\Http\Controllers\laporan\RekapsimpananController;
use Modules\Simpanan01\Http\Controllers\laporan\RekeningkoranController;
use Modules\Simpanan01\Http\Controllers\master\NasabahsimpananController;
use Modules\Simpanan01\Http\Controllers\master\UangController;
use Modules\Simpanan01\Http\Controllers\transaksi\SetorsimpananController;
use Modules\Simpanan01\Http\Controllers\transaksi\TariksimpananController;
use Modules\Simpanan01\Http\Controllers\transaksi\TransfersimpananController;
use Modules\Simpanan01\Http\Controllers\transaksi\TsetorController;

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
//     Route::resource('simpanan01', Simpanan01Controller::class)->names('Simpanan01');
// });

Route::prefix('simpanan01')->group(function() {
    /* master - uang */
    Route::get('/master/uang', [UangController::class, 'index'])->name('simpanan01.master.uang.index')->middleware('auth'); /* halaman uang */
    Route::get('/master/uangshow', [UangController::class, 'show'])->name('simpanan01.master.uang_show')->middleware('auth'); /* menampilkan data uang pada datatable javascript */
    Route::post('/master/uangcreate', [UangController::class, 'create'])->name('simpanan01.master.uang_create')->middleware('auth'); /* menambah uang */
    Route::get('/master/uangedit/{id}', [UangController::class, 'edit'])->name('simpanan01.master.uang_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::get('/master/uangdestroy/{id}', [UangController::class, 'destroy'])->name('simpanan01.master.uang_destroy')->middleware('auth'); /* hapus data uang */
    Route::get('/master/uanglistbahan', [UangController::class, 'listbahan'])->name('simpanan01.master.uang_listbahan')->middleware('auth'); /* menampilkan list bahan */

    /* master - nasabahsimpanan */
    Route::get('/master/nasabahsimpanan', [NasabahsimpananController::class, 'index'])->name('simpanan01.master.nasabahsimpanan.index')->middleware('auth'); /* halaman nasabahsimpanan */
    Route::get('/master/nasabahsimpananshow', [NasabahsimpananController::class, 'show'])->name('simpanan01.master.nasabahsimpanan_show')->middleware('auth'); /* menampilkan data nasabahsimpanan pada datatable javascript */
    Route::get('/master/nasabahsimpananshowanggota', [NasabahsimpananController::class, 'showanggota'])->name('simpanan01.master.nasabahsimpanan_showanggota')->middleware('auth'); /* menampilkan data anggota pada datatable javascript */
    Route::post('/master/nasabahsimpanankirimsyarat', [NasabahsimpananController::class, 'kirimsyarat'])->name('simpanan01.master.nasabahsimpanan_kirimsyarat')->middleware('auth'); /* kirim syarat */
    Route::post('/master/nasabahsimpanancreate', [NasabahsimpananController::class, 'create'])->name('simpanan01.master.nasabahsimpanan_create')->middleware('auth'); /* menambah nasabahsimpanan */
    Route::post('/master/nasabahsimpananimport', [NasabahsimpananController::class, 'importdata'])->name('simpanan01.master.nasabahsimpanan_import')->middleware('auth'); /* import nasabahsimpanan */
    Route::get('/master/nasabahsimpananedit/{id}', [NasabahsimpananController::class, 'edit'])->name('simpanan01.master.nasabahsimpanan_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::post('/master/nasabahsimpananupdate', [NasabahsimpananController::class, 'update'])->name('simpanan01.master.nasabahsimpanan_update')->middleware('auth'); /* update data nasabahsimpanan */
    Route::get('/master/nasabahsimpanandestroy/{id}', [NasabahsimpananController::class, 'destroy'])->name('simpanan01.master.nasabahsimpanan_destroy')->middleware('auth'); /* hapus data nasabahsimpanan */
    Route::get('/master/nasabahsimpananlistproduksimpanan', [NasabahsimpananController::class, 'listproduksimpanan'])->name('simpanan01.master.nasabahsimpanan_listproduksimpanan')->middleware('auth'); /* menampilkan list produksimpanan */
    Route::get('/master/nasabahsimpananprintcover/{norek}', [NasabahsimpananController::class, 'printcover'])->name('simpanan01.master.nasabahsimpanan_printcover')->middleware('auth'); /* print cover */    
    Route::get('/master/nasabahsimpananprintheader/{norek}', [NasabahsimpananController::class, 'printheader'])->name('simpanan01.master.nasabahsimpanan_printheader')->middleware('auth'); /* print header */    

    /* transaksi - tsetor */
    Route::get('/transaksi/tsetor', [TsetorController::class, 'index'])->name('simpanan01.transaksi.tsetor.index')->middleware('auth'); /* halaman tsetor */
    Route::get('/transaksi/tsetorshow', [TsetorController::class, 'show'])->name('simpanan01.transaksi.tsetor_show')->middleware('auth'); /* menampilkan data tsetor pada datatable javascript */
    Route::get('/transaksi/tsetorshownasabah', [TsetorController::class, 'shownasabah'])->name('simpanan01.transaksi.tsetor_shownasabah')->middleware('auth'); /* menampilkan data nasabah pada datatable javascript */
    Route::post('/transaksi/tsetorkirimsyarat', [TsetorController::class, 'kirimsyarat'])->name('simpanan01.transaksi.tsetor_kirimsyarat')->middleware('auth'); /* kirim syarat */
    Route::post('/transaksi/tsetorcariid', [TsetorController::class, 'cariid'])->name('simpanan01.transaksi.tsetor_cariid')->middleware('auth'); /* cari data nasabah */
    Route::post('/transaksi/tsetornomorbukti', [TsetorController::class, 'nomorbukti'])->name('simpanan01.transaksi.tsetor_nomorbukti')->middleware('auth'); /* buat nomorbukti */
    Route::post('/transaksi/tsetornomorposting', [TsetorController::class, 'nomorposting'])->name('simpanan01.transaksi.tsetor_nomorposting')->middleware('auth'); /* buat nomorposting */
    Route::get('/transaksi/tsetorlistnasabah', [TsetorController::class, 'listnasabah'])->name('simpanan01.transaksi.tsetor_listnasabah')->middleware('auth'); /* menampilkan list nasabah */
    Route::post('/transaksi/tsetorcreate', [TsetorController::class, 'create'])->name('simpanan01.transaksi.tsetor_create')->middleware('auth'); /* menambah data tsetor */
    Route::get('/transaksi/tsetoredit/{id}', [TsetorController::class, 'edit'])->name('simpanan01.transaksi.tsetor_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::post('/transaksi/tsetorupdate', [TsetorController::class, 'update'])->name('simpanan01.transaksi.tsetor_update')->middleware('auth'); /* update data tsetor */
    Route::post('/transaksi/tsetorposting', [TsetorController::class, 'posting'])->name('simpanan01.transaksi.tsetor_posting')->middleware('auth'); /* posting data tsetor */
    Route::get('/transaksi/tsetordestroy/{id}', [TsetorController::class, 'destroy'])->name('simpanan01.transaksi.tsetor_destroy')->middleware('auth'); /* hapus data tsetor */
  
    /* transaksi - setorsimpanan */
    Route::get('/transaksi/setorsimpanan', [SetorsimpananController::class, 'index'])->name('simpanan01.transaksi.setorsimpanan.index')->middleware('auth'); /* halaman setorsimpanan */
    Route::get('/transaksi/setorsimpananshow', [SetorsimpananController::class, 'show'])->name('simpanan01.transaksi.setorsimpanan_show')->middleware('auth'); /* menampilkan data setorsimpanan pada datatable javascript */
    Route::get('/transaksi/setorsimpananshownasabah', [SetorsimpananController::class, 'shownasabah'])->name('simpanan01.transaksi.setorsimpanan_shownasabah')->middleware('auth'); /* menampilkan data nasabah pada datatable javascript */
    Route::post('/transaksi/setorsimpanankirimsyarat', [SetorsimpananController::class, 'kirimsyarat'])->name('simpanan01.transaksi.setorsimpanan_kirimsyarat')->middleware('auth'); /* kirim syarat */
    Route::post('/transaksi/setorsimpanancariid', [SetorsimpananController::class, 'cariid'])->name('simpanan01.transaksi.setorsimpanan_cariid')->middleware('auth'); /* cari data nasabah */
    Route::post('/transaksi/setorsimpanancarinomorbukti', [SetorsimpananController::class, 'carinomorbukti'])->name('simpanan01.transaksi.setorsimpanan_carinomorbukti')->middleware('auth'); /* cari data nasabah di jurnalsimpanan*/
    Route::post('/transaksi/setorsimpanannomorbukti', [SetorsimpananController::class, 'nomorbukti'])->name('simpanan01.transaksi.setorsimpanan_nomorbukti')->middleware('auth'); /* buat nomorbukti */
    Route::post('/transaksi/setorsimpanannomorposting', [SetorsimpananController::class, 'nomorposting'])->name('simpanan01.transaksi.setorsimpanan_nomorposting')->middleware('auth'); /* buat nomorposting */
    Route::get('/transaksi/setorsimpananlistnasabah', [SetorsimpananController::class, 'listnasabah'])->name('simpanan01.transaksi.setorsimpanan_listnasabah')->middleware('auth'); /* menampilkan list nasabah */
    Route::post('/transaksi/setorsimpanancreate', [SetorsimpananController::class, 'create'])->name('simpanan01.transaksi.setorsimpanan_create')->middleware('auth'); /* menambah data setorsimpanan */
    Route::get('/transaksi/setorsimpananedit/{id}', [SetorsimpananController::class, 'edit'])->name('simpanan01.transaksi.setorsimpanan_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::post('/transaksi/setorsimpananupdate', [SetorsimpananController::class, 'update'])->name('simpanan01.transaksi.setorsimpanan_update')->middleware('auth'); /* update data setorsimpanan */
    Route::post('/transaksi/setorsimpananposting', [SetorsimpananController::class, 'posting'])->name('simpanan01.transaksi.setorsimpanan_posting')->middleware('auth'); /* posting data setorsimpanan */
    Route::get('/transaksi/setorsimpanandestroy/{id}', [SetorsimpananController::class, 'destroy'])->name('simpanan01.transaksi.setorsimpanan_destroy')->middleware('auth'); /* hapus data setorsimpanan */
    Route::get('/transaksi/setorsimpananprintdetail/{id}', [SetorsimpananController::class, 'printdetail'])->name('simpanan01.transaksi.setorsimpanan_printdetail')->middleware('auth'); /* print detail */
  
    /* transaksi - tariksimpanan */
    Route::get('/transaksi/tariksimpanan', [TariksimpananController::class, 'index'])->name('simpanan01.transaksi.tariksimpanan.index')->middleware('auth'); /* halaman tariksimpanan */
    Route::get('/transaksi/tariksimpananshow', [TariksimpananController::class, 'show'])->name('simpanan01.transaksi.tariksimpanan_show')->middleware('auth'); /* menampilkan data tariksimpanan pada datatable javascript */
    Route::get('/transaksi/tariksimpananshownasabah', [TariksimpananController::class, 'shownasabah'])->name('simpanan01.transaksi.tariksimpanan_shownasabah')->middleware('auth'); /* menampilkan data nasabah pada datatable javascript */
    Route::post('/transaksi/tariksimpanankirimsyarat', [TariksimpananController::class, 'kirimsyarat'])->name('simpanan01.transaksi.tariksimpanan_kirimsyarat')->middleware('auth'); /* kirim syarat */
    Route::post('/transaksi/tariksimpanancariid', [TariksimpananController::class, 'cariid'])->name('simpanan01.transaksi.tariksimpanan_cariid')->middleware('auth'); /* cari data nasabah */
    Route::post('/transaksi/tariksimpanancarinomorbukti', [TariksimpananController::class, 'carinomorbukti'])->name('simpanan01.transaksi.tariksimpanan_carinomorbukti')->middleware('auth'); /* cari data nasabah di jurnalsimpanan */
    Route::post('/transaksi/tariksimpanannomorbukti', [TariksimpananController::class, 'nomorbukti'])->name('simpanan01.transaksi.tariksimpanan_nomorbukti')->middleware('auth'); /* buat nomorbukti */
    Route::post('/transaksi/tariksimpanannomorposting', [TariksimpananController::class, 'nomorposting'])->name('simpanan01.transaksi.tariksimpanan_nomorposting')->middleware('auth'); /* buat nomorposting */
    Route::get('/transaksi/tariksimpananlistnasabah', [TariksimpananController::class, 'listnasabah'])->name('simpanan01.transaksi.tariksimpanan_listnasabah')->middleware('auth'); /* menampilkan list nasabah */
    Route::post('/transaksi/tariksimpanancreate', [TariksimpananController::class, 'create'])->name('simpanan01.transaksi.tariksimpanan_create')->middleware('auth'); /* menambah data tariksimpanan */
    Route::get('/transaksi/tariksimpananedit/{id}', [TariksimpananController::class, 'edit'])->name('simpanan01.transaksi.tariksimpanan_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::post('/transaksi/tariksimpananupdate', [TariksimpananController::class, 'update'])->name('simpanan01.transaksi.tariksimpanan_update')->middleware('auth'); /* update data tariksimpanan */
    Route::post('/transaksi/tariksimpananposting', [TariksimpananController::class, 'posting'])->name('simpanan01.transaksi.tariksimpanan_posting')->middleware('auth'); /* posting data tariksimpanan */
    Route::get('/transaksi/tariksimpanandestroy/{id}', [TariksimpananController::class, 'destroy'])->name('simpanan01.transaksi.tariksimpanan_destroy')->middleware('auth'); /* hapus data tariksimpanan */
    Route::get('/transaksi/tariksimpananprintdetail/{id}', [TariksimpananController::class, 'printdetail'])->name('simpanan01.transaksi.tariksimpanan_printdetail')->middleware('auth'); /* print detail */
  
    /* transaksi - transfersimpanan */
    Route::get('/transaksi/transfersimpanan', [TransfersimpananController::class, 'index'])->name('simpanan01.transaksi.transfersimpanan.index')->middleware('auth'); /* halaman transfersimpanan */
    Route::get('/transaksi/transfersimpananshow', [TransfersimpananController::class, 'show'])->name('simpanan01.transaksi.transfersimpanan_show')->middleware('auth'); /* menampilkan data transfersimpanan pada datatable javascript */
    Route::get('/transaksi/transfersimpananshownasabah', [TransfersimpananController::class, 'shownasabah'])->name('simpanan01.transaksi.transfersimpanan_shownasabah')->middleware('auth'); /* menampilkan data nasabah pada datatable javascript */
    Route::post('/transaksi/transfersimpanankirimsyarat', [TransfersimpananController::class, 'kirimsyarat'])->name('simpanan01.transaksi.transfersimpanan_kirimsyarat')->middleware('auth'); /* kirim syarat */
    Route::post('/transaksi/transfersimpanancariid', [TransfersimpananController::class, 'cariid'])->name('simpanan01.transaksi.transfersimpanan_cariid')->middleware('auth'); /* cari data nasabah */
    Route::post('/transaksi/transfersimpanancarinomorbukti', [TransfersimpananController::class, 'carinomorbukti'])->name('simpanan01.transaksi.transfersimpanan_carinomorbukti')->middleware('auth'); /* cari data nasabah di jurnalsimpanan */
    Route::post('/transaksi/transfersimpanannomorbukti', [TransfersimpananController::class, 'nomorbukti'])->name('simpanan01.transaksi.transfersimpanan_nomorbukti')->middleware('auth'); /* buat nomorbukti */
    Route::post('/transaksi/transfersimpanannomorposting', [TransfersimpananController::class, 'nomorposting'])->name('simpanan01.transaksi.transfersimpanan_nomorposting')->middleware('auth'); /* buat nomorposting */
    Route::get('/transaksi/transfersimpananlistnasabah', [TransfersimpananController::class, 'listnasabah'])->name('simpanan01.transaksi.transfersimpanan_listnasabah')->middleware('auth'); /* menampilkan list nasabah */
    Route::post('/transaksi/transfersimpanancreate', [TransfersimpananController::class, 'create'])->name('simpanan01.transaksi.transfersimpanan_create')->middleware('auth'); /* menambah data transfersimpanan */
    Route::get('/transaksi/transfersimpananedit/{id}', [TransfersimpananController::class, 'edit'])->name('simpanan01.transaksi.transfersimpanan_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::post('/transaksi/transfersimpananupdate', [TransfersimpananController::class, 'update'])->name('simpanan01.transaksi.transfersimpanan_update')->middleware('auth'); /* update data transfersimpanan */
    Route::post('/transaksi/transfersimpananposting', [TransfersimpananController::class, 'posting'])->name('simpanan01.transaksi.transfersimpanan_posting')->middleware('auth'); /* posting data transfersimpanan */
    Route::get('/transaksi/transfersimpanandestroy/{id}', [TransfersimpananController::class, 'destroy'])->name('simpanan01.transaksi.transfersimpanan_destroy')->middleware('auth'); /* hapus data transfersimpanan */
    Route::get('/transaksi/transfersimpananprintdetail/{id}', [TransfersimpananController::class, 'printdetail'])->name('simpanan01.transaksi.transfersimpanan_printdetail')->middleware('auth'); /* print detail */
  
    /* laporan - rekeningkoran */
    Route::get('/laporan/rekeningkoran', [RekeningkoranController::class, 'index'])->name('simpanan01.laporan.rekeningkoran.index')->middleware('auth'); /* halaman rekeningkoran */
    Route::get('/laporan/rekeningkoranshow', [RekeningkoranController::class, 'show'])->name('simpanan01.laporan.rekeningkoran_show')->middleware('auth'); /* menampilkan data rekeningkoran pada datatable javascript */
    Route::get('/laporan/rekeningkoranshownasabah', [RekeningkoranController::class, 'shownasabah'])->name('simpanan01.laporan.rekeningkoran_shownasabah')->middleware('auth'); /* menampilkan data nasabah pada datatable javascript */
    Route::post('/laporan/rekeningkorankirimsyarat', [RekeningkoranController::class, 'kirimsyarat'])->name('simpanan01.laporan.rekeningkoran_kirimsyarat')->middleware('auth'); /* kirim syarat */
    Route::post('/laporan/rekeningkorancariid', [RekeningkoranController::class, 'cariid'])->name('simpanan01.laporan.rekeningkoran_cariid')->middleware('auth'); /* cari data nasabah */
    Route::post('/laporan/rekeningkorannomorbukti', [RekeningkoranController::class, 'nomorbukti'])->name('simpanan01.laporan.rekeningkoran_nomorbukti')->middleware('auth'); /* buat nomorbukti */
    Route::post('/laporan/rekeningkorannomorposting', [RekeningkoranController::class, 'nomorposting'])->name('simpanan01.laporan.rekeningkoran_nomorposting')->middleware('auth'); /* buat nomorposting */
    Route::get('/laporan/rekeningkoranlistnasabah', [RekeningkoranController::class, 'listnasabah'])->name('simpanan01.laporan.rekeningkoran_listnasabah')->middleware('auth'); /* menampilkan list nasabah */
    Route::post('/laporan/rekeningkorancreate', [RekeningkoranController::class, 'create'])->name('simpanan01.laporan.rekeningkoran_create')->middleware('auth'); /* menambah data rekeningkoran */
    Route::get('/laporan/rekeningkoranedit/{id}', [RekeningkoranController::class, 'edit'])->name('simpanan01.laporan.rekeningkoran_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::post('/laporan/rekeningkoranupdate', [RekeningkoranController::class, 'update'])->name('simpanan01.laporan.rekeningkoran_update')->middleware('auth'); /* update data rekeningkoran */
    Route::post('/laporan/rekeningkoranposting', [RekeningkoranController::class, 'posting'])->name('simpanan01.laporan.rekeningkoran_posting')->middleware('auth'); /* posting data rekeningkoran */
    Route::get('/laporan/rekeningkorandestroy/{id}', [RekeningkoranController::class, 'destroy'])->name('simpanan01.laporan.rekeningkoran_destroy')->middleware('auth'); /* hapus data rekeningkoran */
    Route::get('/laporan/rekeningkoranprintdetail', [RekeningkoranController::class, 'printdetail'])->name('simpanan01.laporan.rekeningkoran_printdetail')->middleware('auth'); /* print detail */

    /* laporan - rekapsimpanan */
    Route::get('/laporan/rekapsimpanan', [RekapsimpananController::class, 'index'])->name('simpanan01.laporan.rekapsimpanan.index')->middleware('auth'); /* halaman rekapsimpanan */
    Route::get('/laporan/rekapsimpananshow', [RekapsimpananController::class, 'show'])->name('simpanan01.laporan.rekapsimpanan_show')->middleware('auth'); /* menampilkan data rekapsimpanan pada datatable javascript */
    Route::post('/laporan/rekapsimpanankirimsyarat', [RekapsimpananController::class, 'kirimsyarat'])->name('simpanan01.laporan.rekapsimpanan_kirimsyarat')->middleware('auth'); /* kirim syarat */
    Route::get('/laporan/rekapsimpananlistanggota', [RekapsimpananController::class, 'listanggota'])->name('simpanan01.laporan.rekapsimpanan_listanggota')->middleware('auth'); /* menampilkan list anggota */
    Route::post('/laporan/rekapsimpanancreate', [RekapsimpananController::class, 'create'])->name('simpanan01.laporan.rekapsimpanan_create')->middleware('auth'); /* menambah data rekapsimpanan */
    Route::get('/laporan/rekapsimpananedit/{id}', [RekapsimpananController::class, 'edit'])->name('simpanan01.laporan.rekapsimpanan_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::post('/laporan/rekapsimpananupdate', [RekapsimpananController::class, 'update'])->name('simpanan01.laporan.rekapsimpanan_update')->middleware('auth'); /* update data rekapsimpanan */
    Route::get('/laporan/rekapsimpanandestroy/{id}', [RekapsimpananController::class, 'destroy'])->name('simpanan01.laporan.rekapsimpanan_destroy')->middleware('auth'); /* hapus data rekapsimpanan */
    Route::get('/laporan/rekapsimpananprintkwitansi', [RekapsimpananController::class, 'printkwitansi'])->name('simpanan01.laporan.rekapsimpanan_printkwitansi')->middleware('auth'); /* printkwitansi */
    
   /* laporan - detailsimpanan */
    Route::get('/laporan/detailsimpanan', [DetailsimpananController::class, 'index'])->name('simpanan01.laporan.detailsimpanan.index')->middleware('auth'); /* halaman detailsimpanan */
    Route::get('/laporan/detailsimpananshow', [DetailsimpananController::class, 'show'])->name('simpanan01.laporan.detailsimpanan_show')->middleware('auth'); /* menampilkan data detailsimpanan pada datatable javascript */
    Route::get('/laporan/detailsimpananshownasabah', [DetailsimpananController::class, 'shownasabah'])->name('simpanan01.laporan.detailsimpanan_shownasabah')->middleware('auth'); /* menampilkan data nasabah pada datatable javascript */
    Route::post('/laporan/detailsimpanankirimsyarat', [DetailsimpananController::class, 'kirimsyarat'])->name('simpanan01.laporan.detailsimpanan_kirimsyarat')->middleware('auth'); /* kirim syarat */
    Route::post('/laporan/detailsimpanancariid', [DetailsimpananController::class, 'cariid'])->name('simpanan01.laporan.detailsimpanan_cariid')->middleware('auth'); /* cari data nasabah */
    Route::post('/laporan/detailsimpanancarinomorbukti', [DetailsimpananController::class, 'carinomorbukti'])->name('simpanan01.laporan.detailsimpanan_carinomorbukti')->middleware('auth'); /* cari data nasabah di jurnalsimpanan */
    Route::post('/laporan/detailsimpanannomorbukti', [DetailsimpananController::class, 'nomorbukti'])->name('simpanan01.laporan.detailsimpanan_nomorbukti')->middleware('auth'); /* buat nomorbukti */
    Route::post('/laporan/detailsimpanannomorposting', [DetailsimpananController::class, 'nomorposting'])->name('simpanan01.laporan.detailsimpanan_nomorposting')->middleware('auth'); /* buat nomorposting */
    Route::get('/laporan/detailsimpananlistnasabah', [DetailsimpananController::class, 'listnasabah'])->name('simpanan01.laporan.detailsimpanan_listnasabah')->middleware('auth'); /* menampilkan list nasabah */
    Route::post('/laporan/detailsimpanancreate', [DetailsimpananController::class, 'create'])->name('simpanan01.laporan.detailsimpanan_create')->middleware('auth'); /* menambah data detailsimpanan */
    Route::get('/laporan/detailsimpananedit/{id}', [DetailsimpananController::class, 'edit'])->name('simpanan01.laporan.detailsimpanan_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
    Route::post('/laporan/detailsimpananupdate', [DetailsimpananController::class, 'update'])->name('simpanan01.laporan.detailsimpanan_update')->middleware('auth'); /* update data detailsimpanan */
    Route::post('/laporan/detailsimpananposting', [DetailsimpananController::class, 'posting'])->name('simpanan01.laporan.detailsimpanan_posting')->middleware('auth'); /* posting data detailsimpanan */
    Route::get('/laporan/detailsimpanandestroy/{id}', [DetailsimpananController::class, 'destroy'])->name('simpanan01.laporan.detailsimpanan_destroy')->middleware('auth'); /* hapus data detailsimpanan */
    Route::get('/laporan/detailsimpananprintdetail/{id}', [DetailsimpananController::class, 'printdetail'])->name('simpanan01.laporan.detailsimpanan_printdetail')->middleware('auth'); /* print detail */
  

});

// Route::middleware(['auth', 'verified'])->group(function () {
//     // Route::resource('simpanan01s', Simpanan01Controller::class)->names('simpanan01');

// });
