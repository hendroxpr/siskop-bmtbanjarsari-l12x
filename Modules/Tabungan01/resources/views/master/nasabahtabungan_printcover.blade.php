<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <style>
    
    @page{
      margin-top: 20px;
      margin-bottom: 0px;
      margin-left: 20px;
      margin-right: 10px;
    }
    header{
      position: fixed;
      left: 0px;
      right: 0px;
      height: 60px;
      margin-top: -60px;
    }
    
    
    div {
        font-family: sans-serif, Helvetica, Lucida, Arial;
        font-stretch: condensed;
        font-size: 0.9em;
        line-height:1
    }

    .keterangan {
        font-family: sans-serif, Helvetica, Lucida, Arial;
        font-stretch: condensed;
        font-size: 0.7em;
        line-height:1
    }
    </style>

</head>
<body>

  @php
      use Riskihajar\Terbilang\Terbilang;
      use Modules\Tabungan01\Models\Sandi;
      use Modules\Akuntansi01\Models\Produk;

      $username = auth()->user()->name;
      
      
  @endphp

<div>
  <table style="width: 100%" border="0">
        <tr>
            <td style="width: 20%" rowspan="2">

            </td>    
            <td style="width: 80%" style="font-size: 1.5em;"><b>{{ session('memnama') }}</b></td>    
        </tr>
        <tr>
            <td style="width: 80%" style="font-size: 1.2em;">{{ $produk }} - {{ $keterangan }}</td>    
        </tr>
        <tr>
            <td style="width: 100%" colspan="2"><hr></td>
        </tr>
  
  </table>
</div>
<div>

  @php
      date_default_timezone_set('Asia/Jakarta');
      $created_at = date('Y-m-d  H:i:s');
  @endphp

  <table style="width: 100%" border="0" class="px-4">
        <tr>
            <td style="width: 100%" colspan="3" align="right">Tgl. Cetak : {{ $created_at }} <br><br></td>   
        </tr>
        <tr>
            <td style="width: 29%">Kantor</td>    
            <td style="width: 1%">:</td>    
            <td style="width: 70%">{{ session('memnamasingkat') }}</td>    
        </tr>
        <tr>
            <td style="width: 29%">Nomor Rekening</td>    
            <td style="width: 1%">:</td>    
            <td style="width: 70%">{{ $norek }}</td>    
        </tr>
        <tr>
            <td style="width: 29%">Nama</td>    
            <td style="width: 1%">:</td>    
            <td style="width: 70%">{{ $namalengkap }}</td>    
        </tr>
        <tr>
            <td style="width: 29%">NIS / NISN</td>    
            <td style="width: 1%">:</td>    
            <td style="width: 70%">{{ $nis }} / {{ $nisn }}</td>    
        </tr>        
        <tr>
            <td style="width: 29%">Kelas</td>    
            <td style="width: 1%">:</td>    
            <td style="width: 70%">{{ $kelas }}</td>    
        </tr>
        <tr>
            <td style="width: 29%">Tanda Pengenal</td>    
            <td style="width: 1%">:</td>    
            <td style="width: 70%">KTP</td>    
        </tr>
        <tr>
            <td style="width: 29%">Alamat</td>    
            <td style="width: 1%">:</td>    
            <td style="width: 70%">{{ $alamat }}</td>    
        </tr>
        <tr>
            <td style="width: 29%">Tanda Tangan</td>    
            <td style="width: 1%">:</td>    
            <td style="width: 70%">
              <table style="width: 100%" border="1">
                <tr>
                  <td>
                    <br>
                    <br>
                    <br>
                    <br>
                  </td>
                </tr>
              </table>            
            </td>    
        </tr>
        
  </table>
</div>
<br>
<br>
<br>
<br>
<br>
<br>
<div>
  <table style="width: 100%" border="0">
      <tr>
        <td style="width: 55%; padding-top: 5px; padding-bottom: 5px;">Sandi {{ session('memnamasingkat') }}</td>
        <td style="width: 2%"></td>
        <td style="width: 43%">Produk {{ session('memnamasingkat') }}</td>
      </tr>
      
      <tr>
        <td valign="top">
          <table style="width: 100%" border="0" class="keterangan">
            
            <tr>
              <td style="background-color: rgb(142, 232, 244); padding-right: 5px; padding-left: 5px; padding-top: 5px; padding-bottom: 5px;" align="center">#</td>
              <td style="background-color: rgb(142, 232, 244); padding-right: 5px; padding-left: 5px; padding-top: 5px; padding-bottom: 5px;" align="center">Sandi</td>
              <td style="background-color: rgb(142, 232, 244); padding-right: 5px; padding-left: 5px; padding-top: 5px; padding-bottom: 5px;" align="center">Kode</td>
              <td style="background-color: rgb(142, 232, 244); padding-right: 5px; padding-left: 5px; padding-top: 5px; padding-bottom: 5px;">Keterangan</td>
            </tr>
            @php
                $no = 1;
                $sandix = Sandi::select('*')
                  ->orderBy('kode','asc')
                  ->get();
            @endphp

            @foreach ($sandix as $item)
              <tr>
                <td align="center">{{ $no++ }}</td>
                <td align="center">{{ $item->kode }}</td>
                <td align="center">{{ $item->singkatan }}</td>
                <td>{{ $item->keterangan }}</td>
              </tr>
                
            @endforeach
          </table>
        </td>
        <td></td>
        <td valign="top">
          <table style="width: 100%" border="0" class="keterangan">
            <tr>
              <td style="background-color: rgb(142, 232, 244); padding-right: 5px; padding-left: 5px; padding-top: 5px; padding-bottom: 5px;" align="center">#</td>
              <td style="background-color: rgb(142, 232, 244); padding-right: 5px; padding-left: 5px; padding-top: 5px; padding-bottom: 5px;" >Produk</td>
              <td style="background-color: rgb(142, 232, 244); padding-right: 5px; padding-left: 5px; padding-top: 5px; padding-bottom: 5px;">Keterangan</td>
            </tr>
            @php
                $nox = 1;
                  
                $produkx = Produk::select('*')            
                    ->get();
            @endphp

            @foreach ($produkx as $item2)
              <tr>
                <td align="center">{{ $nox++ }}</td>
                <td>{{ $item2->produk }}</td>
                <td>{{ $item2->keterangan }}</td>
              </tr>
                
            @endforeach


          </table>
        </td>
      </tr>  
      
  </table>
</div>





<script type="text/javascript">
   
$(document).ready(function(){
  
  
   

});
</script>	

</body>