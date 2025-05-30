<?php

namespace Modules\Pos01\Http\Controllers\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DateTime;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Aplikasi;
use Modules\Pos01\Models\Kategori;
use Modules\Pos01\Models\Satuan;
use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Storage;
use App\Models\Menusub;
use Modules\Pos01\Models\Mamin;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class MaminController extends Controller
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
        $remark = 'Halaman ini digunakan untuk menampilkan, menambah, mengubah dan menghapus <b>Makanan & Minuman</b>.';
        $page = 'pos01::master.mamin';
        $link = '/pos01/master/mamin';
        $subtitle = 'Master';
        $caption = 'Makanan & Minuman';
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
            'tabel' => Mamin::SimplePaginate($jmlhal)->withQueryString(),
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
        $barcode = $request['barcode1'];     

        $slug = Str::slug($request['namamin1']);

        $tampil = Mamin::where('id', '=', $id)->get();
        foreach ($tampil as $item) {
            $image = $item->image;            
        }

        

        if ($id == '0') {
            if ($request->hasFile('image1')) {
                $validateimage = $request->validate([
                    'image1' => 'file|max:2048',
                ]);
                if ($validateimage) {
                    $image1 = $request->file('image1')->store('pos01-barang-images');
                } else {
                    $image1 = '';
                }
            } else {
                $image1 = '';
            }
            
        } else {
            if ($request->hasFile('image1')) {
                $validateimage = $request->validate([
                    'image1' => 'file|max:2048',
                ]);
                if ($validateimage) {
                    if ($image) {
                        storage::delete($image);
                        $image1 = $request->file('image1')->store('pos01-mamin-images');
                    } else {
                        $image1 = $request->file('image1')->store('pos01-mamin-images');
                    }
                } else {
                    $image1 = $image;
                }
            } else {
                $image1 = $image;
            }
        }

        $validatedData = $request->validate([
            'idsatuan1' => 'required',
            'idkategori1' => 'required',
            'barcode1' => 'required',
            'namamin1' => 'required',
        ]);
          
        //untuk input tabel yang asli
        
        $data = [            
            'idsatuan' => $validatedData['idsatuan1'],
            'idkategori' => $validatedData['idkategori1'],
            'kode' => $request['kode1'],
            'barcode' => $validatedData['barcode1'],
            'namamin' => $validatedData['namamin1'],
            'slug' => $slug,
            'hjs' => $request['hjs1'],
            'diskonjual' => $request['diskonjual1'],
            'ppnjual' => $request['ppnjual1'],
            'spek' => $request['spek1'],
            'expired' => $request['expired1'],
            'image' => $image1,
            'keterangan' => $request['keterangan1'],
        ];

        if ($id == '0') {
            Mamin::create($data);            

        } else {
            
            Mamin::where('id', '=', $id)->update($data);
            

        }

        
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
        
        $mamin = Mamin::with(['kategori','satuan'])->get();
        $datax = DataTables::of($mamin                          
            );

        $data = $datax
            ->addIndexColumn()
            // $total = Invoice::where('status', 'Completed')
            // ->sum('amount');
            ->addColumn('kategori', function ($row) {
                return $row->idkategori ? $row->kategori->kategori : '';
            })
            ->addColumn('satuan', function ($row) {
                return $row->idsatuan ? $row->satuan->satuan : '';
            })

            ->addColumn('hjs', function ($row) {
                return $row->hjs ? number_format($row->hjs,0) : '';
            })

            ->addColumn('image', function ($row) {
                return $row->image ? '<img class="mb-4" style="width: 100%" src="'. route('front.index') . '/storage/'. $row->image.'">' : '';
            })
           
            ->addColumn('action', function ($row) {
                return '<a href="#" title="Edit Data" class="btn btn-success btn-xs item_edit" data="' . $row->id . '" data2="'. $row->barcode.'" data3="'. $row->nabara.'" data4="'. $row->nabara.'"><i style="font-size:18px" class="fa">&#xf044;</i></a> ' .
                       '<a href="#" title="Hapus Data" class="btn btn-danger btn-xs item_hapus" data="' . $row->id . '" data2="'. $row->barcode.'" data3="'. $row->nabara.'" data4="'. $row->nabara.'"><i style="font-size:18px" class="fa">&#xf00d;</i></a>';
            })
            
            ->rawColumns([
                'hjs',
                'image',
                'action'])


            ->make(true);

            return $data;

    }
   
    public function showmamin()
    {
        
        $mamin = Mamin::with(['kategori','satuan'])->get();
        $datax = DataTables::of($mamin                          
            );

        $data = $datax
            ->addIndexColumn()

            ->addColumn('kategori', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->idkategori ? $row->kategori->kategori : '') .'" class="item_kategori " data1="' . $row->id . '" data2="'. $row->barcode. '" data3="'. $row->namamin. '" data4="'. $row->kategori->kategori.'" data5="'. $row->satuan->satuan.'">'.($row->idkategori ? $row->kategori->kategori : "").'</a>';
            })
            ->addColumn('satuan', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->idsatuan ? $row->satuan->satuan : '') .'" class="item_satuan " data1="' . $row->id . '" data2="'. $row->barcode. '" data3="'. $row->namamin. '" data4="'. $row->kategori->kategori.'" data5="'. $row->satuan->satuan.'">'.($row->idsatuan ? $row->satuan->satuan : "").'</a>';
            })
            
            ->addColumn('barcode', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->barcode ? $row->barcode : '') .'" class="item_barcode " data1="' . $row->id . '" data2="'. $row->barcode. '" data3="'. $row->namamin. '" data4="'. $row->kategori->kategori.'" data5="'. $row->satuan->satuan.'">'.($row->barcode ? $row->barcode : "").'</a>';
            })
            ->addColumn('namamin', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->namamin ? $row->namamin : '') .'" class="item_namamin " data1="' . $row->id . '" data2="'. $row->barcode. '" data3="'. $row->namamin. '" data4="'. $row->kategori->kategori.'" data5="'. $row->satuan->satuan.'">'.($row->nabara ? $row->namamin : "").'</a>';
            })
            
            ->addColumn('hjs', function ($row) {
                return $row->hjs ? number_format($row->hjs,0) : '';
            })

            ->addColumn('image', function ($row) {
                $imagex = '<img class="mb-4" style="width: 100%" src="'. route('front.index') . '/storage/'. $row->image.'">';
                return '<a href="#" style="color: white;" title="'. ($row->nabara ? $row->nabara : '') .'" class="item_image " data1="' . $row->id . '" data2="'. $row->barcode. '" data3="'. $row->nabara. '" data4="'. $row->kategori->kategori.'" data5="'. $row->satuan->satuan.'">'.($row->image ? $imagex : "") .'</a>';
            })
           
            ->addColumn('action', function ($row) {
                return '<a href="#" title="Edit Data" class="btn btn-success btn-xs item_edit" data="' . $row->id . '" data2="'. $row->barcode.'" data3="'. $row->namamin.'" data4="'. $row->namamin.'"><i style="font-size:18px" class="fa">&#xf044;</i></a> ' .
                       '<a href="#" title="Hapus Data" class="btn btn-danger btn-xs item_hapus" data="' . $row->id . '" data2="'. $row->barcode.'" data3="'. $row->namamin.'" data4="'. $row->namamin.'"><i style="font-size:18px" class="fa">&#xf00d;</i></a>';
            })
            
            ->rawColumns([
                'kategori',
                'satuan',
                'barcode',
                'namamin',
                'hjs',
                'image',
                'action'])


            ->make(true);

            return $data;

    }

    
    public function edit($id)
    {
        $data = Mamin::where('id', '=', $id)->get();
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
        $tampil = Mamin::where('id', '=', $id)->get();
        foreach ($tampil as $item) {
            $image = $item->image;            
        }

        if($image){
            storage::delete($image);
        }
        $data = Mamin::where('id', '=', $id)->delete();
        return json_encode(array('data' => $data));
    }

    function listkategori()
    {
        $tampil = Kategori::orderBy('kategori', 'asc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kategori . "</option>";
        }
    }
    function listsatuan()
    {
        $tampil = Satuan::orderBy('satuan', 'asc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->satuan . "</option>";
        }
    }


}
