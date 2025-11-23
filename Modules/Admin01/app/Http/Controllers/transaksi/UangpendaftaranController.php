<?php

namespace Modules\Admin01\Http\Controllers\transaksi;

use Carbon\Carbon;
use App\Models\Menusub;
use App\Models\Aplikasi;
use PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Modules\Admin01\Models\Anggota;
use Modules\Admin01\Models\Uangpendaftaran;
use Modules\Akuntansi01\Models\Coa;
use Modules\Akuntansi01\Models\Jurnal;
use Modules\Akuntansi01\Models\Kategori;
use Modules\Akuntansi01\Models\Setingjurnal;


use Yajra\DataTables\Facades\DataTables;

class  UangpendaftaranController extends Controller
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
        $remark = 'Halaman ini digunakan untuk menampilkan, menambah, mengubah dan menghapus <b>Uang Pendaftaran</b>.<br>
            <div class="row">
                <div class="mt-2 text-right px-2">
                    <h6>Keterangan :</h6>         
                </div>
                <div class="">
                    <table border="1">
                        <tr>        
                            <td class="p-1 px-3" style="background-color: pink">Data Belum Diposting</td>
                            <td class="p-1 px-3">Data Sudah Diposting</td>
                        </tr>
                    </table>
                </div>
            </div>    
        ';
        $page = 'admin01::transaksi.uangpendaftaran';
        $link = '/admin01/transaksi/uangpendaftaran';
        $subtitle = 'Transaksi';
        $caption = 'Uang Pendaftaran';
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
            'tabel' => Uangpendaftaran::SimplePaginate($jmlhal)->withQueryString(),
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
        
        date_default_timezone_set('Asia/Jakarta');
        $created_at1 = date('Y-m-d  H:i:s');
        $updated_at1 = date('Y-m-d  H:i:s');

        $id = $request['id1'];
        $nb = explode(".", $request['nomorbukti1']);
        $nomorb1 = $nb[3];

        $validatedData = $request->validate([
            'idanggota1' => 'required',
            'nomorbukti1' => 'required',
            'tgltransaksi1' => 'required',
        ]);
        
        //untuk input tabel yang asli
        $data1 = [
            'tgltransaksi' => $validatedData['tgltransaksi1'],           
            'idanggota' => $validatedData['idanggota1'],
            'nomorbukti' => $validatedData['nomorbukti1'],
            'nomorb' => $nomorb1,
            'created_at' => $created_at1,
            'updated_at' => $updated_at1,
            'jml' => $request['jml1'],
            'keterangan' => $request['keterangan1'],
        ];

        $data2 = [
            'tgltransaksi' => $validatedData['tgltransaksi1'],           
            'idanggota' => $validatedData['idanggota1'],
            'nomorbukti' => $validatedData['nomorbukti1'],
            'nomorb' => $nomorb1,            
            'updated_at' => $updated_at1,
            'jml' => $request['jml1'],
            'keterangan' => $request['keterangan1'],
        ];

        if ($id == '0') {
            Uangpendaftaran::create($data1);
        } else {
            Uangpendaftaran::where('id', '=', $id)->update($data2);
        }
        return json_encode('data');
    }

    public function posting(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $create_at1 = date('Y-m-d  H:i:s');         

        $id1 = $request['id1'];        
        $tglposting1 = $request['tglposting1'];
        $nomorposting1 = $request['nomorposting1'];  
        
        //ambil nomorp1
        $np = explode(".", $request['nomorposting1']);
        $nomorp1 = intval($np[3]);

        //tabel uangpendaftaran
        $tampil = Uangpendaftaran::select('*')
        ->where('id','=',$id1)
        ->get();
        foreach ($tampil as $baris) {
            $tgltransaksi1 = $baris->tgltransaksi;
            $nomorbukti1 = $baris->nomorbukti;
            $jml1 = $baris->jml;
            $keterangan1 = $baris->keterangan;
            $idanggota1 = $baris->idanggota;
        }

        //tabel Anggota
        $tampil2 = Anggota::select('*')
        ->where('id','=',$idanggota1)
        ->get();
        foreach ($tampil2 as $baris) {
            $nama1 = $baris->nama;           
        }

        //tabel setingjurnal
        $tampil3 = Setingjurnal::select('*')
        ->with([
            'coa01d','coa01k',
            'coa02d','coa02k',
            'coa03d','coa03k',
            'coa04d','coa04k',
            'coa05d','coa05k',
            'coa06d','coa06k',

            'jenisjurnal01d','jenisjurnal01k',
            'jenisjurnal02d','jenisjurnal02k',
            'jenisjurnal03d','jenisjurnal03k',
            'jenisjurnal04d','jenisjurnal04k',
            'jenisjurnal05d','jenisjurnal05k',
            'jenisjurnal06d','jenisjurnal06k',
            ])
        ->where('kode','=','002')
        ->get();
        foreach ($tampil3 as $baris) {
            $idcoa01d1 = $baris->idcoa01d;
            $idcoa02d1 = $baris->idcoa02d;
            $idcoa03d1 = $baris->idcoa03d;
            $idcoa04d1 = $baris->idcoa04d;
            $idcoa05d1 = $baris->idcoa05d;
            $idcoa06d1 = $baris->idcoa06d;
            $idcoa01k1 = $baris->idcoa01k;
            $idcoa02k1 = $baris->idcoa02k;
            $idcoa03k1 = $baris->idcoa03k;
            $idcoa04k1 = $baris->idcoa04k;
            $idcoa05k1 = $baris->idcoa05k;
            
            $idjenisjurnal01d1 = $baris->idjenisjurnal01d;
            $idjenisjurnal02d1 = $baris->idjenisjurnal02d;
            $idjenisjurnal03d1 = $baris->idjenisjurnal03d;
            $idjenisjurnal04d1 = $baris->idjenisjurnal04d;
            $idjenisjurnal05d1 = $baris->idjenisjurnal05d;
            $idjenisjurnal06d1 = $baris->idjenisjurnal06d;
            $idjenisjurnal01k1 = $baris->idjenisjurnal01k;
            $idjenisjurnal02k1 = $baris->idjenisjurnal02k;
            $idjenisjurnal03k1 = $baris->idjenisjurnal03k;
            $idjenisjurnal04k1 = $baris->idjenisjurnal04k;
            $idjenisjurnal05k1 = $baris->idjenisjurnal05k;
            $idjenisjurnal06k1 = $baris->idjenisjurnal06k;

        }

        $tampil4 = Coa::where('id','=',$idcoa01d1)->get();
        foreach ($tampil4 as $baris) {
            $idkelompokd1 = $baris->kodekelompok;
            $idkategorid1 = $baris->idkategori;
        }
        $tampil5 = Coa::where('id','=',$idcoa01k1)->get();
        foreach ($tampil5 as $baris) {
            $idkelompokk1 = $baris->kodekelompok;
            $idkategorik1 = $baris->idkategori;
        }

        /*
        rn : 1=akun ril(aset, kewajiban, modal), 2=akun nominal 
        apl : 1=aktiva(aset, hpp), 2=pasiva(kewajiban, modal dan SHU) 3=laba rugi(pendapatan, beban)
        nl : 1=saldo awal, 2=perubahan, 3-penyesuaian, dst
        */
        //simpan jurnal(debet)
            $datadebet = [
                'rn' => 1,
                'idkelompok' => $idkelompokd1,
                'apl' => 1,
                'idkategori' => $idkategorid1,
                'nl' => 2,
                'idcoa' => $idcoa01d1,
                'idjenisjurnal' => $idjenisjurnal01d1,
                'idnasabaht' => $idanggota1,
                'nama' => $nama1,
                'idsandi' => 1,
                'tglstatus' => $tgltransaksi1,
                'nomorstatus' => $nomorbukti1,                
                'idstatus' => $id1,
                'create_at' => $create_at1,
                'tglposting' => $tglposting1,
                'nomorp' => $nomorp1,
                'nomorposting' => $nomorposting1,
                'debet' => $jml1,
                'kredit' => 0,
                'pengesahan' => substr(auth()->user()->name, 0, 6),
                'idusers' => auth()->user()->id,
                'email' => auth()->user()->email,
                'keterangan' => $keterangan1,                
            ];

            $datakredit = [
                'rn' => 1,
                'idkelompok' => $idkelompokk1,
                'apl' => 3,
                'idkategori' => $idkategorik1,
                'nl' => 2,
                'idcoa' => $idcoa01k1,
                'idjenisjurnal' => $idjenisjurnal01k1,
                'idnasabaht' => $idanggota1,
                'nama' => $nama1,
                'idsandi' => 1,
                'tglstatus' => $tgltransaksi1,
                'nomorstatus' => $nomorbukti1,                
                'idstatus' => $id1,
                'create_at' => $create_at1,
                'tglposting' => $tglposting1,
                'nomorp' => $nomorp1,
                'nomorposting' => $nomorposting1,
                'debet' => 0,
                'kredit' => $jml1,
                'pengesahan' => substr(auth()->user()->name, 0, 6),
                'idusers' => auth()->user()->id,
                'email' => auth()->user()->email,
                'keterangan' => $keterangan1,                
            ];

            Jurnal::create($datadebet);
            Jurnal::create($datakredit);
        
        //untuk update uangpendaftaran
        $data2 = [
            'tglposting' => $request['tglposting1'],
            'nomorposting' => $request['nomorposting1'],                        
        ];
                 
            Uangpendaftaran::with(['anggota']) 
            ->where('id','=', $id1)            
            ->update($data2);

        //update anggota
        $data3 = [
            'uangdaftar' => $jml1,
            'nomorbuktiud' => $nomorbukti1,
        ];
        Anggota::where('id','=', $idanggota1)
            ->update($data3);

        return json_encode('data');
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
        $idanggotax1 = session('idanggotax1');
        if($idanggotax1==''){
            $idanggotax1=0; 
        }

        $datax = DataTables::of(
            Uangpendaftaran::with(['anggota'])
                ->orderBy('id','desc')
                ->where('idanggota', $idanggotax1)
            );

        $data = $datax
        
        ->addIndexColumn()
        ->addColumn('updated_ats', function ($row) {
            $updated_at = explode(" ", $row->updated_at);
            $times = $updated_at[1];
            return $row->tgltransaksi . ' ' . $times;
        })
        ->addColumn('jml', function ($row) {
            return $row->jml ? number_format($row->jml,0) : '0';
        })
        
        ->addColumn('action', function ($row) {
            $tglposting = $row->tglposting;
            if(is_null($tglposting)){
                return '<a href="#" title="Edit Data" class="btn btn-success btn-xs item_edit" data="' . $row->id . '" data2="'. $row->idanggota.'" data3="'. $row->tgltransaksi.'" data4="'. $row->updated_at.'" data5="'. $row->nomorbukti.'" data6="'. $row->jml.'"><i style="font-size:18px" class="fa">&#xf044;</i></a> ' .
                '<a href="#" title="Posting Data" class="btn btn-warning btn-xs item_posting" data="' . $row->id . '" data2="'. $row->idanggota.'" data3="'. $row->tgltransaksi.'" data4="'. $row->updated_at.'" data5="'. $row->nomorbukti.'" data6="'. $row->jml.'"><i style="font-size:18px" class="fa">&#xf093;</i></a> ' .
                '<a href="#" title="Hapus Data" class="btn btn-danger btn-xs item_hapus" data="' . $row->id . '" data2="'. $row->idanggota.'" data3="'. $row->tgltransaksi.'" data4="'. $row->updated_at.'" data5="'. $row->nomorbukti.'" data6="'. $row->jml.'"><i style="font-size:18px" class="fa">&#xf00d;</i></a>';
            }else{
                return '<a href="#" title="Print Data" class="btn btn-info btn-xs item_print" data="' . $row->id . '" data2="'. $row->idanggota.'" data3="'. $row->tgltransaksi.'" data4="'. $row->updated_at.'" data5="'. $row->nomorbukti.'" data6="'. $row->jml.'" data7="'. $row->tglposting.'"><i style="font-size:18px" class="fa">&#xf02f;</i></a> ';
            }
        })
        
        ->rawColumns([
            'updated_ats',
            'action'
            ])


        ->make(true);

        return $data;

    }
    
    public function showanggota()
    {
        
        $datax = DataTables::of(
            Anggota::with(['desa'])
                ->where('uangdaftar','<=',0)                
                ->orderBy('nama','asc')
            );

        $data = $datax
            ->addIndexColumn()

            ->addColumn('namas', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->nama ? $row->nama : '-') .'" class="item_nama" 
                    data1="' . $row->id . '" 
                    data2="'. $row->nama . '" 
                    data3="'. $row->nia . '" 
                    data4="'. $row->nik . '" 
                    data5="'. $row->ecard . '" 
                    data6="'. $row->alamat . '" 
                    data7="'. $row->desa->desa . '" 
                    data8="'. $row->desa->kecamatan->kecamatan . '" 
                    data9="'. $row->desa->kecamatan->kabupaten->kabupaten . '" 
                    data10="'. $row->desa->kecamatan->kabupaten->propinsi->propinsi . '"
                    data11="'. $row->tglkeluar . '"
                    data12="'. $row->tgllahir . '"
                    data13="'. $row->telp . '"
                    data14="'. $row->uangdaftar . '"
                    >'.($row->nama ? $row->nama : '-').'</a> ';
            })
            ->addColumn('nias', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->nia ? $row->nia : '-') .'" class="item_nia" 
                    data1="' . $row->id . '" 
                    data2="'. $row->nama . '" 
                    data3="'. $row->nia . '" 
                    data4="'. $row->nik . '" 
                    data5="'. $row->ecard . '" 
                    data6="'. $row->alamat . '" 
                    data7="'. $row->desa->desa . '" 
                    data8="'. $row->desa->kecamatan->kecamatan . '" 
                    data9="'. $row->desa->kecamatan->kabupaten->kabupaten . '" 
                    data10="'. $row->desa->kecamatan->kabupaten->propinsi->propinsi . '"
                    data11="'. $row->tglkeluar . '"
                    data12="'. $row->tgllahir . '"
                    data13="'. $row->telp . '"
                    data14="'. $row->uangdaftar . '"
                    >'.($row->nia ? $row->nia : '-').'</a> ';
            })
            ->addColumn('niks', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->nik ? $row->nik : '-') .'" class="item_nik" 
                    data1="' . $row->id . '" 
                    data2="'. $row->nama . '" 
                    data3="'. $row->nia . '" 
                    data4="'. $row->nik . '" 
                    data5="'. $row->ecard . '" 
                    data6="'. $row->alamat . '" 
                    data7="'. $row->desa->desa . '" 
                    data8="'. $row->desa->kecamatan->kecamatan . '" 
                    data9="'. $row->desa->kecamatan->kabupaten->kabupaten . '" 
                    data10="'. $row->desa->kecamatan->kabupaten->propinsi->propinsi . '"
                    data11="'. $row->tglkeluar . '"
                    data12="'. $row->tgllahir . '"
                    data13="'. $row->telp . '"
                    data14="'. $row->uangdaftar . '"
                    >'.($row->nik ? $row->nik : '-').'</a> ';
            })
            
            ->addColumn('ecards', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->ecard ? $row->ecard : '-') .'" class="item_ecard " 
                    data1="' . $row->id . '" 
                    data2="'. $row->nama . '" 
                    data3="'. $row->nia . '" 
                    data4="'. $row->nik . '" 
                    data5="'. $row->ecard . '" 
                    data6="'. $row->alamat . '" 
                    data7="'. $row->desa->desa . '" 
                    data8="'. $row->desa->kecamatan->kecamatan . '" 
                    data9="'. $row->desa->kecamatan->kabupaten->kabupaten . '" 
                    data10="'. $row->desa->kecamatan->kabupaten->propinsi->propinsi . '"
                    data11="'. $row->tglkeluar . '"
                    data12="'. $row->tgllahir . '"
                    data13="'. $row->telp . '"
                    data14="'. $row->uangdaftar . '"
                    >'.($row->ecard ? $row->ecard : '-').'</a> ';
            })
            

            ->addColumn('desa', function ($row) {
                return $row->iddesa ? $row->desa->desa : '';
            })
            ->addColumn('kecamatan', function ($row) {
                return $row->iddesa ? $row->desa->kecamatan->kecamatan : '';
            })
            ->addColumn('kabupaten', function ($row) {
                return $row->iddesa ? $row->desa->kecamatan->kabupaten->kabupaten : '';
            })
            ->addColumn('propinsi', function ($row) {
                return $row->iddesa ? $row->desa->kecamatan->kabupaten->propinsi->propinsi : '';
            })

            ->rawColumns([
                'namas',
                'nias',
                'niks',
                'ecards',
                'desa',                
                'kecamatan',
                'kabupaten',
                'propinsi',
            ])

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
        $data = Uangpendaftaran::where('id', '=', $id)
            ->with(['anggota'])
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
        $data = Uangpendaftaran::where('id', '=', $id)->delete();
        return json_encode(array('data' => $data));
    }

    public function nomorbukti(Request $request)
    {
        
        $tgltransaksi1=$request['tgltransaksi1'];
        $tgl1 = Carbon::parse($tgltransaksi1)->format('Ymd');
        $ym1 = Carbon::parse($tgltransaksi1)->format('Ym');
        $d1 = Carbon::parse($tgltransaksi1)->format('d');
        $m1 = Carbon::parse($tgltransaksi1)->format('m');
        $y1 = Carbon::parse($tgltransaksi1)->format('Y');
        
        $jmldata = Uangpendaftaran::where('tgltransaksi','=',$tgltransaksi1)            
            ->count();
        if ($jmldata==0){
            $nomor = 1;    
        }else{
            $nomor = Uangpendaftaran::select('nomorb')
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

        //nomor kwitansi contoh : UDF.001.20230527.0009
        $nbx = 'UDF' . '.' . $idx . '.' . $tgl1 . '.' . $no;  
        $nomorbukti = "'$nbx'";

        $data = Kategori::limit(1)
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
        
        $jmldata = Jurnal::where('tglposting','=',$tglposting1)            
            ->count();
        if ($jmldata==0){
            $nomor = 1;    
        }else{
            $nomor = Jurnal::select('nomorp')
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

        //nomor kwitansi contoh : POS.001.20230527.0009
        $nbx = 'POS' . '.' . $idx . '.' . $tgl1 . '.' . $no;  
        $nomorposting = "'$nbx'";

        $data = Kategori::limit(1)
                ->selectRaw($nomorposting . ' as nomorposting')                
                ->get();
         
        return json_encode(array('data' => $data));        
    }

    public function kirimsyarat(Request $request)
    {
        session([
            'id1' => $request['id1'],
            'cariidx1' => $request['cariidx1'],
            'namax1' => $request['namax1'],
            'niax1' => $request['niax1'],
            'nikx1' => $request['nikx1'],
            'ecardx1' => $request['ecardx1'],
            'alamatx1' => $request['alamatx1'],
            'idanggotax1' => $request['idanggotax1'],
            'tgltransaksi1' => $request['tgltransaksi1'],
            'nomorbukti1' => $request['nomorbukti1'],
            'tglawalx1' => $request['tglawalx1'],
            'tglakhirx1' => $request['tglakhirx1'],
        ]);
        
    }
    public function cariid(Request $request)
    {
        $cari = $request['cari1'];

        $tampil = Anggota::where('nia','=',$cari)
                ->get();
        foreach ($tampil as $item) {
            $idanggota = $item->id;
        }
               
        $data = Anggota::limit(1)->select('*')
                ->where('nia','=',$cari)
                ->get();
         
        return json_encode(array('data' => $data));

    }

public function printkwitansi()
    {
        
        $id1 = session('id1');
        
        $tampil = Uangpendaftaran::with(['anggota'])
            ->where('id','=',$id1)
            ->get();
        foreach ($tampil as $baris) {
            $nomorbukti1 = $baris->nomorbukti;
            $jml1 = $baris->jml;
            $guna1 = $baris->keterangan;
            $guna2 = ' ';
            $tgltransaksi1 = $baris->tgltransaksi;
            $pemberi1 = $baris->anggota->nama . ' / ' . $baris->anggota->nia ;
        }

        $penerima1 = session('memnama');
        $namapenerima1 = auth()->user()->name;
        
        $page = 'admin01::laporan.printkwitansi';
        $link = '/admin01/laporan/printkwitansi';

        $pdf = PDF::loadView($page, [            
                       
            'tabel' => $tampil,
            'nomorbukti1' => $nomorbukti1,
            'pemberi1' => $pemberi1,
            'jml1' => $jml1,
            'guna1' => $guna1,
            'guna2' => $guna2,
            'tgltransaksi1' => $tgltransaksi1,
            'penerima1' => $penerima1,
            'namapenerima1' => $namapenerima1,

        ])->setOptions(['defaultFont' => 'sans-serif', 'defaultFont' => 'sans-serif' ])->set_option('isHtml5ParserEnabled', true); 
        $pdf->set_paper(array(0, 0, 612.00, 936.00), 'portrait');
        
        return $pdf->stream('kwitansi-uang-pendaftaran.pdf');

        // static $PAPER_SIZES = array(
        //     "4a0" => array(0, 0, 4767.87, 6740.79),
        //     "2a0" => array(0, 0, 3370.39, 4767.87),
        //     "a0" => array(0, 0, 2383.94, 3370.39),
        //     "a1" => array(0, 0, 1683.78, 2383.94),
        //     "a2" => array(0, 0, 1190.55, 1683.78),
        //     "a3" => array(0, 0, 841.89, 1190.55),
        //     "a4" => array(0, 0, 595.28, 841.89),
        //     "a5" => array(0, 0, 419.53, 595.28),
        //     "a6" => array(0, 0, 297.64, 419.53),
        //     "a7" => array(0, 0, 209.76, 297.64),
        //     "a8" => array(0, 0, 147.40, 209.76),
        //     "a9" => array(0, 0, 104.88, 147.40),
        //     "a10" => array(0, 0, 73.70, 104.88),
        //     "b0" => array(0, 0, 2834.65, 4008.19),
        //     "b1" => array(0, 0, 2004.09, 2834.65),
        //     "b2" => array(0, 0, 1417.32, 2004.09),
        //     "b3" => array(0, 0, 1000.63, 1417.32),
        //     "b4" => array(0, 0, 708.66, 1000.63),
        //     "b5" => array(0, 0, 498.90, 708.66),
        //     "b6" => array(0, 0, 354.33, 498.90),
        //     "b7" => array(0, 0, 249.45, 354.33),
        //     "b8" => array(0, 0, 175.75, 249.45),
        //     "b9" => array(0, 0, 124.72, 175.75),
        //     "b10" => array(0, 0, 87.87, 124.72),
        //     "c0" => array(0, 0, 2599.37, 3676.54),
        //     "c1" => array(0, 0, 1836.85, 2599.37),
        //     "c2" => array(0, 0, 1298.27, 1836.85),
        //     "c3" => array(0, 0, 918.43, 1298.27),
        //     "c4" => array(0, 0, 649.13, 918.43),
        //     "c5" => array(0, 0, 459.21, 649.13),
        //     "c6" => array(0, 0, 323.15, 459.21),
        //     "c7" => array(0, 0, 229.61, 323.15),
        //     "c8" => array(0, 0, 161.57, 229.61),
        //     "c9" => array(0, 0, 113.39, 161.57),
        //     "c10" => array(0, 0, 79.37, 113.39),
        //     "ra0" => array(0, 0, 2437.80, 3458.27),
        //     "ra1" => array(0, 0, 1729.13, 2437.80),
        //     "ra2" => array(0, 0, 1218.90, 1729.13),
        //     "ra3" => array(0, 0, 864.57, 1218.90),
        //     "ra4" => array(0, 0, 609.45, 864.57),
        //     "sra0" => array(0, 0, 2551.18, 3628.35),
        //     "sra1" => array(0, 0, 1814.17, 2551.18),
        //     "sra2" => array(0, 0, 1275.59, 1814.17),
        //     "sra3" => array(0, 0, 907.09, 1275.59),
        //     "sra4" => array(0, 0, 637.80, 907.09),
        //     "letter" => array(0, 0, 612.00, 792.00),
        //     "legal" => array(0, 0, 612.00, 1008.00),
        //     "ledger" => array(0, 0, 1224.00, 792.00),
        //     "tabloid" => array(0, 0, 792.00, 1224.00),
        //     "executive" => array(0, 0, 521.86, 756.00),
        //     "folio" => array(0, 0, 612.00, 936.00),
        //     "commercial #10 envelope" => array(0, 0, 684, 297),
        //     "catalog #10 1/2 envelope" => array(0, 0, 648, 864),
        //     "8.5x11" => array(0, 0, 612.00, 792.00),
        //     "8.5x14" => array(0, 0, 612.00, 1008.0),
        //     "11x17" => array(0, 0, 792.00, 1224.00),
        // );
        
    }





}
