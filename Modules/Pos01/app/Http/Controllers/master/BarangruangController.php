<?php

namespace Modules\Pos01\Http\Controllers\master;

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
use Modules\Pos01\Models\Barang;
use Modules\Pos01\Models\Barangruang;
use Modules\Pos01\Models\Jenisbiaya;
use Modules\Pos01\Models\Ruang;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class BarangruangController extends Controller
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
        $remark = 'Halaman ini digunakan untuk menampilkan, menambah, mengubah dan menghapus <b>Barang Ruang</b>.';
        $page = 'pos01::master.barangruang';
        $link = '/pos01/master/barangruang';
        $subtitle = 'Master';
        $caption = 'Barang Ruang';
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
            'tabel' => Barangruang::SimplePaginate($jmlhal)->withQueryString(),
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
            'idbarang1' => 'required',
            'idruang1' => 'required',
        ]);
          
        //untuk input tabel yang asli
        $idruangx = $request['idruang1'];
        $tampil = Ruang::where('id','=',$idruangx)->get();
        foreach ($tampil as $baris) {
            $idseksix = $baris->idseksi;
        }

        $data = [            
            'idbarang' => $validatedData['idbarang1'],
            'idseksi' => $idseksix,
            'idruang' => $validatedData['idruang1'],
            'stokmin' => $request['stokmin1'],
            'stokmax' => $request['stokmax1'],
            'aktif' => $request['aktif1'],
        ];

        if ($id == '0') {
            Barangruang::create($data);
        } else {
            Barangruang::where('id', '=', $id)->update($data);
        }
        return json_encode(array('data' => $data));
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
        $idruangx = session('idruang1');
        $barangruang = Barangruang::where('idruang','=',$idruangx)->with('barang','seksi','ruang')->get();
        $datax = DataTables::of($barangruang                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('barang', function ($row) {
                return $row->idbarang ? $row->barang->nabara : '';
            })
            ->addColumn('kode', function ($row) {
                return $row->idbarang ? $row->barang->kode : '';
            })
            ->addColumn('barcode', function ($row) {
                return $row->idbarang ? $row->barang->barcode : '';
            })
            ->addColumn('hbs', function ($row) {
                return $row->idbarang ? number_format($row->barang->hbs,0) : '';
            })
            ->addColumn('action', function ($row) {
                return '<a href="#" title="Edit Data" class="btn btn-success btn-xs item_edit" data="' . $row->id . '" data2="'. $row->barang->nabara.'" data3="'. $row->barang->kode.'" data4="'. $row->barang->barcode.'"><i style="font-size:18px" class="fa">&#xf044;</i></a> ' .
                       '<a href="#" title="Hapus Data" class="btn btn-danger btn-xs item_hapus" data="' . $row->id . '" data2="'. $row->barang->nabara.'" data3="'. $row->barang->kode.'" data4="'. $row->barang->barcode.'"><i style="font-size:18px" class="fa">&#xf00d;</i></a>';
            })
            
            ->rawColumns([
                'action'])

            ->make(true);

            return $data;

    }

    
    public function edit($id)
    {
        $data = Barangruang::where('id', '=', $id)->get();
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

        $data = Barangruang::where('id', '=', $id)->delete();
        return json_encode(array('data' => $data));
    }


    public function showbarang()
    {
        
        $idruangx = session('idruang1');
        $idbarang = Barangruang::select('idbarang')
            ->where('idruang','=',$idruangx);
        $barang = Barang::whereNotIn('id',$idbarang)
            ->orderBy('nabara', 'asc')
            ->with(['kategori','satuan'])->get();
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
    function listbarang()
    {
        $idruangx = session('idruang1');
        $idbarang = Barangruang::select('idbarang')
            ->where('idruang','=',$idruangx);
        $tampil = Barang::whereNotIn('id',$idbarang)
            ->orderBy('nabara', 'asc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->nabara . "</option>";
        }
    }
    function listruang()
    {
        $tampil = Ruang::orderBy('ruang', 'asc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->ruang . "</option>";
        }
        
    }

    public function kirimsyarat(Request $request)
    {
        $idruang1 = $request['idruang1'];
        session([
            'idruang1' => $idruang1,
        ]);
    }


}
