<?php

namespace Modules\Pos01\Http\Controllers\laporan;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Http\Request;

use DateTime;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Aplikasi;
use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Storage;
use App\Models\Menusub;
use Modules\Pos01\Models\Anggota;
use Modules\Pos01\Models\Barang;
use Modules\Pos01\Models\Barangruang;
use Modules\Pos01\Models\Hutang;
use Modules\Pos01\Models\Jenispembayaran;
use Modules\Pos01\Models\Ruang;
use Modules\Pos01\Models\Satuan;
use Modules\Pos01\Models\Stok;
use Modules\Pos01\Models\Stokfifo;
use Modules\Pos01\Models\Stoklifo;
use Modules\Pos01\Models\Stokmamin;
use Modules\Pos01\Models\Stokmova;
use Modules\Pos01\Models\Supplier;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\NumberFormat;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // $product = new Product;
        // $product->setConnection('mysql_second');
        // $something = $product->find(1);
        // return $something;

        $meminstansi = session('memnamasingkat');
        $remark = 'Halaman ini digunakan untuk menampilkan <b>Penjualan</b>.';
        $page = 'pos01::laporan.penjualan';
        $link = '/pos01/laporan/penjualan';
        $subtitle = 'Laporan';
        $caption = 'Penjualan';
        $jmlhal = 2;
       
        $menu=Menusub::where('link','=',$link)
            ->with(['menuutama','aplikasi'])
            ->get();
        return view($page, [
            'title' => $meminstansi . ' | ' . $subtitle . ' | ' . $caption,
            'subtitle' => $subtitle,
            'caption' => $caption,
            'link' => $link,
            'remark' => $remark,
            'jmlhal' => $jmlhal,
            'tabel' => Barangruang::SimplePaginate($jmlhal)->withQueryString(),
            'aplikasi' => Aplikasi::get(),
            'menu' => $menu,
        ]);
        
    }
 
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
   
    public function create(Request $request)
    {
        $id = $request['id1'];
        
        $validatedData = $request->validate([
            'idbarang1' => 'required',
            'idruang1' => 'required',
        ]);
          
        //untuk input tabel yang asli
        $idruangx = $request['idruang1'];
        $tampil = Ruang::where('id','=',$idruangx)->get();
        foreach ($tampil as $baris) {
            $idseksix = $baris->idseksi;
        }

        $data = [            
            'idbarang' => $validatedData['idbarang1'],
            'idseksi' => $idseksix,
            'idruang' => $validatedData['idruang1'],
            'stokmin' => $request['stokmin1'],
            'stokmax' => $request['stokmax1'],
            'aktif' => $request['aktif1'],
        ];

        if ($id == '0') {
            Barangruang::create($data);
        } else {
            Barangruang::where('id', '=', $id)->update($data);
        }
        return json_encode(array('data' => $data));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    
    public function showpenjualansajadetail()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }

        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stok::select('*')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                 ->orWhere('status','=','returbeli');
                })
            ->with('barang','seksi','ruang','jenispembayaran','anggota','supplier','users')
            // ->orderBy('tglstatus','asc')
            // ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('users', function ($row) {
                return $row->iduser ? $row->users->name : '';
            })
            ->addColumn('barang', function ($row) {
                return $row->idbarang ? $row->barang->nabara : '';
            })
            ->addColumn('kode', function ($row) {
                return $row->idbarang ? $row->barang->kode : '';
            })
            ->addColumn('barcode', function ($row) {
                return $row->idbarang ? $row->barang->barcode : '';
            })
            ->addColumn('nabara', function ($row) {
                return $row->idbarang ? $row->barang->nabara : '';
            })
            ->addColumn('satuan', function ($row) {
                return $row->idbarang ? $row->barang->satuan->kode : '';
            })
            ->addColumn('jenispembayaran', function ($row) {
                return $row->idjenispembayaran ? $row->jenispembayaran->jenispembayaran : '';
            })
            ->addColumn('customer', function ($row) {
                return $row->idanggota ? $row->anggota->nama : '';
            })
            ->addColumn('ruang', function ($row) {
                return $row->idruang ? $row->ruang->ruang : '';
            })
            
            ->addColumn('hbsawal', function ($row) {
                return $row->hbsawal ? Number_Format($row->hbsawal,0) : '';
            })
            ->addColumn('hppawal', function ($row) {
                return $row->hppawal ? Number_Format($row->hppawal,0) : '';
            })

            ->addColumn('masuk', function ($row) {
                return $row->masuk ? Number_Format($row->masuk,0) : '';
            })
            ->addColumn('hbsmasuk', function ($row) {
                return $row->hbsmasuk ? Number_Format($row->hbsmasuk,0) : '';
            })
            ->addColumn('hppmasuk', function ($row) {
                return $row->hppmasuk ? Number_Format($row->hppmasuk,0) : '';
            })

            ->addColumn('keluar', function ($row) {
                return $row->keluar ? Number_Format($row->keluar,0) : '';
            })
            ->addColumn('hbskeluar', function ($row) {
                return $row->hbskeluar ? Number_Format($row->hbskeluar,0) : '';
            })
            ->addColumn('hppkeluar', function ($row) {
                return $row->hppkeluar ? Number_Format($row->hppkeluar,0) : '';
            })

            ->addColumn('akhir', function ($row) {
                return $row->akhir ? Number_Format($row->akhir,0) : '';
            })
            ->addColumn('hbsakhir', function ($row) {
                return $row->hbsakhir ? Number_Format($row->hbsakhir,0) : '';
            })
            ->addColumn('hppakhir', function ($row) {
                return $row->hppakhir ? Number_Format($row->hppakhir,0) : '';
            }) 
            ->addColumn('hjs', function ($row) {
                return $row->hjs ? Number_Format($row->hjs,0) : 0;
            }) 
            ->addColumn('hppj', function ($row) {
                return $row->hppj ? Number_Format($row->hppj,0) : 0;
            }) 
            ->addColumn('ppnkeluar', function ($row) {
                return $row->ppnkeluar ? Number_Format($row->ppnkeluar,0) : 0;
            }) 
            ->addColumn('diskonkeluar', function ($row) {
                return $row->diskonkeluar ? Number_Format($row->diskonkeluar,0) : 0;
            }) 
            ->addColumn('totalhj', function ($row) {
                $hj = $row->hppj;
                $ppn = $row->ppnkeluar;
                $diskon = $row->diskonkeluar;
                $totalhj = $hj + $ppn - $diskon;

                return $totalhj ? Number_Format($totalhj,0) : 0;
            }) 
            ->addColumn('laba', function ($row) {
                $hb = $row->hppkeluar;
                $hj = $row->hppj;
                $ppn = $row->ppnkeluar;
                $diskon = $row->diskonkeluar;
                $totalhj = $hj + $ppn - $diskon;
                $laba = $totalhj - $hb;
                return $laba ? Number_Format($laba,0) : 0;
            }) 

            ->addColumn('waktu', function ($row) {
                $x = date_create($row->created_at);
                return date_format($x,'H:i:s');
            })   
            ->addColumn('tanggal', function ($row) {
                $x = date_create($row->created_at);
                return date_format($x,'Y-m-d');
            })   
            
            ->rawColumns([
                'totalhj',
                'laba',
                ])
            

            ->make(true);

            return $data;

    }

    public function showpenjualansajaperitem()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }
        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stok::select('idbarang')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                 ->orWhere('status','=','returbeli');
                })
            ->with('barang','seksi','ruang','jenispembayaran','anggota','supplier')
            ->groupBy('idbarang')
            // ->orderBy('tglstatus','asc')
            // ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('barang', function ($row) {
                return $row->idbarang ? $row->barang->nabara : '';
            })
            ->addColumn('kode', function ($row) {
                return $row->idbarang ? $row->barang->kode : '';
            })
            ->addColumn('barcode', function ($row) {
                return $row->idbarang ? $row->barang->barcode : '';
            })
            ->addColumn('nabara', function ($row) {
                return $row->idbarang ? $row->barang->nabara : '';
            })
            ->addColumn('satuan', function ($row) {
                return $row->idbarang ? $row->barang->satuan->kode : '';
            })
            ->addColumn('qty', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');

                return $qty;
            })
            ->addColumn('hbs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');
                
                $totalhb = Stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb/$qty,0);
            })
            ->addColumn('totalhb', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb,0);
            })
            ->addColumn('hjs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hjs = Stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hjs');

                return number_format($hjs,0);
            })
            ->addColumn('subtotalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hppj = Stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');

                return number_format($hppj,0);
            })
            ->addColumn('ppnjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $ppnjual = Stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');

                return number_format($ppnjual,0);
            })
            ->addColumn('diskonjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $diskonjual = Stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');

                return number_format($diskonjual,0);
            })
            ->addColumn('totalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj,0);
            })
            ->addColumn('laba', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');
                $subtotalhj = Stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj - $totalhb,0);
            })
            ->addColumn('jmlrecord', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }
                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $jmlrecord = Stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->count('idbarang');
                
                return $jmlrecord;
            })
            
            ->rawColumns([
                'kode',
                'barcode',
                'nabara',
                'satuan',
                'jmlrecord',
                'qty',
                'hbs',
                'totalhb',
                'hjs',
                'subtotalhj',
                'ppnjual',
                'diskonjual',
                'totalhj',
                'laba',
                ])

            ->make(true);

            return $data;

    }

    public function showpenjualansajapercustomer()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }
        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stok::select('idanggota')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                 ->orWhere('status','=','returbeli');
                })
            ->with('barang','seksi','ruang','jenispembayaran','anggota','supplier')
            ->groupBy('idanggota')
            // ->orderBy('tglstatus','asc')
            // ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('nama', function ($row) {
                return $row->idanggota ? $row->anggota->nama : '';
            })
            ->addColumn('nia', function ($row) {
                return $row->idanggota ? $row->anggota->nia : '';
            })
            ->addColumn('lembaga', function ($row) {
                return $row->anggota ? $row->anggota->lembaga->lembaga : '';
            })
                        
            ->addColumn('qty', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }
                
                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stok::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');

                return $qty;
            })
            ->addColumn('hbs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stok::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)                    
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');
                
                $totalhb = Stok::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb/$qty,0);
            })
            ->addColumn('totalhb', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stok::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb,0);
            })
            ->addColumn('hjs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hjs = Stok::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hjs');

                return number_format($hjs,0);
            })
            ->addColumn('subtotalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hppj = Stok::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');

                return number_format($hppj,0);
            })
            ->addColumn('ppnjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $ppnjual = Stok::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');

                return number_format($ppnjual,0);
            })
            ->addColumn('diskonjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $diskonjual = Stok::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');

                return number_format($diskonjual,0);
            })
            ->addColumn('totalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stok::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stok::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stok::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj,0);
            })
            ->addColumn('laba', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stok::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');
                $subtotalhj = Stok::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stok::where('idanggota','=',$row->idsuppidanggotalier)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stok::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj - $totalhb,0);
            })
            ->addColumn('jmlrecord', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }
                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $jmlrecord = Stok::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->count('idanggota');
                
                return $jmlrecord;
            })
            
            ->rawColumns([
                'nia',
                'nama',
                'lembaga',
                'jmlrecord',
                'qty',
                'hbs',
                'totalhb',
                'hjs',
                'subtotalhj',
                'ppnjual',
                'diskonjual',
                'totalhj',
                'laba',
                ])

            ->make(true);

            return $data;

    }

    public function showpenjualansajaperfaktur()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }
        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stok::select('nomorstatus')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                 ->orWhere('status','=','returbeli');
                })
            ->with('barang','seksi','ruang','jenispembayaran','anggota','supplier')
            ->groupBy('nomorstatus')
            // ->orderBy('tglstatus','asc')
            // ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
                        
            ->addColumn('tglstatus', function ($row) {
                $nomorstatus = $row->nomorstatus;
                $data = explode("." , $nomorstatus);
                $th = substr($data['2'], 0, 4);
                $bl = substr($data['2'], 4, 2);
                $hr = substr($data['2'], 6, 2);
                return $th . '-' . $bl . '-'. $hr;
            })

            ->addColumn('qty', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stok::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');

                return $qty;
            })
            ->addColumn('hbs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stok::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');
                
                $totalhb = Stok::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb/$qty,0);
            })
            ->addColumn('totalhb', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stok::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb,0);
            })
            ->addColumn('hjs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hjs = Stok::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hjs');

                return number_format($hjs,0);
            })
            ->addColumn('subtotalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hppj = Stok::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');

                return number_format($hppj,0);
            })
            ->addColumn('ppnjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $ppnjual = Stok::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');

                return number_format($ppnjual,0);
            })
            ->addColumn('diskonjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $diskonjual = Stok::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');

                return number_format($diskonjual,0);
            })
            ->addColumn('totalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stok::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stok::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stok::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj,0);
            })
            ->addColumn('laba', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stok::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');
                $subtotalhj = Stok::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stok::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stok::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj - $totalhb,0);
            })
            ->addColumn('jmlrecord', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }
                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $jmlrecord = Stok::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->count('idsupplier');
                
                return $jmlrecord;
            })
            
            ->rawColumns([
                'kode',
                'jmlrecord',
                'tglstatus',
                'qty',
                'hbs',
                'totalhb',
                'hjs',
                'subtotalhj',
                'ppnjual',
                'diskonjual',
                'totalhj',
                'laba',
                ])

            ->make(true);

            return $data;

    }
   
    public function showpenjualansajaperjenispembayaran()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }
        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stok::select('idjenispembayaran')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                 ->orWhere('status','=','returbeli');
                })
            ->with('barang','seksi','ruang','jenispembayaran','anggota','supplier')
            ->groupBy('idjenispembayaran')
            // ->orderBy('tglstatus','asc')
            // ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
                        
            ->addColumn('kode', function ($row) {                
                return ($row->idjenispembayaran ? $row->jenispembayaran->kode : '' );
            })
            ->addColumn('jenispembayaran', function ($row) {                
                return ($row->idjenispembayaran ? $row->jenispembayaran->jenispembayaran : '' );
            })

            ->addColumn('qty', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stok::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');

                return $qty;
            })
            ->addColumn('hbs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stok::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');
                
                $totalhb = Stok::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb/$qty,0);
            })
            ->addColumn('totalhb', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stok::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb,0);
            })
            ->addColumn('hjs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hjs = Stok::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hjs');

                return number_format($hjs,0);
            })
            ->addColumn('subtotalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hppj = Stok::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');

                return number_format($hppj,0);
            })
            ->addColumn('ppnjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $ppnjual = Stok::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');

                return number_format($ppnjual,0);
            })
            ->addColumn('diskonjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $diskonjual = Stok::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');

                return number_format($diskonjual,0);
            })
            ->addColumn('totalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stok::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stok::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stok::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj,0);
            })
            ->addColumn('laba', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stok::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');
                $subtotalhj = Stok::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stok::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stok::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj - $totalhb,0);
            })
            ->addColumn('jmlrecord', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }
                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $jmlrecord = Stok::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->count('idjenispembayaran');
                
                return $jmlrecord;
            })
            
            ->rawColumns([
                'kode',
                'jmlrecord',
                'jenispembayaran',
                'qty',
                'hbs',
                'totalhb',
                'hjs',
                'subtotalhj',
                'ppnjual',
                'diskonjual',
                'totalhj',
                'laba',
                ])

            ->make(true);

            return $data;

    }

    public function showpenjualansajapertanggal()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }

        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stok::select('tglstatus')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                ->orWhere('status','=','returbeli');
                })
            ->with('barang','seksi','ruang','jenispembayaran','anggota','supplier')
            ->groupBy('tglstatus')
            // ->orderBy('tglstatus','asc')
            // ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
                        
            ->addColumn('tglstatus', function ($row) {
                return ($row->tglstatus);
            })

            ->addColumn('qty', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stok::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');

                return $qty;
            })
            ->addColumn('hbs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stok::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');
                
                $totalhb = Stok::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb/$qty,0);
            })
            ->addColumn('totalhb', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stok::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb,0);
            })
            ->addColumn('hjs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hjs = Stok::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hjs');

                return number_format($hjs,0);
            })
            ->addColumn('subtotalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hppj = Stok::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');

                return number_format($hppj,0);
            })
            ->addColumn('ppnjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $ppnjual = Stok::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');

                return number_format($ppnjual,0);
            })
            ->addColumn('diskonjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $diskonjual = Stok::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');

                return number_format($diskonjual,0);
            })
            ->addColumn('totalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stok::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stok::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stok::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj,0);
            })
            ->addColumn('laba', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stok::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');
                $subtotalhj = Stok::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stok::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stok::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj - $totalhb,0);
            })
            ->addColumn('jmlrecord', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }
                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $jmlrecord = Stok::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->count('idsupplier');
                
                return $jmlrecord;
            })

            
            ->rawColumns([
                'kode',
                'jmlrecord',
                'tglstatus',
                'qty',
                'hbs',
                'totalhb',
                'hjs',
                'subtotalhj',
                'ppnjual',
                'diskonjual',
                'totalhj',
                'laba',
                ])

            ->make(true);

            return $data;

    }

    public function showpenjualanfifodetail()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }

        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stokfifo::where('kodepokok','=','2')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                 ->orWhere('status','=','returbeli');
                })
            ->with('barang','seksi','ruang','jenispembayaran','anggota','supplier')
            // ->orderBy('tglstatus','asc')
            // ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('users', function ($row) {
                $iduser = $row->iduser;
                $tampil = Users::select('name')
                    ->where('id','=',$row->iduser)
                    ->get();
                foreach ($tampil as $baris) {
                    $users = $baris->name; 
                }
                return $users;
            })

            ->addColumn('barang', function ($row) {
                return $row->idbarang ? $row->barang->nabara : '';
            })
            ->addColumn('kode', function ($row) {
                return $row->idbarang ? $row->barang->kode : '';
            })
            ->addColumn('barcode', function ($row) {
                return $row->idbarang ? $row->barang->barcode : '';
            })
            ->addColumn('nabara', function ($row) {
                return $row->idbarang ? $row->barang->nabara : '';
            })
            ->addColumn('satuan', function ($row) {
                return $row->idbarang ? $row->barang->satuan->kode : '';
            })
            ->addColumn('jenispembayaran', function ($row) {
                return $row->idjenispembayaran ? $row->jenispembayaran->jenispembayaran : '';
            })
            ->addColumn('customer', function ($row) {
                return $row->idanggota ? $row->anggota->nama : '';
            })
            ->addColumn('ruang', function ($row) {
                return $row->idruang ? $row->ruang->ruang : '';
            })
            
            ->addColumn('hbsawal', function ($row) {
                return $row->hbsawal ? Number_Format($row->hbsawal,0) : '';
            })
            ->addColumn('hppawal', function ($row) {
                return $row->hppawal ? Number_Format($row->hppawal,0) : '';
            })

            ->addColumn('masuk', function ($row) {
                return $row->masuk ? Number_Format($row->masuk,0) : '';
            })
            ->addColumn('hbsmasuk', function ($row) {
                return $row->hbsmasuk ? Number_Format($row->hbsmasuk,0) : '';
            })
            ->addColumn('hppmasuk', function ($row) {
                return $row->hppmasuk ? Number_Format($row->hppmasuk,0) : '';
            })

            ->addColumn('keluar', function ($row) {
                return $row->keluar ? Number_Format($row->keluar,0) : '';
            })
            ->addColumn('hbskeluar', function ($row) {
                return $row->hbskeluar ? Number_Format($row->hbskeluar,0) : '';
            })
            ->addColumn('hppkeluar', function ($row) {
                return $row->hppkeluar ? Number_Format($row->hppkeluar,0) : '';
            })

            ->addColumn('akhir', function ($row) {
                return $row->akhir ? Number_Format($row->akhir,0) : '';
            })
            ->addColumn('hbsakhir', function ($row) {
                return $row->hbsakhir ? Number_Format($row->hbsakhir,0) : '';
            })
            ->addColumn('hppakhir', function ($row) {
                return $row->hppakhir ? Number_Format($row->hppakhir,0) : '';
            }) 
            ->addColumn('hjs', function ($row) {
                return $row->hjs ? Number_Format($row->hjs,0) : 0;
            }) 
            ->addColumn('hppj', function ($row) {
                return $row->hppj ? Number_Format($row->hppj,0) : 0;
            }) 
            ->addColumn('ppnkeluar', function ($row) {
                return $row->ppnkeluar ? Number_Format($row->ppnkeluar,0) : 0;
            }) 
            ->addColumn('diskonkeluar', function ($row) {
                return $row->diskonkeluar ? Number_Format($row->diskonkeluar,0) : 0;
            }) 
            ->addColumn('totalhj', function ($row) {
                $hj = $row->hppj;
                $ppn = $row->ppnkeluar;
                $diskon = $row->diskonkeluar;
                $totalhj = $hj + $ppn - $diskon;

                return $totalhj ? Number_Format($totalhj,0) : 0;
            }) 
            ->addColumn('laba', function ($row) {
                $hb = $row->hppkeluar;
                $hj = $row->hppj;
                $ppn = $row->ppnkeluar;
                $diskon = $row->diskonkeluar;
                $totalhj = $hj + $ppn - $diskon;
                $laba = $totalhj - $hb;
                return $laba ? Number_Format($laba,0) : 0;
            }) 

            ->addColumn('waktu', function ($row) {
                $x = date_create($row->created_at);
                return date_format($x,'H:i:s');
            })   
            ->addColumn('tanggal', function ($row) {
                $x = date_create($row->created_at);
                return date_format($x,'Y-m-d');
            })   
            
            ->rawColumns([
                'users',
                'totalhj',
                'laba',
                ])
            

            ->make(true);

            return $data;

    }
    
    public function showpenjualanfifoperitem()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }
        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stokfifo::select('idbarang')
            ->where('kodepokok','=','2')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                 ->orWhere('status','=','returbeli');
                })
            ->with('barang','seksi','ruang','jenispembayaran','anggota','supplier')
            ->groupBy('idbarang')
            // ->orderBy('tglstatus','asc')
            // ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('barang', function ($row) {
                return $row->idbarang ? $row->barang->nabara : '';
            })
            ->addColumn('kode', function ($row) {
                return $row->idbarang ? $row->barang->kode : '';
            })
            ->addColumn('barcode', function ($row) {
                return $row->idbarang ? $row->barang->barcode : '';
            })
            ->addColumn('nabara', function ($row) {
                return $row->idbarang ? $row->barang->nabara : '';
            })
            ->addColumn('satuan', function ($row) {
                return $row->idbarang ? $row->barang->satuan->kode : '';
            })
            ->addColumn('qty', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokfifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');

                return $qty;
            })
            ->addColumn('hbs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokfifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');
                
                $totalhb = Stokfifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb/$qty,0);
            })
            ->addColumn('totalhb', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stokfifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb,0);
            })
            ->addColumn('hjs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hjs = Stokfifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hjs');

                return number_format($hjs,0);
            })
            ->addColumn('subtotalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hppj = Stokfifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');

                return number_format($hppj,0);
            })
            ->addColumn('ppnjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $ppnjual = Stokfifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');

                return number_format($ppnjual,0);
            })
            ->addColumn('diskonjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $diskonjual = Stokfifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');

                return number_format($diskonjual,0);
            })
            ->addColumn('totalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stokfifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stokfifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stokfifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj,0);
            })
            ->addColumn('laba', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stokfifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');
                $subtotalhj = Stokfifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stokfifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stokfifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj - $totalhb,0);
            })
            ->addColumn('jmlrecord', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }
                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $jmlrecord = Stokfifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->count('idbarang');
                
                return $jmlrecord;
            })
            
            ->rawColumns([
                'kode',
                'barcode',
                'nabara',
                'satuan',
                'jmlrecord',
                'qty',
                'hbs',
                'totalhb',
                'hjs',
                'subtotalhj',
                'ppnjual',
                'diskonjual',
                'totalhj',
                'laba',
                ])

            ->make(true);

            return $data;

    }
    public function showpenjualanfifopercustomer()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }
        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stokfifo::select('idanggota')
            ->where('kodepokok','=','2')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                 ->orWhere('status','=','returbeli');
                })
            ->with('barang','seksi','ruang','jenispembayaran','anggota','supplier')
            ->groupBy('idanggota')
            // ->orderBy('tglstatus','asc')
            // ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('nama', function ($row) {
                return $row->idanggota ? $row->anggota->nama : '';
            })
            ->addColumn('nia', function ($row) {
                return $row->idanggota ? $row->anggota->nia : '';
            })
            ->addColumn('lembaga', function ($row) {
                return $row->anggota ? $row->anggota->lembaga->lembaga : '';
            })
                        
            ->addColumn('qty', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokfifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');

                return $qty;
            })
            ->addColumn('hbs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokfifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');
                
                $totalhb = Stokfifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb/$qty,0);
            })
            ->addColumn('totalhb', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stokfifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb,0);
            })
            ->addColumn('hjs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hjs = Stokfifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hjs');

                return number_format($hjs,0);
            })
            ->addColumn('subtotalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hppj = Stokfifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');

                return number_format($hppj,0);
            })
            ->addColumn('ppnjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $ppnjual = Stokfifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');

                return number_format($ppnjual,0);
            })
            ->addColumn('diskonjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $diskonjual = Stokfifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');

                return number_format($diskonjual,0);
            })
            ->addColumn('totalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stokfifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stokfifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stokfifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj,0);
            })
            ->addColumn('laba', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stokfifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');
                $subtotalhj = Stokfifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stokfifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stokfifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj - $totalhb,0);
            })
            ->addColumn('jmlrecord', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }
                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $jmlrecord = Stokfifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->count('idsupplier');
                
                return $jmlrecord;
            })
            
            ->rawColumns([
                'nama',
                'nia',
                'lembaga',
                'jmlrecord',
                'qty',
                'hbs',
                'totalhb',
                'hjs',
                'subtotalhj',
                'ppnjual',
                'diskonjual',
                'totalhj',
                'laba',
                ])

            ->make(true);

            return $data;

    }

    public function showpenjualanfifoperfaktur()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }
        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stokfifo::select('nomorstatus')
            ->where('kodepokok','=','2')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                 ->orWhere('status','=','returbeli');
                })
            ->with('barang','seksi','ruang','jenispembayaran','anggota','supplier')
            ->groupBy('nomorstatus')
            // ->orderBy('tglstatus','asc')
            // ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
                        
            ->addColumn('tglstatus', function ($row) {
                $nomorstatus = $row->nomorstatus;
                $data = explode("." , $nomorstatus);
                $th = substr($data['2'], 0, 4);
                $bl = substr($data['2'], 4, 2);
                $hr = substr($data['2'], 6, 2);
                return $th . '-' . $bl . '-'. $hr;
            })

            ->addColumn('qty', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokfifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');

                return $qty;
            })
            ->addColumn('hbs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokfifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');
                
                $totalhb = Stokfifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb/$qty,0);
            })
            ->addColumn('totalhb', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stokfifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb,0);
            })
            ->addColumn('hjs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hjs = Stokfifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hjs');

                return number_format($hjs,0);
            })
            ->addColumn('subtotalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hppj = Stokfifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');

                return number_format($hppj,0);
            })
            ->addColumn('ppnjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $ppnjual = Stokfifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');

                return number_format($ppnjual,0);
            })
            ->addColumn('diskonjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $diskonjual = Stokfifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');

                return number_format($diskonjual,0);
            })
            ->addColumn('totalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stokfifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stokfifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stokfifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj,0);
            })
            ->addColumn('laba', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stokfifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');
                $subtotalhj = Stokfifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stokfifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')                    
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stokfifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj - $totalhb,0);
            })
            ->addColumn('jmlrecord', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }
                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $jmlrecord = Stokfifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->count('idsupplier');
                
                return $jmlrecord;
            })
            
            ->rawColumns([
                'kode',
                'jmlrecord',
                'tglstatus',
                'qty',
                'hbs',
                'totalhb',
                'hjs',
                'subtotalhj',
                'ppnjual',
                'diskonjual',
                'totalhj',
                'laba',
                ])

            ->make(true);

            return $data;

    }
    public function showpenjualanfifoperjenispembayaran()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }
        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stokfifo::select('idjenispembayaran')
            ->where('kodepokok','=','2')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                 ->orWhere('status','=','returbeli');
                })
            ->with('barang','seksi','ruang','jenispembayaran','anggota','supplier')
            ->groupBy('idjenispembayaran')
            // ->orderBy('tglstatus','asc')
            // ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
                        
            ->addColumn('kode', function ($row) {                
                return ($row->idjenispembayaran ? $row->jenispembayaran->kode : '' );
            })
            ->addColumn('jenispembayaran', function ($row) {                
                return ($row->idjenispembayaran ? $row->jenispembayaran->jenispembayaran : '' );
            })

            ->addColumn('qty', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokfifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');

                return $qty;
            })
            ->addColumn('hbs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokfifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');
                
                $totalhb = Stokfifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb/$qty,0);
            })
            ->addColumn('totalhb', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stokfifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb,0);
            })
            ->addColumn('hjs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hjs = Stokfifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hjs');

                return number_format($hjs,0);
            })
            ->addColumn('subtotalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hppj = Stokfifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');

                return number_format($hppj,0);
            })
            ->addColumn('ppnjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $ppnjual = Stokfifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');

                return number_format($ppnjual,0);
            })
            ->addColumn('diskonjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $diskonjual = Stokfifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');

                return number_format($diskonjual,0);
            })
            ->addColumn('totalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stokfifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stokfifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stokfifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj,0);
            })
            ->addColumn('laba', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stokfifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');
                $subtotalhj = Stokfifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stokfifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stokfifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj - $totalhb,0);
            })
            ->addColumn('jmlrecord', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }
                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $jmlrecord = Stokfifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->count('idjenispembayaran');
                
                return $jmlrecord;
            })
            
            ->rawColumns([
                'kode',
                'jmlrecord',
                'jenispembayaran',
                'qty',
                'hbs',
                'totalhb',
                'hjs',
                'subtotalhj',
                'ppnjual',
                'diskonjual',
                'totalhj',
                'laba',
                ])

            ->make(true);

            return $data;

    }

    public function showpenjualanfifopertanggal()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }

        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stokfifo::select('tglstatus')
            ->where('kodepokok','=','2')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                ->orWhere('status','=','returbeli');
                })
            ->with('barang','seksi','ruang','jenispembayaran','anggota','supplier')
            ->groupBy('tglstatus')
            // ->orderBy('tglstatus','asc')
            // ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
                        
            ->addColumn('tglstatus', function ($row) {
                return ($row->tglstatus);
            })

            ->addColumn('qty', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokfifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');

                return $qty;
            })
            ->addColumn('hbs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokfifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');
                
                $totalhb = Stokfifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb/$qty,0);
            })
            ->addColumn('totalhb', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stokfifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb,0);
            })
            ->addColumn('hjs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hjs = Stokfifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hjs');

                return number_format($hjs,0);
            })
            ->addColumn('subtotalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hppj = Stokfifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');

                return number_format($hppj,0);
            })
            ->addColumn('ppnjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $ppnjual = Stokfifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');

                return number_format($ppnjual,0);
            })
            ->addColumn('diskonjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $diskonjual = Stokfifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');

                return number_format($diskonjual,0);
            })
            ->addColumn('totalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stokfifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stokfifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stokfifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj,0);
            })
            ->addColumn('laba', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stokfifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');
                $subtotalhj = Stokfifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stokfifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stokfifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj - $totalhb,0);
            })
            ->addColumn('jmlrecord', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }
                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $jmlrecord = Stokfifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->count('idsupplier');
                
                return $jmlrecord;
            })

            
            ->rawColumns([
                'kode',
                'jmlrecord',
                'tglstatus',
                'qty',
                'hbs',
                'totalhb',
                'hjs',
                'subtotalhj',
                'ppnjual',
                'diskonjual',
                'totalhj',
                'laba',
                ])

            ->make(true);

            return $data;

    }

    public function showpenjualanmovadetail()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }

        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stokmova::where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                 ->orWhere('status','=','returbeli');
                })
            ->with('barang','seksi','ruang','jenispembayaran','anggota','supplier')
            // ->orderBy('tglstatus','asc')
            // ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('users', function ($row) {
                $iduser = $row->iduser;
                $tampil = Users::select('name')
                    ->where('id','=',$row->iduser)
                    ->get();
                foreach ($tampil as $baris) {
                    $users = $baris->name; 
                }
                return $users;
            })

            ->addColumn('barang', function ($row) {
                return $row->idbarang ? $row->barang->nabara : '';
            })
            ->addColumn('kode', function ($row) {
                return $row->idbarang ? $row->barang->kode : '';
            })
            ->addColumn('barcode', function ($row) {
                return $row->idbarang ? $row->barang->barcode : '';
            })
            ->addColumn('nabara', function ($row) {
                return $row->idbarang ? $row->barang->nabara : '';
            })
            ->addColumn('satuan', function ($row) {
                return $row->idbarang ? $row->barang->satuan->kode : '';
            })
            ->addColumn('jenispembayaran', function ($row) {
                return $row->idjenispembayaran ? $row->jenispembayaran->jenispembayaran : '';
            })
            ->addColumn('customer', function ($row) {
                return $row->idanggota ? $row->anggota->nama : '';
            })
            ->addColumn('ruang', function ($row) {
                return $row->idruang ? $row->ruang->ruang : '';
            })
            
            ->addColumn('hbsawal', function ($row) {
                return $row->hbsawal ? Number_Format($row->hbsawal,0) : '';
            })
            ->addColumn('hppawal', function ($row) {
                return $row->hppawal ? Number_Format($row->hppawal,0) : '';
            })

            ->addColumn('masuk', function ($row) {
                return $row->masuk ? Number_Format($row->masuk,0) : '';
            })
            ->addColumn('hbsmasuk', function ($row) {
                return $row->hbsmasuk ? Number_Format($row->hbsmasuk,0) : '';
            })
            ->addColumn('hppmasuk', function ($row) {
                return $row->hppmasuk ? Number_Format($row->hppmasuk,0) : '';
            })

            ->addColumn('keluar', function ($row) {
                return $row->keluar ? Number_Format($row->keluar,0) : '';
            })
            ->addColumn('hbskeluar', function ($row) {
                return $row->hbskeluar ? Number_Format($row->hbskeluar,0) : '';
            })
            ->addColumn('hppkeluar', function ($row) {
                return $row->hppkeluar ? Number_Format($row->hppkeluar,0) : '';
            })

            ->addColumn('akhir', function ($row) {
                return $row->akhir ? Number_Format($row->akhir,0) : '';
            })
            ->addColumn('hbsakhir', function ($row) {
                return $row->hbsakhir ? Number_Format($row->hbsakhir,0) : '';
            })
            ->addColumn('hppakhir', function ($row) {
                return $row->hppakhir ? Number_Format($row->hppakhir,0) : '';
            }) 
            ->addColumn('hjs', function ($row) {
                return $row->hjs ? Number_Format($row->hjs,0) : 0;
            }) 
            ->addColumn('hppj', function ($row) {
                return $row->hppj ? Number_Format($row->hppj,0) : 0;
            }) 
            ->addColumn('ppnkeluar', function ($row) {
                return $row->ppnkeluar ? Number_Format($row->ppnkeluar,0) : 0;
            }) 
            ->addColumn('diskonkeluar', function ($row) {
                return $row->diskonkeluar ? Number_Format($row->diskonkeluar,0) : 0;
            }) 
            ->addColumn('totalhj', function ($row) {
                $hj = $row->hppj;
                $ppn = $row->ppnkeluar;
                $diskon = $row->diskonkeluar;
                $totalhj = $hj + $ppn - $diskon;

                return $totalhj ? Number_Format($totalhj,0) : 0;
            }) 
            ->addColumn('laba', function ($row) {
                $hb = $row->hppkeluar;
                $hj = $row->hppj;
                $ppn = $row->ppnkeluar;
                $diskon = $row->diskonkeluar;
                $totalhj = $hj + $ppn - $diskon;
                $laba = $totalhj - $hb;
                return $laba ? Number_Format($laba,0) : 0;
            }) 

            ->addColumn('waktu', function ($row) {
                $x = date_create($row->created_at);
                return date_format($x,'H:i:s');
            })   
            ->addColumn('tanggal', function ($row) {
                $x = date_create($row->created_at);
                return date_format($x,'Y-m-d');
            })   
            
            ->rawColumns([
                'users',
                'totalhj',
                'laba',
                ])
            

            ->make(true);

            return $data;

    }
    public function showpenjualanmovaperitem()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }
        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stokmova::select('idbarang')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                 ->orWhere('status','=','returbeli');
                })
            ->with('barang','seksi','ruang','jenispembayaran','anggota','supplier')
            ->groupBy('idbarang')
            // ->orderBy('tglstatus','asc')
            // ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('barang', function ($row) {
                return $row->idbarang ? $row->barang->nabara : '';
            })
            ->addColumn('kode', function ($row) {
                return $row->idbarang ? $row->barang->kode : '';
            })
            ->addColumn('barcode', function ($row) {
                return $row->idbarang ? $row->barang->barcode : '';
            })
            ->addColumn('nabara', function ($row) {
                return $row->idbarang ? $row->barang->nabara : '';
            })
            ->addColumn('satuan', function ($row) {
                return $row->idbarang ? $row->barang->satuan->kode : '';
            })
            ->addColumn('qty', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokmova::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');

                return $qty;
            })
            ->addColumn('hbs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokmova::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');
                
                $totalhb = Stokmova::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb/$qty,0);
            })
            ->addColumn('totalhb', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stokmova::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb,0);
            })
            ->addColumn('hjs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hjs = Stokmova::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hjs');

                return number_format($hjs,0);
            })
            ->addColumn('subtotalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hppj = Stokmova::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');

                return number_format($hppj,0);
            })
            ->addColumn('ppnjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $ppnjual = Stokmova::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');

                return number_format($ppnjual,0);
            })
            ->addColumn('diskonjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $diskonjual = Stokmova::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');

                return number_format($diskonjual,0);
            })
            ->addColumn('totalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stokmova::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stokmova::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stokmova::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj,0);
            })
            ->addColumn('laba', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stokmova::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');
                $subtotalhj = Stokmova::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stokmova::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stokmova::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj - $totalhb,0);
            })
            ->addColumn('jmlrecord', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }
                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $jmlrecord = Stokmova::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->count('idbarang');
                
                return $jmlrecord;
            })
            
            ->rawColumns([
                'kode',
                'barcode',
                'nabara',
                'satuan',
                'jmlrecord',
                'qty',
                'hbs',
                'totalhb',
                'hjs',
                'subtotalhj',
                'ppnjual',
                'diskonjual',
                'totalhj',
                'laba',
                ])

            ->make(true);

            return $data;

    }
    public function showpenjualanmovapercustomer()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }
        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stokmova::select('idanggota')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                 ->orWhere('status','=','returbeli');
                })
            ->with('barang','seksi','ruang','jenispembayaran','anggota','supplier')
            ->groupBy('idanggota')
            // ->orderBy('tglstatus','asc')
            // ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('nama', function ($row) {
                return $row->idanggota ? $row->anggota->nama : '';
            })
            ->addColumn('nia', function ($row) {
                return $row->idanggota ? $row->anggota->nia : '';
            })
            ->addColumn('lembaga', function ($row) {
                return $row->anggota ? $row->anggota->lembaga->lembaga : '';
            })
                        
            ->addColumn('qty', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokmova::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');

                return $qty;
            })
            ->addColumn('hbs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokmova::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');
                
                $totalhb = Stokmova::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb/$qty,0);
            })
            ->addColumn('totalhb', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stokmova::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb,0);
            })
            ->addColumn('hjs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hjs = Stokmova::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hjs');

                return number_format($hjs,0);
            })
            ->addColumn('subtotalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hppj = Stokmova::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');

                return number_format($hppj,0);
            })
            ->addColumn('ppnjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $ppnjual = Stokmova::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');

                return number_format($ppnjual,0);
            })
            ->addColumn('diskonjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $diskonjual = Stokmova::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');

                return number_format($diskonjual,0);
            })
            ->addColumn('totalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stokmova::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stokmova::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stokmova::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj,0);
            })
            ->addColumn('laba', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stokmova::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');
                $subtotalhj = Stokmova::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stokmova::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stokmova::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj - $totalhb,0);
            })
            ->addColumn('jmlrecord', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }
                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $jmlrecord = Stokmova::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->count('idsupplier');
                
                return $jmlrecord;
            })
            
            ->rawColumns([
                'nia',
                'nama',
                'lembaga',
                'jmlrecord',
                'qty',
                'hbs',
                'totalhb',
                'hjs',
                'subtotalhj',
                'ppnjual',
                'diskonjual',
                'totalhj',
                'laba',
                ])

            ->make(true);

            return $data;

    }
    public function showpenjualanmovaperfaktur()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }
        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stokmova::select('nomorstatus')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                 ->orWhere('status','=','returbeli');
                })
            ->with('barang','seksi','ruang','jenispembayaran','anggota','supplier')
            ->groupBy('nomorstatus')
            // ->orderBy('tglstatus','asc')
            // ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
                        
            ->addColumn('tglstatus', function ($row) {
                $nomorstatus = $row->nomorstatus;
                $data = explode("." , $nomorstatus);
                $th = substr($data['2'], 0, 4);
                $bl = substr($data['2'], 4, 2);
                $hr = substr($data['2'], 6, 2);
                return $th . '-' . $bl . '-'. $hr;
            })

            ->addColumn('qty', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokmova::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');

                return $qty;
            })
            ->addColumn('hbs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokmova::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');
                
                $totalhb = Stokmova::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb/$qty,0);
            })
            ->addColumn('totalhb', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stokmova::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb,0);
            })
            ->addColumn('hjs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hjs = Stokmova::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hjs');

                return number_format($hjs,0);
            })
            ->addColumn('subtotalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hppj = Stokmova::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');

                return number_format($hppj,0);
            })
            ->addColumn('ppnjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $ppnjual = Stokmova::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');

                return number_format($ppnjual,0);
            })
            ->addColumn('diskonjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $diskonjual = Stokmova::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');

                return number_format($diskonjual,0);
            })
            ->addColumn('totalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stokmova::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stokmova::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stokmova::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj,0);
            })
            ->addColumn('laba', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stokmova::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');
                $subtotalhj = Stokmova::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stokmova::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stokmova::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj - $totalhb,0);
            })
            ->addColumn('jmlrecord', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }
                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $jmlrecord = Stokmova::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->count('idsupplier');
                
                return $jmlrecord;
            })
            
            ->rawColumns([
                'kode',
                'jmlrecord',
                'tglstatus',
                'qty',
                'hbs',
                'totalhb',
                'hjs',
                'subtotalhj',
                'ppnjual',
                'diskonjual',
                'totalhj',
                'laba',
                ])

            ->make(true);

            return $data;

    }
    public function showpenjualanmovaperjenispembayaran()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }
        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stokmova::select('idjenispembayaran')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                 ->orWhere('status','=','returbeli');
                })
            ->with('barang','seksi','ruang','jenispembayaran','anggota','supplier')
            ->groupBy('idjenispembayaran')
            // ->orderBy('tglstatus','asc')
            // ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
                        
            ->addColumn('kode', function ($row) {                
                return ($row->idjenispembayaran ? $row->jenispembayaran->kode : '' );
            })
            ->addColumn('jenispembayaran', function ($row) {                
                return ($row->idjenispembayaran ? $row->jenispembayaran->jenispembayaran : '' );
            })

            ->addColumn('qty', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokmova::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');

                return $qty;
            })
            ->addColumn('hbs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokmova::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');
                
                $totalhb = Stokmova::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb/$qty,0);
            })
            ->addColumn('totalhb', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stokmova::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb,0);
            })
            ->addColumn('hjs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hjs = Stokmova::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hjs');

                return number_format($hjs,0);
            })
            ->addColumn('subtotalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hppj = Stokmova::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');

                return number_format($hppj,0);
            })
            ->addColumn('ppnjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $ppnjual = Stokmova::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');

                return number_format($ppnjual,0);
            })
            ->addColumn('diskonjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $diskonjual = Stokmova::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');

                return number_format($diskonjual,0);
            })
            ->addColumn('totalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stokmova::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stokmova::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stokmova::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj,0);
            })
            ->addColumn('laba', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stokmova::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');
                $subtotalhj = Stokmova::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stokmova::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stokmova::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj - $totalhb,0);
            })
            ->addColumn('jmlrecord', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }
                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $jmlrecord = Stokmova::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->count('idjenispembayaran');
                
                return $jmlrecord;
            })
            
            ->rawColumns([
                'kode',
                'jmlrecord',
                'jenispembayaran',
                'qty',
                'hbs',
                'totalhb',
                'hjs',
                'subtotalhj',
                'ppnjual',
                'diskonjual',
                'totalhj',
                'laba',
                ])

            ->make(true);

            return $data;

    }
    public function showpenjualanmovapertanggal()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }

        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stokmova::select('tglstatus')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                ->orWhere('status','=','returbeli');
                })
            ->with('barang','seksi','ruang','jenispembayaran','anggota','supplier')
            ->groupBy('tglstatus')
            // ->orderBy('tglstatus','asc')
            // ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
                        
            ->addColumn('tglstatus', function ($row) {
                return ($row->tglstatus);
            })

            ->addColumn('qty', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokmova::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');

                return $qty;
            })
            ->addColumn('hbs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokmova::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');
                
                $totalhb = Stokmova::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb/$qty,0);
            })
            ->addColumn('totalhb', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stokmova::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb,0);
            })
            ->addColumn('hjs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hjs = Stokmova::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hjs');

                return number_format($hjs,0);
            })
            ->addColumn('subtotalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hppj = Stokmova::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');

                return number_format($hppj,0);
            })
            ->addColumn('ppnjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $ppnjual = Stokmova::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');

                return number_format($ppnjual,0);
            })
            ->addColumn('diskonjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $diskonjual = Stokmova::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');

                return number_format($diskonjual,0);
            })
            ->addColumn('totalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stokmova::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stokmova::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stokmova::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj,0);
            })
            ->addColumn('laba', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stokmova::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');
                $subtotalhj = Stokmova::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stokmova::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stokmova::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj - $totalhb,0);
            })
            ->addColumn('jmlrecord', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }
                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $jmlrecord = Stokmova::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->count('idsupplier');
                
                return $jmlrecord;
            })

            
            ->rawColumns([
                'kode',
                'jmlrecord',
                'tglstatus',
                'qty',
                'hbs',
                'totalhb',
                'hjs',
                'subtotalhj',
                'ppnjual',
                'diskonjual',
                'totalhj',
                'laba',
                ])

            ->make(true);

            return $data;

    }


    public function showpenjualanlifodetail()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }

        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stoklifo::where('kodepokok','=','2')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                 ->orWhere('status','=','returbeli');
                })
            ->with('barang','seksi','ruang','jenispembayaran','anggota','supplier')
            // ->orderBy('tglstatus','asc')
            // ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('users', function ($row) {
                $iduser = $row->iduser;
                $tampil = Users::select('name')
                    ->where('id','=',$row->iduser)
                    ->get();
                foreach ($tampil as $baris) {
                    $users = $baris->name; 
                }
                return $users;
            })

            ->addColumn('barang', function ($row) {
                return $row->idbarang ? $row->barang->nabara : '';
            })
            ->addColumn('kode', function ($row) {
                return $row->idbarang ? $row->barang->kode : '';
            })
            ->addColumn('barcode', function ($row) {
                return $row->idbarang ? $row->barang->barcode : '';
            })
            ->addColumn('nabara', function ($row) {
                return $row->idbarang ? $row->barang->nabara : '';
            })
            ->addColumn('satuan', function ($row) {
                return $row->idbarang ? $row->barang->satuan->kode : '';
            })
            ->addColumn('jenispembayaran', function ($row) {
                return $row->idjenispembayaran ? $row->jenispembayaran->jenispembayaran : '';
            })
            ->addColumn('customer', function ($row) {
                return $row->idanggota ? $row->anggota->nama : '';
            })
            ->addColumn('ruang', function ($row) {
                return $row->idruang ? $row->ruang->ruang : '';
            })
            
            ->addColumn('hbsawal', function ($row) {
                return $row->hbsawal ? Number_Format($row->hbsawal,0) : '';
            })
            ->addColumn('hppawal', function ($row) {
                return $row->hppawal ? Number_Format($row->hppawal,0) : '';
            })

            ->addColumn('masuk', function ($row) {
                return $row->masuk ? Number_Format($row->masuk,0) : '';
            })
            ->addColumn('hbsmasuk', function ($row) {
                return $row->hbsmasuk ? Number_Format($row->hbsmasuk,0) : '';
            })
            ->addColumn('hppmasuk', function ($row) {
                return $row->hppmasuk ? Number_Format($row->hppmasuk,0) : '';
            })

            ->addColumn('keluar', function ($row) {
                return $row->keluar ? Number_Format($row->keluar,0) : '';
            })
            ->addColumn('hbskeluar', function ($row) {
                return $row->hbskeluar ? Number_Format($row->hbskeluar,0) : '';
            })
            ->addColumn('hppkeluar', function ($row) {
                return $row->hppkeluar ? Number_Format($row->hppkeluar,0) : '';
            })

            ->addColumn('akhir', function ($row) {
                return $row->akhir ? Number_Format($row->akhir,0) : '';
            })
            ->addColumn('hbsakhir', function ($row) {
                return $row->hbsakhir ? Number_Format($row->hbsakhir,0) : '';
            })
            ->addColumn('hppakhir', function ($row) {
                return $row->hppakhir ? Number_Format($row->hppakhir,0) : '';
            }) 
            ->addColumn('hjs', function ($row) {
                return $row->hjs ? Number_Format($row->hjs,0) : 0;
            }) 
            ->addColumn('hppj', function ($row) {
                return $row->hppj ? Number_Format($row->hppj,0) : 0;
            }) 
            ->addColumn('ppnkeluar', function ($row) {
                return $row->ppnkeluar ? Number_Format($row->ppnkeluar,0) : 0;
            }) 
            ->addColumn('diskonkeluar', function ($row) {
                return $row->diskonkeluar ? Number_Format($row->diskonkeluar,0) : 0;
            }) 
            ->addColumn('totalhj', function ($row) {
                $hj = $row->hppj;
                $ppn = $row->ppnkeluar;
                $diskon = $row->diskonkeluar;
                $totalhj = $hj + $ppn - $diskon;

                return $totalhj ? Number_Format($totalhj,0) : 0;
            }) 
            ->addColumn('laba', function ($row) {
                $hb = $row->hppkeluar;
                $hj = $row->hppj;
                $ppn = $row->ppnkeluar;
                $diskon = $row->diskonkeluar;
                $totalhj = $hj + $ppn - $diskon;
                $laba = $totalhj - $hb;
                return $laba ? Number_Format($laba,0) : 0;
            }) 

            ->addColumn('waktu', function ($row) {
                $x = date_create($row->created_at);
                return date_format($x,'H:i:s');
            })   
            ->addColumn('tanggal', function ($row) {
                $x = date_create($row->created_at);
                return date_format($x,'Y-m-d');
            })   
            
            ->rawColumns([
                'users',
                'totalhj',
                'laba',
                ])
            

            ->make(true);

            return $data;

    }
    
    public function showpenjualanlifoperitem()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }
        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stoklifo::select('idbarang')
            ->where('kodepokok','=','2')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                 ->orWhere('status','=','returbeli');
                })
            ->with('barang','seksi','ruang','jenispembayaran','anggota','supplier')
            ->groupBy('idbarang')
            // ->orderBy('tglstatus','asc')
            // ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('barang', function ($row) {
                return $row->idbarang ? $row->barang->nabara : '';
            })
            ->addColumn('kode', function ($row) {
                return $row->idbarang ? $row->barang->kode : '';
            })
            ->addColumn('barcode', function ($row) {
                return $row->idbarang ? $row->barang->barcode : '';
            })
            ->addColumn('nabara', function ($row) {
                return $row->idbarang ? $row->barang->nabara : '';
            })
            ->addColumn('satuan', function ($row) {
                return $row->idbarang ? $row->barang->satuan->kode : '';
            })
            ->addColumn('qty', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stoklifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');

                return $qty;
            })
            ->addColumn('hbs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stoklifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');
                
                $totalhb = Stoklifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb/$qty,0);
            })
            ->addColumn('totalhb', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stoklifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb,0);
            })
            ->addColumn('hjs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hjs = Stoklifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hjs');

                return number_format($hjs,0);
            })
            ->addColumn('subtotalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hppj = Stoklifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');

                return number_format($hppj,0);
            })
            ->addColumn('ppnjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $ppnjual = Stoklifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');

                return number_format($ppnjual,0);
            })
            ->addColumn('diskonjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $diskonjual = Stoklifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');

                return number_format($diskonjual,0);
            })
            ->addColumn('totalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stoklifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stoklifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stoklifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj,0);
            })
            ->addColumn('laba', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stoklifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');
                $subtotalhj = Stoklifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stoklifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stoklifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj - $totalhb,0);
            })
            ->addColumn('jmlrecord', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }
                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $jmlrecord = Stoklifo::where('idbarang','=',$row->idbarang)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->count('idbarang');
                
                return $jmlrecord;
            })
            
            ->rawColumns([
                'kode',
                'barcode',
                'nabara',
                'satuan',
                'jmlrecord',
                'qty',
                'hbs',
                'totalhb',
                'hjs',
                'subtotalhj',
                'ppnjual',
                'diskonjual',
                'totalhj',
                'laba',
                ])

            ->make(true);

            return $data;

    }
    public function showpenjualanlifopercustomer()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }
        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stoklifo::select('idanggota')
            ->where('kodepokok','=','2')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                 ->orWhere('status','=','returbeli');
                })
            ->with('barang','seksi','ruang','jenispembayaran','anggota','supplier')
            ->groupBy('idanggota')
            // ->orderBy('tglstatus','asc')
            // ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('nama', function ($row) {
                return $row->idanggota ? $row->anggota->nama : '';
            })
            ->addColumn('nia', function ($row) {
                return $row->idanggota ? $row->anggota->nia : '';
            })
            ->addColumn('lembaga', function ($row) {
                return $row->anggota ? $row->anggota->lembaga->lembaga : '';
            })
                        
            ->addColumn('qty', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stoklifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');

                return $qty;
            })
            ->addColumn('hbs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stoklifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');
                
                $totalhb = Stoklifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb/$qty,0);
            })
            ->addColumn('totalhb', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stoklifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb,0);
            })
            ->addColumn('hjs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hjs = Stoklifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hjs');

                return number_format($hjs,0);
            })
            ->addColumn('subtotalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hppj = Stoklifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');

                return number_format($hppj,0);
            })
            ->addColumn('ppnjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $ppnjual = Stoklifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');

                return number_format($ppnjual,0);
            })
            ->addColumn('diskonjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $diskonjual = Stoklifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');

                return number_format($diskonjual,0);
            })
            ->addColumn('totalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stoklifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stoklifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stoklifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj,0);
            })
            ->addColumn('laba', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stoklifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');
                $subtotalhj = Stoklifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stoklifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stoklifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj - $totalhb,0);
            })
            ->addColumn('jmlrecord', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }
                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $jmlrecord = Stoklifo::where('idanggota','=',$row->idanggota)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->count('idsupplier');
                
                return $jmlrecord;
            })
            
            ->rawColumns([
                'nia',
                'nama',
                'lembaga',
                'jmlrecord',
                'qty',
                'hbs',
                'totalhb',
                'hjs',
                'subtotalhj',
                'ppnjual',
                'diskonjual',
                'totalhj',
                'laba',
                ])

            ->make(true);

            return $data;

    }

    public function showpenjualanlifoperfaktur()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }
        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stoklifo::select('nomorstatus')
            ->where('kodepokok','=','2')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                 ->orWhere('status','=','returbeli');
                })
            ->with('barang','seksi','ruang','jenispembayaran','anggota','supplier')
            ->groupBy('nomorstatus')
            // ->orderBy('tglstatus','asc')
            // ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
                        
            ->addColumn('tglstatus', function ($row) {
                $nomorstatus = $row->nomorstatus;
                $data = explode("." , $nomorstatus);
                $th = substr($data['2'], 0, 4);
                $bl = substr($data['2'], 4, 2);
                $hr = substr($data['2'], 6, 2);
                return $th . '-' . $bl . '-'. $hr;
            })

            ->addColumn('qty', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stoklifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');

                return $qty;
            })
            ->addColumn('hbs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stoklifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');
                
                $totalhb = Stoklifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb/$qty,0);
            })
            ->addColumn('totalhb', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stoklifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb,0);
            })
            ->addColumn('hjs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hjs = Stoklifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hjs');

                return number_format($hjs,0);
            })
            ->addColumn('subtotalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hppj = Stoklifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');

                return number_format($hppj,0);
            })
            ->addColumn('ppnjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $ppnjual = Stoklifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');

                return number_format($ppnjual,0);
            })
            ->addColumn('diskonjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $diskonjual = Stoklifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');

                return number_format($diskonjual,0);
            })
            ->addColumn('totalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stoklifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stoklifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stoklifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj,0);
            })
            ->addColumn('laba', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stoklifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');
                $subtotalhj = Stoklifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stoklifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')                    
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stoklifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj - $totalhb,0);
            })
            ->addColumn('jmlrecord', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }
                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $jmlrecord = Stoklifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->count('idsupplier');
                
                return $jmlrecord;
            })
            
            ->rawColumns([
                'kode',
                'jmlrecord',
                'tglstatus',
                'qty',
                'hbs',
                'totalhb',
                'hjs',
                'subtotalhj',
                'ppnjual',
                'diskonjual',
                'totalhj',
                'laba',
                ])

            ->make(true);

            return $data;

    }
    public function showpenjualanlifoperjenispembayaran()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }
        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stoklifo::select('idjenispembayaran')
            ->where('kodepokok','=','2')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                 ->orWhere('status','=','returbeli');
                })
            ->with('barang','seksi','ruang','jenispembayaran','anggota','supplier')
            ->groupBy('idjenispembayaran')
            // ->orderBy('tglstatus','asc')
            // ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
                        
            ->addColumn('kode', function ($row) {                
                return ($row->idjenispembayaran ? $row->jenispembayaran->kode : '' );
            })
            ->addColumn('jenispembayaran', function ($row) {                
                return ($row->idjenispembayaran ? $row->jenispembayaran->jenispembayaran : '' );
            })

            ->addColumn('qty', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stoklifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');

                return $qty;
            })
            ->addColumn('hbs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stoklifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');
                
                $totalhb = Stoklifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb/$qty,0);
            })
            ->addColumn('totalhb', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stoklifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb,0);
            })
            ->addColumn('hjs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hjs = Stoklifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hjs');

                return number_format($hjs,0);
            })
            ->addColumn('subtotalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hppj = Stoklifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');

                return number_format($hppj,0);
            })
            ->addColumn('ppnjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $ppnjual = Stoklifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');

                return number_format($ppnjual,0);
            })
            ->addColumn('diskonjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $diskonjual = Stoklifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');

                return number_format($diskonjual,0);
            })
            ->addColumn('totalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stoklifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stoklifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stoklifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj,0);
            })
            ->addColumn('laba', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stoklifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');
                $subtotalhj = Stoklifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stoklifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stoklifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj - $totalhb,0);
            })
            ->addColumn('jmlrecord', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }
                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $jmlrecord = Stoklifo::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->count('idjenispembayaran');
                
                return $jmlrecord;
            })
            
            ->rawColumns([
                'kode',
                'jmlrecord',
                'jenispembayaran',
                'qty',
                'hbs',
                'totalhb',
                'hjs',
                'subtotalhj',
                'ppnjual',
                'diskonjual',
                'totalhj',
                'laba',
                ])

            ->make(true);

            return $data;

    }

    public function showpenjualanlifopertanggal()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }

        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stoklifo::select('tglstatus')
            ->where('kodepokok','=','2')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                ->orWhere('status','=','returbeli');
                })
            ->with('barang','seksi','ruang','jenispembayaran','anggota','supplier')
            ->groupBy('tglstatus')
            // ->orderBy('tglstatus','asc')
            // ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
                        
            ->addColumn('tglstatus', function ($row) {
                return ($row->tglstatus);
            })

            ->addColumn('qty', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stoklifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');

                return $qty;
            })
            ->addColumn('hbs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stoklifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('keluar');
                
                $totalhb = Stoklifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb/$qty,0);
            })
            ->addColumn('totalhb', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stoklifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');

                return number_format($totalhb,0);
            })
            ->addColumn('hjs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hjs = Stoklifo::where('nomorstatus','=',$row->nomorstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hjs');

                return number_format($hjs,0);
            })
            ->addColumn('subtotalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hppj = Stoklifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');

                return number_format($hppj,0);
            })
            ->addColumn('ppnjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $ppnjual = Stoklifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');

                return number_format($ppnjual,0);
            })
            ->addColumn('diskonjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $diskonjual = Stoklifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');

                return number_format($diskonjual,0);
            })
            ->addColumn('totalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stoklifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stoklifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stoklifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj,0);
            })
            ->addColumn('laba', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $totalhb = Stoklifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppkeluar');
                $subtotalhj = Stoklifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('hppj');
                $ppnjual = Stoklifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stoklifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj - $totalhb,0);
            })
            ->addColumn('jmlrecord', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }
                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $jmlrecord = Stoklifo::where('tglstatus','=',$row->tglstatus)
                    ->where('kodepokok','=','2')
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluar')
                                        ->orWhere('status','=','returbeli');
                        })
                    ->count('idsupplier');
                
                return $jmlrecord;
            })

            
            ->rawColumns([
                'kode',
                'jmlrecord',
                'tglstatus',
                'qty',
                'hbs',
                'totalhb',
                'hjs',
                'subtotalhj',
                'ppnjual',
                'diskonjual',
                'totalhj',
                'laba',
                ])

            ->make(true);

            return $data;

    }



    
    public function edit($id)
    {
        $data = Barangruang::where('id', '=', $id)->get();
        return json_encode(array('data' => $data));       
    }

    public function showpenjualanlaindetail()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }

        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stokmamin::where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluarmam')
                                 ->orWhere('status','=','returbeli');
                })
            ->with('mamin','ruang','jenispembayaran','anggota')
            
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('users', function ($row) {
                $iduser = $row->iduser;
                $tampil = Users::select('name')
                    ->where('id','=',$row->iduser)
                    ->get();
                foreach ($tampil as $baris) {
                    $users = $baris->name; 
                }
                return $users;
            })

            ->addColumn('kode', function ($row) {
                return $row->idmamin ? $row->mamin->kode : '';
            })
            ->addColumn('barcode', function ($row) {
                return $row->idmamin ? $row->mamin->barcode : '';
            })
            ->addColumn('namamin', function ($row) {
                return $row->idmamin ? $row->mamin->namamin : '';
            })
            ->addColumn('satuan', function ($row) {
                return $row->idmamin ? $row->mamin->satuan->kode : '';
            })
            ->addColumn('jenispembayaran', function ($row) {
                return $row->idjenispembayaran ? $row->jenispembayaran->jenispembayaran : '';
            })
            ->addColumn('customer', function ($row) {
                return $row->idanggota ? $row->anggota->nama : '';
            })
            ->addColumn('ruang', function ($row) {
                return $row->idruang ? $row->ruang->ruang : '';
            })
            
            ->addColumn('hbsawal', function ($row) {
                return $row->hbsawal ? Number_Format($row->hbsawal,0) : '';
            })
            ->addColumn('hppawal', function ($row) {
                return $row->hppawal ? Number_Format($row->hppawal,0) : '';
            })

            ->addColumn('masuk', function ($row) {
                return $row->masuk ? Number_Format($row->masuk,0) : '';
            })
            ->addColumn('hbsmasuk', function ($row) {
                return $row->hbsmasuk ? Number_Format($row->hbsmasuk,0) : '';
            })
            ->addColumn('hppmasuk', function ($row) {
                return $row->hppmasuk ? Number_Format($row->hppmasuk,0) : '';
            })

            ->addColumn('keluar', function ($row) {
                return $row->keluar ? Number_Format($row->keluar,0) : '';
            })
            ->addColumn('hbskeluar', function ($row) {
                return $row->hbskeluar ? Number_Format($row->hbskeluar,0) : '';
            })
            ->addColumn('hppkeluar', function ($row) {
                return $row->hppkeluar ? Number_Format($row->hppkeluar,0) : '';
            })

            ->addColumn('akhir', function ($row) {
                return $row->akhir ? Number_Format($row->akhir,0) : '';
            })
            ->addColumn('hbsakhir', function ($row) {
                return $row->hbsakhir ? Number_Format($row->hbsakhir,0) : '';
            })
            ->addColumn('hppakhir', function ($row) {
                return $row->hppakhir ? Number_Format($row->hppakhir,0) : '';
            }) 
            ->addColumn('hjs', function ($row) {
                return $row->hjs ? Number_Format($row->hjs,0) : 0;
            }) 
            ->addColumn('hppj', function ($row) {
                return $row->hppj ? Number_Format($row->hppj,0) : 0;
            }) 
            ->addColumn('ppnkeluar', function ($row) {
                return $row->ppnkeluar ? Number_Format($row->ppnkeluar,0) : 0;
            }) 
            ->addColumn('diskonkeluar', function ($row) {
                return $row->diskonkeluar ? Number_Format($row->diskonkeluar,0) : 0;
            }) 
            ->addColumn('totalhj', function ($row) {
                $hj = $row->hppj;
                $ppn = $row->ppnkeluar;
                $diskon = $row->diskonkeluar;
                $totalhj = $hj + $ppn - $diskon;

                return $totalhj ? Number_Format($totalhj,0) : 0;
            }) 

            ->addColumn('waktu', function ($row) {
                $x = date_create($row->created_at);
                return date_format($x,'H:i:s');
            })   
            ->addColumn('tanggal', function ($row) {
                $x = date_create($row->created_at);
                return date_format($x,'Y-m-d');
            })   
            
            ->rawColumns([
                'users',
                'totalhj',                
                ])
            

            ->make(true);

            return $data;

    }
    public function showpenjualanlainperitem()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }
        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stokmamin::select('idmamin')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluarmam')
                                 ->orWhere('status','=','returmam');
                })
            ->with('mamin','ruang','jenispembayaran','anggota')
            ->groupBy('idmamin')
            // ->orderBy('tglstatus','asc')
            // ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('kode', function ($row) {
                return $row->idmamin ? $row->mamin->kode : '';
            })
            ->addColumn('barcode', function ($row) {
                return $row->idmamin ? $row->mamin->barcode : '';
            })
            ->addColumn('namamin', function ($row) {
                return $row->idmamin ? $row->mamin->namamin : '';
            })
            ->addColumn('satuan', function ($row) {
                return $row->idmamin ? $row->mamin->satuan->kode : '';
            })
            ->addColumn('qty', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokmamin::where('idmamin','=',$row->idmamin)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('keluar');

                return $qty;
            })
            
            ->addColumn('hjs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokmamin::where('idmamin','=',$row->idmamin)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('keluar');

                $hppj = Stokmamin::where('idmamin','=',$row->idmamin)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('hppj');

                    $hjs = $hppj/$qty;

                return number_format($hjs,0);
            })
            ->addColumn('subtotalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $hppj = Stokmamin::where('idmamin','=',$row->idmamin)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('hppj');

                return number_format($hppj,0);
            })
            ->addColumn('ppnjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $ppnjual = Stokmamin::where('idmamin','=',$row->idmamin)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('ppnkeluar');

                return number_format($ppnjual,0);
            })
            ->addColumn('diskonjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $diskonjual = Stokmamin::where('idmamin','=',$row->idmamin)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('diskonkeluar');

                return number_format($diskonjual,0);
            })
            ->addColumn('totalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stokmamin::where('idmamin','=',$row->idmamin)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('hppj');
                $ppnjual = Stokmamin::where('idmamin','=',$row->idmamin)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returbelimam');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stokmamin::where('idmamin','=',$row->idmamin)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returbelimam');
                        })
                    ->sum('diskonkeluar');

                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj,0);
            })
            
            ->addColumn('jmlrecord', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }
                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $jmlrecord = Stokmamin::where('idmamin','=',$row->idmamin)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->count('idmamin');
                
                return $jmlrecord;
            })
            
            ->rawColumns([
                'kode',
                'barcode',
                'namamin',
                'satuan',
                'jmlrecord',
                'qty',
                'hbs',
                'totalhb',
                'hjs',
                'subtotalhj',
                'ppnjual',
                'diskonjual',
                'totalhj',
                'laba',
                ])

            ->make(true);

            return $data;

    }
    public function showpenjualanlainpercustomer()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }
        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stokmamin::select('idanggota')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluarmam')
                                 ->orWhere('status','=','returmam');
                })
            ->with('mamin','ruang','jenispembayaran','anggota')
            ->groupBy('idanggota')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('nia', function ($row) {
                return $row->idanggota ? $row->anggota->nama : '';
            })
            ->addColumn('nama', function ($row) {
                return $row->idanggota ? $row->anggota->nama : '';
            })
            ->addColumn('lembaga', function ($row) {
                return $row->idanggota ? $row->anggota->lembaga->lembaga : '';
            })
                        
            ->addColumn('qty', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokmamin::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returbelimam');
                        })
                    ->sum('keluar');

                return $qty;
            })
            ->addColumn('hjs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokmamin::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returbelimam');
                        })
                    ->sum('keluar');
                
                $subtotalhj = Stokmamin::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returbelimam');
                        })
                    ->sum('hppj');

                    $hjs = $subtotalhj/$qty;

                return number_format($hjs,0);
            })
            
            ->addColumn('subtotalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stokmamin::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('hppj');

                return number_format($subtotalhj,0);
            })
            ->addColumn('ppnjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $ppnjual = Stokmamin::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('ppnkeluar');

                return number_format($ppnjual,0);
            })
            ->addColumn('diskonjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $diskonjual = Stokmamin::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('diskonkeluar');

                return number_format($diskonjual,0);
            })
            ->addColumn('totalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stokmamin::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('hppj');
                $ppnjual = Stokmamin::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stokmamin::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj,0);
            })
            
            ->addColumn('jmlrecord', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }
                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $jmlrecord = Stokmamin::where('idanggota','=',$row->idanggota)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->count('idanggota');
                
                return $jmlrecord;
            })
            
            ->rawColumns([
                'nia',
                'nama',
                'lembaga',
                'jmlrecord',
                'qty',
                'hjs',
                'subtotalhj',
                'ppnjual',
                'diskonjual',
                'totalhj',
                ])

            ->make(true);

            return $data;

    }
    public function showpenjualanlainperfaktur()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }
        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stokmamin::select('nomorstatus')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluarmam')
                                 ->orWhere('status','=','returmam');
                })
            ->with('mamin','ruang','jenispembayaran','anggota')
            ->groupBy('nomorstatus')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
                        
            ->addColumn('tglstatus', function ($row) {
                $nomorstatus = $row->nomorstatus;
                $data = explode("." , $nomorstatus);
                $th = substr($data['2'], 0, 4);
                $bl = substr($data['2'], 4, 2);
                $hr = substr($data['2'], 6, 2);
                return $th . '-' . $bl . '-'. $hr;
            })

            ->addColumn('qty', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokmamin::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('keluar');

                return $qty;
            })
            
            ->addColumn('hjs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokmamin::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('keluar');

                $subtotalhj = Stokmamin::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('hppj');

                    $hjs = $subtotalhj/$qty;

                return number_format($hjs,0);
            })
            ->addColumn('subtotalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stokmamin::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('hppj');

                return number_format($subtotalhj,0);
            })
            ->addColumn('ppnjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $ppnjual = Stokmamin::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('ppnkeluar');

                return number_format($ppnjual,0);
            })
            ->addColumn('diskonjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $diskonjual = Stokmamin::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('diskonkeluar');

                return number_format($diskonjual,0);
            })
            ->addColumn('totalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stokmamin::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('hppj');
                $ppnjual = Stokmamin::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stokmamin::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj,0);
            })
            
            ->addColumn('jmlrecord', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }
                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $jmlrecord = Stokmamin::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->count('nomorstatus');
                
                return $jmlrecord;
            })
            
            ->rawColumns([
                'kode',
                'jmlrecord',
                'tglstatus',
                'qty',                
                'hjs',
                'subtotalhj',
                'ppnjual',
                'diskonjual',
                'totalhj',
                ])

            ->make(true);

            return $data;

    }
    public function showpenjualanlainperjenispembayaran()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }
        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stokmamin::select('idjenispembayaran')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluarmam')
                                 ->orWhere('status','=','returmam');
                })
            ->with('mamin','ruang','jenispembayaran','anggota')
            ->groupBy('idjenispembayaran')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
                        
            ->addColumn('kode', function ($row) {                
                return ($row->idjenispembayaran ? $row->jenispembayaran->kode : '' );
            })
            ->addColumn('jenispembayaran', function ($row) {                
                return ($row->idjenispembayaran ? $row->jenispembayaran->jenispembayaran : '' );
            })

            ->addColumn('qty', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokmamin::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('keluar');

                return $qty;
            })
            
            ->addColumn('hjs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokmamin::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('keluar');
                $subtotalhj = Stokmamin::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('hppj');

                    $hjs = $subtotalhj/$qty;

                return number_format($hjs,0);
            })
            ->addColumn('subtotalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stokmamin::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('hppj');

                return number_format($subtotalhj,0);
            })
            ->addColumn('ppnjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $ppnjual = Stokmamin::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('ppnkeluar');

                return number_format($ppnjual,0);
            })
            ->addColumn('diskonjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $diskonjual = Stokmamin::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('diskonkeluar');

                return number_format($diskonjual,0);
            })
            ->addColumn('totalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stokmamin::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('hppj');
                $ppnjual = Stokmamin::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stokmamin::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj,0);
            })
            
            ->addColumn('jmlrecord', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }
                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $jmlrecord = Stokmamin::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->count('idjenispembayaran');
                
                return $jmlrecord;
            })
            
            ->rawColumns([
                'kode',
                'jmlrecord',
                'jenispembayaran',
                'qty',
                'hjs',
                'subtotalhj',
                'ppnjual',
                'diskonjual',
                'totalhj',
                ])

            ->make(true);

            return $data;

    }
    public function showpenjualanlainpertanggal()
    {
        date_default_timezone_set("Asia/Bangkok");

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $idanggotax = session('idcustomer1');
        if($idanggotax=='-1'||$idanggotax==''){
            $idanggotaawal = 0;
            $idanggotaakhir = 999999;
        }else{
            $idanggotaawal = $idanggotax;
            $idanggotaakhir = $idanggotax;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }

        $iduserx = session('idoperator1');
        if($iduserx=='-1'||$iduserx==''){
            $iduserawal = 0;
            $iduserakhir = 999999;
        }else{
            $iduserawal = $iduserx;
            $iduserakhir = $iduserx;
        }

        $stok = Stokmamin::select('tglstatus')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idanggota','>=',$idanggotaawal)
            ->where('idanggota','<=',$idanggotaakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where('iduser','>=',$iduserawal)
            ->where('iduser','<=',$iduserakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluarmam')
                                ->orWhere('status','=','returmam');
                })
            ->with('mamin','ruang','jenispembayaran','anggota')
            ->groupBy('tglstatus')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
                        
            ->addColumn('tglstatus', function ($row) {
                return ($row->tglstatus);
            })

            ->addColumn('qty', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $qty = Stokmamin::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('keluar');

                return $qty;
            })
            
            ->addColumn('hjs', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stokmamin::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('hppj');
               $qty = Stokmamin::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('keluar');
                
                   $hjs = $subtotalhj/$qty; 

                return number_format($hjs,0);
            })
            ->addColumn('subtotalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stokmamin::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('hppj');

                return number_format($subtotalhj,0);
            })
            ->addColumn('ppnjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $ppnjual = Stokmamin::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('ppnkeluar');

                return number_format($ppnjual,0);
            })
            ->addColumn('diskonjual', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $diskonjual = Stokmamin::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('diskonkeluar');

                return number_format($diskonjual,0);
            })
            ->addColumn('totalhj', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $subtotalhj = Stokmamin::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('hppj');
                $ppnjual = Stokmamin::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('ppnkeluar');
                $diskonjual = Stokmamin::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->sum('diskonkeluar');
                $totalhj = $subtotalhj + $ppnjual - $diskonjual;
                return number_format($totalhj,0);
            })
            
            ->addColumn('jmlrecord', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idanggotax = session('idcustomer1');
                if($idanggotax=='-1'||$idanggotax==''){
                    $idanggotaawal = 0;
                    $idanggotaakhir = 999999;
                }else{
                    $idanggotaawal = $idanggotax;
                    $idanggotaakhir = $idanggotax;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }
                $iduserx = session('idoperator1');
                if($iduserx=='-1'||$iduserx==''){
                    $iduserawal = 0;
                    $iduserakhir = 999999;
                }else{
                    $iduserawal = $iduserx;
                    $iduserakhir = $iduserx;
                }

                $jmlrecord = Stokmamin::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idanggota','>=',$idanggotaawal)
                    ->where('idanggota','<=',$idanggotaakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where('iduser','>=',$iduserawal)
                    ->where('iduser','<=',$iduserakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','keluarmam')
                                        ->orWhere('status','=','returmam');
                        })
                    ->count('tglstatus');
                
                return $jmlrecord;
            })

            
            ->rawColumns([
                'kode',
                'jmlrecord',
                'tglstatus',
                'qty',
                'hjs',
                'subtotalhj',
                'ppnjual',
                'diskonjual',
                'totalhj',
                ])

            ->make(true);

            return $data;

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {

        $data = Barangruang::where('id', '=', $id)->delete();
        return json_encode(array('data' => $data));
    }


    public function showsupplier()
    {

        $tampil = Supplier::orderBy('supplier', 'asc')
            // ->whereIn('id',$idsupplier)            
            ->get();

        // $tampil = Supplier::get();
            
        $datax = DataTables::of($tampil                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('kode', function ($row) { 
                return '<a href="#" style="color: white;" title="'. ($row->kode ? $row->kode : '-') .'" class="item_kode" data1="' . $row->id . '" data2="'. $row->kode. '" data3="'. $row->supplier. '" data4="'. $row->alamat. '" data5="'. $row->desa. '" data6="'. $row->kecamatan. '" data7="'. $row->kabupaten. '" data100="'. $row->id. '">'.($row->kode ? $row->kode : '-').'</a> ';
            })
            ->addColumn('supplier', function ($row) { 
                return '<a href="#" style="color: white;" title="'. ($row->supplier ? $row->supplier : '-') .'" class="item_supplier" data1="' . $row->id . '" data2="'. $row->kode. '" data3="'. $row->supplier. '" data4="'. $row->alamat. '" data5="'. $row->desa. '" data6="'. $row->kecamatan. '" data7="'. $row->kabupaten. '" data100="'. $row->id. '">'.($row->supplier ? $row->supplier : '-').'</a> ';
            })
            ->addColumn('alamat', function ($row) { 
                return '<a href="#" style="color: white;" title="'. ($row->alamat ? $row->alamat : '-') .'" class="item_alamat" data1="' . $row->id . '" data2="'. $row->kode. '" data3="'. $row->supplier. '" data4="'. $row->alamat. '" data5="'. $row->desa. '" data6="'. $row->kecamatan. '" data7="'. $row->kabupaten. '" data100="'. $row->id. '">'.($row->alamat ? $row->alamat : '-').'</a> ';
            })
            
            ->rawColumns([
                'kode',
                'supplier',
                'alamat'
                ])


            ->make(true);

            return $data;

    }
    function listcustomer()
    {
        $idx1 = '-1';
        $idx2 = '0';
        $isix2 = 'UMUM';
        $tampil = Anggota::where('id','<>','0')
            // ->whereIn('id',$idsupplier)
            ->with('lembaga')
            ->orderBy('nama', 'asc')
            ->get();
            echo "<option value='" . $idx1 . "'>" . "- SEMUA -" . "</option>";
            echo "<option value='" . $idx2 . "'>". $isix2 . "</option>";
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>". $baris->nama . " - " . $baris->lembaga->lembaga . "</option>";
        }


        // $idruangx = session('idruang1');
        // $idbarang = Barangruang::select('idbarang')
        //     ->where('idruang','=',$idruangx);

        // $tampil = Barang::whereIn('id',$idbarang)
        //     ->orderBy('nabara', 'asc')->get();
        // foreach ($tampil as $baris) {
        //     echo "<option value='" . $baris->id . "'>" . $baris->nabara . "</option>";
        // }

    }
   
    function listruang()
    {
        $idx = -1;
        $tampil = Ruang::orderBy('ruang', 'asc')->get();
            echo "<option value='" . $idx . "'>" . "- SEMUA -" . "</option>";
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->ruang . "</option>";
        }
    }
    function listjenispembayaran()
    {
        
            
        $idx1 = '-1';
       
        $tampil = Jenispembayaran::where('id','<>','0')
            // ->whereIn('id',$idjenispembayaran)
            ->get();

        // $tampil = Jenispembayaran::where('id','<>','0')->get();
            echo "<option value='" . $idx1 . "'>" . "- SEMUA -" . "</option>";
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>". $baris->jenispembayaran . "</option>";
        }
    }
    function listoperator()
    {
        $idx = -1;
        $tampil = Users::orderBy('name', 'asc')->get();
            echo "<option value='" . $idx . "'>" . "- SEMUA -" . "</option>";
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->name . "</option>";
        }

    }
    public function cekcustomer(Request $request)
    {
        
        $idcus1=$request['idcus1'];

        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $idruangx = session('idruang1');
        if($idruangx=='-1'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        
        $jmlx = Stok::select('idanggota')
            ->where('idanggota','=',$idcus1) 
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                ->orWhere('status','=','returbeli');
                }) 
            ->where('idruang','>=',$idruangawal) 
            ->where('idruang','<=',$idruangakhir) 
            ->where('tglstatus','>=',$tglawal) 
            ->where('tglstatus','<=',$tglakhir)
            ->count(); 

        $data = Satuan::limit(1)
                ->selectRaw($jmlx . ' as jmlx')                
                ->get();
         
        return json_encode(array('data' => $data));       
    }

    public function kirimsyarat(Request $request)
    {
        session([
            'idruang1' => $request['idruang1'],
            'tabstok1' => $request['tabstok1'],
            'tgltransaksi1' => $request['tgltransaksi1'],
            'tgltransaksi2' => $request['tgltransaksi2'],
            'idoperator1' => $request['idoperator1'],
            'idcustomer1' => $request['idcustomer1'],
            'idjenispembayaran1' => $request['idjenispembayaran1'],
            'idjenispenjualanutama1' => $request['idjenispenjualanutama1'],
            'idjenispenjualansub1' => $request['idjenispenjualansub1'],
        ]);
    }


}
