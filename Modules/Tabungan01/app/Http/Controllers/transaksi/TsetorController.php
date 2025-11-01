<?php

namespace Modules\Tabungan01\Http\Controllers\transaksi;

use Carbon\Carbon;
use App\Models\Menusub;
use App\Models\Aplikasi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Modules\Akuntansi01\Models\Jurnal;
use Modules\Akuntansi01\Models\Kategori;
use Modules\Akuntansi01\Models\Produktabungan;
use Modules\Tabungan01\Models\Nasabah;
use Modules\Tabungan01\Models\Tfkeluar;
use Modules\Tabungan01\Models\Tfmasuk;
use Modules\Tabungan01\Models\Tsetor;
use Modules\Tabungan01\Models\Ttarik;
use Yajra\DataTables\Facades\DataTables;

class TsetorController extends Controller
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
        $remark = 'Halaman ini digunakan untuk menampilkan, menambah, mengubah dan menghapus <b>Tabungan Setor</b>.<br>
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
        $page = 'tabungan01::transaksi.tsetor';
        $link = '/tabungan01/transaksi/tsetor';
        $subtitle = 'Transaksi';
        $caption = 'Tabungan Setor';
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
            'tabel' => Tsetor::SimplePaginate($jmlhal)->withQueryString(),
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
        $nomorb1 = $nb[2];

        $validatedData = $request->validate([
            'idnasabah1' => 'required',
            'nomorbukti1' => 'required',
            'tgltransaksi1' => 'required',
        ]);
        
        //untuk input tabel yang asli
        $data1 = [
            'tgltransaksi' => $validatedData['tgltransaksi1'],           
            'idnasabah' => $validatedData['idnasabah1'],
            'nomorbukti' => $validatedData['nomorbukti1'],
            'nomorb' => $nomorb1,
            'created_at' => $created_at1,
            'updated_at' => $updated_at1,
            'jml' => $request['jml1'],
            'keterangan' => $request['keterangan1'],
        ];

        $data2 = [
            'tgltransaksi' => $validatedData['tgltransaksi1'],           
            'idnasabah' => $validatedData['idnasabah1'],
            'nomorbukti' => $validatedData['nomorbukti1'],
            'nomorb' => $nomorb1,            
            'updated_at' => $updated_at1,
            'jml' => $request['jml1'],
            'keterangan' => $request['keterangan1'],
        ];

        if ($id == '0') {
            Tsetor::create($data1);
        } else {
            Tsetor::where('id', '=', $id)->update($data2);
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
        $nomorp1 = intval($np[2]);

        //tabel tsetor
        $tampil = Tsetor::select('*')
        ->where('id','=',$id1)
        ->get();
        foreach ($tampil as $baris) {
            $tgltransaksi1 = $baris->tgltransaksi;
            $nomorbukti1 = $baris->nomorbukti;
            $jml1 = $baris->jml;
            $keterangan1 = $baris->keterangan;
            $idnasabah1 = $baris->idnasabah;
        }

        //tabel nasabah
        $tampil2 = Nasabah::select('*')
        ->where('id','=',$idnasabah1)
        ->get();
        foreach ($tampil2 as $baris) {
            $idproduk1 = $baris->idproduktabungan;           
            $nama1 = $baris->nama;           
        }

        //tabel produk
        $tampil3 = Produktabungan::select('*')
        ->with(['coasetord','coasetork','coatarikd','coatarikk','coatfmasukd','coatfmasukk',
            'coatfkeluard','coatfkeluark','jenisjurnalsetord','jenisjurnalsetork',
            'jenisjurnaltarikd','jenisjurnaltarikk','jenisjurnaltfmasukd','jenisjurnaltfmasukk',
            'jenisjurnaltfkeluard','jenisjurnaltfkeluark'])
        ->where('id','=',$idproduk1)
        ->get();
        foreach ($tampil3 as $baris) {
            $idcoasetord1 = $baris->idcoasetord;
            $idcoasetork1 = $baris->idcoasetork;
            
            $idkelompokd1 = $baris->coasetord->kodekelompok;
            $idkelompokk1 = $baris->coasetork->kodekelompok;
            
            $idkategorid1 = $baris->coasetord->idkategori;
            $idkategorik1 = $baris->coasetork->idkategori;
            
            $idjenisjurnald1 = $baris->jenisjurnalsetord->id;
            $idjenisjurnalk1 = $baris->jenisjurnalsetork->id;

        }

        /*
        rn : 1=akun ril, 2=akun nominal 
        apl : 1=aktiva, 2=pasiva 3=laba rugi
        nl : 1=saldo awal, 2=perubahan, dst
        */
        //simpan jurnal(debet)
            $datadebet = [
                'rn' => 1,
                'idkelompok' => $idkelompokd1,
                'apl' => 1,
                'idkategori' => $idkategorid1,
                'nl' => 2,
                'idcoa' => $idcoasetord1,
                'idjenisjurnal' => $idjenisjurnald1,
                'idnasabah' => $idnasabah1,
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
                'apl' => 2,
                'idkategori' => $idkategorik1,
                'nl' => 2,
                'idcoa' => $idcoasetork1,
                'idjenisjurnal' => $idjenisjurnalk1,
                'idnasabah' => $idnasabah1,
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
        
        //untuk update tsetor
        $data = [
            'tglposting' => $request['tglposting1'],
            'nomorposting' => $request['nomorposting1'],                        
        ];
                 
            Tsetor::with(['nasabah']) 
            ->where('id','=', $id1)            
            ->update($data);
        
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
        $idnasabahx1 = session('idnasabahx1');
        if($idnasabahx1==''){
            $idnasabahx1=0; 
        }

        $datax = DataTables::of(
            Tsetor::with(['nasabah'])
                ->orderBy('id','desc')
                ->where('idnasabah', $idnasabahx1)
            );

        $data = $datax
        
        ->addIndexColumn()
        
        ->addColumn('jml', function ($row) {
            return $row->jml ? number_format($row->jml,0) : '0';
        })
        
        ->addColumn('action', function ($row) {
            $tglposting = $row->tglposting;
            if(is_null($tglposting)){
                return '<a href="#" title="Edit Data" class="btn btn-success btn-xs item_edit" data="' . $row->id . '" data2="'. $row->idnasabah.'" data3="'. $row->tgltransaksi.'" data4="'. $row->updated_at.'" data5="'. $row->nomorbukti.'" data6="'. $row->jml.'"><i style="font-size:18px" class="fa">&#xf044;</i></a> ' .
                '<a href="#" title="Posting Data" class="btn btn-warning btn-xs item_posting" data="' . $row->id . '" data2="'. $row->idnasabah.'" data3="'. $row->tgltransaksi.'" data4="'. $row->updated_at.'" data5="'. $row->nomorbukti.'" data6="'. $row->jml.'"><i style="font-size:18px" class="fa">&#xf093;</i></a> ' .
                '<a href="#" title="Hapus Data" class="btn btn-danger btn-xs item_hapus" data="' . $row->id . '" data2="'. $row->idnasabah.'" data3="'. $row->tgltransaksi.'" data4="'. $row->updated_at.'" data5="'. $row->nomorbukti.'" data6="'. $row->jml.'"><i style="font-size:18px" class="fa">&#xf00d;</i></a>';
            }else{
                return '<a href="#" title="Print Data" class="btn btn-info btn-xs item_print" data="' . $row->id . '" data2="'. $row->idnasabah.'" data3="'. $row->tgltransaksi.'" data4="'. $row->updated_at.'" data5="'. $row->nomorbukti.'" data6="'. $row->jml.'" data7="'. $row->tglposting.'"><i style="font-size:18px" class="fa">&#xf02f;</i></a> ';
            }
        })
        
        ->rawColumns(['action'])


        ->make(true);

        return $data;

    }

    public function shownasabah()
    {
        
        $datax = DataTables::of(
            Nasabah::with(['produktabungan','anggota'])                
                ->orderBy('nama','asc')
            );

        $data = $datax
            ->addIndexColumn()

            ->addColumn('nama', function ($row) {
                $tsetorx = Tsetor::select('jml')
                    ->where('idnasabah','=',$row->id)
                    ->whereNotNull('nomorposting')
                    ->sum('jml');
                $ttarikx = Ttarik::select('jml')
                    ->where('idnasabah','=',$row->id)
                    ->whereNotNull('nomorposting')
                    ->sum('jml');                
                $tfkeluarx = Tfkeluar::select('jml')
                    ->where('idnasabah','=',$row->id)
                    ->whereNotNull('nomorposting')
                    ->sum('jml');
                $tfmasukx = Tfmasuk::select('jml')
                    ->where('idnasabah','=',$row->id)
                    ->whereNotNull('nomorposting')
                    ->sum('jml');
                $saldo = $tsetorx + $tfmasukx - $ttarikx - $tfkeluarx;
                return '<a href="#" style="color: white;" title="'. ($row->nama ? $row->nama : '-') .'" class="item_nama" 
                    data1="' . $row->id . '" 
                    data2="'. $row->nama . '" 
                    data3="'. $row->nia . '" 
                    data4="'. $row->nik . '" 
                    data5="'. $row->ecard . '" 
                    data6="'. $row->alamat . '" 
                    data7="'. $row->anggota->desa->desa . '" 
                    data8="'. $row->anggota->desa->kecamatan->kecamatan . '" 
                    data9="'. $row->anggota->desa->kecamatan->kabupaten->kabupaten . '" 
                    data10="'. $row->anggota->desa->kecamatan->kabupaten->propinsi->propinsi . '"
                    data11="'. $row->alamat . '"
                    data12="'. $row->tgllahir . '"
                    data13="'. $row->telp . '"
                    data14="'. $row->idproduktabungan . '"
                    data15="'. $row->produktabungan->produktabungan . '"
                    data16="'. $saldo . '"
                    data17="'. $row->norek . '"
                    >'.($row->nama ? $row->nama : '-').'</a> ';
            })
            ->addColumn('nia', function ($row) {
                $tsetorx = Tsetor::select('jml')
                    ->where('idnasabah','=',$row->id)
                    ->whereNotNull('nomorposting')
                    ->sum('jml');
                $ttarikx = Ttarik::select('jml')
                    ->where('idnasabah','=',$row->id)
                    ->whereNotNull('nomorposting')
                    ->sum('jml');                
                $tfkeluarx = Tfkeluar::select('jml')
                    ->where('idnasabah','=',$row->id)
                    ->whereNotNull('nomorposting')
                    ->sum('jml');
                $tfmasukx = Tfmasuk::select('jml')
                    ->where('idnasabah','=',$row->id)
                    ->whereNotNull('nomorposting')
                    ->sum('jml');
                $saldo = $tsetorx + $tfmasukx - $ttarikx - $tfkeluarx;
                return '<a href="#" style="color: white;" title="'. ($row->nia ? $row->nia : '-') .'" class="item_nia" 
                    data1="' . $row->id . '" 
                    data2="'. $row->nama . '" 
                    data3="'. $row->nia . '" 
                    data4="'. $row->nik . '" 
                    data5="'. $row->ecard . '" 
                    data6="'. $row->alamat . '" 
                    data7="'. $row->anggota->desa->desa . '" 
                    data8="'. $row->anggota->desa->kecamatan->kecamatan . '" 
                    data9="'. $row->anggota->desa->kecamatan->kabupaten->kabupaten . '" 
                    data10="'. $row->anggota->desa->kecamatan->kabupaten->propinsi->propinsi . '"
                    data11="'. $row->alamat . '"
                    data12="'. $row->tgllahir . '"
                    data13="'. $row->telp . '"
                    data14="'. $row->idproduktabungan . '"
                    data15="'. $row->produktabungan->produktabungan . '"
                    data16="'. $saldo . '"
                    data17="'. $row->norek . '"
                    >'.($row->nia ? $row->nia : '-').'</a> ';
            })
            ->addColumn('nik', function ($row) {
                $tsetorx = Tsetor::select('jml')
                    ->where('idnasabah','=',$row->id)
                    ->whereNotNull('nomorposting')
                    ->sum('jml');
                $ttarikx = Ttarik::select('jml')
                    ->where('idnasabah','=',$row->id)
                    ->whereNotNull('nomorposting')
                    ->sum('jml');                
                $tfkeluarx = Tfkeluar::select('jml')
                    ->where('idnasabah','=',$row->id)
                    ->whereNotNull('nomorposting')
                    ->sum('jml');
                $tfmasukx = Tfmasuk::select('jml')
                    ->where('idnasabah','=',$row->id)
                    ->whereNotNull('nomorposting')
                    ->sum('jml');
                $saldo = $tsetorx + $tfmasukx - $ttarikx - $tfkeluarx;
                return '<a href="#" style="color: white;" title="'. ($row->nik ? $row->nik : '-') .'" class="item_nik" 
                    data1="' . $row->id . '" 
                    data2="'. $row->nama . '" 
                    data3="'. $row->nia . '" 
                    data4="'. $row->nik . '" 
                    data5="'. $row->ecard . '" 
                    data6="'. $row->alamat . '" 
                    data7="'. $row->anggota->desa->desa . '" 
                    data8="'. $row->anggota->desa->kecamatan->kecamatan . '" 
                    data9="'. $row->anggota->desa->kecamatan->kabupaten->kabupaten . '" 
                    data10="'. $row->anggota->desa->kecamatan->kabupaten->propinsi->propinsi . '"
                    data11="'. $row->alamat . '"
                    data12="'. $row->tgllahir . '"
                    data13="'. $row->telp . '"
                    data14="'. $row->idproduktabungan . '"
                    data15="'. $row->produktabungan->produktabungan . '"
                    data16="'. $saldo . '"
                    data17="'. $row->norek . '"
                    >'.($row->nik ? $row->nik : '-').'</a> ';
            })
            
            ->addColumn('ecard', function ($row) {
                $tsetorx = Tsetor::select('jml')
                    ->where('idnasabah','=',$row->id)
                    ->whereNotNull('nomorposting')
                    ->sum('jml');
                $ttarikx = Ttarik::select('jml')
                    ->where('idnasabah','=',$row->id)
                    ->whereNotNull('nomorposting')
                    ->sum('jml');                
                $tfkeluarx = Tfkeluar::select('jml')
                    ->where('idnasabah','=',$row->id)
                    ->whereNotNull('nomorposting')
                    ->sum('jml');
                $tfmasukx = Tfmasuk::select('jml')
                    ->where('idnasabah','=',$row->id)
                    ->whereNotNull('nomorposting')
                    ->sum('jml');
                $saldo = $tsetorx + $tfmasukx - $ttarikx - $tfkeluarx;
                 
                return '<a href="#" style="color: white;" title="'. ($row->ecard ? $row->ecard : '-') .'" class="item_ecard " 
                    data1="' . $row->id . '" 
                    data2="'. $row->nama . '" 
                    data3="'. $row->nia . '" 
                    data4="'. $row->nik . '" 
                    data5="'. $row->ecard . '" 
                    data6="'. $row->alamat . '" 
                    data7="'. $row->anggota->desa->desa . '" 
                    data8="'. $row->anggota->desa->kecamatan->kecamatan . '" 
                    data9="'. $row->anggota->desa->kecamatan->kabupaten->kabupaten . '" 
                    data10="'. $row->anggota->desa->kecamatan->kabupaten->propinsi->propinsi . '"
                    data11="'. $row->alamat . '"
                    data12="'. $row->tgllahir . '"
                    data13="'. $row->telp . '"
                    data14="'. $row->idproduktabungan . '"
                    data15="'. $row->produktabungan->produktabungan . '"
                    data16="'. $saldo . '"
                    data17="'. $row->norek . '"
                    >'.($row->ecard ? $row->ecard : '-').'</a> ';
            })
            
            ->addColumn('saldo', function ($row) {
                $tsetorx = Tsetor::select('jml')
                    ->where('idnasabah','=',$row->id)
                    ->whereNotNull('nomorposting')
                    ->sum('jml');
                $ttarikx = Ttarik::select('jml')
                    ->where('idnasabah','=',$row->id)
                    ->whereNotNull('nomorposting')
                    ->sum('jml');                
                $tfkeluarx = Tfkeluar::select('jml')
                    ->where('idnasabah','=',$row->id)
                    ->whereNotNull('nomorposting')
                    ->sum('jml');
                $tfmasukx = Tfmasuk::select('jml')
                    ->where('idnasabah','=',$row->id)
                    ->whereNotNull('nomorposting')
                    ->sum('jml');
                $saldo = $tsetorx + $tfmasukx - $ttarikx - $tfkeluarx;
                
                return '<a href="#" style="color: white;" title="'. number_format($saldo,0) .'" class="item_saldo " 
                    data1="' . $row->id . '" 
                    data2="'. $row->nama . '" 
                    data3="'. $row->nia . '" 
                    data4="'. $row->nik . '" 
                    data5="'. $row->ecard . '" 
                    data6="'. $row->alamat . '" 
                    data7="'. $row->anggota->desa->desa . '" 
                    data8="'. $row->anggota->desa->kecamatan->kecamatan . '" 
                    data9="'. $row->anggota->desa->kecamatan->kabupaten->kabupaten . '" 
                    data10="'. $row->anggota->desa->kecamatan->kabupaten->propinsi->propinsi . '"
                    data11="'. $row->alamat . '"
                    data12="'. $row->tgllahir . '"
                    data13="'. $row->telp . '"
                    data14="'. $row->idproduktabungan . '"
                    data15="'. $row->produktabungan->produktabungan . '"
                    data16="'. $saldo . '"
                    data17="'. $row->norek . '"
                    >'. number_format($saldo,0) .'</a> ';
            })

            ->addColumn('desa', function ($row) {
                return $row->idanggota ? $row->anggota->desa->desa : '';
            })
            ->addColumn('kecamatan', function ($row) {
                return $row->idanggota ? $row->anggota->desa->kecamatan->kecamatan : '';
            })
            ->addColumn('kabupaten', function ($row) {
                return $row->idanggota ? $row->anggota->desa->kecamatan->kabupaten->kabupaten : '';
            })
            ->addColumn('propinsi', function ($row) {
                return $row->idanggota ? $row->anggota->desa->kecamatan->kabupaten->propinsi->propinsi : '';
            })

            ->rawColumns([
                'nama',
                'nia',
                'nik',
                'ecard',
                'saldo',
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
        $data = Tsetor::where('id', '=', $id)
            ->with(['nasabah'])
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
        $data = Tsetor::where('id', '=', $id)->delete();
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
        
        $jmldata = Tsetor::where('tgltransaksi','=',$tgltransaksi1)            
            ->count();
        if ($jmldata==0){
            $nomor = 1;    
        }else{
            $nomor = Tsetor::select('nomorb')
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

        //nomor kwitansi contoh : STR.20230527.0009
        $nbx = 'STR' . '.' . $tgl1 . '.' . $no;  
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

        //nomor kwitansi contoh : POS.20230527.0009
        $nbx = 'POS' . '.' . $tgl1 . '.' . $no;  
        $nomorposting = "'$nbx'";

        $data = Kategori::limit(1)
                ->selectRaw($nomorposting . ' as nomorposting')                
                ->get();
         
        return json_encode(array('data' => $data));        
    }

    public function kirimsyarat(Request $request)
    {
        session([

            'cariidx1' => $request['cariidx1'],
            'namax1' => $request['namax1'],
            'norekx1' => $request['norekx1'],
            'niax1' => $request['niax1'],
            'nikx1' => $request['nikx1'],
            'ecardx1' => $request['ecardx1'],
            'saldox1' => $request['saldox1'],
            'idnasabahx1' => $request['idnasabahx1'],
            'idproduktabunganx1' => $request['idproduktabunganx1'],
            'produktabunganx1' => $request['produktabunganx1'],
            'tgltransaksi1' => $request['tgltransaksi1'],
            'nomorbukti1' => $request['nomorbukti1'],
            'tglawalx1' => $request['tglawalx1'],
            'tglakhirx1' => $request['tglakhirx1'],
        ]);
        
    }
    public function cariid(Request $request)
    {
        $cari = $request['cari1'];

        $tampil = Nasabah::where('norek','=',$cari)
                ->get();
        foreach ($tampil as $item) {
            $idnasabah = $item->id;
        }
        $tsetorx = Tsetor::select('jml')
            ->where('idnasabah','=',$idnasabah)
            ->whereNotNull('nomorposting')
            ->sum('jml');
        $ttarikx = Ttarik::select('jml')
            ->where('idnasabah','=',$idnasabah)
            ->whereNotNull('nomorposting')
            ->sum('jml');                
        $tfkeluarx = Tfkeluar::select('jml')
            ->where('idnasabah','=',$idnasabah)
            ->whereNotNull('nomorposting')
            ->sum('jml');
        $tfmasukx = Tfmasuk::select('jml')
            ->where('idnasabah','=',$idnasabah)
            ->whereNotNull('nomorposting')
            ->sum('jml');
        $saldo = $tsetorx + $tfmasukx - $ttarikx - $tfkeluarx;
               
        $data = Nasabah::limit(1)->select('*')
                ->with(['produktabungan'])
                ->selectRaw('('. $saldo .') as saldox')
                ->where('norek','=',$cari)
                ->get();
         
        return json_encode(array('data' => $data));

    }

}
