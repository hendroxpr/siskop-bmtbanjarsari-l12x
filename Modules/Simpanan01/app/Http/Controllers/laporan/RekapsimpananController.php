<?php

namespace Modules\Simpanan01\Http\Controllers\laporan;

use Carbon\Carbon;
use App\Models\Menusub;
use App\Models\Aplikasi;
use Modules\Akuntansi01\Models\Jenissimpanan;
use Modules\Simpanan01\Models\Jurnalsimpanan;
use PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Modules\Simpanan01\Models\Nasabah;

use Yajra\DataTables\Facades\DataTables;

class  RekapsimpananController extends Controller
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
        $remark = 'Halaman ini digunakan untuk menampilkan <b>Rekap Simpanan</b>';
        $page = 'simpanan01::laporan.rekapsimpanan';
        $link = '/simpanan01/laporan/rekapsimpanan';
        $subtitle = 'Laporan';
        $caption = 'Rekap Simpanan';
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
            'tabel' => Nasabah::SimplePaginate($jmlhal)->withQueryString(),
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
        $idjenissimpanan1 = session('idjenissimpananx1');
        $idnasabah1 = Jurnalsimpanan::select('idtarget')->get();

        $datax = DataTables::of(
            Nasabah::with(['anggota','jenissimpanan'])
                ->where('idjenissimpanan','=',$idjenissimpanan1)
                ->where('aktif','=','Y')
                ->whereIn('id',$idnasabah1)
            );

        $data = $datax
            ->addIndexColumn()

            ->addColumn('tgltransaksi', function ($row) {
                $tampil = Jurnalsimpanan::limit(1)
                    ->where('idtarget','=',$row->id)
                    ->where('idcoa','=',session('idcoa1'))
                    ->orderBy('id','asc')
                    ->get();
                foreach ($tampil as $baris) {
                    $tgltransaksi1 = $baris->tgltransaksi;
                }
                return $tgltransaksi1;
            })
            ->addColumn('nomorbukti', function ($row) {
                $tampil = Jurnalsimpanan::limit(1)
                    ->where('idtarget','=',$row->id)
                    ->where('idcoa','=',session('idcoa1'))
                    ->orderBy('id','asc')
                    ->get();
                foreach ($tampil as $baris) {
                    $nomorbukti1 = $baris->nomorbukti;
                }
                return $nomorbukti1;
            })
            ->addColumn('keterangan', function ($row) {
                $tampil = Jurnalsimpanan::limit(1)
                    ->where('idtarget','=',$row->id)
                    ->where('idcoa','=',session('idcoa1'))
                    ->orderBy('id','asc')
                    ->get();
                foreach ($tampil as $baris) {
                    $keterangan1 = $baris->keterangan;
                }
                return $keterangan1;
            })
            ->addColumn('nama', function ($row) {
                return $row->idanggota ? $row->anggota->nama : '-';
            })
            ->addColumn('nia', function ($row) {
                return $row->idanggota ? $row->anggota->nia : '-';
            })
           
            ->addColumn('jenissimpanan', function ($row) {
                return $row->idjenissimpanan ? $row->jenissimpanan->jenissimpanan : '-';
            })
            ->addColumn('debet', function ($row) {
                $debet = Jurnalsimpanan::select('debet')
                    ->where('idtarget','=',$row->id)
                    ->where('idcoa','=',session('idcoa1'))
                    ->sum('debet');    
                $kredit = Jurnalsimpanan::select('kredit')
                    ->where('idtarget','=',$row->id)
                    ->where('idcoa','=',session('idcoa1'))
                    ->sum('kredit');    
                $saldo = $kredit-$debet;
                return number_format($debet,'0');
            })
            ->addColumn('kredit', function ($row) {
                $debet = Jurnalsimpanan::select('debet')
                    ->where('idtarget','=',$row->id)
                    ->where('idcoa','=',session('idcoa1'))
                    ->sum('debet');    
                $kredit = Jurnalsimpanan::select('kredit')
                    ->where('idtarget','=',$row->id)
                    ->where('idcoa','=',session('idcoa1'))
                    ->sum('kredit');    
                $saldo = $kredit-$debet;
                return number_format($kredit,'0');
            })
            ->addColumn('saldo', function ($row) {
                $debet = Jurnalsimpanan::select('debet')
                    ->where('idtarget','=',$row->id)
                    ->where('idcoa','=',session('idcoa1'))
                    ->sum('debet');    
                $kredit = Jurnalsimpanan::select('kredit')
                    ->where('idtarget','=',$row->id)
                    ->where('idcoa','=',session('idcoa1'))
                    ->sum('kredit');    
                $saldo = $kredit-$debet;
                return number_format($saldo,'0');
            })
            

            ->rawColumns([
                'tgltransaksi',
                'nomorbukti',
                'norek',
                'nama',
                'nia',
                'jenissimpanan',
                'debet',
                'kredit',
                'saldo',
                'keterangan',
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
    
    public function kirimsyarat(Request $request)
    {

        $idjenissimpanan1 = $request['idjenissimpananx1'];
        $tampil =Jenissimpanan::where('id','=',$idjenissimpanan1)
            ->get();
        foreach ($tampil as $baris) {
            $idcoa1 = $baris->idcoasetork;
        }


        session([
            'idjenissimpananx1' => $request['idjenissimpananx1'],
            'idcoa1' => $idcoa1,
        ]);
        
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
