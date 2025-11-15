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
use Modules\Akuntansi01\Models\Coa;
use Modules\Akuntansi01\Models\Jurnal;
use Modules\Simpanan01\Models\Sandi;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class HistoricoaController extends Controller
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
        $remark = 'Halaman ini digunakan untuk menampilkan <b>Histori COA</b>.';
        $page = 'akuntansi01::laporan.historicoa';
        $link = '/akuntansi01/laporan/historicoa';
        $subtitle = 'Laporan';
        $caption = 'Histori COA';
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
        
        $a1 = session()->get('memtanggal1');
        if($a1==''){
            $a1=session()->get('memtanggal');  
        }
        $a2 = session()->get('memtanggal2');
        if($a2==''){
            $a2=session()->get('memtanggal');  
        }

        $idcoa1 = session('idcoa1');
        $keterangan1 = session('keterangan1');

        $datax = DataTables::of(
            Jurnal::select('*')
            ->where('tglposting','>=',$a1)
            ->where('tglposting','<=',$a2)
            ->where('idcoa','=',$idcoa1)
            ->where('nl','=', 2)
            // ->where('keterangan','like', '%'. $keterangan1 .'%')
            ->orderBy('id','asc')                
            );

        $data = $datax
            ->addIndexColumn()

            ->addColumn('status', function ($row) {
                // $singkatan = substr($row->nomorstatus,0,3);
                $tampil = Sandi::select('sandi')
                ->where('id','=',$row->idsandi)
                ->get();
                foreach ($tampil as $baris) {
                    $status = $baris->sandi;
                }
                
                return $status; 
            })
            
            ->addColumn('awald', function ($row) {
                $debet = Jurnal::select('debet')
                ->where('id','<',$row->id)
                ->where('idcoa','=',$row->idcoa)
                ->where('nl','=', 2)
                ->sum('debet');

                $kredit = Jurnal::select('kredit')
                ->where('id','<',$row->id)
                ->where('idcoa','=',$row->idcoa)
                ->where('nl','=', 2)
                ->sum('kredit');

                if($debet>$kredit){
                    $awald = number_format($debet - $kredit,0);
                }else{
                    $awald ='';
                }

                return $awald; 
            })
            ->addColumn('awalk', function ($row) {
                $debet = Jurnal::select('debet')
                ->where('id','<',$row->id)
                ->where('idcoa','=',$row->idcoa)
                ->where('nl','=', 2)
                ->sum('debet');

                $kredit = Jurnal::select('kredit')
                ->where('id','<',$row->id)
                ->where('idcoa','=',$row->idcoa)
                ->where('nl','=', 2)
                ->sum('kredit');

                if($kredit>$debet){
                    $awalk = number_format($kredit - $debet,0); 
                }else{
                    $awalk = '';
                }
                return $awalk;
            })
            
            ->addColumn('debet', function ($row) {
                
                if($row->debet > 0 ){
                    $debet = number_format($row->debet,0); 
                }else{
                    $debet = '';
                }
                return $debet;
            })

            ->addColumn('kredit', function ($row) {
                
                if($row->kredit > 0 ){
                    $kredit = number_format($row->kredit,0); 
                }else{
                    $kredit = '';
                }
                return $kredit;
            })

            ->addColumn('saldod', function ($row) {
                $debet = Jurnal::select('debet')
                ->where('id','<=',$row->id)
                ->where('idcoa','=',$row->idcoa)
                ->where('nl','=', 2)
                ->sum('debet');

                $kredit = Jurnal::select('kredit')
                ->where('id','<=',$row->id)
                ->where('idcoa','=',$row->idcoa)
                ->where('nl','=', 2)
                ->sum('kredit');

                if($debet>$kredit){                    
                    $saldod = number_format($debet - $kredit,0); 
                }else{
                    $saldod = '';
                }
                return $saldod;
            })
            ->addColumn('saldok', function ($row) {
                $debet = Jurnal::select('debet')
                ->where('id','<=',$row->id)
                ->where('idcoa','=',$row->idcoa)
                ->where('nl','=', 2)
                ->sum('debet');

                $kredit = Jurnal::select('kredit')
                ->where('id','<=',$row->id)
                ->where('idcoa','=',$row->idcoa)
                ->where('nl','=', 2)
                ->sum('kredit');
                
                if($kredit>$debet){
                    $saldok = number_format($kredit - $debet,0);
                }else{
                    $saldok = '';
                }
                return $saldok;
                 
            })

            ->rawColumns([
                'status',
                'awald',
                'awalk',
                'debet',
                'kredit',
                'saldod',
                'saldok',
                ])

            ->make(true);

            return $data;

    }


    public function kirimsyarat(Request $request)
    {
        
        session([
            'memtanggal1' => $request['tanggalx1'],
            'memtanggal2' => $request['tanggalx2'],
            'idcoa1' => $request['idcoa1'],            
            'keterangan1' => $request['keterangan1'],
        ]);
    }

    function listcoa()
    {        
        $tampil = Coa::select('*') 
            ->where('hd','=','D')
            ->orderBy('kode','asc')           
            ->get();           
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kode . " - " . $baris->coa . "</option>";
        }
    }

    


}
