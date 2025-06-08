<?php

namespace Modules\Pos01\Http\Controllers\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use DateTime;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Aplikasi;
use Modules\Pos01\Models\Barang;
use Modules\Pos01\Models\Kategori;
use Modules\Pos01\Models\Satuan;
use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Storage;
use App\Models\Menusub;
use Modules\Pos01\Models\Barangruang;
use Modules\Pos01\Models\Barcode;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class BarangController extends Controller
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
        $remark = 'Halaman ini digunakan untuk menampilkan, menambah, mengubah dan menghapus <b>Barang</b>.';
        $page = 'pos01::master.barang';
        $link = '/pos01/master/barang';
        $subtitle = 'Master';
        $caption = 'Barang';
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
            'tabel' => Barang::SimplePaginate($jmlhal)->withQueryString(),
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

        $slug = Str::slug($request['nabara1']);

        $tampil = Barang::where('id', '=', $id)->get();
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
                        $image1 = $request->file('image1')->store('pos01-barang-images');
                    } else {
                        $image1 = $request->file('image1')->store('pos01-barang-images');
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
            'nabara1' => 'required',
        ]);
          
        //untuk input tabel yang asli
        
        $data = [            
            'idsatuan' => $validatedData['idsatuan1'],
            'idkategori' => $validatedData['idkategori1'],
            'kode' => $request['kode1'],
            'barcode' => $validatedData['barcode1'],
            'nabara' => strtoupper($validatedData['nabara1']),
            'slug' => $slug,
            'hbs' => $request['hbs1'],
            'hjs' => $request['hjs1'],
            'diskonbeli' => $request['diskonbeli1'],
            'ppnbeli' => $request['ppnbeli1'],
            'diskonjual' => $request['diskonjual1'],
            'ppnjual' => $request['ppnjual1'],
            'spek' => $request['spek1'],
            'expired' => $request['expired1'],
            'image' => $image1,
            'keterangan' => $request['keterangan1'],
        ];

        if ($id == '0') {
            Barang::create($data);
            
            // //ambil barang.id terakhir
            // $tampil = Barang::limit(1)->orderBy('id','desc')->get();
            // foreach ($tampil as $item) {
            //     $idx = $item->id;            
            //     $barcodex = $item->barcode;            
            //     $spekx = $item->spek;            
            // }

            // $databc = [
            //     'idbarang' => $idx,
            //     'barcode' => $barcodex,
            //     'keterangan' => $spekx,
            // ];

            // //cek tabel barcode
            // $jml = Barcode::where('barcode','=',$barcode)->count();
            // if($jml=='0'){
            //     Barcode::create($databc);
            // }else{
            //     Barcode::where('barcode','=',$barcodex)->update($databc);
            // }

        } else {
            // //cek barang sebelum update
            // $tampil = Barang::where('id','=', $id)->get();
            // foreach ($tampil as $item) {
            //     $barcodex = $item->barcode;            
            // }

            //update barang
            Barang::where('id', '=', $id)->update($data);
            
            // //cek barcode setelah update
            // $jml = Barcode::where('barcode','=',$barcode)                
            //     ->count();
            // $databc = [
            //     'idbarang' => $id,
            //     'barcode' => $validatedData['barcode1'],
            //     'keterangan' => $request['spek1'],
            // ];
            // if($jml=='0'){
            //     Barcode::create($databc);
            // }else{
            //     Barcode::where('barcode','=',$barcode)->update($databc);
            // }

        }

        // $databc = [
        //     'idbarang' => $id,
        //     'barcode' => $barcode,
        //     'keterangan' => $request['spek1'],
        // ];

        // $jml = Barcode::where('barcode','=',$barcode)->count();
        // if($jml==0){
        //     Barcode::create($databc);
        // }else{
        //     Barcode::where('barcode','=',$barcode)->update($databc);
        // }


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
        
        $barang = Barang::with(['kategori','satuan'])->get();
        $datax = DataTables::of($barang                          
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
            ->addColumn('qty', function ($row) {
                $qty = Barangruang::where('idbarang','=',$row->id)->sum('qty');
                return $qty;
            })
            ->addColumn('detailqty', function ($row) {
                $tampil = Barangruang::with('ruang')
                ->select('idruang')
                ->where('idbarang', '=', $row->id)
                ->groupBy('idruang')
                ->get();
                    $ruang = '';
                foreach ($tampil as $item) {
                    $qty = Barangruang::where('idbarang','=',$row->id)
                    ->where('idruang','=',$item->idruang)
                    ->sum('qty');
                    $ruang = $ruang . $item->ruang->kode . ':'. $qty .'</br>';            
                }
                return $ruang;
            })
            ->addColumn('hbs', function ($row) {
                return $row->hbs ? number_format($row->hbs,0) : '';
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
                'hbs',
                'hjs',
                'image',
                'qty',
                'detailqty',
                'action'])


            ->make(true);

            return $data;

    }
   
    public function showbarang()
    {
        
        $barang = Barang::with(['kategori','satuan'])->get();
        $datax = DataTables::of($barang                          
            );

        $data = $datax
            ->addIndexColumn()

            ->addColumn('kategori', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->idkategori ? $row->kategori->kategori : '') .'" class="item_kategori " data1="' . $row->id . '" data2="'. $row->barcode. '" data3="'. $row->nabara. '" data4="'. $row->kategori->kategori.'" data5="'. $row->satuan->satuan.'">'.($row->idkategori ? $row->kategori->kategori : "").'</a>';
            })
            ->addColumn('satuan', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->idsatuan ? $row->satuan->satuan : '') .'" class="item_satuan " data1="' . $row->id . '" data2="'. $row->barcode. '" data3="'. $row->nabara. '" data4="'. $row->kategori->kategori.'" data5="'. $row->satuan->satuan.'">'.($row->idsatuan ? $row->satuan->satuan : "").'</a>';
            })
            ->addColumn('kode', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->kode ? $row->kode : '') .'" class="item_kode " data1="' . $row->id . '" data2="'. $row->barcode. '" data3="'. $row->nabara. '" data4="'. $row->kategori->kategori.'" data5="'. $row->satuan->satuan.'">'.($row->kode ? $row->kode : "").'</a>';
            })
            ->addColumn('barcode', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->barcode ? $row->barcode : '') .'" class="item_barcode " data1="' . $row->id . '" data2="'. $row->barcode. '" data3="'. $row->nabara. '" data4="'. $row->kategori->kategori.'" data5="'. $row->satuan->satuan.'">'.($row->barcode ? $row->barcode : "").'</a>';
            })
            ->addColumn('nabara', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->nabara ? $row->nabara : '') .'" class="item_barcode " data1="' . $row->id . '" data2="'. $row->barcode. '" data3="'. $row->nabara. '" data4="'. $row->kategori->kategori.'" data5="'. $row->satuan->satuan.'">'.($row->nabara ? $row->nabara : "").'</a>';
            })
            ->addColumn('hbs', function ($row) {
                return $row->hbs ? number_format($row->hbs,0) : '';
            })

            ->addColumn('hjs', function ($row) {
                return $row->hjs ? number_format($row->hjs,0) : '';
            })

            ->addColumn('image', function ($row) {
                $imagex = '<img class="mb-4" style="width: 100%" src="'. route('front.index') . '/storage/'. $row->image.'">';
                return '<a href="#" style="color: white;" title="'. ($row->nabara ? $row->nabara : '') .'" class="item_image " data1="' . $row->id . '" data2="'. $row->barcode. '" data3="'. $row->nabara. '" data4="'. $row->kategori->kategori.'" data5="'. $row->satuan->satuan.'">'.($row->image ? $imagex : "") .'</a>';
            })
           
            ->addColumn('action', function ($row) {
                return '<a href="#" title="Edit Data" class="btn btn-success btn-xs item_edit" data="' . $row->id . '" data2="'. $row->barcode.'" data3="'. $row->nabara.'" data4="'. $row->nabara.'"><i style="font-size:18px" class="fa">&#xf044;</i></a> ' .
                       '<a href="#" title="Hapus Data" class="btn btn-danger btn-xs item_hapus" data="' . $row->id . '" data2="'. $row->barcode.'" data3="'. $row->nabara.'" data4="'. $row->nabara.'"><i style="font-size:18px" class="fa">&#xf00d;</i></a>';
            })
            
            ->rawColumns([
                'kategori',
                'satuan',
                'kode',
                'barcode',
                'nabara',
                'hbs',
                'hjs',
                'image',
                'action'])


            ->make(true);

            return $data;

    }

    
    public function edit($id)
    {
        $data = Barang::where('id', '=', $id)->get();
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
        $tampil = Barang::where('id', '=', $id)->get();
        foreach ($tampil as $item) {
            $image = $item->image;            
        }

        if($image){
            storage::delete($image);
        }
        $data = Barang::where('id', '=', $id)->delete();
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
