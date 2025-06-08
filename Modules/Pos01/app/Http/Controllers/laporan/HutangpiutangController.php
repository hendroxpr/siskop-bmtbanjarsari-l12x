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
use Modules\Pos01\Models\Hutang;
use Modules\Pos01\Models\Ruang;
use Modules\Pos01\Models\Stok;
use Modules\Pos01\Models\Stokfifo;
use Modules\Pos01\Models\Stoklifo;
use Modules\Pos01\Models\Stokmova;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class HutangpiutangController extends Controller
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
        $remark = 'Halaman ini digunakan untuk menampilkan <b>Hutang/Piutang</b>.';
        $page = 'pos01::laporan.hutangpiutang';
        $link = '/pos01/laporan/hutangpiutang';
        $subtitle = 'Laporan';
        $caption = 'Hutang/Piutang';
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
    public function showhutangpiutangcustomer()
    {
        
        $hutang = Hutang::select('*')
            ->where('kodepokok','=','1')
            ->where('status','=','hutangcus')
            ->with('anggota','supplier')
            ->get();
        $datax = DataTables::of($hutang                          
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
                return $row->idanggota ? $row->anggota->lembaga->lembaga : '';
            })
            ->addColumn('xangsuran', function ($row) {
                return $row->angsuranke.'/'.$row->xangsuran;
            })
            ->addColumn('nilaiangsuran', function ($row) {
                return number_format($row->asli/$row->xangsuran,0);
            })
            ->addColumn('asli', function ($row) {
                return number_format($row->asli,0);
            })
            ->addColumn('sudahbayar', function ($row) {
                $jml = Hutang::where('kodepokok','=','2')
                    ->where('nomorstatusasal','=',$row->nomorstatus)
                    ->where('status','=','bayarcus')
                    ->with('anggota','supplier')
                    ->orderBy('id','desc')
                    ->count();
                if($jml<>'0'){
                    $hutang = Hutang::select('*')->limit(1)
                        ->where('kodepokok','=','2')
                        ->where('nomorstatusasal','=',$row->nomorstatus)
                        ->where('status','=','bayarcus')
                        ->with('anggota','supplier')
                        ->orderBy('id','desc')
                        ->get();
                        foreach ($hutang as $baris) {
                            $saldo = $baris->akhir;
                        }
                }else{
                    $saldo=$row->asli;
                } 

                $sudahbayar=$row->asli-$saldo;
                return number_format($sudahbayar,0);
            })
            ->addColumn('saldo', function ($row) {
                $jml = Hutang::where('kodepokok','=','2')
                    ->where('nomorstatusasal','=',$row->nomorstatus)
                    ->where('status','=','bayarcus')
                    ->with('anggota','supplier')
                    ->orderBy('id','desc')
                    ->count();
                if($jml<>'0'){
                    $hutang = Hutang::select('*')->limit(1)
                        ->where('kodepokok','=','2')
                        ->where('nomorstatusasal','=',$row->nomorstatus)
                        ->where('status','=','bayarcus')
                        ->with('anggota','supplier')
                        ->orderBy('id','desc')
                        ->get();
                        foreach ($hutang as $baris) {
                            $saldo = $baris->akhir;
                        }
                }else{
                    $saldo=$row->asli;
                } 

                return number_format($saldo,0);
            })
            
                        
            ->rawColumns([
                'xangsuran',
                'nilaiangsuran',
                'sudahbayar',
                'saldo',
                'qty',
                'satuan',
                'totalhpp',
                ])

            ->make(true);

            return $data;

    }
    public function showhutangpiutangsupplier()
    {
        
        $hutang = Hutang::select('*')
            ->where('kodepokok','=','1')
            ->where('status','=','hutangsup')
            ->with('anggota','supplier')
            ->get();
        $datax = DataTables::of($hutang                          
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
                return $row->idsupplier ? $row->supplier->alamat : '';
            })
            ->addColumn('xangsuran', function ($row) {
                return $row->angsuranke.'/'.$row->xangsuran;
            })
            ->addColumn('nilaiangsuran', function ($row) {
                return number_format($row->asli/$row->xangsuran,0);
            })
            ->addColumn('asli', function ($row) {
                return number_format($row->asli,0);
            })
            ->addColumn('sudahbayar', function ($row) {
                $jml = Hutang::where('kodepokok','=','2')
                    ->where('nomorstatusasal','=',$row->nomorstatus)
                    ->where('status','=','bayarsup')
                    ->with('anggota','supplier')
                    ->orderBy('id','desc')
                    ->count();
                if($jml<>'0'){
                    $hutang = Hutang::select('*')->limit(1)
                        ->where('kodepokok','=','2')
                        ->where('nomorstatusasal','=',$row->nomorstatus)
                        ->where('status','=','bayarsup')
                        ->with('anggota','supplier')
                        ->orderBy('id','desc')
                        ->get();
                        foreach ($hutang as $baris) {
                            $saldo = $baris->akhir;
                        }
                }else{
                    $saldo=$row->asli;
                } 
                
                $sudahbayar=$row->asli-$saldo;
                return number_format($sudahbayar,0);
            })
            ->addColumn('saldo', function ($row) {
                $jml = Hutang::where('kodepokok','=','2')
                    ->where('nomorstatusasal','=',$row->nomorstatus)
                    ->where('status','=','bayarsup')
                    ->with('anggota','supplier')
                    ->orderBy('id','desc')
                    ->count();
                if($jml<>'0'){
                    $hutang = Hutang::select('*')->limit(1)
                        ->where('kodepokok','=','2')
                        ->where('nomorstatusasal','=',$row->nomorstatus)
                        ->where('status','=','bayarsup')
                        ->with('anggota','supplier')
                        ->orderBy('id','desc')
                        ->get();
                        foreach ($hutang as $baris) {
                            $saldo = $baris->akhir;
                        }
                }else{
                    $saldo=$row->asli;
                } 

                return number_format($saldo,0);
            })
            
                        
            ->rawColumns([
                'xangsuran',
                'nilaiangsuran',
                'sudahbayar',
                'saldo',
                'qty',
                'satuan',
                'totalhpp',
                ])

            ->make(true);

            return $data;

    }

    public function showstoklifo()
    {
        $idruangx = session('idruang1');
        if($idruangx=='0'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }
        $barangruang = Barangruang::select('*')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->with('barang','seksi','ruang')
            ->get();
        $datax = DataTables::of($barangruang                          
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
            ->addColumn('hbs', function ($row) {
                $idruangx = session('idruang1');
                if($idruangx=='0'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }
                $jml = Stoklifo::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('kodepokok','=','2')
                    ->count();
                if($jml<>0){
                    $tampil = Stoklifo::limit('1')->where('idbarang','=',$row->idbarang)
                        ->where('idruang','>=',$idruangawal)
                        ->where('idruang','<=',$idruangakhir)
                        ->where('kodepokok','=','2')
                        ->orderBy('id','desc')
                        ->get();
                        foreach ($tampil as $baris) {
                            $hbsx = $baris->hbsakhir;
                        }
                }else{
                    $hbsx = $row->idbarang ? $row->barang->hbs : '0';
                }
                return number_format($hbsx,0);
            })
            ->addColumn('qty', function ($row) {
                $idruangx = session('idruang1');
                if($idruangx=='0'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }
                $jml = Stoklifo::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('kodepokok','=','2')
                    ->count();
                if($jml<>0){
                    $tampil = Stoklifo::limit('1')->where('idbarang','=',$row->idbarang)
                        ->where('idruang','>=',$idruangawal)
                        ->where('idruang','<=',$idruangakhir)
                        ->where('kodepokok','=','2')
                        ->orderBy('id','desc')
                        ->get();
                        foreach ($tampil as $baris) {
                            $qtyx = $baris->akhir;
                        }
                }else{
                    $qtyx = $row->qty;
                }
                return number_format($qtyx,0);
            })
            ->addColumn('totalhpp', function ($row) {
                $idruangx = session('idruang1');
                if($idruangx=='0'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }
                $jml = Stoklifo::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->where('kodepokok','=','2')
                    ->count();
                if($jml<>0){
                    $tampil = Stoklifo::limit('1')->where('idbarang','=',$row->idbarang)
                        ->where('idruang','>=',$idruangawal)
                        ->where('idruang','<=',$idruangakhir)
                        ->where('kodepokok','=','2')
                        ->orderBy('id','desc')
                        ->get();
                        foreach ($tampil as $baris) {
                            $totalhppx = $baris->hppakhir;
                        }
                }else{
                    $totalhppx = $row->qty * $row->barang->hbs;
                }
                return number_format($totalhppx,0);
            })
                        
            ->rawColumns([
                'hbs',
                'qty',
                'satuan',
                'totalhpp',
                ])

            ->make(true);

            return $data;
    }

    public function showstokmova()
    {
        $idruangx = session('idruang1');
        if($idruangx=='0'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }
        $barangruang = Barangruang::select('*')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->with('barang','seksi','ruang')
            ->get();
        $datax = DataTables::of($barangruang                          
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
            ->addColumn('hbs', function ($row) {
                $idruangx = session('idruang1');
                if($idruangx=='0'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }
                $jml = Stokmova::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->count();
                if($jml<>0){
                    $tampil = Stokmova::limit('1')->where('idbarang','=',$row->idbarang)
                        ->where('idruang','>=',$idruangawal)
                        ->where('idruang','<=',$idruangakhir)
                        ->orderBy('id','desc')
                        ->get();
                        foreach ($tampil as $baris) {
                            $hbsx = $baris->hbsakhir;
                        }
                }else{
                    $hbsx = $row->idbarang ? $row->barang->hbs : '0';
                }
                return number_format($hbsx,0);
            })
            ->addColumn('qty', function ($row) {
                $idruangx = session('idruang1');
                if($idruangx=='0'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }
                $jml = Stokmova::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->count();
                if($jml<>0){
                    $tampil = Stokmova::limit('1')->where('idbarang','=',$row->idbarang)
                        ->where('idruang','>=',$idruangawal)
                        ->where('idruang','<=',$idruangakhir)
                        ->orderBy('id','desc')
                        ->get();
                        foreach ($tampil as $baris) {
                            $qtyx = $baris->akhir;
                        }
                }else{
                    $qtyx = $row->qty;
                }
                return number_format($qtyx,0);
            })
            ->addColumn('totalhpp', function ($row) {
                $idruangx = session('idruang1');
                if($idruangx=='0'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }
                $jml = Stokmova::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->count();
                if($jml<>0){
                    $tampil = Stokmova::limit('1')->where('idbarang','=',$row->idbarang)
                        ->where('idruang','>=',$idruangawal)
                        ->where('idruang','<=',$idruangakhir)
                        ->orderBy('id','desc')
                        ->get();
                        foreach ($tampil as $baris) {
                            $totalhppx = $baris->hppakhir;
                        }
                }else{
                    $totalhppx = $row->qty * $row->barang->hbs;
                }
                return number_format($totalhppx,0);
            })
                        
            ->rawColumns([
                'hbs',
                'qty',
                'satuan',
                'totalhpp',
                ])

            ->make(true);

            return $data;

    }

    public function showstokexpired()
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
        $tgltransaksix = session('tgltransaksi1');
        if($tgltransaksix==''||$tgltransaksix=='0'){
            $tgltransaksi=$currentDate;
        }else{
            $tgltransaksi=$tgltransaksix;
        }
        
        $idbarang = Barang::select('id')
            ->where('expired','<=',$tgltransaksi)
            ->whereNotNull('expired');

        $barangruang = Barangruang::select('*')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->whereIn('idbarang',$idbarang)
            ->with('barang','seksi','ruang')
            ->get();
        $datax = DataTables::of($barangruang                          
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
            ->addColumn('expired', function ($row) {
                return $row->idbarang ? $row->barang->expired : '';
            })
            ->addColumn('hbs', function ($row) {
                $idruangx = session('idruang1');
                if($idruangx=='0'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }
                $jml = stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->count();
                if($jml<>0){
                    $tampil = stok::limit('1')->where('idbarang','=',$row->idbarang)
                        ->where('idruang','>=',$idruangawal)
                        ->where('idruang','<=',$idruangakhir)
                        ->orderBy('id','desc')
                        ->get();
                        foreach ($tampil as $baris) {
                            $hbsx = $baris->hbsakhir;
                        }
                }else{
                    $hbsx = $row->idbarang ? $row->barang->hbs : '0';
                }
                return number_format($hbsx,0);
            })
            ->addColumn('qty', function ($row) {
                $idruangx = session('idruang1');
                if($idruangx=='0'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }
                $jml = stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->count();
                if($jml<>0){
                    $tampil = stok::limit('1')->where('idbarang','=',$row->idbarang)
                        ->where('idruang','>=',$idruangawal)
                        ->where('idruang','<=',$idruangakhir)
                        ->orderBy('id','desc')
                        ->get();
                        foreach ($tampil as $baris) {
                            $qtyx = $baris->akhir;
                        }
                }else{
                    $qtyx = $row->qty;
                }
                return number_format($qtyx,0);
            })
            ->addColumn('totalhpp', function ($row) {
                $idruangx = session('idruang1');
                if($idruangx=='0'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }
                $jml = stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->count();
                if($jml<>0){
                    $tampil = stok::limit('1')->where('idbarang','=',$row->idbarang)
                        ->where('idruang','>=',$idruangawal)
                        ->where('idruang','<=',$idruangakhir)
                        ->orderBy('id','desc')
                        ->get();
                        foreach ($tampil as $baris) {
                            $totalhppx = $baris->hppakhir;
                        }
                }else{
                    $totalhppx = $row->qty * $row->barang->hbs;
                }
                return number_format($totalhppx,0);
            })
                        
            ->rawColumns([
                'hbs',
                'qty',
                'satuan',
                'totalhpp',
                'expired',
                ])

            ->make(true);

            return $data;

    }
    
    public function showstokmin()
    {
        
        $idruangx = session('idruang1');
        if($idruangx=='0'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $barangruang = Barangruang::select('*')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('qty','<=','stokmin')
            ->with('barang','seksi','ruang')
            ->get();
        $datax = DataTables::of($barangruang                          
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
            
            ->addColumn('hbs', function ($row) {
                $idruangx = session('idruang1');
                if($idruangx=='0'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }
                $jml = stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->count();
                if($jml<>0){
                    $tampil = stok::limit('1')->where('idbarang','=',$row->idbarang)
                        ->where('idruang','>=',$idruangawal)
                        ->where('idruang','<=',$idruangakhir)
                        ->orderBy('id','desc')
                        ->get();
                        foreach ($tampil as $baris) {
                            $hbsx = $baris->hbsakhir;
                        }
                }else{
                    $hbsx = $row->idbarang ? $row->barang->hbs : '0';
                }
                return number_format($hbsx,0);
            })
            ->addColumn('qty', function ($row) {
                $idruangx = session('idruang1');
                if($idruangx=='0'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }
                $jml = stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->count();
                if($jml<>0){
                    $tampil = stok::limit('1')->where('idbarang','=',$row->idbarang)
                        ->where('idruang','>=',$idruangawal)
                        ->where('idruang','<=',$idruangakhir)
                        ->orderBy('id','desc')
                        ->get();
                        foreach ($tampil as $baris) {
                            $qtyx = $baris->akhir;
                        }
                }else{
                    $qtyx = $row->qty;
                }
                return number_format($qtyx,0);
            })
            ->addColumn('totalhpp', function ($row) {
                $idruangx = session('idruang1');
                if($idruangx=='0'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }
                $jml = stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->count();
                if($jml<>0){
                    $tampil = stok::limit('1')->where('idbarang','=',$row->idbarang)
                        ->where('idruang','>=',$idruangawal)
                        ->where('idruang','<=',$idruangakhir)
                        ->orderBy('id','desc')
                        ->get();
                        foreach ($tampil as $baris) {
                            $totalhppx = $baris->hppakhir;
                        }
                }else{
                    $totalhppx = $row->qty * $row->barang->hbs;
                }
                return number_format($totalhppx,0);
            })
                        
            ->rawColumns([
                'hbs',
                'qty',
                'satuan',
                'totalhpp',
                ])

            ->make(true);

            return $data;

    }
  
    public function showstokmax()
    {
        
        $idruangx = session('idruang1');
        if($idruangx=='0'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $barangruang = Barangruang::select('*')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('qty','>=','stokmax')
            ->with('barang','seksi','ruang')
            ->get();
        $datax = DataTables::of($barangruang                          
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
            
            ->addColumn('hbs', function ($row) {
                $idruangx = session('idruang1');
                if($idruangx=='0'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }
                $jml = stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->count();
                if($jml<>0){
                    $tampil = stok::limit('1')->where('idbarang','=',$row->idbarang)
                        ->where('idruang','>=',$idruangawal)
                        ->where('idruang','<=',$idruangakhir)
                        ->orderBy('id','desc')
                        ->get();
                        foreach ($tampil as $baris) {
                            $hbsx = $baris->hbsakhir;
                        }
                }else{
                    $hbsx = $row->idbarang ? $row->barang->hbs : '0';
                }
                return number_format($hbsx,0);
            })
            ->addColumn('qty', function ($row) {
                $idruangx = session('idruang1');
                if($idruangx=='0'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }
                $jml = stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->count();
                if($jml<>0){
                    $tampil = stok::limit('1')->where('idbarang','=',$row->idbarang)
                        ->where('idruang','>=',$idruangawal)
                        ->where('idruang','<=',$idruangakhir)
                        ->orderBy('id','desc')
                        ->get();
                        foreach ($tampil as $baris) {
                            $qtyx = $baris->akhir;
                        }
                }else{
                    $qtyx = $row->qty;
                }
                return number_format($qtyx,0);
            })
            ->addColumn('totalhpp', function ($row) {
                $idruangx = session('idruang1');
                if($idruangx=='0'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }
                $jml = stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->count();
                if($jml<>0){
                    $tampil = stok::limit('1')->where('idbarang','=',$row->idbarang)
                        ->where('idruang','>=',$idruangawal)
                        ->where('idruang','<=',$idruangakhir)
                        ->orderBy('id','desc')
                        ->get();
                        foreach ($tampil as $baris) {
                            $totalhppx = $baris->hppakhir;
                        }
                }else{
                    $totalhppx = $row->qty * $row->barang->hbs;
                }
                return number_format($totalhppx,0);
            })
                        
            ->rawColumns([
                'hbs',
                'qty',
                'satuan',
                'totalhpp',
                ])

            ->make(true);

            return $data;

    }

    public function showstokhabis()
    {
        
        $idruangx = session('idruang1');
        if($idruangx=='0'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        $barangruang = Barangruang::select('*')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('qty','=','')
            ->with('barang','seksi','ruang')
            ->get();
        $datax = DataTables::of($barangruang                          
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
            
            ->addColumn('hbs', function ($row) {
                $idruangx = session('idruang1');
                if($idruangx=='0'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }
                $jml = stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->count();
                if($jml<>0){
                    $tampil = stok::limit('1')->where('idbarang','=',$row->idbarang)
                        ->where('idruang','>=',$idruangawal)
                        ->where('idruang','<=',$idruangakhir)
                        ->orderBy('id','desc')
                        ->get();
                        foreach ($tampil as $baris) {
                            $hbsx = $baris->hbsakhir;
                        }
                }else{
                    $hbsx = $row->idbarang ? $row->barang->hbs : '0';
                }
                return number_format($hbsx,0);
            })
            ->addColumn('qty', function ($row) {
                $idruangx = session('idruang1');
                if($idruangx=='0'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }
                $jml = stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->count();
                if($jml<>0){
                    $tampil = stok::limit('1')->where('idbarang','=',$row->idbarang)
                        ->where('idruang','>=',$idruangawal)
                        ->where('idruang','<=',$idruangakhir)
                        ->orderBy('id','desc')
                        ->get();
                        foreach ($tampil as $baris) {
                            $qtyx = $baris->akhir;
                        }
                }else{
                    $qtyx = $row->qty;
                }
                return number_format($qtyx,0);
            })
            ->addColumn('totalhpp', function ($row) {
                $idruangx = session('idruang1');
                if($idruangx=='0'||$idruangx==''){
                    $idruangawal = 0;
                    $idruangakhir = 999999;
                }else{
                    $idruangawal = $idruangx;
                    $idruangakhir = $idruangx;
                }
                $jml = stok::where('idbarang','=',$row->idbarang)
                    ->where('idruang','>=',$idruangawal)
                    ->where('idruang','<=',$idruangakhir)
                    ->count();
                if($jml<>0){
                    $tampil = stok::limit('1')->where('idbarang','=',$row->idbarang)
                        ->where('idruang','>=',$idruangawal)
                        ->where('idruang','<=',$idruangakhir)
                        ->orderBy('id','desc')
                        ->get();
                        foreach ($tampil as $baris) {
                            $totalhppx = $baris->hppakhir;
                        }
                }else{
                    $totalhppx = $row->qty * $row->barang->hbs;
                }
                return number_format($totalhppx,0);
            })
                        
            ->rawColumns([
                'hbs',
                'qty',
                'satuan',
                'totalhpp',
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
            // echo "<option value='" . $idx . "'>" . "- SEMUA -" . "</option>";
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->ruang . "</option>";
        }
    }

    public function kirimsyarat(Request $request)
    {
        session([
            'idruang1' => $request['idruang1'],
            'tabstok1' => $request['tabstok1'],
            'tgltransaksi1' => $request['tgltransaksi1'],
        ]);
    }


}
