<?php

namespace Modules\Pinjaman01\Http\Controllers\transaksi;

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
use Modules\Akuntansi01\Models\Coa;
use Modules\Akuntansi01\Models\Jenispinjaman;
use Modules\Akuntansi01\Models\Jurnal;
use Modules\Akuntansi01\Models\Kategori;
use Modules\Pinjaman01\Models\Jurnalpinjaman;
use Modules\Simpanan01\Models\Sandi;
use Yajra\DataTables\Facades\DataTables;

class  JurnalangsuranController extends Controller
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
        $remark = 'Halaman ini digunakan untuk menampilkan, menambah, mengubah dan menghapus <b>Jurnal Angsuran</b>.<br>
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
        $page = 'pinjaman01::transaksi.jurnalangsuran';
        $link = '/pinjaman01/transaksi/jurnalangsuran';
        $subtitle = 'Transaksi';
        $caption = 'Jurnal Angsuran';
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
            'tabel' => Jurnalpinjaman::SimplePaginate($jmlhal)->withQueryString(),
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
        $nb = explode(".", $request['nomorbuktix1']);
        $nomorb1 = $nb[3];

        $validatedData = $request->validate([
            'nomorbuktix1' => 'required',
            'tgltransaksix1' => 'required',
        ]);

        //untuk input tabel yang asli
        $data1 = [
            'tgltransaksi' => $validatedData['tgltransaksix1'],           
            'nomorbukti' => $validatedData['nomorbuktix1'],
            'idjenispinjaman' => $request['idjenispinjamanx1'],
            'idanggota' => $request['idanggotax1'],
            'kode' => $request['kodepinjamanx1'],
            'nomorb' => $nomorb1,
            'created_at' => $created_at1,
            'updated_at' => $updated_at1,
            'idsandi' => $request['idsandi1'],
            'idcoa' => $request['idcoa1'],
            'idtarget' => $request['idtargetx1'],
            'idjenisjurnal' => $request['idjenisjurnal1'],
            'debet' => $request['debet1'],
            'kredit' => $request['kredit1'],
            'tipe' => $request['tipe1'],
            'ke' => $request['ke1'],
            'xangsuran' => $request['xangsuran1'],
            'nilaiangsuran' => $request['nilaiangsuran1'],
            'ujroh' => $request['ujroh1'],
            'jatuhtempo' => $request['jatuhtempo1'],
            'keterangan' => $request['keterangan1'],
        ];

        $data2 = [
            'tgltransaksi' => $validatedData['tgltransaksix1'],           
            'nomorbukti' => $validatedData['nomorbuktix1'],
            'idjenispinjaman' => $request['idjenispinjamanx1'],
            'idanggota' => $request['idanggotax1'],
            'kode' => $request['kodepinjamanx1'],
            'nomorb' => $nomorb1,
            'updated_at' => $updated_at1,
            'idsandi' => $request['idsandi1'],
            'idcoa' => $request['idcoa1'],
            'idtarget' => $request['idtargetx1'],
            'idjenisjurnal' => $request['idjenisjurnal1'],
            'debet' => $request['debet1'],
            'kredit' => $request['kredit1'],
            'tipe' => $request['tipe1'],
            'ke' => $request['ke1'],
            'xangsuran' => $request['xangsuran1'],
            'nilaiangsuran' => $request['nilaiangsuran1'],
            'ujroh' => $request['ujroh1'],
            'jatuhtempo' => $request['jatuhtempo1'],
            'keterangan' => $request['keterangan1'],
        ];

        if ($id == '0') {
            Jurnalpinjaman::create($data1);
        } else {
            Jurnalpinjaman::where('id', '=', $id)->update($data2);
        }
        return json_encode('data');
    }

    public function posting(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $create_at1 = date('Y-m-d  H:i:s');         

        $tglposting1 = $request['tglposting1'];
        $tgltransaksi1 = $request['tgltransaksi1'];
        $nomorbukti1 = $request['nomorbukti1'];
        $nomorposting1 = $request['nomorposting1']; 
        $idjenispinjaman1 = $request['idjenispinjaman1']; 
        $kode1 = $request['kode1']; 

        //ambil nomorp1
        $np = explode(".", $request['nomorposting1']);
        $nomorp1 = intval($np[3]);
        
        /*
        rn : 1=akun riil(aset[1], kewajiban[2], modal[3]), 2=akun nominal (hpp[5], beban[6], pendapatan[4,8], SHU[9])
        apl : 1=aktiva(aset[1], hpp[5]), 2=pasiva(kewajiban[2], modal[3] dan SHU[9]) 3=laba rugi(pendapatan[4,8], beban[6]), SHU[9]
        nl : 1=saldo awal, 2=perubahan, 3-penyesuaian, 4-setelah penyesuian, 5-laba rugi, 6-saldo akhir
        */

        

        //ambil data jurnalpinjaman
        $tampil = Jurnalpinjaman::with(['anggota'])
            ->where('tgltransaksi','=',$tgltransaksi1)
            ->where('nomorbukti','=',$nomorbukti1)
            ->get();
        foreach ($tampil as $baris) {
            $id1 = $baris->id;
            $idcoa1 = $baris->idcoa; 
            $idsandi1 = $baris->idsandi;
            $idjenisjurnal1 = $baris->idjenisjurnal;
            $idsumber1 = $baris->idanggota;
            $idtarget1 = $baris->idtarget;            
            $debet1  = $baris->debet;
            $kredit1 = $baris->kredit;
            $keterangan1 = $baris->keterangan;
            $nama1 = $baris->anggota->nama;

            //cek coa
            $tampil2 = Coa::where('id','=',$idcoa1)->get();
            foreach ($tampil2 as $baris) {
                $idkategori1 = $baris->idkategori;
                $idkelompok1 = $baris->kodekelompok;
                $nl1 = 2;
                if(($idkelompok1=='1')||($idkelompok1=='2')||($idkelompok1=='3')){
                    $rn1 = 1;
                }else{
                    $rn1 = 2;
                }

                if(($idkelompok1=='1')||($idkelompok1=='5')){
                    $apl1 = 1;
                }else if(($idkelompok1=='2')||($idkelompok1=='3')||($idkelompok1=='9')){
                    $apl1 = 2;
                }else{
                    $apl1 = 2;
                }
            }

            //simpan data
            $data = [
                'rn' => $rn1,
                'idkelompok' => $idkelompok1,
                'apl' => $apl1,
                'idkategori' => $idkategori1,
                'nl' => $nl1,
                'idcoa' => $idcoa1,
                'idjenisjurnal' => $idjenisjurnal1,
                'idnasabah' => $idsumber1,
                'idnasabaht' => $idtarget1,
                'nama' => $nama1,
                'idsandi' => $idsandi1,
                'tglstatus' => $tgltransaksi1,
                'nomorstatus' => $nomorbukti1,                
                'idstatus' => $id1,
                'create_at' => $create_at1,
                'tglposting' => $tglposting1,
                'nomorp' => $nomorp1,
                'nomorposting' => $nomorposting1,
                'debet' => $debet1,
                'kredit' => $kredit1,
                'pengesahan' => substr(auth()->user()->name, 0, 6),
                'idusers' => auth()->user()->id,
                'email' => auth()->user()->email,
                'keterangan' => $keterangan1,                
            ];

            Jurnal::create($data);
        }
        
        //untuk update jurnalpinjaman
        $data2 = [
            'tglposting' => $request['tglposting1'],
            'nomorposting' => $request['nomorposting1'],                        
                                   
        ];
                 
            Jurnalpinjaman::where('tgltransaksi','=', $tgltransaksi1)
            ->where('nomorbukti','=', $nomorbukti1)             
            ->update($data2);
        
        //ambil idcoa dan update Lunas
        $tmp = Jenispinjaman::where('id','=',$idjenispinjaman1)->get();
        foreach ($tmp as $baris) {
            $idcoa01d = $baris->idcoa01d;
        }

        $nilaipinjam = Jurnalpinjaman::select('debet')
            ->where('kode','=',$kode1)
            ->where('idcoa','=',$idcoa01d)
            ->whereRaw('left(nomorbukti,3) = ? ', ['PJM'])
            ->sum('debet');
        $nilaibayar = Jurnalpinjaman::select('kredit')
            ->where('kode','=',$kode1)
            ->where('idcoa','=',$idcoa01d)
            ->whereRaw('left(nomorbukti,3) = ? ', ['ANG'])
            ->whereNotNull('tglposting')
            ->sum('kredit');
        $cekdata = [
            'lb' => 'Lunas',
        ];

        if($nilaibayar>=$nilaipinjam){
            Jurnalpinjaman::where('kode','=', $kode1)
            ->update($cekdata);
        }
        
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
        $nomorbuktix1 = session('nomorbuktix1');
        $tgltransaksix1 = session('tgltransaksix1');

        $datax = DataTables::of(
                Jurnalpinjaman::with(['sandi','coa','anggota','jenispinjaman','jenisjurnal'])
                ->orderBy('id','asc')
                ->where('nomorbukti', '=',$nomorbuktix1)
                ->where('tgltransaksi', '=',$tgltransaksix1)
            );

        $data = $datax
        
        ->addIndexColumn()
        
        ->addColumn('debet', function ($row) {
            return $row->debet ? number_format($row->debet,0) : '0';
        })
        ->addColumn('kredit', function ($row) {
            return $row->kredit ? number_format($row->kredit,0) : '0';
        })
        
        ->addColumn('coa', function ($row) {
            return $row->idcoa ? $row->coa->kode . '-' . $row->coa->coa : '';
        })
        ->addColumn('sandi', function ($row) {
            // return $row->idsandi ? $row->sandi->kode . '-' . $row->sandi->sandi : '';
            return $row->sandi->kode . '-' . $row->sandi->sandi;
        })
        
        ->addColumn('action', function ($row) {
            $tglposting = $row->tglposting;
            if(is_null($tglposting)){
                
                return '<a href="#" title="Edit Data" class="btn btn-success btn-xs item_edit" 
                    data="' . $row->id . '" 
                    data2="'. $row->kode .'" 
                    data3="'. $row->idcoa .'" 
                    data4="'. $row->idsandi .'" 
                    data5="'. $row->idjenisjurnal .'" 
                    data6="'. $row->idjenispinjaman .'" 
                    data7="'. $row->idanggota .'" 
                    data8="'. $row->idtarget .'"
                    data9="'. $row->idanggota .'"
                    data10="'. $row->idanggota .'"
                    data11="'. $row->tgltransaksi .'"
                    data12="'. $row->nomorbukti .'"
                    data13="'. $row->debet .'"
                    data14="'. $row->kredit .'"
                    data15="'. $row->tipe .'"
                    data16="'. $row->xangsuran .'"
                    data17="'. $row->nilaiangsuran .'"
                    data18="'. $row->ke .'"
                    data19="'. $row->ujroh .'"
                    data20="'. $row->jatuhtempo .'"
                    data21="'. $row->keterangan .'"
                    ><i style="font-size:18px" class="fa">&#xf044;</i></a> ' .
                // '<a href="#" title="Posting Data" class="btn btn-warning btn-xs item_posting" data="' . $row->id . '" data2="'. $row->idcoa.'" data3="'. $row->tgltransaksi.'" data4="'. $row->updated_at.'" data5="'. $row->nomorbukti.'" data6="'. $row->debet.'" data7="'. $row->tglposting.'" data8="'. $row->kredit.'"><i style="font-size:18px" class="fa">&#xf093;</i></a> ' .
                '<a href="#" title="Hapus Data" class="btn btn-danger btn-xs item_hapus" data="' . $row->id . '" data2="'. $row->coa->kode .'-' . $row->coa->coa.'" data3="'. $row->tgltransaksi.'" data4="'. $row->updated_at.'" data5="'. $row->nomorbukti.'" data6="'. $row->debet.'" data7="'. $row->kredit.'"><i style="font-size:18px" class="fa">&#xf00d;</i></a>';
            }else{
                return '<a href="#" title="Print Data" class="btn btn-info btn-xs item_print" data="' . $row->id . '" data2="'. $row->coa->kode .'-' . $row->coa->coa.'" data3="'. $row->tgltransaksi.'" data4="'. $row->updated_at.'" data5="'. $row->nomorbukti.'" data6="'. $row->debet.'" data7="'. $row->tglposting.'" data8="'. $row->kredit.'"><i style="font-size:18px" class="fa">&#xf02f;</i></a> ';
            }
        })
        
        ->rawColumns([
            'coa',
            'sandi',
            'debet',
            'kredit',
            'action'
            ])


        ->make(true);

        return $data;

    }

    public function showanggota()
    {
        
        $idjenispinjaman1 = session('idjenispinjamanx1');
        $idcoa01d =Jenispinjaman::select('idcoa01d')
            ->where('id','=',$idjenispinjaman1)
            ->get();

        $datax = DataTables::of(
            Jurnalpinjaman::with(['anggota','coa','jenispinjaman','jenisjurnal','sandi'])
                ->where('lb','=','Belum')
                ->whereIn('idcoa',$idcoa01d)
                ->whereRaw('left(nomorbukti,3) = ? ', ['PJM'])
                ->orderByRaw('(select nama from anggota where id=jurnalpinjaman.idanggota) asc')
            );

        $data = $datax
            ->addIndexColumn()

            ->addColumn('kode', function ($row) {
                $idjenispinjaman1 = session('idjenispinjamanx1');
                $idcoa01d =Jenispinjaman::select('idcoa01d')
                    ->where('id','=',$idjenispinjaman1)
                    ->get();
                $ke=Jurnalpinjaman::whereIn('idcoa',$idcoa01d)
                    ->where('idtarget','=',$row->id)
                    ->whereRaw('left(nomorbukti,3) = ? ', ['ANG'])
                    ->count();
                return '<a href="#" style="color: white;" title="'. ($row->kode ? $row->kode : '-') .'" class="item_kode" 
                    data1="' . $row->id . '" 
                    data2="' . $row->idanggota . '" 
                    data3="'. $row->anggota->nama . '" 
                    data4="'. $row->anggota->nia . '" 
                    data5="'. $row->kode . '" 
                    data6="'. $row->tgltransaksi . '" 
                    data7="'. $row->tipe . '" 
                    data8="'. $ke . '" 
                    data9="'. $row->xangsuran . '" 
                    data10="'. $row->nilaiangsuran . '" 
                    data11="'. $row->ujroh . '" 
                    data12="'. $row->idtarget . '"
                    >'.($row->kode ? $row->kode : '-').'</a> ';
            })
            ->addColumn('nama', function ($row) {
                $idjenispinjaman1 = session('idjenispinjamanx1');
                $idcoa01d =Jenispinjaman::select('idcoa01d')
                    ->where('id','=',$idjenispinjaman1)
                    ->get();
                $ke=Jurnalpinjaman::whereIn('idcoa',$idcoa01d)
                    ->where('idtarget','=',$row->id)
                    ->whereRaw('left(nomorbukti,3) = ? ', ['ANG'])
                    ->count();
                return '<a href="#" style="color: white;" title="'. ($row->idanggota ? $row->anggota->nama : '-') .'" class="item_nama" 
                    data1="' . $row->id . '" 
                    data2="' . $row->idanggota . '" 
                    data3="'. $row->anggota->nama . '" 
                    data4="'. $row->anggota->nia . '" 
                    data5="'. $row->kode . '" 
                    data6="'. $row->tgltransaksi . '" 
                    data7="'. $row->tipe . '" 
                    data8="'. $ke . '" 
                    data9="'. $row->xangsuran . '" 
                    data10="'. $row->nilaiangsuran . '" 
                    data11="'. $row->ujroh . '" 
                    data12="'. $row->idtarget . '"
                    >'.($row->idanggota ? $row->anggota->nama : '-').'</a> ';
            })
            ->addColumn('nia', function ($row) {
                $idjenispinjaman1 = session('idjenispinjamanx1');
                $idcoa01d =Jenispinjaman::select('idcoa01d')
                    ->where('id','=',$idjenispinjaman1)
                    ->get();
                $ke=Jurnalpinjaman::whereIn('idcoa',$idcoa01d)
                    ->where('idtarget','=',$row->id)
                    ->whereRaw('left(nomorbukti,3) = ? ', ['ANG'])
                    ->count();
                return '<a href="#" style="color: white;" title="'. ($row->idanggota ? $row->anggota->nia : '-') .'" class="item_nia" 
                    data1="' . $row->id . '" 
                    data2="' . $row->idanggota . '" 
                    data3="'. $row->anggota->nama . '" 
                    data4="'. $row->anggota->nia . '" 
                    data5="'. $row->kode . '" 
                    data6="'. $row->tgltransaksi . '" 
                    data7="'. $row->tipe . '" 
                    data8="'. $ke . '" 
                    data9="'. $row->xangsuran . '" 
                    data10="'. $row->nilaiangsuran . '" 
                    data11="'. $row->ujroh . '" 
                    data12="'. $row->idtarget . '"
                    >'.($row->idanggota ? $row->anggota->nia : '-').'</a> ';
            })
           
            ->addColumn('jenispinjaman', function ($row) {
                return $row->idjenispinjaman ? $row->jenispinjaman->jenispinjaman : '-';
            })
            ->addColumn('debet', function ($row) {
                return number_format($row->debet,'0');
            })
            ->addColumn('kredit', function ($row) {
                $idjenispinjaman1 = session('idjenispinjamanx1');
                $idcoa01d =Jenispinjaman::select('idcoa01d')
                    ->where('id','=',$idjenispinjaman1)
                    ->get();
                $kredit=Jurnalpinjaman::select('kredit')
                    ->whereIn('idcoa',$idcoa01d)
                    ->where('idtarget','=',$row->id)
                    ->whereRaw('left(nomorbukti,3) = ? ', ['ANG'])
                    ->whereNotNull('tglposting')
                    ->sum('kredit');

                return number_format($kredit,'0');
            })
            ->addColumn('saldo', function ($row) {
                $idjenispinjaman1 = session('idjenispinjamanx1');
                $idcoa01d =Jenispinjaman::select('idcoa01d')
                    ->where('id','=',$idjenispinjaman1)
                    ->get();
                $kredit=Jurnalpinjaman::select('kredit')
                    ->whereIn('idcoa',$idcoa01d)
                    ->where('idtarget','=',$row->id)
                    ->whereNotNull('tglposting')
                    ->whereRaw('left(nomorbukti,3) = ? ', ['ANG'])
                    ->sum('kredit');

                return number_format($row->debet - $kredit,'0');
            })
            ->addColumn('xangsuran', function ($row) {
                $idjenispinjaman1 = session('idjenispinjamanx1');
                $idcoa01d =Jenispinjaman::select('idcoa01d')
                    ->where('id','=',$idjenispinjaman1)
                    ->get();
                $ke=Jurnalpinjaman::whereIn('idcoa',$idcoa01d)
                    ->where('idtarget','=',$row->id)
                    ->whereRaw('left(nomorbukti,3) = ? ', ['ANG'])
                    ->count();

                return $ke . '/' . $row->xangsuran;
            })
            ->addColumn('nilaiangsuran', function ($row) {
                return number_format($row->nilaiangsuran,'0');
            })
            ->addColumn('ujroh', function ($row) {
                return number_format($row->ujroh,'0');
            })

            ->rawColumns([
                'kode',
                'nama',
                'nia',
                'jenispinjaman',
                'debet',
                'kredit',
                'saldo',
                'xangsuran',
                'nilaiangsuran',
                'ujroh',
                
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
        $data = Jurnalpinjaman::where('id', '=', $id)
            ->with(['sandi','coa','anggota'])
            ->get();
        return json_encode(array('data' => $data)); 
    }
    public function jenispinjaman($id)
    {
        $data = Jenispinjaman::where('id', '=', $id)
            ->with([
                'coa01d','coa01k',
                'coa02d','coa02k',
                'coa03d','coa03k',
                'coa04d','coa04k',
                'coa05d','coa05k',
                'coa06d','coa06k'
                ])
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
        $data = Jurnalpinjaman::where('id', '=', $id)->delete();
        return json_encode(array('data' => $data));
    }

    public function nomorbukti(Request $request)
    {
        
        $tgltransaksi1=$request['tgltransaksix1'];
        $tgl1 = Carbon::parse($tgltransaksi1)->format('Ymd');
        $ym1 = Carbon::parse($tgltransaksi1)->format('Ym');
        $d1 = Carbon::parse($tgltransaksi1)->format('d');
        $m1 = Carbon::parse($tgltransaksi1)->format('m');
        $y1 = Carbon::parse($tgltransaksi1)->format('Y');
        
        $jmldata = Jurnalpinjaman::where('tgltransaksi','=',$tgltransaksi1)
            ->whereRaw('left(nomorbukti,3) = ? ', ['ANG'])            
            ->count();
        if ($jmldata==0){
            $nomor = 1;    
        }else{
            $nomor = Jurnalpinjaman::select('nomorb')
                ->where('tgltransaksi','=',$tgltransaksi1)
                ->whereRaw('left(nomorbukti,3) = ? ', ['ANG'])
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

        //nomor kwitansi contoh : ANG.001.20230527.0009
        $nbx = 'ANG' . '.' . $idx . '.' . $tgl1 . '.' . $no;  
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
            'tgltransaksix1' => $request['tgltransaksix1'],
            'nomorbuktix1' => $request['nomorbuktix1'],
            'idjenispinjamanx1' => $request['idjenispinjamanx1'],
            'idanggotax1' => $request['idanggotax1'],
            'niax1' => $request['niax1'],
            'namax1' => $request['namax1'],
            'kodepinjamanx1' => $request['kodepinjamanx1'],
        ]);
        
    }
    public function cariid(Request $request)
    {
        $cari = $request['cari1'];

        $tampil = Anggota::where('nia','=',$cari)
                ->where('aktif','=','Y')
                ->get();
        foreach ($tampil as $item) {
            $idanggota = $item->id;
        }
               
        $data = Anggota::limit(1)->select('*')
                ->where('nia','=',$cari)
                ->where('aktif','=','Y')
                ->get();
         
        return json_encode(array('data' => $data));

    }

    function listcoa()
    {
        // asc
        $idjenispinjamanx1 = session('idjenispinjamanx1');
        
        $idcoa01d = Jenispinjaman::select('idcoa01d')->where('id','=',$idjenispinjamanx1)->get();
        $idcoa02d = Jenispinjaman::select('idcoa02d')->where('id','=',$idjenispinjamanx1)->get();
        $idcoa03d = Jenispinjaman::select('idcoa03d')->where('id','=',$idjenispinjamanx1)->get();
        $idcoa04d = Jenispinjaman::select('idcoa04d')->where('id','=',$idjenispinjamanx1)->get();
        $idcoa05d = Jenispinjaman::select('idcoa05d')->where('id','=',$idjenispinjamanx1)->get();
        $idcoa06d = Jenispinjaman::select('idcoa06d')->where('id','=',$idjenispinjamanx1)->get();
        $idcoa01k = Jenispinjaman::select('idcoa01k')->where('id','=',$idjenispinjamanx1)->get();
        $idcoa02k = Jenispinjaman::select('idcoa02k')->where('id','=',$idjenispinjamanx1)->get();
        $idcoa03k = Jenispinjaman::select('idcoa03k')->where('id','=',$idjenispinjamanx1)->get();
        $idcoa04k = Jenispinjaman::select('idcoa04k')->where('id','=',$idjenispinjamanx1)->get();
        $idcoa05k = Jenispinjaman::select('idcoa05k')->where('id','=',$idjenispinjamanx1)->get();
        $idcoa06k = Jenispinjaman::select('idcoa06k')->where('id','=',$idjenispinjamanx1)->get();
        $tampil = Coa::whereIn('id',$idcoa01d)
            ->orWhereIn('id',$idcoa02d)
            ->orWhereIn('id',$idcoa03d)
            ->orWhereIn('id',$idcoa04d)
            ->orWhereIn('id',$idcoa05d)
            ->orWhereIn('id',$idcoa06d)
            ->orWhereIn('id',$idcoa06d)
            ->orWhereIn('id',$idcoa01k)
            ->orWhereIn('id',$idcoa02k)
            ->orWhereIn('id',$idcoa03k)
            ->orWhereIn('id',$idcoa04k)
            ->orWhereIn('id',$idcoa05k)
            ->orWhereIn('id',$idcoa06k)
            ->orderBy('kode', 'asc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kode . ' - ' . $baris->coa . "</option>";
        }
    }

    function listsandi()
    {
        // asc
        
        $tampil = Sandi::where('id', '>',5)
            ->orderBy('kode', 'asc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kode . ' - ' . $baris->sandi . "</option>";
        }
    }

    public function printkwitansi()
    {
        
        $nomorbuktix1 = session('nomorbuktix1');
        $tgltransaksix1 = session('tgltransaksix1');
        $jml1 = Jurnalpinjaman::where('nomorbukti','=',$nomorbuktix1)
            ->where('tgltransaksi','=',$tgltransaksix1)
            ->sum('debet');
        $tampil = Jurnalpinjaman::limit(1)
            ->with(['anggota'])
            ->where('nomorbukti','=',$nomorbuktix1)
            ->where('tgltransaksi','=',$tgltransaksix1)
            ->get();
        foreach ($tampil as $baris) {
            $nomorbukti1 = $baris->nomorbukti;
            $pemberi1 = $baris->anggota->nama . ' / ' . $baris->anggota->nia;
            $guna1 = $baris->keterangan;
            $guna2 = 'Nomor : ' . $baris->kode;
            $tgltransaksi1 = $baris->tgltransaksi;
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
        
        return $pdf->stream('kwitansi-jurnalumum.pdf');

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
