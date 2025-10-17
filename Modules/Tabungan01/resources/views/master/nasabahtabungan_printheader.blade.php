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
      use Riskihajar\Terbilang\Terbilang;

      $username = auth()->user()->name;
      
  @endphp



<div>
  <table style="width: 100%" border="0">
       <tr>
            <td style="width: 5%; background-color: rgb(142, 232, 244); padding-top: 8px; padding-bottom: 8px;" align="center">#</td>
            <td style="width: 15%; background-color: rgb(142, 232, 244);" align="center">Tanggal</td>
            <td style="width: 10%; background-color: rgb(142, 232, 244);" align="center">Sandi</td>
            <td style="width: 18%; background-color: rgb(142, 232, 244);" align="right">Debet</td>
            <td style="width: 18%; background-color: rgb(142, 232, 244);" align="right">Kredit</td>
            <td style="width: 18%; background-color: rgb(142, 232, 244);" align="right">Saldo</td>
            <td style="width: 16%; background-color: rgb(142, 232, 244);" align="center">Ket.</td>
       </tr>
  
  </table>
</div>
<div>
  <table style="width: 100%" border="0">
       <tr>
          <td>
               
          </td>
       </tr>
  
  </table>
</div>


<script type="text/javascript">
   
$(document).ready(function(){
  
 
   

});
</script>	

</body>