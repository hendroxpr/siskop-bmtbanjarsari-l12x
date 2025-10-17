<?php

namespace Modules\Admin01\Http\Controllers\master;

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
use Modules\Admin01\Models\Propinsi;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class PropinsiController extends Controller
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
        $remark = 'Halaman ini digunakan untuk menampilkan, menambah, mengubah dan menghapus <b>Propinsi</b>.';
        $page = 'admin01::master.propinsi';
        $link = '/admin01/master/propinsi';
        $subtitle = 'Master';
        $caption = 'Propinsi';
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
            'tabel' => Propinsi::SimplePaginate($jmlhal)->withQueryString(),
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

        $slug = Str::slug($request['propinsi1']);

        $validatedData = $request->validate([
            'kode1' => 'required',
            'propinsi1' => 'required',
        ]);
          
        //untuk input tabel yang asli
        
        $data = [            
            'kode' => $validatedData['kode1'],
            'propinsi' => $validatedData['propinsi1'],
            'slug' => $slug,
            'inisial' => $request['inisial1'],
            'ibukota' => $request['ibukota1'],
        ];

        if ($id == '0') {
            Propinsi::create($data);
        } else {
            Propinsi::where('id', '=', $id)->update($data);
        }
        // return json_encode('data');
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
        
        $datax = Propinsi::get();
        
        $data = DataTables::of($datax)
            ->addIndexColumn()
           
            ->addColumn('action', function ($row) {
                return '<a href="#" title="Edit Data" class="btn btn-success btn-xs item_edit" data="' . $row->id . '" data2="'. $row->kode.'" data3="'. $row->propinsi.'" data4="'. $row->ibukota.'"><i style="font-size:18px" class="fa">&#xf044;</i></a> ' .
                       '<a href="#" title="Hapus Data" class="btn btn-danger btn-xs item_hapus" data="' . $row->id . '" data2="'. $row->kode.'" data3="'. $row->propinsi.'" data4="'. $row->ibukota.'"><i style="font-size:18px" class="fa">&#xf00d;</i></a>';
            })
            
            ->rawColumns([
                'action'])


            ->make(true);

            return $data;

    }

    
    public function edit($id)
    {
        $data = Propinsi::where('id', '=', $id)->get();
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

        $data = Propinsi::where('id', '=', $id)->delete();
        return json_encode(array('data' => $data));
    }


}
