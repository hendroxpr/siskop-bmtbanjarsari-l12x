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
use Modules\Akuntansi01\Models\Jenissimpanan;
use rawilk\Printing\Contracts\Printer;
use Rawilk\Printing\Facades\Printing;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class JenissimpananController extends Controller
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
        $remark = 'Halaman ini digunakan untuk menampilkan, menambah, mengubah dan menghapus <b>Jenis Simpanan</b>.';
        $page = 'akuntansi01::master.jenissimpanan';
        $link = '/akuntansi01/master/jenissimpanan';
        $subtitle = 'Master';
        $caption = 'Jenis Simpanan';
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
            'tabel' => Jenissimpanan::SimplePaginate($jmlhal)->withQueryString(),
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
            'idcoasetord1' => 'required',
            'idcoatarikd1' => 'required',
            'idcoatfkeluard1' => 'required',
            'idcoatfmasukd1' => 'required',
            'idcoaadmind1' => 'required',
            'idcoaujrohd1' => 'required',
            'idcoasetork1' => 'required',
            'idcoatarikk1' => 'required',
            'idcoatfkeluark1' => 'required',
            'idcoatfmasukk1' => 'required',
            'idcoaadmink1' => 'required',
            'idcoaujrohk1' => 'required',  

            'idjenisjurnalsetord1' => 'required',
            'idjenisjurnaltarikd1' => 'required',
            'idjenisjurnaltfkeluard1' => 'required',
            'idjenisjurnaltfmasukd1' => 'required',
            'idjenisjurnaladmind1' => 'required',
            'idjenisjurnalujrohd1' => 'required',
            'idjenisjurnalsetork1' => 'required',
            'idjenisjurnaltarikk1' => 'required',
            'idjenisjurnaltfkeluark1' => 'required',
            'idjenisjurnaltfmasukk1' => 'required',
            'idjenisjurnaladmink1' => 'required',
            'idjenisjurnalujrohk1' => 'required',            
        ]);
          
        //untuk input tabel yang asli
        $data = [            
            'kode' => $validatedData['kode1'],
            'jenissimpanan' => $request['jenissimpanan1'],
            'ujroha' => $request['ujroha1'],
            'ujrohb' => $request['ujrohb1'],
            'idcoasetord' => $validatedData['idcoasetord1'],
            'idcoatarikd' => $validatedData['idcoatarikd1'],
            'idcoatfkeluard' => $validatedData['idcoatfkeluard1'],
            'idcoatfmasukd' => $validatedData['idcoatfmasukd1'],
            'idcoaadmind' => $validatedData['idcoaadmind1'],
            'idcoaujrohd' => $validatedData['idcoaujrohd1'],
            'idcoasetork' => $validatedData['idcoasetork1'],
            'idcoatarikk' => $validatedData['idcoatarikk1'],
            'idcoatfkeluark' => $validatedData['idcoatfkeluark1'],
            'idcoatfmasukk' => $validatedData['idcoatfmasukk1'],
            'idcoaadmink' => $validatedData['idcoaadmink1'],
            'idcoaujrohk' => $validatedData['idcoaujrohk1'],
            'idjenisjurnalsetord' => $validatedData['idjenisjurnalsetord1'],
            'idjenisjurnaltarikd' => $validatedData['idjenisjurnaltarikd1'],
            'idjenisjurnaltfkeluard' => $validatedData['idjenisjurnaltfkeluard1'],
            'idjenisjurnaltfmasukd' => $validatedData['idjenisjurnaltfmasukd1'],
            'idjenisjurnaladmind' => $validatedData['idjenisjurnaladmind1'],
            'idjenisjurnalujrohd' => $validatedData['idjenisjurnalujrohd1'],
            'idjenisjurnalsetork' => $validatedData['idjenisjurnalsetork1'],
            'idjenisjurnaltarikk' => $validatedData['idjenisjurnaltarikk1'],
            'idjenisjurnaltfkeluark' => $validatedData['idjenisjurnaltfkeluark1'],
            'idjenisjurnaltfmasukk' => $validatedData['idjenisjurnaltfmasukk1'],
            'idjenisjurnaladmink' => $validatedData['idjenisjurnaladmink1'],
            'idjenisjurnalujrohk' => $validatedData['idjenisjurnalujrohk1'],
            'keterangan' => $request['keterangan1'],
        ];

        if ($id == '0') {
            Jenissimpanan::create($data);
        } else {
            Jenissimpanan::where('id', '=', $id)->update($data);
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
            Jenissimpanan::with([
                'coasetord', 'coasetork',
                'coatarikd', 'coatarikk',
                'coatfkeluard', 'coatfkeluark',
                'coatfmasukd', 'coatfmasukk',
                'coaadmind', 'coaadmink',
                'coaujrohd', 'coaujrohk',

                'jenisjurnalsetord', 'jenisjurnalsetork',
                'jenisjurnaltarikd', 'jenisjurnaltarikk',
                'jenisjurnaltfkeluard', 'jenisjurnaltfkeluark',
                'jenisjurnaltfmasukd', 'jenisjurnaltfmasukk',
                'jenisjurnaladmind', 'jenisjurnaladmink',
                'jenisjurnalujrohd', 'jenisjurnalujrohk',
                
                ])            
            // ->orderBy('kode','asc')                
            );

        $data = $datax
            ->addIndexColumn()
            
            ->addColumn('coasetord', function ($row) {                
                return $row->idcoasetord ? $row->coasetord->kode .'-' . $row->coasetord->coa . '<br>' .
                       $row->jenisjurnalsetord->kode .'-' . $row->jenisjurnalsetord->jenisjurnal : '';
            })
            ->addColumn('coatarikd', function ($row) {                
                return $row->idcoatarikd ? $row->coatarikd->kode .'-' . $row->coatarikd->coa . '<br>' .
                       $row->jenisjurnaltarikd->kode .'-' . $row->jenisjurnaltarikd->jenisjurnal : '';
            })
            ->addColumn('coatfkeluard', function ($row) {                
                return $row->idcoatfkeluard ? $row->coatfkeluard->kode .'-' . $row->coatfkeluard->coa . '<br>' .
                       $row->jenisjurnaltfkeluard->kode .'-' . $row->jenisjurnaltfkeluard->jenisjurnal : '';
            })
            ->addColumn('coatfmasukd', function ($row) {                
                return $row->idcoatfmasukd ? $row->coatfmasukd->kode .'-' . $row->coatfmasukd->coa . '<br>' .
                       $row->jenisjurnaltfmasukd->kode .'-' . $row->jenisjurnaltfmasukd->jenisjurnal : '';
            })
            ->addColumn('coaadmind', function ($row) {                
                return $row->idcoaadmind ? $row->coaadmind->kode .'-' . $row->coaadmind->coa . '<br>' .
                       $row->jenisjurnaladmind->kode .'-' . $row->jenisjurnaladmind->jenisjurnal : '';
            })
            ->addColumn('coaujrohd', function ($row) {                
                return $row->idcoaujrohd ? $row->coaujrohd->kode .'-' . $row->coaujrohd->coa . '<br>' .
                       $row->jenisjurnalujrohd->kode .'-' . $row->jenisjurnalujrohd->jenisjurnal : '';
            })

            ->addColumn('coasetork', function ($row) {                
                return $row->idcoasetork ? $row->coasetork->kode .'-' . $row->coasetork->coa . '<br>' .
                       $row->jenisjurnalsetork->kode .'-' . $row->jenisjurnalsetork->jenisjurnal : '';
            })
            ->addColumn('coatarikk', function ($row) {                
                return $row->idcoatarikk ? $row->coatarikk->kode .'-' . $row->coatarikk->coa . '<br>' .
                       $row->jenisjurnaltarikk->kode .'-' . $row->jenisjurnaltarikk->jenisjurnal : '';
            })
            ->addColumn('coatfkeluark', function ($row) {                
                return $row->idcoatfkeluark ? $row->coatfkeluark->kode .'-' . $row->coatfkeluark->coa . '<br>' .
                       $row->jenisjurnaltfkeluark->kode .'-' . $row->jenisjurnaltfkeluark->jenisjurnal : '';
            })
            ->addColumn('coatfmasukk', function ($row) {                
                return $row->idcoatfmasukk ? $row->coatfmasukk->kode .'-' . $row->coatfmasukk->coa . '<br>' .
                       $row->jenisjurnaltfmasukk->kode .'-' . $row->jenisjurnaltfmasukk->jenisjurnal : '';
            })
            ->addColumn('coaadmink', function ($row) {                
                return $row->idcoaadmink ? $row->coaadmink->kode .'-' . $row->coaadmink->coa . '<br>' .
                       $row->jenisjurnaladmink->kode .'-' . $row->jenisjurnaladmink->jenisjurnal : '';
            })
            ->addColumn('coaujrohk', function ($row) {                
                return $row->idcoaujrohk ? $row->coaujrohk->kode .'-' . $row->coaujrohk->coa . '<br>' .
                       $row->jenisjurnalujrohk->kode .'-' . $row->jenisjurnalujrohk->jenisjurnal : '';
            })
            ->addColumn('ujroh', function ($row) { 
                $ujrohx = $row->ujroha / $row->ujrohb * 100;               
                return number_format($ujrohx,2) . '%';
            })
            
            ->addColumn('action', function ($row) {
                return '<a href="#" title="Edit Data" class="btn btn-success btn-xs item_edit" data="' . $row->id . '" data2="'. $row->kode.'" data3="'. $row->jenissimpanan.'" data4="'. $row->jenissimpanan.'"><i style="font-size:18px" class="fa">&#xf044;</i></a> ' .
                       '<a href="#" title="Hapus Data" class="btn btn-danger btn-xs item_hapus" data="' . $row->id . '" data2="'. $row->kode.'" data3="'. $row->jenissimpanan.'" data4="'. $row->jenissimpanan.'"><i style="font-size:18px" class="fa">&#xf00d;</i></a>';
            })
            
            ->rawColumns([
                'ujroh',
                'coasetord', 'coasetork',
                'coatarikd', 'coatarikk',
                'coatfkeluard', 'coatfkeluark',
                'coatfmasukd', 'coatfmasukk',
                'coaadmind', 'coaadmink',
                'coaujrohd', 'coaujrohk',

                'jenisjurnalsetord', 'jenisjurnalsetork',
                'jenisjurnaltarikd', 'jenisjurnaltarikk',
                'jenisjurnaltfkeluard', 'jenisjurnaltfkeluark',
                'jenisjurnaltfmasukd', 'jenisjurnaltfmasukk',
                'jenisjurnaladmind', 'jenisjurnaladmink',
                'jenisjurnalujrohd', 'jenisjurnalujrohk',

                'action'
                ])

            ->make(true);

            return $data;

    }

    
    public function edit($id)
    {
        $data = Jenissimpanan::where('id', '=', $id)->get();
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
        $data = Jenissimpanan::where('id', '=', $id)->delete();
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
