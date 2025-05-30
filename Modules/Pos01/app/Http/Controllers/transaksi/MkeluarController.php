<?php

namespace Modules\Pos01\Http\Controllers\transaksi;

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
use Illuminate\Support\Facades\DB;
use Modules\Pos01\Models\Anggota;
use Modules\Pos01\Models\Barang;
use Modules\Pos01\Models\Barangruang;
use Modules\Pos01\Models\Bkeluar;
use Modules\Pos01\Models\Hutang;
use Modules\Pos01\Models\Jenispembayaran;
use Modules\Pos01\Models\Mamin;
use Modules\Pos01\Models\Mkeluar;
use Modules\Pos01\Models\Parameter;
use Modules\Pos01\Models\Ruang;
use Modules\Pos01\Models\Satuan;
use Modules\Pos01\Models\Savings;
use Modules\Pos01\Models\Seksi;
use Modules\Pos01\Models\Stok;
use Modules\Pos01\Models\Stokfifo;
use Modules\Pos01\Models\Stoklifo;
use Modules\Pos01\Models\Stokmamin;
use Modules\Pos01\Models\Stokmova;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class mkeluarController extends Controller
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
        $remark = 'Halaman ini digunakan untuk menampilkan, menambah, mengubah dan menghapus <b>Mamin Keluar</b>.';
        $page = 'pos01::transaksi.mkeluar';
        $link = '/pos01/transaksi/mkeluar';
        $subtitle = 'Transaksi';
        $caption = 'Mamin Keluar';
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
            'tabel' => Bkeluar::SimplePaginate($jmlhal)->withQueryString(),
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

        $nb = explode(".", $request['nomorbuktia1']); ;
        $nomorba1 = $nb[3];

        $validatedData = $request->validate([
            'idbarang1' => 'required',
            'nomorbuktia1' => 'required',
            'tgltransaksi1' => 'required',
            'idanggota1' => 'required',
        ]);

        $data = [
            'tgltransaksi' => $validatedData['tgltransaksi1'],
            'nomorbuktia' => $validatedData['nomorbuktia1'],
            'nomorba' => $nomorba1,
            'idmamin' => $validatedData['idbarang1'],
            'idanggota' => $validatedData['idanggota1'],
            
            'qty' => $request['qty1'],
            'hjs' => $request['hjs1'],
            'hppj' => $request['hppj1'],
            'ppn' => $request['ppn1'],
            'diskon' => $request['diskon1'],
            'ppnpersen' => $request['ppnpersen1'],
            'diskonpersen' => $request['diskonpersen1'],
            'email' => auth()->user()->email,
            'iduser' => auth()->user()->id,
            'keterangan' => $request['keterangan1'],
        ];

        if ($id == '0') {
            Mkeluar::create($data);
        } else {
            Mkeluar::where('id', '=', $id)->update($data);
        }
        return json_encode(array('data' => $data));
    }

    public function posting(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $created_at1 = date('Y-m-d  H:i:s'); 
        $status1 = 'keluarmam';

        $tgltransaksi1 = $request['tgltransaksi1'];
        $nomorbuktia1 = $request['nomorbuktia1'];
        $tglposting1 = $request['tglposting1'];
        $nomorposting1 = $request['nomorposting1'];

        $np = explode(".", $request['nomorposting1']);
        $nomorp1 = intval($np[3]);

        $validatedData = $request->validate([
            'tgltransaksi1' => 'required',
            'nomorbuktia1' => 'required',
            'tglposting1' => 'required',
            'nomorposting1' => 'required',
        ]);

        $tampil = mkeluar::with(['mamin','anggota']) 
            ->where('tgltransaksi','=',  $validatedData['tgltransaksi1'])            
            ->where('nomorbuktia','=',  $validatedData['nomorbuktia1'])
            ->get();

            // SELECT `id`, `idmamin`, `idanggota`, `tgltransaksi`, `nomorba`, `nomorbuktia`, `qty`, `hjs`, `hppj`, 
            // `ppnpersen`, `diskonpersen`, `ppn`, `diskon`, `tglposting`, `nomorposting`, `keterangan`, `email`, `iduser` FROM `mkeluar` WHERE 1
        foreach ($tampil as $baris) {
            $idmamin1 = $baris->idmamin;
            $idanggota1 = $baris->idanggota;
            $nama1 = $baris->anggota->nama;
            $idstatus1 = $baris->id;
            $tgltransaksi1 = $baris->tgltransaksi;
            $nomorbuktia1 = $baris->nomorbuktia;
            $qty1 = $baris->qty;
            $hjs1 = $baris->hjs;
            $hppj1 = $baris->hppj;
            $ppn1 = $baris->ppn;
            $diskon1 = $baris->diskon;
            $email1 = $baris->email;
            $keterangan1 = $baris->keterangan;
            $iduser1 = $baris->iduser;
            
            //4.stokmamin
            $qty2 = Stokmamin::where('idmamin','=',$idmamin1)
                ->count();
            
                $data2 = [
                    'idmamin' => $idmamin1,
                    'idanggota' => $idanggota1,
                    'nama' => $nama1,
                    'status' => $status1,
                    'tglstatus' => $tgltransaksi1,
                    'nomorstatus' => $nomorbuktia1,
                    'idstatus' => $idstatus1,
                    'created_at' => $created_at1,
                    'tglposting' => $validatedData['tglposting1'],
                    'nomorp' => $nomorp1,
                    'nomorposting' => $validatedData['nomorposting1'],
                    'keluar' => $qty1,
                    'ppnkeluar' => $ppn1,
                    'diskonkeluar' => $diskon1,
                    'hjs' => $hjs1,
                    'hppj' => $hppj1,
                    'email' => $email1,
                    'iduser' => $iduser1,
                    'keterangan' => $keterangan1,
                ];

            if($qty2<>'0'){
                // Stokmamin::create($data2);
            }else{
                Stokmamin::create($data2);
                //
            }

            //6.update mkeluar
            $data = [
                'tglposting' => $validatedData['tglposting1'],
                'nomorposting' => $validatedData['nomorposting1'],                        
            ];
                 
            mkeluar::with(['mamin','anggota']) 
            ->where('tgltransaksi','=', $tgltransaksi1)            
            ->where('nomorbuktia','=', $nomorbuktia1)
            ->update($data);

        }

            

        // return json_encode('data');
    }
    
    public function proses(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $created_at1 = date('Y-m-d  H:i:s'); 
        $tglsekarang = date('Y-m-d');
        $status1 = 'hutangcus';

        $subtotals1 = $request['subtotals1']; 
        $ppns1 = $request['ppns1'];
        $diskons1 = $request['diskons1'];
        $totals1 = $request['totals1'];
        $bayars1 = $request['bayars1'];
        $vouchers1 = $request['vouchers1'];
        $ambilsavings1 = $request['ambilsavings1'];
        $kembalis1 = $request['kembalis1'];
        $savings1 = $request['savings1'];
        $idjenispembayaran1 = $request['idjenispembayaran1'];
        $nomorpostingnya1 = $request['nomorpostingnya1'];
        $tglpostingnya1 = $request['tglpostingnya1'];
        $nomorbuktia1 = $request['nomorbuktia1'];
        $tgltransaksi1 = $request['tgltransaksi1'];
        $idanggota1 = $request['idanggota1'];
        $xangsuran1 = $request['kali1'];
        $persenjasa1 = $request['persenjasa1'];
        $nilaihutang1 = $request['nilaihutang1'];

        // $np = explode(".", $request['$nomorpostingnya1']);
        // $nomorp1 = intval($np[3]);
        $tampil = Stokmamin::where('nomorstatus','=',$nomorbuktia1)->get();
        foreach ($tampil as $baris) {
             $nomorp1 = $baris->nomorp;
        }
            
        if($tgltransaksi1 == $tglsekarang){
            //update stokmamin
            $data = [
                'subtotals' => $subtotals1,
                'ppns' => $ppns1,
                'diskons' => $diskons1,
                'totals' => $totals1,
                'bayars' => $bayars1,
                'vouchers' => $vouchers1,
                'ambilsavings' => $ambilsavings1,
                'kembalis' => $kembalis1,
                'savings' => $savings1,
                'idjenispembayaran' => $idjenispembayaran1,                
                'xangsuran' => $xangsuran1,                
            ]; 
            $tampil = Stokmamin::where('nomorstatus','=',$nomorbuktia1)->count();
            if($tampil<>'0'){
                Stok::where('nomorstatus','=',$nomorbuktia1)->update($data);
            }
            
            //savings
            if($savings1=='0'||''){
                Savings::where('idanggota','=',$idanggota1)
                ->where('nomorbukti','=',$nomorbuktia1)
                ->delete();
            }else{
                $tampil = Savings::where('idanggota','=',$idanggota1)
                ->where('nomorbukti','<',$nomorbuktia1)
                ->count(); 
                if($tampil<>'0'){
                    $tampil1 = Savings::where('idanggota','=',$idanggota1)
                        ->orderBy('nomorbukti','desc')        
                        ->get();
                    foreach ($tampil1 as $baris) {
                        $awalx = $baris->akhir;
                    }
                    $tampil2 = Savings::where('idanggota','=',$idanggota1)
                    ->where('nomorbukti','=',$nomorbuktia1)
                    ->count();
                    $data = [
                        'idanggota' => $idanggota1,
                        'tgltransaksi' => $tgltransaksi1,
                        'nomorbukti' => $nomorbuktia1,
                        'tglposting' => $tglpostingnya1,
                        'nomorposting' => $nomorpostingnya1,
                        'awal' => $awalx,
                        'masuk' => $savings1,
                        'keluar' => $ambilsavings1,
                        'akhir' => $awalx + $savings1 - $ambilsavings1,
                        'email' => auth()->user()->email,
                        'iduser' => auth()->user()->id,                                
                    ];
                    if($tampil2<>'0'){
                        Savings::where('idanggota','=',$idanggota1)
                        ->where('nomorbukti','=',$nomorbuktia1)
                        ->update($data);
                    }else{
                        Savings::create($data);
                    }
                }else{
                    $tampil2 = Savings::where('idanggota','=',$idanggota1)
                    ->where('nomorbukti','=',$nomorbuktia1)
                    ->count();
                    $data = [
                        'idanggota' => $idanggota1,
                        'tgltransaksi' => $tgltransaksi1,
                        'nomorbukti' => $nomorbuktia1,
                        'tglposting' => $tglpostingnya1,
                        'nomorposting' => $nomorpostingnya1,
                        'awal' => 0,
                        'masuk' => $savings1,
                        'keluar' => $ambilsavings1,
                        'akhir' => 0 + $savings1 - $ambilsavings1,  
                        'email' => auth()->user()->email,
                        'iduser' => auth()->user()->id,                              
                    ];
                    if($tampil2<>'0'){
                        Savings::where('idanggota','=',$idanggota1)
                        ->where('nomorbukti','=',$nomorbuktia1)
                        ->update($data);
                    }else{
                        Savings::create($data);
                    }
                    
                }
            }

            //hutang
            if($idjenispembayaran1<>'99'){
                Hutang::where('idanggota','=',$idanggota1)
                ->where('nomorstatus','=',$nomorbuktia1)
                ->delete();
            }else{
                $tampil = Hutang::where('idanggota','=',$idanggota1)
                ->where('kodepokok','=',2)
                ->where('created_at','<',$created_at1)
                ->count(); 
                if($tampil<>'0'){
                    $tampil1 = Hutang::where('idanggota','=',$idanggota1)
                        ->where('kodepokok','=',2)
                        ->where('created_at','<',$created_at1)
                        ->orderBy('created_at','desc')        
                        ->get();
                    foreach ($tampil1 as $baris) {
                        $awalx = $baris->awal;
                        $akhirx = $baris->akhir;
                        $nomorbuktix = $baris->nomorstatus;
                    }
                    if($nomorbuktix==$nomorbuktia1){
                        $data = [
                            'kodepokok' => 2,
                            'xangsuran' => $xangsuran1,
                            'created_at' => $created_at1,
                            'nomorp' => $nomorp1,
                            'tglposting' => $tglpostingnya1,
                            'nomorposting' => $nomorpostingnya1,
                            'masuk' => $nilaihutang1,
                            'akhir' => $awalx + $nilaihutang1,
                            'totalori' => $totals1,
                            'persenjasa' => $persenjasa1,
                            'email' => auth()->user()->email,
                            'iduser' => auth()->user()->id,                                
                        ]; 
                        Hutang::where('idanggota','=',$idanggota1)
                        ->where('kodepokok','=',2)
                        ->where('nomorstatus','=',$nomorbuktia1)
                        ->update($data);
                    }else{
                        $data = [
                            'kodepokok' => 2,
                            'idanggota' => $idanggota1,
                            'tglstatus' => $tgltransaksi1,
                            'nomorp' => $nomorp1,
                            'nomorstatus' => $nomorbuktia1,
                            'status' => $status1,
                            'tglposting' => $tglpostingnya1,
                            'nomorposting' => $nomorpostingnya1,
                            'xangsuran' => $xangsuran1,
                            'created_at' => $created_at1,
                            'awal' => $awalx,
                            'masuk' => $nilaihutang1,
                            'akhir' => $awalx + $nilaihutang1,
                            'totalori' => $totals1,
                            'persenjasa' => $persenjasa1,
                            'email' => auth()->user()->email,
                            'iduser' => auth()->user()->id,                                
                        ];
                        Hutang::create($data);
                    }

                }else{                
                    $data = [
                        'kodepokok' => 2,
                        'idanggota' => $idanggota1,
                        'tglstatus' => $tgltransaksi1,
                        'nomorp' => $nomorp1,
                        'nomorstatus' => $nomorbuktia1,
                        'status' => $status1,
                        'tglposting' => $tglpostingnya1,
                        'nomorposting' => $nomorpostingnya1,
                        'xangsuran' => $xangsuran1,
                        'created_at' => $created_at1,
                        'awal' => 0,
                        'masuk' => $nilaihutang1,
                        'akhir' => 0 + $nilaihutang1,
                        'totalori' => $totals1,
                        'persenjasa' => $persenjasa1,
                        'email' => auth()->user()->email,
                        'iduser' => auth()->user()->id,                               
                    ];
                    Hutang::create($data);
                }
                $tampil = Hutang::where('idanggota','=',$idanggota1)
                ->where('kodepokok','=',1)
                ->where('nomorstatus','=',$nomorbuktia1)
                ->count();
                $data = [
                    'kodepokok' => 1,
                    'idanggota' => $idanggota1,
                    'tglstatus' => $tgltransaksi1,
                    'nomorp' => $nomorp1,
                    'nomorstatus' => $nomorbuktia1,
                    'status' => $status1,
                    'tglposting' => $tglpostingnya1,
                    'nomorposting' => $nomorpostingnya1,
                    'xangsuran' => $xangsuran1,
                    'created_at' => $created_at1,
                    'asli' => $nilaihutang1,
                    'pokok' => $nilaihutang1,
                    'totalori' => $totals1,
                    'persenjasa' => $persenjasa1,
                    'email' => auth()->user()->email,
                    'iduser' => auth()->user()->id,
                ];
                if($tampil<>'0'){
                    Hutang::where('idanggota','=',$idanggota1)
                    ->where('kodepokok','=',1)
                    ->where('nomorstatus','=',$nomorbuktia1)->update($data);
                }else{
                    Hutang::create($data); 
                }
            }

            
        }else{
            //
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
        
        $tgltransaksi1 = session('tgltransaksi1');    
        $nomorbuktia1 = session('nomorbuktia1');    

        $data = mkeluar::with(['mamin','anggota'])            
            ->where('tgltransaksi','=', $tgltransaksi1)            
            ->where('nomorbuktia','=', $nomorbuktia1)            
            ->get();
        return json_encode(array('data' => $data));

    }

    public function showmamin()
    {
        $mamin = Mamin::with('satuan','kategori')
            ->whereNull('expired')
            ->orderBy('namamin','asc')
            ->get();
        $datax = DataTables::of($mamin                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('namamin', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->namamin) .'" class="item_namamin " data1="' . $row->id . '" data2="'. $row->kode . '" data3="'. $row->barcode . '" data4="'. $row->namamin. '" data5="'. $row->satuan->satuan. '" data6="'. $row->kategori->kategori. '" data7="'. $row->hjs. '">'.($row->namamin).'</a> ';
            })
            ->addColumn('kode', function ($row) {
                return '<a href="#" style="color: white; " title="'. ($row->kode) .'" class="item_kode " data1="' . $row->id . '" data2="'. $row->kode . '" data3="'. $row->barcode . '" data4="'. $row->namamin. '" data5="'. $row->satuan->satuan. '" data6="'. $row->kategori->kategori. '" data7="'. $row->hjs. '">' . ($row->kode).'</a> ';
            })
            ->addColumn('barcode', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->barcode) .'" class="item_barcode " data1="' . $row->id . '" data2="'. $row->kode . '" data3="'. $row->barcode . '" data4="'. $row->namamin. '" data5="'. $row->satuan->satuan. '" data6="'. $row->kategori->kategori. '" data7="'. $row->hjs. '">'.($row->barcode).'</a> ';
            })
           
            ->addColumn('hjs', function ($row) {
                return $row->hjs ? number_format($row->hjs,0) : '';
            })
            ->addColumn('satuan', function ($row) {
                return $row->idsatuan ? $row->satuan->kode : '';
            })
            ->addColumn('kategori', function ($row) {
                return $row->idkategori ? $row->kategori->kategori : '';
            })
            
            // ->addColumn('action', function ($row) {
            //     return '<a href="#" title="Edit Data" class="btn btn-success btn-xs item_edit" data="' . $row->id . '" data2="'. $row->barang->nabara.'" data3="'. $row->barang->kode.'" data4="'. $row->barang->barcode.'"><i style="font-size:18px" class="fa">&#xf044;</i></a> ' .
            //            '<a href="#" title="Hapus Data" class="btn btn-danger btn-xs item_hapus" data="' . $row->id . '" data2="'. $row->barang->nabara.'" data3="'. $row->barang->kode.'" data4="'. $row->barang->barcode.'"><i style="font-size:18px" class="fa">&#xf00d;</i></a>';
            // })
            
            ->rawColumns([
                'namamin',
                'kode',
                'barcode',
                'hjs',
                'satuan',
                'kategori',
                'action'])

            ->make(true);

            return $data;

    }
    public function showanggota()
    {
        $anggota = Anggota::with('lembaga')->get();
        $datax = DataTables::of($anggota                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('ecard', function ($row) { 
                $lembaga = $row->lembaga->lembaga;
                return '<a href="#" style="" title="'. ($row->ecard ? $row->ecard : '-') .'" class="item_ecard " data1="' . $row->id . '" data2="'. $row->ecard. '" data3="'. $row->nia. '" data4="'. $row->ktp. '" data5="'. $row->nama. '" data6="'. $row->lembaga->lembaga. '" data7="'. $row->telp. '" data100="'. $row->id. '">'.($row->ecard ? $row->ecard : '-').'</a> ';
            })
            ->addColumn('nia', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->nia ? $row->nia : '-') .'" class="item_nia " data1="' . $row->id . '" data2="'. $row->ecard. '" data3="'. $row->nia. '" data4="'. $row->ktp. '" data5="'. $row->nama. '" data6="'. $row->lembaga->lembaga. '" data7="'. $row->telp. '" data100="'. $row->id. '">'.($row->nia ? $row->nia : '-').'</a> ';
            })
            ->addColumn('ktp', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->ktp ? $row->ktp : '-') .'" class="item_ktp " data1="' . $row->id . '" data2="'. $row->ecard. '" data3="'. $row->nia. '" data4="'. $row->ktp. '" data5="'. $row->nama. '" data6="'. $row->lembaga->lembaga. '" data7="'. $row->telp. '" data100="'. $row->id. '">'.($row->ktp ? $row->ktp : '-').'</a> ';
            })
            ->addColumn('nama', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->nama ? $row->nama : '-') .'" class="item_nama " data1="' . $row->id . '" data2="'. $row->ecard. '" data3="'. $row->nia. '" data4="'. $row->ktp. '" data5="'. $row->nama. '" data6="'. $row->lembaga->lembaga. '" data7="'. $row->telp. '" data100="'. $row->id. '">'.($row->nama ? $row->nama : '-').'</a> ';
            })

            ->addColumn('lembaga', function ($row) {
                return $row->idlembaga ? $row->lembaga->lembaga : '';
            })
            
            ->addColumn('action', function ($row) {
                return '<a href="#" title="Edit Data" class="btn btn-success btn-xs item_edit" data="' . $row->id . '" data2="'. $row->ecard.'" data3="'. $row->nia.'" data4="'. $row->ktp.'"><i style="font-size:18px" class="fa">&#xf044;</i></a> ' .
                       '<a href="#" title="Hapus Data" class="btn btn-danger btn-xs item_hapus" data="' . $row->id . '" data2="'. $row->ecard.'" data3="'. $row->nia.'" data4="'. $row->ktp.'"><i style="font-size:18px" class="fa">&#xf00d;</i></a>';
            })
            
            ->rawColumns([
                'ecard',
                'nia',
                'ktp',
                'nama',
                'action'])

            ->make(true);

            return $data;

    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data = mkeluar::where('id', '=', $id)
            ->with(['mamin','anggota'])
            ->get();
        return json_encode(array('data' => $data));       
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
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
        $data = Bkeluar::where('id', '=', $id)->delete();
        return json_encode(array('data' => $data));
    }
  
    function listmamin()
    {
        $tampil = Mamin::orderBy('namamin','asc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>". $baris->namamin . "</option>";
        }
    }
    function listruang()
    {
        $tampil = Ruang::get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>". $baris->ruang . "</option>";
        }
    }
    function listanggota()
    {
        $tampil = Anggota::with('lembaga')
            ->where('aktif','=','Y')
            ->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>".$baris->ecard."|".$baris->nia."|".$baris->ktp."|".$baris->nama."|". $baris->lembaga->lembaga . "</option>";
        }
    }
    function listjenispembayaran()
    {
        $tampil = Jenispembayaran::where('id','<>','0')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>". $baris->jenispembayaran . "</option>";
        }
    }
   
    public function nomorbukti(Request $request)
    {
        
        $tgltransaksi1=$request['tgltransaksi1'];
        $tgl1 = Carbon::parse($tgltransaksi1)->format('Ymd');
        $ym1 = Carbon::parse($tgltransaksi1)->format('Ym');
        $d1 = Carbon::parse($tgltransaksi1)->format('d');
        $m1 = Carbon::parse($tgltransaksi1)->format('m');
        $y1 = Carbon::parse($tgltransaksi1)->format('Y');
        
        $jmldata = Mkeluar::where('tgltransaksi','=',$tgltransaksi1)            
            ->count();
        if ($jmldata==0){
            $nomor = 1;    
        }else{
            $nomor = Mkeluar::select('nomorba')
                ->where('tgltransaksi','=',$tgltransaksi1)
                ->max('nomorba')+1;
        }  

        if ($nomor<10){
            $no='000'.$nomor;
        }else if($nomor<100){
            $no='00'.$nomor;
        }else if($nomor<1000){
            $no='0'.$nomor;
        }else{
            $no=''.$nomor;
        }

        $userid = auth()->user()->id;
        if($userid<10){
            $idx = '00'.$userid;
        }else if($userid<100){
            $idx = '0'.$userid;
        }else{
            $idx = ''.$userid;
        }
        
        //nomor kwitansi contoh : KMM.010.20230527.0009
        $nbx = 'KMM' . '.' . $idx . '.' . $tgl1 . '.' . $no;  
        $nomorbukti = "'$nbx'";

        $data = Satuan::limit(1)
                ->selectRaw($nomorbukti . ' as nomorbuktia')                
                ->get();
         
        return json_encode(array('data' => $data));        
    }

    public function nomorposting(Request $request)
    {
        
        $tglposting1=$request['tglposting1'];
        $tgl1 = Carbon::parse($tglposting1)->format('Ymd');
        $ym1 = Carbon::parse($tglposting1)->format('Ym');
        $d1 = Carbon::parse($tglposting1)->format('d');
        $m1 = Carbon::parse($tglposting1)->format('m');
        $y1 = Carbon::parse($tglposting1)->format('Y');
        
        $jmldata = Stok::where('tglposting','=',$tglposting1)            
            ->count();
        if ($jmldata==0){
            $nomor = 1;    
        }else{
            $nomor = Stok::select('nomorp')
                ->where('tglposting','=',$tglposting1)
                ->max('nomorp')+1;
        }  

        if ($nomor<10){
            $no='000'.$nomor;
        }else if($nomor<100){
            $no='00'.$nomor;
        }else if($nomor<1000){
            $no='0'.$nomor;
        }else{
            $no=''.$nomor;
        }

        $userid = auth()->user()->id;
        if($userid<10){
            $idx = '00'.$userid;
        }else if($userid<100){
            $idx = '0'.$userid;
        }else{
            $idx = ''.$userid;
        }
        

        //nomor kwitansi contoh : POS.010.20230527.0009
        $nbx = 'POS' . '.' . $idx . '.' . $tgl1 . '.' . $no;  
        $nomorposting = "'$nbx'";

        $data = Satuan::limit(1)
                ->selectRaw($nomorposting . ' as nomorposting')                
                ->get();
         
        return json_encode(array('data' => $data));        
    }

    public function kirimsyarat(Request $request)
    {
        session([
            'tgltransaksi1' => $request['tgltransaksi1'],
            'nomorbuktia1' => $request['nomorbuktia1'],
        ]);
        
    }

    public function cariid(Request $request)
    {
        $cari = $request['cari1'];

        session(['carix' => $cari]);
               
        // $data = Anggota::with('lembaga')
        //         ->where('ecard','=',$cari)
        //         ->Orwhere('nia','=',$cari)
        //         ->Orwhere('ktp','=',$cari)
        //         ->Orwhere('telp','=',$cari)
        //         ->get();

         $data = Anggota::with('lembaga')
                ->where('aktif','=','Y')
                ->where(function (Builder $query) {
                    $cari = session('carix');
                    return $query->where('ecard','=',$cari)
                    ->Orwhere('nia','=',$cari)
                    ->Orwhere('ktp','=',$cari)
                    ->Orwhere('telp','=',$cari);
                })
                ->get();        
         
        return json_encode(array('data' => $data));

    }

    public function displaypembayaran($id)
    {
        
        $jml = hutang::where('nomorstatus','=',$id)
            ->where('kodepokok','=','1')
            ->count();
        if($jml <> '0'){
            $tampil = hutang::where('nomorstatus','=',$id)
                ->where('kodepokok','=','1')
                ->get();
            
            foreach ($tampil as $baris) {
                $persenjasa = $baris->persenjasa;
                $asli = $baris->asli;
            }
        }else{
            $persenjasa = 0;
            $asli = 0;
        }

        $tampil = Stok::where('nomorstatus','=',$id)
        ->get();
        $jml = 0;
        foreach ($tampil as $baris) {
            $jml = $jml + $baris->bayars + $baris->vouchers + $baris->ambilsavings;
        }
        
        $data = stok::limit(1)->select('*')
            ->selectRaw('('. $jml .') as jml')
            ->selectRaw('('. $persenjasa .') as persenjasa')
            ->selectRaw('('. $asli .') as nilaihutang')
            ->where('nomorstatus','=',$id)        
            ->orderBy('id','desc')
            ->get();

        return json_encode(array('data' => $data));       
    }
    public function lihatpersen($id)
    {
        $data = Parameter::where('xangsuran', '=', $id)
            ->get();
        return json_encode(array('data' => $data));       
    }


}
