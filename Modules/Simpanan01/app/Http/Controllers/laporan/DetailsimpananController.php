<?php

namespace Modules\Simpanan01\Http\Controllers\laporan;

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
use Modules\Akuntansi01\Models\Jenissimpanan;
use Modules\Akuntansi01\Models\Jurnal;
use Modules\Akuntansi01\Models\Kategori;
use Modules\Simpanan01\Models\Jurnalsimpanan;
use Modules\Simpanan01\Models\Nasabah;

use Yajra\DataTables\Facades\DataTables;

class DetailsimpananController extends Controller
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
        $remark = 'Halaman ini digunakan untuk menampilkan <b>Detail Simpanan</b>';
        $page = 'simpanan01::laporan.detailsimpanan';
        $link = '/simpanan01/laporan/detailsimpanan';
        $subtitle = 'Laporan';
        $caption = 'Detail Simpanan';
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
            'tabel' => Jurnalsimpanan::SimplePaginate($jmlhal)->withQueryString(),
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
            'idnasabah1' => 'required',
            'nomorbukti1' => 'required',
            'tgltransaksi1' => 'required',
        ]);

        //ambil idjenissimpanan dan idanggota
        $tampil = Nasabah::with(['jenissimpanan'])
            ->where('id','=',$validatedData['idnasabah1'])
            ->get();
        foreach ($tampil as $baris) {
            $idjenissimpanan1 = $baris->idjenissimpanan;
            $idcoasetord1 = $baris->jenissimpanan->idcoasetord;
        }

        //tabel jenisimpanan
        $tampil3 = Jenissimpanan::select('*')
        ->with(['coasetord','coasetork','coatarikd','coatarikk','coatfmasukd','coatfmasukk',
            'coatfkeluard','coatfkeluark','jenisjurnalsetord','jenisjurnalsetork',
            'jenisjurnaltarikd','jenisjurnaltarikk','jenisjurnaltfmasukd','jenisjurnaltfmasukk',
            'jenisjurnaltfkeluard','jenisjurnaltfkeluark'])
        ->where('id','=',$idjenissimpanan1)
        ->get();
        foreach ($tampil3 as $baris) {
            $idcoad1 = $baris->idcoatarikd;
            $idcoak1 = $baris->idcoatarikk;
            
            $idkelompokd1 = $baris->coatarikd->kodekelompok;
            $idkelompokk1 = $baris->coatarikk->kodekelompok;
            
            $idkategorid1 = $baris->coatarikd->idkategori;
            $idkategorik1 = $baris->coatarikk->idkategori;
            
            $idjenisjurnald1 = $baris->jenisjurnaltarikd->id;
            $idjenisjurnalk1 = $baris->jenisjurnaltarikk->id;

        }
        
        //untuk input tabel yang asli
        $data1 = [
            'tgltransaksi' => $validatedData['tgltransaksi1'],           
            'idnasabah' => $validatedData['idnasabah1'],
            'nomorbukti' => $validatedData['nomorbukti1'],
            'nomorb' => $nomorb1,
            'idjenissimpanan' => $idjenissimpanan1,
            'idcoa' => $idcoad1,
            'idtarget' => $validatedData['idnasabah1'],
            'idsandi' => '2',
            'idjenisjurnal' => $idjenisjurnald1,
            'created_at' => $created_at1,
            'updated_at' => $updated_at1,
            'debet' => $request['jml1'],
            'keterangan' => $request['keterangan1'],
        ];

        $data2 = [
            'tgltransaksi' => $validatedData['tgltransaksi1'],           
            'idnasabah' => $validatedData['idnasabah1'],
            'nomorbukti' => $validatedData['nomorbukti1'],
            'nomorb' => $nomorb1,  
            'idjenissimpanan' => $idjenissimpanan1,
            'idcoa' => $idcoad1,
            'idtarget' => $validatedData['idnasabah1'], 
            'idsandi' => '2',
            'idjenisjurnal' => $idjenisjurnald1,        
            'updated_at' => $updated_at1,
            'debet' => $request['jml1'],
            'keterangan' => $request['keterangan1'],
        ];

        if ($id == '0') {
            Jurnalsimpanan::create($data1);
        } else {
            Jurnalsimpanan::where('id', '=', $id)->update($data2);
        }
        return json_encode('data');
    }

    public function posting(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $created_at1 = date('Y-m-d  H:i:s');         

        $id1 = $request['id1'];        
        $tglposting1 = $request['tglposting1'];
        $nomorposting1 = $request['nomorposting1'];  
        
        //ambil nomorp1
        $np = explode(".", $request['nomorposting1']);
        $nomorp1 = intval($np[3]);

        //tabel jurnalsimpanan
        $tampil = Jurnalsimpanan::with(['nasabah'])
        ->where('id','=',$id1)
        ->get();
        foreach ($tampil as $baris) {
            $idcoa1 = $baris->idcoa;
            $idsandi1 = $baris->idsandi;
            $idjenisjurnal1 = $baris->idjenisjurnal;
            $idjenissimpanan1 = $baris->idjenissimpanan;
            $idnasabah1 = $baris->idnasabah;
            $idtarget1 = $baris->idtarget;
            $tgltransaksi1 = $baris->tgltransaksi;
            $nomorb1 = $baris->nomorb;
            $nomorbukti1 = $baris->nomorbukti;
            $jml1 = $baris->debet;
            $keterangan1 = $baris->keterangan;
            $nama1 = $baris->nasabah->nama;
        }

        //tabel jenisimpanan
        $tampil3 = Jenissimpanan::select('*')
        ->with(['coasetord','coasetork','coatarikd','coatarikk','coatfmasukd','coatfmasukk',
            'coatfkeluard','coatfkeluark','jenisjurnalsetord','jenisjurnalsetork',
            'jenisjurnaltarikd','jenisjurnaltarikk','jenisjurnaltfmasukd','jenisjurnaltfmasukk',
            'jenisjurnaltfkeluard','jenisjurnaltfkeluark'])
        ->where('id','=',$idjenissimpanan1)
        ->get();
        foreach ($tampil3 as $baris) {
            $idcoad1 = $baris->idcoatarikd;
            $idcoak1 = $baris->idcoatarikk;
            
            $idkelompokd1 = $baris->coatarikd->kodekelompok;
            $idkelompokk1 = $baris->coatarikk->kodekelompok;
            
            $idkategorid1 = $baris->coatarikd->idkategori;
            $idkategorik1 = $baris->coatarikk->idkategori;
            
            $idjenisjurnald1 = $baris->jenisjurnaltarikd->id;
            $idjenisjurnalk1 = $baris->jenisjurnaltarikk->id;
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
                'idcoa' => $idcoad1,
                'idjenisjurnal' => $idjenisjurnald1,
                'idnasabah' => $idnasabah1,
                'nama' => $nama1,
                'idsandi' => $idsandi1,
                'tglstatus' => $tgltransaksi1,
                'nomorstatus' => $nomorbukti1,                
                'idstatus' => $id1,
                'create_at' => $created_at1,
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
                'idcoa' => $idcoak1,
                'idjenisjurnal' => $idjenisjurnalk1,
                'idnasabah' => $idnasabah1,
                'nama' => $nama1,
                'idsandi' => $idsandi1,
                'tglstatus' => $tgltransaksi1,
                'nomorstatus' => $nomorbukti1,                
                'idstatus' => $id1,
                'create_at' => $created_at1,
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
        
        // untuk update jurnalsimpanan
        $data = [
            'tglposting' => $request['tglposting1'],
            'nomorposting' => $request['nomorposting1'],                        
        ];
                 
            Jurnalsimpanan::with(['nasabah']) 
            ->where('id','=', $id1)            
            ->update($data);
        
        
        $tampil = Jurnalsimpanan::where('id','=',$id1)
            ->get();

        foreach ($tampil as $baris) {
            $idcoa1 = $baris->idcoa;
            $idsandi1 = $baris->idsandi;
            $idjenisjurnal1 = $baris->idjenisjurnal;
            $idjenissimpanan1 = $baris->idjenissimpanan;
            $idnasabah1 = $baris->idnasabah;
            $idtarget1 = $baris->idtarget;
            $created_at1 = $baris->created_at;
            $updated_at1 = $baris->updated_at;
            $tgltransaksi1 = $baris->tgltransaksi;
            $nomorb1 = $baris->nomorb;
            $nomorbukti1 = $baris->nomorbukti;
            $jml1 = $baris->kredit;
            $tglposting1 = $baris->tglposting;
            $nomorposting1 = $baris->nomorposting;
            $keterangan1 = $baris->keterangan;
            
        }
        //tambah data baru kas  
        $data2 = [
            'tgltransaksi' => $tgltransaksi1,           
            'idnasabah' => $idnasabah1,
            'nomorbukti' => $nomorbukti1,
            'nomorb' => $nomorb1,
            'idjenissimpanan' => $idjenissimpanan1,
            'idcoa' => $idcoak1,
            'idtarget' => $idtarget1,
            'idsandi' => $idsandi1,
            'idjenisjurnal' => $idjenisjurnalk1,
            'created_at' => $created_at1,
            'updated_at' => $updated_at1,
            'tglposting' => $tglposting1,
            'nomorposting' => $nomorposting1,
            'kredit' => $jml1,
            'keterangan' => $keterangan1,
        ];
        Jurnalsimpanan::create($data2);
        
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
        $tglawalx1 = session('tglawalx1');
        if($tglawalx1==''){
            $tglawalx1 = session('memtanggal');
        }
        $tglakhirx1 = session('tglakhirx1');
        if($tglakhirx1==''){
            $tglakhirx1 = session('memtanggal');
        }

        $datax = DataTables::of(
            Jurnalsimpanan::with(['nasabah','sandi'])
                ->where('idcoa','=',session('idcoa1'))
                ->where('idtarget','=', session('idnasabahx1'))
                ->where('tgltransaksi','>=',$tglawalx1)
                ->where('tgltransaksi','<=',$tglakhirx1)
                ->orderBy('id','asc')
            );

        $data = $datax
        
        ->addIndexColumn()
        
        ->addColumn('debet', function ($row) {
            return $row->debet ? number_format($row->debet,0) : '0';
        })
        ->addColumn('kredit', function ($row) {
            return $row->kredit ? number_format($row->kredit,0) : '0';
        })
        ->addColumn('saldo', function ($row) {
            $debet = Jurnalsimpanan::select('debet')
                ->where('idcoa','=',session('idcoa1'))
                ->where('idtarget','=', $row->idtarget)
                ->where('id','<=', $row->id)
                ->sum('debet');
            $kredit = Jurnalsimpanan::select('kredit')
                ->where('idcoa','=',session('idcoa1'))
                ->where('idtarget','=', $row->idtarget)
                ->where('id','<=', $row->id)
                ->sum('kredit');
            return number_format($kredit-$debet,0);
        })
        ->addColumn('norek', function ($row) {
            return $row->idtarget ? $row->target->norek : '';
        })
        ->addColumn('nia', function ($row) {
            return $row->idtarget ? $row->target->nia : '';
        })
        ->addColumn('nama', function ($row) {
            return $row->idtarget ? $row->target->nama : '';
        })
        ->addColumn('jenissimpanan', function ($row) {
            return $row->idjenissimpanan ? $row->jenissimpanan->jenissimpanan : '';
        })
        ->addColumn('sandi', function ($row) {
            return $row->idsandi ? $row->sandi->kode : '';
        })
        
        ->addColumn('action', function ($row) {
            return '<a href="#" title="Print Data" class="btn btn-info btn-xs item_print" 
                    data="' . $row->id . '" 
                    data2="'. $row->idnasabah.'" 
                    data3="'. $row->tgltransaksi.'" 
                    data4="'. $row->updated_at.'" 
                    data5="'. $row->nomorbukti.'" 
                    data6="'. $row->debet.'" 
                    data7="'. $row->tglposting.'"
                ><i style="font-size:18px" class="fa">&#xf02f;</i></a> ';
        })
        
        ->rawColumns([
            'norek',
            'nia',
            'nama',
            'sandi',
            'debet',
            'kredit',
            'saldo',
            'jenissimpanan',
            'action'
            ])


        ->make(true);

        return $data;

    }
    
    public function shownasabah()
    {
        $idnasabah = Jurnalsimpanan::select('idtarget')->get();
        $datax = DataTables::of(
            Nasabah::with(['jenissimpanan','anggota'])
                ->where('aktif','=','Y')
                ->whereIn('id',$idnasabah)                
                ->orderBy('nama','asc')
            );

        $data = $datax
            ->addIndexColumn()

            ->addColumn('nama', function ($row) {
                $tampil = Jenissimpanan::where('id','=',$row->idjenissimpanan)
                    ->get();
                foreach ($tampil as $baris) {
                    $idcoad1 = $baris->idcoatarikd;
                }
                $debet = Jurnalsimpanan::select('debet')
                    ->where('idtarget','=',$row->id)
                    ->where('idcoa','=',$idcoad1)
                    ->sum('debet');    
                $kredit = Jurnalsimpanan::select('kredit')
                    ->where('idtarget','=',$row->id)
                    ->where('idcoa','=',$idcoad1)
                    ->sum('kredit');    
                $saldo = $kredit-$debet;
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
                    data14="'. $row->idjenissimpanan . '"
                    data15="'. $row->jenissimpanan->jenissimpanan . '"
                    data16="'. $saldo . '"
                    data17="'. $row->norek . '"
                    >'.($row->nama ? $row->nama : '-').'</a> ';
            })
            ->addColumn('nia', function ($row) {
                $tampil = Jenissimpanan::where('id','=',$row->idjenissimpanan)
                    ->get();
                foreach ($tampil as $baris) {
                    $idcoad1 = $baris->idcoatarikd;
                }
                $debet = Jurnalsimpanan::select('debet')
                    ->where('idtarget','=',$row->id)
                    ->where('idcoa','=',$idcoad1)
                    ->sum('debet');    
                $kredit = Jurnalsimpanan::select('kredit')
                    ->where('idtarget','=',$row->id)
                    ->where('idcoa','=',$idcoad1)
                    ->sum('kredit');   
                $saldo = $kredit-$debet;
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
                    data14="'. $row->idjenissimpanan . '"
                    data15="'. $row->jenissimpanan->jenissimpanan . '"
                    data16="'. $saldo . '"
                    data17="'. $row->norek . '"
                    >'.($row->nia ? $row->nia : '-').'</a> ';
            })
            ->addColumn('nik', function ($row) {
                $tampil = Jenissimpanan::where('id','=',$row->idjenissimpanan)
                    ->get();
                foreach ($tampil as $baris) {
                    $idcoad1 = $baris->idcoatarikd;
                }
                $debet = Jurnalsimpanan::select('debet')
                    ->where('idtarget','=',$row->id)
                    ->where('idcoa','=',$idcoad1)
                    ->sum('debet');    
                $kredit = Jurnalsimpanan::select('kredit')
                    ->where('idtarget','=',$row->id)
                    ->where('idcoa','=',$idcoad1)
                    ->sum('kredit');   
                $saldo = $kredit-$debet;
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
                    data14="'. $row->idjenissimpanan . '"
                    data15="'. $row->jenissimpanan->jenissimpanan . '"
                    data16="'. $saldo . '"
                    data17="'. $row->norek . '"
                    >'.($row->nik ? $row->nik : '-').'</a> ';
            })
            
            ->addColumn('ecard', function ($row) {
                $tampil = Jenissimpanan::where('id','=',$row->idjenissimpanan)
                    ->get();
                foreach ($tampil as $baris) {
                    $idcoad1 = $baris->idcoatarikd;
                }
                $debet = Jurnalsimpanan::select('debet')
                    ->where('idtarget','=',$row->id)
                    ->where('idcoa','=',$idcoad1)
                    ->sum('debet');    
                $kredit = Jurnalsimpanan::select('kredit')
                    ->where('idtarget','=',$row->id)
                    ->where('idcoa','=',$idcoad1)
                    ->sum('kredit');    
                $saldo = $kredit-$debet;
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
                    data14="'. $row->idjenissimpanan . '"
                    data15="'. $row->jenissimpanan->jenissimpanan . '"
                    data16="'. $saldo . '"
                    data17="'. $row->norek . '"
                    >'.($row->ecard ? $row->ecard : '-').'</a> ';
            })
            
            ->addColumn('saldo', function ($row) {
                $tampil = Jenissimpanan::where('id','=',$row->idjenissimpanan)
                    ->get();
                foreach ($tampil as $baris) {
                    $idcoad1 = $baris->idcoatarikd;
                }
                $debet = Jurnalsimpanan::select('debet')
                    ->where('idtarget','=',$row->id)
                    ->where('idcoa','=',$idcoad1)
                    ->sum('debet');    
                $kredit = Jurnalsimpanan::select('kredit')
                    ->where('idtarget','=',$row->id)
                    ->where('idcoa','=',$idcoad1)
                    ->sum('kredit');    
                $saldo = $kredit-$debet;
                
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
                    data14="'. $row->idjenissimpanan . '"
                    data15="'. $row->jenissimpanan->jenissimpanan . '"
                    data16="'. $saldo . '"
                    data17="'. $row->norek . '"
                    >'. number_format($saldo,0) .'</a> ';
            })

            ->addColumn('jenissimpanan', function ($row) {
                return $row->idjenissimpanan ? $row->jenissimpanan->jenissimpanan : '';
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
                'jenissimpanan',
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
        $data = Jurnalsimpanan::where('id', '=', $id)
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
        $data = Jurnalsimpanan::where('id', '=', $id)->delete();
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
        
        $jmldata = Jurnalsimpanan::where('tgltransaksi','=',$tgltransaksi1)            
            ->count();
        if ($jmldata==0){
            $nomor = 1;    
        }else{
            $nomor = Jurnalsimpanan::select('nomorb')
                ->where('tgltransaksi','=',$tgltransaksi1)
                ->whereRaw('left(nomorbukti,3) = ? ', ['TRK'])
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

        //nomor kwitansi contoh : TRK.001.20230527.0009
        $nbx = 'TRK' . '.' . $idx . '.' . $tgl1 . '.' . $no;  
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
        //tabel jenisimpanan
        $tampil3 = Jenissimpanan::select('*')
        ->with(['coasetord','coasetork','coatarikd','coatarikk','coatfmasukd','coatfmasukk',
            'coatfkeluard','coatfkeluark','jenisjurnalsetord','jenisjurnalsetork',
            'jenisjurnaltarikd','jenisjurnaltarikk','jenisjurnaltfmasukd','jenisjurnaltfmasukk',
            'jenisjurnaltfkeluard','jenisjurnaltfkeluark'])
        ->where('id','=',$request['idjenissimpananx1'])
        ->get();
        foreach ($tampil3 as $baris) {
            $idcoad1 = $baris->idcoasetord;
            $idcoak1 = $baris->idcoasetork;
            
            $idkelompokd1 = $baris->coatarikd->kodekelompok;
            $idkelompokk1 = $baris->coatarikk->kodekelompok;
            
            $idkategorid1 = $baris->coatarikd->idkategori;
            $idkategorik1 = $baris->coatarikk->idkategori;
            
            $idjenisjurnald1 = $baris->jenisjurnaltarikd->id;
            $idjenisjurnalk1 = $baris->jenisjurnaltarikk->id;
        }

        session([
            'cariidx1' => $request['cariidx1'],
            'namax1' => $request['namax1'],
            'norekx1' => $request['norekx1'],
            'niax1' => $request['niax1'],
            'nikx1' => $request['nikx1'],
            'ecardx1' => $request['ecardx1'],
            'saldox1' => $request['saldox1'],
            'idnasabahx1' => $request['idnasabahx1'],
            'idjenissimpananx1' => $request['idjenissimpananx1'],
            'jenissimpananx1' => $request['jenissimpananx1'],
            'tgltransaksi1' => $request['tgltransaksi1'],
            'nomorbukti1' => $request['nomorbukti1'],
            'tglawalx1' => $request['tglawalx1'],
            'tglakhirx1' => $request['tglakhirx1'],
            'idcoa1' => $idcoak1,
            
        ]);
        
    }
    public function cariid(Request $request)
    {
        $cari = $request['cari1'];
        $idnasabah = Jurnalsimpanan::select('idtarget')->get();
        $tampil = Nasabah::where('norek','=',$cari)
                ->whereIn('id',$idnasabah)
                ->where('aktif','=','Y')
                ->get();
        foreach ($tampil as $item) {
            $id1 = $item->id;
            $idjenissimpanan1 = $item->idjenissimpanan;
        }

        $tampil = Jenissimpanan::where('id','=',$idjenissimpanan1)
            ->get();
        foreach ($tampil as $baris) {
            $idcoad1 = $baris->idcoatarikd;
        }
        $debet = Jurnalsimpanan::select('debet')
            ->where('idtarget','=',$id1)
            ->where('idcoa','=',$idcoad1)
            ->sum('debet');    
        $kredit = Jurnalsimpanan::select('kredit')
            ->where('idtarget','=',$id1)
            ->where('idcoa','=',$idcoad1)
            ->sum('kredit');    
        $saldo = $kredit-$debet;

        $data = Nasabah::limit(1)->select('*')
                ->with(['jenissimpanan'])
                ->selectRaw('('. $saldo .') as saldox')
                ->where('norek','=',$cari)
                ->where('aktif','=','Y')
                ->get();
         
        return json_encode(array('data' => $data));

    }
    public function carinomorbukti(Request $request)
    {
        $cari = $request['cari1'];

        $tampil = Jurnalsimpanan::with(['nasabah'])
            ->where('nomorbukti','=',$cari)
            ->get();
        foreach ($tampil as $item) {
            $idjenissimpanan1 = $item->idjenissimpanan;
            $id1 = $item->idtarget;
        }

        $tampil = Jenissimpanan::where('id','=',$idjenissimpanan1)
            ->get();
        foreach ($tampil as $baris) {
            $idcoak1 = $baris->idcoatarikd;
        }
        $debet = Jurnalsimpanan::select('debet')
            ->where('idtarget','=',$id1)
            ->where('idcoa','=',$idcoak1)
            ->sum('debet');    
        $kredit = Jurnalsimpanan::select('kredit')
            ->where('idtarget','=',$id1)
            ->where('idcoa','=',$idcoak1)
            ->sum('kredit');    
        $saldo = $kredit-$debet;

        $data = Nasabah::limit(1)->select('*')
                ->with(['jenissimpanan'])
                ->selectRaw('('. $saldo .') as saldox')
                ->where('id','=',$id1)
                ->where('aktif','=','Y')
                ->get();
         
        return json_encode(array('data' => $data));

    }

    public function printdetail($id)
    {
        $nb = explode("_", $id);
        $id6x = $nb[0];
        $noprintx6 = $nb[1];
        
        $tmp = Jurnalsimpanan::where('id','=',$id6x)->get();
        foreach ($tmp as $baris) {
            $idcoa1 = $baris->idcoa;
            $idsandi1 = $baris->idsandi;
            $idjenisjurnal1 = $baris->idjenisjurnal;
            $idjenissimpanan1 = $baris->idjenissimpanan;
            $idnasabah1 = $baris->idnasabah;
            $idtarget1 = $baris->idtarget;
            $created_at1 = $baris->created_at;
            $updated_at1 = $baris->updated_at;
            $tgltransaksi1 = $baris->tgltransaksi;
            $nomorb1 = $baris->nomorb;
            $nomorbukti1 = $baris->nomorbukti;
            $jml1 = $baris->debet;
            $tglposting1 = $baris->tglposting;
            $nomorposting1 = $baris->nomorposting;
            $keterangan1 = $baris->keterangan;
        }

        //tabel jenisimpanan
        $tampil3 = Jenissimpanan::select('*')
        ->with(['coasetord','coasetork','coatarikd','coatarikk','coatfmasukd','coatfmasukk',
            'coatfkeluard','coatfkeluark','jenisjurnalsetord','jenisjurnalsetork',
            'jenisjurnaltarikd','jenisjurnaltarikk','jenisjurnaltfmasukd','jenisjurnaltfmasukk',
            'jenisjurnaltfkeluard','jenisjurnaltfkeluark'])
        ->where('id','=',$idjenissimpanan1)
        ->get();
        foreach ($tampil3 as $baris) {
            $idcoad1 = $baris->idcoatarikd;
            $idcoak1 = $baris->idcoatarikk;
            
            $idkelompokd1 = $baris->coatarikd->kodekelompok;
            $idkelompokk1 = $baris->coatarikk->kodekelompok;
            
            $idkategorid1 = $baris->coatarikd->idkategori;
            $idkategorik1 = $baris->coatarikk->idkategori;
            
            $idjenisjurnald1 = $baris->jenisjurnaltarikd->id;
            $idjenisjurnalk1 = $baris->jenisjurnaltarikk->id;
        }
        

        $tampil = Jurnalsimpanan::select('*')
                ->orderBy('id','asc')
                ->where('idtarget','=', $idtarget1)
                ->where('idcoa','=',$idcoad1)
                ->where('id','>=', $id6x)
                ->get();

        
        session([
            'noprintx6' => $noprintx6,
        ]);
            
        $page = 'simpanan01::laporan.rekeningkoran_printdetail';
        $link = '/simpanan01/laporan/rekeningkoran_printdetail';

        $pdf = PDF::loadView($page, [            
                       
            'tabel' => $tampil,
            'noprintx6' => $noprintx6,
            'idcoa1' => $idcoad1,
            'idtarget1' => $idtarget1,
            

        ])->setOptions(['defaultFont' => 'sans-serif', 'defaultFont' => 'sans-serif' ])->set_option('isHtml5ParserEnabled', true); 
        $pdf->set_paper(array(0, 0, 419.53, 595.28), 'portrait');
        
        return $pdf->stream('detailsimpanan.pdf');

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
