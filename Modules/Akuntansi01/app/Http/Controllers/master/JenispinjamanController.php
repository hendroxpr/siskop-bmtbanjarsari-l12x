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
use Modules\Akuntansi01\Models\Jenisjurnal;
use Modules\Akuntansi01\Models\Jenispinjaman;
// use Modules\Akuntansi01\Models\Coa;
// use Modules\Akuntansi01\Models\Jenisjurnal;
// use Modules\Akuntansi01\Models\Setingjurnal;
use rawilk\Printing\Contracts\Printer;
use Rawilk\Printing\Facades\Printing;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class JenispinjamanController extends Controller
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
        $remark = 'Halaman ini digunakan untuk menampilkan, menambah, mengubah dan menghapus <b>Jenis Pinjaman</b>.';
        $page = 'akuntansi01::master.jenispinjaman';
        $link = '/akuntansi01/master/jenispinjaman';
        $subtitle = 'Master';
        $caption = 'Jenis Pinjaman';
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
            'tabel' => Jenispinjaman::SimplePaginate($jmlhal)->withQueryString(),
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
            'kode1' => 'required',
            'idcoa01d1' => 'required',
            'idcoa02d1' => 'required',
            'idcoa03d1' => 'required',
            'idcoa04d1' => 'required',
            'idcoa05d1' => 'required',
            'idcoa06d1' => 'required',
            'idcoa01k1' => 'required',
            'idcoa02k1' => 'required',
            'idcoa03k1' => 'required',
            'idcoa04k1' => 'required',
            'idcoa05k1' => 'required',
            'idcoa06k1' => 'required',  

            'idjenisjurnal01d1' => 'required',
            'idjenisjurnal02d1' => 'required',
            'idjenisjurnal03d1' => 'required',
            'idjenisjurnal04d1' => 'required',
            'idjenisjurnal05d1' => 'required',
            'idjenisjurnal06d1' => 'required',
            'idjenisjurnal01k1' => 'required',
            'idjenisjurnal02k1' => 'required',
            'idjenisjurnal03k1' => 'required',
            'idjenisjurnal04k1' => 'required',
            'idjenisjurnal05k1' => 'required',
            'idjenisjurnal06k1' => 'required',            
        ]);
          
        //untuk input tabel yang asli
        $data = [            
            'kode' => $validatedData['kode1'],
            'jenispinjaman' => $request['jenispinjaman1'],
            'ujroha' => $request['ujroha1'],
            'ujrohb' => $request['ujrohb1'],
            'idcoa01d' => $validatedData['idcoa01d1'],
            'idcoa02d' => $validatedData['idcoa02d1'],
            'idcoa03d' => $validatedData['idcoa03d1'],
            'idcoa04d' => $validatedData['idcoa04d1'],
            'idcoa05d' => $validatedData['idcoa05d1'],
            'idcoa06d' => $validatedData['idcoa06d1'],
            'idcoa01k' => $validatedData['idcoa01k1'],
            'idcoa02k' => $validatedData['idcoa02k1'],
            'idcoa03k' => $validatedData['idcoa03k1'],
            'idcoa04k' => $validatedData['idcoa04k1'],
            'idcoa05k' => $validatedData['idcoa05k1'],
            'idcoa06k' => $validatedData['idcoa06k1'],
            'idjenisjurnal01d' => $validatedData['idjenisjurnal01d1'],
            'idjenisjurnal02d' => $validatedData['idjenisjurnal02d1'],
            'idjenisjurnal03d' => $validatedData['idjenisjurnal03d1'],
            'idjenisjurnal04d' => $validatedData['idjenisjurnal04d1'],
            'idjenisjurnal05d' => $validatedData['idjenisjurnal05d1'],
            'idjenisjurnal06d' => $validatedData['idjenisjurnal06d1'],
            'idjenisjurnal01k' => $validatedData['idjenisjurnal01k1'],
            'idjenisjurnal02k' => $validatedData['idjenisjurnal02k1'],
            'idjenisjurnal03k' => $validatedData['idjenisjurnal03k1'],
            'idjenisjurnal04k' => $validatedData['idjenisjurnal04k1'],
            'idjenisjurnal05k' => $validatedData['idjenisjurnal05k1'],
            'idjenisjurnal06k' => $validatedData['idjenisjurnal06k1'],
            'keterangan' => $request['keterangan1'],
        ];

        if ($id == '0') {
            Jenispinjaman::create($data);
        } else {
            Jenispinjaman::where('id', '=', $id)->update($data);
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

        $datax = DataTables::of(
            Jenispinjaman::with([
                'coa01d', 'coa01k',
                'coa02d', 'coa02k',
                'coa03d', 'coa03k',
                'coa04d', 'coa04k',
                'coa05d', 'coa05k',
                'coa06d', 'coa06k',

                'jenisjurnal01d', 'jenisjurnal01k',
                'jenisjurnal02d', 'jenisjurnal02k',
                'jenisjurnal03d', 'jenisjurnal03k',
                'jenisjurnal04d', 'jenisjurnal04k',
                'jenisjurnal05d', 'jenisjurnal05k',
                'jenisjurnal06d', 'jenisjurnal06k',
                
                ])            
            // ->orderBy('kode','asc')                
            );

        $data = $datax
            ->addIndexColumn()
            
            ->addColumn('coa01d', function ($row) {                
                return $row->idcoa01d ? $row->coa01d->kode .'-' . $row->coa01d->coa . '<br>' .
                       $row->jenisjurnal01d->kode .'-' . $row->jenisjurnal01d->jenisjurnal : '';
            })
            ->addColumn('coa02d', function ($row) {                
                return $row->idcoa02d ? $row->coa02d->kode .'-' . $row->coa02d->coa . '<br>' .
                       $row->jenisjurnal02d->kode .'-' . $row->jenisjurnal02d->jenisjurnal : '';
            })
            ->addColumn('coa03d', function ($row) {                
                return $row->idcoa03d ? $row->coa03d->kode .'-' . $row->coa03d->coa . '<br>' .
                       $row->jenisjurnal03d->kode .'-' . $row->jenisjurnal03d->jenisjurnal : '';
            })
            ->addColumn('coa04d', function ($row) {                
                return $row->idcoa04d ? $row->coa04d->kode .'-' . $row->coa04d->coa . '<br>' .
                       $row->jenisjurnal04d->kode .'-' . $row->jenisjurnal04d->jenisjurnal : '';
            })
            ->addColumn('coa05d', function ($row) {                
                return $row->idcoa05d ? $row->coa05d->kode .'-' . $row->coa05d->coa . '<br>' .
                       $row->jenisjurnal05d->kode .'-' . $row->jenisjurnal05d->jenisjurnal : '';
            })
            ->addColumn('coa06d', function ($row) {                
                return $row->idcoa06d ? $row->coa06d->kode .'-' . $row->coa06d->coa . '<br>' .
                       $row->jenisjurnal06d->kode .'-' . $row->jenisjurnal06d->jenisjurnal : '';
            })

            ->addColumn('coa01k', function ($row) {                
                return $row->idcoa01k ? $row->coa01k->kode .'-' . $row->coa01k->coa . '<br>' .
                       $row->jenisjurnal01k->kode .'-' . $row->jenisjurnal01k->jenisjurnal : '';
            })
            ->addColumn('coa02k', function ($row) {                
                return $row->idcoa02k ? $row->coa02k->kode .'-' . $row->coa02k->coa . '<br>' .
                       $row->jenisjurnal02k->kode .'-' . $row->jenisjurnal02k->jenisjurnal : '';
            })
            ->addColumn('coa03k', function ($row) {                
                return $row->idcoa03k ? $row->coa03k->kode .'-' . $row->coa03k->coa . '<br>' .
                       $row->jenisjurnal03k->kode .'-' . $row->jenisjurnal03k->jenisjurnal : '';
            })
            ->addColumn('coa04k', function ($row) {                
                return $row->idcoa04k ? $row->coa04k->kode .'-' . $row->coa04k->coa . '<br>' .
                       $row->jenisjurnal04k->kode .'-' . $row->jenisjurnal04k->jenisjurnal : '';
            })
            ->addColumn('coa05k', function ($row) {                
                return $row->idcoa05k ? $row->coa05k->kode .'-' . $row->coa05k->coa . '<br>' .
                       $row->jenisjurnal05k->kode .'-' . $row->jenisjurnal05k->jenisjurnal : '';
            })
            ->addColumn('coa06k', function ($row) {                
                return $row->idcoa06k ? $row->coa06k->kode .'-' . $row->coa06k->coa . '<br>' .
                       $row->jenisjurnal06k->kode .'-' . $row->jenisjurnal06k->jenisjurnal : '';
            })
            ->addColumn('ujroh', function ($row) { 
                $ujrohx = $row->ujroha / $row->ujrohb * 100;               
                return number_format($ujrohx,2) . '%';
            })
            
            ->addColumn('action', function ($row) {
                return '<a href="#" title="Edit Data" class="btn btn-success btn-xs item_edit" data="' . $row->id . '" data2="'. $row->kode.'" data3="'. $row->jenispinjaman.'" data4="'. $row->jenispinjaman.'"><i style="font-size:18px" class="fa">&#xf044;</i></a> ' .
                       '<a href="#" title="Hapus Data" class="btn btn-danger btn-xs item_hapus" data="' . $row->id . '" data2="'. $row->kode.'" data3="'. $row->jenispinjaman.'" data4="'. $row->jenispinjaman.'"><i style="font-size:18px" class="fa">&#xf00d;</i></a>';
            })
            
            ->rawColumns([
                'ujroh',
                'coa01d', 'coa01k',
                'coa02d', 'coa02k',
                'coa03d', 'coa03k',
                'coa04d', 'coa04k',
                'coa05d', 'coa05k',
                'coa06d', 'coa06k',

                'jenisjurnal01d', 'jenisjurnal01k',
                'jenisjurnal02d', 'jenisjurnal02k',
                'jenisjurnal03d', 'jenisjurnal03k',
                'jenisjurnal04d', 'jenisjurnal04k',
                'jenisjurnal05d', 'jenisjurnal05k',
                'jenisjurnal06d', 'jenisjurnal06k',

                'action'
                ])

            ->make(true);

            return $data;

    }

    
    public function edit($id)
    {
        $data = Jenispinjaman::where('id', '=', $id)->get();
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
        $data = Jenispinjaman::where('id', '=', $id)->delete();
        return json_encode(array('data' => $data));
    }
    
    function listcoa()
    {        
        $tampil = Coa::select('*') 
            ->where('hd','=','D')
            ->orderBy('kode','asc')           
            ->get();           
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kode . '-' . $baris->coa . "</option>";
        }

        // $printnya = Printing::printers();
        // foreach ($printnya as $printer) {
        //     $printx = $printer->name();
            
        // }
        // echo "<option value='" . $printx . "'>" . $printx . "</option>";


    }
    function listjenisjurnal()
    {        
        $tampil = Jenisjurnal::select('*')                         
            ->get();           
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kode . '-' . $baris->jenisjurnal . "</option>";
        }
    }


}
