<?php

namespace Modules\Tabungan01\Http\Controllers\laporan;

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
use Modules\Akuntansi01\Models\Jurnal;
use Modules\Akuntansi01\Models\Kategori;
use Modules\Akuntansi01\Models\Produktabungan;
use Modules\Tabungan01\Models\Nasabah;
use Modules\Tabungan01\Models\Sandi;
use Modules\Tabungan01\Models\Tfkeluar;
use Modules\Tabungan01\Models\Tfmasuk;
use Modules\Tabungan01\Models\Tsetor;
use Modules\Tabungan01\Models\Ttarik;
use Yajra\DataTables\Facades\DataTables;

class RekeningkoranController extends Controller
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

        $ecardx1 = session('ecardx1');
        $namax1 = session('namax1');
        $tglawalx1 = session('tglawalx1');
        $tglakhirx1 = session('tglakhirx1');

        $meminstansi = session('memnamasingkat');        
        $remark = 'Halaman ini digunakan untuk menampilkan dan cetak <b>Rekening Koran</b>.';        
        $page = 'tabungan01::laporan.rekeningkoran';
        $link = '/tabungan01/laporan/rekeningkoran';
        $subtitle = 'Laporan';
        $caption = 'Rekening Koran';
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
            'tabel' => Jurnal::SimplePaginate($jmlhal)->withQueryString(),
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
                'apl' => 2,
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
                'keterangan' => $keterangan1,                
            ];

            $datakredit = [
                'rn' => 1,
                'idkelompok' => $idkelompokk1,
                'apl' => 1,
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

        $tglawalx1 = session('tglawalx1');
        if($tglawalx1==''){
            $tglawalx1='1900-01-01'; 
        }
        $tglakhirx1 = session('tglakhirx1');
        if($tglakhirx1==''){
            $tglakhirx1='1900-01-01'; 
        }

        $datax = DataTables::of(
            Jurnal::select('*')
                ->orderBy('id','asc')
                ->where('idnasabah','=', $idnasabahx1)
                ->where('tglposting','>=',$tglawalx1)
                ->where('tglposting','<=',$tglakhirx1)
                ->where('apl','=', 2)
            );

        $data = $datax
        
        ->addIndexColumn()
                
        ->addColumn('debet', function ($row) {
            $debetx = $row->debet;
            if($debetx==0){
                return '';
            }else{
                return number_format($debetx,0);
            }
        })
        ->addColumn('kredit', function ($row) {
            $kreditx = $row->kredit;
            if($kreditx==0){
                return '';
            }else{
                return number_format($kreditx,0);
            }
        })

        ->addColumn('saldo', function ($row) {
            $debetx = Jurnal::select('debet')
                ->where('idnasabah','=', $row->idnasabah)
                ->where('apl','=', 2)
                ->where('id','<=', $row->id)
                ->sum('debet');
            $kreditx = Jurnal::select('kredit')
                ->where('idnasabah','=', $row->idnasabah)
                ->where('apl','=', 2)
                ->where('id','<=', $row->id)
                ->sum('kredit');
            $saldox = $kreditx - $debetx;            
            return number_format($saldox,0);            
        })

        ->addColumn('keterangan', function ($row) {
            $sandi = substr($row->nomorstatus,0,3);
            $tampil = Sandi::select('komen')
                ->where('singkatan','=',$sandi)                
                ->get();
            foreach ($tampil as $baris) {
                $komen = $baris->komen;
            }
            
            $idnasabaht = $row->idnasabaht;
            if($idnasabaht==0){
                $subkomen = '';
            }else{
                $tampil = Nasabah::select('nama')
                    ->where('id','=',$row->idnasabaht)                
                    ->get();
                foreach ($tampil as $baris) {
                    $subkomen = $baris->nama;
                }
            }
                        
            return $komen . ' ' . $subkomen;
            
        })
        
        ->addColumn('action', function ($row) {
            $judulx = 'Start Print Detail ' . $row->nomorstatus;
            return '<a href="#" title="'. $judulx . '" class="btn btn-info btn-xs item_printdetail" data="' . $row->id . '" data2="'. $row->nomorstatus.'" data3="'. $row->namalengkap.'" data4="'. $row->nisn.'" data5="'. $row->norek.'"><i style="font-size:18px" class="fa">&#xf02f;</i></a>';
        })
        
        ->rawColumns([
            'debet',
            'kredit',
            'saldo',
            'keterangan',
            'action'
            ])


        ->make(true);

        return $data;

    }

    public function shownasabah()
    {
        
        $datanasabahx = DataTables::of(
            Nasabah::with(['produktabungan'])                
                ->orderBy('nama','asc')
            );

        $datanasabah = $datanasabahx
            ->addIndexColumn()
            
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
                 
                return '<a href="#" style="color: white;" title="'. ($row->ecard ? $row->ecard : '-') .'" class="item_ecard " data1="' . $row->id . '" data2="'. $row->nis. '" data3="'. $row->nisn. '" data4="'. $row->namalengkap.'" data5="'. $row->ecard.'" data6="'. $row->kelas.'"  data7="'. $row->idproduk.'" data8="'. $row->produk->produk.'" data9="'. $saldo.'">'.($row->ecard ? $row->ecard : '-').'</a> ';
            })
            ->addColumn('nis', function ($row) {
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

                return '<a href="#" style="color: white;" title="'. ($row->nis ? $row->nis : '-') .'" class="item_nis " data1="' . $row->id . '" data2="'. $row->nis. '" data3="'. $row->nisn. '" data4="'. $row->namalengkap.'" data5="'. $row->ecard.'" data6="'. $row->kelas.'"  data7="'. $row->idproduk.'" data8="'. $row->produk->produk.'" data9="'. $saldo.'">'.($row->nis ? $row->nis : '-').'</a> ';
            })
            ->addColumn('nisn', function ($row) {
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

                return '<a href="#" style="color: white;" title="'. ($row->nisn ? $row->nisn : '-') .'" class="item_nisn " data1="' . $row->id . '" data2="'. $row->nis. '" data3="'. $row->nisn. '" data4="'. $row->namalengkap.'" data5="'. $row->ecard.'" data6="'. $row->kelas.'"  data7="'. $row->idproduk.'" data8="'. $row->produk->produk.'" data9="'. $saldo.'">'.($row->nisn ? $row->nisn : '-').'</a> ';
            })
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

                return '<a href="#" style="color: white;" title="'. ($row->namalengkap ? $row->namalengkap : '-') .'" class="item_nama " data1="' . $row->id . '" data2="'. $row->nis. '" data3="'. $row->nisn. '" data4="'. $row->namalengkap.'" data5="'. $row->ecard.'" data6="'. $row->kelas.'"  data7="'. $row->idproduk.'" data8="'. $row->produk->produk.'" data9="'. $saldo.'">'.($row->namalengkap ? $row->namalengkap : '-').'</a> ';
            })
            ->addColumn('kelas', function ($row) {
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

                return '<a href="#" style="color: white;" title="'. ($row->kelas ? $row->kelas : '-') .'" class="item_kelas " data1="' . $row->id . '" data2="'. $row->nis. '" data3="'. $row->nisn. '" data4="'. $row->namalengkap.'" data5="'. $row->ecard.'" data6="'. $row->kelas.'"  data7="'. $row->idproduk.'" data8="'. $row->produk->produk.'" data9="'. $saldo.'">'.($row->kelas ? $row->kelas : '-').'</a> ';
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
                
                return '<a href="#" style="color: white;" title="'. number_format($saldo,0) .'" class="item_saldo " data1="' . $row->id . '" data2="'. $row->nis. '" data3="'. $row->nisn. '" data4="'. $row->namalengkap.'" data5="'. $row->ecard.'" data6="'. $row->kelas.'"  data7="'. $row->idproduk.'" data8="'. $row->produk->produk.'" data9="'. $saldo.'">'. number_format($saldo,0) .'</a> ';
            })

            ->rawColumns([
                'ecard','nis','nisn','nama','kelas', 'saldo'
            ])

            ->make(true);

            return $datanasabah;

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

        $data = ModelsKategori::limit(1)
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
            'id6x' => $request['id6x'],
            'noprintx6' => $request['noprintx6'],
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

    public function printdetail()
    {
        $idnasabahx1 = session('idnasabahx1');
        if($idnasabahx1==''){
            $idnasabahx1=0; 
        }

        $tglawalx1 = session('tglawalx1');
        if($tglawalx1==''){
            $tglawalx1='1900-01-01'; 
        }
        $tglakhirx1 = session('tglakhirx1');
        if($tglakhirx1==''){
            $tglakhirx1='1900-01-01'; 
        }

        $id6x = session('id6x');
        if($id6x==''){
            $id6x='0'; 
        }

        $tampil = Jurnal::select('*')
                ->orderBy('id','asc')
                ->where('idnasabah','=', $idnasabahx1)
                ->where('tglposting','>=',$tglawalx1)
                ->where('tglposting','<=',$tglakhirx1)
                ->where('apl','=', 2)
                ->where('id','>=', $id6x)
                ->get();

            
        $page = 'tabungan01::laporan.rekeningkoran_printdetail';
        $link = '/tabungan01/laporan/rekeningkoran_printdetail';

        $pdf = PDF::loadView($page, [            
                       
            'tabel' => $tampil,
            'noprintx6' => session('noprintx6'),
            // 'norek' => $norek,
            // 'nis' => $nis,
            // 'nisn' => $nisn,
            // 'kelas' => $kelas,
            // 'alamat' => $alamat,

        ])->setOptions(['defaultFont' => 'sans-serif', 'defaultFont' => 'sans-serif' ])->set_option('isHtml5ParserEnabled', true); 
        $pdf->set_paper(array(0, 0, 419.53, 595.28), 'portrait');
        
        return $pdf->stream('bukutabungan_dalam.pdf');

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
