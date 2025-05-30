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
use Modules\Pos01\Models\Bmasuk;
use Modules\Pos01\Models\Hutang;
use Modules\Pos01\Models\Jenispembayaran;
use Modules\Pos01\Models\Ruang;
use Modules\Pos01\Models\Satuan;
use Modules\Pos01\Models\Seksi;
use Modules\Pos01\Models\Stok;
use Modules\Pos01\Models\Stokfifo;
use Modules\Pos01\Models\Stoklifo;
use Modules\Pos01\Models\Stokmova;
use Modules\Pos01\Models\Supplier;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class BmasukController extends Controller
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
        $remark = 'Halaman ini digunakan untuk menampilkan, menambah, mengubah dan menghapus <b>Barang Masuk</b>.';
        $page = 'pos01::transaksi.bmasuk';
        $link = '/pos01/transaksi/bmasuk';
        $subtitle = 'Transaksi';
        $caption = 'Barang Masuk';
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
            'tabel' => Bmasuk::SimplePaginate($jmlhal)->withQueryString(),
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

        $nb = explode(".", $request['nomorbuktia1']); ;
        $nomorba1 = $nb[3];

        $validatedData = $request->validate([
            'idbarang1' => 'required',
            'idruang1' => 'required',
            'nomorbuktia1' => 'required',
            'tgltransaksi1' => 'required',
        ]);
        
        //untuk input tabel yang asli

        $data = [
            'tgltransaksi' => $validatedData['tgltransaksi1'],
            'nomorbuktia' => $validatedData['nomorbuktia1'],
            'nomorba' => $nomorba1,
            
            'idbarang' => $validatedData['idbarang1'],
            'idruang' => $validatedData['idruang1'],
            'nomorbuktib' => $request['nomorbuktib1'],
            'idsupplier' => $request['idsupplier1'],
            
            'qty' => $request['qty1'],
            'hbs' => $request['hbs1'],
            'hpp' => $request['hpp1'],
            'ppn' => $request['ppn1'],
            'diskon' => $request['diskon1'],
            'ppnpersen' => $request['ppnpersen1'],
            'diskonpersen' => $request['diskonpersen1'],
            'email' => auth()->user()->email,
            'iduser' => auth()->user()->id,
            'keterangan' => $request['keterangan1'],
        ];

        if ($id == '0') {
            Bmasuk::create($data);
        } else {
            Bmasuk::where('id', '=', $id)->update($data);
        }
        return json_encode(array('data' => $data));
    }

    public function posting(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $created_at1 = date('Y-m-d  H:i:s'); 
        $status1 = 'masuk';

        $tgltransaksi1 = $request['tgltransaksi1'];
        $nomorbuktia1 = $request['nomorbuktia1'];
        $tglposting1 = $request['tglposting1'];
        $nomorposting1 = $request['nomorposting1'];

        $np = explode(".", $request['nomorposting1']);
        $nomorp1 = intval($np[3]);

        $validatedData = $request->validate([
            'tgltransaksi1' => 'required',
            'nomorbuktia1' => 'required',
            'tglposting1' => 'required',
            'nomorposting1' => 'required',
        ]);

        $tampil = Bmasuk::with(['barang','ruang']) 
            ->where('tgltransaksi','=',  $validatedData['tgltransaksi1'])            
            ->where('nomorbuktia','=',  $validatedData['nomorbuktia1'])
            ->get();

        foreach ($tampil as $baris) {
            $idbarang1 = $baris->idbarang;
            $idruang1 = $baris->idruang;
            $idseksi1 = $baris->ruang->idseksi;
            $idstatus1 = $baris->id;
            $tgltransaksi1 = $baris->tgltransaksi;
            $nomorbuktia1 = $baris->nomorbuktia;
            $qty1 = $baris->qty;
            $hbs1 = $baris->hbs;
            $hpp1 = $baris->hpp;
            $ppn1 = $baris->ppn;
            $diskon1 = $baris->diskon;
            $email1 = $baris->email;
            $keterangan1 = $baris->keterangan;
            $iduser1 = $baris->iduser;

            //2.barangruang
            $qty2 = Barangruang::where('idbarang','=',$idbarang1)
            ->where('idruang','=',$idruang1)->count();

            if($qty2==1){
                $tampil2 = Barangruang::where('idbarang','=',$idbarang1)
                ->where('idruang','=',$idruang1)->get();
                foreach ($tampil2 as $baris) {
                    $qtyx = $baris->qty;
                }
                $data2 = [
                    'qty' => $qtyx+$qty1,
                ];
                Barangruang::where('idbarang','=',$idbarang1)
                    ->where('idruang','=',$idruang1)->update($data2);
            }

            //3.barang
            $qty2 = Barang::where('id','=',$idbarang1)->count();
            if($qty2==1){                
                $data2 = [
                    'hbs' => $hbs1,
                ];
                Barang::where('id','=',$idbarang1)->update($data2);
            }
            //4.stok
            $qty2 = Stok::where('idbarang','=',$idbarang1)
            ->where('idruang','=',$idruang1)->count();

            if($qty2<>'0'){
                $tampil2 = stok::limit(1)
                ->where('idbarang','=',$idbarang1)
                ->where('idruang','=',$idruang1)
                ->where('created_at','<',$created_at1)
                ->orderBy('created_at','desc')
                ->get();
                
                foreach ($tampil2 as $baris) {
                    $awalx = $baris->akhir;
                    $hbsawalx = $baris->hbsakhir;
                    $hppawalx = $baris->hppakhir;
                }
                $akhirx = $awalx + $qty1;                
                $hppakhirx = $akhirx*$hbs1; 

                $data2 = [
                    'idbarang' => $idbarang1,
                    'idanggota' => 0,
                    'idseksi' => $idseksi1,
                    'idruang' => $idruang1,
                    'nama' => 'UMUM',
                    'status' => $status1,
                    'tglstatus' => $tgltransaksi1,
                    'nomorstatus' => $nomorbuktia1,
                    'idstatus' => $idstatus1,
                    'created_at' => $created_at1,
                    'tglposting' => $validatedData['tglposting1'],
                    'nomorp' => $nomorp1,
                    'nomorposting' => $validatedData['nomorposting1'],
                    'awal' => $awalx,
                    'hbsawal' => $hbsawalx,
                    'hppawal' => $hppawalx,
                    'masuk' => $qty1,
                    'hbsmasuk' => $hbs1,
                    'hppmasuk' => $hpp1,
                    'ppnmasuk' => $ppn1,
                    'diskonmasuk' => $diskon1,
                    'akhir' => $akhirx,
                    'hbsakhir' => $hbs1,
                    'hppakhir' => $hppakhirx,
                    'email' => $email1,
                    'iduser' => $iduser1,
                    'keterangan' => $keterangan1,
                ];
                Stok::create($data2);
            }else{
                $data2 = [
                    'idbarang' => $idbarang1,
                    'idanggota' => 0,
                    'idseksi' => $idseksi1,
                    'idruang' => $idruang1,
                    'nama' => 'UMUM',
                    'status' => $status1,
                    'tglstatus' => $tgltransaksi1,
                    'nomorstatus' => $nomorbuktia1,
                    'idstatus' => $idstatus1,
                    'created_at' => $created_at1,
                    'tglposting' => $validatedData['tglposting1'],
                    'nomorp' => $nomorp1,
                    'nomorposting' => $validatedData['nomorposting1'],
                    'awal' => 0,
                    'hbsawal' => 0,
                    'hppawal' => 0,
                    'masuk' => $qty1,
                    'hbsmasuk' => $hbs1,
                    'hppmasuk' => $hpp1,
                    'ppnmasuk' => $ppn1,
                    'diskonmasuk' => $diskon1,
                    'akhir' => $qty1,
                    'hbsakhir' => $hbs1,
                    'hppakhir' => $hpp1,
                    'email' => $email1,
                    'iduser' => $iduser1,
                    'keterangan' => $keterangan1,
                ];
                Stok::create($data2);  
            }

            //5.stokmova
            $qty2 = Stokmova::where('idbarang','=',$idbarang1)
            ->where('idruang','=',$idruang1)->count();

            if($qty2<>'0'){
                $tampil2 = Stokmova::limit(1)
                ->where('idbarang','=',$idbarang1)
                ->where('idruang','=',$idruang1)
                ->where('created_at','<',$created_at1)
                ->orderBy('created_at','desc')
                ->get();
                
                foreach ($tampil2 as $baris) {
                    $awalx = $baris->akhir;
                    $hbsawalx = $baris->hbsakhir;
                    $hppawalx = $baris->hppakhir;
                }
                $masukx = $qty1;
                $hbsmasukx = $hbs1; 
                $hppmasukx = $hpp1; 
                
                $akhirx = $qty1+$awalx;
                $hbsakhirx = ($hppawalx+$hpp1)/$akhirx; 
                $hppakhirx = $akhirx*$hbsakhirx; 

                $data2 = [
                    'idbarang' => $idbarang1,
                    'idanggota' => 0,
                    'idseksi' => $idseksi1,
                    'idruang' => $idruang1,
                    'nama' => 'UMUM',
                    'status' => $status1,
                    'tglstatus' => $tgltransaksi1,
                    'nomorstatus' => $nomorbuktia1,
                    'idstatus' => $idstatus1,
                    'created_at' => $created_at1,
                    'tglposting' => $validatedData['tglposting1'],
                    'nomorp' => $nomorp1,
                    'nomorposting' => $validatedData['nomorposting1'],
                    'awal' => $awalx,
                    'hbsawal' => $hbsawalx,
                    'hppawal' => $hppawalx,
                    'masuk' => $masukx,
                    'hbsmasuk' => $hbsmasukx,
                    'hppmasuk' => $hppmasukx,
                    'ppnmasuk' => $ppn1,
                    'diskonmasuk' => $diskon1,
                    'akhir' => $akhirx,
                    'hbsakhir' => $hbsakhirx,
                    'hppakhir' => $hppakhirx,
                    'email' => $email1,
                    'iduser' => $iduser1,
                    'keterangan' => $keterangan1,
                ];
                Stokmova::create($data2);
            }else{
                $data2 = [
                    'idbarang' => $idbarang1,
                    'idanggota' => 0,
                    'idseksi' => $idseksi1,
                    'idruang' => $idruang1,
                    'nama' => 'UMUM',
                    'status' => $status1,
                    'tglstatus' => $tgltransaksi1,
                    'nomorstatus' => $nomorbuktia1,
                    'idstatus' => $idstatus1,
                    'created_at' => $created_at1,
                    'tglposting' => $validatedData['tglposting1'],
                    'nomorp' => $nomorp1,
                    'nomorposting' => $validatedData['nomorposting1'],
                    'awal' => 0,
                    'hbsawal' => 0,
                    'hppawal' => 0,
                    'masuk' => $qty1,
                    'hbsmasuk' => $hbs1,
                    'hppmasuk' => $hpp1,
                    'ppnmasuk' => $ppn1,
                    'diskonmasuk' => $diskon1,
                    'akhir' => $qty1,
                    'hbsakhir' => $hbs1,
                    'hppakhir' => $hpp1,
                    'email' => $email1,
                    'iduser' => $iduser1,
                    'keterangan' => $keterangan1,
                ];
                Stokmova::create($data2);  
            }
            
            //5.stokfifo
            $qty2 = Stokfifo::where('idbarang','=',$idbarang1)
            ->where('idruang','=',$idruang1)
            ->where('created_at','<=',$created_at1)
            ->where('kodepokok','=','1')
            ->count();

            if($qty2<>'0'){
                $tampil2 = Stokfifo::where('idbarang','=',$idbarang1)
                ->where('idruang','=',$idruang1)
                ->where('created_at','<=',$created_at1)
                ->where('kodepokok','=','1')
                ->orderBy('created_at','asc')
                ->get();
                
                $awalx = 0;
                $hbsawalx = 0;
                $hppawalx = 0;

                foreach ($tampil2 as $baris) {
                    $awalx = $awalx + $baris->pokok;
                    $hppawalx = $hppawalx + $baris->hpppokok;
                }
                
                $hbsawalx = $hppawalx/$awalx;
                $masukx = $qty1;
                $hbsmasukx = $hbs1; 
                $hppmasukx = $hpp1; 
                
                $akhirx = $qty1+$awalx;
                $hppakhirx = $hpp1+$hppawalx; 
                $hbsakhirx = $hppakhirx/$akhirx; 

                $data2 = [
                    'idbarang' => $idbarang1,
                    'idanggota' => 0,
                    'idseksi' => $idseksi1,
                    'idruang' => $idruang1,
                    'nama' => 'UMUM',
                    'status' => $status1,
                    'tglstatus' => $tgltransaksi1,
                    'nomorstatus' => $nomorbuktia1,
                    'idstatus' => $idstatus1,
                    'created_at' => $created_at1,
                    'tglposting' => $validatedData['tglposting1'],
                    'nomorp' => $nomorp1,
                    'nomorposting' => $validatedData['nomorposting1'],
                    'awal' => $awalx,
                    'hbsawal' => $hbsawalx,
                    'hppawal' => $hppawalx,
                    'masuk' => $masukx,
                    'hbsmasuk' => $hbsmasukx,
                    'hppmasuk' => $hppmasukx,
                    'ppnmasuk' => $ppn1,
                    'diskonmasuk' => $diskon1,
                    'akhir' => $akhirx,
                    'hbsakhir' => $hbsakhirx,
                    'hppakhir' => $hppakhirx,
                    'email' => $email1,
                    'iduser' => $iduser1,
                    'keterangan' => $keterangan1,
                    'kodepokok' => 2,
                ];
                Stokfifo::create($data2);

                $data3 = [
                    'idbarang' => $idbarang1,
                    'idanggota' => 0,
                    'idseksi' => $idseksi1,
                    'idruang' => $idruang1,
                    'nama' => 'UMUM',
                    'status' => $status1,
                    'tglstatus' => $tgltransaksi1,
                    'nomorstatus' => $nomorbuktia1,
                    'idstatus' => $idstatus1,
                    'created_at' => $created_at1,
                    'tglposting' => $validatedData['tglposting1'],
                    'nomorp' => $nomorp1,
                    'nomorposting' => $validatedData['nomorposting1'],                    
                    'pokok' => $qty1,
                    'hbspokok' => $hbs1,
                    'hpppokok' => $hpp1,
                    'email' => $email1,
                    'iduser' => $iduser1,
                    'kodepokok' => 1,
                ];
                Stokfifo::create($data3);

            }else{
                $data2 = [
                    'idbarang' => $idbarang1,
                    'idanggota' => 0,
                    'idseksi' => $idseksi1,
                    'idruang' => $idruang1,
                    'nama' => 'UMUM',
                    'status' => $status1,
                    'tglstatus' => $tgltransaksi1,
                    'nomorstatus' => $nomorbuktia1,
                    'idstatus' => $idstatus1,
                    'created_at' => $created_at1,
                    'tglposting' => $validatedData['tglposting1'],
                    'nomorp' => $nomorp1,
                    'nomorposting' => $validatedData['nomorposting1'],
                    'awal' => 0,
                    'hbsawal' => 0,
                    'hppawal' => 0,
                    'masuk' => $qty1,
                    'hbsmasuk' => $hbs1,
                    'hppmasuk' => $hpp1,
                    'ppnmasuk' => $ppn1,
                    'diskonmasuk' => $diskon1,
                    'akhir' => $qty1,
                    'hbsakhir' => $hbs1,
                    'hppakhir' => $hpp1,
                    'email' => $email1,
                    'iduser' => $iduser1,
                    'keterangan' => $keterangan1,
                    'kodepokok' => 2,
                ];
                Stokfifo::create($data2);
                
                $data3 = [
                    'idbarang' => $idbarang1,
                    'idanggota' => 0,
                    'idseksi' => $idseksi1,
                    'idruang' => $idruang1,
                    'nama' => 'UMUM',
                    'status' => $status1,
                    'tglstatus' => $tgltransaksi1,
                    'nomorstatus' => $nomorbuktia1,
                    'idstatus' => $idstatus1,
                    'created_at' => $created_at1,
                    'tglposting' => $validatedData['tglposting1'],
                    'nomorp' => $nomorp1,
                    'nomorposting' => $validatedData['nomorposting1'],                    
                    'pokok' => $qty1,
                    'hbspokok' => $hbs1,
                    'hpppokok' => $hpp1,
                    'email' => $email1,
                    'iduser' => $iduser1,
                    'kodepokok' => 1,
                ];
                Stokfifo::create($data3);

            }
            //5.stoklifo
            $qty2 = Stoklifo::where('idbarang','=',$idbarang1)
            ->where('idruang','=',$idruang1)
            ->where('created_at','<=',$created_at1)
            ->where('kodepokok','=','1')
            ->count();

            if($qty2<>'0'){
                $tampil2 = Stoklifo::where('idbarang','=',$idbarang1)
                ->where('idruang','=',$idruang1)
                ->where('created_at','<=',$created_at1)
                ->where('kodepokok','=','1')
                ->orderBy('created_at','desc')
                ->get();
                
                $awalx = 0;
                $hbsawalx = 0;
                $hppawalx = 0;

                foreach ($tampil2 as $baris) {
                    $awalx = $awalx + $baris->pokok;
                    $hppawalx = $hppawalx + $baris->hpppokok;
                }
                
                $hbsawalx = $hppawalx/$awalx;
                $masukx = $qty1;
                $hbsmasukx = $hbs1; 
                $hppmasukx = $hpp1; 
                
                $akhirx = $qty1+$awalx;
                $hppakhirx = $hpp1+$hppawalx; 
                $hbsakhirx = $hppakhirx/$akhirx; 

                $data2 = [
                    'idbarang' => $idbarang1,
                    'idanggota' => 0,
                    'idseksi' => $idseksi1,
                    'idruang' => $idruang1,
                    'nama' => 'UMUM',
                    'status' => $status1,
                    'tglstatus' => $tgltransaksi1,
                    'nomorstatus' => $nomorbuktia1,
                    'idstatus' => $idstatus1,
                    'created_at' => $created_at1,
                    'tglposting' => $validatedData['tglposting1'],
                    'nomorp' => $nomorp1,
                    'nomorposting' => $validatedData['nomorposting1'],
                    'awal' => $awalx,
                    'hbsawal' => $hbsawalx,
                    'hppawal' => $hppawalx,
                    'masuk' => $masukx,
                    'hbsmasuk' => $hbsmasukx,
                    'hppmasuk' => $hppmasukx,
                    'ppnmasuk' => $ppn1,
                    'diskonmasuk' => $diskon1,
                    'akhir' => $akhirx,
                    'hbsakhir' => $hbsakhirx,
                    'hppakhir' => $hppakhirx,
                    'email' => $email1,
                    'iduser' => $iduser1,
                    'keterangan' => $keterangan1,
                    'kodepokok' => 2,
                ];
                Stoklifo::create($data2);

                $data3 = [
                    'idbarang' => $idbarang1,
                    'idanggota' => 0,
                    'idseksi' => $idseksi1,
                    'idruang' => $idruang1,
                    'nama' => 'UMUM',
                    'status' => $status1,
                    'tglstatus' => $tgltransaksi1,
                    'nomorstatus' => $nomorbuktia1,
                    'idstatus' => $idstatus1,
                    'created_at' => $created_at1,
                    'tglposting' => $validatedData['tglposting1'],
                    'nomorp' => $nomorp1,
                    'nomorposting' => $validatedData['nomorposting1'],                    
                    'pokok' => $qty1,
                    'hbspokok' => $hbs1,
                    'hpppokok' => $hpp1,
                    'email' => $email1,
                    'iduser' => $iduser1,
                    'kodepokok' => 1,
                ];
                Stoklifo::create($data3);

            }else{
                $data2 = [
                    'idbarang' => $idbarang1,
                    'idanggota' => 0,
                    'idseksi' => $idseksi1,
                    'idruang' => $idruang1,
                    'nama' => 'UMUM',
                    'status' => $status1,
                    'tglstatus' => $tgltransaksi1,
                    'nomorstatus' => $nomorbuktia1,
                    'idstatus' => $idstatus1,
                    'created_at' => $created_at1,
                    'tglposting' => $validatedData['tglposting1'],
                    'nomorp' => $nomorp1,
                    'nomorposting' => $validatedData['nomorposting1'],
                    'awal' => 0,
                    'hbsawal' => 0,
                    'hppawal' => 0,
                    'masuk' => $qty1,
                    'hbsmasuk' => $hbs1,
                    'hppmasuk' => $hpp1,
                    'ppnmasuk' => $ppn1,
                    'diskonmasuk' => $diskon1,
                    'akhir' => $qty1,
                    'hbsakhir' => $hbs1,
                    'hppakhir' => $hpp1,
                    'email' => $email1,
                    'iduser' => $iduser1,
                    'keterangan' => $keterangan1,
                    'kodepokok' => 2,
                ];
                Stoklifo::create($data2);
                
                $data3 = [
                    'idbarang' => $idbarang1,
                    'idanggota' => 0,
                    'idseksi' => $idseksi1,
                    'idruang' => $idruang1,
                    'nama' => 'UMUM',
                    'status' => $status1,
                    'tglstatus' => $tgltransaksi1,
                    'nomorstatus' => $nomorbuktia1,
                    'idstatus' => $idstatus1,
                    'created_at' => $created_at1,
                    'tglposting' => $validatedData['tglposting1'],
                    'nomorp' => $nomorp1,
                    'nomorposting' => $validatedData['nomorposting1'],                    
                    'pokok' => $qty1,
                    'hbspokok' => $hbs1,
                    'hpppokok' => $hpp1,
                    'email' => $email1,
                    'iduser' => $iduser1,
                    'kodepokok' => 1,
                ];
                Stoklifo::create($data3);

            }

            //6.update bmasuk
            $data = [
                'tglposting' => $validatedData['tglposting1'],
                'nomorposting' => $validatedData['nomorposting1'],                        
            ];
                 
            Bmasuk::with(['barang','ruang']) 
            ->where('tgltransaksi','=', $tgltransaksi1)            
            ->where('nomorbuktia','=', $nomorbuktia1)
            ->update($data);

        }

            

        // return json_encode('data');
    }

    public function proses(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $created_at1 = date('Y-m-d  H:i:s'); 
        $tglsekarang = date('Y-m-d');
        $status1 = 'hutangsup';

        $subtotals1 = $request['subtotals1']; 
        $ppns1 = $request['ppns1'];
        $diskons1 = $request['diskons1'];
        $totals1 = $request['totals1'];
        $bayars1 = $request['bayars1'];
        $vouchers1 = $request['vouchers1'];
        $kembalis1 = $request['kembalis1'];
        $idjenispembayaran1 = $request['idjenispembayaran1'];
        $nomorpostingnya1 = $request['nomorpostingnya1'];
        $tglpostingnya1 = $request['tglpostingnya1'];
        $nomorbuktia1 = $request['nomorbuktia1'];
        $tgltransaksi1 = $request['tgltransaksi1'];
        $idsupplier1 = $request['idsupplier1'];
        $xangsuran1 = $request['kali1'];
        $persenjasa1 = $request['persenjasa1'];
        $nilaihutang1 = $request['nilaihutang1'];

        // $np = explode(".", $request['$nomorpostingnya1']);
        // $nomorp1 = intval($np[3]);
        $tampil = Stok::where('nomorstatus','=',$nomorbuktia1)->get();
        foreach ($tampil as $baris) {
             $nomorp1 = $baris->nomorp;
        }
            
        if($tgltransaksi1 == $tglsekarang){
            //update stok, stokfifo, stoklifo, updatemova

            $data = [
                'subtotals' => $subtotals1,
                'ppns' => $ppns1,
                'diskons' => $diskons1,
                'totals' => $totals1,
                'bayars' => $bayars1,
                'vouchers' => $vouchers1,
                'kembalis' => $kembalis1,
                'idjenispembayaran' => $idjenispembayaran1,                
                'xangsuran' => $xangsuran1,                
            ]; 
            $tampil = Stok::where('nomorstatus','=',$nomorbuktia1)->count();
            if($tampil<>'0'){
                Stok::where('nomorstatus','=',$nomorbuktia1)->update($data);
            }
            $tampil = Stokfifo::where('nomorstatus','=',$nomorbuktia1)->count();
            if($tampil<>'0'){
                Stokfifo::where('nomorstatus','=',$nomorbuktia1)->update($data);
            }
            $tampil = Stoklifo::where('nomorstatus','=',$nomorbuktia1)->count();
            if($tampil<>'0'){
                Stoklifo::where('nomorstatus','=',$nomorbuktia1)->update($data);
            }
            $tampil = Stokmova::where('nomorstatus','=',$nomorbuktia1)->count();
            if($tampil<>'0'){
                Stokmova::where('nomorstatus','=',$nomorbuktia1)->update($data);
            }

            //hutang
            if($idjenispembayaran1<>'99'){
                Hutang::where('idsupplier','=',$idsupplier1)
                ->where('nomorstatus','=',$nomorbuktia1)
                ->delete();
            }else{
                $tampil = Hutang::where('idsupplier','=',$idsupplier1)
                ->where('kodepokok','=',2)
                ->where('created_at','<',$created_at1)
                ->count(); 
                if($tampil<>'0'){
                    $tampil1 = Hutang::where('idsupplier','=',$idsupplier1)
                        ->where('kodepokok','=',2)
                        ->where('created_at','<',$created_at1)
                        ->orderBy('created_at','desc')        
                        ->get();
                    foreach ($tampil1 as $baris) {
                        $awalx = $baris->awal;
                        $akhirx = $baris->akhir;
                        $nomorbuktix = $baris->nomorstatus;
                    }
                    if($nomorbuktix==$nomorbuktia1){
                        $data = [
                            'kodepokok' => 2,
                            'xangsuran' => $xangsuran1,
                            'created_at' => $created_at1,
                            'nomorp' => $nomorp1,
                            'tglposting' => $tglpostingnya1,
                            'nomorposting' => $nomorpostingnya1,
                            'masuk' => $nilaihutang1,
                            'akhir' => $awalx + $nilaihutang1,
                            'totalori' => $totals1,
                            'persenjasa' => $persenjasa1,
                            'email' => auth()->user()->email,
                            'iduser' => auth()->user()->id,                                
                        ]; 
                        Hutang::where('idsupplier','=',$idsupplier1)
                        ->where('kodepokok','=',2)
                        ->where('nomorstatus','=',$nomorbuktia1)
                        ->update($data);
                    }else{
                        $data = [
                            'kodepokok' => 2,
                            'idsupplier' => $idsupplier1,
                            'tglstatus' => $tgltransaksi1,
                            'nomorp' => $nomorp1,
                            'nomorstatus' => $nomorbuktia1,
                            'status' => $status1,
                            'tglposting' => $tglpostingnya1,
                            'nomorposting' => $nomorpostingnya1,
                            'xangsuran' => $xangsuran1,
                            'created_at' => $created_at1,
                            'awal' => $awalx,
                            'masuk' => $nilaihutang1,
                            'akhir' => $awalx + $nilaihutang1,
                            'totalori' => $totals1,
                            'persenjasa' => $persenjasa1,
                            'email' => auth()->user()->email,
                            'iduser' => auth()->user()->id,                                
                        ];
                        Hutang::create($data);
                    }

                }else{                
                    $data = [
                        'kodepokok' => 2,
                        'idsupplier' => $idsupplier1,
                        'tglstatus' => $tgltransaksi1,
                        'nomorp' => $nomorp1,
                        'nomorstatus' => $nomorbuktia1,
                        'status' => $status1,
                        'tglposting' => $tglpostingnya1,
                        'nomorposting' => $nomorpostingnya1,
                        'xangsuran' => $xangsuran1,
                        'created_at' => $created_at1,
                        'awal' => 0,
                        'masuk' => $nilaihutang1,
                        'akhir' => 0 + $nilaihutang1,
                        'totalori' => $totals1,
                        'persenjasa' => $persenjasa1,
                        'email' => auth()->user()->email,
                        'iduser' => auth()->user()->id,                               
                    ];
                    Hutang::create($data);
                }
                $tampil = Hutang::where('idsupplier','=',$idsupplier1)
                ->where('kodepokok','=',1)
                ->where('nomorstatus','=',$nomorbuktia1)
                ->count();
                $data = [
                    'kodepokok' => 1,
                    'idsupplier' => $idsupplier1,
                    'tglstatus' => $tgltransaksi1,
                    'nomorp' => $nomorp1,
                    'nomorstatus' => $nomorbuktia1,
                    'status' => $status1,
                    'tglposting' => $tglpostingnya1,
                    'nomorposting' => $nomorpostingnya1,
                    'xangsuran' => $xangsuran1,
                    'created_at' => $created_at1,
                    'asli' => $nilaihutang1,
                    'pokok' => $nilaihutang1,
                    'totalori' => $totals1,
                    'persenjasa' => $persenjasa1,
                    'email' => auth()->user()->email,
                    'iduser' => auth()->user()->id,
                ];
                if($tampil<>'0'){
                    Hutang::where('idsupplier','=',$idsupplier1)
                    ->where('kodepokok','=',1)
                    ->where('nomorstatus','=',$nomorbuktia1)->update($data);
                }else{
                    Hutang::create($data); 
                }
            }

            
        }else{
            //
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
        
        $tgltransaksi1 = session('tgltransaksi1');    
        $nomorbuktia1 = session('nomorbuktia1');    
        $nomorbuktib1 = session('nomorbuktib1');    

        $data = Bmasuk::with(['barang','ruang'])            
            ->where('tgltransaksi','=', $tgltransaksi1)            
            ->where('nomorbuktia','=', $nomorbuktia1)            
            ->get();
        return json_encode(array('data' => $data));

    }

    public function showbarang()
    {
        $barangruang = Barangruang::with('barang','seksi','ruang')->get();
        $datax = DataTables::of($barangruang                          
            );

        $data = $datax
            ->addIndexColumn()
           
            ->addColumn('nabara', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->idbarang ? $row->barang->nabara : '-') .'" class="item_nabara " data1="' . $row->idbarang . '" data2="'. $row->barang->kode. '" data3="'. $row->barang->barcode. '" data4="'. $row->barang->hbs. '" data5="'. $row->barang->hjs. '" data6="'. $row->idruang. '" data7="'. $row->barang->ppnbeli. '" data8="'. $row->barang->diskonbeli. '" data9="'. $row->barang->ppnjual. '" data10="'. $row->barang->diskonjual. '">'.($row->idbarang ? $row->barang->nabara : '-').'</a> ';
            })
            ->addColumn('kode', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->idbarang ? $row->barang->kode : '-') .'" class="item_kode " data1="' . $row->idbarang . '" data2="'. $row->barang->kode. '" data3="'. $row->barang->barcode. '" data4="'. $row->barang->hbs. '" data5="'. $row->barang->hjs. '" data6="'. $row->idruang. '" data7="'. $row->barang->ppnbeli. '" data8="'. $row->barang->diskonbeli. '" data9="'. $row->barang->ppnjual. '" data10="'. $row->barang->diskonjual. '">'.($row->idbarang ? $row->barang->kode : '-').'</a> ';
            })
            ->addColumn('barcode', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->idbarang ? $row->barang->barcode : '-') .'" class="item_barcode " data1="' . $row->idbarang . '" data2="'. $row->barang->kode. '" data3="'. $row->barang->barcode. '" data4="'. $row->barang->hbs. '" data5="'. $row->barang->hjs. '" data6="'. $row->idruang. '" data7="'. $row->barang->ppnbeli. '" data8="'. $row->barang->diskonbeli. '" data9="'. $row->barang->ppnjual. '" data10="'. $row->barang->diskonjual. '">'.($row->idbarang ? $row->barang->barcode : '-').'</a> ';
            })
            
            ->addColumn('hbs', function ($row) {
                return $row->idbarang ? number_format($row->barang->hbs,0) : '';
            })
            ->addColumn('hjs', function ($row) {
                return $row->idbarang ? number_format($row->barang->hjs,0) : '';
            })
            ->addColumn('satuan', function ($row) {
                return $row->idbarang ? $row->barang->satuan->kode : '';
            })
            ->addColumn('ruang', function ($row) {
                return $row->idruang ? $row->ruang->ruang : '';
            })
            ->addColumn('seksi', function ($row) {
                return $row->idruang ? $row->ruang->seksi->seksi : '';
            })
            
            ->addColumn('action', function ($row) {
                return '<a href="#" title="Edit Data" class="btn btn-success btn-xs item_edit" data="' . $row->id . '" data2="'. $row->barang->nabara.'" data3="'. $row->barang->kode.'" data4="'. $row->barang->barcode.'"><i style="font-size:18px" class="fa">&#xf044;</i></a> ' .
                       '<a href="#" title="Hapus Data" class="btn btn-danger btn-xs item_hapus" data="' . $row->id . '" data2="'. $row->barang->nabara.'" data3="'. $row->barang->kode.'" data4="'. $row->barang->barcode.'"><i style="font-size:18px" class="fa">&#xf00d;</i></a>';
            })
            
            ->rawColumns([
                'nabara',
                'kode',
                'barcode',
                'hbs',
                'hjs',
                'satuan',
                'ruang',
                'seksi',
                'action'])

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
        $data = Bmasuk::where('id', '=', $id)
            ->with(['barang','ruang'])
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
        $data = Bmasuk::where('id', '=', $id)->delete();
        return json_encode(array('data' => $data));
    }
    function listsupplier()
    {
        $idx = '0';
        $isix = 'UMUM';
        $tampil = Supplier::where('id','<>','0')
            ->orderBy('supplier', 'asc')
            ->get();
            echo "<option value='" . $idx . "'>". $isix . "</option>";
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>". $baris->supplier . "</option>";
        }
    }
    function listjenispembayaran()
    {
        $tampil = Jenispembayaran::where('id','<>','0')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>". $baris->jenispembayaran . "</option>";
        }
    }
    function listbarang()
    {
        $tampil = Barang::orderBy('nabara','asc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>". $baris->nabara . "</option>";
        }
    }
    function listruang()
    {
        $tampil = Ruang::get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>". $baris->ruang . "</option>";
        }
    }
   
    public function nomorbukti(Request $request)
    {
        
        $tgltransaksi1=$request['tgltransaksi1'];
        $tgl1 = Carbon::parse($tgltransaksi1)->format('Ymd');
        $ym1 = Carbon::parse($tgltransaksi1)->format('Ym');
        $d1 = Carbon::parse($tgltransaksi1)->format('d');
        $m1 = Carbon::parse($tgltransaksi1)->format('m');
        $y1 = Carbon::parse($tgltransaksi1)->format('Y');
        
        $jmldata = Bmasuk::where('tgltransaksi','=',$tgltransaksi1)            
            ->count();
        if ($jmldata==0){
            $nomor = 1;    
        }else{
            $nomor = Bmasuk::select('nomorba')
                ->where('tgltransaksi','=',$tgltransaksi1)
                ->max('nomorba')+1;
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
        
        //nomor kwitansi contoh : MSK.010.20230527.0009
        $nbx = 'MSK' . '.' . $idx . '.' . $tgl1 . '.' . $no;  
        $nomorbukti = "'$nbx'";

        $data = Satuan::limit(1)
                ->selectRaw($nomorbukti . ' as nomorbuktia')                
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
        
        $jmldata = Stok::where('tglposting','=',$tglposting1)            
            ->count();
        if ($jmldata==0){
            $nomor = 1;    
        }else{
            $nomor = Stok::select('nomorp')
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
        

        //nomor kwitansi contoh : POS.010.20230527.0009
        $nbx = 'POS' . '.' . $idx . '.' . $tgl1 . '.' . $no;  
        $nomorposting = "'$nbx'";

        $data = Satuan::limit(1)
                ->selectRaw($nomorposting . ' as nomorposting')                
                ->get();
         
        return json_encode(array('data' => $data));        
    }

    public function kirimsyarat(Request $request)
    {
        session([
            'tgltransaksi1' => $request['tgltransaksi1'],
            'nomorbuktia1' => $request['nomorbuktia1'],
            'nomorbuktib1' => $request['nomorbuktib1'],
            'idsupplier1' => $request['idsupplier1'],            
        ]);
        
    }

    public function displaypembayaran($id)
    {
        
        $jml = Hutang::where('nomorstatus','=',$id)
            ->where('kodepokok','=','1')
            ->count();
        if($jml <> '0'){
            $tampil = hutang::where('nomorstatus','=',$id)
                ->where('kodepokok','=','1')
                ->get();
            
            foreach ($tampil as $baris) {
                $persenjasa = $baris->persenjasa;
                $asli = $baris->asli;
            }
        }else{
            $persenjasa = 0;
            $asli = 0;
        }

        $tampil = Stok::where('nomorstatus','=',$id)
        ->get();
        $jml = 0;
        foreach ($tampil as $baris) {
            $jml = $jml + $baris->bayars + $baris->vouchers + $baris->ambilsavings;
        }
        
        $data = stok::limit(1)->select('*')
            ->selectRaw('('. $jml .') as jml')
            ->selectRaw('('. $persenjasa .') as persenjasa')
            ->selectRaw('('. $asli .') as nilaihutang')
            ->where('nomorstatus','=',$id)        
            ->orderBy('id','desc')
            ->get();

        return json_encode(array('data' => $data));       
    }

}
