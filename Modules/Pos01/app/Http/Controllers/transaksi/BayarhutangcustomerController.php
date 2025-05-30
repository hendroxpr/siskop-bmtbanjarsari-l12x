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
use Modules\Pos01\Models\Bayarhutang;
use Modules\Pos01\Models\Bkeluar;
use Modules\Pos01\Models\Hutang;
use Modules\Pos01\Models\Jenispembayaran;
use Modules\Pos01\Models\Ruang;
use Modules\Pos01\Models\Satuan;
use Modules\Pos01\Models\Savings;
use Modules\Pos01\Models\Seksi;
use Modules\Pos01\Models\Stok;
use Modules\Pos01\Models\Stokfifo;
use Modules\Pos01\Models\Stoklifo;
use Modules\Pos01\Models\Stokmova;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class BayarhutangcustomerController extends Controller
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
        $remark = 'Halaman ini digunakan untuk menampilkan, menambah, mengubah dan menghapus <b>Bayar Hutang (Customer)</b>.';
        $page = 'pos01::transaksi.bayarhutangcustomer';
        $link = '/pos01/transaksi/bayarhutangcustomer';
        $subtitle = 'Transaksi';
        $caption = 'Bayar Hutang (customer)';
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

        $nb = explode(".", $request['nomorbukti1']); ;
        $nomorb1 = $nb[3];            

        $validatedData = $request->validate([
            'idhutang1' => 'required',
            'idanggota1' => 'required',
            'nomorbukti1' => 'required',
            'tgltransaksi1' => 'required',
        ]);
        
        //ambil xangsuran dan angsuranke
        $tampil = Hutang::where('id','=',$request['idhutang1'])->get();
        foreach ($tampil as $baris) {
            $angsuranke = ($baris->angsuranke + 1).'/'.$baris->xangsuran;            
        }
        //untuk input tabel yang asli

        $data = [

            'tgltransaksi' => $validatedData['tgltransaksi1'],
            'nomorbukti' => $validatedData['nomorbukti1'],
            'nomorb' => $nomorb1,
            'idanggota' => $validatedData['idanggota1'],
            'idhutang' => $validatedData['idhutang1'],

            'bayar' => $request['bayar1'],
            'awal' => $request['saldohutang1'],
            'akhir' => $request['saldohutang1']-$request['bayar1'],
            'angsuranke' => $angsuranke,

            'email' => auth()->user()->email,
            'iduser' => auth()->user()->id,
            'keterangan' => $request['keterangan1'],
        ];

        if ($id == '0') {
            Bayarhutang::create($data);
        } else {
            Bayarhutang::where('id', '=', $id)->update($data);
        }
        return json_encode(array('data' => $data));
    }

    public function posting(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $created_at1 = date('Y-m-d  H:i:s'); 
        $status1 = 'bayarcus';

        $tgltransaksi1 = $request['tgltransaksi1'];
        $nomorbukti1 = $request['nomorbukti1'];
        $tglposting1 = $request['tglposting1'];
        $nomorposting1 = $request['nomorposting1'];

        $np = explode(".", $nomorposting1);
        $nomorp1 = intval($np[3]);

        $validatedData = $request->validate([
            'tgltransaksi1' => 'required',
            'nomorbukti1' => 'required',
            'tglposting1' => 'required',
            'nomorposting1' => 'required',
        ]);

        $tampil = Bayarhutang::with(['hutang','anggota']) 
            ->where('tgltransaksi','=',  $validatedData['tgltransaksi1'])            
            ->where('nomorbukti','=',  $validatedData['nomorbukti1'])
            ->get();
        $bayarx = 0;
        foreach ($tampil as $baris) {
            $idanggota1 = $baris->idanggota;            
            $idhutang1 = $baris->idhutang;            
            $idstatus1 = $baris->id;            
            $tgltransaksi1 = $baris->tgltransaksi;
            $nomorbukti1 = $baris->nomorbukti;

            $angske = explode("/", $baris->angsuranke); ;
            $angsuranke1 = $angske[0];

            $awal1 = $baris->awal;
            $bayar1 = $baris->bayar;
            $bayarx = $bayarx + $bayar1;
            $akhir1 = $baris->akhir;
            
            $email1 = $baris->email;
            $keterangan1 = $baris->keterangan;
            $iduser1 = $baris->iduser;

            //1. bayarhutang
            $qty2 = Bayarhutang::with(['hutang','anggota']) 
            ->where('tgltransaksi','=',  $validatedData['tgltransaksi1'])            
            ->where('nomorbukti','=',  $validatedData['nomorbukti1'])
            ->count();
            if($qty2<>'0'){
                $data2 = [                    
                    'tglposting' => $validatedData['tglposting1'],
                    'nomorposting' => $validatedData['nomorposting1'],                    
                    'keterangan' => $keterangan1,
                ];
                Bayarhutang::with(['hutang','anggota']) 
                ->where('tgltransaksi','=',  $validatedData['tgltransaksi1'])            
                ->where('nomorbukti','=',  $validatedData['nomorbukti1'])
                ->update($data2); 
            }

            //2. hutang
            $tampil2 = Hutang::where('id','=', $idhutang1)->get();
            foreach ($tampil2 as $baris) {
                $pokokx = $baris->pokok - $bayar1;
                $angskex = $baris->angsuranke + 1;
            }

            if($pokokx=='0'){
                $data2 = [                    
                    'pokok' => $pokokx,
                    'angsuranke' => $angskex,                    
                    'kodepokok' => '0',                    
                ]; 
                Hutang::where('id','=', $idhutang1)->update($data2);
            }else{
                $data2 = [
                    'pokok' => $pokokx,
                    'angsuranke' => $angskex,
                ];
                Hutang::where('id','=',$idhutang1)->update($data2);
            }

            $tampil1 = Hutang::where('id','=',$idhutang1)->get();
            foreach ($tampil1 as $baris) {
                $nomorstatusasal1 = $baris->nomorstatus;                
                $angsuranke1 = $baris->angsuranke;
                $xangsuran1 = $baris->xangsuran;
            }

            $tampil2 = Hutang::limit(1)
                ->where('idanggota','=', $idanggota1)
                ->where('kodepokok','=', '2')
                ->orderBy('id','desc')
                ->get();
                foreach ($tampil2 as $baris) {
                    $awal1 = $baris->akhir;
                    
                }
                $data2 = [
                    'kodepokok' => 2,
                    'idanggota' => $idanggota1,
                    'tglstatus' => $tgltransaksi1,                    
                    'nomorp' => $nomorp1,
                    'nomorstatus' => $nomorbukti1,
                    'nomorstatusasal' => $nomorstatusasal1,
                    'idstatus' => $idstatus1,
                    'status' => $status1,
                    'tglposting' => $tglposting1,
                    'nomorposting' => $nomorposting1,
                    'xangsuran' => $xangsuran1,
                    'created_at' => $created_at1,
                    'awal' => $awal1,
                    'masuk' => 0,
                    'keluar' => $bayar1,
                    'akhir' => $awal1 - $bayar1,
                    'totalori' => $bayar1,
                    'persenjasa' => 0,
                    'angsuranke' => $angsuranke1,
                    'email' => auth()->user()->email,
                    'iduser' => auth()->user()->id,                               
                                                   
                ];
                Hutang::create($data2);

        }

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
        $nomorbukti1 = $request['nomorbukti1'];
        $tgltransaksi1 = $request['tgltransaksi1'];
        $idanggota1 = $request['idanggota1'];

            
        if($tgltransaksi1 == $tglsekarang){
            //update hutang

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
                             
            ]; 
            $tampil = Hutang::where('nomorstatus','=',$nomorbukti1)->count();
            if($tampil<>'0'){
                hutang::where('nomorstatus','=',$nomorbukti1)->update($data);
            }
            
            //savings
            if($savings1>'0'||$ambilsavings1>'0'){
                $tampil = Savings::where('idanggota','=',$idanggota1)
                ->where('nomorbukti','<>',$nomorbukti1)
                ->where('akhir','>','0')
                ->count();
                if($tampil<>'0'){
                    $tampil2 = Savings::limit('1')
                    ->where('idanggota','=',$idanggota1)
                    ->where('nomorbukti','<>',$nomorbukti1)
                    ->where('akhir','>','0')
                    ->orderBy('id','desc')
                    ->get();
                    foreach ($tampil2 as $baris){
                        $awal = $baris->akhir;
                    }
                    $tampil3 = Savings::where('idanggota','=',$idanggota1)
                    ->where('nomorbukti','=',$nomorbukti1)
                    ->count();
                        $data3 = [
                            'idanggota' => $idanggota1,
                            'tgltransaksi' => $tgltransaksi1,
                            'nomorbukti' => $nomorbukti1,
                            'tglposting' => $tglpostingnya1,
                            'nomorposting' => $nomorpostingnya1,
                            'awal' => $awal,
                            'masuk' => $savings1,
                            'keluar' => $ambilsavings1,
                            'akhir' => $awal + $savings1 - $ambilsavings1,  
                            'email' => auth()->user()->email,
                            'iduser' => auth()->user()->id,                              
                        ];
                    if($tampil3<>'0'){
                        Savings::where('idanggota','=',$idanggota1)
                        ->where('nomorbukti','=',$nomorbukti1)
                        ->update($data3);
                    }else{
                        Savings::create($data3);
                    }

                }else{
                    $tampil3 = Savings::where('idanggota','=',$idanggota1)
                    ->where('nomorbukti','=',$nomorbukti1)
                    ->count();
                        $data3 = [
                            'idanggota' => $idanggota1,
                            'tgltransaksi' => $tgltransaksi1,
                            'nomorbukti' => $nomorbukti1,
                            'tglposting' => $tglpostingnya1,
                            'nomorposting' => $nomorpostingnya1,
                            'awal' => 0,
                            'masuk' => $savings1,
                            'keluar' => $ambilsavings1,
                            'akhir' => 0 + $savings1 - $ambilsavings1,  
                            'email' => auth()->user()->email,
                            'iduser' => auth()->user()->id,                              
                        ];
                    if($tampil3<>'0'){
                        Savings::create($data3);
                    }else{
                        Savings::where('idanggota','=',$idanggota1)
                        ->where('nomorbukti','=',$nomorbukti1)
                        ->update($data3);
                    }

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
        $nomorbukti1 = session('nomorbukti1');    

        $data = Bayarhutang::with(['hutang','anggota'])            
            ->where('tgltransaksi','=', $tgltransaksi1)            
            ->where('nomorbukti','=', $nomorbukti1)            
            ->get();
        return json_encode(array('data' => $data));
    }

    public function showhutang()
    {
        $idanggota = session('idanggota1');
        $hutang = Hutang::where('idanggota','=',$idanggota)
            ->where('kodepokok','=','1')
            ->get();
        $datax = DataTables::of($hutang                          
            );

        $data = $datax
            ->addIndexColumn()
            
            ->addColumn('tglstatus', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->tglstatus ? $row->tglstatus : '-') .'" class="item_tglstatus " data1="' . $row->id . '" data2="'. $row->nomorstatus. '" data3="'. $row->tglstatus.'">'.($row->tglstatus ? $row->tglstatus : '-').'</a> ';
            })
            ->addColumn('nomorstatus', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->tglstatus ? $row->nomorstatus : '-') .'" class="item_nomorstatus " data1="' . $row->id . '" data2="'. $row->nomorstatus. '" data3="'. $row->tglstatus.'">'.($row->tglstatus ? $row->nomorstatus : '-').'</a> ';
            })
            
            ->addColumn('xangsuran', function ($row) {
                return $row->angsuranke .'/'.$row->xangsuran;
            })
            ->addColumn('nilaiangsuran', function ($row) {
                return number_format($row->asli/$row->xangsuran,0);
            })
            ->addColumn('nilaihutang', function ($row) {
                return number_format($row->asli,0);
            })
            ->addColumn('sudahbayar', function ($row) {
                return number_format($row->asli-$row->pokok,0);
            })
            ->addColumn('saldo', function ($row) {
                return number_format($row->pokok,0);
            })
            
            
            // ->addColumn('action', function ($row) {
            //     return '<a href="#" title="Edit Data" class="btn btn-success btn-xs item_edit" data="' . $row->id . '" data2="'. $row->barang->nabara.'" data3="'. $row->barang->kode.'" data4="'. $row->barang->barcode.'"><i style="font-size:18px" class="fa">&#xf044;</i></a> ' .
            //            '<a href="#" title="Hapus Data" class="btn btn-danger btn-xs item_hapus" data="' . $row->id . '" data2="'. $row->barang->nabara.'" data3="'. $row->barang->kode.'" data4="'. $row->barang->barcode.'"><i style="font-size:18px" class="fa">&#xf00d;</i></a>';
            // })
            
            ->rawColumns([
                'tglstatus',
                'nomorstatus',
                'xangsuran',
                'nilaiangsuran',
                'nilaihutang',
                'sudahbayar',
                'saldo',
                'action'])

            ->make(true);

            return $data;

    }
    public function showanggota()
    {
        $idanggotax = Hutang::select('idanggota')
            ->where('idsupplier','=','0')
            ->where('kodepokok','=','1');

        $anggota = Anggota::with('lembaga')
            ->whereIn('id',$idanggotax)
            ->get();
        $datax = DataTables::of($anggota                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('ecard', function ($row) { 
                $lembaga = $row->lembaga->lembaga;
                return '<a href="#" style="color: white;" title="'. ($row->ecard ? $row->ecard : '-') .'" class="item_ecard " data1="' . $row->id . '" data2="'. $row->ecard. '" data3="'. $row->nia. '" data4="'. $row->ktp. '" data5="'. $row->nama. '" data6="'. $row->lembaga->lembaga. '" data7="'. $row->telp. '" data100="'. $row->id. '">'.($row->ecard ? $row->ecard : '-').'</a> ';
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
            ->addColumn('saldo', function ($row) {
                $saldox = Hutang::where('idanggota','=',$row->id)
                    ->where('kodepokok','=','1')
                    ->sum('pokok');
                return number_format($saldox,0);
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
                'saldo',
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
        $data = Bayarhutang::where('id', '=', $id)
            ->with(['hutang', 'anggota'])
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
        $data = Bayarhutang::where('id', '=', $id)->delete();
        return json_encode(array('data' => $data));
    }
  
    public function displayhutang($id)
    {
        $data = Hutang::where('id', '=', $id)
            ->where('kodepokok','=','1')
            ->get();
        return json_encode(array('data' => $data));       
    }
    function listhutang()
    {
        $idanggota = session('idanggota1');
        $tampil = Hutang::where('idanggota','=',$idanggota)
            ->where('kodepokok','=','1')
            ->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>". $baris->nomorstatus . "</option>";
        }
    }
    function listhutangx()
    {
        $idanggota = session('idanggota1');
        $tampil = Hutang::where('idanggota','=',$idanggota)
            ->where('kodepokok','=','1')
            ->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>". $baris->tglstatus . "|" . $baris->nomorstatus . "|" . $baris->angsuranke + 1 . "|" . $baris->xangsuran . "|"
                . $baris->asli / $baris->xangsuran . "|" . $baris->asli . "|" . $baris->asli - $baris->pokok  . "|" . $baris->pokok
                . "</option>";
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
        $idanggotax = Hutang::select('idanggota')
            ->where('idsupplier','=','0')
            ->where('kodepokok','=','1');
    
        $tampil = Anggota::with('lembaga')
            ->whereIn('id',$idanggotax)
            ->get();
        foreach ($tampil as $baris) {
            $saldox = Hutang::where('idanggota','=',$baris->id)
                    ->where('idsupplier','=','0')
                    ->where('kodepokok','=','1')
                    ->sum('pokok');
            echo "<option value='" . $baris->id . "'>".$baris->ecard."|".$baris->nia."|".$baris->ktp."|".$baris->nama."|". $baris->lembaga->lembaga."|". $saldox . "</option>";
        }
    }
    function listjenispembayaran()
    {
        $tampil = Jenispembayaran::where('id','<>','0')
        ->where('id','<>','99')
        ->get();
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
        
        $jmldata = Bayarhutang::where('tgltransaksi','=',$tgltransaksi1)            
            ->count();
        if ($jmldata==0){
            $nomor = 1;    
        }else{
            $nomor = Bayarhutang::select('nomorb')
                ->where('tgltransaksi','=',$tgltransaksi1)
                ->max('nomorb')+1;
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
        
        //nomor kwitansi contoh : BHC.010.20230527.0009
        $nbx = 'BHC' . '.' . $idx . '.' . $tgl1 . '.' . $no;  
        $nomorbukti = "'$nbx'";

        $data = Satuan::limit(1)
                ->selectRaw($nomorbukti . ' as nomorbukti')                
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
        
        $jmldata = Hutang::where('tglposting','=',$tglposting1)            
            ->count();
        if ($jmldata==0){
            $nomor = 1;    
        }else{
            $nomor = Hutang::select('nomorp')
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
            'nomorbukti1' => $request['nomorbukti1'],
            'idanggota1' => $request['idanggota1'],
            'saldo1' => $request['saldo1'],
        ]);
        
    }

    public function cariid(Request $request)
    {
        $idanggotax = Hutang::select('idanggota')
        ->where('idsupplier','=','0')
        ->where('kodepokok','=','1');
    
        $cari = $request['cari1'];        
        
        session(['carix' => $cari]);

        $data = Anggota::with('lembaga')
                ->whereIn('id',$idanggotax)
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
        
        $tampil = Hutang::where('nomorstatus','=',$id)
        ->get();
        $jml = 0;
        foreach ($tampil as $baris) {
            $jml = $jml + $baris->bayars + $baris->vouchers + $baris->ambilsavings;
        }

        $data = Hutang::limit(1)
        ->select('*')
        ->selectRaw('('. $jml .') as jml')
        ->where('nomorstatus','=',$id)        
        ->orderBy('id','desc')
        ->get();

        return json_encode(array('data' => $data));       
    }

}
