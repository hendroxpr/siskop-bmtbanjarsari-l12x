@extends('admin.layouts.main')

@section('contents')

@php
    $idruang = session('idruang1');
    if($idruang==''){
        $idruang = '1';
    }    
   
    $tablabarugi = session('tablabarugi1');
    $tgltransaksi1 = session('tgltransaksi1');   
    if($tgltransaksi1==''){
        $tgltransaksi1=session('memtanggal');;  
    }    
    $tgltransaksi2 = session('tgltransaksi2');   
    if($tgltransaksi2==''){
        $tgltransaksi2=session('memtanggal');;  
    }      
@endphp

@php
    $kunci1 = auth()->user()->kunci1;
    $kunci2 = auth()->user()->kunci2;    
    $level = auth()->user()->levels;
    foreach ($menu as $item) {
        $idaplikasi = $item->idaplikasi;
    }
@endphp

@if($kunci1==1)
    <div class="container-fluid px-0" style="display:block"> 
@else
    @if($idaplikasi<>$kunci1)
        <div>
            @include('admin.layouts.forbidden')
        </div>

        <div class="container-fluid px-0" style="display:none"> 
    @else
        <div class="container-fluid px-0" style="display:block">
    @endif
@endif


    <div class="box-header mb-3">  
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-3 mt-2 text-right">
                        <h6>Lokasi</h6>
                    </div>
                    <div class="col-md-7">
                        <select name="idruang1" id="idruang1" class="w3-input w3-border" value="{{ $idruang }}"></select>
                        <input name="tablabarugi1" id="tablabarugi1" class="" type="hidden" value="{{ $tablabarugi }}"> 
                        <input name="event1" id="event1" class="" type="hidden" value="0"> 
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-3 text-right">
                        <h6 class="mt-2">Periode Tanggal</h6>
                    </div>
                    <div class="col-md-3">
                        <input name="tgltransaksi1" id="tgltransaksi1" class="w3-input w3-border" maxlength="10" type="text" placeholder="Tanggal awal" autocomplete="off" value="{{ $tgltransaksi1 }}">                       
                    </div>
                    <div class="col-md-1 text-center">
                        <h6 class="mt-2">s/d</h6>
                    </div>
                    <div class="col-md-3">
                        <input name="tgltransaksi2" id="tgltransaksi2" class="w3-input w3-border" maxlength="10" type="text" placeholder="Tanggal akhir" autocomplete="off" value="{{ $tgltransaksi2 }}">                       
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        {{--  --}}
                    </div>
                    <div class="col-md-7">
                        {{--  --}}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="w3-row" align="right"><i class="fa fa-refresh" aria-hidden="true"></i>            
                    <a href="{{ url('/') }}{{ $link }}" class="btn bg-success rounded-0"><i style="font-size:18px" class="fa">&#xf021;</i> Refresh</a>            
                </div> 
            </div>
        </div>

    </div>

    <ul class="nav nav-tabs" id="tab-labarugi" role="tablist">
        <li class="nav-item">
            <a class="nav-link" id="tab-labarugisaja" data-toggle="pill" href="#isi-tab-labarugisaja" role="tab" aria-controls="tab-labarugisaja" aria-selected="true">Laba Rugi</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-labarugififo" data-toggle="pill" href="#isi-tab-labarugififo" role="tab" aria-controls="tab-labarugififo" aria-selected="false">Laba Rugi FIFO</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-labarugimova" data-toggle="pill" href="#isi-tab-labarugimova" role="tab" aria-controls="tab-labarugimova" aria-selected="false">Laba Rugi Moving Average</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-labarugilifo" data-toggle="pill" href="#isi-tab-labarugilifo" role="tab" aria-controls="tab-labarugilifo" aria-selected="false">Laba Rugi LIFO</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-labarugijasa" data-toggle="pill" href="#isi-tab-labarugijasa" role="tab" aria-controls="tab-labarugijasa" aria-selected="false">Laba Rugi Jasa</a>
        </li>
             
    </ul>

    <!--awal tabel-->        
    <div class="box-body" id="headerjudul" style="display: block;">
        <div class="tab-content mt-3" id="tab-labarugi-tabContent">

            <!--tab-labarugisaja -->
            <div class="tab-pane fade" id="isi-tab-labarugisaja" role="tabpanel" aria-labelledby="tab-labarugisaja">
                <div id="reload" class="table-responsive">
                   <table id="labarugisaja1" class="" style="width: 100%;">
                        <thead>
                            <tr style="display: none;">
                                <th style="width:500x; height:1px;">&nbsp;</th>                            
                                <th style="width:200px"></th>							
                                <th style="width:10px"></th>							
                                <th style="width:200px"></th>							
                                <th style="width:10px"></th>							
                            </tr>
                            
                        </thead>
                        <tfoot id="show_footerlabarugisaja1">
                            
                        </tfoot>
                        <tbody id="show_datalabarugisaja1">
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <!--/tab-labarugisaja -->
            
            <!--tab-labarugififo -->
            <div class="tab-pane fade" id="isi-tab-labarugififo" role="tabpanel" aria-labelledby="tab-labarugififo">
                <div id="reload" class="table-responsive">
                   <table id="labarugififo1" class="" style="width: 100%;">
                        <thead>
                            <tr style="display: none;">
                                <th style="width:500x; height:1px;">&nbsp;</th>                            
                                <th style="width:200px"></th>							
                                <th style="width:10px"></th>							
                                <th style="width:200px"></th>							
                                <th style="width:10px"></th>							
                            </tr>
                            
                        </thead>
                        <tfoot id="show_footerlabarugififo1">
                            
                        </tfoot>
                        <tbody id="show_datalabarugififo1">
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <!--/tab-labarugififo -->
            
            <!--tab-labarugimova -->
            <div class="tab-pane fade" id="isi-tab-labarugimova" role="tabpanel" aria-labelledby="tab-labarugimova">
                <div id="reload" class="table-responsive">
                    <table id="labarugimova1" class="" style="width: 100%;">
                        <thead>
                            <tr style="display: none;">
                                <th style="width:500x; height:1px;">&nbsp;</th>                            
                                <th style="width:200px"></th>							
                                <th style="width:10px"></th>							
                                <th style="width:200px"></th>							
                                <th style="width:10px"></th>							
                            </tr>
                            
                        </thead>
                        <tfoot id="show_footerlabarugimova1">
                            
                        </tfoot>
                        <tbody id="show_datalabarugimova1">
                            
                        </tbody>
                    </table>            
                </div>
            </div>
            <!--/tab-labarugimova -->

            <!--tab-labarugilifo -->
            <div class="tab-pane fade" id="isi-tab-labarugilifo" role="tabpanel" aria-labelledby="tab-labarugilifo">
                <div id="reload" class="table-responsive">
                    <table id="labarugilifo1" class="" style="width: 100%;">
                        <thead>
                            <tr style="display: none;">
                                <th style="width:500x; height:1px;">&nbsp;</th>                            
                                <th style="width:200px"></th>							
                                <th style="width:10px"></th>							
                                <th style="width:200px"></th>							
                                <th style="width:10px"></th>							
                            </tr>
                            
                        </thead>
                        <tfoot id="show_footerlabarugilifo1">
                            
                        </tfoot>
                        <tbody id="show_datalabarugilifo1">
                            
                        </tbody>
                    </table>            
                </div>
            </div>
            <!--/tab-labarugilifo -->

            <!--tab-labarugijasa -->
            <div class="tab-pane fade" id="isi-tab-labarugijasa" role="tabpanel" aria-labelledby="tab-labarugijasa">
                <div id="reload" class="table-responsive">
                    <table id="labarugijasa1" class="" style="width: 100%;">
                        <thead>
                            <tr style="display: none;">
                                <th style="width:500x; height:1px;">&nbsp;</th>                            
                                <th style="width:200px"></th>							
                                <th style="width:10px"></th>							
                                <th style="width:200px"></th>							
                                <th style="width:10px"></th>							
                            </tr>
                            
                        </thead>
                        <tfoot id="show_footerlabarugijasa1">
                            
                        </tfoot>
                        <tbody id="show_datalabarugijasa1">
                            
                        </tbody>
                    </table>            
                </div>
            </div>
            <!--/tab-labarugijasa -->

        </div>
    </div>    
<!--akhir tabel-->

    <!-- khusus menyimpan data yang akan dihapus -->
    <input name="id3" id="id3"type="hidden">	
    <input name="data3a" id="data3a"type="hidden">	
    <input name="data3b" id="data3b"type="hidden">	
    <input name="data3c" id="data3c"type="hidden">	

</div>


<script type="text/javascript">
    var labarugisaja1Datatable;
    var labarugififo1Datatable;
    var labarugimova1Datatable;
    var labarugilifo1Datatable;
    var labarugijasa1Datatable;
   
$(document).ready(function(){
    
    function formatRupiah(angka, prefix,desimal){
			angka1=parseFloat(angka);			
			angka2=angka1.toFixed(10);
		    angka3=angka2.substr(0,(angka2.length)-11);			
			var number_string = angka3.replace(/[^,\d]/g, '').toString(),
			split   		= number_string.split(','),
			sisa     		= split[0].length % 3,
			rupiah     		= split[0].substr(0, sisa),
			ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
		 
			// tambahkan titik jika yang di input sudah menjadi angka ribuan
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
					jmldesimal=parseFloat(desimal);					
					//a1 = parseFloat(angka);
					a1 = parseFloat(angka1);
					b1 = a1.toFixed(0);					
					b2 = a1.toFixed(parseFloat(jmldesimal));					
					pos1 = b2.indexOf(".");
					pos2 = b2.indexOf(",");					
					if (parseFloat(pos1)<0){
						pos1=0;
					}
					if (parseFloat(pos2)<0){
						pos2=0;
					}
					pos = parseFloat(pos1)+ parseFloat(pos2)+parseFloat(1);
					
					koma = ','+b2.substr(parseFloat(pos),parseFloat(jmldesimal));
					
			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah + koma;
			return prefix == undefined ? rupiah : (rupiah ? ' ' + rupiah : '');
		}
		
		function formatAngka(angka, prefix){
			angka1=parseFloat(angka);			
			angka2=angka1.toFixed(10);
		    angka3=angka2.substr(0,(angka2.length)-11);			
			var number_string = angka3.replace(/[^,\d]/g, '').toString(),
			split   		= number_string.split(','),
			sisa     		= split[0].length % 3,
			rupiah     		= split[0].substr(0, sisa),
			ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
		 
			// tambahkan titik jika yang di input sudah menjadi angka ribuan
			if(ribuan){
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}
						
			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
		}
		
		function cek_angka(angka){	
			var x='';
			var validasiAngka = /^[0-9]+$/;
			//cek validasi
			if(angka.match(validasiAngka)){
				x=parseFloat(angka);
			}else{
				x=parseFloat(1);
			}
			return x;			
        }

    function tgl_sekarang(){
        var x = new Date();
        var tgl = x.getDate();
        if(tgl<10){
            tgl='0'+tgl;
        }
        var bln = x.getMonth()+1;
        if(bln<10){
            bln='0'+bln;
        }
        var thn = x.getFullYear();

			return thn+'-'+bln+'-'+tgl;

	}

    tglhariini();
    function tglhariini(){
			var tgl=new Date();
			var hari=tgl.getDate();
			if(hari<10){
				var hari='0'+hari;
			}
			
			var bulan=tgl.getMonth()+1;
			if(bulan<10){
				var bulan='0'+bulan;
			}
			var tahun=tgl.getFullYear();
            var tahun2=parseInt(tahun)-17;
			var tglsekarang=tahun+'-'+bulan+'-'+hari;
			var tglsekarang2=tahun2+'-'+bulan+'-'+hari;
			
		}

    
    $('#idruang1').change(); 

    tampil_labarugisaja1(); 
    tombol_labarugisaja1();
    function tombol_labarugisaja1(){
        $('#labarugisaja1').DataTable( {
            "order": false, 
            "responsive": true, 
            "lengthChange": false, 
            "paging": false, 
            "searching": false, 
            "autoWidth": false,
            "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ],
            buttons : [ 
                {extend: 'colvis', postfixButtons: [ 'colvisRestore' ] }, 
                {extend:'copy'}, 
                {extend:'csv'}, 
                {extend: 'pdf', orientation: 'portrait', pageSize: 'A4', title:'{{ $caption }}'}, 
                {extend: 'excel', title: '{{ $caption }}'}, 
                {extend:'print', orientation: 'portrait', pageSize: 'A4', title: '{{ $caption }}'}, 
            ],
		    }).buttons().container().appendTo('#labarugisaja1_wrapper .col-md-6:eq(0)');   
     } 
    //tampilkan dalam tabel ->OK
    function tampil_labarugisaja1(){	
        $.ajax({
            type  : 'get',
            url   : '{{route('pos01.laporan.labarugi_showlabarugisaja')}}',
            async : false,
            dataType : 'json',
            				 				
            success : function(data){
                var html = '';
                var i;                
                var resultData = data.data;	                			
                for(i=0; i<resultData.length; i++){
                    html += '<tr>'+
                                '<td style="height:10px;"></td>'+ 
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr style="background-color:grey;">'+
                                '<td style="height: 1px;"></td>'+ 
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="height:10px;"></td>'+ 
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">PENDAPATAN :</td>'+ 
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PPN PENJUALAN</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right">'+ resultData[i].ppnjual +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; DISCOUNT PENJUALAN</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right">'+ resultData[i].discountjual +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PENDAPATAN DARI PENJUALAN</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right">'+ resultData[i].pendapatanjual +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; HARGA POKOK PENJUALAN</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right" style="border-bottom: 1px solid #111">'+ resultData[i].hpp +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>LABA KOTOR</b></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right"><b>'+ resultData[i].labakotor +'</b></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">BEBAN USAHA :</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right"><b></b></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PENGELUARAN BIAYA</td>'+
                                '<td align="right" style="border-bottom: 1px solid #111">'+ resultData[i].pengeluaranbiaya +'</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right" style="border-bottom: 1px solid #111">'+ resultData[i].bebanusaha +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b> LABA USAHA</b></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right"><b>'+ resultData[i].labausaha +'</b></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">PENDAPATAN DI LUAR USAHA :</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right"><b></b></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PENDAPATAN LAIN</td>'+
                                '<td align="right" style="border-bottom: 1px solid #111">'+ resultData[i].pendapatanlain +'</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right" style="border-bottom: 1px solid #111">'+ resultData[i].pendapatandiluarusaha +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b> LABA BERSIH</b></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right"><b>'+ resultData[i].lababersih +'</b></td>'+
                                '<td></td>'+
                            '</tr>';
                }

                $('#show_datalabarugisaja1').html(html); 
                                            
            }
        }); 
    
    }
    
    tampil_labarugififo1(); 
    tombol_labarugififo1();
    function tombol_labarugififo1(){
        $('#labarugififo1').DataTable( {
            "order": false, 
            "responsive": true, 
            "lengthChange": false, 
            "paging": false, 
            "searching": false, 
            "autoWidth": false,
            "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ],
            buttons : [ 
                {extend: 'colvis', postfixButtons: [ 'colvisRestore' ] }, 
                {extend:'copy'}, 
                {extend:'csv'}, 
                {extend: 'pdf', orientation: 'portrait', pageSize: 'A4', title:'{{ $caption }}'}, 
                {extend: 'excel', title: '{{ $caption }}'}, 
                {extend:'print', orientation: 'portrait', pageSize: 'A4', title: '{{ $caption }}'}, 
            ],
		    }).buttons().container().appendTo('#labarugififo1_wrapper .col-md-6:eq(0)');   
     } 
    //tampilkan dalam tabel ->OK
    function tampil_labarugififo1(){	
        $.ajax({
            type  : 'get',
            url   : '{{route('pos01.laporan.labarugi_showlabarugififo')}}',
            async : false,
            dataType : 'json',
            				 				
            success : function(data){
                var html = '';
                var i;                
                var resultData = data.data;	                			
                for(i=0; i<resultData.length; i++){
                    html += '<tr>'+
                                '<td style="height:10px;"></td>'+ 
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr style="background-color:grey;">'+
                                '<td style="height: 1px;"></td>'+ 
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="height:10px;"></td>'+ 
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">PENDAPATAN :</td>'+ 
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PPN PENJUALAN</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right">'+ resultData[i].ppnjual +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; DISCOUNT PENJUALAN</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right">'+ resultData[i].discountjual +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PENDAPATAN DARI PENJUALAN</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right">'+ resultData[i].pendapatanjual +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; HARGA POKOK PENJUALAN</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right" style="border-bottom: 1px solid #111">'+ resultData[i].hpp +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>LABA KOTOR</b></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right"><b>'+ resultData[i].labakotor +'</b></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">BEBAN USAHA :</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right"><b></b></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PENGELUARAN BIAYA</td>'+
                                '<td align="right" style="border-bottom: 1px solid #111">'+ resultData[i].pengeluaranbiaya +'</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right" style="border-bottom: 1px solid #111">'+ resultData[i].bebanusaha +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b> LABA USAHA</b></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right"><b>'+ resultData[i].labausaha +'</b></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">PENDAPATAN DI LUAR USAHA :</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right"><b></b></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PENDAPATAN LAIN</td>'+
                                '<td align="right" style="border-bottom: 1px solid #111">'+ resultData[i].pendapatanlain +'</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right" style="border-bottom: 1px solid #111">'+ resultData[i].pendapatandiluarusaha +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b> LABA BERSIH</b></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right"><b>'+ resultData[i].lababersih +'</b></td>'+
                                '<td></td>'+
                            '</tr>';
                }

                $('#show_datalabarugififo1').html(html); 
                                            
            }
        }); 
    
    }
    
    tampil_labarugimova1(); 
    tombol_labarugimova1();
    function tombol_labarugimova1(){
        $('#labarugimova1').DataTable( {
            "order": false, 
            "responsive": true, 
            "lengthChange": false, 
            "paging": false, 
            "searching": false, 
            "autoWidth": false,
            "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ],
            buttons : [ 
                {extend: 'colvis', postfixButtons: [ 'colvisRestore' ] }, 
                {extend:'copy'}, 
                {extend:'csv'}, 
                {extend: 'pdf', orientation: 'portrait', pageSize: 'A4', title:'{{ $caption }}'}, 
                {extend: 'excel', title: '{{ $caption }}'}, 
                {extend:'print', orientation: 'portrait', pageSize: 'A4', title: '{{ $caption }}'}, 
            ],
		    }).buttons().container().appendTo('#labarugimova1_wrapper .col-md-6:eq(0)');   
     } 
    //tampilkan dalam tabel ->OK
    function tampil_labarugimova1(){	
        $.ajax({
            type  : 'get',
            url   : '{{route('pos01.laporan.labarugi_showlabarugimova')}}',
            async : false,
            dataType : 'json',
            				 				
            success : function(data){
                var html = '';
                var i;                
                var resultData = data.data;	                			
                for(i=0; i<resultData.length; i++){
                    html += '<tr>'+
                                '<td style="height:10px;"></td>'+ 
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr style="background-color:grey;">'+
                                '<td style="height: 1px;"></td>'+ 
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="height:10px;"></td>'+ 
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">PENDAPATAN :</td>'+ 
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PPN PENJUALAN</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right">'+ resultData[i].ppnjual +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; DISCOUNT PENJUALAN</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right">'+ resultData[i].discountjual +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PENDAPATAN DARI PENJUALAN</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right">'+ resultData[i].pendapatanjual +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; HARGA POKOK PENJUALAN</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right" style="border-bottom: 1px solid #111">'+ resultData[i].hpp +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>LABA KOTOR</b></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right"><b>'+ resultData[i].labakotor +'</b></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">BEBAN USAHA :</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right"><b></b></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PENGELUARAN BIAYA</td>'+
                                '<td align="right" style="border-bottom: 1px solid #111">'+ resultData[i].pengeluaranbiaya +'</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right" style="border-bottom: 1px solid #111">'+ resultData[i].bebanusaha +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b> LABA USAHA</b></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right"><b>'+ resultData[i].labausaha +'</b></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">PENDAPATAN DI LUAR USAHA :</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right"><b></b></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PENDAPATAN LAIN</td>'+
                                '<td align="right" style="border-bottom: 1px solid #111">'+ resultData[i].pendapatanlain +'</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right" style="border-bottom: 1px solid #111">'+ resultData[i].pendapatandiluarusaha +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b> LABA BERSIH</b></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right"><b>'+ resultData[i].lababersih +'</b></td>'+
                                '<td></td>'+
                            '</tr>';
                }

                $('#show_datalabarugimova1').html(html); 
                                            
            }
        }); 
    
    }
    
    tampil_labarugilifo1(); 
    tombol_labarugilifo1();
    function tombol_labarugilifo1(){
        $('#labarugilifo1').DataTable( {
            "order": false, 
            "responsive": true, 
            "lengthChange": false, 
            "paging": false, 
            "searching": false, 
            "autoWidth": false,
            "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ],
            buttons : [ 
                {extend: 'colvis', postfixButtons: [ 'colvisRestore' ] }, 
                {extend:'copy'}, 
                {extend:'csv'}, 
                {extend: 'pdf', orientation: 'portrait', pageSize: 'A4', title:'{{ $caption }}'}, 
                {extend: 'excel', title: '{{ $caption }}'}, 
                {extend:'print', orientation: 'portrait', pageSize: 'A4', title: '{{ $caption }}'}, 
            ],
		    }).buttons().container().appendTo('#labarugilifo1_wrapper .col-md-6:eq(0)');   
     } 
    //tampilkan dalam tabel ->OK
    function tampil_labarugilifo1(){	
        $.ajax({
            type  : 'get',
            url   : '{{route('pos01.laporan.labarugi_showlabarugilifo')}}',
            async : false,
            dataType : 'json',
            				 				
            success : function(data){
                var html = '';
                var i;                
                var resultData = data.data;	                			
                for(i=0; i<resultData.length; i++){
                    html += '<tr>'+
                                '<td style="height:10px;"></td>'+ 
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr style="background-color:grey;">'+
                                '<td style="height: 1px;"></td>'+ 
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="height:10px;"></td>'+ 
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">PENDAPATAN :</td>'+ 
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PPN PENJUALAN</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right">'+ resultData[i].ppnjual +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; DISCOUNT PENJUALAN</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right">'+ resultData[i].discountjual +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PENDAPATAN DARI PENJUALAN</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right">'+ resultData[i].pendapatanjual +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; HARGA POKOK PENJUALAN</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right" style="border-bottom: 1px solid #111">'+ resultData[i].hpp +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>LABA KOTOR</b></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right"><b>'+ resultData[i].labakotor +'</b></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">BEBAN USAHA :</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right"><b></b></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PENGELUARAN BIAYA</td>'+
                                '<td align="right" style="border-bottom: 1px solid #111">'+ resultData[i].pengeluaranbiaya +'</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right" style="border-bottom: 1px solid #111">'+ resultData[i].bebanusaha +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b> LABA USAHA</b></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right"><b>'+ resultData[i].labausaha +'</b></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">PENDAPATAN DI LUAR USAHA :</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right"><b></b></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PENDAPATAN LAIN</td>'+
                                '<td align="right" style="border-bottom: 1px solid #111">'+ resultData[i].pendapatanlain +'</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right" style="border-bottom: 1px solid #111">'+ resultData[i].pendapatandiluarusaha +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b> LABA BERSIH</b></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right"><b>'+ resultData[i].lababersih +'</b></td>'+
                                '<td></td>'+
                            '</tr>';
                }

                $('#show_datalabarugilifo1').html(html); 
                                            
            }
        }); 
    
    }
     
    tampil_labarugijasa1(); 
    tombol_labarugijasa1();
    function tombol_labarugijasa1(){
        $('#labarugijasa1').DataTable( {
            "order": false, 
            "responsive": true, 
            "lengthChange": false, 
            "paging": false, 
            "searching": false, 
            "autoWidth": false,
            "lengthMenu": [ [25, 50, 100, -1], [25, 50, 100, "All"] ],
            buttons : [ 
                {extend: 'colvis', postfixButtons: [ 'colvisRestore' ] }, 
                {extend:'copy'}, 
                {extend:'csv'}, 
                {extend: 'pdf', orientation: 'portrait', pageSize: 'A4', title:'{{ $caption }}'}, 
                {extend: 'excel', title: '{{ $caption }}'}, 
                {extend:'print', orientation: 'portrait', pageSize: 'A4', title: '{{ $caption }}'}, 
            ],
		    }).buttons().container().appendTo('#labarugijasa1_wrapper .col-md-6:eq(0)');   
     } 
    //tampilkan dalam tabel ->OK
    function tampil_labarugijasa1(){	
        $.ajax({
            type  : 'get',
            url   : '{{route('pos01.laporan.labarugi_showlabarugijasa')}}',
            async : false,
            dataType : 'json',
            				 				
            success : function(data){
                var html = '';
                var i;                
                var resultData = data.data;	                			
                for(i=0; i<resultData.length; i++){
                    html += '<tr>'+
                                '<td style="height:10px;"></td>'+ 
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr style="background-color:grey;">'+
                                '<td style="height: 1px;"></td>'+ 
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="height:10px;"></td>'+ 
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">PENDAPATAN :</td>'+ 
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PPN PENJUALAN</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right">'+ resultData[i].ppnjual +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; DISCOUNT PENJUALAN</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right">'+ resultData[i].discountjual +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PENDAPATAN DARI PENJUALAN</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right">'+ resultData[i].pendapatanjual +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; BIAYA PRODUKSI</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right" style="border-bottom: 1px solid #111">'+ resultData[i].hpp +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>LABA KOTOR</b></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right"><b>'+ resultData[i].labakotor +'</b></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">BEBAN USAHA :</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right"><b></b></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PENGELUARAN BIAYA</td>'+
                                '<td align="right" style="border-bottom: 1px solid #111">'+ resultData[i].pengeluaranbiaya +'</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right" style="border-bottom: 1px solid #111">'+ resultData[i].bebanusaha +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b> LABA USAHA</b></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right"><b>'+ resultData[i].labausaha +'</b></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">PENDAPATAN DI LUAR USAHA :</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right"><b></b></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PENDAPATAN LAIN</td>'+
                                '<td align="right" style="border-bottom: 1px solid #111">'+ resultData[i].pendapatanlain +'</td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right" style="border-bottom: 1px solid #111">'+ resultData[i].pendapatandiluarusaha +'</td>'+
                                '<td></td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td style="padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b> LABA BERSIH</b></td>'+
                                '<td></td>'+
                                '<td></td>'+
                                '<td align="right"><b>'+ resultData[i].lababersih +'</b></td>'+
                                '<td></td>'+
                            '</tr>';
                }

                $('#show_datalabarugijasa1').html(html); 
                                            
            }
        }); 
    
    }

    setTimeout(() => {
        if($('#tablabarugi1').val()=='tab-labarugisaja'){
            $('#tab-labarugisaja').click();            
        }else if($('#tablabarugi1').val()=='tab-labarugififo'){
            $('#tab-labarugififo').click();            
        }else if($('#tablabarugi1').val()=='tab-labarugimova'){
            $('#tab-labarugimova').click();            
        }else if($('#tablabarugi1').val()=='tab-labarugijasa'){
            $('#tab-labarugijasa').click();            
        }else if($('#tablabarugi1').val()=='tab-labarugilain'){
            $('#tab-labarugilain').click();            
        }else{
            $('#tab-labarugisaja').click();            
        }
    }, 100);

    $('#tab-labarugisaja').on('click',function(){
        $('#tablabarugi1').val('tab-labarugisaja');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-labarugififo').on('click',function(){
        $('#tablabarugi1').val('tab-labarugififo');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-labarugimova').on('click',function(){
        $('#tablabarugi1').val('tab-labarugimova');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-labarugijasa').on('click',function(){
        $('#tablabarugi1').val('tab-labarugijasa');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-labarugilain').on('click',function(){
        $('#tablabarugi1').val('tab-labarugilain');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
   
    //menampilkan combo ruang
    setTimeout(() => {
        tampil_listruang();
    }, 500);
    
    // koneksi_datatable();

    $('#idruang1').on('change',function(){
        $('#event1').val('1');
        setTimeout(() => {
            kirimsyarat();	
        }, 500);
    });

    $("#tgltransaksi1").datepicker({
           dateFormat  : "yy-mm-dd",
           changeMonth : true,
           changeYear  : true         
    });
    
    $("#tgltransaksi2").datepicker({
           dateFormat  : "yy-mm-dd",
           changeMonth : true,
           changeYear  : true         
    });
    
    $('#tgltransaksi1').on('click',function(){
        $('#event1').val('1');       
        setTimeout(() => {
            kirimsyarat();           
        }, 500);     				
    });
    
    $('#tgltransaksi1').on('change',function(){	
        $('#event1').val('1');			
        setTimeout(() => {
            kirimsyarat();
        }, 500);					
    });
    $('#tgltransaksi2').on('click',function(){  
        $('#event1').val('1');     
        setTimeout(() => {
            kirimsyarat();           
        }, 100);     				
    });
    
    $('#tgltransaksi2').on('change',function(){	
        $('#event1').val('1');			
        setTimeout(() => {
            kirimsyarat();
        }, 100);					
    });
    
    function kirimsyarat(){
        var event1=$('#event1').val();
        
        var idruang1=$('#idruang1').val();
        var tablabarugi1=$('#tablabarugi1').val();
        var tgltransaksi1=$('#tgltransaksi1').val();
        var tgltransaksi2=$('#tgltransaksi2').val();
        
        let formData = new FormData();
            formData.append('idruang1', idruang1);
            formData.append('tablabarugi1', tablabarugi1);
            formData.append('tgltransaksi1', tgltransaksi1);
            formData.append('tgltransaksi2', tgltransaksi2);

        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pos01.laporan.labarugi_kirimsyarat')}}',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },				 				
            success : function(formData){ 
                    
                    if(event1=='1'){
                        // $("#idruang1").val(idruang1);                                        
                        // tampil_dataTable(); 
                        setTimeout(() => {
                            labarugisaja1Datatable = tampil_labarugisaja1();
                            labarugififo1Datatable = tampil_labarugififo1();
                            labarugimova1Datatable = tampil_labarugimova1();
                            labarugilifo1Datatable = tampil_labarugilifo1();
                            labarugijasa1Datatable = tampil_labarugijasa1();
                            setTimeout(() => {
                                labarugisaja1Datatable.ajax.url('{{route('pos01.laporan.labarugi_showlabarugisaja')}}').load();                
                                labarugisaja1Datatable.draw(null, false);                                                    
                                labarugififo1Datatable.ajax.url('{{route('pos01.laporan.labarugi_showlabarugififo')}}').load();                
                                labarugififo1Datatable.draw(null, false);                                                    
                                labarugimova1Datatable.ajax.url('{{route('pos01.laporan.labarugi_showlabarugimova')}}').load();                
                                labarugimova1Datatable.draw(null, false);                                                    
                                labarugilifo1Datatable.ajax.url('{{route('pos01.laporan.labarugi_showlabarugilifo')}}').load();                
                                labarugilifo1Datatable.draw(null, false);                                                    
                                labarugijasa1Datatable.ajax.url('{{route('pos01.laporan.labarugi_showlabarugilifo')}}').load();                
                                labarugijasa1Datatable.draw(null, false);                                                    
                            }, 500);            
                        }, 500);
                    }
                },
            error : function(formData){
                 
            }
            
        });
    }
 
    //menampilkan combo ruang
    function tampil_listruang(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.laporan.labarugi_listruang')}}',
            
            success: function(data){				    
                $("#idruang1").html(data);
                $("#idruang1").val({{ $idruang }});

            }
        })                    
    }

    //menampilkan combo barang
    function tampil_listbarang(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.master.barangruang_listbarang')}}',
            
            success: function(data){				    
                $("#idbarang1").html(data);
            }
        })                    
    }

    //menampilkan combo barangedit
    function tampil_listbarangedit(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.master.barcode_listbarang')}}',
            
            success: function(data){				    
                $("#idbarang1").html(data);
            }
        })                    
    }

    function tampil_dataTable(){        
        labarugisaja1Datatable.draw(null, false);        
        labarugififo1Datatable.draw(null, false);        
        labarugilifo1Datatable.draw(null, false);        
        labarugimova1Datatable.draw(null, false);        
        labarugijasa1Datatable.draw(null, false);        
    }

    function koneksi_datatable(){
        labarugisaja1Datatable = tampil_labarugisaja1();    
        labarugififo1Datatable = tampil_labarugififo1();    
        labarugimova1Datatable = tampil_labarugimova1();    
        labarugilifo1Datatable = tampil_labarugilifo1();    
        labarugijasa1Datatable = tampil_labarugijasa1();    
    }

    function swaltambah(x){
        Swal.fire({
            icon: 'success',
            title: 'Save successfully',
            text: x,
            timer:1000
        })
    }

    function swalgagaltambah(x){
        Swal.fire({
            icon: 'error',
            title: 'Oops...failed to add/update record',
            text: x,
            timer:1000
        })
    }

    function swalupdate(x){
        Swal.fire({
            icon: 'success',
            title: 'Update successfully',
            text: x,
            timer:1000
        })
    }

    function swalgagalupdate(x){
        Swal.fire({
            icon: 'error',
            title: 'Oops...failed to update',
            text: x,
            timer:1000
        })
    }

    function swalhapus(x){
        Swal.fire({
            icon: 'success',
            title: 'Delete successfully',
            text: x,
            timer:1000
        })
    }

    function swalgagalhapus(x){
        Swal.fire({
            icon: 'error',
            title: 'Oops...failed to delete',
            text: x,
            timer:1000
        })
    }

    function swalsukseskirim(){
        Swal.fire({
            icon: 'success',
            title: 'Send successfully',
            text: '',
            timer:1000
        })
    }

    function swalgagalkirim(){
        Swal.fire({
            icon: 'error',
            title: 'Oops...failed to send',
            text: '',
            timer:1000
        })
    }

});

</script>	



@endsection