<?php

namespace App\Http\Controllers;

use App\Models\Aplikasi;
use App\Models\Menuutama;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Menusub;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Modules\Admin01\Models\Desa;
use Modules\Admin01\Models\Propinsi;
use Modules\Sis01\src\Entities\Mainmenu;
use RealRashid\SweetAlert\Facades\Alert;

class MenuutamaController extends Controller
{
    public function index(Request $request)
    {

        $meminstansi = session('memnamasingkat');
        $remark = 'Halaman ini digunakan untuk menampilkan, menambah, mengubah dan menghapus <b>Menu Utama</b>.';
        $page = 'admin.dashboards.menuutama';
        $link = '/admin/menuutama';
        $subtitle = 'Setting Menu';
        $caption = 'Menu Utama';
        $jmlhal = 2;

        $search = $request->input('search');
        $desa = Desa::when($search, function($query,$search){
            $query->where('desa','like','%'. $search . '%');
        })->paginate(10);

        return view($page, [
            'title' => $meminstansi . ' | ' . $subtitle . ' | ' . $caption,
            'subtitle' => $subtitle,
            'caption' => $caption,
            'link' => $link,
            'remark' => $remark,
            'jmlhal' => $jmlhal,
            'tabel' => Menuutama::SimplePaginate($jmlhal)->withQueryString(),
            'tabelx' => $desa,
            'aplikasi' => Aplikasi::get()
        ]);
    }

    public function show()
    {
        $data = Menuutama::get();

        return json_encode(array('data' => $data));
    }

    public function create(Request $request)
    {
        $id = $request['id1'];

        $namamenu = $request['menu1'];
        $slug = Str::slug($request['menu1']);

        $validatedData = $request->validate([
            'menu1' => 'required',
            'aktif1' => 'required',
            'admin1' => 'required',
            'user1' => 'required',
            'entry1' => 'required',
        ]);

        //untuk input tabel MAINMENU yang asli
        $data = [
            'namamenu' => $namamenu,
            'slug' => $slug,
            'aktif' => $validatedData['aktif1'],
            'adminmenu' => $validatedData['admin1'],
            'usermenu' => $validatedData['user1'],
            'entrymenu' => $validatedData['entry1'],
            'urutan' => $request['urutan1']
        ];

        if ($id == '0') {
            Menuutama::create($data);
        } else {
            Menuutama::where('id', '=', $id)->update($data);
        }
        return json_encode('data');
    }

    public function edit($id)
    {
        $data = Menuutama::where('id', '=', $id)->get();
        return json_encode(array('data' => $data));
    }

    public function destroy($id)
    {
        $data = Menuutama::where('id', '=', $id)->delete();
        return json_encode(array('data' => $data));
    }
}
