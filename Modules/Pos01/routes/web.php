<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Modules\Pos01\Http\Controllers\laporan\Biayabiaya2Controller;
use Modules\Pos01\Http\Controllers\laporan\HutangpiutangController;
use Modules\Pos01\Http\Controllers\laporan\KartuhutangController;
use Modules\Pos01\Http\Controllers\laporan\KartupiutangController;
use Modules\Pos01\Http\Controllers\laporan\KartustokController;
use Modules\Pos01\Http\Controllers\laporan\LabarugiController;
use Modules\Pos01\Http\Controllers\laporan\PembelianController;
use Modules\Pos01\Http\Controllers\laporan\Pendapatanlain2Controller;
use Modules\Pos01\Http\Controllers\laporan\PenjualanController;
use Modules\Pos01\Http\Controllers\laporan\StokkeluarmasukController;
use Modules\Pos01\Http\Controllers\laporan\StokbarangController;
use Modules\Pos01\Http\Controllers\master\MaminController;
use Modules\Pos01\Http\Controllers\pengaturan\ParameterController;
use Modules\Pos01\Http\Controllers\transaksi\BayarhutangcustomerController;
use Modules\Pos01\Http\Controllers\transaksi\BmasukController;
use Modules\Pos01\Http\Controllers\master\BarangController;
use Modules\Pos01\Http\Controllers\master\BarangruangController;
use Modules\Pos01\Http\Controllers\master\BarcodeController;
use Modules\Pos01\Http\Controllers\master\KategoriController;
use Modules\Pos01\Http\Controllers\master\RuangController;
use Modules\Pos01\Http\Controllers\master\SeksiController;
use Modules\Pos01\Http\Controllers\master\AnggotaController;
use Modules\Pos01\Http\Controllers\master\SatuanController;
use Modules\Pos01\Http\Controllers\master\SupplierController;
use Modules\Pos01\Http\Controllers\master\LembagaController;
use Modules\Pos01\Http\Controllers\Pos01Controller;
use Modules\Pos01\Http\Controllers\transaksi\BayarhutangSupplierController;
use Modules\Pos01\Http\Controllers\transaksi\BiayabiayaController;
use Modules\Pos01\Http\Controllers\transaksi\BkeluarController;
use Modules\Pos01\Http\Controllers\transaksi\MkeluarController;
use Modules\Pos01\Http\Controllers\transaksi\PendapatanlainController;

// use Modules\Pos01\Http\Controllers\master\AnggotaController;
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
//     Route::resource('pos01', Pos01Controller::class)->names('pos01');
// });

Route::prefix('pos01')->group(function() {
    // Route::get('/', 'Pos01Controller@index');

/* master - anggota */
Route::get('/master/anggota', [AnggotaController::class, 'index'])->name('pos01.master.anggota.index')->middleware('auth'); /* halaman anggota */
Route::get('/master/anggotashow', [AnggotaController::class, 'show'])->name('pos01.master.anggota_show')->middleware('auth'); /* menampilkan data anggota pada datatable javascript */
Route::get('/master/anggotalistlembaga', [AnggotaController::class, 'listlembaga'])->name('pos01.master.anggota_listlembaga')->middleware('auth'); /* menampilkan list lembaga */
Route::post('/master/anggotacreate', [AnggotaController::class, 'create'])->name('pos01.master.anggota_create')->middleware('auth'); /* menambah anggota */
Route::get('/master/anggotaedit/{id}', [AnggotaController::class, 'edit'])->name('pos01.master.anggota_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::get('/master/anggotadestroy/{id}', [AnggotaController::class, 'destroy'])->name('pos01.master.anggota_destroy')->middleware('auth'); /* hapus data anggota */

/* master - satuan */
Route::get('/master/satuan', [SatuanController::class, 'index'])->name('pos01.master.satuan.index')->middleware('auth'); /* halaman satuan */
Route::get('/master/satuanshow', [SatuanController::class, 'show'])->name('pos01.master.satuan_show')->middleware('auth'); /* menampilkan data satuan pada datatable javascript */
Route::post('/master/satuancreate', [SatuanController::class, 'create'])->name('pos01.master.satuan_create')->middleware('auth'); /* menambah satuan */
Route::get('/master/satuanedit/{id}', [SatuanController::class, 'edit'])->name('pos01.master.satuan_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::get('/master/satuandestroy/{id}', [SatuanController::class, 'destroy'])->name('pos01.master.satuan_destroy')->middleware('auth'); /* hapus data satuan */

/* master - kategori */
Route::get('/master/kategori', [KategoriController::class, 'index'])->name('pos01.master.kategori.index')->middleware('auth'); /* halaman kategori */
Route::get('/master/kategorishow', [KategoriController::class, 'show'])->name('pos01.master.kategori_show')->middleware('auth'); /* menampilkan data kategori pada datatable javascript */
Route::post('/master/kategoricreate', [KategoriController::class, 'create'])->name('pos01.master.kategori_create')->middleware('auth'); /* menambah kategori */
Route::get('/master/kategoriedit/{id}', [KategoriController::class, 'edit'])->name('pos01.master.kategori_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::get('/master/kategoridestroy/{id}', [KategoriController::class, 'destroy'])->name('pos01.master.kategori_destroy')->middleware('auth'); /* hapus data kategori */

/* master - barang */
Route::get('/master/barang', [BarangController::class, 'index'])->name('pos01.master.barang.index')->middleware('auth'); /* halaman barang */
Route::get('/master/barangshow', [BarangController::class, 'show'])->name('pos01.master.barang_show')->middleware('auth'); /* menampilkan data barang pada datatable javascript */
Route::get('/master/barangshowbarang', [BarangController::class, 'showbarang'])->name('pos01.master.barang_showbarang')->middleware('auth'); /* menampilkan data barang untuk pilihan pada datatable javascript */
Route::get('/master/baranglistkategori', [BarangController::class, 'listkategori'])->name('pos01.master.barang_listkategori')->middleware('auth'); /* menampilkan list kategori */
Route::get('/master/baranglistsatuan', [BarangController::class, 'listsatuan'])->name('pos01.master.barang_listsatuan')->middleware('auth'); /* menampilkan satuan */
Route::post('/master/barangcreate', [BarangController::class, 'create'])->name('pos01.master.barang_create')->middleware('auth'); /* menambah barang */
Route::get('/master/barangedit/{id}', [BarangController::class, 'edit'])->name('pos01.master.barang_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::get('/master/barangdestroy/{id}', [BarangController::class, 'destroy'])->name('pos01.master.barang_destroy')->middleware('auth'); /* hapus data barang */

/* master - barangruang */
Route::get('/master/barangruang', [BarangruangController::class, 'index'])->name('pos01.master.barangruang.index')->middleware('auth'); /* halaman barangruang */
Route::get('/master/barangruangshow', [BarangruangController::class, 'show'])->name('pos01.master.barangruang_show')->middleware('auth'); /* menampilkan data barangruang pada datatable javascript */
Route::get('/master/barangruangshowbarang', [BarangruangController::class, 'showbarang'])->name('pos01.master.barangruang_showbarang')->middleware('auth'); /* menampilkan data barang yg gk ada di barangruang pada datatable javascript */
Route::get('/master/barangruanglistbarang', [BarangruangController::class, 'listbarang'])->name('pos01.master.barangruang_listbarang')->middleware('auth'); /* menampilkan list barang */
Route::get('/master/barangruanglistruang', [BarangruangController::class, 'listruang'])->name('pos01.master.barangruang_listruang')->middleware('auth'); /* menampilkan list ruang */
Route::post('/master/barangruangcreate', [BarangruangController::class, 'create'])->name('pos01.master.barangruang_create')->middleware('auth'); /* menambah barangruang */
Route::post('/master/barangruangkirimsyarat', [BarangruangController::class, 'kirimsyarat'])->name('pos01.master.barangruang_kirimsyarat')->middleware('auth'); /* kirimsyarat */
Route::get('/master/barangruangedit/{id}', [BarangruangController::class, 'edit'])->name('pos01.master.barangruang_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::get('/master/barangruangdestroy/{id}', [BarangruangController::class, 'destroy'])->name('pos01.master.barangruang_destroy')->middleware('auth'); /* hapus data barangruang */

/* master - mamin */
Route::get('/master/mamin', [MaminController  ::class, 'index'])->name('pos01.master.mamin.index')->middleware('auth'); /* halaman mamin */
Route::get('/master/maminshow', [MaminController::class, 'show'])->name('pos01.master.mamin_show')->middleware('auth'); /* menampilkan data mamin pada datatable javascript */
Route::get('/master/maminshowmamin', [MaminController::class, 'showmamin'])->name('pos01.master.mamin_showmamin')->middleware('auth'); /* menampilkan data mamin untuk pilihan pada datatable javascript */
Route::get('/master/maminlistkategori', [MaminController::class, 'listkategori'])->name('pos01.master.mamin_listkategori')->middleware('auth'); /* menampilkan list kategori */
Route::get('/master/maminlistsatuan', [MaminController::class, 'listsatuan'])->name('pos01.master.mamin_listsatuan')->middleware('auth'); /* menampilkan satuan */
Route::post('/master/mamincreate', [MaminController::class, 'create'])->name('pos01.master.mamin_create')->middleware('auth'); /* menambah mamin */
Route::get('/master/maminedit/{id}', [MaminController::class, 'edit'])->name('pos01.master.mamin_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::get('/master/mamindestroy/{id}', [MaminController::class, 'destroy'])->name('pos01.master.mamin_destroy')->middleware('auth'); /* hapus data mamin */

/* master - barcode */
Route::get('/master/barcode', [BarcodeController::class, 'index'])->name('pos01.master.barcode.index')->middleware('auth'); /* halaman barcode */
Route::get('/master/barcodeshow', [BarcodeController::class, 'show'])->name('pos01.master.barcode_show')->middleware('auth'); /* menampilkan data barcode pada datatable javascript */
Route::get('/master/barcodelistbarang', [BarcodeController::class, 'listbarang'])->name('pos01.master.barcode_listbarang')->middleware('auth'); /* menampilkan list barang */
Route::post('/master/barcodecreate', [BarcodeController::class, 'create'])->name('pos01.master.barcode_create')->middleware('auth'); /* menambah barcode */
Route::get('/master/barcodeedit/{id}', [BarcodeController::class, 'edit'])->name('pos01.master.barcode_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::get('/master/barcodedestroy/{id}', [BarcodeController::class, 'destroy'])->name('pos01.master.barcode_destroy')->middleware('auth'); /* hapus data barcode */

/* master - supplier */
Route::get('/master/supplier', [SupplierController::class, 'index'])->name('pos01.master.supplier.index')->middleware('auth'); /* halaman supplier */
Route::get('/master/suppliershow', [SupplierController::class, 'show'])->name('pos01.master.supplier_show')->middleware('auth'); /* menampilkan data supplier pada datatable javascript */
Route::post('/master/suppliercreate', [SupplierController::class, 'create'])->name('pos01.master.supplier_create')->middleware('auth'); /* menambah supplier */
Route::get('/master/supplieredit/{id}', [SupplierController::class, 'edit'])->name('pos01.master.supplier_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::get('/master/supplierdestroy/{id}', [SupplierController::class, 'destroy'])->name('pos01.master.supplier_destroy')->middleware('auth'); /* hapus data supplier */

/* master - lembaga */
Route::get('/master/lembaga', [LembagaController::class, 'index'])->name('pos01.master.lembaga.index')->middleware('auth'); /* halaman lembaga */
Route::get('/master/lembagashow', [LembagaController::class, 'show'])->name('pos01.master.lembaga_show')->middleware('auth'); /* menampilkan data lembaga pada datatable javascript */
Route::post('/master/lembagacreate', [LembagaController::class, 'create'])->name('pos01.master.lembaga_create')->middleware('auth'); /* menambah lembaga */
Route::get('/master/lembagaedit/{id}', [LembagaController::class, 'edit'])->name('pos01.master.lembaga_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::get('/master/lembagaedit/{id}', [LembagaController::class, 'edit'])->name('pos01.master.lembaga_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::get('/master/lembagadestroy/{id}', [LembagaController::class, 'destroy'])->name('pos01.master.lembaga_destroy')->middleware('auth'); /* hapus data lembaga */

/* master - seksi */
Route::get('/master/seksi', [SeksiController::class, 'index'])->name('pos01.master.seksi.index')->middleware('auth'); /* halaman seksi */
Route::get('/master/seksishow', [SeksiController::class, 'show'])->name('pos01.master.seksi_show')->middleware('auth'); /* menampilkan data seksi pada datatable javascript */
Route::post('/master/seksicreate', [SeksiController::class, 'create'])->name('pos01.master.seksi_create')->middleware('auth'); /* menambah data seksi */
Route::get('/master/seksiedit/{id}', [SeksiController::class, 'edit'])->name('pos01.master.seksi_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::post('/master/seksiupdate', [SeksiController::class, 'update'])->name('pos01.master.seksi_update')->middleware('auth'); /* update data seksi */
Route::get('/master/seksidestroy/{id}', [SeksiController::class, 'destroy'])->name('pos01.master.seksi_destroy')->middleware('auth'); /* hapus data seksi */

/* master - ruang */
Route::get('/master/ruang', [RuangController::class, 'index'])->name('pos01.master.ruang.index')->middleware('auth'); /* halaman ruang */
Route::get('/master/ruangshow', [RuangController::class, 'show'])->name('pos01.master.ruang_show')->middleware('auth'); /* menampilkan data ruang pada datatable javascript */
Route::get('/master/ruanglistseksi', [RuangController::class, 'listseksi'])->name('pos01.master.ruang_listseksi')->middleware('auth'); /* menampilkan list jurusan */
Route::post('/master/ruangcreate', [RuangController::class, 'create'])->name('pos01.master.ruang_create')->middleware('auth'); /* menambah data ruang */
Route::get('/master/ruangedit/{id}', [RuangController::class, 'edit'])->name('pos01.master.ruang_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::post('/master/ruangupdate', [RuangController::class, 'update'])->name('pos01.master.ruang_update')->middleware('auth'); /* update data ruang */
Route::post('/master/ruangkirimsyarat', [RuangController::class, 'kirimsyarat'])->name('pos01.master.ruang_kirimsyarat')->middleware('auth'); /* kirimsyarat */
Route::get('/master/ruangdelete/{id}', [RuangController::class, 'destroy'])->name('pos01.master.ruang_destroy')->middleware('auth'); /* hapus data ruang */

/* transaksi - bmasuk */
Route::get('/transaksi/bmasuk', [BmasukController::class, 'index'])->name('pos01.transaksi.bmasuk.index')->middleware('auth'); /* halaman bmasuk */
Route::get('/transaksi/bmasukshow', [BmasukController::class, 'show'])->name('pos01.transaksi.bmasuk_show')->middleware('auth'); /* menampilkan data bmasuk pada datatable javascript */
Route::post('/transaksi/bmasukkirimsyarat', [BmasukController::class, 'kirimsyarat'])->name('pos01.transaksi.bmasuk_kirimsyarat')->middleware('auth'); /* kirim syarat */
Route::get('/transaksi/bmasukshowbarang', [BmasukController::class, 'showbarang'])->name('pos01.transaksi.bmasuk_showbarang')->middleware('auth'); /* menampilkan barang pada datatable javascript */
Route::post('/transaksi/bmasuknomorbukti', [BmasukController::class, 'nomorbukti'])->name('pos01.transaksi.bmasuk_nomorbukti')->middleware('auth'); /* buat nomorbukti */
Route::post('/transaksi/bmasuknomorposting', [BmasukController::class, 'nomorposting'])->name('pos01.transaksi.bmasuk_nomorposting')->middleware('auth'); /* buat nomorposting */
Route::get('/transaksi/bmasuklistbarang', [BmasukController::class, 'listbarang'])->name('pos01.transaksi.bmasuk_listbarang')->middleware('auth'); /* menampilkan list barang */
Route::get('/transaksi/bmasuklistruang', [BmasukController::class, 'listruang'])->name('pos01.transaksi.bmasuk_listruang')->middleware('auth'); /* menampilkan list ruang */
Route::get('/transaksi/bmasuklistjenispembayaran', [BmasukController::class, 'listjenispembayaran'])->name('pos01.transaksi.bmasuk_listjenispembayaran')->middleware('auth'); /* menampilkan list jenispembayaran */
Route::get('/transaksi/bmasuklistsupplier', [BmasukController::class, 'listsupplier'])->name('pos01.transaksi.bmasuk_listsupplier')->middleware('auth'); /* menampilkan list supplier */
Route::post('/transaksi/bmasukcreate', [BmasukController::class, 'create'])->name('pos01.transaksi.bmasuk_create')->middleware('auth'); /* menambah data bmasuk */
Route::post('/transaksi/bmasukproses', [BmasukController::class, 'proses'])->name('pos01.transaksi.bmasuk_proses')->middleware('auth'); /* menambah proses bmasuk */
Route::get('/transaksi/bmasukedit/{id}', [BmasukController::class, 'edit'])->name('pos01.transaksi.bmasuk_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::get('/transaksi/bmasukdisplaypembayaran/{id}', [BmasukController::class, 'displaypembayaran'])->name('pos01.transaksi.bmasuk_displaypembayaran')->middleware('auth'); /* menampilkan pembayaran */
Route::post('/transaksi/bmasukupdate', [BmasukController::class, 'update'])->name('pos01.transaksi.bmasuk_update')->middleware('auth'); /* update data bmasuk */
Route::post('/transaksi/bmasukposting', [BmasukController::class, 'posting'])->name('pos01.transaksi.bmasuk_posting')->middleware('auth'); /* posting data bmasuk */
Route::get('/transaksi/bmasukdestroy/{id}', [BmasukController::class, 'destroy'])->name('pos01.transaksi.bmasuk_destroy')->middleware('auth'); /* hapus data bmasuk */

/* transaksi - bkeluar */
Route::get('/transaksi/bkeluar', [BkeluarController::class, 'index'])->name('pos01.transaksi.bkeluar.index')->middleware('auth'); /* halaman bkeluar */
Route::get('/transaksi/bkeluarshow', [BkeluarController::class, 'show'])->name('pos01.transaksi.bkeluar_show')->middleware('auth'); /* menampilkan data bkeluar pada datatable javascript */
Route::post('/transaksi/bkeluarkirimsyarat', [BkeluarController::class, 'kirimsyarat'])->name('pos01.transaksi.bkeluar_kirimsyarat')->middleware('auth'); /* kirim syarat */
Route::post('/transaksi/bkeluarcariid', [BkeluarController::class, 'cariid'])->name('pos01.transaksi.bkeluar_cariid')->middleware('auth'); /* cari data anggota */
Route::get('/transaksi/bkeluarshowbarang', [BkeluarController::class, 'showbarang'])->name('pos01.transaksi.bkeluar_showbarang')->middleware('auth'); /* menampilkan barang pada datatable javascript */
Route::get('/transaksi/bkeluarshowanggota', [BkeluarController::class, 'showanggota'])->name('pos01.transaksi.bkeluar_showanggota')->middleware('auth'); /* menampilkan anggota pada datatable javascript */
Route::post('/transaksi/bkeluarnomorbukti', [BkeluarController::class, 'nomorbukti'])->name('pos01.transaksi.bkeluar_nomorbukti')->middleware('auth'); /* buat nomorbukti */
Route::post('/transaksi/bkeluarnomorposting', [BkeluarController::class, 'nomorposting'])->name('pos01.transaksi.bkeluar_nomorposting')->middleware('auth'); /* buat nomorposting */
Route::get('/transaksi/bkeluarlistbarang', [BkeluarController::class, 'listbarang'])->name('pos01.transaksi.bkeluar_listbarang')->middleware('auth'); /* menampilkan list barang */
Route::get('/transaksi/bkeluarlistruang', [BkeluarController::class, 'listruang'])->name('pos01.transaksi.bkeluar_listruang')->middleware('auth'); /* menampilkan list ruang */
Route::get('/transaksi/bkeluarlistanggota', [BkeluarController::class, 'listanggota'])->name('pos01.transaksi.bkeluar_listanggota')->middleware('auth'); /* menampilkan list anggota */
Route::get('/transaksi/bkeluarlistjenispembayaran', [BkeluarController::class, 'listjenispembayaran'])->name('pos01.transaksi.bkeluar_listjenispembayaran')->middleware('auth'); /* menampilkan list jenispembayaran */
Route::post('/transaksi/bkeluarcreate', [BkeluarController::class, 'create'])->name('pos01.transaksi.bkeluar_create')->middleware('auth'); /* menambah data bkeluar */
Route::post('/transaksi/bkeluarproses', [BkeluarController::class, 'proses'])->name('pos01.transaksi.bkeluar_proses')->middleware('auth'); /* menambah proses */
Route::get('/transaksi/bkeluaredit/{id}', [BkeluarController::class, 'edit'])->name('pos01.transaksi.bkeluar_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::get('/transaksi/bkeluardisplaypembayaran/{id}', [BkeluarController::class, 'displaypembayaran'])->name('pos01.transaksi.bkeluar_displaypembayaran')->middleware('auth'); /* menampilkan pembayaran */
Route::get('/transaksi/bkeluarlihatpersen/{id}', [BkeluarController::class, 'lihatpersen'])->name('pos01.transaksi.bkeluar_lihatpersen')->middleware('auth'); /* menampilkan lihatpersen jasa */
Route::post('/transaksi/bkeluarupdate', [BkeluarController::class, 'update'])->name('pos01.transaksi.bkeluar_update')->middleware('auth'); /* update data bkeluar */
Route::post('/transaksi/bkeluarposting', [BkeluarController::class, 'posting'])->name('pos01.transaksi.bkeluar_posting')->middleware('auth'); /* posting data bkeluar */
Route::get('/transaksi/bkeluardestroy/{id}', [BkeluarController::class, 'destroy'])->name('pos01.transaksi.bkeluar_destroy')->middleware('auth'); /* hapus data bkeluar */

/* transaksi - mkeluar */
Route::get('/transaksi/mkeluar', [MkeluarController ::class, 'index'])->name('pos01.transaksi.mkeluar.index')->middleware('auth'); /* halaman mkeluar */
Route::get('/transaksi/mkeluarshow', [MkeluarController::class, 'show'])->name('pos01.transaksi.mkeluar_show')->middleware('auth'); /* menampilkan data mkeluar pada datatable javascript */
Route::post('/transaksi/mkeluarkirimsyarat', [MkeluarController::class, 'kirimsyarat'])->name('pos01.transaksi.mkeluar_kirimsyarat')->middleware('auth'); /* kirim syarat */
Route::post('/transaksi/mkeluarcariid', [MkeluarController::class, 'cariid'])->name('pos01.transaksi.mkeluar_cariid')->middleware('auth'); /* cari data anggota */
Route::get('/transaksi/mkeluarshowmamin', [MkeluarController::class, 'showmamin'])->name('pos01.transaksi.mkeluar_showmamin')->middleware('auth'); /* menampilkan mamin pada datatable javascript */
Route::get('/transaksi/mkeluarshowanggota', [MkeluarController::class, 'showanggota'])->name('pos01.transaksi.mkeluar_showanggota')->middleware('auth'); /* menampilkan anggota pada datatable javascript */
Route::post('/transaksi/mkeluarnomorbukti', [MkeluarController::class, 'nomorbukti'])->name('pos01.transaksi.mkeluar_nomorbukti')->middleware('auth'); /* buat nomorbukti */
Route::post('/transaksi/mkeluarnomorposting', [MkeluarController::class, 'nomorposting'])->name('pos01.transaksi.mkeluar_nomorposting')->middleware('auth'); /* buat nomorposting */
Route::get('/transaksi/mkeluarlistruang', [MkeluarController::class, 'listruang'])->name('pos01.transaksi.mkeluar_listruang')->middleware('auth'); /* menampilkan listruang */
Route::get('/transaksi/mkeluarlistmamin', [MkeluarController::class, 'listmamin'])->name('pos01.transaksi.mkeluar_listmamin')->middleware('auth'); /* menampilkan list mamin */
Route::get('/transaksi/mkeluarlistanggota', [MkeluarController::class, 'listanggota'])->name('pos01.transaksi.mkeluar_listanggota')->middleware('auth'); /* menampilkan list anggota */
Route::get('/transaksi/mkeluarlistjenispembayaran', [MkeluarController::class, 'listjenispembayaran'])->name('pos01.transaksi.mkeluar_listjenispembayaran')->middleware('auth'); /* menampilkan list jenispembayaran */
Route::post('/transaksi/mkeluarcreate', [MkeluarController::class, 'create'])->name('pos01.transaksi.mkeluar_create')->middleware('auth'); /* menambah data mkeluar */
Route::post('/transaksi/mkeluarproses', [MkeluarController::class, 'proses'])->name('pos01.transaksi.mkeluar_proses')->middleware('auth'); /* menambah proses */
Route::get('/transaksi/mkeluaredit/{id}', [MkeluarController::class, 'edit'])->name('pos01.transaksi.mkeluar_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::get('/transaksi/mkeluardisplaypembayaran/{id}', [MkeluarController::class, 'displaypembayaran'])->name('pos01.transaksi.mkeluar_displaypembayaran')->middleware('auth'); /* menampilkan pembayaran */
Route::get('/transaksi/mkeluarlihatpersen/{id}', [MkeluarController::class, 'lihatpersen'])->name('pos01.transaksi.mkeluar_lihatpersen')->middleware('auth'); /* menampilkan lihatpersen jasa */
Route::post('/transaksi/mkeluarupdate', [MkeluarController::class, 'update'])->name('pos01.transaksi.mkeluar_update')->middleware('auth'); /* update data mkeluar */
Route::post('/transaksi/mkeluarposting', [MkeluarController::class, 'posting'])->name('pos01.transaksi.mkeluar_posting')->middleware('auth'); /* posting data mkeluar */
Route::get('/transaksi/mkeluardestroy/{id}', [MkeluarController::class, 'destroy'])->name('pos01.transaksi.mkeluar_destroy')->middleware('auth'); /* hapus data mkeluar */

/* transaksi - bayarhutangcustomer */
Route::get('/transaksi/bayarhutangcustomer', [BayarhutangcustomerController::class, 'index'])->name('pos01.transaksi.bayarhutangcustomer.index')->middleware('auth'); /* halaman bayarhutangcustomer */
Route::get('/transaksi/bayarhutangcustomershow', [BayarhutangcustomerController::class, 'show'])->name('pos01.transaksi.bayarhutangcustomer_show')->middleware('auth'); /* menampilkan data bayarhutangcustomer pada datatable javascript */
Route::post('/transaksi/bayarhutangcustomerkirimsyarat', [BayarhutangcustomerController::class, 'kirimsyarat'])->name('pos01.transaksi.bayarhutangcustomer_kirimsyarat')->middleware('auth'); /* kirim syarat */
Route::post('/transaksi/bayarhutangcustomercariid', [BayarhutangcustomerController::class, 'cariid'])->name('pos01.transaksi.bayarhutangcustomer_cariid')->middleware('auth'); /* cari data anggota */
Route::get('/transaksi/bayarhutangcustomershowhutang', [BayarhutangcustomerController::class, 'showhutang'])->name('pos01.transaksi.bayarhutangcustomer_showhutang')->middleware('auth'); /* menampilkan hutang pada datatable javascript */
Route::get('/transaksi/bayarhutangcustomershowanggota', [BayarhutangcustomerController::class, 'showanggota'])->name('pos01.transaksi.bayarhutangcustomer_showanggota')->middleware('auth'); /* menampilkan anggota pada datatable javascript */
Route::post('/transaksi/bayarhutangcustomernomorbukti', [BayarhutangcustomerController::class, 'nomorbukti'])->name('pos01.transaksi.bayarhutangcustomer_nomorbukti')->middleware('auth'); /* buat nomorbukti */
Route::post('/transaksi/bayarhutangcustomernomorposting', [BayarhutangcustomerController::class, 'nomorposting'])->name('pos01.transaksi.bayarhutangcustomer_nomorposting')->middleware('auth'); /* buat nomorposting */
Route::get('/transaksi/bayarhutangcustomerlisthutang', [BayarhutangcustomerController::class, 'listhutang'])->name('pos01.transaksi.bayarhutangcustomer_listhutang')->middleware('auth'); /* menampilkan list hutang */
Route::get('/transaksi/bayarhutangcustomerlisthutangx', [BayarhutangcustomerController::class, 'listhutangx'])->name('pos01.transaksi.bayarhutangcustomer_listhutangx')->middleware('auth'); /* menampilkan list hutangx */
Route::get('/transaksi/bayarhutangcustomerlistruang', [BayarhutangcustomerController::class, 'listruang'])->name('pos01.transaksi.bayarhutangcustomer_listruang')->middleware('auth'); /* menampilkan list ruang */
Route::get('/transaksi/bayarhutangcustomerlistanggota', [BayarhutangcustomerController::class, 'listanggota'])->name('pos01.transaksi.bayarhutangcustomer_listanggota')->middleware('auth'); /* menampilkan list anggota */
Route::get('/transaksi/bayarhutangcustomerlistjenispembayaran', [BayarhutangcustomerController::class, 'listjenispembayaran'])->name('pos01.transaksi.bayarhutangcustomer_listjenispembayaran')->middleware('auth'); /* menampilkan list jenispembayaran */
Route::post('/transaksi/bayarhutangcustomercreate', [BayarhutangcustomerController::class, 'create'])->name('pos01.transaksi.bayarhutangcustomer_create')->middleware('auth'); /* menambah data bayarhutangcustomer */
Route::post('/transaksi/bayarhutangcustomerproses', [BayarhutangcustomerController::class, 'proses'])->name('pos01.transaksi.bayarhutangcustomer_proses')->middleware('auth'); /* menambah proses */
Route::get('/transaksi/bayarhutangcustomeredit/{id}', [BayarhutangcustomerController::class, 'edit'])->name('pos01.transaksi.bayarhutangcustomer_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::get('/transaksi/bayarhutangcustomerdisplayhutang/{id}', [BayarhutangcustomerController::class, 'displayhutang'])->name('pos01.transaksi.bayarhutangcustomer_displayhutang')->middleware('auth'); /* menampilkan data hutang */
Route::get('/transaksi/bayarhutangcustomerdisplaypembayaran/{id}', [BayarhutangcustomerController::class, 'displaypembayaran'])->name('pos01.transaksi.bayarhutangcustomer_displaypembayaran')->middleware('auth'); /* menampilkan pembayaran */
Route::post('/transaksi/bayarhutangcustomerupdate', [BayarhutangcustomerController::class, 'update'])->name('pos01.transaksi.bayarhutangcustomer_update')->middleware('auth'); /* update data bayarhutangcustomer */
Route::post('/transaksi/bayarhutangcustomerposting', [BayarhutangcustomerController::class, 'posting'])->name('pos01.transaksi.bayarhutangcustomer_posting')->middleware('auth'); /* posting data bayarhutangcustomer */
Route::get('/transaksi/bayarhutangcustomerdestroy/{id}', [BayarhutangcustomerController::class, 'destroy'])->name('pos01.transaksi.bayarhutangcustomer_destroy')->middleware('auth'); /* hapus data bayarhutangcustomer */

/* transaksi - bayarhutangsupplier */
Route::get('/transaksi/bayarhutangsupplier', [BayarhutangSupplierController ::class, 'index'])->name('pos01.transaksi.bayarhutangsupplier.index')->middleware('auth'); /* halaman bayarhutangsupplier */
Route::get('/transaksi/bayarhutangsuppliershow', [BayarhutangsupplierController::class, 'show'])->name('pos01.transaksi.bayarhutangsupplier_show')->middleware('auth'); /* menampilkan data bayarhutangsupplier pada datatable javascript */
Route::post('/transaksi/bayarhutangsupplierkirimsyarat', [BayarhutangsupplierController::class, 'kirimsyarat'])->name('pos01.transaksi.bayarhutangsupplier_kirimsyarat')->middleware('auth'); /* kirim syarat */
Route::post('/transaksi/bayarhutangsuppliercariid', [BayarhutangsupplierController::class, 'cariid'])->name('pos01.transaksi.bayarhutangsupplier_cariid')->middleware('auth'); /* cari data supplier */
Route::get('/transaksi/bayarhutangsuppliershowhutang', [BayarhutangsupplierController::class, 'showhutang'])->name('pos01.transaksi.bayarhutangsupplier_showhutang')->middleware('auth'); /* menampilkan hutang pada datatable javascript */
Route::get('/transaksi/bayarhutangsuppliershowsupplier', [BayarhutangsupplierController::class, 'showsupplier'])->name('pos01.transaksi.bayarhutangsupplier_showsupplier')->middleware('auth'); /* menampilkan supplier pada datatable javascript */
Route::post('/transaksi/bayarhutangsuppliernomorbukti', [BayarhutangsupplierController::class, 'nomorbukti'])->name('pos01.transaksi.bayarhutangsupplier_nomorbukti')->middleware('auth'); /* buat nomorbukti */
Route::post('/transaksi/bayarhutangsuppliernomorposting', [BayarhutangsupplierController::class, 'nomorposting'])->name('pos01.transaksi.bayarhutangsupplier_nomorposting')->middleware('auth'); /* buat nomorposting */
Route::get('/transaksi/bayarhutangsupplierlisthutang', [BayarhutangsupplierController::class, 'listhutang'])->name('pos01.transaksi.bayarhutangsupplier_listhutang')->middleware('auth'); /* menampilkan list hutang */
Route::get('/transaksi/bayarhutangsupplierlisthutangx', [BayarhutangsupplierController::class, 'listhutangx'])->name('pos01.transaksi.bayarhutangsupplier_listhutangx')->middleware('auth'); /* menampilkan list hutangx */
Route::get('/transaksi/bayarhutangsupplierlistruang', [BayarhutangsupplierController::class, 'listruang'])->name('pos01.transaksi.bayarhutangsupplier_listruang')->middleware('auth'); /* menampilkan list ruang */
Route::get('/transaksi/bayarhutangsupplierlistsupplier', [BayarhutangsupplierController::class, 'listsupplier'])->name('pos01.transaksi.bayarhutangsupplier_listsupplier')->middleware('auth'); /* menampilkan list anggota */
Route::get('/transaksi/bayarhutangsupplierlistjenispembayaran', [BayarhutangsupplierController::class, 'listjenispembayaran'])->name('pos01.transaksi.bayarhutangsupplier_listjenispembayaran')->middleware('auth'); /* menampilkan list jenispembayaran */
Route::post('/transaksi/bayarhutangsuppliercreate', [BayarhutangsupplierController::class, 'create'])->name('pos01.transaksi.bayarhutangsupplier_create')->middleware('auth'); /* menambah data bayarhutangsupplier */
Route::post('/transaksi/bayarhutangsupplierproses', [BayarhutangsupplierController::class, 'proses'])->name('pos01.transaksi.bayarhutangsupplier_proses')->middleware('auth'); /* menambah proses */
Route::get('/transaksi/bayarhutangsupplieredit/{id}', [BayarhutangsupplierController::class, 'edit'])->name('pos01.transaksi.bayarhutangsupplier_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::get('/transaksi/bayarhutangsupplierdisplayhutang/{id}', [BayarhutangsupplierController::class, 'displayhutang'])->name('pos01.transaksi.bayarhutangsupplier_displayhutang')->middleware('auth'); /* menampilkan data hutang */
Route::get('/transaksi/bayarhutangsupplierdisplaypembayaran/{id}', [BayarhutangsupplierController::class, 'displaypembayaran'])->name('pos01.transaksi.bayarhutangsupplier_displaypembayaran')->middleware('auth'); /* menampilkan pembayaran */
Route::post('/transaksi/bayarhutangsupplierupdate', [BayarhutangsupplierController::class, 'update'])->name('pos01.transaksi.bayarhutangsupplier_update')->middleware('auth'); /* update data bayarhutangsupplier */
Route::post('/transaksi/bayarhutangsupplierposting', [BayarhutangsupplierController::class, 'posting'])->name('pos01.transaksi.bayarhutangsupplier_posting')->middleware('auth'); /* posting data bayarhutangsupplier */
Route::get('/transaksi/bayarhutangsupplierdestroy/{id}', [BayarhutangsupplierController::class, 'destroy'])->name('pos01.transaksi.bayarhutangsupplier_destroy')->middleware('auth'); /* hapus data bayarhutangsupplier */

/* laporan - stokbarang */
Route::get('/laporan/stokbarang', [StokbarangController::class, 'index'])->name('pos01.laporan.stokbarang.index')->middleware('auth'); /* halaman stokbarang */
Route::get('/laporan/stokbarangshowstokbarang', [StokbarangController::class, 'showstokbarang'])->name('pos01.laporan.stokbarang_showstokbarang')->middleware('auth'); /* menampilkan data stokbarang pada datatable javascript */
Route::get('/laporan/stokbarangshowstokfifo', [StokbarangController::class, 'showstokfifo'])->name('pos01.laporan.stokbarang_showstokfifo')->middleware('auth'); /* menampilkan data stokfifo pada datatable javascript */
Route::get('/laporan/stokbarangshowstokmova', [StokbarangController::class, 'showstokmova'])->name('pos01.laporan.stokbarang_showstokmova')->middleware('auth'); /* menampilkan data stokmova pada datatable javascript */
Route::get('/laporan/stokbarangshowstoklifo', [StokbarangController::class, 'showstoklifo'])->name('pos01.laporan.stokbarang_showstoklifo')->middleware('auth'); /* menampilkan data stoklifo pada datatable javascript */
Route::get('/laporan/stokbarangshowstokexpired', [StokbarangController::class, 'showstokexpired'])->name('pos01.laporan.stokbarang_showstokexpired')->middleware('auth'); /* menampilkan data stokexpired pada datatable javascript */
Route::get('/laporan/stokbarangshowstokmin', [StokbarangController::class, 'showstokmin'])->name('pos01.laporan.stokbarang_showstokmin')->middleware('auth'); /* menampilkan data stokmin pada datatable javascript */
Route::get('/laporan/stokbarangshowstokmax', [StokbarangController::class, 'showstokmax'])->name('pos01.laporan.stokbarang_showstokmax')->middleware('auth'); /* menampilkan data stokmax pada datatable javascript */
Route::get('/laporan/stokbarangshowstokhabis', [StokbarangController::class, 'showstokhabis'])->name('pos01.laporan.stokbarang_showstokhabis')->middleware('auth'); /* menampilkan data stokhabis pada datatable javascript */
Route::get('/laporan/stokbarangshowbarang', [StokbarangController::class, 'showbarang'])->name('pos01.laporan.stokbarang_showbarang')->middleware('auth'); /* menampilkan data barang yg gk ada di stokbarang pada datatable javascript */
Route::get('/laporan/stokbaranglistbarang', [StokbarangController::class, 'listbarang'])->name('pos01.laporan.stokbarang_listbarang')->middleware('auth'); /* menampilkan list barang */
Route::get('/laporan/stokbaranglistruang', [StokbarangController::class, 'listruang'])->name('pos01.laporan.stokbarang_listruang')->middleware('auth'); /* menampilkan list ruang */
Route::post('/laporan/stokbarangcreate', [StokbarangController::class, 'create'])->name('pos01.laporan.stokbarang_create')->middleware('auth'); /* menambah stokbarang */
Route::post('/laporan/stokbarangkirimsyarat', [StokbarangController::class, 'kirimsyarat'])->name('pos01.laporan.stokbarang_kirimsyarat')->middleware('auth'); /* kirimsyarat */
Route::get('/laporan/stokbarangedit/{id}', [StokbarangController::class, 'edit'])->name('pos01.laporan.stokbarang_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::get('/laporan/stokbarangdestroy/{id}', [StokbarangController::class, 'destroy'])->name('pos01.laporan.stokbarang_destroy')->middleware('auth'); /* hapus data stokbarang */

/* transaksi - biayabiaya */
Route::get('/transaksi/biayabiaya', [BiayabiayaController::class, 'index'])->name('pos01.transaksi.biayabiaya.index')->middleware('auth'); /* halaman biayabiaya */
Route::get('/transaksi/biayabiayashow', [BiayabiayaController::class, 'show'])->name('pos01.transaksi.biayabiaya_show')->middleware('auth'); /* menampilkan data biayabiaya pada datatable javascript */
Route::get('/transaksi/biayabiayashowbarang', [BiayabiayaController::class, 'showbarang'])->name('pos01.transaksi.biayabiaya_showbarang')->middleware('auth'); /* menampilkan data barang yg gk ada di biayabiaya pada datatable javascript */
Route::get('/transaksi/biayabiayalistjenisbiaya', [BiayabiayaController::class, 'listjenisbiaya'])->name('pos01.transaksi.biayabiaya_listjenisbiaya')->middleware('auth'); /* menampilkan listjenisbiaya */
Route::get('/transaksi/biayabiayalistjenisbiayax', [BiayabiayaController::class, 'listjenisbiayax'])->name('pos01.transaksi.biayabiaya_listjenisbiayax')->middleware('auth'); /* menampilkan listjenisbiaya */
Route::get('/transaksi/biayabiayalistkategoribiaya', [BiayabiayaController::class, 'listkategoribiaya'])->name('pos01.transaksi.biayabiaya_listkategoribiaya')->middleware('auth'); /* menampilkan listkategoribiaya */
Route::get('/transaksi/biayabiayalistkategoribiayax', [BiayabiayaController::class, 'listkategoribiayax'])->name('pos01.transaksi.biayabiaya_listkategoribiayax')->middleware('auth'); /* menampilkan listkategoribiaya */
Route::get('/transaksi/biayabiayalistsupplier', [BiayabiayaController::class, 'listsupplier'])->name('pos01.transaksi.biayabiaya_listsupplier')->middleware('auth'); /* menampilkan listsupplier */
Route::get('/transaksi/biayabiayalistruang', [BiayabiayaController::class, 'listruang'])->name('pos01.transaksi.biayabiaya_listruang')->middleware('auth'); /* menampilkan list ruang */
Route::get('/transaksi/biayabiayalistsatuan', [BiayabiayaController::class, 'listsatuan'])->name('pos01.transaksi.biayabiaya_listsatuan')->middleware('auth'); /* menampilkan listsatuan */
Route::post('/transaksi/biayabiayacreate', [BiayabiayaController::class, 'create'])->name('pos01.transaksi.biayabiaya_create')->middleware('auth'); /* menambah biayabiaya */
Route::post('/transaksi/biayabiayakirimsyarat', [BiayabiayaController::class, 'kirimsyarat'])->name('pos01.transaksi.biayabiaya_kirimsyarat')->middleware('auth'); /* kirimsyarat */
Route::get('/transaksi/biayabiayaedit/{id}', [BiayabiayaController::class, 'edit'])->name('pos01.transaksi.biayabiaya_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::get('/transaksi/biayabiayadestroy/{id}', [BiayabiayaController::class, 'destroy'])->name('pos01.transaksi.biayabiaya_destroy')->middleware('auth'); /* hapus data biayabiaya */

/* transaksi - pendapatanlain */
Route::get('/transaksi/pendapatanlain', [PendapatanlainController::class, 'index'])->name('pos01.transaksi.pendapatanlain.index')->middleware('auth'); /* halaman pendapatanlain */
Route::get('/transaksi/pendapatanlainshow', [PendapatanlainController::class, 'show'])->name('pos01.transaksi.pendapatanlain_show')->middleware('auth'); /* menampilkan data pendapatanlain pada datatable javascript */
Route::get('/transaksi/pendapatanlainshowbarang', [PendapatanlainController::class, 'showbarang'])->name('pos01.transaksi.pendapatanlain_showbarang')->middleware('auth'); /* menampilkan data barang yg gk ada di pendapatanlain pada datatable javascript */
Route::get('/transaksi/pendapatanlainlistkategoribiaya', [PendapatanlainController::class, 'listkategoribiaya'])->name('pos01.transaksi.pendapatanlain_listkategoribiaya')->middleware('auth'); /* menampilkan listkategoribiaya */
Route::get('/transaksi/pendapatanlainlistkategoribiayax', [PendapatanlainController::class, 'listkategoribiayax'])->name('pos01.transaksi.pendapatanlain_listkategoribiayax')->middleware('auth'); /* menampilkan listkategoribiaya */
Route::get('/transaksi/pendapatanlainlistruang', [PendapatanlainController::class, 'listruang'])->name('pos01.transaksi.pendapatanlain_listruang')->middleware('auth'); /* menampilkan list ruang */
Route::get('/transaksi/pendapatanlainlistsatuan', [PendapatanlainController::class, 'listsatuan'])->name('pos01.transaksi.pendapatanlain_listsatuan')->middleware('auth'); /* menampilkan listsatuan */
Route::post('/transaksi/pendapatanlaincreate', [PendapatanlainController::class, 'create'])->name('pos01.transaksi.pendapatanlain_create')->middleware('auth'); /* menambah pendapatanlain */
Route::post('/transaksi/pendapatanlainkirimsyarat', [PendapatanlainController::class, 'kirimsyarat'])->name('pos01.transaksi.pendapatanlain_kirimsyarat')->middleware('auth'); /* kirimsyarat */
Route::get('/transaksi/pendapatanlainedit/{id}', [PendapatanlainController::class, 'edit'])->name('pos01.transaksi.pendapatanlain_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::get('/transaksi/pendapatanlaindestroy/{id}', [PendapatanlainController::class, 'destroy'])->name('pos01.transaksi.pendapatanlain_destroy')->middleware('auth'); /* hapus data pendapatanlain */

/* laporan - stokkeluarmasuk */
Route::get('/laporan/stokkeluarmasuk', [StokkeluarmasukController::class, 'index'])->name('pos01.laporan.stokkeluarmasuk.index')->middleware('auth'); /* halaman stokkeluarmasuk */
Route::get('/laporan/stokkeluarmasukshowstokmasuk', [StokkeluarmasukController::class, 'showstokmasuk'])->name('pos01.laporan.stokkeluarmasuk_showstokmasuk')->middleware('auth'); /* menampilkan data stokmsduk pada datatable javascript */
Route::get('/laporan/stokkeluarmasukshowstokmasukfifo', [StokkeluarmasukController::class, 'showstokmasukfifo'])->name('pos01.laporan.stokkeluarmasuk_showstokmasukfifo')->middleware('auth'); /* menampilkan data stokmasukfifo pada datatable javascript */
Route::get('/laporan/stokkeluarmasukshowstokmasukmova', [StokkeluarmasukController::class, 'showstokmasukmova'])->name('pos01.laporan.stokkeluarmasuk_showstokmasukmova')->middleware('auth'); /* menampilkan data stokmasukmova pada datatable javascript */
Route::get('/laporan/stokkeluarmasukshowstokmasuklifo', [StokkeluarmasukController::class, 'showstokmasuklifo'])->name('pos01.laporan.stokkeluarmasuk_showstokmasuklifo')->middleware('auth'); /* menampilkan data stokmasuklifo pada datatable javascript */
Route::get('/laporan/stokkeluarmasukshowstokkeluar', [StokkeluarmasukController::class, 'showstokkeluar'])->name('pos01.laporan.stokkeluarmasuk_showstokkeluar')->middleware('auth'); /* menampilkan data stokkeluar pada datatable javascript */
Route::get('/laporan/stokkeluarmasukshowstokkeluarfifo', [StokkeluarmasukController::class, 'showstokkeluarfifo'])->name('pos01.laporan.stokkeluarmasuk_showstokkeluarfifo')->middleware('auth'); /* menampilkan data stokkeluarfifo pada datatable javascript */
Route::get('/laporan/stokkeluarmasukshowstokkeluarmova', [StokkeluarmasukController::class, 'showstokkeluarmova'])->name('pos01.laporan.stokkeluarmasuk_showstokkeluarmova')->middleware('auth'); /* menampilkan data stokkeluarmova pada datatable javascript */
Route::get('/laporan/stokkeluarmasukshowstokkeluarlifo', [StokkeluarmasukController::class, 'showstokkeluarlifo'])->name('pos01.laporan.stokkeluarmasuk_showstokkeluarlifo')->middleware('auth'); /* menampilkan data stokkeluarlifo pada datatable javascript */
Route::get('/laporan/stokkeluarmasukshowstokrekap', [StokkeluarmasukController::class, 'showstokrekap'])->name('pos01.laporan.stokkeluarmasuk_showstokrekap')->middleware('auth'); /* menampilkan data stokrekap pada datatable javascript */
Route::get('/laporan/stokkeluarmasukshowstokrekapfifo', [StokkeluarmasukController::class, 'showstokrekapfifo'])->name('pos01.laporan.stokkeluarmasuk_showstokrekapfifo')->middleware('auth'); /* menampilkan data stokrekapfifo pada datatable javascript */
Route::get('/laporan/stokkeluarmasukshowstokrekapmova', [StokkeluarmasukController::class, 'showstokrekapmova'])->name('pos01.laporan.stokkeluarmasuk_showstokrekapmova')->middleware('auth'); /* menampilkan data stokrekapmova pada datatable javascript */
Route::get('/laporan/stokkeluarmasukshowstokrekaplifo', [StokkeluarmasukController::class, 'showstokrekaplifo'])->name('pos01.laporan.stokkeluarmasuk_showstokrekaplifo')->middleware('auth'); /* menampilkan data stokrekaplifo pada datatable javascript */
Route::get('/laporan/stokkeluarmasukshowbarang', [StokkeluarmasukController::class, 'showbarang'])->name('pos01.laporan.stokkeluarmasuk_showbarang')->middleware('auth'); /* menampilkan data barang yg gk ada di stokkeluarmasuk pada datatable javascript */
Route::get('/laporan/stokkeluarmasuklistbarang', [StokkeluarmasukController::class, 'listbarang'])->name('pos01.laporan.stokkeluarmasuk_listbarang')->middleware('auth'); /* menampilkan list barang */
Route::get('/laporan/stokkeluarmasuklistruang', [StokkeluarmasukController::class, 'listruang'])->name('pos01.laporan.stokkeluarmasuk_listruang')->middleware('auth'); /* menampilkan list ruang */
Route::post('/laporan/stokkeluarmasukcreate', [StokkeluarmasukController::class, 'create'])->name('pos01.laporan.stokkeluarmasuk_create')->middleware('auth'); /* menambah stokkeluarmasuk */
Route::post('/laporan/stokkeluarmasukkirimsyarat', [StokkeluarmasukController::class, 'kirimsyarat'])->name('pos01.laporan.stokkeluarmasuk_kirimsyarat')->middleware('auth'); /* kirimsyarat */
Route::get('/laporan/stokkeluarmasukedit/{id}', [StokkeluarmasukController::class, 'edit'])->name('pos01.laporan.stokkeluarmasuk_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::get('/laporan/stokkeluarmasukdestroy/{id}', [StokkeluarmasukController::class, 'destroy'])->name('pos01.laporan.stokkeluarmasuk_destroy')->middleware('auth'); /* hapus data stokkeluarmasuk */

/* laporan - kartustok */
Route::get('/laporan/kartustok', [KartustokController::class, 'index'])->name('pos01.laporan.kartustok.index')->middleware('auth'); /* halaman kartustok */
Route::get('/laporan/kartustokshowstokrekap', [KartustokController::class, 'showstokrekap'])->name('pos01.laporan.kartustok_showstokrekap')->middleware('auth'); /* menampilkan data stokrekap pada datatable javascript */
Route::get('/laporan/kartustokshowstokrekapfifo', [KartustokController::class, 'showstokrekapfifo'])->name('pos01.laporan.kartustok_showstokrekapfifo')->middleware('auth'); /* menampilkan data stokrekapfifo pada datatable javascript */
Route::get('/laporan/kartustokshowstokrekapmova', [KartustokController::class, 'showstokrekapmova'])->name('pos01.laporan.kartustok_showstokrekapmova')->middleware('auth'); /* menampilkan data stokrekapmova pada datatable javascript */
Route::get('/laporan/kartustokshowstokrekaplifo', [KartustokController::class, 'showstokrekaplifo'])->name('pos01.laporan.kartustok_showstokrekaplifo')->middleware('auth'); /* menampilkan data stokrekaplifo pada datatable javascript */
Route::get('/laporan/kartustokshowbarang', [KartustokController::class, 'showbarang'])->name('pos01.laporan.kartustok_showbarang')->middleware('auth'); /* menampilkan data barang yg gk ada di kartustok pada datatable javascript */
Route::get('/laporan/kartustoklistbarang', [KartustokController::class, 'listbarang'])->name('pos01.laporan.kartustok_listbarang')->middleware('auth'); /* menampilkan list barang */
Route::get('/laporan/kartustoklistruang', [KartustokController::class, 'listruang'])->name('pos01.laporan.kartustok_listruang')->middleware('auth'); /* menampilkan list ruang */
Route::post('/laporan/kartustokcreate', [KartustokController::class, 'create'])->name('pos01.laporan.kartustok_create')->middleware('auth'); /* menambah kartustok */
Route::post('/laporan/kartustokkirimsyarat', [KartustokController::class, 'kirimsyarat'])->name('pos01.laporan.kartustok_kirimsyarat')->middleware('auth'); /* kirimsyarat */
Route::get('/laporan/kartustokedit/{id}', [KartustokController::class, 'edit'])->name('pos01.laporan.kartustok_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::get('/laporan/kartustokdestroy/{id}', [KartustokController::class, 'destroy'])->name('pos01.laporan.kartustok_destroy')->middleware('auth'); /* hapus data kartustok */

/* laporan - kartuhutang */
Route::get('/laporan/kartuhutang', [KartuhutangController ::class, 'index'])->name('pos01.laporan.kartuhutang.index')->middleware('auth'); /* halaman kartuhutang */
Route::get('/laporan/kartuhutangshowkartuhutang', [KartuhutangController::class, 'showkartuhutang'])->name('pos01.laporan.kartuhutang_showkartuhutang')->middleware('auth'); /* menampilkan data showkartuhutang pada datatable javascript */
Route::get('/laporan/kartuhutangshowhutang', [KartuhutangController::class, 'showhutang'])->name('pos01.laporan.kartuhutang_showhutang')->middleware('auth'); /* menampilkan data hutang yg gk ada di kartuhutang pada datatable javascript */
Route::get('/laporan/kartuhutanglisthutang', [KartuhutangController::class, 'listhutang'])->name('pos01.laporan.kartuhutang_listhutang')->middleware('auth'); /* menampilkan list hutang */
Route::get('/laporan/kartuhutanglisthutangx', [KartuhutangController::class, 'listhutangx'])->name('pos01.laporan.kartuhutang_listhutangx')->middleware('auth'); /* menampilkan list hutang */
Route::post('/laporan/kartuhutangcreate', [KartuhutangController::class, 'create'])->name('pos01.laporan.kartuhutang_create')->middleware('auth'); /* menambah kartuhutang */
Route::post('/laporan/kartuhutangkirimsyarat', [KartuhutangController::class, 'kirimsyarat'])->name('pos01.laporan.kartuhutang_kirimsyarat')->middleware('auth'); /* kirimsyarat */
Route::get('/laporan/kartuhutangedit/{id}', [KartuhutangController::class, 'edit'])->name('pos01.laporan.kartuhutang_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::get('/laporan/kartuhutangdestroy/{id}', [KartuhutangController::class, 'destroy'])->name('pos01.laporan.kartuhutang_destroy')->middleware('auth'); /* hapus data kartuhutang */

/* laporan - kartupiutang */
Route::get('/laporan/kartupiutang', [KartupiutangController ::class, 'index'])->name('pos01.laporan.kartupiutang.index')->middleware('auth'); /* halaman kartupiutang */
Route::get('/laporan/kartupiutangshowkartupiutang', [KartupiutangController::class, 'showkartupiutang'])->name('pos01.laporan.kartupiutang_showkartupiutang')->middleware('auth'); /* menampilkan data showkartupiutang pada datatable javascript */
Route::get('/laporan/kartupiutangshowpiutang', [KartupiutangController::class, 'showpiutang'])->name('pos01.laporan.kartupiutang_showpiutang')->middleware('auth'); /* menampilkan data piutang yg gk ada di kartupiutang pada datatable javascript */
Route::get('/laporan/kartupiutanglistpiutang', [KartupiutangController::class, 'listpiutang'])->name('pos01.laporan.kartupiutang_listpiutang')->middleware('auth'); /* menampilkan list piutang */
Route::get('/laporan/kartupiutanglistpiutangx', [KartupiutangController::class, 'listpiutangx'])->name('pos01.laporan.kartupiutang_listpiutangx')->middleware('auth'); /* menampilkan list hutang */
Route::post('/laporan/kartupiutangcreate', [KartupiutangController::class, 'create'])->name('pos01.laporan.kartupiutang_create')->middleware('auth'); /* menambah kartupiutang */
Route::post('/laporan/kartupiutangkirimsyarat', [KartupiutangController::class, 'kirimsyarat'])->name('pos01.laporan.kartupiutang_kirimsyarat')->middleware('auth'); /* kirimsyarat */
Route::get('/laporan/kartupiutangedit/{id}', [KartupiutangController::class, 'edit'])->name('pos01.laporan.kartupiutang_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::get('/laporan/kartupiutangdestroy/{id}', [KartupiutangController::class, 'destroy'])->name('pos01.laporan.kartupiutang_destroy')->middleware('auth'); /* hapus data kartupiutang */

/* laporan - pembelian */
Route::get('/laporan/pembelian', [PembelianController::class, 'index'])->name('pos01.laporan.pembelian.index')->middleware('auth'); /* halaman pembelian */
Route::get('/laporan/pembelianshowpembeliandetail', [PembelianController::class, 'showpembeliandetail'])->name('pos01.laporan.pembelian_showpembeliandetail')->middleware('auth'); /* menampilkan data pembeliandetail pada datatable javascript */
Route::get('/laporan/pembelianshowpembelianperitem', [PembelianController::class, 'showpembelianperitem'])->name('pos01.laporan.pembelian_showpembelianperitem')->middleware('auth'); /* menampilkan data pembelianperitem pada datatable javascript */
Route::get('/laporan/pembelianshowpembelianpersupplier', [PembelianController::class, 'showpembelianpersupplier'])->name('pos01.laporan.pembelian_showpembelianpersupplier')->middleware('auth'); /* menampilkan data pembelianpersupplier pada datatable javascript */
Route::get('/laporan/pembelianshowpembelianperfaktur', [PembelianController::class, 'showpembelianperfaktur'])->name('pos01.laporan.pembelian_showpembelianperfaktur')->middleware('auth'); /* menampilkan data pembelianperfaktur pada datatable javascript */
Route::get('/laporan/pembelianshowpembelianperjenispembayaran', [PembelianController::class, 'showpembelianperjenispembayaran'])->name('pos01.laporan.pembelian_showpembelianperjenispembayaran')->middleware('auth'); /* menampilkan data pembelianperjenispembayaran pada datatable javascript */
Route::get('/laporan/pembelianshowpembelianpertanggal', [PembelianController::class, 'showpembelianpertanggal'])->name('pos01.laporan.pembelian_showpembelianpertanggal')->middleware('auth'); /* menampilkan data pembelianpertanggal pada datatable javascript */
Route::get('/laporan/pembelianshowsupplier', [PembelianController::class, 'showsupplier'])->name('pos01.laporan.pembelian_showsupplier')->middleware('auth'); /* menampilkan data supplier yg gk ada di pembelian pada datatable javascript */
Route::get('/laporan/pembelianlistsupplier', [PembelianController::class, 'listsupplier'])->name('pos01.laporan.pembelian_listsupplier')->middleware('auth'); /* menampilkan list supplier */
Route::get('/laporan/pembelianlistruang', [PembelianController::class, 'listruang'])->name('pos01.laporan.pembelian_listruang')->middleware('auth'); /* menampilkan list ruang */
Route::get('/laporan/pembelianlistjenispembayaran', [PembelianController::class, 'listjenispembayaran'])->name('pos01.laporan.pembelian_listjenispembayaran')->middleware('auth'); /* menampilkan list jenispembayaran */
Route::post('/laporan/pembeliancreate', [PembelianController::class, 'create'])->name('pos01.laporan.pembelian_create')->middleware('auth'); /* menambah pembelian */
Route::post('/laporan/pembeliankirimsyarat', [PembelianController::class, 'kirimsyarat'])->name('pos01.laporan.pembelian_kirimsyarat')->middleware('auth'); /* kirimsyarat */
Route::post('/laporan/pembelianceksupplier', [PembelianController::class, 'ceksupplier'])->name('pos01.laporan.pembelian_ceksupplier')->middleware('auth'); /* ceksupplier */
Route::get('/laporan/pembelianedit/{id}', [PembelianController::class, 'edit'])->name('pos01.laporan.pembelian_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::get('/laporan/pembeliandestroy/{id}', [PembelianController::class, 'destroy'])->name('pos01.laporan.pembelian_destroy')->middleware('auth'); /* hapus data pembelian */

/* laporan - penjualan */
Route::get('/laporan/penjualan', [PenjualanController::class, 'index'])->name('pos01.laporan.penjualan.index')->middleware('auth'); /* halaman penjualan */
Route::get('/laporan/penjualanshowpenjualansajadetail', [PenjualanController::class, 'showpenjualansajadetail'])->name('pos01.laporan.penjualan_showpenjualansajadetail')->middleware('auth'); /* menampilkan data penjualansajadetail pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualansajaperitem', [PenjualanController::class, 'showpenjualansajaperitem'])->name('pos01.laporan.penjualan_showpenjualansajaperitem')->middleware('auth'); /* menampilkan data showpenjualansajaperitem pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualansajapercustomer', [PenjualanController::class, 'showpenjualansajapercustomer'])->name('pos01.laporan.penjualan_showpenjualansajapercustomer')->middleware('auth'); /* menampilkan data showpenjualansajapercustomer pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualansajaperfaktur', [PenjualanController::class, 'showpenjualansajaperfaktur'])->name('pos01.laporan.penjualan_showpenjualansajaperfaktur')->middleware('auth'); /* menampilkan data showpenjualansajaperfaktur pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualansajaperjenispembayaran', [PenjualanController::class, 'showpenjualansajaperjenispembayaran'])->name('pos01.laporan.penjualan_showpenjualansajaperjenispembayaran')->middleware('auth'); /* menampilkan data showpenjualansajaperjenispembayaran pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualansajapertanggal', [PenjualanController::class, 'showpenjualansajapertanggal'])->name('pos01.laporan.penjualan_showpenjualansajapertanggal')->middleware('auth'); /* menampilkan data showpenjualansajapertanggal pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualanfifodetail', [PenjualanController::class, 'showpenjualanfifodetail'])->name('pos01.laporan.penjualan_showpenjualanfifodetail')->middleware('auth'); /* menampilkan data penjualanfifodetail pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualanfifoperitem', [PenjualanController::class, 'showpenjualanfifoperitem'])->name('pos01.laporan.penjualan_showpenjualanfifoperitem')->middleware('auth'); /* menampilkan data showpenjualanfifoperitem pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualanfifopercustomer', [PenjualanController::class, 'showpenjualanfifopercustomer'])->name('pos01.laporan.penjualan_showpenjualanfifopercustomer')->middleware('auth'); /* menampilkan data showpenjualanfifopercustomer pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualanfifoperfaktur', [PenjualanController::class, 'showpenjualanfifoperfaktur'])->name('pos01.laporan.penjualan_showpenjualanfifoperfaktur')->middleware('auth'); /* menampilkan data showpenjualanfifoperfaktur pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualanfifoperjenispembayaran', [PenjualanController::class, 'showpenjualanfifoperjenispembayaran'])->name('pos01.laporan.penjualan_showpenjualanfifoperjenispembayaran')->middleware('auth'); /* menampilkan data showpenjualanfifoperjenispembayaran pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualanfifopertanggal', [PenjualanController::class, 'showpenjualanfifopertanggal'])->name('pos01.laporan.penjualan_showpenjualanfifopertanggal')->middleware('auth'); /* menampilkan data showpenjualanfifopertanggal pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualanmovadetail', [PenjualanController::class, 'showpenjualanmovadetail'])->name('pos01.laporan.penjualan_showpenjualanmovadetail')->middleware('auth'); /* menampilkan data penjualanmovadetail pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualanmovaperitem', [PenjualanController::class, 'showpenjualanmovaperitem'])->name('pos01.laporan.penjualan_showpenjualanmovaperitem')->middleware('auth'); /* menampilkan data showpenjualanmovaperitem pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualanmovapercustomer', [PenjualanController::class, 'showpenjualanmovapercustomer'])->name('pos01.laporan.penjualan_showpenjualanmovapercustomer')->middleware('auth'); /* menampilkan data showpenjualanmovapercustomer pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualanmovaperfaktur', [PenjualanController::class, 'showpenjualanmovaperfaktur'])->name('pos01.laporan.penjualan_showpenjualanmovaperfaktur')->middleware('auth'); /* menampilkan data showpenjualanmovaperfaktur pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualanmovaperjenispembayaran', [PenjualanController::class, 'showpenjualanmovaperjenispembayaran'])->name('pos01.laporan.penjualan_showpenjualanmovaperjenispembayaran')->middleware('auth'); /* menampilkan data showpenjualanmovaperjenispembayaran pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualanmovapertanggal', [PenjualanController::class, 'showpenjualanmovapertanggal'])->name('pos01.laporan.penjualan_showpenjualanmovapertanggal')->middleware('auth'); /* menampilkan data showpenjualanmovapertanggal pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualanlifodetail', [PenjualanController::class, 'showpenjualanlifodetail'])->name('pos01.laporan.penjualan_showpenjualanlifodetail')->middleware('auth'); /* menampilkan data penjualanlifodetail pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualanlifoperitem', [PenjualanController::class, 'showpenjualanlifoperitem'])->name('pos01.laporan.penjualan_showpenjualanlifoperitem')->middleware('auth'); /* menampilkan data showpenjualanlifoperitem pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualanlifopercustomer', [PenjualanController::class, 'showpenjualanlifopercustomer'])->name('pos01.laporan.penjualan_showpenjualanlifopercustomer')->middleware('auth'); /* menampilkan data showpenjualanlifopercustomer pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualanlifoperfaktur', [PenjualanController::class, 'showpenjualanlifoperfaktur'])->name('pos01.laporan.penjualan_showpenjualanlifoperfaktur')->middleware('auth'); /* menampilkan data showpenjualanlifoperfaktur pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualanlifoperjenispembayaran', [PenjualanController::class, 'showpenjualanlifoperjenispembayaran'])->name('pos01.laporan.penjualan_showpenjualanlifoperjenispembayaran')->middleware('auth'); /* menampilkan data showpenjualanlifoperjenispembayaran pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualanlifopertanggal', [PenjualanController::class, 'showpenjualanlifopertanggal'])->name('pos01.laporan.penjualan_showpenjualanlifopertanggal')->middleware('auth'); /* menampilkan data showpenjualanlifopertanggal pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualanlaindetail', [PenjualanController::class, 'showpenjualanlaindetail'])->name('pos01.laporan.penjualan_showpenjualanlaindetail')->middleware('auth'); /* menampilkan data penjualanlaindetail pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualanlainperitem', [PenjualanController::class, 'showpenjualanlainperitem'])->name('pos01.laporan.penjualan_showpenjualanlainperitem')->middleware('auth'); /* menampilkan data showpenjualanlainperitem pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualanlainpercustomer', [PenjualanController::class, 'showpenjualanlainpercustomer'])->name('pos01.laporan.penjualan_showpenjualanlainpercustomer')->middleware('auth'); /* menampilkan data showpenjualanlainpercustomer pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualanlainperfaktur', [PenjualanController::class, 'showpenjualanlainperfaktur'])->name('pos01.laporan.penjualan_showpenjualanlainperfaktur')->middleware('auth'); /* menampilkan data showpenjualanlainperfaktur pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualanlainperjenispembayaran', [PenjualanController::class, 'showpenjualanlainperjenispembayaran'])->name('pos01.laporan.penjualan_showpenjualanlainperjenispembayaran')->middleware('auth'); /* menampilkan data showpenjualanlainperjenispembayaran pada datatable javascript */
Route::get('/laporan/penjualanshowpenjualanlainpertanggal', [PenjualanController::class, 'showpenjualanlainpertanggal'])->name('pos01.laporan.penjualan_showpenjualanlainpertanggal')->middleware('auth'); /* menampilkan data showpenjualanlainpertanggal pada datatable javascript */
Route::get('/laporan/penjualanshowsupplier', [PenjualanController::class, 'showsupplier'])->name('pos01.laporan.penjualan_showsupplier')->middleware('auth'); /* menampilkan data supplier yg gk ada di penjualan pada datatable javascript */
Route::get('/laporan/penjualanlistcustomer', [PenjualanController::class, 'listcustomer'])->name('pos01.laporan.penjualan_listcustomer')->middleware('auth'); /* menampilkan list customer/anggota */
Route::get('/laporan/penjualanlistruang', [PenjualanController::class, 'listruang'])->name('pos01.laporan.penjualan_listruang')->middleware('auth'); /* menampilkan list ruang */
Route::get('/laporan/penjualanlistjenispembayaran', [PenjualanController::class, 'listjenispembayaran'])->name('pos01.laporan.penjualan_listjenispembayaran')->middleware('auth'); /* menampilkan list jenispembayaran */
Route::get('/laporan/penjualanlistoperator', [PenjualanController::class, 'listoperator'])->name('pos01.laporan.penjualan_listoperator')->middleware('auth'); /* menampilkan list users */
Route::post('/laporan/penjualancreate', [PenjualanController::class, 'create'])->name('pos01.laporan.penjualan_create')->middleware('auth'); /* menambah penjualan */
Route::post('/laporan/penjualankirimsyarat', [PenjualanController::class, 'kirimsyarat'])->name('pos01.laporan.penjualan_kirimsyarat')->middleware('auth'); /* kirimsyarat */
Route::post('/laporan/penjualancekcustomer', [PenjualanController::class, 'cekcustomer'])->name('pos01.laporan.penjualan_cekcustomer')->middleware('auth'); /* cekcustomer */
Route::get('/laporan/penjualanedit/{id}', [PenjualanController::class, 'edit'])->name('pos01.laporan.penjualan_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::get('/laporan/penjualandestroy/{id}', [PenjualanController::class, 'destroy'])->name('pos01.laporan.penjualan_destroy')->middleware('auth'); /* hapus data penjualan */

/* laporan - hutangpiutang */
Route::get('/laporan/hutangpiutang', [HutangpiutangController::class, 'index'])->name('pos01.laporan.hutangpiutang.index')->middleware('auth'); /* halaman hutangpiutang */
Route::get('/laporan/hutangpiutangshowhutangpiutangbelumcustomer', [HutangpiutangController::class, 'showhutangpiutangbelumcustomer'])->name('pos01.laporan.hutangpiutang_showhutangpiutangbelumcustomer')->middleware('auth'); /* menampilkan data hutangpiutangbelumcustomer pada datatable javascript */
Route::get('/laporan/hutangpiutangshowhutangpiutangbelumsupplier', [HutangpiutangController::class, 'showhutangpiutangbelumsupplier'])->name('pos01.laporan.hutangpiutang_showhutangpiutangbelumsupplier')->middleware('auth'); /* menampilkan data hutangpiutangbelumsupplier pada datatable javascript */
Route::get('/laporan/hutangpiutangshowhutangpiutangsudahcustomer', [HutangpiutangController::class, 'showhutangpiutangsudahcustomer'])->name('pos01.laporan.hutangpiutang_showhutangpiutangsudahcustomer')->middleware('auth'); /* menampilkan data hutangpiutangsudahcustomer pada datatable javascript */
Route::get('/laporan/hutangpiutangshowhutangpiutangsudahsupplier', [HutangpiutangController::class, 'showhutangpiutangsudahsupplier'])->name('pos01.laporan.hutangpiutang_showhutangpiutangsudahsupplier')->middleware('auth'); /* menampilkan data hutangpiutangsudahsupplier pada datatable javascript */
Route::get('/laporan/hutangpiutangshowbarang', [HutangpiutangController::class, 'showbarang'])->name('pos01.laporan.hutangpiutang_showbarang')->middleware('auth'); /* menampilkan data barang yg gk ada di hutangpiutang pada datatable javascript */
Route::get('/laporan/hutangpiutanglistbarang', [HutangpiutangController::class, 'listbarang'])->name('pos01.laporan.hutangpiutang_listbarang')->middleware('auth'); /* menampilkan list barang */
Route::get('/laporan/hutangpiutanglistruang', [HutangpiutangController::class, 'listruang'])->name('pos01.laporan.hutangpiutang_listruang')->middleware('auth'); /* menampilkan list ruang */
Route::post('/laporan/hutangpiutangcreate', [HutangpiutangController::class, 'create'])->name('pos01.laporan.hutangpiutang_create')->middleware('auth'); /* menambah hutangpiutang */
Route::post('/laporan/hutangpiutangkirimsyarat', [HutangpiutangController::class, 'kirimsyarat'])->name('pos01.laporan.hutangpiutang_kirimsyarat')->middleware('auth'); /* kirimsyarat */
Route::get('/laporan/hutangpiutangedit/{id}', [HutangpiutangController::class, 'edit'])->name('pos01.laporan.hutangpiutang_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::get('/laporan/hutangpiutangdestroy/{id}', [HutangpiutangController::class, 'destroy'])->name('pos01.laporan.hutangpiutang_destroy')->middleware('auth'); /* hapus data hutangpiutang */

/* laporan - labarugi */
Route::get('/laporan/labarugi', [LabarugiController::class, 'index'])->name('pos01.laporan.labarugi.index')->middleware('auth'); /* halaman labarugi */
Route::get('/laporan/labarugishowlabarugisaja', [LabarugiController::class, 'showlabarugisaja'])->name('pos01.laporan.labarugi_showlabarugisaja')->middleware('auth'); /* menampilkan showlabarugisaja pada datatable javascript */
Route::get('/laporan/labarugishowlabarugififo', [LabarugiController::class, 'showlabarugififo'])->name('pos01.laporan.labarugi_showlabarugififo')->middleware('auth'); /* menampilkan data labarugififo pada datatable javascript */
Route::get('/laporan/labarugishowlabarugimova', [LabarugiController::class, 'showlabarugimova'])->name('pos01.laporan.labarugi_showlabarugimova')->middleware('auth'); /* menampilkan data labarugimova pada datatable javascript */
Route::get('/laporan/labarugishowlabarugilifo', [LabarugiController::class, 'showlabarugilifo'])->name('pos01.laporan.labarugi_showlabarugilifo')->middleware('auth'); /* menampilkan data labarugilifo pada datatable javascript */
Route::get('/laporan/labarugishowlabarugijasa', [LabarugiController::class, 'showlabarugijasa'])->name('pos01.laporan.labarugi_showlabarugijasa')->middleware('auth'); /* menampilkan data showlabarugijasa pada datatable javascript */
Route::get('/laporan/labarugilistruang', [LabarugiController::class, 'listruang'])->name('pos01.laporan.labarugi_listruang')->middleware('auth'); /* menampilkan list ruang */
Route::post('/laporan/labarugikirimsyarat', [LabarugiController::class, 'kirimsyarat'])->name('pos01.laporan.labarugi_kirimsyarat')->middleware('auth'); /* kirimsyarat */

/* laporan - pendapatanlain2 */
Route::get('/laporan/pendapatanlain2', [Pendapatanlain2Controller::class, 'index'])->name('pos01.laporan.pendapatanlain2.index')->middleware('auth'); /* halaman pendapatanlain2 */
Route::get('/laporan/pendapatanlain2show', [Pendapatanlain2Controller::class, 'show'])->name('pos01.laporan.pendapatanlain2_show')->middleware('auth'); /* menampilkan data pendapatanlain2 pada datatable javascript */
Route::get('/laporan/pendapatanlain2showbarang', [Pendapatanlain2Controller::class, 'showbarang'])->name('pos01.laporan.pendapatanlain2_showbarang')->middleware('auth'); /* menampilkan data barang yg gk ada di pendapatanlain2 pada datatable javascript */
Route::get('/laporan/pendapatanlain2listkategoribiaya', [Pendapatanlain2Controller::class, 'listkategoribiaya'])->name('pos01.laporan.pendapatanlain2_listkategoribiaya')->middleware('auth'); /* menampilkan listkategoribiaya */
Route::get('/laporan/pendapatanlain2listkategoribiayax', [Pendapatanlain2Controller::class, 'listkategoribiayax'])->name('pos01.laporan.pendapatanlain2_listkategoribiayax')->middleware('auth'); /* menampilkan listkategoribiaya */
Route::get('/laporan/pendapatanlain2listruang', [Pendapatanlain2Controller::class, 'listruang'])->name('pos01.laporan.pendapatanlain2_listruang')->middleware('auth'); /* menampilkan list ruang */
Route::get('/laporan/pendapatanlain2listsatuan', [Pendapatanlain2Controller::class, 'listsatuan'])->name('pos01.laporan.pendapatanlain2_listsatuan')->middleware('auth'); /* menampilkan listsatuan */
Route::post('/laporan/pendapatanlain2create', [Pendapatanlain2Controller::class, 'create'])->name('pos01.laporan.pendapatanlain2_create')->middleware('auth'); /* menambah pendapatanlain2 */
Route::post('/laporan/pendapatanlain2kirimsyarat', [Pendapatanlain2Controller::class, 'kirimsyarat'])->name('pos01.laporan.pendapatanlain2_kirimsyarat')->middleware('auth'); /* kirimsyarat */
Route::get('/laporan/pendapatanlain2edit/{id}', [Pendapatanlain2Controller::class, 'edit'])->name('pos01.laporan.pendapatanlain2_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::get('/laporan/pendapatanlain2destroy/{id}', [Pendapatanlain2Controller::class, 'destroy'])->name('pos01.laporan.pendapatanlain2_destroy')->middleware('auth'); /* hapus data pendapatanlain2 */

/* laporan - biayabiaya2 */
Route::get('/laporan/biayabiaya2', [Biayabiaya2Controller::class, 'index'])->name('pos01.laporan.biayabiaya2.index')->middleware('auth'); /* halaman biayabiaya2 */
Route::get('/laporan/biayabiaya2show', [Biayabiaya2Controller::class, 'show'])->name('pos01.laporan.biayabiaya2_show')->middleware('auth'); /* menampilkan data biayabiaya2 pada datatable javascript */
Route::get('/laporan/biayabiaya2showbarang', [Biayabiaya2Controller::class, 'showbarang'])->name('pos01.laporan.biayabiaya2_showbarang')->middleware('auth'); /* menampilkan data barang yg gk ada di biayabiaya2 pada datatable javascript */
Route::get('/laporan/biayabiaya2listkategoribiaya', [Biayabiaya2Controller::class, 'listkategoribiaya'])->name('pos01.laporan.biayabiaya2_listkategoribiaya')->middleware('auth'); /* menampilkan listkategoribiaya */
Route::get('/laporan/biayabiaya2listkategoribiayax', [Biayabiaya2Controller::class, 'listkategoribiayax'])->name('pos01.laporan.biayabiaya2_listkategoribiayax')->middleware('auth'); /* menampilkan listkategoribiaya */
Route::get('/laporan/biayabiaya2listruang', [Biayabiaya2Controller::class, 'listruang'])->name('pos01.laporan.biayabiaya2_listruang')->middleware('auth'); /* menampilkan list ruang */
Route::get('/laporan/biayabiaya2listjenisbiaya', [Biayabiaya2Controller::class, 'listjenisbiaya'])->name('pos01.laporan.biayabiaya2_listjenisbiaya')->middleware('auth'); /* menampilkan listjenisbiaya */
Route::post('/laporan/biayabiaya2create', [Biayabiaya2Controller::class, 'create'])->name('pos01.laporan.biayabiaya2_create')->middleware('auth'); /* menambah biayabiaya2 */
Route::post('/laporan/biayabiaya2kirimsyarat', [Biayabiaya2Controller::class, 'kirimsyarat'])->name('pos01.laporan.biayabiaya2_kirimsyarat')->middleware('auth'); /* kirimsyarat */
Route::get('/laporan/biayabiaya2edit/{id}', [Biayabiaya2Controller::class, 'edit'])->name('pos01.laporan.biayabiaya2_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::get('/laporan/biayabiaya2destroy/{id}', [Biayabiaya2Controller::class, 'destroy'])->name('pos01.laporan.biayabiaya2_destroy')->middleware('auth'); /* hapus data biayabiaya2 */

/* pengaturan - parameter */
Route::get('/pengaturan/parameter', [ParameterController::class, 'index'])->name('pos01.pengaturan.parameter.index')->middleware('auth'); /* halaman parameter */
Route::get('/pengaturan/parametershow', [ParameterController::class, 'show'])->name('pos01.pengaturan.parameter_show')->middleware('auth'); /* menampilkan data parameter pada datatable javascript */
Route::post('/pengaturan/parametercreate', [ParameterController::class, 'create'])->name('pos01.pengaturan.parameter_create')->middleware('auth'); /* menambah parameter */
Route::get('/pengaturan/parameteredit/{id}', [ParameterController::class, 'edit'])->name('pos01.pengaturan.parameter_edit')->middleware('auth'); /* menampilkan data yang akan dirubah */
Route::get('/pengaturan/parameterdestroy/{id}', [ParameterController::class, 'destroy'])->name('pos01.pengaturan.parameter_destroy')->middleware('auth'); /* hapus data parameter */



});