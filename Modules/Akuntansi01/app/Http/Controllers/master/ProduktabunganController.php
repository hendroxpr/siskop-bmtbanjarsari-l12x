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
use Modules\Akuntansi01\Models\Produk;
use Modules\Akuntansi01\Models\Produktabungan;
use rawilk\Printing\Contracts\Printer;
use Rawilk\Printing\Facades\Printing;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class ProduktabunganController extends Controller
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
        $remark = 'Halaman ini digunakan untuk menampilkan, menambah, mengubah dan menghapus <b>Produk Tabungan</b>.';
        $page = 'akuntansi01::master.produktabungan';
        $link = '/akuntansi01/master/produktabungan';
        $subtitle = 'Master';
        $caption = 'Produk Tabungan';
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
            'tabel' => Produktabungan::SimplePaginate($jmlhal)->withQueryString(),
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
            'produktabungan1' => 'required',
            'idcoasetord1' => 'required',
            'idcoasetork1' => 'required',
            'idcoatarikd1' => 'required',
            'idcoatarikk1' => 'required',
            'idcoatfkeluard1' => 'required',
            'idcoatfkeluark1' => 'required',
            'idcoatfmasukd1' => 'required',
            'idcoatfmasukk1' => 'required',

            'idjenisjurnalsetord1' => 'required',
            'idjenisjurnalsetork1' => 'required',
            'idjenisjurnaltarikd1' => 'required',
            'idjenisjurnaltarikk1' => 'required',
            'idjenisjurnaltfkeluard1' => 'required',
            'idjenisjurnaltfkeluark1' => 'required',
            'idjenisjurnaltfmasukd1' => 'required',
            'idjenisjurnaltfmasukk1' => 'required',
        ]);
          
        //untuk input tabel yang asli
        $data = [            
            'kode' => $validatedData['kode1'],
            'produktabungan' => $validatedData['produktabungan1'],
            'idcoasetord' => $validatedData['idcoasetord1'],
            'idcoasetork' => $validatedData['idcoasetork1'],
            'idcoatarikd' => $validatedData['idcoatarikd1'],
            'idcoatarikk' => $validatedData['idcoatarikk1'],
            'idcoatfkeluard' => $validatedData['idcoatfkeluard1'],
            'idcoatfkeluark' => $validatedData['idcoatfkeluark1'],
            'idcoatfmasukd' => $validatedData['idcoatfmasukd1'],
            'idcoatfmasukk' => $validatedData['idcoatfmasukk1'],

            'idjenisjurnalsetord' => $validatedData['idjenisjurnalsetord1'],
            'idjenisjurnalsetork' => $validatedData['idjenisjurnalsetork1'],
            'idjenisjurnaltarikd' => $validatedData['idjenisjurnaltarikd1'],
            'idjenisjurnaltarikk' => $validatedData['idjenisjurnaltarikk1'],
            'idjenisjurnaltfkeluard' => $validatedData['idjenisjurnaltfkeluard1'],
            'idjenisjurnaltfkeluark' => $validatedData['idjenisjurnaltfkeluark1'],
            'idjenisjurnaltfmasukd' => $validatedData['idjenisjurnaltfmasukd1'],
            'idjenisjurnaltfmasukk' => $validatedData['idjenisjurnaltfmasukk1'],

            'keterangan' => $request['keterangan1'],
        ];

        if ($id == '0') {
            Produktabungan::create($data);
        } else {
            Produktabungan::where('id', '=', $id)->update($data);
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
            Produktabungan::with([
                'coasetord','coasetork','coatarikd','coatarikk',
                'coatfkeluard','coatfkeluark',
                'coatfmasukd','coatfmasukk',
                'jenisjurnalsetord','jenisjurnalsetork','jenisjurnaltarikd','jenisjurnaltarikk',
                'jenisjurnaltfkeluard','jenisjurnaltfkeluark',
                'jenisjurnaltfmasukd','jenisjurnaltfmasukk',
                ])            
            // ->orderBy('namalengkap','asc')                
            );

        $data = $datax
            ->addIndexColumn()
            
            ->addColumn('coasetord', function ($row) {
                //apikey = wob5hCPsziT_WG7NVU1Cb2_av4399TF_qop8dnugY3c
                // $printnya = Printing::printers();
                // foreach ($printnya as $printer) {
                //     return $printer->name();
                    
                // }
                return $row->idcoasetord ? $row->coasetord->kode .'-' . $row->coasetord->coa . '<br>' .
                       $row->jenisjurnalsetord->kode .'-' . $row->jenisjurnalsetord->jenisjurnal : '';
            })
            ->addColumn('coasetork', function ($row) {
                return $row->idcoasetork ? $row->coasetork->kode .'-' . $row->coasetork->coa . '<br>' .
                        $row->jenisjurnalsetork->kode .'-' . $row->jenisjurnalsetork->jenisjurnal: '';
            })
            ->addColumn('coatarikd', function ($row) {
                return $row->idcoatarikd ? $row->coatarikd->kode .'-' . $row->coatarikd->coa . '<br>' .
                        $row->jenisjurnaltarikd->kode .'-' . $row->jenisjurnaltarikd->jenisjurnal: '';
            })
            ->addColumn('coatarikk', function ($row) {
                return $row->idcoatarikk ? $row->coatarikk->kode .'-' . $row->coatarikk->coa . '<br>' .
                        $row->jenisjurnaltarikk->kode .'-' . $row->jenisjurnaltarikk->jenisjurnal: '';
            })
            ->addColumn('coatfmasukd', function ($row) {
                return $row->idcoatfmasukd ? $row->coatfmasukd->kode .'-' . $row->coatfmasukd->coa . '<br>' .
                        $row->jenisjurnaltfmasukd->kode .'-' . $row->jenisjurnaltfmasukd->jenisjurnal : '';
            })
            ->addColumn('coatfmasukk', function ($row) {
                return $row->idcoatfmasukk ? $row->coatfmasukk->kode .'-' . $row->coatfmasukk->coa . '<br>' .
                        $row->jenisjurnaltfmasukk->kode .'-' . $row->jenisjurnaltfmasukk->jenisjurnal : '';
            })
            ->addColumn('coatfkeluard', function ($row) {
                return $row->idcoatfkeluard ? $row->coatfkeluard->kode .'-' . $row->coatfkeluard->coa . '<br>' . 
                        $row->jenisjurnaltfkeluard->kode .'-' . $row->jenisjurnaltfkeluard->jenisjurnal: '';
            })
            ->addColumn('coatfkeluark', function ($row) {
                return $row->idcoatfkeluark ? $row->coatfkeluark->kode .'-' . $row->coatfkeluark->coa . '<br>' .
                        $row->jenisjurnaltfkeluark->kode .'-' . $row->jenisjurnaltfkeluark->jenisjurnal : '';
            })
            
            ->addColumn('action', function ($row) {
                return '<a href="#" title="Edit Data" class="btn btn-success btn-xs item_edit" data="' . $row->id . '" data2="'. $row->produk.'" data3="'. $row->produk.'" data4="'. $row->produk.'"><i style="font-size:18px" class="fa">&#xf044;</i></a> ' .
                       '<a href="#" title="Hapus Data" class="btn btn-danger btn-xs item_hapus" data="' . $row->id . '" data2="'. $row->produk.'" data3="'. $row->produk.'" data4="'. $row->produk.'"><i style="font-size:18px" class="fa">&#xf00d;</i></a>';
            })
            
            ->rawColumns(['coasetord','coasetork','coatarikd','coatarikk',
                'coatfmasukd','coatfmasukk','coatfkeluard','coatfkeluark',
                'action'])

            ->make(true);

            return $data;

    }

    
    public function edit($id)
    {
        $data = Produktabungan::where('id', '=', $id)->get();
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
        $data = Produktabungan::where('id', '=', $id)->delete();
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
