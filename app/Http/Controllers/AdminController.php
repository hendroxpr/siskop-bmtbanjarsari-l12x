<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Admin01\Models\Desa;
use Modules\Admin01\Models\Kabupaten;
use Modules\Admin01\Models\Kecamatan;
use Modules\Admin01\Models\Propinsi;
use Modules\Akuntansi01\Models\Coa;
use Modules\Akuntansi01\Models\Jenisjurnal;
use Modules\Akuntansi01\Models\Jenispinjaman;
use Modules\Akuntansi01\Models\Jenissimpanan;
use Modules\Akuntansi01\Models\Kategori;
use Modules\Akuntansi01\Models\Kelompok;
use Modules\Simpanan01\Models\Sandi;

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
        // ori
        $tampil = Propinsi::get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->propinsi . "</option>";
        }
    }
    function listpropinsi11()
    {
        // asc        
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
    // list kelompok
    function listkelompok10()
    {
        // ori
        $tampil = Kelompok::get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kode . ' - ' . $baris->kelompok . "</option>";
        }
    }
    function listkelompok11()
    {
        // asc
        $tampil = Kelompok::orderBy('kode', 'asc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kode . ' - ' . $baris->kelompok . "</option>";
        }
    }
    function listkelompok12()
    {
        // asc
        $tampil = Kelompok::orderBy('kode', 'desc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kode . ' - ' . $baris->kelompok . "</option>";
        }
    }
    // list kategori
    function listkategori20()
    {
        $idkelompok1 = session('idkelompok1');
        $tampil = Kategori::where('idkelompok','=',$idkelompok1)->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kode . ' - ' . $baris->kategori . "</option>";
        }
    }
    function listkategori21()
    {
        $idkelompok1 = session('idkelompok1');
        $tampil = Kategori::where('idkelompok','=',$idkelompok1)->orderBy('kode', 'asc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kode . ' - ' . $baris->kategori . "</option>";
        }
    }
    function listkategori22()
    {
        $idkelompok1 = session('idkelompok1');
        $tampil = Kategori::where('idkelompok','=',$idkelompok1)->orderBy('kode', 'desc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kode . ' - ' . $baris->kategori . "</option>";
        }
    }
    // list jenissimpanan
    function listjenissimpanan10()
    {
        // ori
        $tampil = Jenissimpanan::get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kode . ' - ' . $baris->jenissimpanan . "</option>";
        }
    }
    function listjenissimpanan11()
    {
        // asc
        $tampil = Jenissimpanan::orderBy('kode', 'asc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kode . ' - ' . $baris->jenissimpanan . "</option>";
        }
    }
    function listjenissimpanan12()
    {
        // asc
        $tampil = Jenissimpanan::orderBy('kode', 'desc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kode . ' - ' . $baris->jenissimpanan . "</option>";
        }
    }
    // list desain
    function listdesain10()
    {
        echo "<option value='" ."INDIVIDUAL" . "'>" . "INDIVIDUAL" . "</option>";
        echo "<option value='" ."COMPANY" . "'>" . "COMPANY" . "</option>";
    }
    // list desain
    function listtandapengenal10()
    {
        echo "<option value='" ."KTP (KARTU TANDA PENDUDUK)" . "'>" . "KTP (KARTU TANDA PENDUDUK)" . "</option>";
        echo "<option value='" ."SIM (SURAT IZIN MENGEMUDI)" . "'>" . "SIM (SURAT IZIN MENGEMUDI)" . "</option>";
        echo "<option value='" ."PASPOR" . "'>" . "PASPOR" . "</option>";
        echo "<option value='" ."BUKU NIKAH" . "'>" . "BUKU NIKAH" . "</option>";
        echo "<option value='" ."KK (KARTU KELUARGA)" . "'>" . "KK (KARTU KELUARGA)" . "</option>";
        echo "<option value='" ."KIA (KARTU IDENTITAS ANAK)" . "'>" . "KIA (KARTU IDENTITAS ANAK)" . "</option>";
        echo "<option value='" ."KARTU PELAJAR" . "'>" . "KARTU PELAJAR" . "</option>";
        echo "<option value='" ."KARTU MAHASISWA" . "'>" . "KARTU MAHASISWA" . "</option>";
        echo "<option value='" ."KARTU PEGAWAI" . "'>" . "KARTU PEGAWAI" . "</option>";
    }

    // list sandi
    function listsandi10()
    {
        // ori
        $tampil = Sandi::get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kode . ' - ' . $baris->sandi . "</option>";
        }
    }
    function listsandi11()
    {
        // asc
        $tampil = Sandi::orderBy('kode', 'asc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kode . ' - ' . $baris->sandi . "</option>";
        }
    }
    function listsandi12()
    {
        // asc
        $tampil = Sandi::orderBy('kode', 'desc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kode . ' - ' . $baris->sandi . "</option>";
        }
    }
    // list coa
    function listcoa10()
    {
        // ori
        $tampil = Coa::where('hd','=','D')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kode . ' - ' . $baris->coa . "</option>";
        }
    }
    function listcoa11()
    {
        // asc
        $tampil = Coa::where('hd','=','D')->orderBy('kode', 'asc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kode . ' - ' . $baris->coa . "</option>";
        }
    }
    function listcoa12()
    {
        // asc
        $tampil = Coa::where('hd','=','D')->orderBy('kode', 'desc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kode . ' - ' . $baris->coa . "</option>";
        }
    }

    // list jenisjurnal
    function listjenisjurnal10()
    {
        // ori
        $tampil = Jenisjurnal::get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kode . ' - ' . $baris->jenisjurnal . "</option>";
        }
    }
    function listjenisjurnal11()
    {
        // asc
        $tampil = Jenisjurnal::orderBy('kode', 'asc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kode . ' - ' . $baris->jenisjurnal . "</option>";
        }
    }
    function listjenisjurnal12()
    {
        // asc
        $tampil = Jenisjurnal::orderBy('kode', 'desc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kode . ' - ' . $baris->jenisjurnal . "</option>";
        }
    }
    // list jenispinjaman
    function listjenispinjaman10()
    {
        // ori
        $tampil = Jenispinjaman::get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kode . ' - ' . $baris->jenispinjaman . "</option>";
        }
    }
    function listjenispinjaman11()
    {
        // asc
        $tampil = Jenispinjaman::orderBy('kode', 'asc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kode . ' - ' . $baris->jenispinjaman . "</option>";
        }
    }
    function listjenispinjaman12()
    {
        // asc
        $tampil = Jenispinjaman::orderBy('kode', 'desc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->kode . ' - ' . $baris->jenispinjaman . "</option>";
        }
    }




}
