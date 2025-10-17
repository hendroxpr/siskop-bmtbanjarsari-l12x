<?php

namespace Modules\Akuntansi01\Http\Controllers\master;
use DateTime;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Aplikasi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Storage;
use App\Models\Menusub;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Akuntansi01\Models\Coa;
use Modules\Akuntansi01\Models\Jurnal;
use Modules\Akuntansi01\Models\Kategori;
use Modules\Akuntansi01\Models\Kelompok;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class CoaController extends Controller
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
        $remark = 'Halaman ini digunakan untuk menampilkan, menambah, mengubah dan menghapus <b>COA (Chart Of Accounts)</b>.';
        $page = 'akuntansi01::master.coa';
        $link = '/akuntansi01/master/coa';
        $subtitle = 'Master';
        $caption = 'COA (Chart Of Accounts)';
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
            'tabel' => Coa::SimplePaginate($jmlhal)->withQueryString(),
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
        $idkelompok = $request['idkelompok1'];
        $idkategori = $request['idkategori1'];
        

        $validatedData = $request->validate([
            'golongan1' => 'required',
            'idkelompok1' => 'required',
            'idkategori1' => 'required',            
            'kode1' => 'required|min:7|max:7',            
            'coa1' => 'required',            
        ]);

        $tampil = Kelompok::select('*')
        ->where('id','=',$idkelompok)
        ->get();
        foreach ($tampil as $baris) {
            $kodekelompok = $baris->kode;
        }
        $tampil = Kategori::select('*')
        ->where('id','=',$idkategori)
        ->get();
        foreach ($tampil as $baris) {
            $kodekategori = $baris->kode;
        }
          
        //untuk input tabel yang asli                
        $data = [            
            'hd' => $validatedData['golongan1'],
            'idkategori' => $validatedData['idkategori1'],
            'kodekelompok' => $kodekelompok,
            'kodekategori' => $kodekategori,
            'kode' => $validatedData['kode1'],
            'coa' => $validatedData['coa1'],            
            'keterangan' => $request['keterangan1'],
        ];

        if ($id == '0') {
            Coa::create($data);
        } else {
            Coa::where('id', '=', $id)->update($data);
        }
        return json_encode('data');
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
    public function show()
    {
        $idtab = session('idtab1');
        if($idtab==''||$idtab=='0'){
            $kodekelompok1 = 0;
            $kodekelompok2 = 9999;
        }else{
            $kodekelompok1 = $idtab;
            $kodekelompok2 = $idtab;
        }

        $datax = DataTables::of(
            Coa::with(['kategori']) 
            ->where('kodekelompok','>=',$kodekelompok1)           
            ->where('kodekelompok','<=',$kodekelompok2)           
            ->orderBy('kode','asc')                
            );

        $data = $datax
            ->addIndexColumn()
            ->addColumn('kelompok', function ($row) {
                $kodekelompok = $row->kodekelompok;
                $tampil = Kelompok::select('*')
                ->where('kode','=',$kodekelompok)
                ->get();
                foreach ($tampil as $baris) {
                    $kelompok = $baris->kelompok;
                }
                return $row->kodekelompok ? $kelompok : '';
            })
            ->addColumn('kategori', function ($row) {
                $x = substr($row->kode, 1, 4);
                
                $kodekelompok = $row->kodekelompok;
                $tampil = Kelompok::select('*')
                ->where('kode','=',$kodekelompok)
                ->get();
                foreach ($tampil as $baris) {
                    $kelompok = $baris->kelompok;
                }

                if($x=='0000'){
                    return $kelompok;
                }else{
                    return $row->idkategori ? $row->kategori->kategori : '';
                }
            })

            ->addColumn('coa', function ($row) {
                $x6 = substr($row->kode, 1, 6);
                $x5 = substr($row->kode, 2, 5);
                $x4 = substr($row->kode, 3, 4);
                $x3 = substr($row->kode, 4, 3);
                $x2 = substr($row->kode, 5, 2);
                $hd = $row->hd;

                $spc = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

                if($hd=='H'){
                    if($x6=='000000'){                    
                        return $row->coa ? $row->coa : '';                                 
                    }else{
                        if($x4=='0000'){
                            return $row->coa ? $spc . $row->coa : '';
                        }else{
                            if($x3=='000'){
                                return $row->coa ? $spc . $spc . $row->coa : '';
                            }else{
                                if($x2=='00'){
                                    return $row->coa ? $spc . $spc . $spc . $row->coa : '';
                                }else{
                                    return $row->coa ? $spc . $spc . $spc . $spc .  $row->coa : '';
                                }
                            }    
                        }
                    }
                }else{
                    return $row->coa ? $spc . $spc . $spc . $spc . $spc. $row->coa : '';
                }

            })

            ->addColumn('debet', function ($row) {
                $x6 = substr($row->kode, 1, 6);
                $x5 = substr($row->kode, 2, 5);
                $x4 = substr($row->kode, 3, 4);
                $x3 = substr($row->kode, 4, 3);
                $x2 = substr($row->kode, 5, 2);
                $hd = $row->hd;

                if($hd=='H'){
                    if($x6=='000000'){                        
                        $kode = substr($row->kode,0,1);
                        $listidcoa = Coa::select('id')
                            ->where('kode', 'like', "$kode%");
                        $debet = Jurnal::select('debet')
                            ->whereIn('idcoa',$listidcoa)
                            ->where('nl',2)
                            ->sum('debet');
                        $kredit = Jurnal::select('kredit')
                            ->whereIn('idcoa',$listidcoa)
                            ->where('nl',2)
                            ->sum('kredit');

                        $saldo = $debet - $kredit;
                        if($saldo>0){                            
                            return number_format($saldo,0);
                        }else{
                            return '';
                        } 
                    }else{
                        if($x5=='00000'){
                            $kode = substr($row->kode,0,2);
                            $listidcoa = Coa::select('id')
                                ->where('kode', 'like', "$kode%");
                            $debet = Jurnal::select('debet')
                                ->whereIn('idcoa',$listidcoa)
                                ->where('nl',2)
                                ->sum('debet');
                            $kredit = Jurnal::select('kredit')
                                ->whereIn('idcoa',$listidcoa)
                                ->where('nl',2)
                                ->sum('kredit');
    
                            $saldo = $debet - $kredit;
                            if($saldo>0){                            
                                return number_format($saldo,0);
                            }else{
                                return '';
                            }                            
                        }else{
                            if($x4=='0000'){
                                $kode = substr($row->kode,0,3);
                                $listidcoa = Coa::select('id')
                                    ->where('kode', 'like', "$kode%");
                                $debet = Jurnal::select('debet')
                                    ->whereIn('idcoa',$listidcoa)
                                    ->where('nl',2)
                                    ->sum('debet');
                                $kredit = Jurnal::select('kredit')
                                    ->whereIn('idcoa',$listidcoa)
                                    ->where('nl',2)
                                    ->sum('kredit');
        
                                $saldo = $debet - $kredit;
                                if($saldo>0){                            
                                    return number_format($saldo,0);
                                }else{
                                    return '';
                                }
                            }else{
                                if($x3=='000'){
                                    $kode = substr($row->kode,0,4);
                                    $listidcoa = Coa::select('id')
                                        ->where('kode', 'like', "$kode%");
                                    $debet = Jurnal::select('debet')
                                        ->whereIn('idcoa',$listidcoa)
                                        ->where('nl',2)
                                        ->sum('debet');
                                    $kredit = Jurnal::select('kredit')
                                        ->whereIn('idcoa',$listidcoa)
                                        ->where('nl',2)
                                        ->sum('kredit');
            
                                    $saldo = $debet - $kredit;
                                    if($saldo>0){                            
                                        return number_format($saldo,0);
                                    }else{
                                        return '';
                                    }
                                }else{
                                    if($x2=='00'){
                                        $kode = substr($row->kode,0,5);
                                        $listidcoa = Coa::select('id')
                                            ->where('kode', 'like', "$kode%");
                                        $debet = Jurnal::select('debet')
                                            ->whereIn('idcoa',$listidcoa)
                                            ->where('nl',2)
                                            ->sum('debet');
                                        $kredit = Jurnal::select('kredit')
                                            ->whereIn('idcoa',$listidcoa)
                                            ->where('nl',2)
                                            ->sum('kredit');
                
                                        $saldo = $debet - $kredit;
                                        if($saldo>0){                            
                                            return number_format($saldo,0);
                                        }else{
                                            return '';
                                        }
                                    }else{
                                        $kode = substr($row->kode,0,6);
                                        $listidcoa = Coa::select('id')
                                            ->where('kode', 'like', "$kode%");
                                        $debet = Jurnal::select('debet')
                                            ->whereIn('idcoa',$listidcoa)
                                            ->where('nl',2)
                                            ->sum('debet');
                                        $kredit = Jurnal::select('kredit')
                                            ->whereIn('idcoa',$listidcoa)
                                            ->where('nl',2)
                                            ->sum('kredit');
                
                                        $saldo = $debet - $kredit;
                                        if($saldo>0){                            
                                            return number_format($saldo,0);
                                        }else{
                                            return '';
                                        }  
                                    }

                                }    
                            }
                        }
                    }
                }else{
                    $kode = $row->kode;
                    $listidcoa = Coa::select('id')
                        ->where('kode', '=', $kode);
                    $debet = Jurnal::select('debet')
                        ->whereIn('idcoa',$listidcoa)
                        ->where('nl',2)
                        ->sum('debet');
                    $kredit = Jurnal::select('kredit')
                        ->whereIn('idcoa',$listidcoa)
                        ->where('nl',2)
                        ->sum('kredit');

                    $saldo = $debet - $kredit;
                    if($saldo>0){                            
                        return number_format($saldo,0);
                    }else{
                        return '';
                    }
                }

            })
            ->addColumn('kredit', function ($row) {
                $x6 = substr($row->kode, 1, 6);
                $x5 = substr($row->kode, 2, 5);
                $x4 = substr($row->kode, 3, 4);
                $x3 = substr($row->kode, 4, 3);
                $x2 = substr($row->kode, 5, 2);
                $hd = $row->hd;

                if($hd=='H'){
                    if($x6=='000000'){                        
                        $kode = substr($row->kode,0,1);
                        $listidcoa = Coa::select('id')
                            ->where('kode', 'like', "$kode%");
                        $debet = Jurnal::select('debet')
                            ->whereIn('idcoa',$listidcoa)
                            ->where('nl',2)
                            ->sum('debet');
                        $kredit = Jurnal::select('kredit')
                            ->whereIn('idcoa',$listidcoa)
                            ->where('nl',2)
                            ->sum('kredit');

                        $saldo = $kredit - $debet;
                        if($saldo>0){                            
                            return number_format($saldo,0);
                        }else{
                            return '';
                        } 
                    }else{
                        if($x5=='00000'){
                            $kode = substr($row->kode,0,2);
                            $listidcoa = Coa::select('id')
                                ->where('kode', 'like', "$kode%");
                            $debet = Jurnal::select('debet')
                                ->whereIn('idcoa',$listidcoa)
                                ->where('nl',2)
                                ->sum('debet');
                            $kredit = Jurnal::select('kredit')
                                ->whereIn('idcoa',$listidcoa)
                                ->where('nl',2)
                                ->sum('kredit');
    
                            $saldo = $kredit - $debet;
                            if($saldo>0){                            
                                return number_format($saldo,0);
                            }else{
                                return '';
                            }                            
                        }else{
                            if($x4=='0000'){
                                $kode = substr($row->kode,0,3);
                                $listidcoa = Coa::select('id')
                                    ->where('kode', 'like', "$kode%");
                                $debet = Jurnal::select('debet')
                                    ->whereIn('idcoa',$listidcoa)
                                    ->where('nl',2)
                                    ->sum('debet');
                                $kredit = Jurnal::select('kredit')
                                    ->whereIn('idcoa',$listidcoa)
                                    ->where('nl',2)
                                    ->sum('kredit');
        
                                $saldo = $kredit - $debet;
                                if($saldo>0){                            
                                    return number_format($saldo,0);
                                }else{
                                    return '';
                                }
                            }else{
                                if($x3=='000'){
                                    $kode = substr($row->kode,0,4);
                                    $listidcoa = Coa::select('id')
                                        ->where('kode', 'like', "$kode%");
                                    $debet = Jurnal::select('debet')
                                        ->whereIn('idcoa',$listidcoa)
                                        ->where('nl',2)
                                        ->sum('debet');
                                    $kredit = Jurnal::select('kredit')
                                        ->whereIn('idcoa',$listidcoa)
                                        ->where('nl',2)
                                        ->sum('kredit');
            
                                    $saldo = $kredit - $debet;
                                    if($saldo>0){                            
                                        return number_format($saldo,0);
                                    }else{
                                        return '';
                                    }
                                }else{
                                    if($x2=='00'){
                                        $kode = substr($row->kode,0,5);
                                        $listidcoa = Coa::select('id')
                                            ->where('kode', 'like', "$kode%");
                                        $debet = Jurnal::select('debet')
                                            ->whereIn('idcoa',$listidcoa)
                                            ->where('nl',2)
                                            ->sum('debet');
                                        $kredit = Jurnal::select('kredit')
                                            ->whereIn('idcoa',$listidcoa)
                                            ->where('nl',2)
                                            ->sum('kredit');
                
                                        $saldo = $kredit - $debet;
                                        if($saldo>0){                            
                                            return number_format($saldo,0);
                                        }else{
                                            return '';
                                        }
                                    }else{
                                        $kode = substr($row->kode,0,6);
                                        $listidcoa = Coa::select('id')
                                            ->where('kode', 'like', "$kode%");
                                        $debet = Jurnal::select('debet')
                                            ->whereIn('idcoa',$listidcoa)
                                            ->where('nl',2)
                                            ->sum('debet');
                                        $kredit = Jurnal::select('kredit')
                                            ->whereIn('idcoa',$listidcoa)
                                            ->where('nl',2)
                                            ->sum('kredit');
                
                                        $saldo = $kredit - $debet;
                                        if($saldo>0){                            
                                            return number_format($saldo,0);
                                        }else{
                                            return '';
                                        } 
                                    }
                                }    
                            }
                        }
                    }
                }else{
                    $kode = $row->kode;
                    $listidcoa = Coa::select('id')
                        ->where('kode', '=', $kode);
                    $debet = Jurnal::select('debet')
                        ->whereIn('idcoa',$listidcoa)
                        ->where('nl',2)
                        ->sum('debet');
                    $kredit = Jurnal::select('kredit')
                        ->whereIn('idcoa',$listidcoa)
                        ->where('nl',2)
                        ->sum('kredit');

                    $saldo = $kredit - $debet;
                    if($saldo>0){                            
                        return number_format($saldo,0);
                    }else{
                        return '';
                    }
                }

            })
            
            ->addColumn('action', function ($row) {
                $tampil = Kelompok::select('*')
                ->where('kode','=',$row->kodekelompok)
                ->get();
                foreach ($tampil as $baris) {
                    $idkelompok = $baris->id;
                }

                $x6 = substr($row->kode, 1, 6);
                $x5 = substr($row->kode, 2, 5);
                $x4 = substr($row->kode, 3, 4);
                $x3 = substr($row->kode, 4, 3);
                $x2 = substr($row->kode, 5, 2);
                $hd = $row->hd;

                if($hd=='H'){
                    if($x6=='000000'){                        
                        $kode = substr($row->kode,0,1);
                        $listidcoa = Coa::select('id')
                            ->where('kode', 'like', "$kode%");
                        $debet = Jurnal::select('debet')
                            ->whereIn('idcoa',$listidcoa)
                            ->where('nl',2)
                            ->sum('debet');
                        $kredit = Jurnal::select('kredit')
                            ->whereIn('idcoa',$listidcoa)
                            ->where('nl',2)
                            ->sum('kredit');
                    }else{
                        if($x5=='00000'){
                            $kode = substr($row->kode,0,2);
                            $listidcoa = Coa::select('id')
                                ->where('kode', 'like', "$kode%");
                            $debet = Jurnal::select('debet')
                                ->whereIn('idcoa',$listidcoa)
                                ->where('nl',2)
                                ->sum('debet');
                            $kredit = Jurnal::select('kredit')
                                ->whereIn('idcoa',$listidcoa)
                                ->where('nl',2)
                                ->sum('kredit');    
                        }else{
                            if($x4=='0000'){
                                $kode = substr($row->kode,0,3);
                                $listidcoa = Coa::select('id')
                                    ->where('kode', 'like', "$kode%");
                                $debet = Jurnal::select('debet')
                                    ->whereIn('idcoa',$listidcoa)
                                    ->where('nl',2)
                                    ->sum('debet');
                                $kredit = Jurnal::select('kredit')
                                    ->whereIn('idcoa',$listidcoa)
                                    ->where('nl',2)
                                    ->sum('kredit');
                            }else{
                                if($x3=='000'){
                                    $kode = substr($row->kode,0,4);
                                    $listidcoa = Coa::select('id')
                                        ->where('kode', 'like', "$kode%");
                                    $debet = Jurnal::select('debet')
                                        ->whereIn('idcoa',$listidcoa)
                                        ->where('nl',2)
                                        ->sum('debet');
                                    $kredit = Jurnal::select('kredit')
                                        ->whereIn('idcoa',$listidcoa)
                                        ->where('nl',2)
                                        ->sum('kredit');
                                }else{
                                    if($x2=='00'){
                                        $kode = substr($row->kode,0,5);
                                        $listidcoa = Coa::select('id')
                                            ->where('kode', 'like', "$kode%");
                                        $debet = Jurnal::select('debet')
                                            ->whereIn('idcoa',$listidcoa)
                                            ->where('nl',2)
                                            ->sum('debet');
                                        $kredit = Jurnal::select('kredit')
                                            ->whereIn('idcoa',$listidcoa)
                                            ->where('nl',2)
                                            ->sum('kredit');
                                    }else{
                                        $kode = substr($row->kode,0,6);
                                        $listidcoa = Coa::select('id')
                                            ->where('kode', 'like', "$kode%");
                                        $debet = Jurnal::select('debet')
                                            ->whereIn('idcoa',$listidcoa)
                                            ->where('nl',2)
                                            ->sum('debet');
                                        $kredit = Jurnal::select('kredit')
                                            ->whereIn('idcoa',$listidcoa)
                                            ->where('nl',2)
                                            ->sum('kredit');
                                    }

                                }    
                            }
                        }
                    }
                }else{
                    $kode = $row->kode;
                    $listidcoa = Coa::select('id')
                        ->where('kode', '=', $kode);
                    $debet = Jurnal::select('debet')
                        ->whereIn('idcoa',$listidcoa)
                        ->where('nl',2)
                        ->sum('debet');
                    $kredit = Jurnal::select('kredit')
                        ->whereIn('idcoa',$listidcoa)
                        ->where('nl',2)
                        ->sum('kredit');
                }
                
                if($debet>$kredit){
                    $debetx = $debet - $kredit;
                    $kreditx = 0;
                }else if($debet<$kredit){
                    $debetx = 0;
                    $kreditx = $kredit - $debet;
                }else{
                    $debetx = 0;
                    $kreditx = 0;
                }

                return '<a href="#" title="Edit Data" class="btn btn-success btn-xs item_edit" data="' . $row->id . '" data2="'. $row->kode.'" data3="'. $row->coa.'" data4="'. $row->idkategori.'" data5="'. $idkelompok.'" data6="'. $debetx.'" data7="'. $kreditx.'"><i style="font-size:18px" class="fa">&#xf044;</i></a> ' .
                       '<a href="#" title="Hapus Data" class="btn btn-danger btn-xs item_hapus" data="' . $row->id . '" data2="'. $row->kode.'" data3="'. $row->coa.'" data4="'. $row->idkategori.'" data5="'. $idkelompok.'"><i style="font-size:18px" class="fa">&#xf00d;</i></a>';
            })
            
            ->rawColumns(['coa','kelompok','kategori','debet','kredit','action'])

            ->make(true);

            return $data;
    }
   
    public function edit($id)
    {
        $data = Coa::where('id', '=', $id)->get();
        return json_encode(array('data' => $data));       
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        // 
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $data = Coa::where('id', '=', $id)->delete();
        return json_encode(array('data' => $data));
    }

    public function kirimsyarat(Request $request)
    {
        session([
            'idkelompok1' => $request['idkelompok1'],
            'idkategori1' => $request['idkategori1'],
            'idtab1' => $request['idtab1'],
        ]);
    }

    function listkelompok()
    {        
        $tampil = Kelompok::select('*')            
            ->get();           
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kode . ' - ' . $baris->kelompok . "</option>";
        }
    }

    function listkategori($id)
    {        
        $data = Kategori::where('idkelompok', '=', $id)->get();
        return json_encode(array('data' => $data));
    }

    function listkategori2()
    {        
        $tampil = Kategori::select('*')            
            ->get();           
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kode . ' - ' . $baris->kategori . "</option>";
        }
    }


}
