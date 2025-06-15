<?php

namespace Modules\Pos01\Http\Controllers\laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DateTime;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Aplikasi;
use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Storage;
use App\Models\Menusub;
use Modules\Pos01\Models\Barang;
use Modules\Pos01\Models\Barangruang;
use Modules\Pos01\Models\Biaya;
use Modules\Pos01\Models\Bkeluar;
use Modules\Pos01\Models\Pendapatan;
use Modules\Pos01\Models\Ruang;
use Modules\Pos01\Models\Satuan;
use Modules\Pos01\Models\Stok;
use Modules\Pos01\Models\Stokfifo;
use Modules\Pos01\Models\Stoklifo;
use Modules\Pos01\Models\Stokmova;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\NumberFormat;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class LabarugiController extends Controller
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
        $remark = 'Halaman ini digunakan untuk menampilkan <b>Laba Rugi</b>.';
        $page = 'pos01::laporan.labarugi';
        $link = '/pos01/laporan/labarugi';
        $subtitle = 'Laporan';
        $caption = 'Laba Rugi';
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
    public function formatangka($angka)
    {
        if($angka<'0'){
            $x = '('.number_format(abs($angka),2).')';
        }else{
            $x = number_format($angka,2);
        }
        return $x;
    }     
    public function showlabarugisaja()
    {
        
        $idruang = session('idruang1');
        if($idruang=='0'||$idruang==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruang;
            $idruangakhir = $idruang;
        }
        $tglawal = session('tgltransaksi1');
        if($tglawal==''){
            $tglawal=session('memtanggal');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''){
            $tglakhir=session('memtanggal');
        }

       $ppnkeluar = Stok::select('ppnkeluar')
            ->where('tglstatus','>=',$tglawal)        
            ->where('tglstatus','<=',$tglakhir)        
            ->where('idruang','>=',$idruangawal)        
            ->where('idruang','<=',$idruangakhir)        
            ->sum('ppnkeluar');
        $ppnjualx = $ppnkeluar;
        $ppnjual = $this->formatangka($ppnjualx); 

        $diskonkeluar = Stok::select('diskonkeluar')
            ->where('tglstatus','>=',$tglawal)        
            ->where('tglstatus','<=',$tglakhir)        
            ->where('idruang','>=',$idruangawal)        
            ->where('idruang','<=',$idruangakhir)        
            ->sum('diskonkeluar');
        $discountjualx = $diskonkeluar * -1;
        $discountjual = $this->formatangka($discountjualx);  
        
        $hppj = Stok::select('hppj')
            ->where('tglstatus','>=',$tglawal)        
            ->where('tglstatus','<=',$tglakhir)        
            ->where('idruang','>=',$idruangawal)        
            ->where('idruang','<=',$idruangakhir)        
            ->sum('hppj');
        $pendapatanjualx = $hppj;
        $pendapatanjual = $this->formatangka($pendapatanjualx);  
        
        $hppkeluar = Stok::select('hppkeluar')
            ->where('tglstatus','>=',$tglawal)        
            ->where('tglstatus','<=',$tglakhir)        
            ->where('idruang','>=',$idruangawal)        
            ->where('idruang','<=',$idruangakhir)        
            ->sum('hppkeluar');
        $hppx = $hppkeluar * -1;
        $hpp = $this->formatangka($hppx);  
        
        $labakotorx = $ppnjualx + $discountjualx + $pendapatanjualx + $hppx;
        $labakotor = $this->formatangka($labakotorx);  
        
        $totalbeli = Biaya::select('totalbeli')
            ->where('idjenisbiaya','=','1')        
            ->where('tgltransaksi','>=',$tglawal)        
            ->where('tgltransaksi','<=',$tglakhir)        
            ->where('idruang','>=',$idruangawal)        
            ->where('idruang','<=',$idruangakhir)        
            ->sum('totalbeli');
        $pengeluaranbiayax = $totalbeli;
        $pengeluaranbiaya = $this->formatangka($pengeluaranbiayax);  
        
        $bebanusahax = ($pengeluaranbiayax)*-1;
        $bebanusaha = $this->formatangka($bebanusahax);  
        
        $labausahax = $labakotorx + $bebanusahax;
        $labausaha = $this->formatangka($labausahax);  
        
        $totaljual = Pendapatan::select('totaljual')
            ->where('idjenisbiaya','=','1')        
            ->where('tgltransaksi','>=',$tglawal)        
            ->where('tgltransaksi','<=',$tglakhir)        
            ->where('idruang','>=',$idruangawal)        
            ->where('idruang','<=',$idruangakhir)        
            ->sum('totaljual');
        $pendapatanlainx = $totaljual;
        $pendapatanlain = $this->formatangka($pendapatanlainx);  
        
        $pendapatandiluarusahax = ($pendapatanlainx);
        $pendapatandiluarusaha = $this->formatangka($pendapatandiluarusahax);  
        
        $lababersihx = $labausahax + $pendapatandiluarusahax;
        $lababersih = $this->formatangka($lababersihx);  

        $data = Satuan::limit(1)
                ->select('*')
                ->selectRaw("'$ppnjual'" . " as ppnjual")  
                ->selectRaw("'$discountjual'" . " as discountjual")  
                ->selectRaw("'$pendapatanjual'" . " as pendapatanjual")  
                ->selectRaw("'$hpp'" . " as hpp")  
                ->selectRaw("'$labakotor'" . " as labakotor")  
                ->selectRaw("'$pengeluaranbiaya'" . " as pengeluaranbiaya")  
                ->selectRaw("'$bebanusaha'" . " as bebanusaha")  
                ->selectRaw("'$labausaha'" . " as labausaha")  
                ->selectRaw("'$pendapatanlain'" . " as pendapatanlain")  
                ->selectRaw("'$pendapatandiluarusaha'" . " as pendapatandiluarusaha")  
                ->selectRaw("'$lababersih'" . " as lababersih")  
                ->get();
         
        return json_encode(array('data' => $data));        
    }

    public function showstokmasuk()
    {
        
        $idruangx = session('idruang1');
        if($idruangx=='0'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

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

        $stok = Stok::select('*')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)            
            ->where(function (Builder $query) {
                    return $query->where('status','=','masuk')
                                 ->orWhere('status','=','mutmasuk')
                                 ->orWhere('status','=','retmasuk')
                                 ->orWhere('status','=','konmasuk')
                                 ->orWhere('status','=','kormasuk');
                })
            ->with('barang','seksi','ruang')
            ->orderBy('tglstatus','asc')
            ->orderBy('id','asc')
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
            ->addColumn('satuan', function ($row) {
                return $row->idbarang ? $row->barang->satuan->kode : '';
            })
            ->addColumn('hbsmasuk', function ($row) {
                return $row->hbsmasuk ? Number_Format($row->hbsmasuk,0) : '';
            })
            ->addColumn('hppmasuk', function ($row) {
                return $row->hppmasuk ? Number_Format($row->hppmasuk,0) : '';
            })

            ->make(true);

            return $data;

    }
    public function showstokmasukfifo()
    {
        $idruangx = session('idruang1');
        if($idruangx=='0'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

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

        $stok = Stokfifo::select('*')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','masuk')
                                 ->orWhere('status','=','mutmasuk')
                                 ->orWhere('status','=','retmasuk')
                                 ->orWhere('status','=','konmasuk')
                                 ->orWhere('status','=','kormasuk');
                })
            ->where('kodepokok','=','2')
            ->with('barang','seksi','ruang')
            ->orderBy('tglstatus','asc')
            ->orderBy('id','asc')
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
            ->addColumn('satuan', function ($row) {
                return $row->idbarang ? $row->barang->satuan->kode : '';
            })
            ->addColumn('hbsmasuk', function ($row) {
                return $row->hbsmasuk ? Number_Format($row->hbsmasuk,0) : '';
            })
            ->addColumn('hppmasuk', function ($row) {
                return $row->hppmasuk ? Number_Format($row->hppmasuk,0) : '';
            })

            ->make(true);

            return $data;
    }

    public function showstokmasuklifo()
    {
        $idruangx = session('idruang1');
        if($idruangx=='0'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

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

        $stok = Stoklifo::select('*')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','masuk')
                                 ->orWhere('status','=','mutmasuk')
                                 ->orWhere('status','=','retmasuk')
                                 ->orWhere('status','=','konmasuk')
                                 ->orWhere('status','=','kormasuk');
                })
            ->where('kodepokok','=','2')
            ->with('barang','seksi','ruang')
            ->orderBy('tglstatus','asc')
            ->orderBy('id','asc')
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
            ->addColumn('satuan', function ($row) {
                return $row->idbarang ? $row->barang->satuan->kode : '';
            })
            ->addColumn('hbsmasuk', function ($row) {
                return $row->hbsmasuk ? Number_Format($row->hbsmasuk,0) : '';
            })
            ->addColumn('hppmasuk', function ($row) {
                return $row->hppmasuk ? Number_Format($row->hppmasuk,0) : '';
            })

            ->make(true);

            return $data;
    }

    public function showstokmasukmova()
    {
        $idruangx = session('idruang1');
        if($idruangx=='0'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

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

        $stok = Stokmova::select('*')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','masuk')
                                 ->orWhere('status','=','mutmasuk')
                                 ->orWhere('status','=','retmasuk')
                                 ->orWhere('status','=','konmasuk')
                                 ->orWhere('status','=','kormasuk');
                })
            ->with('barang','seksi','ruang')
            ->orderBy('tglstatus','asc')
            ->orderBy('id','asc')
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
            ->addColumn('satuan', function ($row) {
                return $row->idbarang ? $row->barang->satuan->kode : '';
            })
            ->addColumn('hbsmasuk', function ($row) {
                return $row->hbsmasuk ? Number_Format($row->hbsmasuk,0) : '';
            })
            ->addColumn('hppmasuk', function ($row) {
                return $row->hppmasuk ? Number_Format($row->hppmasuk,0) : '';
            })

            ->make(true);

            return $data;

    }
    public function showstokkeluar()
    {
        
        $idruangx = session('idruang1');
        if($idruangx=='0'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

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

        $stok = Stok::select('*')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                 ->orWhere('status','=','mutkeluar')
                                 ->orWhere('status','=','retkeluar')
                                 ->orWhere('status','=','konkeluar')
                                 ->orWhere('status','=','korkeluar');
                })
            ->with('barang','seksi','ruang')
            ->orderBy('tglstatus','asc')
            ->orderBy('id','asc')
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
            ->addColumn('satuan', function ($row) {
                return $row->idbarang ? $row->barang->satuan->kode : '';
            })
            ->addColumn('hbsmasuk', function ($row) {
                return $row->hbsmasuk ? Number_Format($row->hbsmasuk,0) : '';
            })
            ->addColumn('hppmasuk', function ($row) {
                return $row->hppmasuk ? Number_Format($row->hppmasuk,0) : '';
            })
            ->addColumn('hbskeluar', function ($row) {
                return $row->hbskeluar ? Number_Format($row->hbskeluar,0) : '';
            })
            ->addColumn('hppkeluar', function ($row) {
                return $row->hppkeluar ? Number_Format($row->hppkeluar,0) : '';
            })

            ->make(true);

            return $data;

    }
    
    public function showstokkeluarfifo()
    {
        $idruangx = session('idruang1');
        if($idruangx=='0'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

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

        $stok = Stokfifo::select('*')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                 ->orWhere('status','=','mutkeluar')
                                 ->orWhere('status','=','retkeluar')
                                 ->orWhere('status','=','konkeluar')
                                 ->orWhere('status','=','korkeluar');
                })
            ->where('kodepokok','=','2')
            ->with('barang','seksi','ruang')
            ->orderBy('tglstatus','asc')
            ->orderBy('id','asc')
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
            ->addColumn('satuan', function ($row) {
                return $row->idbarang ? $row->barang->satuan->kode : '';
            })
            ->addColumn('hbsmasuk', function ($row) {
                return $row->hbsmasuk ? Number_Format($row->hbsmasuk,0) : '';
            })
            ->addColumn('hppmasuk', function ($row) {
                return $row->hppmasuk ? Number_Format($row->hppmasuk,0) : '';
            })
            ->addColumn('hbskeluar', function ($row) {
                return $row->hbskeluar ? Number_Format($row->hbskeluar,0) : '';
            })
            ->addColumn('hppkeluar', function ($row) {
                return $row->hppkeluar ? Number_Format($row->hppkeluar,0) : '';
            })

            ->make(true);

            return $data;

    }
  
    public function showstokkeluarmova()
    {
        $idruangx = session('idruang1');
        if($idruangx=='0'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

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

        $stok = Stokmova::select('*')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                 ->orWhere('status','=','mutkeluar')
                                 ->orWhere('status','=','retkeluar')
                                 ->orWhere('status','=','konkeluar')
                                 ->orWhere('status','=','korkeluar');
                })
            ->with('barang','seksi','ruang')
            ->orderBy('tglstatus','asc')
            ->orderBy('id','asc')
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
            ->addColumn('satuan', function ($row) {
                return $row->idbarang ? $row->barang->satuan->kode : '';
            })
            ->addColumn('hbsmasuk', function ($row) {
                return $row->hbsmasuk ? Number_Format($row->hbsmasuk,0) : '';
            })
            ->addColumn('hppmasuk', function ($row) {
                return $row->hppmasuk ? Number_Format($row->hppmasuk,0) : '';
            })
            ->addColumn('hbskeluar', function ($row) {
                return $row->hbskeluar ? Number_Format($row->hbskeluar,0) : '';
            })
            ->addColumn('hppkeluar', function ($row) {
                return $row->hppkeluar ? Number_Format($row->hppkeluar,0) : '';
            })

            ->make(true);

            return $data;
    }

    public function showstokkeluarlifo()
    {
        $idruangx = session('idruang1');
        if($idruangx=='0'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

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

        $stok = Stoklifo::select('*')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where(function (Builder $query) {
                    return $query->where('status','=','keluar')
                                 ->orWhere('status','=','mutkeluar')
                                 ->orWhere('status','=','retkeluar')
                                 ->orWhere('status','=','konkeluar')
                                 ->orWhere('status','=','korkeluar');
                })
            ->where('kodepokok','=','2')
            ->with('barang','seksi','ruang')
            ->orderBy('tglstatus','asc')
            ->orderBy('id','asc')
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
            ->addColumn('satuan', function ($row) {
                return $row->idbarang ? $row->barang->satuan->kode : '';
            })
            ->addColumn('hbsmasuk', function ($row) {
                return $row->hbsmasuk ? Number_Format($row->hbsmasuk,0) : '';
            })
            ->addColumn('hppmasuk', function ($row) {
                return $row->hppmasuk ? Number_Format($row->hppmasuk,0) : '';
            })
            ->addColumn('hbskeluar', function ($row) {
                return $row->hbskeluar ? Number_Format($row->hbskeluar,0) : '';
            })
            ->addColumn('hppkeluar', function ($row) {
                return $row->hppkeluar ? Number_Format($row->hppkeluar,0) : '';
            })

            ->make(true);

            return $data;
    }

    public function showstokrekap()
    {
        
        $idruangx = session('idruang1');
        if($idruangx=='0'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

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

        $stok = Stok::select('*')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            // ->where(function (Builder $query) {
            //         return $query->where('status','=','keluar')
            //                      ->orWhere('status','=','mutkeluar')
            //                      ->orWhere('status','=','retkeluar')
            //                      ->orWhere('status','=','konkeluar')
            //                      ->orWhere('status','=','korkeluar');
            //     })
            ->with('barang','seksi','ruang')
            ->orderBy('tglstatus','asc')
            ->orderBy('id','asc')
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
            ->addColumn('satuan', function ($row) {
                return $row->idbarang ? $row->barang->satuan->kode : '';
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

            ->make(true);

            return $data;

    }

    public function showstokrekapfifo()
    {
        $idruangx = session('idruang1');
        if($idruangx=='0'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

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

        $stok = Stokfifo::select('*')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            // ->where(function (Builder $query) {
            //         return $query->where('status','=','keluar')
            //                      ->orWhere('status','=','mutkeluar')
            //                      ->orWhere('status','=','retkeluar')
            //                      ->orWhere('status','=','konkeluar')
            //                      ->orWhere('status','=','korkeluar');
            //     })
            ->where('kodepokok','=','2')
            ->with('barang','seksi','ruang')
            ->orderBy('tglstatus','asc')
            ->orderBy('id','asc')
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
            ->addColumn('satuan', function ($row) {
                return $row->idbarang ? $row->barang->satuan->kode : '';
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

            ->make(true);

            return $data;

    }

    public function showstokrekapmova()
    {
        $idruangx = session('idruang1');
        if($idruangx=='0'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

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

        $stok = Stokmova::select('*')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            // ->where(function (Builder $query) {
            //         return $query->where('status','=','keluar')
            //                      ->orWhere('status','=','mutkeluar')
            //                      ->orWhere('status','=','retkeluar')
            //                      ->orWhere('status','=','konkeluar')
            //                      ->orWhere('status','=','korkeluar');
            //     })
            ->with('barang','seksi','ruang')
            ->orderBy('tglstatus','asc')
            ->orderBy('id','asc')
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
            ->addColumn('satuan', function ($row) {
                return $row->idbarang ? $row->barang->satuan->kode : '';
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

            ->make(true);

            return $data;
    }

    public function showstokrekaplifo()
    {
        $idruangx = session('idruang1');
        if($idruangx=='0'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

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

        $stok = Stoklifo::select('*')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            // ->where(function (Builder $query) {
            //         return $query->where('status','=','keluar')
            //                      ->orWhere('status','=','mutkeluar')
            //                      ->orWhere('status','=','retkeluar')
            //                      ->orWhere('status','=','konkeluar')
            //                      ->orWhere('status','=','korkeluar');
            //     })
            ->where('kodepokok','=','2')
            ->with('barang','seksi','ruang')
            ->orderBy('tglstatus','asc')
            ->orderBy('id','asc')
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
            ->addColumn('satuan', function ($row) {
                return $row->idbarang ? $row->barang->satuan->kode : '';
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


    public function showstokbarangx()
    {
        
        $idruangx = session('idruang1');
        $idbarang = Barangruang::select('idbarang')
            ->where('idruang','=',$idruangx);
        $barang = Barang::whereNotIn('id',$idbarang)
            ->orderBy('nabara', 'asc')
            ->with(['kategori','satuan'])->get();
        $datax = DataTables::of($barang                          
            );

        $data = $datax
            ->addIndexColumn()

            ->addColumn('kategori', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->idkategori ? $row->kategori->kategori : '') .'" class="item_kategori " data1="' . $row->id . '" data2="'. $row->barcode. '" data3="'. $row->nabara. '" data4="'. $row->kategori->kategori.'" data5="'. $row->satuan->satuan.'">'.($row->idkategori ? $row->kategori->kategori : "").'</a>';
            })
            ->addColumn('satuan', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->idsatuan ? $row->satuan->satuan : '') .'" class="item_satuan " data1="' . $row->id . '" data2="'. $row->barcode. '" data3="'. $row->nabara. '" data4="'. $row->kategori->kategori.'" data5="'. $row->satuan->satuan.'">'.($row->idsatuan ? $row->satuan->satuan : "").'</a>';
            })
            ->addColumn('kode', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->kode ? $row->kode : '') .'" class="item_kode " data1="' . $row->id . '" data2="'. $row->barcode. '" data3="'. $row->nabara. '" data4="'. $row->kategori->kategori.'" data5="'. $row->satuan->satuan.'">'.($row->kode ? $row->kode : "").'</a>';
            })
            ->addColumn('barcode', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->barcode ? $row->barcode : '') .'" class="item_barcode " data1="' . $row->id . '" data2="'. $row->barcode. '" data3="'. $row->nabara. '" data4="'. $row->kategori->kategori.'" data5="'. $row->satuan->satuan.'">'.($row->barcode ? $row->barcode : "").'</a>';
            })
            ->addColumn('nabara', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->nabara ? $row->nabara : '') .'" class="item_barcode " data1="' . $row->id . '" data2="'. $row->barcode. '" data3="'. $row->nabara. '" data4="'. $row->kategori->kategori.'" data5="'. $row->satuan->satuan.'">'.($row->nabara ? $row->nabara : "").'</a>';
            })
            ->addColumn('hbs', function ($row) {
                return $row->hbs ? number_format($row->hbs,0) : '';
            })

            ->addColumn('hjs', function ($row) {
                return $row->hjs ? number_format($row->hjs,0) : '';
            })

            ->addColumn('image', function ($row) {
                $imagex = '<img class="mb-4" style="width: 100%" src="'. route('front.index') . '/storage/'. $row->image.'">';
                return '<a href="#" style="color: white;" title="'. ($row->nabara ? $row->nabara : '') .'" class="item_image " data1="' . $row->id . '" data2="'. $row->barcode. '" data3="'. $row->nabara. '" data4="'. $row->kategori->kategori.'" data5="'. $row->satuan->satuan.'">'.($row->image ? $imagex : "") .'</a>';
            })
           
            ->addColumn('action', function ($row) {
                return '<a href="#" title="Edit Data" class="btn btn-success btn-xs item_edit" data="' . $row->id . '" data2="'. $row->barcode.'" data3="'. $row->nabara.'" data4="'. $row->nabara.'"><i style="font-size:18px" class="fa">&#xf044;</i></a> ' .
                       '<a href="#" title="Hapus Data" class="btn btn-danger btn-xs item_hapus" data="' . $row->id . '" data2="'. $row->barcode.'" data3="'. $row->nabara.'" data4="'. $row->nabara.'"><i style="font-size:18px" class="fa">&#xf00d;</i></a>';
            })
            
            ->rawColumns([
                'kategori',
                'satuan',
                'kode',
                'barcode',
                'nabara',
                'hbs',
                'hjs',
                'image',
                'action'])


            ->make(true);

            return $data;

    }
    function listbarang()
    {
        $idruangx = session('idruang1');
        $idbarang = Barangruang::select('idbarang')
            ->where('idruang','=',$idruangx);
        $tampil = Barang::whereNotIn('id',$idbarang)
            ->orderBy('nabara', 'asc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->nabara . "</option>";
        }
    }
    function listruang()
    {
        $idx = 0;
        $tampil = Ruang::orderBy('ruang', 'asc')->get();
            echo "<option value='" . $idx . "'>" . "- SEMUA -" . "</option>";
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->ruang . "</option>";
        }
    }

    public function kirimsyarat(Request $request)
    {
        session([
            'idruang1' => $request['idruang1'],
            'tablabarugi1' => $request['tablabarugi1'],
            'tgltransaksi1' => $request['tgltransaksi1'],
            'tgltransaksi2' => $request['tgltransaksi2'],
        ]);
    }


}
