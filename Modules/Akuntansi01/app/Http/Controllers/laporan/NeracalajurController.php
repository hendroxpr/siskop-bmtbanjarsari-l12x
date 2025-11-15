<?php

namespace Modules\Akuntansi01\Http\Controllers\laporan;
use DateTime;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Aplikasi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Storage;
use App\Imports\ImportDatasiswa;
use App\Models\Menusub;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin01\Models\Bulan;
use Modules\Akuntansi01\Models\Coa;
use Modules\Akuntansi01\Models\Jurnal;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class NeracalajurController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {

        // $product = new Product;
        // $product->setConnection('mysql_second');
        // $something = $product->find(1);
        // return $something;

        $meminstansi = session('memnamasingkat');
        $remark = 'Halaman ini digunakan untuk menampilkan <b>Neraca Lajur</b>.';
        $page = 'akuntansi01::laporan.neracalajur';
        $link = '/akuntansi01/laporan/neracalajur';
        $subtitle = 'Laporan';
        $caption = 'Neraca Lajur';
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
            'tabel' => Jurnal::SimplePaginate($jmlhal)->withQueryString(),
            'aplikasi' => Aplikasi::get(),
            'menu' => $menu,
        ]);
        
    }
 
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
       
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
    public function show()
    {
        
        
        $datax = DataTables::of(
            Coa::with(['kategori'])                                  
            ->where('hd','=','D')           
            ->orderBy('kode', 'asc')              
            );

        $data = $datax
            ->addIndexColumn()

            ->addColumn('d1', function ($row) {
                $tahun = session('tahunx1');
                $bln = session('idbulanx1');
                if ($tahun==''){
                    $tahun = 1900;
                }
                if($bln==''){
                    $tgl1 = '1900-01-01';
                    $tgl2 = '1900-01-01';
                }else{
                    $tgl1 = $tahun .'-01-01';
                    $tgl2 = $bln;
                }

                $debetx = Jurnal::select('debet')
                    ->where('idcoa','=',$row)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->where('rn','=',1)
                    ->where('nl','=',1)
                    ->sum('debet');
                $kreditx = Jurnal::select('kredit')
                    ->where('idcoa','=',$row)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->where('rn','=',1)
                    ->where('nl','=',1)
                    ->sum('kredit');
                if($debetx>$kreditx){
                    $saldox = number_format($debetx - $kreditx,0);
                }else{
                    $saldox = '';
                } 
                return $saldox;
            })
            ->addColumn('k1', function ($row) {
                $tahun = session('tahunx1');
                $bln = session('idbulanx1');
                if ($tahun==''){
                    $tahun = 1900;
                }
                if($bln==''){
                    $tgl1 = '1900-01-01';
                    $tgl2 = '1900-01-01';
                }else{
                    $tgl1 = $tahun .'-01-01';
                    $tgl2 = $bln;
                }

                $debetx = Jurnal::select('debet')
                    ->where('idcoa','=',$row)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->where('rn','=',1)
                    ->where('nl','=',1)
                    ->sum('debet');
                $kreditx = Jurnal::select('kredit')
                    ->where('idcoa','=',$row)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->where('rn','=',1)
                    ->where('nl','=',1)
                    ->sum('kredit');
                if($kreditx>$debetx){
                    $saldox = number_format( $kreditx - $debetx,0);
                }else{
                    $saldox = '';
                } 
                return $saldox;
            })

            ->addColumn('d2', function ($row) {
                $tahun = session('tahunx1');
                $bln = session('idbulanx1');
                if ($tahun==''){
                    $tahun = 1900;
                }
                if($bln==''){
                    $tgl1 = '1900-01-01';
                    $tgl2 = '1900-01-01';
                }else{
                    $tgl1 = $tahun .'-01-01';
                    $tgl2 = $bln;
                }
                $debetx = Jurnal::select('debet')
                    ->where('idcoa','=',$row->id)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->where('nl','=',2)                    
                    ->sum('debet');
                $kreditx = Jurnal::select('kredit')
                    ->where('idcoa','=',$row->id)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->where('nl','=',2)
                    ->sum('kredit');
                    // if($debetx>$kreditx){
                    //     $saldox = number_format($debetx - $kreditx,0);
                    // }else{
                    //     $saldox = '';
                    // } 
                    
                    if($debetx>0){
                        $saldox = number_format($debetx,0);
                    }else{
                        $saldox = '';
                    }                    
                    return $saldox;
                })
            ->addColumn('k2', function ($row) {
                $tahun = session('tahunx1');
                $bln = session('idbulanx1');
                if ($tahun==''){
                    $tahun = 1900;
                }
                if($bln==''){
                    $tgl1 = '1900-01-01';
                    $tgl2 = '1900-01-01';
                }else{
                    $tgl1 = $tahun .'-01-01';
                    $tgl2 = $bln;
                }
                $debetx = Jurnal::select('debet')
                    ->where('idcoa','=',$row->id)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->where('nl','=',2)                    
                    ->sum('debet');
                $kreditx = Jurnal::select('kredit')
                    ->where('idcoa','=',$row->id)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->where('nl','=',2)
                    ->sum('kredit');
                // if($kreditx>$debetx){                    
                //     $saldox = number_format( $kreditx - $debetx,0);
                // }else{
                //     $saldox = '';
                // } 
                if($kreditx>0){
                    $saldox = number_format($kreditx,0);
                }else{
                    $saldox = '';
                }                    
                return $saldox;
            })

            ->addColumn('d3', function ($row) {
                $tahun = session('tahunx1');
                $bln = session('idbulanx1');
                if ($tahun==''){
                    $tahun = 1900;
                }
                if($bln==''){
                    $tgl1 = '1900-01-01';
                    $tgl2 = '1900-01-01';
                }else{
                    $tgl1 = $tahun .'-01-01';
                    $tgl2 = $bln;
                }
                $debetx = Jurnal::select('debet')
                    ->where('idcoa','=',$row->id)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->where('nl','=',3)                    
                    ->sum('debet');
                $kreditx = Jurnal::select('kredit')
                    ->where('idcoa','=',$row->id)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->where('nl','=',3)
                    ->sum('kredit');
                    
                    if($debetx>0){
                        $saldox = number_format($debetx,0);
                    }else{
                        $saldox = '';
                    }                    
                    return $saldox;
            })

            ->addColumn('k3', function ($row) {
                $tahun = session('tahunx1');
                $bln = session('idbulanx1');
                if ($tahun==''){
                    $tahun = 1900;
                }
                if($bln==''){
                    $tgl1 = '1900-01-01';
                    $tgl2 = '1900-01-01';
                }else{
                    $tgl1 = $tahun .'-01-01';
                    $tgl2 = $bln;
                }
                $debetx = Jurnal::select('debet')
                    ->where('idcoa','=',$row->id)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->where('nl','=',3)                    
                    ->sum('debet');
                $kreditx = Jurnal::select('kredit')
                    ->where('idcoa','=',$row->id)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->where('nl','=',3)
                    ->sum('kredit');

                
                 
                if($kreditx>0){
                    $saldox = number_format($kreditx,0);
                }else{
                    $saldox = '';
                }                    
                return $saldox;
            })

            ->addColumn('d4', function ($row) {
                $tahun = session('tahunx1');
                $bln = session('idbulanx1');
                if ($tahun==''){
                    $tahun = 1900;
                }
                if($bln==''){
                    $tgl1 = '1900-01-01';
                    $tgl2 = '1900-01-01';
                }else{
                    $tgl1 = $tahun .'-01-01';
                    $tgl2 = $bln;
                }

                // $debetx = Jurnal::select('debet')
                //     ->where('idcoa','=',$row->id)
                //     ->where('tglposting','>=',$tgl1)
                //     ->where('tglposting','<=',$tgl2)
                //     ->whereIn('nl',[2,3])                   
                //     ->sum('debet');
                // $kreditx = Jurnal::select('kredit')
                //     ->where('idcoa','=',$row->id)
                //     ->where('tglposting','>=',$tgl1)
                //     ->where('tglposting','<=',$tgl2)
                //     ->whereIn('nl',[2,3])
                //     ->sum('kredit');

                //saldo awal
                $debet1x = Jurnal::select('debet')
                    ->where('idcoa','=',$row)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->where('rn','=',1)
                    ->where('nl','=',1)
                    ->sum('debet');
                $kredit1x = Jurnal::select('kredit')
                    ->where('idcoa','=',$row)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->where('rn','=',1)
                    ->where('nl','=',1)
                    ->sum('kredit');
                //perubahan
                $debet2x = Jurnal::select('debet')
                    ->where('idcoa','=',$row->id)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->where('nl','=',2)                    
                    ->sum('debet');
                $kredit2x = Jurnal::select('kredit')
                    ->where('idcoa','=',$row->id)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->where('nl','=',2)
                    ->sum('kredit');
                //penyesuaian
                $debet3x = Jurnal::select('debet')
                    ->where('idcoa','=',$row->id)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->where('nl','=',3)                    
                    ->sum('debet');
                $kredit3x = Jurnal::select('kredit')
                    ->where('idcoa','=',$row->id)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->where('nl','=',3)
                    ->sum('kredit');
                $debetx = $debet1x + $debet2x + $debet3x; 
                $kreditx = $kredit1x + $kredit2x + $kredit3x;
                
                if($debetx>$kreditx){
                    $saldox = number_format($debetx-$kreditx,0);
                }else{
                    $saldox = '';
                } 

                return $saldox;
            })

            ->addColumn('k4', function ($row) {
                $tahun = session('tahunx1');
                $bln = session('idbulanx1');
                if ($tahun==''){
                    $tahun = 1900;
                }
                if($bln==''){
                    $tgl1 = '1900-01-01';
                    $tgl2 = '1900-01-01';
                }else{
                    $tgl1 = $tahun .'-01-01';
                    $tgl2 = $bln;
                }
                // $debetx = Jurnal::select('debet')
                //     ->where('idcoa','=',$row->id)
                //     ->where('tglposting','>=',$tgl1)
                //     ->where('tglposting','<=',$tgl2)
                //     ->whereIn('nl',[2,3])                    
                //     ->sum('debet');
                // $kreditx = Jurnal::select('kredit')
                //     ->where('idcoa','=',$row->id)
                //     ->where('tglposting','>=',$tgl1)
                //     ->where('tglposting','<=',$tgl2)
                //     ->whereIn('nl',[2,3])
                //     ->sum('kredit');

                //saldo awal
                $debet1x = Jurnal::select('debet')
                    ->where('idcoa','=',$row)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->where('rn','=',1)
                    ->where('nl','=',1)
                    ->sum('debet');
                $kredit1x = Jurnal::select('kredit')
                    ->where('idcoa','=',$row)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->where('rn','=',1)
                    ->where('nl','=',1)
                    ->sum('kredit');
                //perubahan
                $debet2x = Jurnal::select('debet')
                    ->where('idcoa','=',$row->id)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->where('nl','=',2)                    
                    ->sum('debet');
                $kredit2x = Jurnal::select('kredit')
                    ->where('idcoa','=',$row->id)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->where('nl','=',2)
                    ->sum('kredit');
                //penyesuaian
                $debet3x = Jurnal::select('debet')
                    ->where('idcoa','=',$row->id)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->where('nl','=',3)                    
                    ->sum('debet');
                $kredit3x = Jurnal::select('kredit')
                    ->where('idcoa','=',$row->id)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->where('nl','=',3)
                    ->sum('kredit');
                $debetx = $debet1x + $debet2x + $debet3x; 
                $kreditx = $kredit1x + $kredit2x + $kredit3x;

                if($kreditx>$debetx){
                    $saldox = number_format($kreditx-$debetx,0);
                }else{
                    $saldox = '';
                }
                return $saldox;
                
            })

            ->addColumn('d5', function ($row) {
                $tahun = session('tahunx1');
                $bln = session('idbulanx1');
                if ($tahun==''){
                    $tahun = 1900;
                }
                if($bln==''){
                    $tgl1 = '1900-01-01';
                    $tgl2 = '1900-01-01';
                }else{
                    $tgl1 = $tahun .'-01-01';
                    $tgl2 = $bln;
                }

                //ambil idcoa laba rugi (kodekelompok =4,6,8,9);
                $idcoax = Coa::select('id')->whereIn('kodekelompok',['4','6','8','9']);
                // $debetx = Jurnal::select('debet')
                //     ->where('idcoa','=',$row->id)
                //     ->where('tglposting','>=',$tgl1)
                //     ->where('tglposting','<=',$tgl2)
                //     ->where('rn','=',2)
                //     ->whereIn('nl',[5])                   
                //     ->sum('debet');
                // $kreditx = Jurnal::select('kredit')
                //     ->where('idcoa','=',$row->id)
                //     ->where('tglposting','>=',$tgl1)
                //     ->where('tglposting','<=',$tgl2)
                //     ->where('rn','=',2)
                //     ->whereIn('nl',[5])
                //     ->sum('kredit');

                $debetx = Jurnal::select('debet')
                    ->where('idcoa','=',$row->id)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->whereIn('idcoa',$idcoax)              
                    ->sum('debet');
                $kreditx = Jurnal::select('kredit')
                    ->where('idcoa','=',$row->id)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->whereIn('idcoa',$idcoax)
                    ->sum('kredit');
                
                $tdebetx = Jurnal::select('debet')
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->whereIn('idcoa',$idcoax)              
                    ->sum('debet');
                $tkreditx = Jurnal::select('kredit')
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->whereIn('idcoa',$idcoax)
                    ->sum('kredit');
                
                if($row->id == '1'){
                    if($tkreditx>$tdebetx){                    
                        $saldox = number_format( $tkreditx - $tdebetx,0);
                    }else{
                        $saldox = '';                        
                    }                
                    return $saldox;
                }else{
                    if($debetx>$kreditx){
                        $saldox = number_format($debetx - $kreditx,0);
                    }else{
                        $saldox = '';                        
                    }                
                    return $saldox;
                }
            })

            ->addColumn('k5', function ($row) {
                $tahun = session('tahunx1');
                $bln = session('idbulanx1');
                if ($tahun==''){
                    $tahun = 1900;
                }
                if($bln==''){
                    $tgl1 = '1900-01-01';
                    $tgl2 = '1900-01-01';
                }else{
                    $tgl1 = $tahun .'-01-01';
                    $tgl2 = $bln;
                }
                //ambil idcoa laba rugi (kodekelompok =4,6,8,9);
                $idcoax = Coa::select('id')->whereIn('kodekelompok',['4','6','8','9']);
                // $debetx = Jurnal::select('debet')
                //     ->where('idcoa','=',$row->id)
                //     ->where('tglposting','>=',$tgl1)
                //     ->where('tglposting','<=',$tgl2)
                //     ->where('rn','=',2)
                //     ->whereIn('nl',[5])                   
                //     ->sum('debet');
                // $kreditx = Jurnal::select('kredit')
                //     ->where('idcoa','=',$row->id)
                //     ->where('tglposting','>=',$tgl1)
                //     ->where('tglposting','<=',$tgl2)
                //     ->where('rn','=',2)
                //     ->whereIn('nl',[5])
                //     ->sum('kredit');

                $debetx = Jurnal::select('debet')
                     ->where('idcoa','=',$row->id)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->whereIn('idcoa',$idcoax)              
                    ->sum('debet');
                $kreditx = Jurnal::select('kredit')
                    ->where('idcoa','=',$row->id)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->whereIn('idcoa',$idcoax)
                    ->sum('kredit');

                $tdebetx = Jurnal::select('debet')
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->whereIn('idcoa',$idcoax)              
                    ->sum('debet');
                $tkreditx = Jurnal::select('kredit')
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->whereIn('idcoa',$idcoax)
                    ->sum('kredit');

                if($row->id == '1'){
                    if($tdebetx>$tkreditx){
                        $saldox = number_format($tdebetx - $tkreditx,0);
                    }else{
                        $saldox = '';
                    } 
                    return $saldox;
                }else{

                    if($kreditx>$debetx){                    
                        $saldox = number_format( $kreditx - $debetx,0);
                    }else{
                        $saldox = '';
                    } 
                    return $saldox;
                }    
                
                
            })
            ->addColumn('d6', function ($row) {
                $tahun = session('tahunx1');
                $bln = session('idbulanx1');
                if ($tahun==''){
                    $tahun = 1900;
                }
                if($bln==''){
                    $tgl1 = '1900-01-01';
                    $tgl2 = '1900-01-01';
                }else{
                    $tgl1 = $tahun .'-01-01';
                    $tgl2 = $bln;
                }

                //ambil idcoa laba rugi (kodekelompok =4,6,8,9);
                $idcoax = Coa::select('id')->whereIn('kodekelompok',['4','6','8','9']);
                $idcoay = Coa::select('id')->whereIn('kodekelompok',['1','2','3','5','7']);
                // $debetx = Jurnal::select('debet')
                //     ->where('idcoa','=',$row->id)
                //     ->where('tglposting','>=',$tgl1)
                //     ->where('tglposting','<=',$tgl2)
                //     ->where('rn','=',2)
                //     ->whereIn('nl',[5])                   
                //     ->sum('debet');
                // $kreditx = Jurnal::select('kredit')
                //     ->where('idcoa','=',$row->id)
                //     ->where('tglposting','>=',$tgl1)
                //     ->where('tglposting','<=',$tgl2)
                //     ->where('rn','=',2)
                //     ->whereIn('nl',[5])
                //     ->sum('kredit');

                $debetx = Jurnal::select('debet')
                    ->where('idcoa','=',$row->id)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->whereIn('idcoa',$idcoay)              
                    ->sum('debet');
                $kreditx = Jurnal::select('kredit')
                    ->where('idcoa','=',$row->id)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->whereIn('idcoa',$idcoay)
                    ->sum('kredit');
                
                $tdebetx = Jurnal::select('debet')
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->whereIn('idcoa',$idcoax)              
                    ->sum('debet');
                $tkreditx = Jurnal::select('kredit')
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->whereIn('idcoa',$idcoax)
                    ->sum('kredit');
                
                if($row->id == '1'){
                    if($tdebetx>$tkreditx){
                        $saldox = number_format($tdebetx - $tkreditx,0);
                    
                    }else{
                        $saldox = '';                        
                    }                
                    return $saldox;
                }else{
                    if($debetx>$kreditx){
                        $saldox = number_format($debetx - $kreditx,0);
                    }else{
                        $saldox = '';                        
                    }                
                    return $saldox;
                }
            })

            ->addColumn('k6', function ($row) {
                $tahun = session('tahunx1');
                $bln = session('idbulanx1');
                if ($tahun==''){
                    $tahun = 1900;
                }
                if($bln==''){
                    $tgl1 = '1900-01-01';
                    $tgl2 = '1900-01-01';
                }else{
                    $tgl1 = $tahun .'-01-01';
                    $tgl2 = $bln;
                }
                //ambil idcoa laba rugi (kodekelompok =4,6,8,9);
                $idcoax = Coa::select('id')->whereIn('kodekelompok',['4','6','8','9']);
                $idcoay = Coa::select('id')->whereIn('kodekelompok',['1','2','3','5','7']);
                // $debetx = Jurnal::select('debet')
                //     ->where('idcoa','=',$row->id)
                //     ->where('tglposting','>=',$tgl1)
                //     ->where('tglposting','<=',$tgl2)
                //     ->where('rn','=',2)
                //     ->whereIn('nl',[5])                   
                //     ->sum('debet');
                // $kreditx = Jurnal::select('kredit')
                //     ->where('idcoa','=',$row->id)
                //     ->where('tglposting','>=',$tgl1)
                //     ->where('tglposting','<=',$tgl2)
                //     ->where('rn','=',2)
                //     ->whereIn('nl',[5])
                //     ->sum('kredit');

                $debetx = Jurnal::select('debet')
                     ->where('idcoa','=',$row->id)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->whereIn('idcoa',$idcoay)              
                    ->sum('debet');
                $kreditx = Jurnal::select('kredit')
                    ->where('idcoa','=',$row->id)
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->whereIn('idcoa',$idcoay)
                    ->sum('kredit');

                $tdebetx = Jurnal::select('debet')
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->whereIn('idcoa',$idcoax)              
                    ->sum('debet');
                $tkreditx = Jurnal::select('kredit')
                    ->where('tglposting','>=',$tgl1)
                    ->where('tglposting','<=',$tgl2)
                    ->whereIn('idcoa',$idcoax)
                    ->sum('kredit');

                if($row->id == '1'){
                    if($tkreditx>$tdebetx){                    
                        $saldox = number_format( $tkreditx - $tdebetx,0);
                    }else{
                        $saldox = '';
                    } 
                    return $saldox;
                }else{

                    if($kreditx>$debetx){                    
                        $saldox = number_format( $kreditx - $debetx,0);
                    }else{
                        $saldox = '';
                    } 
                    return $saldox;
                }    
                
                
            })

            ->rawColumns([
                'd1','k1',                
                'd2','k2',                
                'd3','k3',                
                'd4','k4',                
                'd5','k5',                
                'd6','k6',                
                'd7','k7',                
                ])

            ->make(true);

            return $data;

    }

    function listbulan()
    {               
        $thn = session('tahunx1');
        $tampil = Bulan::where('namabulan', 'like', "%$thn")
        ->orderBy('tglakhir', 'asc')
        ->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->tglakhir . "'>" . $baris->namabulan . "</option>";
        }
    }

    public function kirimsyarat(Request $request)
    {
        session([                      
            'tahunx1' => $request['tahunx1'],           
            'idbulanx1' => $request['idbulanx1'],           
        ]);
    }



}
