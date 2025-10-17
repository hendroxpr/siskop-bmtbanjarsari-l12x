@extends('admin.layouts.main')

@section('contents')

<style>
    .tabelku {        
        
        border-collapse: collapse        
    }

    .tabelku td {
        padding-bottom: 10px;
        padding-top: 10px;
        padding-right: 10px;
        padding-bottom: 10px;
        padding-left: 10px;       
    }
</style>


<div class="row">
    <div class="col-md-6">
        <table class="tabelku " border="1" width="100%">
            <tr>
                <td colspan="2" class="text-center" style="background-color: rgb(233, 231, 231)">Informasi Instansi</td>
                
            </tr>
            <tr>
                <td style="width: 35%">Nama</td>
                <td style="width: 65%">{{ session('memnama') }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>
                    {{ session('memalamat') }} <br>
                    Desa/Kelurahan {{ session('memdesa') }} <br>
                    Kecamatan {{ session('memkecamatan') }} <br>
                    Kabupaten {{ session('memkabupaten') }} <br>
                    Propinsi {{ session('mempropinsi') }}                
                </td>
            </tr>
            <tr>
                <td>Kode Pos</td>
                <td>{{ session('memkodepos') }}</td>
            </tr>
            <tr>
                <td>Telp.</td>
                <td>{{ session('memnomorkontak') }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>{{ session('mememail') }}</td>
            </tr>
            <tr>
                <td>Web</td>
                <td>{{ session('memwebsite') }}</td>
            </tr>
            

        </table>
    </div>

    <div class="col-md-6">
        <table class="tabelku " border="1" width="100%">
            <tr>
                <td colspan="2" class="text-center" style="background-color: rgb(233, 231, 231)">Informasi Aplikasi</td>
                
            </tr>
            <tr>
                <td style="width: 35%">Nama</td>
                <td style="width: 65%">Sistem Informasi BMT Terpadu (SIBT)</td>
            </tr>
            <tr>
                <td>Unit</td>
                <td>{{ session('aplikasi') }} ({{ session('kode') }})</td>
            </tr>
            <tr>
                <td>Framework</td>
                <td>Laravel v{{ Illuminate\Foundation\Application::VERSION }}</td>
            </tr>
            <tr>
                <td>PHP</td>
                <td> <?php print phpversion(); ?></td>
            </tr>
            <tr>
                <td>IP Address</td>
                <td>{{ $_SERVER['REMOTE_ADDR'] }}</td>
            </tr>
            
                    @php
                        use Jenssegers\Agent\Facades\Agent;
                        $device = Agent::device();
                        $browser = Agent::browser(). ' ' . Agent::version(Agent::browser());
                        $platform = Agent::platform(). ' ' . Agent::version(Agent::platform());
                        if (Agent::isMobile()) {
                            $result = 'Mobile';
                        }else if (Agent::isDesktop()) {
                            $result = 'Desktop';
                        }else if (Agent::isTablet()) {
                            $result = 'Tablet';
                        }else if (Agent::isPhone()) {
                            $result = 'Phone';
                        }
                    @endphp
            <tr>
                <td>Browser</td>
                <td>{{ $browser }}</td>
            </tr>
            <tr>
                <td>OS</td>
                <td>{{ $platform }}</td>
            </tr>
            <tr>
                <td>Device</td>
                <td>{{ $result }}</td>
            </tr>
            

        </table>
    </div>

</div>


<div class="text-center">
    <table style="border-collapse: collapse; border:1px">
        <tr>
            <td></td>
        </tr>
    </table>

</div>



@endsection