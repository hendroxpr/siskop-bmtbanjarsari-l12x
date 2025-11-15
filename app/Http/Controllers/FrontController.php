<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use DateTime;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\User;
use App\Models\Users;
use App\Models\Instansi;
use App\Models\Sessions;
use App\Models\Aplikasi;

class FrontController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
     public function index()
     {
        date_default_timezone_set('Asia/Jakarta');

        $tgl = new DateTime('now');
        $d = $tgl->format('d');
        $m = $tgl->format('n');
        $y = $tgl->format('Y');
        $hari = $tgl->format('N');

        $mm = $tgl->format('m');

        session([            
            'navigasi' => 'home',
            'memtanggal' => $y . '-' . $mm . '-' . $d,
            'memtanggal2' => $y . '-' . $mm . '-' . $d,
            'tglawalx1' => $y . '-' . $mm . '-' . $d,
            'tglakhirx1' => $y . '-' . $mm . '-' . $d,
            'memtahun' => $y,        //   
            'tgltransaksi1' => $y . '-' . $mm . '-' . $d,
            'tgltransaksix1' => $y . '-' . $mm . '-' . $d,
        ]);

        $namabulanx = array(" ", "Januari ", "Februari ", "Maret ", "April ", "Mei ", "Juni ", "Juli ", "Agustus ", "September ", "Oktober ", "Nopember ", "Desember ");
        $namaharix = array(" ", "Senin ", "Selasa ", "Rabu ", "Kamis ", "Jum'at ", "Sabtu ", "Minggu");

        $tglsekarang = $namaharix[$hari] . ', ' . $d . ' ' . $namabulanx[$m] . ' ' . $y;

        //membuat session instansi
        $instansi = $this->tabelx('instansi');
        foreach ($instansi as $item) {
            session([
                'memnama' => $item->nama,
                'memnamasingkat' => $item->namasingkat,
                'memalamat' => $item->alamat,
                'memdesa' => $item->desa,
                'memkecamatan' => $item->kecamatan,
                'memkabupaten' => $item->kabupaten,
                'mempropinsi' => $item->propinsi,
                'memkodepos' => $item->kodepos,
                'mememail' => $item->email,
                'memfacebook' => $item->facebook,
                'meminstagram' => $item->instagram,
                'memtwitter' => $item->twitter,
                'memwebsite' => $item->website,
                'memkontakperson' => $item->kontakperson,
                'memnomorkontak' => $item->nomorkontak,
                'memjabatankontak' => $item->jabatankontak,
                'memketua' => $item->ketua,
                'memnipketua' => $item->nipketua,
                'memfotoketua' => $item->fotoketua,
                'memsambutanketua' => $item->sambutanketua,
                'membendahara' => $item->bendahara,
                'memnipbendahara' => $item->nipbendahara,
                'memkotattd' => $item->kotattd,
                'memlogo' => $item->logo,
                'memmotto' => $item->motto,
                'memvisi' => $item->visi,
                'memmisi' => $item->misi,
                'memtujuan' => $item->tujuan,
                'memstrukturorganisasi' => $item->strukturorganisasi,
                'memketerangan' => $item->keterangan,
            ]);

            // return view('front.dashboards.beranda', [
            //     'title' => session('memnamasingkat'),
            //     'instansi' => $this->tabelx('instansi'),
            //     'posts01' => $this->tabelx('posts01'),
            //     'postspopuler01' => $this->tabelx('postspopuler01'),
            //     'poststerkini01' => $this->tabelx('poststerkini01'),
            //     'gurudanpegawai01' => $this->tabelx('gurudanpegawai01'),
            //     'kompetensi01' => $this->tabelx('kompetensi01'),
            //     'tglsekarang' => $tglsekarang,
            //     'caption' => '',
            //     'active' => 'xx'
            // ]);
 
       }
     }
     

     public function tabelx($key)
     {
         
         $instansi = Instansi::get();
         $sessions = Sessions::orderBy('norut','desc')->get();
         
         $arr = [
             'instansi' => $instansi,    //0
             'sessions' => $sessions,    //0
                        
         ];
         return $arr[$key];
     }
    
     public function loginform()
     {
        
        date_default_timezone_set('Asia/Jakarta');

        $tgl = new DateTime('now');
        $d = $tgl->format('d');
        $m = $tgl->format('n');
        $y = $tgl->format('Y');
        $hari = $tgl->format('N');

        $mm = $tgl->format('m');

        session([            
            'navigasi' => 'home',
            'memtanggal' => $y . '-' . $mm . '-' . $d,
            'memtanggal2' => $y . '-' . $mm . '-' . $d,
            'memtahun' => $y,        //     
        ]);

        $namabulanx = array(" ", "Januari ", "Februari ", "Maret ", "April ", "Mei ", "Juni ", "Juli ", "Agustus ", "September ", "Oktober ", "Nopember ", "Desember ");
        $namaharix = array(" ", "Senin ", "Selasa ", "Rabu ", "Kamis ", "Jum'at ", "Sabtu ", "Minggu");

        $tglsekarang = $namaharix[$hari] . ', ' . $d . ' ' . $namabulanx[$m] . ' ' . $y;

        //membuat session instansi dll
        $instansi = $this->tabelx('instansi');
        foreach ($instansi as $item) {
            session([
                'memnama' => $item->nama,
                'memnamasingkat' => $item->namasingkat,
                'memalamat' => $item->alamat,
                'memdesa' => $item->desa,
                'memkecamatan' => $item->kecamatan,
                'memkabupaten' => $item->kabupaten,
                'mempropinsi' => $item->propinsi,
                'memkodepos' => $item->kodepos,
                'mememail' => $item->email,
                'memfacebook' => $item->facebook,
                'meminstagram' => $item->instagram,
                'memtwitter' => $item->twitter,
                'memwebsite' => $item->website,
                'memkontakperson' => $item->kontakperson,
                'memnomorkontak' => $item->nomorkontak,
                'memjabatankontak' => $item->jabatankontak,
                'memketua' => $item->ketua,
                'memnipketua' => $item->nipketua,
                'memfotoketua' => $item->fotoketua,
                'memsambutanketua' => $item->sambutanketua,
                'membendahara' => $item->bendahara,
                'memnipbendahara' => $item->nipbendahara,
                'memkotattd' => $item->kotattd,
                'memlogo' => $item->logo,
                'memmotto' => $item->motto,
                'memvisi' => $item->visi,
                'memmisi' => $item->misi,
                'memtujuan' => $item->tujuan,
                'memstrukturorganisasi' => $item->strukturorganisasi,
                'memketerangan' => $item->keterangan,

                'idpropinsi1' => 15,
                'idpropinsix1' => 15,
                'idkabupaten1' => 249,
                'idkabupatenx1' => 249,
                'idkecamatan1' => 3737,
                'idkecamatanx1' => 3737,
                'iddesa1' => 47780,
                'iddesax1' => 47780,
                'idkelompok1' => 1,
                'idkelompokx1' => 1,
                'idkategori1' => 1,
                'idkategorix1' => 1,
                'idproduktabungan1' => 1,
                'idproduktabunganx1' => 1,
                'desain1' => 'INDIVIDUAL',
                'desainX1' => 'INDIVIDUAL',
                'tandapengenal1' => 'KTP (KARTU TANDA PENDUDUK)',
                'tandapengenalX1' => 'KTP (KARTU TANDA PENDUDUK)',

            ]);
        }

        //membuat session sessions        
        $sessions = Sessions::orderBy('norut','desc')->limit(1)->get();
        foreach ($sessions as $item) {
            session([
                'sessions_id' => $item->id,
                'sessions_user_id' => $item->user_id,
                'sessions_ip_address' => $item->ip_address,
                'sessions_user_agent' => $item->user_agent,
                'sessions_payload' => $item->payload,
                'sessions_last_activity' => $item->last_activity,                
            ]);
        }
        
        // tampilkan data ke view
        return view('front.dashboards.login', [
            'title' => session('memnamasingkat'),
            'caption' => '',
            'instansi' => Instansi::get(),
            'active' => 'xx'
        ]);

    }
     public function registerform()
     {
        
        date_default_timezone_set('Asia/Jakarta');

        return view('front.dashboards.register', [
            'title' => session('memnamasingkat'),
            'caption' => '',
            'instansi' => Instansi::get(),
            'active' => 'xx'
        ]);
    }

    function listaplikasi()
    {
        $tampil = Aplikasi::orderBy('aplikasi', 'asc')->get();
        foreach ($tampil as $baris) {
            echo "<option value='" . $baris->id . "'>" . $baris->aplikasi . "</option>";
        }
    }

    public function create(Request $request)
    {

        $name = $request['username2'];
        $request['username2'] = Str::slug($request['username2']);
        $request['email2'] = Str::lower($request['email2']);
        
        $jml1 = Users::where('slug','=',$request['username2'])->count();
        $jml2 = Users::where('email','=',$request['email2'])->count();

        $jml = $jml1 + $jml2;

        if($jml>0){
            alert()->error('Register', 'Save failed ...');
            return redirect('/register');
        }else{
            $validateData = $request->validate([
                'username2' => 'required|min:3|max:100|unique:users,slug',
                'email2' => 'required|min:5|max:100|email|unique:users,email',
                'password2' => 'required|min:3|max:100',
                'idaplikasi2' => 'required',
            ]);

            $data = [
                'name' => $name,
                'slug' => $validateData['username2'],
                'idsessions' => session('sessions_id'),
                'email' => $validateData['email2'],
                'password' => hash::make($validateData['password2']),
                'namadepan' => $request['namadepan2'],
                'namatengah' => $request['namatengah2'],
                'namabelakang' => $request['namabelakang2'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'kunci1' => $request['idaplikasi2'],
                'kunci2' => $request['idaplikasi2'],            
                'levels' => 3,
                'blokir' => 'Y',
            ];
            
            Users::create($data);
            alert()->success('Register', 'Save successed ...');
            return redirect('/register');
        }
    
    }

    public function authenticate(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
         
        $request['email'] = Str::lower($request['email']);
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|max:100'
        ]);

        $cek = Users::where('email', '=', $request['email'])->get();
        foreach ($cek as $item) {
            $blokir = $item->blokir;
        }


        if (Auth::attempt($credentials)) {
            if ($blokir == 'N') {
                $request->session()->regenerate();
                $sesi = $request->session()->get('key');
                
                $jmllogin = auth()->user()->jmllogin + 1;
                $email = auth()->user()->email;
                $kunci1 = auth()->user()->kunci1;
                $level = auth()->user()->levels;

                //update jmllogin+1
                Users::where('email', $email)->update(['jmllogin' => $jmllogin]);

                //update email_verified_at
                Users::where('email', '=', $email)->update(
                    [
                        'email_verified_at' => Carbon::now(),
                        'jmllogin' => $jmllogin,
                        'idsessions' => session('sessions_id'),
                    ]
                );

                $aplikasis = Aplikasi::where('id', '=', $kunci1)->get();
                foreach ($aplikasis as $item) {
                    $kode = $item->kode;
                    $aplikasi = $item->aplikasi;
                    $idaplikasi = $item->id;
                }

                //buat session
                session([
                    'awaljam' => Carbon::now(), 
                    'kode' => $kode,
                    'aplikasi' => $aplikasi,
                    'idaplikasi' => $idaplikasi,
                ]);
                return redirect()->intended('/admin');
            } else {
                Auth::logout();

                $request->session()->invalidate();

                $request->session()->regenerateToken();
                alert()->warning('Login', 'Your account blocked ...');
                return redirect('/login');
            }
        } else {
            alert()->error('Login', 'Login failed ...');
            return redirect('/login');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        alert()->success('Logout', 'Logout successed ...');
        return redirect('/');
    }


}
