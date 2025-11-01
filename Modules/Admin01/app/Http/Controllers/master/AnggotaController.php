<?php

namespace Modules\Admin01\Http\Controllers\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

use DateTime;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Aplikasi;
use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Storage;
use App\Models\Menusub;
use Modules\Admin01\Models\Anggota;
use Modules\Admin01\Models\Desa;
use Modules\Admin01\Models\Kabupaten;
use Modules\Admin01\Models\Kecamatan;
use Modules\Admin01\Models\Propinsi;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;

class AnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        // $product = new Product;
        // $product->setConnection('mysql_second');
        // $something = $product->find(1);
        // return $something;

        $meminstansi = session('memnamasingkat');
        $remark = 'Halaman ini digunakan untuk menampilkan, menambah, mengubah dan menghapus <b>Anggota</b>.';
        $page = 'admin01::master.anggota';
        $link = '/admin01/master/anggota';
        $subtitle = 'Master';
        $caption = 'Anggota';
        $jmlhal = 2;
       
        $menu=Menusub::where('link','=',$link)
            ->with(['menuutama','aplikasi'])
            ->get();

        $search = $request->input('search');
        $desa = Desa::when($search, function($query,$search){
            $query->where('desa','like','%'. $search . '%');
        })->paginate(10);

        // $tabelx = Desa::with(['kecamatan'])->where('idkecamatan','3737')->get();
        return view($page, [
            'title' => $meminstansi . ' | ' . $subtitle . ' | ' . $caption,
            'subtitle' => $subtitle,
            'caption' => $caption,
            'link' => $link,
            'remark' => $remark,
            'jmlhal' => $jmlhal,
            'tabelx' => $desa,
            'tabel' => Anggota::SimplePaginate($jmlhal)->withQueryString(),
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

        $id = $request['id1'];
        $niax1 = intval(substr($request['nia1'],8,4));

        $tampil = Anggota::where('ID', '=', $id)->get();
        foreach ($tampil as $item) {            
            //images
            $img_foto = $item->img_foto;
            $img_ktp = $item->img_ktp;
            $img_kk = $item->img_kk;
            $img_bukunikah = $item->img_bukunikah;
            $img_nomorbuktiud = $item->img_nomorbuktiud;
        }

        //cek img_foto
        if ($id == '0') {
            if ($request->hasFile('img_foto1')) {
                $validateimg_foto = $request->validate([
                    'img_foto1' => 'file|max:2048',
                ]);
                if ($validateimg_foto) {
                    $img_foto1 = $request->file('img_foto1')->store('adm01-anggota-img_fotos');
                } else {
                    $img_foto1 = '';
                }
            } else {
                $img_foto1 = '';
            }
            
        } else {
            if ($request->hasFile('img_foto1')) {
                $validateimg_foto = $request->validate([
                    'img_foto1' => 'file|max:2048',
                ]);
                if ($validateimg_foto) {
                    if ($img_foto) {
                        storage::delete($img_foto);
                        $img_foto1 = $request->file('img_foto1')->store('adm01-anggota-img_fotos');
                    } else {
                        $img_foto1 = $request->file('img_foto1')->store('adm01-anggota-img_fotos');
                    }
                } else {
                    $img_foto1 = $img_foto;
                }
            } else {
                $img_foto1 = $img_foto;
            }
        }

        //cek img_ktp
        if ($id == '0') {
            if ($request->hasFile('img_ktp1')) {
                $validateimg_ktp = $request->validate([
                    'img_ktp1' => 'file|max:2048',
                ]);
                if ($validateimg_ktp) {
                    $img_ktp1 = $request->file('img_ktp1')->store('adm01-anggota-img_ktps');
                } else {
                    $img_ktp1 = '';
                }
            } else {
                $img_ktp1 = '';
            }
            
        } else {
            if ($request->hasFile('img_ktp1')) {
                $validateimg_ktp = $request->validate([
                    'img_ktp1' => 'file|max:2048',
                ]);
                if ($validateimg_ktp) {
                    if ($img_ktp) {
                        storage::delete($img_ktp);
                        $img_ktp1 = $request->file('img_ktp1')->store('adm01-anggota-img_ktps');
                    } else {
                        $img_ktp1 = $request->file('img_ktp1')->store('adm01-anggota-img_ktps');
                    }
                } else {
                    $img_ktp1 = $img_ktp;
                }
            } else {
                $img_ktp1 = $img_ktp;
            }
        }

        //cek img_kk
        if ($id == '0') {
            if ($request->hasFile('img_kk1')) {
                $validateimg_kk = $request->validate([
                    'img_kk1' => 'file|max:2048',
                ]);
                if ($validateimg_kk) {
                    $img_kk1 = $request->file('img_kk1')->store('adm01-anggota-img_kks');
                } else {
                    $img_kk1 = '';
                }
            } else {
                $img_kk1 = '';
            }
            
        } else {
            if ($request->hasFile('img_kk1')) {
                $validateimg_kk = $request->validate([
                    'img_kk1' => 'file|max:2048',
                ]);
                if ($validateimg_kk) {
                    if ($img_kk) {
                        storage::delete($img_kk);
                        $img_kk1 = $request->file('img_kk1')->store('adm01-anggota-img_kks');
                    } else {
                        $img_kk1 = $request->file('img_kk1')->store('adm01-anggota-img_kks');
                    }
                } else {
                    $img_kk1 = $img_kk;
                }
            } else {
                $img_kk1 = $img_kk;
            }
        }

        //cek img_bukunikah
        if ($id == '0') {
            if ($request->hasFile('img_bukunikah1')) {
                $validateimg_bukunikah = $request->validate([
                    'img_bukunikah1' => 'file|max:2048',
                ]);
                if ($validateimg_bukunikah) {
                    $img_bukunikah1 = $request->file('img_bukunikah1')->store('adm01-anggota-img_bukunikahs');
                } else {
                    $img_bukunikah1 = '';
                }
            } else {
                $img_bukunikah1 = '';
            }
            
        } else {
            if ($request->hasFile('img_bukunikah1')) {
                $validateimg_bukunikah = $request->validate([
                    'img_bukunikah1' => 'file|max:2048',
                ]);
                if ($validateimg_bukunikah) {
                    if ($img_bukunikah) {
                        storage::delete($img_bukunikah);
                        $img_bukunikah1 = $request->file('img_bukunikah1')->store('adm01-anggota-img_bukunikahs');
                    } else {
                        $img_bukunikah1 = $request->file('img_bukunikah1')->store('adm01-anggota-img_bukunikahs');
                    }
                } else {
                    $img_bukunikah1 = $img_bukunikah;
                }
            } else {
                $img_bukunikah1 = $img_bukunikah;
            }
        }

        //cek img_nomorbuktiud
        if ($id == '0') {
            if ($request->hasFile('img_nomorbuktiud1')) {
                $validateimg_nomorbuktiud = $request->validate([
                    'img_nomorbuktiud1' => 'file|max:2048',
                ]);
                if ($validateimg_nomorbuktiud) {
                    $img_nomorbuktiud1 = $request->file('img_nomorbuktiud1')->store('adm01-anggota-img_nomorbuktiuds');
                } else {
                    $img_nomorbuktiud1 = '';
                }
            } else {
                $img_nomorbuktiud1 = '';
            }
            
        } else {
            if ($request->hasFile('img_nomorbuktiud1')) {
                $validateimg_nomorbuktiud = $request->validate([
                    'img_nomorbuktiud1' => 'file|max:2048',
                ]);
                if ($validateimg_nomorbuktiud) {
                    if ($img_nomorbuktiud) {
                        storage::delete($img_nomorbuktiud);
                        $img_nomorbuktiud1 = $request->file('img_nomorbuktiud1')->store('adm01-anggota-img_nomorbuktiuds');
                    } else {
                        $img_nomorbuktiud1 = $request->file('img_nomorbuktiud1')->store('adm01-anggota-img_nomorbuktiuds');
                    }
                } else {
                    $img_nomorbuktiud1 = $img_nomorbuktiud;
                }
            } else {
                $img_nomorbuktiud1 = $img_nomorbuktiud;
            }
        }
        
        $validatedData = $request->validate([
            'tgldaftar1' => 'required',
            'nia1' => 'required',
            'nik1' => 'required',
            'ecard1' => 'required',
            'nama1' => 'required',
            'iddesa1' => 'required',
        ]);
        //untuk input tabel yang asli
        
        $data = [            
            'tgldaftar' => $validatedData['tgldaftar1'],
            'nia' => $validatedData['nia1'],
            'nik' => $validatedData['nik1'],
            'ecard' => $validatedData['ecard1'],
            'nama' => $validatedData['nama1'],
            'iddesa' => $validatedData['iddesa1'],
            'niax' => $niax1,
            'tgllahir' => $request['tgllahir1'],
            'tglkeluar' => $request['tglkeluar1'],
            'alamat' => $request['alamat1'],
            'telp' => $request['telp1'],
            'email' => $request['email1'],
            'aktif' => $request['aktif1'],
            'uangdaftar' => $request['uangdaftar1'],
            'nomorbuktiud' => $request['nomorbuktiud1'],
            'img_foto' => $img_foto1,
            'img_ktp' => $img_ktp1,
            'img_kk' => $img_kk1,
            'img_bukunikah' => $img_bukunikah1,
            'img_nomorbuktiud' => $img_nomorbuktiud1,
        ];

        if ($id == '0') {
            Anggota::create($data);
        } else {
            Anggota::where('id', '=', $id)->update($data);
        }

        return json_encode(array('data' => $data));
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
        
        $datax = Anggota::with(['desa'])->get();
        
        $data = DataTables::of($datax)
            ->addIndexColumn()

            ->addColumn('desa', function ($row) {
                return $row->iddesa ? $row->desa->desa : '';
            })
            ->addColumn('kecamatan', function ($row) {
                return $row->iddesa ? $row->desa->kecamatan->kecamatan : '';
            })
            ->addColumn('kabupaten', function ($row) {
                return $row->iddesa ? $row->desa->kecamatan->kabupaten->kabupaten: '';
            })
            ->addColumn('propinsi', function ($row) {
                return $row->iddesa ? $row->desa->kecamatan->kabupaten->propinsi->propinsi: '';
            })

            ->addColumn('img_foto', function ($row) {
                return $row->img_foto ? '<img class="mb-4" style="width: 100%" src="'. route('front.index') . '/storage/'. $row->img_foto.'">' : '';
            })
           
            ->addColumn('img_kk', function ($row) {
                return $row->img_kk ? '<iframe class="mb-4" style="width: 100%; height: auto;" src="'. route('front.index') . '/storage/'. $row->img_kk.'"></iframe>' : '';
            })
            ->addColumn('img_ktp', function ($row) {
                return $row->img_ktp ? '<iframe class="mb-4" style="width: 100%; height: auto;" src="'. route('front.index') . '/storage/'. $row->img_ktp.'"></iframe>' : '';
            })
            ->addColumn('img_bukunikah', function ($row) {
                return $row->img_bukunikah ? '<iframe class="mb-4" style="width: 100%; height: auto;" src="'. route('front.index') . '/storage/'. $row->img_bukunikah.'"></iframe>' : '';
            })
            ->addColumn('img_nomorbuktiud', function ($row) {
                return $row->img_nomorbuktiud ? '<span>'.$row->nomorbuktiud.'</span><iframe class="mb-4" style="width: 100%; height: auto;" src="'. route('front.index') . '/storage/'. $row->img_nomorbuktiud.'"></iframe>' : '';
            })

            ->addColumn('action', function ($row) {
                return '<a href="#" title="Edit Data" class="btn btn-success btn-xs item_edit" data="' . $row->id . '" data2="'. $row->nia.'" data3="'. $row->nik.'" data4="'. $row->ecard.'" data5="'. $row->nama.'" data6="'.$row->desa->desa.'" data7="'.$row->desa->kecamatan->kecamatan.'" data8="'.$row->desa->kecamatan->kabupaten->kabupaten.'" data9="'.$row->desa->kecamatan->kabupaten->propinsi->propinsi.'"><i style="font-size:18px" class="fa">&#xf044;</i></a> ' .
                       '<a href="#" title="Hapus Data" class="btn btn-danger btn-xs item_hapus" data="' . $row->id . '" data2="'. $row->nia.'" data3="'. $row->nik.'" data4="'. $row->ecard.'" data5="'. $row->nama.'"><i style="font-size:18px" class="fa">&#xf00d;</i></a>';
            })
            
            ->rawColumns([
                'desa',
                'kecamatan',
                'kabupaten',
                'propinsi',
                'img_foto',
                'img_kk',
                'img_ktp',
                'img_bukunikah',
                'img_nomorbuktiud',
                'action'])


            ->make(true);

            return $data;

    }

    public function showdesa()
    {
        $idkab = Kabupaten::select('id')->where('idpropinsi','=',session('idpropinsix1'))->get();
        $idkec = Kecamatan::select('id')->whereIn('idkabupaten',$idkab )->get();
        $datax = Desa::whereIn('idkecamatan',$idkec )->with(['kecamatan'])->get();
        
        $data = DataTables::of($datax)
            ->addIndexColumn()
           
            ->addColumn('desa', function ($row) {
                return '<a href="#" style="color: white;" title="'. ($row->desa ? $row->desa : '-') .'" class="item_desa " data1="' . $row->id . '" data2="'. $row->desa. '" data3="'. $row->kecamatan->kecamatan. '" data4="'. $row->kecamatan->kabupaten->kabupaten. '" data5="'. $row->kecamatan->kabupaten->propinsi->propinsi. '">'.($row->desa ? $row->desa : '-').'</a> ';
            })
            
            ->addColumn('kecamatan', function ($row) {
                return $row->idkecamatan ? $row->kecamatan->kecamatan : '';
            })
            ->addColumn('kabupaten', function ($row) {
                return $row->idkecamatan ? $row->kecamatan->kabupaten->kabupaten : '';
            })
            ->addColumn('propinsi', function ($row) {
                return $row->idkecamatan ? $row->kecamatan->kabupaten->propinsi->propinsi : '';
            })
            
            ->addColumn('action', function ($row) {
                return '<a href="#" title="Edit Data" class="btn btn-success btn-xs item_edit" data="' . $row->id . '" data2="'. $row->desa.'" data3="'. $row->kecamatan->kecamatan.'" data4="'. $row->kecamatan->kabupaten->kabupaten.'"><i style="font-size:18px" class="fa">&#xf044;</i></a> ' .
                       '<a href="#" title="Hapus Data" class="btn btn-danger btn-xs item_hapus" data="' . $row->id . '" data2="'. $row->desa.'" data3="'. $row->kecamatan->kecamatan.'" data4="'. $row->kecamatan->kabupaten->kabupaten.'"><i style="font-size:18px" class="fa">&#xf00d;</i></a>';
            })
            
            ->rawColumns([
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
        $data = Anggota::where('id', '=', $id)->with(['desa'])->get();
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
        $tampil = Anggota::where('ID', '=', $id)->get();
        foreach ($tampil as $item) {            
            //images
            $img_foto = $item->img_foto;
            $img_ktp = $item->img_ktp;
            $img_kk = $item->img_kk;
            $img_bukunikah = $item->img_bukunikah;
            $img_nomorbuktiud = $item->img_nomorbuktiud;
        }
        if ($img_foto) {
            storage::delete($img_foto);            
        }
        if ($img_ktp) {
            storage::delete($img_ktp);            
        }
        if ($img_kk) {
            storage::delete($img_kk);            
        }
        if ($img_bukunikah) {
            storage::delete($img_bukunikah);            
        }
        if ($img_nomorbuktiud) {
            storage::delete($img_nomorbuktiud);            
        }

        $data = Anggota::where('id', '=', $id)->delete();
        return json_encode(array('data' => $data));
    }

    public function nia(Request $request)
    {
        
        $tgldaftar1=$request['tgldaftar1'];
        $tgl1 = Carbon::parse($tgldaftar1)->format('Ymd');
        $ym1 = Carbon::parse($tgldaftar1)->format('Ym');
        $d1 = Carbon::parse($tgldaftar1)->format('d');
        $m1 = Carbon::parse($tgldaftar1)->format('m');
        $y1 = Carbon::parse($tgldaftar1)->format('Y');
        
        $jmldata = Anggota::where('tgldaftar','=',$tgldaftar1)            
            ->count();
        if ($jmldata==0){
            $nomor = 1;    
        }else{
            $nomor = Anggota::select('niax')
                ->where('tgldaftar','=',$tgldaftar1)
                ->max('niax')+1;
        }  

        if($nomor<10){
            $no='000'.$nomor;
        }else if($nomor<100){
            $no='00'.$nomor;
        }else if($nomor<1000){
            $no='0'.$nomor;
        }else{
            $no=''.$nomor;
        }
        
        //nia contoh : 202506070001 artinya 2025=tahun daftar, 06=bulan daftar(juni), 07=tgl daftar, 0001 nomor urut daftar
        $ndx = $tgl1 . $no;  
        $nomordaftar = "'$ndx'";

        $data = Aplikasi::limit(1)
                ->selectRaw($nomordaftar . ' as nia')                
                ->get();
         
        return json_encode(array('data' => $data));        
    }


}
