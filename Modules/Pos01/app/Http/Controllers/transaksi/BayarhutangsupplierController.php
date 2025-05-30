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
use Modules\Pos01\Models\Supplier;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class BayarhutangSupplierController extends Controller
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
        $remark = 'Halaman ini digunakan untuk menampilkan, menambah, mengubah dan menghapus <b>Bayar Hutang (Supplier)</b>.';
        $page = 'pos01::transaksi.bayarhutangsupplier';
        $link = '/pos01/transaksi/bayarhutangsupplier';
        $subtitle = 'Transaksi';
        $caption = 'Bayar Hutang (Supplier)';
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
            'idsupplier1' => 'required',
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
            'idsupplier' => $validatedData['idsupplier1'],
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
        $status1 = 'bayarsup';

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

        $tampil = Bayarhutang::with(['hutang','supplier']) 
            ->where('tgltransaksi','=',  $validatedData['tgltransaksi1'])            
            ->where('nomorbukti','=',  $validatedData['nomorbukti1'])
            ->get();
        $bayarx = 0;
        foreach ($tampil as $baris) {
            $idsupplier1 = $baris->idsupplier;            
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
            $qty2 = Bayarhutang::with(['hutang','supplier']) 
            ->where('tgltransaksi','=',  $validatedData['tgltransaksi1'])            
            ->where('nomorbukti','=',  $validatedData['nomorbukti1'])
            ->count();
            if($qty2<>'0'){
                $data2 = [                    
                    'tglposting' => $validatedData['tglposting1'],
                    'nomorposting' => $validatedData['nomorposting1'],                    
                    'keterangan' => $keterangan1,
                ];
                Bayarhutang::with(['hutang','supplier']) 
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
                ->where('idsupplier','=', $idsupplier1)
                ->where('kodepokok','=', '2')
                ->orderBy('id','desc')
                ->get();
                foreach ($tampil2 as $baris) {
                    $awal1 = $baris->akhir;
                    
                }
                $data2 = [
                    'kodepokok' => 2,
                    'idsupplier' => $idsupplier1,
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
        $status1 = 'hutangsup';

        $subtotals1 = $request['subtotals1']; 
        $ppns1 = $request['ppns1'];
        $diskons1 = $request['diskons1'];
        $totals1 = $request['totals1'];
        $bayars1 = $request['bayars1'];
        $vouchers1 = $request['vouchers1'];
        $kembalis1 = $request['kembalis1'];
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
                'kembalis' => $kembalis1,
                'idjenispembayaran' => $idjenispembayaran1,               
                             
            ]; 
            $tampil = Hutang::where('nomorstatus','=',$nomorbukti1)->count();
            if($tampil<>'0'){
                hutang::where('nomorstatus','=',$nomorbukti1)->update($data);
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

        $data = Bayarhutang::with(['hutang','anggota','supplier'])            
            ->where('tgltransaksi','=', $tgltransaksi1)            
            ->where('nomorbukti','=', $nomorbukti1)            
            ->get();
        return json_encode(array('data' => $data));
    }

    public function showhutang()
    {
        $idsupplier = session('idsupplier1');
        $hutang = Hutang::where('idsupplier','=',$idsupplier)
            ->where('idanggota','=','0')
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
    public function showsupplier()
    {
        $idsupplierx = Hutang::select('idsupplier')
            ->where('idanggota','=','0')
            ->where('kodepokok','=','1');

        $supplier = Supplier::whereIn('id',$idsupplierx)
            ->get();
        $datax = DataTables::of($supplier                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('kode', function ($row) { 
                return '<a href="#" style="color: white;" title="'. ($row->kode ? $row->kode : '-') .'" class="item_kode " data1="' . $row->id . '" data2="'. $row->kode. '" data3="'. $row->supplier. '" data4="'. $row->alamat. '" data5="'. $row->desa. '" data6="'. $row->kecamatan. '" data7="'. $row->kabupaten. '" data100="'. $row->id. '">'.($row->kode ? $row->kode : '-').'</a> ';
            })
            ->addColumn('supplier', function ($row) { 
                return '<a href="#" style="color: white;" title="'. ($row->supplier ? $row->supplier : '-') .'" class="item_kode " data1="' . $row->id . '" data2="'. $row->kode. '" data3="'. $row->supplier. '" data4="'. $row->alamat. '" data5="'. $row->desa. '" data6="'. $row->kecamatan. '" data7="'. $row->kabupaten. '" data100="'. $row->id. '">'.($row->supplier ? $row->supplier : '-').'</a> ';
            })
            ->addColumn('alamat', function ($row) { 
                return '<a href="#" style="color: white;" title="'. ($row->alamat ? $row->alamat : '-') .'" class="item_kode " data1="' . $row->id . '" data2="'. $row->kode. '" data3="'. $row->supplier. '" data4="'. $row->alamat. '" data5="'. $row->desa. '" data6="'. $row->kecamatan. '" data7="'. $row->kabupaten. '" data100="'. $row->id. '">'.($row->alamat ? $row->alamat : '-').'</a> ';
            })
            
            ->addColumn('saldo', function ($row) {
                $saldox = Hutang::where('idsupplier','=',$row->id)
                    ->where('kodepokok','=','1')
                    ->sum('pokok');
                return number_format($saldox,0);
            })
            
            // ->addColumn('action', function ($row) {
            //     return '<a href="#" title="Edit Data" class="btn btn-success btn-xs item_edit" data="' . $row->id . '" data2="'. $row->ecard.'" data3="'. $row->nia.'" data4="'. $row->ktp.'"><i style="font-size:18px" class="fa">&#xf044;</i></a> ' .
            //            '<a href="#" title="Hapus Data" class="btn btn-danger btn-xs item_hapus" data="' . $row->id . '" data2="'. $row->ecard.'" data3="'. $row->nia.'" data4="'. $row->ktp.'"><i style="font-size:18px" class="fa">&#xf00d;</i></a>';
            // })
            
            ->rawColumns([
                'kode',
                'supplier',
                'alamat',
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
            ->with(['hutang', 'anggota','supplier'])
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
        $idsupplier = session('idsupplier1');
        $tampil = Hutang::where('idsupplier','=',$idsupplier)
            ->where('idanggota','=','0')
            ->where('kodepokok','=','1')
            ->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>". $baris->nomorstatus . "</option>";
        }
    }
    function listhutangx()
    {
        $idsupplier = session('idsupplier1');
        $tampil = Hutang::where('idsupplier','=',$idsupplier)
            ->where('idanggota','=','0')
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
    function listsupplier()
    {
        $idsupplierx = Hutang::select('idsupplier')
            ->where('idanggota','=','0')
            ->where('kodepokok','=','1');
    
        $tampil = Supplier::whereIn('id',$idsupplierx)
            ->get();
        foreach ($tampil as $baris) {
            $saldox = Hutang::where('idsupplier','=',$baris->id)
                    ->where('idanggota','=','0')
                    ->where('kodepokok','=','1')
                    ->sum('pokok');
            echo "<option value='" . $baris->id . "'>".$baris->kode."|".$baris->supplier."|".$baris->alamat."|".$baris->desa."|". $baris->kecamatan."|". $saldox . "</option>";
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
        
        //nomor kwitansi contoh : BHS.010.20230527.0009
        $nbx = 'BHS' . '.' . $idx . '.' . $tgl1 . '.' . $no;  
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
            'idsupplier1' => $request['idsupplier1'],
        ]);
        
    }

    public function cariid(Request $request)
    {
        $idsupplierx = Hutang::select('idsupplier')
        ->where('idanggota','=','0')
        ->where('kodepokok','=','1');
    
        $cari = $request['cari1'];        
        
        session(['carix' => $cari]);

        $data = Supplier::whereIn('id',$idsupplierx)
                ->where(function (Builder $query) {
                    $cari = session('carix');
                    return $query->where('kode','=',$cari)
                    ->Orwhere('kode','=',$cari)
                    ->Orwhere('kode','=',$cari)
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
