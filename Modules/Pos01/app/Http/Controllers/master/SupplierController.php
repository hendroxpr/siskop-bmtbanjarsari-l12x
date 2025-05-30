<?php

namespace Modules\Pos01\Http\Controllers\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DateTime;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Aplikasi;
use Modules\Pos01\Models\Supplier;
use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Storage;
use App\Models\Menusub;
use Modules\Pos01\Models\Hutang;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class SupplierController extends Controller
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
        $remark = 'Halaman ini digunakan untuk menampilkan, menambah, mengubah dan menghapus <b>Supplier</b>.';
        $page = 'pos01::master.supplier';
        $link = '/pos01/master/supplier';
        $subtitle = 'Master';
        $caption = 'Supplier';
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
            'tabel' => Supplier::SimplePaginate($jmlhal)->withQueryString(),
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
            'supplier1' => 'required',
        ]);
          
        //untuk input tabel yang asli
        
        $data = [            
            'kode' => $validatedData['kode1'],
            'supplier' => $validatedData['supplier1'],
            'alamat' => $request['alamat1'],
            'desa' => $request['desa1'],
            'kecamatan' => $request['kecamatan1'],
            'kabupaten' => $request['kabupaten1'],
            'provinsi' => $request['provinsi1'],
            'telp' => $request['telp1'],
            'wa' => $request['wa1'],
            'email' => $request['email1'],
            'web' => $request['web1'],
            'keterangan' => $request['keterangan1'],
        ];

        if ($id == '0') {
            Supplier::create($data);
        } else {
            Supplier::where('id', '=', $id)->update($data);
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
        
        $supplier = Supplier::get();
        $datax = DataTables::of($supplier                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('action', function ($row) {
                return '<a href="#" title="Edit Data" class="btn btn-success btn-xs item_edit" data="' . $row->id . '" data2="'. $row->kode.'" data3="'. $row->supplier.'" data4="'. $row->alamat.'"><i style="font-size:18px" class="fa">&#xf044;</i></a> ' .
                       '<a href="#" title="Hapus Data" class="btn btn-danger btn-xs item_hapus" data="' . $row->id . '" data2="'. $row->kode.'" data3="'. $row->supplier.'" data4="'. $row->alamat.'"><i style="font-size:18px" class="fa">&#xf00d;</i></a>';
            })
            
            ->rawColumns([
                'action'])


            ->make(true);

            return $data;

    }

    
    public function edit($id)
    {
        // $data = Supplier::where('id', '=', $id)->get();
        // return json_encode(array('data' => $data)); 
        
        $saldohutang = Hutang::where('idsupplier','=',$id)->sum('pokok');

        $data = Supplier::select('*')
        ->selectRaw('('. $saldohutang .') as saldohutang')
        ->where('id', '=', $id)  
        ->get();
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

        $data = Supplier::where('id', '=', $id)->delete();
        return json_encode(array('data' => $data));
    }


}
