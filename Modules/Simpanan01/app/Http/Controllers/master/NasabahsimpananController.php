<?php

namespace Modules\Simpanan01\Http\Controllers\master;
use DateTime;
use Carbon\Carbon;
use Modules\Simpanan01\Models\Sandi;
use PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Aplikasi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Storage;
use App\Imports\ImportDatasiswa;
use App\Models\Menusub;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin01\Models\Anggota;
use Modules\Akuntansi01\Models\Jenissimpanan;
use Modules\Simpanan01\Models\Nasabah;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class NasabahsimpananController extends Controller
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
        $remark = 'Halaman ini digunakan untuk menampilkan, menambah, mengubah dan menghapus <b>Nasabah Simpanan</b>.';
        $page = 'simpanan01::master.nasabahsimpanan';
        $link = '/simpanan01/master/nasabahsimpanan';
        $subtitle = 'Master';
        $caption = 'Nasabah simpanan';
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
 
    public function importData(Request $request)
    {
        // //VALIDASI
        // // $this->validate($request, [
        // //     'file' => 'required|mimes:xls,xlsx'
        // // ]);
        
        $data = Sandi::get();

        // if ($request->hasFile('file1')) {
        //     $validateFile = $request->validate([
        //         'file1' => 'required|mimes:xls,xlsx',
        //     ]);

        //     if($validateFile){
        //         $filex = $request->file('file1')->store('sips01-datasiswa-excel');
        //         //import data            
        //         Excel::import(new ImportDatasiswa, $filex); //IMPORT FILE
        //         //delete memory
        //         storage::delete($filex);
               
        //         $tampil = Mem_datasiswa::whereNotNull(['nis','nisn','ecard'])
        //             ->get();
        //         foreach ($tampil as $item) {
        //             $nis = $item->nis;            
        //             $nisn = $item->nisn;            
        //             $ecard = $item->ecard;            
        //             $namalengkap = $item->namalengkap;

        //             $data1 = [
        //                 'norek' => $nisn,
        //                 'nis' => $nis,
        //                 'nisn' => $nisn,
        //                 'ecard' => $ecard,
        //                 'namalengkap' => $namalengkap,                        
        //                 'kelas' => $request['kelasx'],                        
        //                 'tgldaftar' => $request['tgldaftarx'],                        
        //                 'tgllahir' => $request['tgllahirx'],                                                                       
        //             ];

        //             $data2 = [
        //                 'nis' => $nis,
        //                 'nisn' => $nisn,
        //                 'ecard' => $ecard,
        //                 'namalengkap' => $namalengkap,                        
        //                 'kelas' => $request['kelasx'],                        
        //                 'tgldaftar' => $request['tgldaftarx'],                        
        //                 'tgllahir' => $request['tgllahirx'],                                                                       
        //             ];

        //             $cari = Nasabah::where('nis', '=', $nis)
        //                 ->orWhere('nisn', '=', $nisn)
        //                 ->orWhere('ecard', '=', $ecard)
        //                 ->count();
        //             if($cari==0){
        //                 Nasabah::create($data1);
        //             }else{
        //                 Nasabah::where('nis', '=', $nis)
        //                 ->orWhere('nisn', '=', $nisn)
        //                 ->orWhere('ecard', '=', $ecard)
        //                 ->update($data2);
        //             }    
        //         }
                
        //          Mem_datasiswa::where('id','>',0)
        //             ->orWhere(function(Builder $query1) {
        //                 $query1->whereNull(['nis']);
        //                 })                 
        //             ->orWhere(function(Builder $query2) {
        //                 $query2->whereNull(['nisn']);
        //                 })                 
        //             ->orWhere(function(Builder $query3) {
        //                 $query3->whereNull(['ecard']);
        //                 })                 
        //             ->delete();
        //          alert()->success('Import Data', 'Successed ...'); 
        //     }
        //     return redirect()->back();
        // }  
        // return redirect()->back();


        // // Mem_datasiswa::delete();
        // // return json_encode(array('data' => $data));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
   
    public function create(Request $request)
    {
        $id = $request['id1'];

        $validatedData = $request->validate([
            'nama1' => 'required',
            'idanggota1' => 'required',
            'norek1' => 'required',
            'ecard1' => 'required',
            'nia1' => 'required',
            'nik1' => 'required',
            'tgldaftar1' => 'required',
            'idjenissimpananx1' => 'required',
        ]);
          
        //untuk input tabel yang asli
        
        $data = [            
            'idjenissimpanan' => $validatedData['idjenissimpananx1'],
            'idanggota' => $validatedData['idanggota1'],
            'nama' => $validatedData['nama1'],
            'norek' => $validatedData['norek1'],
            'ecard' => $validatedData['ecard1'],
            'nia' => $validatedData['nia1'],
            'nik' => $validatedData['nik1'],
            'desain' => $request['desain1'],
            'tandapengenal' => $request['tandapengenal1'],
            'tgllahir' => $request['tgllahir1'],
            'tgldaftar' => $validatedData['tgldaftar1'],
            'tglkeluar' => $request['tglkeluar1'],
            'alamat' => $request['alamat1'],
            'telp' => $request['telp1'],
            'aktif' => $request['aktif1'],
        ];

        if ($id == '0') {
            Nasabah::create($data);
        } else {
            Nasabah::where('id', '=', $id)->update($data);
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
        $a1 = session()->get('idjenissimpananx1');

        $datax = DataTables::of(
            Nasabah::with(['jenissimpanan'])
            ->where('idjenissimpanan','=',$a1)
            // ->orderBy('namalengkap','asc')                
            );

        $data = $datax
            ->addIndexColumn()
            ->addColumn('jenissimpanan', function ($row) {
                return $row->idjenissimpanan ? $row->jenissimpanan->jenissimpanan : '';
            })
            
            ->addColumn('tgldaftar', function ($row) {
                return $row->tgldaftar ? $row->tgldaftar : '';
            })
            ->addColumn('tgllahir', function ($row) {
                return $row->tgllahir ? $row->tgllahir : '';
            })
            ->addColumn('alamat', function ($row) {
                return $row->alamat ? $row->alamat : '';
            })
            ->addColumn('telp', function ($row) {
                return $row->telp ? $row->telp : '';
            })
            ->addColumn('saldo', function ($row) {
                return $row->saldo ? number_format($row->saldo,0) : '0';
            })
            
            ->addColumn('action', function ($row) {
                return '<a href="#" title="Edit Data" class="btn btn-success btn-xs item_edit" data="' . $row->id . '" data2="'. $row->anggota->nia.'" data3="'. $row->nama.'" data4="'. $row->nik.'"><i style="font-size:18px" class="fa">&#xf044;</i></a> ' .
                       '<a href="#" title="Hapus Data" class="btn btn-danger btn-xs item_hapus" data="' . $row->id . '" data2="'. $row->anggota->nia.'" data3="'. $row->nama.'" data4="'. $row->nik.'"><i style="font-size:18px" class="fa">&#xf00d;</i></a> ' .
                       '<a href="#" title="Print Cover" class="btn btn-info btn-xs item_printcover" data="' . $row->id . '" data2="'. $row->anggota->nia.'" data3="'. $row->nama.'" data4="'. $row->nik.'" data5="'. $row->norek.'"><i style="font-size:18px" class="fa">&#xf02f;</i></a> ' .
                       '<a href="#" title="Print Header" class="btn btn-info btn-xs item_printheader" data="' . $row->id . '" data2="'. $row->anggota->nia.'" data3="'. $row->nama.'" data4="'. $row->nik.'" data5="'. $row->norek.'"><i style="font-size:18px" class="fa">&#xf02f;</i></a>';
            })
            
            ->rawColumns(['jenissimpanan','action'])


            ->make(true);

            return $data;

    }

    public function showanggota()
    {
        $datax = Anggota::with(['desa'])->get();
        
        $data = DataTables::of($datax)
            ->addIndexColumn()
           
            ->addColumn('nama', function ($row) {
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
                    data11="'. $row->alamat . '"
                    data12="'. $row->tgllahir . '"
                    data13="'. $row->telp . '"
                    >'.($row->nama ? $row->nama : '-').'</a> ';
            })
            ->addColumn('nia', function ($row) {
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
                    data11="'. $row->alamat . '"
                    data12="'. $row->tgllahir . '"
                    data13="'. $row->telp . '"
                    >'.($row->nia ? $row->nia : '-').'</a> ';
            })
            ->addColumn('nik', function ($row) {
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
                    data11="'. $row->alamat . '"
                    data12="'. $row->tgllahir . '"
                    data13="'. $row->telp . '"
                    >'.($row->nik ? $row->nik : '-').'</a> ';
            })
            ->addColumn('ecard', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->ecard ? $row->ecard : '-') .'" class="item_ecard" 
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
                    data11="'. $row->alamat . '"
                    data12="'. $row->tgllahir . '"
                    data13="'. $row->telp . '"
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
                'nama',                
                'nia',                
                'nik',                
                'ecard',                
                'desa',                
                'kecamatan',
                'kabupaten',
                'propinsi',
                'action'])

            ->make(true);

            return $data;

    }
    
    public function edit($id)
    {
        $data = Nasabah::where('id', '=', $id)->get();
        return json_encode(array('data' => $data));       
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        // date_default_timezone_set('Asia/Jakarta');

        // $tgl = new DateTime('now');
        // $d = $tgl->format('d');
        // $m = $tgl->format('n');
        // $y = $tgl->format('Y');
        // $hari = $tgl->format('N');
        // $mm = $tgl->format('m');
        // $tgldaftar = $y . '-' . $mm . '-' . $d;

        // $cektp = Tp::select('id')
        // ->where('tglawal','<=',$tgldaftar)
        // ->where('tglakhir','>=',$tgldaftar)
        // ->get();
        // foreach ($cektp as $item) {
        //     $idtp = $item->id;
        // }

        // $tampil = Datasiswa::select(['id','nis','nisn','ecard','namalengkap','tgllahir','alamat','telp','tglkeluar','aktif'])
        //     ->whereNotNull(['nis','nisn','ecard'])
        //     ->get();
        // foreach ($tampil as $item) {
        //     $iddatasiswa = $item->id;
        //     $nis = $item->nis;            
        //     $nisn = $item->nisn;            
        //     $ecard = $item->ecard;            
        //     $namalengkap = $item->namalengkap;
        //     $tgllahir = $item->tgllahir;
        //     $tglkeluar = $item->tglkeluar;
        //     $alamat = $item->alamat;
        //     $telp = $item->telp;
        //     $aktif = $item->aktif;

        //     if($aktif=='Y'){
        //         $cekkelas = Siswakelas::where('iddatasiswa','=',$iddatasiswa)
        //         ->where('idtp','=',$idtp)            
        //         ->count();
        //         if($cekkelas==0){
        //             $kelas='';
        //         }else{
        //             $kelasx = Siswakelas::where('iddatasiswa','=',$iddatasiswa)
        //             ->where('idtp','=',$idtp)
        //             ->with(['datasiswa','tp','kelas'])
        //             ->get();
        //             foreach ($kelasx as $item) {
        //                 $kelas = $item->kelas->kelas;
        //             }
        //         }
                
        //     }else{
        //         $kelas='Keluar';                
        //     }

        //     $data = [            
        //         'namalengkap' => $namalengkap,
        //         'norek' => $nisn,
        //         'ecard' => $ecard,
        //         'nis' => $nis,
        //         'nisn' => $nisn,
        //         'kelas' => $kelas,
        //         'tgllahir' => $tgllahir,
        //         'tgldaftar' => $tgldaftar,
        //         'tglkeluar' => $tglkeluar,
        //         'alamat' => $alamat,
        //         'telp' => $telp,
        //         'aktif' => $aktif,
        //     ];

        //     $data2 = [            
        //         'namalengkap' => $namalengkap,
        //         'kelas' => $kelas,                                
        //         'tgllahir' => $tgllahir,
        //         'tglkeluar' => $tglkeluar,
        //         'alamat' => $alamat,
        //         'telp' => $telp,
        //         'aktif' => $aktif,
        //     ];

        //     $cari = Nasabah::where('nis', '=', $nis)
        //         ->orWhere('nisn', '=', $nisn)
        //         ->orWhere('ecard', '=', $ecard)
        //         ->count();
        //     if($cari==0){
        //         Nasabah::create($data);
        //     }else{
        //         Nasabah::where('nis', '=', $nis)
        //         ->orWhere('nisn', '=', $nisn)
        //         ->orWhere('ecard', '=', $ecard)
        //         ->update($data2);
        //     }    
        // }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $data = Nasabah::where('id', '=', $id)->delete();
        return json_encode(array('data' => $data));
    }

    public function kirimsyarat(Request $request)
    {
        session([
            'idjenissimpanan1' => $request['idjenissimpananx1'],
            'idjenissimpananx1' => $request['idjenissimpananx1'],
        ]);
    }

    function listjenissimpanan()
    {        
        $tampil = Jenissimpanan::select('*')            
            ->get();           
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->jenissimpanan . "</option>";
        }
    }

    public function printcover($norek)
    {
        $tampil = Nasabah::with(['jenissimpanan'])
            ->where('norek','=', $norek)
            ->get();
        
        foreach ($tampil as $baris) {
            $jenissimpanan = $baris->jenissimpanan->jenissimpanan;
            $keterangan = $baris->jenissimpanan->keterangan;
            $nama = $baris->nama;
            $norek = $baris->norek;
            $nia = $baris->nia;
            $nik = $baris->nik;
            $desain = $baris->desain;
            $tandapengenal = $baris->tandapengenal;
            $alamat = $baris->alamat;
        }
            
        $page = 'simpanan01::master.nasabahsimpanan_printcover';
        $link = '/simpanan01/master/nasabahsimpanan_printcover';

        $pdf = PDF::loadView($page, [            
                       
            'jenissimpanan' => $jenissimpanan,
            'keterangan' => $keterangan,
            'nama' => $nama,
            'norek' => $norek,
            'nia' => $nia,
            'nik' => $nik,
            'desain' => $desain,
            'tandapengenal' => $tandapengenal,
            'alamat' => $alamat,

        ])->setOptions(['defaultFont' => 'sans-serif', 'defaultFont' => 'sans-serif' ])->set_option('isHtml5ParserEnabled', true); 
        $pdf->set_paper(array(0, 0, 419.53, 595.28), 'portrait');
        
        return $pdf->stream('bukutabungan_cover.pdf');

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
    public function printheader($norek)
    {
        $tampil = Nasabah::with(['jenissimpanan'])
            ->where('norek','=', $norek)
            ->get();
        
        foreach ($tampil as $baris) {
            $jenissimpanan = $baris->jenissimpanan->jenissimpanan;
            $namalengkap = $baris->namalengkap;
            $norek = $baris->norek;
            $nis = $baris->nis;
            $nisn = $baris->nisn;
            $kelas = $baris->kelas;
            $alamat = $baris->alamat;
        }

        

            
        $page = 'simpanan01::master.nasabahsimpanan_printheader';
        $link = '/simpanan01/master/nasabahsimpanan_printheader';

        $pdf = PDF::loadView($page, [            
                       
            'jenissimpanan' => $jenissimpanan,
            'namalengkap' => $namalengkap,
            'norek' => $norek,
            'nis' => $nis,
            'nisn' => $nisn,
            'kelas' => $kelas,
            'alamat' => $alamat,
            
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
