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
use Modules\Pos01\Models\Barang;
use Modules\Pos01\Models\Barangruang;
use Modules\Pos01\Models\Biaya;
use Modules\Pos01\Models\Jenisbiaya;
use Modules\Pos01\Models\Ruang;
use Modules\Pos01\Models\Satuan;
use Modules\Pos01\Models\Supplier;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class BiayaoperasionalController extends Controller
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
        $remark = 'Halaman ini digunakan untuk menampilkan, menambah, mengubah dan menghapus <b>Biaya Operasional</b>.';
        $page = 'pos01::transaksi.biayaoperasional';
        $link = '/pos01/transaksi/biayaoperasional';
        $subtitle = 'Transaksi';
        $caption = 'Biaya Operasional';
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
            'tabel' => Barangruang::SimplePaginate($jmlhal)->withQueryString(),
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
            'idsupplier1' => 'required',
            'idjenisbiaya1' => 'required',            
        ]);

        //untuk input tabel yang asli
        $data = [            
            'tgltransaksi' => $validatedData['tgltransaksi1'],
            'idruang' => $validatedData['idruang1'],
            'idsupplier' => $validatedData['idsupplier1'],
            'idjenisbiaya' => $validatedData['idjenisbiaya1'],

            'idsatuan' => $request['idsatuan1'],
            'nomorbukti' => $request['nomorbukti1'],
            'biaya' => $request['biaya1'],
            'qty' => $request['qty1'],
            'hbs' => $request['hbs1'],
            'totalbeli' => $request['totalbeli1'],
            'keterangan' => $request['keterangan1'],
            'idusers' => auth()->user()->id,
        ];

        if ($id == '0') {
            Biaya::create($data);
        } else {
            Biaya::where('id', '=', $id)->update($data);
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
        $tgltransaksi = session('tgltransaksi1');     
        $idruang = session('idruangx1');
        $idjenisbiaya = session('idjenisbiayax1');
        $idsupplier = session('idsupplierx1');

        $biaya = Biaya::where('tgltransaksi','=',$tgltransaksi)
            ->where('idruang','=',$idruang)
            ->where('idjenisbiaya','=',$idjenisbiaya)
            ->where('idsupplier','=',$idsupplier)
            ->with('jenisbiaya','supplier','ruang')
            ->get();
        $datax = DataTables::of($biaya                          
            );

        $data = $datax
            ->addIndexColumn()
            
            ->addColumn('satuan', function ($row) {
                return $row->idsatuan ? $row->satuan->satuan : '';
            })
            ->addColumn('hbs', function ($row) {
                return $row->hbs ? number_format($row->hbs,0) : '';
            })
            ->addColumn('totalbeli', function ($row) {
                return $row->totalbeli ? number_format($row->totalbeli,0) : '';
            })
            
            ->addColumn('action', function ($row) {
                return '<a href="#" title="Edit Data" class="btn btn-success btn-xs item_edit" data="' . $row->id . '" data2="'. $row->biaya.'" data3="'. $row->qty.'" data4="'. $row->hbs.'"><i style="font-size:18px" class="fa">&#xf044;</i></a> ' .
                       '<a href="#" title="Hapus Data" class="btn btn-danger btn-xs item_hapus" data="' . $row->id . '" data2="'. $row->biaya.'" data3="'. $row->qty.'" data4="'. $row->hbs.'"><i style="font-size:18px" class="fa">&#xf00d;</i></a>';
            })
            
            ->rawColumns([
                'satuan',
                'action',
                ])

            ->make(true);

            return $data;

    }

    
    public function edit($id)
    {
        $data = Biaya::where('id', '=', $id)->get();
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

        $data = Biaya::where('id', '=', $id)->delete();
        return json_encode(array('data' => $data));
    }
    function listjenisbiaya()
    {
        $tampil = Jenisbiaya::orderBy('id')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->jenisbiaya . "</option>";
        }
    }
    function listjenisbiayax()
    {
        $idx='0';
        $isi='- SEMUA -';        
        $tampil = Jenisbiaya::get();
        echo "<option value='" . $idx . "'>" . $isi . "</option>";
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->jenisbiaya . "</option>";
        }
    }
    function listsupplier()
    {
        $tampil = Supplier::orderBy('id')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->supplier . "</option>";
        }
    }
    function listruang()
    {
        $tampil = Ruang::orderBy('ruang', 'asc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->ruang . "</option>";
        }
        
    }
    function listsatuan()
    {
        $tampil = Satuan::get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->satuan . "</option>";
        }
        
    }

    public function kirimsyarat(Request $request)
    {
        session([
            'tgltransaksi1' => $request['tgltransaksi1'],
            'idjenisbiayax1' => $request['idjenisbiayax1'],
            'idsupplierx1' => $request['idsupplierx1'],
            'idruangx1' => $request['idruangx1'],
        ]);
    }


}
