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

        $idsupplierx = session('idsupplier1');
        if($idsupplierx=='-1'||$idsupplierx==''){
            $idsupplierawal = 0;
            $idsupplierakhir = 999999;
        }else{
            $idsupplierawal = $idsupplierx;
            $idsupplierakhir = $idsupplierx;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
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
            ->where('idsupplier','>=',$idsupplierawal)
            ->where('idsupplier','<=',$idsupplierakhir)
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

        $idsupplierx = session('idsupplier1');
        if($idsupplierx=='-1'||$idsupplierx==''){
            $idsupplierawal = 0;
            $idsupplierakhir = 999999;
        }else{
            $idsupplierawal = $idsupplierx;
            $idsupplierakhir = $idsupplierx;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }

        $stok = Stok::select('idbarang')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idsupplier','>=',$idsupplierawal)
            ->where('idsupplier','<=',$idsupplierakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','masuk')
                                 ->orWhere('status','=','returjual');
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

                $idsupplierx = session('idsupplier1');
                if($idsupplierx=='-1'||$idsupplierx==''){
                    $idsupplierawal = 0;
                    $idsupplierakhir = 999999;
                }else{
                    $idsupplierawal = $idsupplierx;
                    $idsupplierakhir = $idsupplierx;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $qty = Stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('masuk');
                
                $total = Stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('hppmasuk');

                return $qty;
            })

            ->addColumn('harga', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idsupplierx = session('idsupplier1');
                if($idsupplierx=='-1'||$idsupplierx==''){
                    $idsupplierawal = 0;
                    $idsupplierakhir = 999999;
                }else{
                    $idsupplierawal = $idsupplierx;
                    $idsupplierakhir = $idsupplierx;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $qty = Stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('masuk');
                
                $total = Stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('hppmasuk');

                return number_format($total/$qty,0);
            })

            ->addColumn('total', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idsupplierx = session('idsupplier1');
                if($idsupplierx=='-1'||$idsupplierx==''){
                    $idsupplierawal = 0;
                    $idsupplierakhir = 999999;
                }else{
                    $idsupplierawal = $idsupplierx;
                    $idsupplierakhir = $idsupplierx;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $qty = Stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('masuk');
                
                $total = Stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('hppmasuk');
                return number_format($total,0);
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

                $idsupplierx = session('idsupplier1');
                if($idsupplierx=='-1'||$idsupplierx==''){
                    $idsupplierawal = 0;
                    $idsupplierakhir = 999999;
                }else{
                    $idsupplierawal = $idsupplierx;
                    $idsupplierakhir = $idsupplierx;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $jmlrecord = Stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
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
                'harga',
                'total',
                ])

            ->make(true);

            return $data;

    }

    public function showpembelianpersupplier()
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

        $idsupplierx = session('idsupplier1');
        if($idsupplierx=='-1'||$idsupplierx==''){
            $idsupplierawal = 0;
            $idsupplierakhir = 999999;
        }else{
            $idsupplierawal = $idsupplierx;
            $idsupplierakhir = $idsupplierx;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }

        $stok = Stok::select('idsupplier')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idsupplier','>=',$idsupplierawal)
            ->where('idsupplier','<=',$idsupplierakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','masuk')
                                 ->orWhere('status','=','returjual');
                })
            ->with('barang','seksi','ruang','jenispembayaran','anggota','supplier')
            ->groupBy('idsupplier')
            // ->orderBy('tglstatus','asc')
            // ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('supplier', function ($row) {
                return $row->idsupplier ? $row->supplier->supplier : '';
            })
            ->addColumn('kode', function ($row) {
                return $row->idsupplier ? $row->supplier->kode : '';
            })
            ->addColumn('alamat', function ($row) {
                return $row->idalamat ? $row->supplier->alamat : '';
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

                $idsupplierx = session('idsupplier1');
                if($idsupplierx=='-1'||$idsupplierx==''){
                    $idsupplierawal = 0;
                    $idsupplierakhir = 999999;
                }else{
                    $idsupplierawal = $idsupplierx;
                    $idsupplierakhir = $idsupplierx;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $qty = Stok::where('idsupplier','=',$row->idsupplier)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('masuk');
                
                $total = Stok::where('idsupplier','=',$row->idsupplier)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('hppmasuk');

                return $qty;
            })

            ->addColumn('harga', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idsupplierx = session('idsupplier1');
                if($idsupplierx=='-1'||$idsupplierx==''){
                    $idsupplierawal = 0;
                    $idsupplierakhir = 999999;
                }else{
                    $idsupplierawal = $idsupplierx;
                    $idsupplierakhir = $idsupplierx;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $qty = Stok::where('idsupplier','=',$row->idsupplier)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('masuk');
                
                $total = Stok::where('idsupplier','=',$row->idsupplier)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('hppmasuk');

                return number_format($total/$qty,0);
            })

            ->addColumn('total', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idsupplierx = session('idsupplier1');
                if($idsupplierx=='-1'||$idsupplierx==''){
                    $idsupplierawal = 0;
                    $idsupplierakhir = 999999;
                }else{
                    $idsupplierawal = $idsupplierx;
                    $idsupplierakhir = $idsupplierx;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $qty = Stok::where('idsupplier','=',$row->idsupplier)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('masuk');
                
                $total = Stok::where('idsupplier','=',$row->idsupplier)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('hppmasuk');
                return number_format($total,0);
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

                $idsupplierx = session('idsupplier1');
                if($idsupplierx=='-1'||$idsupplierx==''){
                    $idsupplierawal = 0;
                    $idsupplierakhir = 999999;
                }else{
                    $idsupplierawal = $idsupplierx;
                    $idsupplierakhir = $idsupplierx;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $jmlrecord = Stok::where('idsupplier','=',$row->idsupplier)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->count('idsupplier');
                
                return $jmlrecord;
            })

            
            ->rawColumns([
                'kode',
                'supplier',
                'alamat',
                'jmlrecord',
                'qty',
                'harga',
                'total',
                ])

            ->make(true);

            return $data;

    }

    public function showpembelianperfaktur()
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

        $idsupplierx = session('idsupplier1');
        if($idsupplierx=='-1'||$idsupplierx==''){
            $idsupplierawal = 0;
            $idsupplierakhir = 999999;
        }else{
            $idsupplierawal = $idsupplierx;
            $idsupplierakhir = $idsupplierx;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }

        $stok = Stok::select('nomorstatus')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idsupplier','>=',$idsupplierawal)
            ->where('idsupplier','<=',$idsupplierakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','masuk')
                                 ->orWhere('status','=','returjual');
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

                $idsupplierx = session('idsupplier1');
                if($idsupplierx=='-1'||$idsupplierx==''){
                    $idsupplierawal = 0;
                    $idsupplierakhir = 999999;
                }else{
                    $idsupplierawal = $idsupplierx;
                    $idsupplierakhir = $idsupplierx;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $qty = Stok::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('masuk');
                
                $total = Stok::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('hppmasuk');

                return $qty;
            })

            ->addColumn('harga', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idsupplierx = session('idsupplier1');
                if($idsupplierx=='-1'||$idsupplierx==''){
                    $idsupplierawal = 0;
                    $idsupplierakhir = 999999;
                }else{
                    $idsupplierawal = $idsupplierx;
                    $idsupplierakhir = $idsupplierx;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $qty = Stok::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('masuk');
                
                $total = Stok::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('hppmasuk');

                return number_format($total/$qty,0);
            })

            ->addColumn('total', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idsupplierx = session('idsupplier1');
                if($idsupplierx=='-1'||$idsupplierx==''){
                    $idsupplierawal = 0;
                    $idsupplierakhir = 999999;
                }else{
                    $idsupplierawal = $idsupplierx;
                    $idsupplierakhir = $idsupplierx;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $qty = Stok::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('masuk');
                
                $total = Stok::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('hppmasuk');
                return number_format($total,0);
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

                $idsupplierx = session('idsupplier1');
                if($idsupplierx=='-1'||$idsupplierx==''){
                    $idsupplierawal = 0;
                    $idsupplierakhir = 999999;
                }else{
                    $idsupplierawal = $idsupplierx;
                    $idsupplierakhir = $idsupplierx;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $jmlrecord = Stok::where('nomorstatus','=',$row->nomorstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->count('nomorstatus');
                
                return $jmlrecord;
            })

            
            ->rawColumns([
                'kode',
                'jmlrecord',
                'tglstatus',
                'qty',
                'harga',
                'total',
                ])

            ->make(true);

            return $data;

    }
   
    public function showpembelianperjenispembayaran()
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

        $idsupplierx = session('idsupplier1');
        if($idsupplierx=='-1'||$idsupplierx==''){
            $idsupplierawal = 0;
            $idsupplierakhir = 999999;
        }else{
            $idsupplierawal = $idsupplierx;
            $idsupplierakhir = $idsupplierx;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }

        $stok = Stok::select('idjenispembayaran')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idsupplier','>=',$idsupplierawal)
            ->where('idsupplier','<=',$idsupplierakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','masuk')
                                 ->orWhere('status','=','returjual');
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

                $idsupplierx = session('idsupplier1');
                if($idsupplierx=='-1'||$idsupplierx==''){
                    $idsupplierawal = 0;
                    $idsupplierakhir = 999999;
                }else{
                    $idsupplierawal = $idsupplierx;
                    $idsupplierakhir = $idsupplierx;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $qty = Stok::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('masuk');
                
                $total = Stok::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('hppmasuk');

                return $qty;
            })

            ->addColumn('harga', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idsupplierx = session('idsupplier1');
                if($idsupplierx=='-1'||$idsupplierx==''){
                    $idsupplierawal = 0;
                    $idsupplierakhir = 999999;
                }else{
                    $idsupplierawal = $idsupplierx;
                    $idsupplierakhir = $idsupplierx;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $qty = Stok::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('masuk');
                
                $total = Stok::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('hppmasuk');

                return number_format($total/$qty,0);
            })

            ->addColumn('total', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idsupplierx = session('idsupplier1');
                if($idsupplierx=='-1'||$idsupplierx==''){
                    $idsupplierawal = 0;
                    $idsupplierakhir = 999999;
                }else{
                    $idsupplierawal = $idsupplierx;
                    $idsupplierakhir = $idsupplierx;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $qty = Stok::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('masuk');
                
                $total = Stok::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('hppmasuk');
                return number_format($total,0);
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

                $idsupplierx = session('idsupplier1');
                if($idsupplierx=='-1'||$idsupplierx==''){
                    $idsupplierawal = 0;
                    $idsupplierakhir = 999999;
                }else{
                    $idsupplierawal = $idsupplierx;
                    $idsupplierakhir = $idsupplierx;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $jmlrecord = Stok::where('idjenispembayaran','=',$row->idjenispembayaran)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->count('idjenispembayaran');
                
                return $jmlrecord;
            })

            
            ->rawColumns([
                'kode',
                'jmlrecord',
                'jenispembayaran',
                'qty',
                'harga',
                'total',
                ])

            ->make(true);

            return $data;

    }

    public function showpembelianpertanggal()
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

        $idsupplierx = session('idsupplier1');
        if($idsupplierx=='-1'||$idsupplierx==''){
            $idsupplierawal = 0;
            $idsupplierakhir = 999999;
        }else{
            $idsupplierawal = $idsupplierx;
            $idsupplierakhir = $idsupplierx;
        }

        $idjenispembayaranx = session('idjenispembayaran1');
        if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
            $idjenispembayaranawal = 0;
            $idjenispembayaranakhir = 999999;
        }else{
            $idjenispembayaranawal = $idjenispembayaranx;
            $idjenispembayaranakhir = $idjenispembayaranx;
        }

        $stok = Stok::select('tglstatus')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idsupplier','>=',$idsupplierawal)
            ->where('idsupplier','<=',$idsupplierakhir)
            ->where('idjenispembayaran','>=',$idjenispembayaranawal)
            ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','masuk')
                                 ->orWhere('status','=','returjual');
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

                $idsupplierx = session('idsupplier1');
                if($idsupplierx=='-1'||$idsupplierx==''){
                    $idsupplierawal = 0;
                    $idsupplierakhir = 999999;
                }else{
                    $idsupplierawal = $idsupplierx;
                    $idsupplierakhir = $idsupplierx;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $qty = Stok::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('masuk');
                
                $total = Stok::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('hppmasuk');

                return $qty;
            })

            ->addColumn('harga', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idsupplierx = session('idsupplier1');
                if($idsupplierx=='-1'||$idsupplierx==''){
                    $idsupplierawal = 0;
                    $idsupplierakhir = 999999;
                }else{
                    $idsupplierawal = $idsupplierx;
                    $idsupplierakhir = $idsupplierx;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $qty = Stok::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('masuk');
                
                $total = Stok::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('hppmasuk');

                return number_format($total/$qty,0);
            })

            ->addColumn('total', function ($row) {
                $currentDate = date('Y-m-d');
                $tglawal = session('tgltransaksi1');
                if($tglawal==''||$tglawal=='0'){
                    $tglawal=$currentDate;
                }else{
                    $tglawal=session('tgltransaksi1');
                }
                $tglakhir = session('tgltransaksi2');
                if($tglakhir==''||$tglakhir=='0'){
                    $tglakhir=$currentDate;
                }else{
                    $tglakhir=session('tgltransaksi2');
                }

                $idruangx = session('idruang1');
                if($idruangx=='-1'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }

                $idsupplierx = session('idsupplier1');
                if($idsupplierx=='-1'||$idsupplierx==''){
                    $idsupplierawal = 0;
                    $idsupplierakhir = 999999;
                }else{
                    $idsupplierawal = $idsupplierx;
                    $idsupplierakhir = $idsupplierx;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $qty = Stok::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('masuk');
                
                $total = Stok::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->sum('hppmasuk');
                return number_format($total,0);
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

                $idsupplierx = session('idsupplier1');
                if($idsupplierx=='-1'||$idsupplierx==''){
                    $idsupplierawal = 0;
                    $idsupplierakhir = 999999;
                }else{
                    $idsupplierawal = $idsupplierx;
                    $idsupplierakhir = $idsupplierx;
                }

                $idjenispembayaranx = session('idjenispembayaran1');
                if($idjenispembayaranx=='-1'||$idjenispembayaranx==''){
                    $idjenispembayaranawal = 0;
                    $idjenispembayaranakhir = 999999;
                }else{
                    $idjenispembayaranawal = $idjenispembayaranx;
                    $idjenispembayaranakhir = $idjenispembayaranx;
                }

                $jmlrecord = Stok::where('tglstatus','=',$row->tglstatus)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('tglstatus','>=',$tglawal)
                    ->where('tglstatus','<=',$tglakhir)
                    ->where('idsupplier','>=',$idsupplierawal)
                    ->where('idsupplier','<=',$idsupplierakhir)
                    ->where('idjenispembayaran','>=',$idjenispembayaranawal)
                    ->where('idjenispembayaran','<=',$idjenispembayaranakhir)
                    ->where(function (Builder $query) {
                            return $query->where('status','=','masuk')
                                        ->orWhere('status','=','returjual');
                        })
                    ->count('tglstatus');
                
                return $jmlrecord;
            })

            
            ->rawColumns([
                'kode',
                'jmlrecord',
                'tglstatus',
                'qty',
                'harga',
                'total',
                ])

            ->make(true);

            return $data;

    }

    public function edit($id)
    {
        $data = Barangruang::where('id', '=', $id)->get();
        return json_encode(array('data' => $data));       
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

        // $tglawal = session('tgltransaksi1');
        // $tglakhir = session('tgltransaksi2');
        
        // $idruangx = session('idruang1');
        // if($idruangx=='-1'){
        //     $idruangawal = '0';
        //     $idruangakhir = '99999999';
        // }else{
        //     $idruangawal = $idruangx;
        //     $idruangakhir = $idruangx;
        // }

        // $idsupplier = Stok::select('idsupplier')
        //     ->where('status','=','masuk') 
        //     ->where('idruang','>=',$idruangawal) 
        //     ->where('idruang','<=',$idruangakhir)            
        //     ->where('tglstatus','>=',$tglawal) 
        //     ->where('tglstatus','<=',$tglakhir); 
       
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

        // $tglawal = session('tgltransaksi1');
        // $tglakhir = session('tgltransaksi2');
        
        // $idruangx = session('idruang1');
        // if($idruangx=='-1'){
        //     $idruangawal = '0';
        //     $idruangakhir = '99999999';
        // }else{
        //     $idruangawal = $idruangx;
        //     $idruangakhir = $idruangx;
        // }

        // $idsupplier = Stok::select('idsupplier')
        //     ->where('status','=','masuk') 
        //     ->where('idruang','>=',$idruangawal) 
        //     ->where('idruang','<=',$idruangakhir)
        //     ->where('tglstatus','>=',$tglawal) 
        //     ->where('tglstatus','<=',$tglakhir); 

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
        
        // $tglawal = session('tgltransaksi1');
        // $tglakhir = session('tgltransaksi2');
        
        // $idruangx = session('idruang1');
        // if($idruangx=='-1'){
        //     $idruangawal = '0';
        //     $idruangakhir = '99999999';
        // }else{
        //     $idruangawal = $idruangx;
        //     $idruangakhir = $idruangx;
        // }

        // $idjenispembayaran = Stok::select('idjenispembayaran')
        //     ->where('status','=','masuk') 
        //     ->where('idruang','>=',$idruangawal) 
        //     ->where('idruang','<=',$idruangakhir)            
        //     ->where('tglstatus','>=',$tglawal) 
        //     ->where('tglstatus','<=',$tglakhir); 
            
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

        $tglawal = session('tgltransaksi1');
        $tglakhir = session('tgltransaksi2');
        
        $idruangx = session('idruang1');
        if($idruangx=='-1'){
            $idruangawal = '0';
            $idruangakhir = '99999999';
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }
        
        $jmlx = Stok::select('idanggota')
            ->where('idanggota','=',$idcus1) 
            ->where('status','=','masuk') 
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
