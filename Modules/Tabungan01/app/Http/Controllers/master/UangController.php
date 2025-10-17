<?php

namespace Modules\Tabungan01\Http\Controllers\master;
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
use Modules\Tabungan01\Models\Uang;
use rawilk\Printing\Contracts\Printer;
use Rawilk\Printing\Facades\Printing;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class UangController extends Controller
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
        $remark = 'Halaman ini digunakan untuk menampilkan, menambah, mengubah dan menghapus <b>Pecahan Uang</b>.';
        $page = 'tabungan01::master.uang';
        $link = '/tabungan01/master/uang';
        $subtitle = 'Master';
        $caption = 'Pecahan Uang';
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
            'tabel' => Uang::SimplePaginate($jmlhal)->withQueryString(),
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
            'uang1' => 'required',            
        ]);

        $tampil = Uang::where('id', '=', $id)->get();
        foreach ($tampil as $item) {
            $gallery = $item->gambar;            
        }

        if ($id == '0') {

            if ($request->hasFile('gallery1')) {
                $validategallery = $request->validate([
                    'gallery1' => 'file|max:2048',
                ]);
                if ($validategallery) {
                    $gallery1 = $request->file('gallery1')->store('tabungan01-uang-gambar');
                } else {
                    $gallery1 = '';
                }
            } else {
                $gallery1 = '';
            }
            
        } else {

            if ($request->hasFile('gallery1')) {
                $validategallery = $request->validate([
                    'gallery1' => 'file|max:2048',
                ]);
                if ($validategallery) {
                    if ($gallery) {
                        storage::delete($gallery);
                        $gallery1 = $request->file('gallery1')->store('tabungan01-uang-gambar');
                    } else {
                        $gallery1 = $request->file('gallery1')->store('tabungan01-uang-gambar');
                    }
                } else {
                    $gallery1 = $gallery;
                }
            } else {
                $gallery1 = $gallery;
            }
        }
          
        //untuk input tabel yang asli
        $data = [            
            'uang' => $validatedData['uang1'],
            'bahan' => $request['bahan1'],
            'nilai' => $request['nilai1'],
            'gambar' => $gallery1,            
            'keterangan' => $request['keterangan1'],
        ];

        if ($id == '0') {
            Uang::create($data);
        } else {
            Uang::where('id', '=', $id)->update($data);
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
            Uang::orderBy('bahan','desc')
            ->orderBy('nilai','asc')                
            );

        $data = $datax
            ->addIndexColumn()
            
            ->addColumn('gambar', function ($row) {
                return $row->gambar ? '<img class="mb-4" style="width: 100%" src="'. route('front.index') . '/storage/'. $row->gambar.'">' : '';
            })

            ->addColumn('nilai', function ($row) {
                return $row->nilai ? number_format($row->nilai,0) : '';
            })

            ->addColumn('action', function ($row) {
                return '<a href="#" title="Edit Data" class="btn btn-success btn-xs item_edit" data="' . $row->id . '" data2="'. $row->uang.'" data3="'. $row->bahan.'" data4="'. $row->nilai.'"><i style="font-size:18px" class="fa">&#xf044;</i></a> ' .
                       '<a href="#" title="Hapus Data" class="btn btn-danger btn-xs item_hapus" data="' . $row->id . '" data2="'. $row->uang.'" data3="'. $row->bahan.'" data4="'. $row->nilai.'"><i style="font-size:18px" class="fa">&#xf00d;</i></a>';
            })
            
            ->rawColumns(['gambar','action'])

            ->make(true);

            return $data;

    }
    public function edit($id)
    {
        $data = Uang::where('id', '=', $id)->get();
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
        $tampil = Uang::where('id', '=', $id)->get();
        foreach ($tampil as $item) {
            $gallery = $item->gambar;                      
        }

        if($gallery){
            storage::delete($gallery);
        }
        $data = Uang::where('id', '=', $id)->delete();
        
        return json_encode(array('data' => $data));
    }
    
    function listbahan()
    {        
         echo "<option value='Koin'>Koin</option>";
         echo "<option value='Kertas'>Kertas</option>";
    }
    


}
