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
use Modules\Pos01\Models\Stokmamin;
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
            ->where('idjenisbiaya','=','2')        
            ->where('idkategoribiaya','=','1')        
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
            ->where('idkategoribiaya','=','1')        
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

    public function showlabarugififo()
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

       $ppnkeluar = Stokfifo::select('ppnkeluar')
            ->where('tglstatus','>=',$tglawal)        
            ->where('tglstatus','<=',$tglakhir)        
            ->where('idruang','>=',$idruangawal)        
            ->where('idruang','<=',$idruangakhir)        
            ->sum('ppnkeluar');
        $ppnjualx = $ppnkeluar;
        $ppnjual = $this->formatangka($ppnjualx); 

        $diskonkeluar = Stokfifo::select('diskonkeluar')
            ->where('tglstatus','>=',$tglawal)        
            ->where('tglstatus','<=',$tglakhir)        
            ->where('idruang','>=',$idruangawal)        
            ->where('idruang','<=',$idruangakhir)        
            ->sum('diskonkeluar');
        $discountjualx = $diskonkeluar * -1;
        $discountjual = $this->formatangka($discountjualx);  
        
        $hppj = Stokfifo::select('hppj')
            ->where('tglstatus','>=',$tglawal)        
            ->where('tglstatus','<=',$tglakhir)        
            ->where('idruang','>=',$idruangawal)        
            ->where('idruang','<=',$idruangakhir)        
            ->sum('hppj');
        $pendapatanjualx = $hppj;
        $pendapatanjual = $this->formatangka($pendapatanjualx);  
        
        $hppkeluar = Stokfifo::select('hppkeluar')
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
            ->where('idjenisbiaya','=','2')        
            ->where('idkategoribiaya','=','1')        
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
            ->where('idkategoribiaya','=','1')        
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

    public function showlabarugimova()
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

       $ppnkeluar = Stokmova::select('ppnkeluar')
            ->where('tglstatus','>=',$tglawal)        
            ->where('tglstatus','<=',$tglakhir)        
            ->where('idruang','>=',$idruangawal)        
            ->where('idruang','<=',$idruangakhir)        
            ->sum('ppnkeluar');
        $ppnjualx = $ppnkeluar;
        $ppnjual = $this->formatangka($ppnjualx); 

        $diskonkeluar = Stokmova::select('diskonkeluar')
            ->where('tglstatus','>=',$tglawal)        
            ->where('tglstatus','<=',$tglakhir)        
            ->where('idruang','>=',$idruangawal)        
            ->where('idruang','<=',$idruangakhir)        
            ->sum('diskonkeluar');
        $discountjualx = $diskonkeluar * -1;
        $discountjual = $this->formatangka($discountjualx);  
        
        $hppj = Stokmova::select('hppj')
            ->where('tglstatus','>=',$tglawal)        
            ->where('tglstatus','<=',$tglakhir)        
            ->where('idruang','>=',$idruangawal)        
            ->where('idruang','<=',$idruangakhir)        
            ->sum('hppj');
        $pendapatanjualx = $hppj;
        $pendapatanjual = $this->formatangka($pendapatanjualx);  
        
        $hppkeluar = Stokmova::select('hppkeluar')
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
            ->where('idjenisbiaya','=','2')        
            ->where('idkategoribiaya','=','1')        
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
            ->where('idkategoribiaya','=','1')        
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

    public function showlabarugilifo()
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

       $ppnkeluar = Stoklifo::select('ppnkeluar')
            ->where('tglstatus','>=',$tglawal)        
            ->where('tglstatus','<=',$tglakhir)        
            ->where('idruang','>=',$idruangawal)        
            ->where('idruang','<=',$idruangakhir)        
            ->sum('ppnkeluar');
        $ppnjualx = $ppnkeluar;
        $ppnjual = $this->formatangka($ppnjualx); 

        $diskonkeluar = Stoklifo::select('diskonkeluar')
            ->where('tglstatus','>=',$tglawal)        
            ->where('tglstatus','<=',$tglakhir)        
            ->where('idruang','>=',$idruangawal)        
            ->where('idruang','<=',$idruangakhir)        
            ->sum('diskonkeluar');
        $discountjualx = $diskonkeluar * -1;
        $discountjual = $this->formatangka($discountjualx);  
        
        $hppj = Stoklifo::select('hppj')
            ->where('tglstatus','>=',$tglawal)        
            ->where('tglstatus','<=',$tglakhir)        
            ->where('idruang','>=',$idruangawal)        
            ->where('idruang','<=',$idruangakhir)        
            ->sum('hppj');
        $pendapatanjualx = $hppj;
        $pendapatanjual = $this->formatangka($pendapatanjualx);  
        
        $hppkeluar = Stoklifo::select('hppkeluar')
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
            ->where('idjenisbiaya','=','2')        
            ->where('idkategoribiaya','=','1')        
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
            ->where('idkategoribiaya','=','1')        
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

    public function showlabarugijasa()
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

       $ppnkeluar = Stokmamin::select('ppnkeluar')
            ->where('tglstatus','>=',$tglawal)        
            ->where('tglstatus','<=',$tglakhir)        
            ->where('idruang','>=',$idruangawal)        
            ->where('idruang','<=',$idruangakhir)        
            ->sum('ppnkeluar');
        $ppnjualx = $ppnkeluar;
        $ppnjual = $this->formatangka($ppnjualx); 

        $diskonkeluar = Stokmamin::select('diskonkeluar')
            ->where('tglstatus','>=',$tglawal)        
            ->where('tglstatus','<=',$tglakhir)        
            ->where('idruang','>=',$idruangawal)        
            ->where('idruang','<=',$idruangakhir)        
            ->sum('diskonkeluar');
        $discountjualx = $diskonkeluar * -1;
        $discountjual = $this->formatangka($discountjualx);  
        
        $hppj = Stokmamin::select('hppj')
            ->where('tglstatus','>=',$tglawal)        
            ->where('tglstatus','<=',$tglakhir)        
            ->where('idruang','>=',$idruangawal)        
            ->where('idruang','<=',$idruangakhir)        
            ->sum('hppj');
        $pendapatanjualx = $hppj;
        $pendapatanjual = $this->formatangka($pendapatanjualx);  

        $hppkeluar=Biaya::select('totalbeli')
            ->where('idkategoribiaya','=',2)        
            ->where('idjenisbiaya','=',1)        
            ->where('tgltransaksi','>=',$tglawal)        
            ->where('tgltransaksi','<=',$tglakhir)        
            ->where('idruang','>=',$idruangawal)        
            ->where('idruang','<=',$idruangakhir) 
            ->sum('totalbeli');
        
        $hppx = $hppkeluar * -1;
        $hpp = $this->formatangka($hppx);  
        
        $labakotorx = $ppnjualx + $discountjualx + $pendapatanjualx + $hppx;
        $labakotor = $this->formatangka($labakotorx);  
        
        $totalbeli = Biaya::select('totalbeli')
            ->where('idjenisbiaya','=','2')        
            ->where('idkategoribiaya','=','2')        
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
            ->where('idkategoribiaya','=','2')        
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
