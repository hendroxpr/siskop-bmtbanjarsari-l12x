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
      margin-left: 10px;
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
        font-stretch: narrower;
        font-size: 0.7em;
        line-height:1.5;
    }
    </style>

</head>
<body>

  @php
      use Modules\Tabungan01\Models\Sandi;
      use Modules\Akuntansi01\Models\Jurnal;

      $username = auth()->user()->name;
  @endphp



<div>
  <table style="width: 100%" border="0">
    
    @php
        $noprintx6 = session('noprintx6');
        if($noprintx6=='1'){
            echo 
                '
                <tr>
                    <td style="width: 5%; background-color: rgb(142, 232, 244); padding-top: 8px; padding-bottom: 8px;" align="center">#</td>
                    <td style="width: 15%; background-color: rgb(142, 232, 244);" align="center">Tanggal</td>
                    <td style="width: 10%; background-color: rgb(142, 232, 244);" align="center">Sandi</td>
                    <td style="width: 18%; background-color: rgb(142, 232, 244);" align="right">Debet</td>
                    <td style="width: 18%; background-color: rgb(142, 232, 244);" align="right">Kredit</td>
                    <td style="width: 18%; background-color: rgb(142, 232, 244);" align="right">Saldo</td>
                    <td style="width: 16%; background-color: rgb(142, 232, 244);" align="center">Ket.</td> 
                </tr>

                '; 
        }else{
            echo 
                '
                <tr>
                    <td style="width: 5%;  padding-top: 8px; padding-bottom: 8px;" align="center">&nbsp;</td>
                    <td style="width: 15%;" align="center"></td>
                    <td style="width: 10%;" align="center"></td>
                    <td style="width: 18%;" align="right"></td>
                    <td style="width: 18%;" align="right"></td>
                    <td style="width: 18%;" align="right"></td>
                    <td style="width: 16%;" align="center"></td> 
                </tr>

                ';
        }
          
    @endphp

  </table>
</div>
<div>
  <table style="width: 100%" border="0">
      
       @php
            $no = session('noprintx6');
            for ($x = 1; $x < $no; $x++) {
                echo 
                '
                <tr>
                    <td style="width: 5%">&nbsp;</td> 
                </tr>

                ';
            }    
       @endphp

       @foreach ($tabel as $item)
            @php
               
                $idx = $item->id;
                $nomorstatus = $item->nomorstatus;
                $singkatan = substr($nomorstatus,0,3);
                
                $sandi = Sandi::select('kode')
                    ->where('singkatan','=',$singkatan)
                    ->get();
                    foreach ($sandi as $baris) {
                        $kode = $baris->kode;
                    }
                $debet = $item->debet;
                if($debet=='0'){
                    $debet = '';
                }else{
                    $debet = number_format($debet,0);
                }
                $kredit = $item->kredit;
                if($kredit=='0'){
                    $kredit = '';
                }else{
                    $kredit = number_format($kredit,0);
                }

                $idnasabahx1 = session('idnasabahx1');
                if($idnasabahx1==''){
                    $idnasabahx1=0; 
                }

                $tglawalx1 = session('tglawalx1');
                if($tglawalx1==''){
                    $tglawalx1='1900-01-01'; 
                }
                $tglakhirx1 = session('tglakhirx1');
                if($tglakhirx1==''){
                    $tglakhirx1='1900-01-01'; 
                }

                $totaldebet = Jurnal::select('debet')                        
                        ->where('idnasabah','=', $idnasabahx1)
                        ->where('tglposting','>=',$tglawalx1)
                        ->where('tglposting','<=',$tglakhirx1)
                        ->where('apl','=', 2)
                        ->where('id','<=', $idx)
                        ->sum('debet');

                $totalkredit = Jurnal::select('kredit')                        
                        ->where('idnasabah','=', $idnasabahx1)
                        ->where('tglposting','>=',$tglawalx1)
                        ->where('tglposting','<=',$tglakhirx1)
                        ->where('apl','=', 2)
                        ->where('id','<=', $idx)
                        ->sum('kredit');
                $total = $totalkredit - $totaldebet;
                $total = number_format($total,0);

            @endphp

            <tr>
                <td style="width: 5%">{{ $no++ }}</td>
                <td style="width: 15%">{{ $item->tglposting }}</td>
                <td style="width: 10%" align="center">{{ $kode }}</td>
                <td style="width: 18%" align="right">{{ $debet }}</td>
                <td style="width: 18%" align="right">{{ $kredit }}</td>
                <td style="width: 18%" align="right">{{ $total }}</td>
                <td style="width: 16%">&nbsp; {{ $item->pengesahan }}</td>
            </tr>
       @endforeach
       
  
  </table>
</div>


<script type="text/javascript">
   
$(document).ready(function(){
  
 
   

});
</script>	

</body>