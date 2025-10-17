<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\InstansiController;
use App\Http\Controllers\MenuutamaController;
use App\Http\Controllers\MenusubController;

Route::get('/', [FrontController::class, 'loginform'])->name('front.index')->middleware('guest');
Route::get('/login/', [FrontController::class, 'loginform'])->name('front.admin_loginform')->middleware('guest');
Route::get('/register/', [FrontController::class, 'registerform'])->name('front.admin_registerform')->middleware('guest');
Route::post('/register/', [FrontController::class, 'create'])->name('front.admin_create')->middleware('guest');
Route::get('/registerlistaplikasi/', [FrontController::class, 'listaplikasi'])->name('front.admin_registerlistaplikasi')->middleware('guest');
Route::post('/login/', [FrontController::class, 'authenticate'])->name('front.admin_login')->middleware('guest');
Route::post('/logout/', [FrontController::class, 'logout'])->name('front.admin_logout');
Route::get('/logout/', [FrontController::class, 'logout'])->name('login');

/* admin */
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index')->middleware('auth');   /* halaman beranda */

/* halaman admin-instansi */
Route::get('/admin/instansi', [InstansiController::class, 'index'])->name('admin.instansi_index')->middleware('auth'); /* halaman menu utama */
Route::get('/admin/instansishow', [InstansiController::class, 'show'])->name('admin.instansi_show')->middleware('auth'); /* menampilkan data menu utama pada datatable javascript */
Route::post('/admin/instansiupdate', [InstansiController::class, 'update'])->name('admin.instansi_update')->middleware('auth'); /* update data instansi */

/* halaman admin-user */
Route::get('/admin/users', [UsersController::class, 'index'])->name('admin.users_index')->middleware('auth'); /* halaman menu utama */
Route::get('/admin/usersshow', [UsersController::class, 'show'])->name('admin.users_show')->middleware('auth'); /* menampilkan data menu utama pada datatable javascript */
Route::post('/admin/userscreate', [UsersController::class, 'create'])->name('admin.users_create')->middleware('auth'); /* menambah data user */
Route::get('/admin/usersedit/{id}', [UsersController::class, 'edit'])->middleware('auth'); /* menampilkan data menu utama yang akan dirubah */
Route::get('/admin/usersdelete/{id}', [UsersController::class, 'destroy'])->middleware('auth'); /* hapus data menu utama */
Route::get('/admin/userslistaplikasi', [UsersController::class, 'listaplikasi'])->name('admin.users_listaplikasi')->middleware('auth'); /* list aplikasi */

/* halaman admin-menuutama */
Route::get('/admin/menuutama', [MenuutamaController::class, 'index'])->name('admin.menuutama_index')->middleware('auth'); /* halaman menu utama */
Route::get('/admin/menuutamashow', [MenuutamaController::class, 'show'])->name('admin.menuutama_show')->middleware('auth'); /* menampilkan data menu utama pada datatable javascript */
Route::post('/admin/menuutamacreate', [MenuutamaController::class, 'create'])->name('admin.menuutama_create')->middleware('auth'); /* menambah data menuutama */
Route::get('/admin/menuutamaedit/{id}', [MenuutamaController::class, 'edit'])->middleware('auth'); /* menampilkan data menu utama yang akan dirubah */
Route::get('/admin/menuutamadelete/{id}', [MenuutamaController::class, 'destroy'])->middleware('auth'); /* hapus data menu utama */
            
/* halaman admin-menusub */
Route::get('/admin/menusub', [MenusubController::class, 'index'])->name('admin.menusub_index')->middleware('auth'); /* halaman menu sub */
Route::get('/admin/menusubshow', [MenusubController::class, 'show'])->name('admin.menusub_show')->middleware('auth'); /* menampilkan data menu sub pada datatable javascript */
Route::post('/admin/menusubcreate', [MenusubController::class, 'create'])->name('admin.menusub_create')->middleware('auth'); /* menambah data menusub */
Route::get('/admin/menusubedit/{id}', [MenusubController::class, 'edit'])->middleware('auth'); /* menampilkan data menu sub yang akan dirubah */
Route::get('/admin/menusubdelete/{id}', [MenusubController::class, 'destroy'])->middleware('auth'); /* hapus data menu sub */
Route::get('/admin/menusublistmenuutama', [MenusubController::class, 'listmenuutama'])->name('admin.menusub_listmenuutama')->middleware('auth'); /* list menuutama */
Route::get('/admin/menusublistaplikasi', [MenusubController::class, 'listaplikasi'])->name('admin.menusub_listaplikasi')->middleware('auth'); /* list aplikasi */
Route::post('/admin/menusubkirimsyarat', [MenusubController::class, 'kirimsyarat'])->name('admin.menusub_kirimsyarat')->middleware('auth'); /* kirim syarat */


/* khusus list */
Route::get('/admin/listpropinsi10', [AdminController::class, 'listpropinsi10'])->name('admin.listpropinsi10')->middleware('auth'); /* list propinsi ori */
Route::get('/admin/listpropinsi11', [AdminController::class, 'listpropinsi11'])->name('admin.listpropinsi11')->middleware('auth'); /* list propinsi sort asc */
Route::get('/admin/listpropinsi12', [AdminController::class, 'listpropinsi12'])->name('admin.listpropinsi12')->middleware('auth'); /* list propinsi sort desc */

Route::get('/admin/listkabupatenx20', [AdminController::class, 'listkabupatenx20'])->name('admin.listkabupatenx20')->middleware('auth'); /* list kabupaten ori */
Route::get('/admin/listkabupatenx21', [AdminController::class, 'listkabupatenx21'])->name('admin.listkabupatenx21')->middleware('auth'); /* list kabupaten sort asc */
Route::get('/admin/listkabupatenx22', [AdminController::class, 'listkabupatenx22'])->name('admin.listkabupatenx22')->middleware('auth'); /* list kabupaten sort desc */
Route::get('/admin/listkabupaten20', [AdminController::class, 'listkabupaten20'])->name('admin.listkabupaten20')->middleware('auth'); /* list kabupaten ori */
Route::get('/admin/listkabupaten21', [AdminController::class, 'listkabupaten21'])->name('admin.listkabupaten21')->middleware('auth'); /* list kabupaten sort asc */
Route::get('/admin/listkabupaten22', [AdminController::class, 'listkabupaten22'])->name('admin.listkabupaten22')->middleware('auth'); /* list kabupaten sort desc */

Route::get('/admin/listkecamatanx20', [AdminController::class, 'listkecamatanx20'])->name('admin.listkecamatanx20')->middleware('auth'); /* list kecamatan ori */
Route::get('/admin/listkecamatanx21', [AdminController::class, 'listkecamatanx21'])->name('admin.listkecamatanx21')->middleware('auth'); /* list kecamatan sort asc */
Route::get('/admin/listkecamatanx22', [AdminController::class, 'listkecamatanx22'])->name('admin.listkecamatanx22')->middleware('auth'); /* list kecamatan sort desc */
Route::get('/admin/listkecamatan20', [AdminController::class, 'listkecamatan20'])->name('admin.listkecamatan20')->middleware('auth'); /* list kecamatan ori */
Route::get('/admin/listkecamatan21', [AdminController::class, 'listkecamatan21'])->name('admin.listkecamatan21')->middleware('auth'); /* list kecamatan sort asc */
Route::get('/admin/listkecamatan22', [AdminController::class, 'listkecamatan22'])->name('admin.listkecamatan22')->middleware('auth'); /* list kecamatan sort desc */

Route::get('/admin/listdesa109', [AdminController::class, 'listdesa109'])->name('admin.listdesa109')->middleware('auth'); /* list desa ori */

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
