<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <style>
    
    @page{
      margin-top: 10px;
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
        font-size: 0.8em;
        line-height:0.7
    }
    </style>

</head>
<body>

  @php
      use Riskihajar\Terbilang\Terbilang;
      Config::set('terbilang.locale', 'id');
  @endphp

<div>
  <table style="width: 100%" border="0">
      <tr>
        <td style="width: 40%"><h2><b>KWINTANSI PEMBAYARAN</b></h2></td>
        <td style="width: 2%">:</td>
        <td style="width: 58%" colspan="5"><h2><b>KWINTANSI PEMBAYARAN</b></h2></td>
      </tr>
      <tr>
        <td>Nomor : {{ $nomorbukti1 }}</td>
        <td>:</td>
        <td style="width: 15%">Nomor</td>
        <td style="width: 2%">:</td>
        <td style="width: 41%" colspan="3">{{ $nomorbukti1 }}</td>
      </tr> 
      <tr>
        <td>Telah terima dari :</td>
        <td>:</td>
      </tr> 
      <tr>
        <td> &nbsp; &nbsp; {{ $pemberi1 }} </td>
        <td>:</td>
        <td>Telah terima dari</td>
        <td>:</td>
        <td colspan="3">{{ $pemberi1 }}</td>
      </tr>
      
      <tr>
        <td>Uang sebesar :</td>
        <td>:</td>
      </tr> 
      <tr>
        <td> &nbsp; &nbsp;
          @php
              echo 'Rp. ' . number_format($jml1,2)
          @endphp 
          
        </td>
        <td>:</td>
        <td>Uang sebesar</td>
        <td>:</td>
        <td colspan="3">{{ ucwords(Riskihajar\Terbilang\Facades\Terbilang::make($jml1,' rupiah','')) }}</td>
      </tr> 
      <tr>
        <td>Guna membayar :</td>
        <td>:</td>
      </tr> 
      <tr>
        <td> &nbsp; &nbsp; {{ $guna1 }}</td>
        <td>:</td>
        <td>Guna membayar</td>
        <td>:</td>
        <td colspan="3">{{ $guna1 }}</td>
      </tr>
      <tr>
        <td> &nbsp; &nbsp; {{ $guna2 }}</td>
        <td>:</td>
        <td></td>
        <td></td>
        <td colspan="3">{{ $guna2 }}</td>
      </tr>

      <tr>
        <td> &nbsp; &nbsp; </td>        
      </tr>
      <tr>
        <td> &nbsp; &nbsp; {{ session('memkotattd') }}, {{ Carbon\Carbon::parse($tgltransaksi1)->format('d F Y') }}</td>
        <td>:</td>
        <td></td>
        <td></td>
        <td style="width: 5%"></td>
        <td style="width: 1%"></td>
        <td>{{ session('memkotattd') }}, {{ Carbon\Carbon::parse($tgltransaksi1)->format('d F Y') }}</td>
      </tr>
      <tr>
        <td> &nbsp; &nbsp; Penerima {{ $penerima1 }},</td>
        <td>:</td>
        <td colspan="3">---------------------------------------</td>        
        <td></td>
        <td>Penerima {{ $penerima1 }},</td>
      </tr>
      <tr>
        <td> &nbsp; &nbsp;</td>
        <td>:</td>
        <td colspan="3" align="center">
          @php
              echo '<i>Rp. ' . number_format($jml1,2).'</i>';
          @endphp  
        </td>        
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td> &nbsp; &nbsp; </td>
        <td>:</td>
        <td colspan="3">---------------------------------------</td>        
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td> &nbsp; &nbsp; <b><u>{{ $namapenerima1 }}</u></b></td>
        <td>:</td>
        <td colspan="3"></td>        
        <td></td>
        <td><b><u>{{ $namapenerima1 }}</u></b></td>
      </tr>
  
  </table>
</div>



<script type="text/javascript">
   
$(document).ready(function(){
    
   

});
</script>	

</body>