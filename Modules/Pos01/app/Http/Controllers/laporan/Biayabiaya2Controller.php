<?php

namespace Modules\Pos01\Http\Controllers\laporan;

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
use Modules\Pos01\Models\Barang;
use Modules\Pos01\Models\Biaya;
use Modules\Pos01\Models\Jenisbiaya;
use Modules\Pos01\Models\Kategoribiaya;
use Modules\Pos01\Models\Pendapatan;
use Modules\Pos01\Models\Ruang;
use Modules\Pos01\Models\Satuan;
use Modules\Pos01\Models\Supplier;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class Biayabiaya2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // $product = new Product;
        // $product->setConnection('mysql_second');
        // $something = $product->find(1);
        // return $something;

        $meminstansi = session('memnamasingkat');
        $remark = 'Halaman ini digunakan untuk menampilkan <b>Biaya-biaya</b>.';
        $page = 'pos01::laporan.biayabiaya2';
        $link = '/pos01/laporan/biayabiaya2';
        $subtitle = 'Laporan';
        $caption = 'Biaya-biaya';
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
            'tabel' => Pendapatan::SimplePaginate($jmlhal)->withQueryString(),
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

        $validatedData = $request->validate([
            'tgltransaksi1' => 'required',
            'idruang1' => 'required',
            'idkategoribiaya1' => 'required',            
        ]);
        
        $data = [            
            'tgltransaksi' => $validatedData['tgltransaksi1'],
            'idruang' => $validatedData['idruang1'],
            'idkategoribiaya' => $validatedData['idkategoribiaya1'],

            'idsatuan' => $request['idsatuan1'],
            'nomorbukti' => $request['nomorbukti1'],
            'pendapatan' => $request['pendapatan1'],
            'qty' => $request['qty1'],
            'hjs' => $request['hjs1'],
            'subtotal' => $request['subtotal1'],
            'ppn' => $request['ppn1'],
            'diskon' => $request['diskon1'],
            'totaljual' => $request['totaljual1'],
            'keterangan' => $request['keterangan1'],
            'idusers' => auth()->user()->id,
        ];

        if ($id == '0') {
            Pendapatan::create($data);
        } else {
            Pendapatan::where('id', '=', $id)->update($data);
        }
        return json_encode(array('data' => $data));
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
        if($tgltransaksi1==''){
            $tgltransaksi1 = session('memtanggal');
        }    
        $tgltransaksi2 = session('tgltransaksi2');
        if($tgltransaksi2==''){
            $tgltransaksi2 = session('memtanggal');
        }    
        $idruang = session('idruangx1');
        if($idruang==''||$idruang=='0'){
            $idruangawal = 0;
            $idruangakhir = 999999;
        }else{
            $idruangawal = $idruang;
            $idruangakhir = $idruang;
        }
        $idkategoribiaya = session('idketegoribiayax1');
        if($idkategoribiaya==''||$idkategoribiaya=='0'){
            $idkategoribiayaawal = 0;
            $idkategoribiayaakhir = 999999;
        }else{
            $idkategoribiayaawal = $idkategoribiaya;
            $idkategoribiayaakhir = $idkategoribiaya;
        }
        $idjenisbiaya = session('idjenisbiayax1');
        if($idjenisbiaya==''||$idjenisbiaya=='0'){
            $idjenisbiayaawal = 0;
            $idjenisbiayaakhir = 999999;
        }else{
            $idjenisbiayaawal = $idjenisbiaya;
            $idjenisbiayaakhir = $idjenisbiaya;
        }

        $biaya = Biaya::select('*')
            ->where('tgltransaksi','>=',$tgltransaksi1)
            ->where('tgltransaksi','<=',$tgltransaksi2)
            ->where('idruang','>=',$idruangawal)
            ->where('idruang','<=',$idruangakhir)
            ->where('idkategoribiaya','>=',$idkategoribiayaawal)
            ->where('idkategoribiaya','<=',$idkategoribiayaakhir)
            ->where('idjenisbiaya','>=',$idjenisbiayaawal)
            ->where('idjenisbiaya','<=',$idjenisbiayaakhir)
            ->with('kategoribiaya','ruang','jenisbiaya')
            ->get();
        $datax = DataTables::of($biaya                          
            );

        $data = $datax
            ->addIndexColumn()
            
            ->addColumn('jenisbiaya', function ($row) {
                return $row->idjenisbiaya ? $row->jenisbiaya->jenisbiaya : '';
            })
            ->addColumn('kategoribiaya', function ($row) {
                return $row->idkategoribiaya ? $row->kategoribiaya->kategoribiaya : '';
            })
            ->addColumn('ruang', function ($row) {
                return $row->idruang ? $row->ruang->ruang : '';
            })
            ->addColumn('satuan', function ($row) {
                return $row->idsatuan ? $row->satuan->satuan : '';
            })
            ->addColumn('hjs', function ($row) {
                return $row->hjs ? number_format($row->hjs,0) : '0';
            })
            ->addColumn('subtotal', function ($row) {
                return $row->subtotal ? number_format($row->subtotal,0) : '0';
            })
            ->addColumn('ppn', function ($row) {
                return $row->ppn ? number_format($row->ppn,0) : '0';
            })
            ->addColumn('diskon', function ($row) {
                return $row->diskon ? number_format($row->diskon,0) : '0';
            })
            ->addColumn('totalbeli', function ($row) {
                return $row->totalbeli ? number_format($row->totalbeli,0) : '0';
            })
            
            ->rawColumns([
                'satuan',
                ])

            ->make(true);

            return $data;

    }

    
    public function edit($id)
    {
        $data = Pendapatan::where('id', '=', $id)->get();
        return json_encode(array('data' => $data));       
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {

        $data = Pendapatan::where('id', '=', $id)->delete();
        return json_encode(array('data' => $data));
    }
    function listkategoribiaya()
    {
        $idx='0';
        $isi='- SEMUA -';
        $tampil = Kategoribiaya::orderBy('id')->get();
            echo "<option value='" . $idx . "'>" . $isi . "</option>";
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kategoribiaya . "</option>";
        }
    }
    function listkategoribiayax()
    {
        $idx='0';
        $isi='- SEMUA -';        
        $tampil = Kategoribiaya::get();
        echo "<option value='" . $idx . "'>" . $isi . "</option>";
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kategoribiaya . "</option>";
        }
    }
    
    function listruang()
    {
        $idx='0';
        $isi='- SEMUA -';
        $tampil = Ruang::orderBy('ruang', 'asc')->get();
            echo "<option value='" . $idx . "'>" . $isi . "</option>";
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->ruang . "</option>";
        }
        
    }
    function listjenisbiaya()
    {
        $idx='0';
        $isi='- SEMUA -';
        $tampil = Jenisbiaya::get();
            echo "<option value='" . $idx . "'>" . $isi . "</option>";
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->jenisbiaya . "</option>";
        }
        
    }
    

    public function kirimsyarat(Request $request)
    {
        session([
            'tgltransaksi1' => $request['tgltransaksi1'],
            'tgltransaksi2' => $request['tgltransaksi2'],
            'idketegoribiayax1' => $request['idkategoribiayax1'],
            'idjenisbiayax1' => $request['idjenisbiayax1'],
            'idruangx1' => $request['idruangx1'],
        ]);
    }


}
