<?php

namespace Modules\Pos01\Http\Controllers\laporan;

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
use Modules\Pos01\Models\Ruang;
use Modules\Pos01\Models\Stok;
use Modules\Pos01\Models\Stokfifo;
use Modules\Pos01\Models\Stoklifo;
use Modules\Pos01\Models\Stokmova;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\NumberFormat;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class KartustokController extends Controller
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
        $remark = 'Halaman ini digunakan untuk menampilkan <b>Kartu Stok</b>.';
        $page = 'pos01::laporan.kartustok';
        $link = '/pos01/laporan/kartustok';
        $subtitle = 'Laporan';
        $caption = 'Kartu Stok';
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
    
    public function showstokrekap()
    {
        $idbarang1 = session('idbarang1');
        if($idbarang1==''||$idbarang1='0'){
            $idbarang1 = '0';
        }

        $idruangx = session('idruang1');
        if($idruangx=='0'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        date_default_timezone_set("Asia/Bangkok");
        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $stok = Stok::select('*')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idbarang','=',session('idbarang1'))
            // ->where(function (Builder $query) {
            //         return $query->where('status','=','keluar')
            //                      ->orWhere('status','=','mutkeluar')
            //                      ->orWhere('status','=','retkeluar')
            //                      ->orWhere('status','=','konkeluar')
            //                      ->orWhere('status','=','korkeluar');
            //     })
            ->with('barang','seksi','ruang')
            ->orderBy('tglstatus','asc')
            ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
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
            ->addColumn('satuan', function ($row) {
                return $row->idbarang ? $row->barang->satuan->kode : '';
            })

            // ->addColumn('awal', function ($row) {
            //     return $row->masuk ? Number_Format($row->masuk,0) : '';
            // })
            ->addColumn('hbsawal', function ($row) {
                return $row->hbsawal ? Number_Format($row->hbsawal,0) : '';
            })
            ->addColumn('hppawal', function ($row) {
                return $row->hppawal ? Number_Format($row->hppawal,0) : '';
            })

            ->addColumn('masuk', function ($row) {
                return $row->masuk ? Number_Format($row->masuk,0) : '';
            })
            ->addColumn('hbsmasuk', function ($row) {
                return $row->hbsmasuk ? Number_Format($row->hbsmasuk,0) : '';
            })
            ->addColumn('hppmasuk', function ($row) {
                return $row->hppmasuk ? Number_Format($row->hppmasuk,0) : '';
            })

            ->addColumn('keluar', function ($row) {
                return $row->keluar ? Number_Format($row->keluar,0) : '';
            })
            ->addColumn('hbskeluar', function ($row) {
                return $row->hbskeluar ? Number_Format($row->hbskeluar,0) : '';
            })
            ->addColumn('hppkeluar', function ($row) {
                return $row->hppkeluar ? Number_Format($row->hppkeluar,0) : '';
            })

            ->addColumn('akhir', function ($row) {
                return $row->akhir ? Number_Format($row->akhir,0) : '';
            })
            ->addColumn('hbsakhir', function ($row) {
                return $row->hbsakhir ? Number_Format($row->hbsakhir,0) : '';
            })
            ->addColumn('hppakhir', function ($row) {
                return $row->hppakhir ? Number_Format($row->hppakhir,0) : '';
            }) 

            ->addColumn('waktu', function ($row) {
                $x = date_create($row->created_at);
                return date_format($x,'H:i:s');
            })   
            ->addColumn('tanggal', function ($row) {
                $x = date_create($row->created_at);
                return date_format($x,'Y-m-d');
            })   
            
            // ->rawColumns([
            //             'waktu',
            //             ])

            ->make(true);

            return $data;

    }

    public function showstokrekapfifo()
    {
        $idbarang1 = session('idbarang1');
        if($idbarang1==''||$idbarang1='0'){
            $idbarang1 = '0';
        }

        $idruangx = session('idruang1');
        if($idruangx=='0'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        date_default_timezone_set("Asia/Bangkok");
        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $stok = Stokfifo::select('*')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idbarang','=',session('idbarang1'))
            // ->where(function (Builder $query) {
            //         return $query->where('status','=','keluar')
            //                      ->orWhere('status','=','mutkeluar')
            //                      ->orWhere('status','=','retkeluar')
            //                      ->orWhere('status','=','konkeluar')
            //                      ->orWhere('status','=','korkeluar');
            //     })
            ->where('kodepokok','=','2')
            ->with('barang','seksi','ruang')
            ->orderBy('tglstatus','asc')
            ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
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
            ->addColumn('satuan', function ($row) {
                return $row->idbarang ? $row->barang->satuan->kode : '';
            })

            // ->addColumn('awal', function ($row) {
            //     return $row->masuk ? Number_Format($row->masuk,0) : '';
            // })
            ->addColumn('hbsawal', function ($row) {
                return $row->hbsawal ? Number_Format($row->hbsawal,0) : '';
            })
            ->addColumn('hppawal', function ($row) {
                return $row->hppawal ? Number_Format($row->hppawal,0) : '';
            })

            ->addColumn('masuk', function ($row) {
                return $row->masuk ? Number_Format($row->masuk,0) : '';
            })
            ->addColumn('hbsmasuk', function ($row) {
                return $row->hbsmasuk ? Number_Format($row->hbsmasuk,0) : '';
            })
            ->addColumn('hppmasuk', function ($row) {
                return $row->hppmasuk ? Number_Format($row->hppmasuk,0) : '';
            })

            ->addColumn('keluar', function ($row) {
                return $row->keluar ? Number_Format($row->keluar,0) : '';
            })
            ->addColumn('hbskeluar', function ($row) {
                return $row->hbskeluar ? Number_Format($row->hbskeluar,0) : '';
            })
            ->addColumn('hppkeluar', function ($row) {
                return $row->hppkeluar ? Number_Format($row->hppkeluar,0) : '';
            })

            ->addColumn('akhir', function ($row) {
                return $row->akhir ? Number_Format($row->akhir,0) : '';
            })
            ->addColumn('hbsakhir', function ($row) {
                return $row->hbsakhir ? Number_Format($row->hbsakhir,0) : '';
            })
            ->addColumn('hppakhir', function ($row) {
                return $row->hppakhir ? Number_Format($row->hppakhir,0) : '';
            }) 

            ->addColumn('waktu', function ($row) {
                $x = date_create($row->created_at);
                return date_format($x,'H:i:s');
            })   
            ->addColumn('tanggal', function ($row) {
                $x = date_create($row->created_at);
                return date_format($x,'Y-m-d');
            })

            ->make(true);

            return $data;

    }

    public function showstokrekapmova()
    {
        $idbarang1 = session('idbarang1');
        if($idbarang1==''||$idbarang1='0'){
            $idbarang1 = '0';
        }

        $idruangx = session('idruang1');
        if($idruangx=='0'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        date_default_timezone_set("Asia/Bangkok");
        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $stok = Stokmova::select('*')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idbarang','=',session('idbarang1'))
            // ->where(function (Builder $query) {
            //         return $query->where('status','=','keluar')
            //                      ->orWhere('status','=','mutkeluar')
            //                      ->orWhere('status','=','retkeluar')
            //                      ->orWhere('status','=','konkeluar')
            //                      ->orWhere('status','=','korkeluar');
            //     })
            ->with('barang','seksi','ruang')
            ->orderBy('tglstatus','asc')
            ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
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
            ->addColumn('satuan', function ($row) {
                return $row->idbarang ? $row->barang->satuan->kode : '';
            })

            // ->addColumn('awal', function ($row) {
            //     return $row->masuk ? Number_Format($row->masuk,0) : '';
            // })
            ->addColumn('hbsawal', function ($row) {
                return $row->hbsawal ? Number_Format($row->hbsawal,0) : '';
            })
            ->addColumn('hppawal', function ($row) {
                return $row->hppawal ? Number_Format($row->hppawal,0) : '';
            })

            ->addColumn('masuk', function ($row) {
                return $row->masuk ? Number_Format($row->masuk,0) : '';
            })
            ->addColumn('hbsmasuk', function ($row) {
                return $row->hbsmasuk ? Number_Format($row->hbsmasuk,0) : '';
            })
            ->addColumn('hppmasuk', function ($row) {
                return $row->hppmasuk ? Number_Format($row->hppmasuk,0) : '';
            })

            ->addColumn('keluar', function ($row) {
                return $row->keluar ? Number_Format($row->keluar,0) : '';
            })
            ->addColumn('hbskeluar', function ($row) {
                return $row->hbskeluar ? Number_Format($row->hbskeluar,0) : '';
            })
            ->addColumn('hppkeluar', function ($row) {
                return $row->hppkeluar ? Number_Format($row->hppkeluar,0) : '';
            })

            ->addColumn('akhir', function ($row) {
                return $row->akhir ? Number_Format($row->akhir,0) : '';
            })
            ->addColumn('hbsakhir', function ($row) {
                return $row->hbsakhir ? Number_Format($row->hbsakhir,0) : '';
            })
            ->addColumn('hppakhir', function ($row) {
                return $row->hppakhir ? Number_Format($row->hppakhir,0) : '';
            }) 

            ->addColumn('waktu', function ($row) {
                $x = date_create($row->created_at);
                return date_format($x,'H:i:s');
            })   
            ->addColumn('tanggal', function ($row) {
                $x = date_create($row->created_at);
                return date_format($x,'Y-m-d');
            })

            ->make(true);

            return $data;
    }

    public function showstokrekaplifo()
    {
        $idbarang1 = session('idbarang1');
        if($idbarang1==''||$idbarang1='0'){
            $idbarang1 = '0';
        }

        $idruangx = session('idruang1');
        if($idruangx=='0'||$idruangx==''){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruangx;
            $idruangakhir = $idruangx;
        }

        date_default_timezone_set("Asia/Bangkok");
        $currentDate = date('Y-m-d');
        $tglawal = session('tgltransaksi1');
        if($tglawal==''||$tglawal=='0'){
            $tglawal=$currentDate;
        }else{
            $tglawal=session('tgltransaksi1');
        }
        $tglakhir = session('tgltransaksi2');
        if($tglakhir==''||$tglakhir=='0'){
            $tglakhir=$currentDate;
        }else{
            $tglakhir=session('tgltransaksi2');
        }

        $stok = Stoklifo::select('*')
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('tglstatus','>=',$tglawal)
            ->where('tglstatus','<=',$tglakhir)
            ->where('idbarang','=',session('idbarang1'))
            // ->where(function (Builder $query) {
            //         return $query->where('status','=','keluar')
            //                      ->orWhere('status','=','mutkeluar')
            //                      ->orWhere('status','=','retkeluar')
            //                      ->orWhere('status','=','konkeluar')
            //                      ->orWhere('status','=','korkeluar');
            //     })
            ->where('kodepokok','=','2')
            ->with('barang','seksi','ruang')
            ->orderBy('tglstatus','asc')
            ->orderBy('id','asc')
            ->get();
        $datax = DataTables::of($stok                          
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
            ->addColumn('satuan', function ($row) {
                return $row->idbarang ? $row->barang->satuan->kode : '';
            })

            // ->addColumn('awal', function ($row) {
            //     return $row->masuk ? Number_Format($row->masuk,0) : '';
            // })
            ->addColumn('hbsawal', function ($row) {
                return $row->hbsawal ? Number_Format($row->hbsawal,0) : '';
            })
            ->addColumn('hppawal', function ($row) {
                return $row->hppawal ? Number_Format($row->hppawal,0) : '';
            })

            ->addColumn('masuk', function ($row) {
                return $row->masuk ? Number_Format($row->masuk,0) : '';
            })
            ->addColumn('hbsmasuk', function ($row) {
                return $row->hbsmasuk ? Number_Format($row->hbsmasuk,0) : '';
            })
            ->addColumn('hppmasuk', function ($row) {
                return $row->hppmasuk ? Number_Format($row->hppmasuk,0) : '';
            })

            ->addColumn('keluar', function ($row) {
                return $row->keluar ? Number_Format($row->keluar,0) : '';
            })
            ->addColumn('hbskeluar', function ($row) {
                return $row->hbskeluar ? Number_Format($row->hbskeluar,0) : '';
            })
            ->addColumn('hppkeluar', function ($row) {
                return $row->hppkeluar ? Number_Format($row->hppkeluar,0) : '';
            })

            ->addColumn('akhir', function ($row) {
                return $row->akhir ? Number_Format($row->akhir,0) : '';
            })
            ->addColumn('hbsakhir', function ($row) {
                return $row->hbsakhir ? Number_Format($row->hbsakhir,0) : '';
            })
            ->addColumn('hppakhir', function ($row) {
                return $row->hppakhir ? Number_Format($row->hppakhir,0) : '';
            }) 

            ->addColumn('waktu', function ($row) {
                $x = date_create($row->created_at);
                return date_format($x,'H:i:s');
            })   
            ->addColumn('tanggal', function ($row) {
                $x = date_create($row->created_at);
                return date_format($x,'Y-m-d');
            })

            ->make(true);

            return $data;
    }

    public function showbarang()
    {
        $idruang = session('idruang1');
        if($idruang==''){
            $idruang = '1';
        }
        $idbarang = Barangruang::select('idbarang')
            ->where('idruang','=',$idruang);

        $barang = Barang::with('kategori','satuan')
            ->whereIn('id',$idbarang)
            ->get();
        $datax = DataTables::of($barang                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('kode', function ($row) {
                return '<a href="#" style="color: white;" title="'. $row->kode .'" class="item_kode " data1="' . $row->id . '" data2="'. $row->kode. '" data3="'. $row->barcode. '" data4="'. $row->nabara. '" data5="'. $row->kategori->kategori. '" data6="'. $row->satuan->satuan . '">'. $row->kode .'</a> ';
            })
            ->addColumn('barcode', function ($row) {
                return '<a href="#" style="color: white;" title="'. $row->barcode .'" class="item_barcode " data1="' . $row->id . '" data2="'. $row->kode. '" data3="'. $row->barcode. '" data4="'. $row->nabara. '" data5="'. $row->kategori->kategori. '" data6="'. $row->satuan->satuan . '">'. $row->barcode .'</a> ';
            })
            ->addColumn('nabara', function ($row) {
                return '<a href="#" style="color: white;" title="'. $row->nabara .'" class="item_nabara " data1="' . $row->id . '" data2="'. $row->kode. '" data3="'. $row->barcode. '" data4="'. $row->nabara. '" data5="'. $row->kategori->kategori. '" data6="'. $row->satuan->satuan . '">'. $row->nabara .'</a> ';
            })
            ->addColumn('kategori', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->idkategori ? $row->kategori->kategori : '') .'" class="item_kategori " data1="' . $row->id . '" data2="'. $row->kode. '" data3="'. $row->barcode. '" data4="'. $row->nabara. '" data5="'. $row->kategori->kategori. '" data6="'. $row->satuan->satuan . '">'. ($row->idkategori ? $row->kategori->kategori : '') .'</a> ';
            })
            ->addColumn('satuan', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->idsatuan ? $row->satuan->satuan : '') .'" class="item_satuan " data1="' . $row->id . '" data2="'. $row->kode. '" data3="'. $row->barcode. '" data4="'. $row->nabara. '" data5="'. $row->kategori->kategori. '" data6="'. $row->satuan->satuan . '">'. ($row->idsatuan ? $row->satuan->satuan : '') .'</a> ';
            })
            ->addColumn('image', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->image ? $row->nabara : '') .'" class="item_image " data1="' . $row->id . '" data2="'. $row->kode. '" data3="'. $row->barcode. '" data4="'. $row->nabara. '" data5="'. $row->kategori->kategori. '" data6="'. $row->satuan->satuan . '">'. ($row->image ? '<img class="mb-4" style="width: 100%" src="'. route('front.index') . '/storage/'. $row->image.'">' : '') .'</a> ';
            })
            
            ->rawColumns([
                'nabara',
                'kode',
                'barcode',                
                'kategori',                
                'satuan',
                'image',
                ])

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


    public function showhutang()
    {
        
        $idruangx = session('idruang1');
        if($idruangx==''||$idruangx=='0'){
            $idruangx='1';
        }
        $idbarang = Barangruang::select('idbarang')
            ->where('idruang','=',$idruangx);

        $barang = Barang::whereIn('id',$idbarang)
            ->orderBy('nabara', 'asc')->get();;
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

        $tampil = Barang::whereIn('id',$idbarang)
            ->orderBy('nabara', 'asc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->nabara . "</option>";
        }
    }
   
    function listruang()
    {
        $idx = 0;
        $tampil = Ruang::orderBy('ruang', 'asc')->get();
            // echo "<option value='" . $idx . "'>" . "- SEMUA -" . "</option>";
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->ruang . "</option>";
        }
    }

    public function kirimsyarat(Request $request)
    {
        session([
            'idruang1' => $request['idruang1'],
            'tabstok1' => $request['tabstok1'],
            'tgltransaksi1' => $request['tgltransaksi1'],
            'tgltransaksi2' => $request['tgltransaksi2'],
            'idbarang1' => $request['idbarang1'],
        ]);
    }


}
