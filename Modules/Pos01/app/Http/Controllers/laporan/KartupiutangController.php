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
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\NumberFormat;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class KartupiutangController extends Controller
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
        $remark = 'Halaman ini digunakan untuk menampilkan <b>Kartu Piutang</b>.';
        $page = 'pos01::laporan.kartupiutang';
        $link = '/pos01/laporan/kartupiutang';
        $subtitle = 'Laporan';
        $caption = 'Kartu Piutang';
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
            'tabel' => Hutang::SimplePaginate($jmlhal)->withQueryString(),
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
    public function showkartupiutang()
    {

        $tampil = Hutang::where('id','=',session('idpiutang1'))
            ->get();
        foreach ($tampil as $baris) {
            $nomorstatus = $baris->nomorstatus;
        }

        $hutang = Hutang::select('*')
            ->where('kodepokok','=','2')
            ->where(function (Builder $query) {
                    return $query->where('status','=','hutangcus')
                                 ->orWhere('status','=','bayarcus')
                                 ->orWhere('status','=','hutangmam')
                                 ->orWhere('status','=','bayarmam')
                                 ->orWhere('status','=','returcus')
                                 ->orWhere('status','=','returmam');
                })
            ->where(function (Builder $query) {
                    $tampil = Hutang::where('id','=',session('idpiutang1'))
                        ->get();
                    foreach ($tampil as $baris) {
                        $nomorstatus = $baris->nomorstatus;
                    }
                    return $query->where('nomorstatus','=',$nomorstatus)
                                 ->orWhere('nomorstatusasal','=',$nomorstatus);
                })
            ->with('anggota','supplier')
            ->orderBy('created_at','asc')
            ->get();
        $datax = DataTables::of($hutang                          
            );
      
        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('nia', function ($row) {
                return $row->idanggota ? $row->anggota->nia : '';
            })
            ->addColumn('nama', function ($row) {
                return $row->idanggota ? $row->anggota->nama : '';
            })
            ->addColumn('lembaga', function ($row) {
                return $row->idanggota ? $row->anggota->lembaga->lembaga : '';
            })
            ->addColumn('xangsuran', function ($row) {
                $tampil = Hutang::where('id','=',session('idpiutang1'))
                    ->get();
                foreach ($tampil as $baris) {
                    $nomorstatus = $baris->nomorstatus;
                }

                $jml = Hutang::select('id')
                    ->where('kodepokok','=','2')
                    ->where(function (Builder $query) {
                            return $query->where('status','=','hutangcus')
                                 ->orWhere('status','=','bayarcus')
                                 ->orWhere('status','=','hutangmam')
                                 ->orWhere('status','=','bayarmam')
                                 ->orWhere('status','=','returcus')
                                 ->orWhere('status','=','returmam');
                        })
                    ->where(function (Builder $query) {
                            $tampil = Hutang::where('id','=',session('idpiutang1'))
                                ->get();
                            foreach ($tampil as $baris) {
                                $nomorstatus = $baris->nomorstatus;
                            }
                            return $query->where('nomorstatus','=',$nomorstatus)
                                        ->orWhere('nomorstatusasal','=',$nomorstatus);
                        })
                    ->where('created_at','<',$row->created_at)
                    ->with('anggota','supplier')
                    ->count();


                return $jml .'/' . $row->xangsuran;
            })
            ->addColumn('awal', function ($row) {
                return $row->awal ? number_format($row->awal,0) : '0';
            })
            ->addColumn('masuk', function ($row) {
                return $row->masuk ? number_format($row->masuk,0) : '0';
            })
            ->addColumn('keluar', function ($row) {
                return $row->keluar ? number_format($row->keluar,0) : '0';
            })
            ->addColumn('akhir', function ($row) {
                return $row->akhir ? number_format($row->akhir,0) : '0';
            })
           
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

    public function showpiutang()
    {
        $hutang = Hutang::select('*')
            ->where(function (Builder $query) {
                    return $query->where('status','=','hutangcus')
                                 ->orWhere('status','=','hutangmam')
                                 ->orWhere('status','=','returmam')
                                 ->orWhere('status','=','returcus');
                })
            ->where(function (Builder $query) {
                    return $query->where('kodepokok','=','0')
                                 ->orWhere('kodepokok','=','1');
                })
            ->with('anggota','supplier')
            ->orderBy('created_at','asc')
            ->get();
        $datax = DataTables::of($hutang                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('nomorstatus', function ($row) {
                return '<a href="#" style="color: white;" title="'. $row->nomorstatus .'" class="item_nomorstatus " data1="' . $row->id . '" data2="'. $row->nomorstatus. '" data3="'. $row->anggota->nama. '" data4="'. $row->anggota->lembaga->lembaga. '">'. $row->nomorstatus .'</a> ';
            })
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
            ->addColumn('keterangan', function ($row) {
                $x = $row->kodepokok;
                if($x=='0'){
                    $keterangan = 'Sudah Lunas';
                }else if($x=='1'){
                    $keterangan = 'Belum Lunas';
                }else{
                    $keterangan = '';
                }

                return $keterangan;
            })
                        
            ->rawColumns([
                'nomorstatus',
                'xangsuran',
                'nilaiangsuran',
                'sudahbayar',
                'saldo',
                'qty',
                'satuan',
                'totalhpp',
                'keterangan',
                ])

            ->make(true);

            return $data;

    }

    function listpiutang()
    {
        $tampil = Hutang::select('*')
            ->where(function (Builder $query) {
                    return $query->where('status','=','hutangcus')
                                 ->orWhere('status','=','hutangmam')
                                 ->orWhere('status','=','returcus')
                                 ->orWhere('status','=','returmam');
                })
            ->where(function (Builder $query) {
                    return $query->where('kodepokok','=','0')
                                 ->orWhere('kodepokok','=','1');
                })
            ->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->nomorstatus . "</option>";
        }
    }
    function listpiutangx()
    {
        $tampil = Hutang::select('*')
            ->where(function (Builder $query) {
                    return $query->where('status','=','hutangcus')
                                 ->orWhere('status','=','hutangmam')
                                 ->orWhere('status','=','returcus')
                                 ->orWhere('status','=','returmam');
                })
            ->where(function (Builder $query) {
                    return $query->where('kodepokok','=','0')
                                 ->orWhere('kodepokok','=','1');
                })
            ->with('anggota','supplier')
            ->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . 
                $baris->nomorstatus . '|' .
                $baris->anggota->nama . '|' .
                $baris->anggota->lembaga->lembaga . 
                 "</option>";
        }
    }
    public function kirimsyarat(Request $request)
    {
        session([
            'idpiutang1' => $request['idpiutang1'],
        ]);
    }


}
