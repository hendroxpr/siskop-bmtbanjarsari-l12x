<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Admin01\Models\Desa;
use Modules\Admin01\Models\Kabupaten;
use Modules\Admin01\Models\Kecamatan;
use Modules\Admin01\Models\Propinsi;

class AdminController extends Controller
{
    public function index()
    {
        $meminstansi = session('memnamasingkat');
        $remark = 'Menu Dashboards/beranda';
        $page = 'admin.dashboards.beranda';
        $link = '/admin';
        $subtitle = 'Dashboard';
        $caption = 'Beranda';
        $jmlhal = 2;

        return view($page, [
            'title' => $meminstansi . ' | ' . $subtitle . ' | ' . $caption,
            'subtitle' => $subtitle,
            'caption' => $caption,
            'link' => $link,
            'remark' => $remark,
            'jmlhal' => $jmlhal,
            'tabel' => Users::SimplePaginate($jmlhal)->withQueryString(),
        ]);
    }

    // list propinsi
    function listpropinsi10()
    {
        $tampil = Propinsi::get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->propinsi . "</option>";
        }
    }
    function listpropinsi11()
    {
        $tampil = Propinsi::orderBy('propinsi', 'asc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->propinsi . "</option>";
        }
    }
    function listpropinsi12()
    {
        $tampil = Propinsi::orderBy('propinsi', 'desc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->propinsi . "</option>";
        }
    }

    // list kabupaten berdasarkan Propinsi
    function listkabupatenx20()
    {
        $idpropinsix1 = session('idpropinsix1');
        $tampil = Kabupaten::where('idpropinsi','=',$idpropinsix1)->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kabupaten . "</option>";
        }
    }
    function listkabupatenx21()
    {
        $idpropinsix1 = session('idpropinsix1');
        $tampil = Kabupaten::where('idpropinsi','=',$idpropinsix1)->orderBy('kabupaten', 'asc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kabupaten . "</option>";
        }
    }
    function listkabupatenx22()
    {
        $idpropinsix1 = session('idpropinsix1');
        $tampil = Kabupaten::where('idpropinsi','=',$idpropinsix1)->orderBy('kabupaten', 'desc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kabupaten . "</option>";
        }
    }
    function listkabupaten20()
    {
        $idpropinsi1 = session('idpropinsi1');
        $tampil = Kabupaten::where('idpropinsi','=',$idpropinsi1)->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kabupaten . "</option>";
        }
    }
    function listkabupaten21()
    {
        $idpropinsi1 = session('idpropinsi1');
        $tampil = Kabupaten::where('idpropinsi','=',$idpropinsi1)->orderBy('kabupaten', 'asc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kabupaten . "</option>";
        }
    }
    function listkabupaten22()
    {
        $idpropinsi1 = session('idpropinsi1');
        $tampil = Kabupaten::where('idpropinsi','=',$idpropinsi1)->orderBy('kabupaten', 'desc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kabupaten . "</option>";
        }
    }

    // list kecamatan berdasarkan kabupaten
    function listkecamatanx20()
    {
        $idkabupatenx1 = session('idkabupatenx1');
        $tampil = Kecamatan::where('idkabupaten','=',$idkabupatenx1)->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kecamatan . "</option>";
        }
    }
    function listkecamatanx21()
    {
        $idkabupatenx1 = session('idkabupatenx1');
        $tampil = Kecamatan::where('idkabupaten','=',$idkabupatenx1)->orderBy('kecamatan', 'asc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kecamatan . "</option>";
        }
    }
    function listkecamatanx22()
    {
        $idkabupatenx1 = session('idkabupatenx1');
        $tampil = Kecamatan::where('idkabupaten','=',$idkabupatenx1)->orderBy('kecamatan', 'desc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kecamatan . "</option>";
        }
    }
    function listkecamatan20()
    {
        $idkabupaten1 = session('idkabupaten1');
        $tampil = Kecamatan::where('idkabupaten','=',$idkabupaten1)->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kecamatan . "</option>";
        }
    }
    function listkecamatan21()
    {
        $idkabupaten1 = session('idkabupaten1');
        $tampil = Kecamatan::where('idkabupaten','=',$idkabupaten1)->orderBy('kecamatan', 'asc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kecamatan . "</option>";
        }
    }
    function listkecamatan22()
    {
        $idkabupaten1 = session('idkabupaten1');
        $tampil = Kecamatan::where('idkabupaten','=',$idkabupaten1)->orderBy('kecamatan', 'desc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kecamatan . "</option>";
        }
    }
    //list desa
    function listdesa109()
    {
        $idkab = Kabupaten::select('id')->where('idpropinsi','=',session('idpropinsix1'))->get();
        $idkec = Kecamatan::select('id')->whereIn('idkabupaten',$idkab )->get();
        // $tampil = Desa::whereIn('idkecamatan',$idkec )->with(['kecamatan'])->get();

        $tampil = Desa::with(['kecamatan'])->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . 
                $baris->desa . '|' . 
                $baris->kecamatan->kecamatan . '|' . 
                $baris->kecamatan->kabupaten->kabupaten . '|' . 
                $baris->kecamatan->kabupaten->propinsi->propinsi . '|' . 
                
            "</option>";
        }
    }


}
