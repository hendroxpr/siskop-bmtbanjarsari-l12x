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
use Modules\Pos01\Models\Hutang;
use Modules\Pos01\Models\Ruang;
use Modules\Pos01\Models\Stok;
use Modules\Pos01\Models\Stokfifo;
use Modules\Pos01\Models\Stoklifo;
use Modules\Pos01\Models\Stokmova;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class HutangpiutangController extends Controller
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
        $remark = 'Halaman ini digunakan untuk menampilkan <b>Hutang/Piutang</b>.';
        $page = 'pos01::laporan.hutangpiutang';
        $link = '/pos01/laporan/hutangpiutang';
        $subtitle = 'Laporan';
        $caption = 'Hutang/Piutang';
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
    public function showhutangpiutangbelumcustomer()
    {
        $tgltransaksi1 = session('tgltransaksi1');
        if($tgltransaksi1==''){
            $tgltransaksi1 = session('memtanggal');
        }    
        $tgltransaksi2 = session('tgltransaksi2');
        if($tgltransaksi2==''){
            $tgltransaksi2 = session('memtanggal');
        }
        
        $hutang = Hutang::select('*')
            ->where('tglstatus','>=',$tgltransaksi1)
            ->where('tglstatus','<=',$tgltransaksi2)
            ->where('kodepokok','=','1')
            ->where('status','=','hutangcus')
            ->with('anggota','supplier')
            ->get();
        $datax = DataTables::of($hutang                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('nama', function ($row) {
                return $row->idanggota ? $row->anggota->nama : '';
            })
            ->addColumn('nia', function ($row) {
                return $row->idanggota ? $row->anggota->nia : '';
            })
            ->addColumn('lembaga', function ($row) {
                return $row->idanggota ? $row->anggota->lembaga->lembaga : '';
            })
            ->addColumn('xangsuran', function ($row) {
                return $row->angsuranke.'/'.$row->xangsuran;
            })
            ->addColumn('nilaiangsuran', function ($row) {
                return number_format($row->asli/$row->xangsuran,0);
            })
            ->addColumn('asli', function ($row) {
                return number_format($row->asli,0);
            })
            ->addColumn('sudahbayar', function ($row) {
                $jml = Hutang::where('kodepokok','=','2')
                    ->where('nomorstatusasal','=',$row->nomorstatus)
                    ->where('status','=','bayarcus')
                    ->with('anggota','supplier')
                    ->orderBy('id','desc')
                    ->count();
                if($jml<>'0'){
                    $hutang = Hutang::select('*')->limit(1)
                        ->where('kodepokok','=','2')
                        ->where('nomorstatusasal','=',$row->nomorstatus)
                        ->where('status','=','bayarcus')
                        ->with('anggota','supplier')
                        ->orderBy('id','desc')
                        ->get();
                        foreach ($hutang as $baris) {
                            $saldo = $baris->akhir;
                        }
                }else{
                    $saldo=$row->asli;
                } 

                $sudahbayar=$row->asli-$saldo;
                return number_format($sudahbayar,0);
            })
            ->addColumn('saldo', function ($row) {
                $jml = Hutang::where('kodepokok','=','2')
                    ->where('nomorstatusasal','=',$row->nomorstatus)
                    ->where('status','=','bayarcus')
                    ->with('anggota','supplier')
                    ->orderBy('id','desc')
                    ->count();
                if($jml<>'0'){
                    $hutang = Hutang::select('*')->limit(1)
                        ->where('kodepokok','=','2')
                        ->where('nomorstatusasal','=',$row->nomorstatus)
                        ->where('status','=','bayarcus')
                        ->with('anggota','supplier')
                        ->orderBy('id','desc')
                        ->get();
                        foreach ($hutang as $baris) {
                            $saldo = $baris->akhir;
                        }
                }else{
                    $saldo=$row->asli;
                } 

                return number_format($saldo,0);
            })
            
                        
            ->rawColumns([
                'xangsuran',
                'nilaiangsuran',
                'sudahbayar',
                'saldo',
                'qty',
                'satuan',
                'totalhpp',
                ])

            ->make(true);

            return $data;

    }
    public function showhutangpiutangbelumsupplier()
    {
        $tgltransaksi1 = session('tgltransaksi1');
        if($tgltransaksi1==''){
            $tgltransaksi1 = session('memtanggal');
        }    
        $tgltransaksi2 = session('tgltransaksi2');
        if($tgltransaksi2==''){
            $tgltransaksi2 = session('memtanggal');
        }

        $hutang = Hutang::select('*')
            ->where('tglstatus','>=',$tgltransaksi1)
            ->where('tglstatus','<=',$tgltransaksi2)
            ->where('kodepokok','=','1')
            ->where('status','=','hutangsup')
            ->with('anggota','supplier')
            ->get();
        $datax = DataTables::of($hutang                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('supplier', function ($row) {
                return $row->idsupplier ? $row->supplier->supplier : '';
            })
            ->addColumn('kode', function ($row) {
                return $row->idsupplier ? $row->supplier->kode : '';
            })
            ->addColumn('alamat', function ($row) {
                return $row->idsupplier ? $row->supplier->alamat : '';
            })
            ->addColumn('xangsuran', function ($row) {
                return $row->angsuranke.'/'.$row->xangsuran;
            })
            ->addColumn('nilaiangsuran', function ($row) {
                return number_format($row->asli/$row->xangsuran,0);
            })
            ->addColumn('asli', function ($row) {
                return number_format($row->asli,0);
            })
            ->addColumn('sudahbayar', function ($row) {
                $jml = Hutang::where('kodepokok','=','2')
                    ->where('nomorstatusasal','=',$row->nomorstatus)
                    ->where('status','=','bayarsup')
                    ->with('anggota','supplier')
                    ->orderBy('id','desc')
                    ->count();
                if($jml<>'0'){
                    $hutang = Hutang::select('*')->limit(1)
                        ->where('kodepokok','=','2')
                        ->where('nomorstatusasal','=',$row->nomorstatus)
                        ->where('status','=','bayarsup')
                        ->with('anggota','supplier')
                        ->orderBy('id','desc')
                        ->get();
                        foreach ($hutang as $baris) {
                            $saldo = $baris->akhir;
                        }
                }else{
                    $saldo=$row->asli;
                } 
                
                $sudahbayar=$row->asli-$saldo;
                return number_format($sudahbayar,0);
            })
            ->addColumn('saldo', function ($row) {
                $jml = Hutang::where('kodepokok','=','2')
                    ->where('nomorstatusasal','=',$row->nomorstatus)
                    ->where('status','=','bayarsup')
                    ->with('anggota','supplier')
                    ->orderBy('id','desc')
                    ->count();
                if($jml<>'0'){
                    $hutang = Hutang::select('*')->limit(1)
                        ->where('kodepokok','=','2')
                        ->where('nomorstatusasal','=',$row->nomorstatus)
                        ->where('status','=','bayarsup')
                        ->with('anggota','supplier')
                        ->orderBy('id','desc')
                        ->get();
                        foreach ($hutang as $baris) {
                            $saldo = $baris->akhir;
                        }
                }else{
                    $saldo=$row->asli;
                } 

                return number_format($saldo,0);
            })
            
                        
            ->rawColumns([
                'xangsuran',
                'nilaiangsuran',
                'sudahbayar',
                'saldo',
                'qty',
                'satuan',
                'totalhpp',
                ])

            ->make(true);

            return $data;

    }

    public function showhutangpiutangsudahcustomer()
    {
        $tgltransaksi1 = session('tgltransaksi1');
        if($tgltransaksi1==''){
            $tgltransaksi1 = session('memtanggal');
        }    
        $tgltransaksi2 = session('tgltransaksi2');
        if($tgltransaksi2==''){
            $tgltransaksi2 = session('memtanggal');
        }

        $hutang = Hutang::select('*')
            ->where('tglstatus','>=',$tgltransaksi1)
            ->where('tglstatus','<=',$tgltransaksi2)
            ->where('kodepokok','=','0')
            ->where('status','=','hutangcus')
            ->with('anggota','supplier')
            ->get();
        $datax = DataTables::of($hutang                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('nama', function ($row) {
                return $row->idanggota ? $row->anggota->nama : '';
            })
            ->addColumn('nia', function ($row) {
                return $row->idanggota ? $row->anggota->nia : '';
            })
            ->addColumn('lembaga', function ($row) {
                return $row->idanggota ? $row->anggota->lembaga->lembaga : '';
            })
            ->addColumn('xangsuran', function ($row) {
                return $row->angsuranke.'/'.$row->xangsuran;
            })
            ->addColumn('nilaiangsuran', function ($row) {
                return number_format($row->asli/$row->xangsuran,0);
            })
            ->addColumn('asli', function ($row) {
                return number_format($row->asli,0);
            })
            ->addColumn('sudahbayar', function ($row) {
                $sudahbayar=$row->asli;
                return number_format($sudahbayar,0);
            })
            ->addColumn('saldo', function ($row) {
                $saldo=$row->pokok;

                return number_format($saldo,0);
            })
            
                        
            ->rawColumns([
                'xangsuran',
                'nilaiangsuran',
                'sudahbayar',
                'saldo',
                'qty',
                'satuan',
                'totalhpp',
                ])

            ->make(true);

            return $data;

    }
    public function showhutangpiutangsudahsupplier()
    {
        $tgltransaksi1 = session('tgltransaksi1');
        if($tgltransaksi1==''){
            $tgltransaksi1 = session('memtanggal');
        }    
        $tgltransaksi2 = session('tgltransaksi2');
        if($tgltransaksi2==''){
            $tgltransaksi2 = session('memtanggal');
        }

        $hutang = Hutang::select('*')
            ->where('tglstatus','>=',$tgltransaksi1)
            ->where('tglstatus','<=',$tgltransaksi2)
            ->where('kodepokok','=','0')
            ->where('status','=','hutangsup')
            ->with('anggota','supplier')
            ->get();
        $datax = DataTables::of($hutang                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('supplier', function ($row) {
                return $row->idsupplier ? $row->supplier->supplier : '';
            })
            ->addColumn('kode', function ($row) {
                return $row->idsupplier ? $row->supplier->kode : '';
            })
            ->addColumn('alamat', function ($row) {
                return $row->idsupplier ? $row->supplier->alamat : '';
            })
            ->addColumn('xangsuran', function ($row) {
                return $row->angsuranke.'/'.$row->xangsuran;
            })
            ->addColumn('nilaiangsuran', function ($row) {
                return number_format($row->asli/$row->xangsuran,0);
            })
            ->addColumn('asli', function ($row) {
                return number_format($row->asli,0);
            })
            ->addColumn('sudahbayar', function ($row) {
                $sudahbayar=$row->asli;
                return number_format($sudahbayar,0);
            })
            ->addColumn('saldo', function ($row) {
                $saldo=$row->pokok;
                return number_format($saldo,0);
            })
                        
            ->rawColumns([
                'xangsuran',
                'nilaiangsuran',
                'sudahbayar',
                'saldo',
                'qty',
                'satuan',
                'totalhpp',
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


    public function showstokbarangx()
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
            'tabhutang1' => $request['tabhutang1'],
            'event1' => $request['event1'],
            'tgltransaksi1' => $request['tgltransaksi1'],
            'tgltransaksi2' => $request['tgltransaksi2'],
        ]);
    }


}
