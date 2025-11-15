<?php

namespace Modules\Admin01\Http\Controllers\master;

use App\Models\Aplikasi;
use App\Models\Menusub;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;
use Modules\Admin01\Models\Bulan;

class BulanController extends Controller
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
        $remark = 'Halaman ini digunakan untuk menampilkan, menambah, mengubah dan menghapus <b>Bulan</b>.';
        $page = 'admin01::master.bulan';
        $link = '/admin01/master/bulan';
        $subtitle = 'Master';
        $caption = 'Bulan';
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
            'tabel' => Bulan::SimplePaginate($jmlhal)->withQueryString(),
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
        $tahunx1 = $request['tahunx1'];

        $namabulanx =[' ', 'Januari ', 'Februari ', 'Maret ', 'April ', 'Mei ', 'Juni ', 'Juli ', 'Agustus ', 'September ', 'Oktober ', 'Nopember ', 'Desember '];
		$bulanx =[' ', '-01-', '-02-', '-03-', '-04-', '-05-', '-06-', '-07-', '-08-', '-09-', '-10-', '-11-', '-12-'];
		$tglakhirx =[' ', '31', '28', '31', '30', '31', '30', '31', '31', '30', '31', '30', '31'];
        
        $i = 1;    
        while($i <= 12) { 

            if (intval($tahunx1)%4==0){
                $x = intval(substr($bulanx[$i],1,2));
                if($x=='2'){
                    $tgl='29';
                }else{
                    $tgl = $tglakhirx[$i]; 
                }
            }else{
                $tgl = $tglakhirx[$i]; 
            }

            $namabulan=$namabulanx[$i].$tahunx1;
            
            $bulan = substr($bulanx[$i],1,2);
            $bln = $bulanx[$i];
            $tglawal = $tahunx1 . $bln . '01';
            $tglakhir = $tahunx1 . $bln . $tgl;
            
            $data = [
                'bulan' => $bulan,
                'namabulan' => $namabulan,
                'tglawal' => $tglawal,
                'tglakhir' => $tglakhir,
            ];
            Bulan::create($data);
            $i++;
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
        $tahunx1 = session('tahunx1');
        $data = Bulan::whereRaw('right(namabulan,4) = ? ', [$tahunx1])
            ->get();
        return json_encode(array('data' => $data));        
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $data = Bulan::where('id', '=', $id)->get();
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
        $data = Bulan::where('id', '=', $id)->delete();
        return json_encode(array('data' => $data));
    }

    public function kirimsyarat(Request $request)
    {
        $tahunx1 = $request['tahunx1'];
        session([
            'tahunx1' => $tahunx1,
        ]);
    }
}
