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
            <a class="nav-link" id="tab-labarugimasuk" data-toggle="pill" href="#isi-tab-labarugimasuk" role="tab" aria-controls="tab-labarugimasuk" aria-selected="true">Laba Rugi</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-labarugimasukfifo" data-toggle="pill" href="#isi-tab-labarugimasukfifo" role="tab" aria-controls="tab-labarugimasukfifo" aria-selected="false">Laba Rugi FIFO</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-labarugimasukmova" data-toggle="pill" href="#isi-tab-labarugimasukmova" role="tab" aria-controls="tab-labarugimasukmova" aria-selected="false">Laba Rugi Moving Average</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-labarugimasuklifo" data-toggle="pill" href="#isi-tab-labarugimasuklifo" role="tab" aria-controls="tab-labarugimasuklifo" aria-selected="false">Laba Rugi LIFO</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-labarugikeluar" data-toggle="pill" href="#isi-tab-labarugikeluar" role="tab" aria-controls="tab-labarugikeluar" aria-selected="false">Laba Rugi Lain</a>
        </li>
             
    </ul>

    <!--awal tabel-->        
    <div class="box-body" id="headerjudul" style="display: block;">
        <div class="tab-content mt-3" id="tab-labarugi-tabContent">

            <!--tab-labarugimasuk -->
            <div class="tab-pane fade" id="isi-tab-labarugimasuk" role="tabpanel" aria-labelledby="tab-labarugimasuk">
                <div id="reload" class="table-responsive">
                   
                   {{-- <table id="example1" class="table table-bordered table-striped table-hover" style="width: 100%"> --}}
                   <table id="example1" class="" style="width: 100%;">
                    <thead>
                        <tr style="display: none;">
                            <th style="width:500x; height:1px;">&nbsp;</th>                            
							<th style="width:200px"></th>							
							<th style="width:10px"></th>							
							<th style="width:200px"></th>							
							<th style="width:10px"></th>							
                        </tr>
                        
                    </thead>
                    <tfoot id="show_footer">
                        
                    </tfoot>
                    <tbody id="show_data">
                        
                    </tbody>
                </table>
                   
                   
                    {{-- <table id="labarugimasuk1" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>                            
                                <th style="width:20px">Faktur</th>
                                <th style="width:20px">Tanggal</th>
                                <th style="width:50px">Kode</th>
                                <th style="width:50px">Barcode</th>
                                <th style="width:300px">Nama Barang</th>							
                                <th style="width:20px">Satuan</th>							
                                <th style="width:10px">Qty</th>							
                                <th style="width:20px">HPP</th>							
                                <th style="width:20px">Total HPP</th>							
                                <th style="width:50px">Keterangan</th>							
                            </tr>
                        </thead>
                        <tfoot id="show_footerlabarugimasuk1">
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>                            
                        </tfoot>
                        <tbody id="show_labarugimasuk1">
                        
                        </tbody>
                    </table>             --}}
                </div>
            </div>
            <!--/tab-labarugimasuk -->
            
            <!--tab-labarugimasukfifo -->
            <div class="tab-pane fade" id="isi-tab-labarugimasukfifo" role="tabpanel" aria-labelledby="tab-labarugimasukfifo">
                <div id="reload" class="table-responsive">
                    <table id="labarugimasukfifo1" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>                            
                                <th style="width:20px">Faktur</th>
                                <th style="width:20px">Tanggal</th>
                                <th style="width:50px">Kode</th>
                                <th style="width:50px">Barcode</th>
                                <th style="width:300px">Nama Barang</th>							
                                <th style="width:20px">Satuan</th>							
                                <th style="width:10px">Qty</th>							
                                <th style="width:20px">HPP</th>							
                                <th style="width:20px">Total HPP</th>							
                                <th style="width:50px">Keterangan</th>							
                            </tr>
                        </thead>
                        <tfoot id="show_footerlabarugimasukfifo1">
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody id="show_labarugimasukfifo1">
                        
                        </tbody>
                    </table>            
                </div>
            </div>
            <!--/tab-labarugimasukfifo -->
            
            <!--tab-labarugimasukmova -->
            <div class="tab-pane fade" id="isi-tab-labarugimasukmova" role="tabpanel" aria-labelledby="tab-labarugimasukmova">
                <div id="reload" class="table-responsive">
                    <table id="labarugimasukmova1" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>                            
                                <th style="width:20px">Faktur</th>
                                <th style="width:20px">Tanggal</th>
                                <th style="width:50px">Kode</th>
                                <th style="width:50px">Barcode</th>
                                <th style="width:300px">Nama Barang</th>							
                                <th style="width:20px">Satuan</th>							
                                <th style="width:10px">Qty</th>							
                                <th style="width:20px">HPP</th>							
                                <th style="width:20px">Total HPP</th>							
                                <th style="width:50px">Keterangan</th>							
                            </tr>
                        </thead>
                        <tfoot id="show_footerlabarugimasukmova1">
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody id="show_labarugimasukmova1">
                        
                        </tbody>
                    </table>            
                </div>
            </div>
            <!--/tab-labarugimasukmova -->

            <!--tab-labarugimasuklifo -->
            <div class="tab-pane fade" id="isi-tab-labarugimasuklifo" role="tabpanel" aria-labelledby="tab-labarugimasuklifo">
                <div id="reload" class="table-responsive">
                    <table id="labarugimasuklifo1" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>                            
                                <th style="width:20px">Faktur</th>
                                <th style="width:20px">Tanggal</th>
                                <th style="width:50px">Kode</th>
                                <th style="width:50px">Barcode</th>
                                <th style="width:300px">Nama Barang</th>							
                                <th style="width:20px">Satuan</th>							
                                <th style="width:10px">Qty</th>							
                                <th style="width:20px">HPP</th>							
                                <th style="width:20px">Total HPP</th>							
                                <th style="width:50px">Keterangan</th>								
                            </tr>
                        </thead>
                        <tfoot id="show_footerlabarugimasuklifo1">
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody id="show_labarugimasuklifo1">
                        
                        </tbody>
                    </table>            
                </div>
            </div>
            <!--/tab-labarugimasuklifo -->

            <!--tab-labarugikeluar -->
            <div class="tab-pane fade" id="isi-tab-labarugikeluar" role="tabpanel" aria-labelledby="tab-labarugikeluar">
                <div id="reload" class="table-responsive">
                    <table id="labarugikeluar1" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>                            
                                <th style="width:20px">Faktur</th>
                                <th style="width:20px">Tanggal</th>
                                <th style="width:50px">Kode</th>
                                <th style="width:50px">Barcode</th>
                                <th style="width:300px">Nama Barang</th>							
                                <th style="width:20px">Satuan</th>							
                                <th style="width:10px">Qty</th>							
                                <th style="width:20px">HPP</th>							
                                <th style="width:20px">Total HPP</th>							
                                <th style="width:50px">Keterangan</th>							
                            </tr>
                        </thead>
                        <tfoot id="show_footerlabarugikeluar1">
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody id="show_labarugikeluar1">
                        
                        </tbody>
                    </table>            
                </div>
            </div>
            <!--/tab-labarugikeluar -->

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
    var labarugimasuk1Datatable;
    var labarugimasukfifo1Datatable;
    var labarugimasukmova1Datatable;
    var labarugimasuklifo1Datatable;
    var labarugikeluar1Datatable;
   
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

    
    tampil_data();  
    tampil_tombol();


    function tampil_tombol(){
        $('#example1').DataTable( {
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
		    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');   
     } 
         
      

    //tampilkan dalam tabel ->OK
    function tampil_data(){	
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

                $('#show_data').html(html); 
                                            
            }
        }); 
    
    }





    
    setTimeout(() => {
        if($('#tablabarugi1').val()=='tab-labarugimasuk'){
            $('#tab-labarugimasuk').click();            
        }else if($('#tablabarugi1').val()=='tab-labarugimasukfifo'){
            $('#tab-labarugimasukfifo').click();            
        }else if($('#tablabarugi1').val()=='tab-labarugimasukmova'){
            $('#tab-labarugimasukmova').click();            
        }else if($('#tablabarugi1').val()=='tab-labarugimasuklifo'){
            $('#tab-labarugimasuklifo').click();            
        }else if($('#tablabarugi1').val()=='tab-labarugikeluar'){
            $('#tab-labarugikeluar').click();            
        }else{
            $('#tab-labarugimasuk').click();            
        }
    }, 100);

    $('#tab-labarugimasuk').on('click',function(){
        $('#tablabarugi1').val('tab-labarugimasuk');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-labarugimasukfifo').on('click',function(){
        $('#tablabarugi1').val('tab-labarugimasukfifo');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-labarugimasukmova').on('click',function(){
        $('#tablabarugi1').val('tab-labarugimasukmova');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-labarugimasuklifo').on('click',function(){
        $('#tablabarugi1').val('tab-labarugimasuklifo');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-labarugikeluar').on('click',function(){
        $('#tablabarugi1').val('tab-labarugikeluar');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
   
    //menampilkan combo ruang
    setTimeout(() => {
        tampil_listruang();
    }, 500);
    
    koneksi_datatable();

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
                        $("#idruang1").val(idruang1);                                        
                        tampil_dataTable();                   
                    }


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

    function tampil_labarugimasuk1(){
        let i = 1;	
        return $('#labarugimasuk1').DataTable({
            responsive : true,
            retrieve: true,
            autoWidth : true,
            buttons : [ {extend: 'colvis', postfixButtons: [ 'colvisRestore' ] }, {extend:'copy'}, {extend:'csv'}, {extend: 'pdf', orientation: 'portrait', pageSize: 'A4', title:'{{ $caption }}'}, {extend: 'excel', title: '{{ $caption }}'}, {extend:'print', orientation: 'portrait', pageSize: 'A4', title: '{{ $caption }}'}, ],        
            dom: 'lBfrtip',
            lengthMenu: [
                [ 10, 25, 50, 100, 500, 1000, 5000, -1 ],
                [ '10', '25', '50', '100', '500','1000','5000', 'All' ]
            ],
            
            footerCallback: function (row, data, start, end, display) {
            let api = this.api();
    
            // Remove the formatting to get integer data for summation
            let intVal = function (i) {
                return typeof i === 'string'
                    ? i.replace(/[\$,]/g, '') * 1
                    : typeof i === 'number'
                    ? i
                    : 0;
            };
    
            // Total over all pages
            totalhpp = api
                .column(9)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Total over this page
            pagetotalhpp = api
                .column(9, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(5).footer().innerHTML = 'SUB TOTAL :';
            api.column(9).footer().innerHTML = formatAngka(pagetotalhpp,'');
            },


            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.stokkeluarmasuk_showstokmasuk')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nomorstatus', name: 'nomorstatus', className: 'dt-center' },
                { data: 'tglstatus', name: 'tglstatus', className: 'dt-center' },
                { data: 'kode', name: 'barang.kode', className: 'dt-center' },
                { data: 'barcode', name: 'barang.barcode', className: 'dt-center' },
                { data: 'barang', name: 'barang.barang' },
                { data: 'satuan', name: 'barang.satuan.kode', className: 'dt-center' },
                { data: 'masuk', name: 'masuk', className: 'dt-center' },
                { data: 'hbsmasuk', name: 'hbsmasuk', className: 'dt-right' },
                { data: 'hppmasuk', name: 'hppmasuk', className: 'dt-right' },
                { data: 'keterangan', name: 'keterangan', className: 'dt-left' },
            ]
        });
    }
    
    function tampil_labarugimasukfifo1(){
        let i = 1;	
        return $('#labarugimasukfifo1').DataTable({
            responsive : true,
            retrieve: true,
            autoWidth : true,
            buttons : [ {extend: 'colvis', postfixButtons: [ 'colvisRestore' ] }, {extend:'copy'}, {extend:'csv'}, {extend: 'pdf', orientation: 'portrait', pageSize: 'A4', title:'{{ $caption }}'}, {extend: 'excel', title: '{{ $caption }}'}, {extend:'print', orientation: 'portrait', pageSize: 'A4', title: '{{ $caption }}'}, ],        
            dom: 'lBfrtip',
            lengthMenu: [
                [ 10, 25, 50, 100, 500, 1000, 5000, -1 ],
                [ '10', '25', '50', '100', '500','1000','5000', 'All' ]
            ],
            footerCallback: function (row, data, start, end, display) {
            let api = this.api();
    
            // Remove the formatting to get integer data for summation
            let intVal = function (i) {
                return typeof i === 'string'
                    ? i.replace(/[\$,]/g, '') * 1
                    : typeof i === 'number'
                    ? i
                    : 0;
            };
    
            // Total over all pages
            totalhpp = api
                .column(9)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Total over this page
            pagetotalhpp = api
                .column(9, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(5).footer().innerHTML = 'SUB TOTAL :';
            api.column(9).footer().innerHTML = formatAngka(pagetotalhpp,'');
            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.stokkeluarmasuk_showstokmasukfifo')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nomorstatus', name: 'nomorstatus', className: 'dt-center' },
                { data: 'tglstatus', name: 'tglstatus', className: 'dt-center' },
                { data: 'kode', name: 'barang.kode', className: 'dt-center' },
                { data: 'barcode', name: 'barang.barcode', className: 'dt-center' },
                { data: 'barang', name: 'barang.barang' },
                { data: 'satuan', name: 'barang.satuan.kode', className: 'dt-center' },
                { data: 'masuk', name: 'masuk', className: 'dt-center' },
                { data: 'hbsmasuk', name: 'hbsmasuk', className: 'dt-right' },
                { data: 'hppmasuk', name: 'hppmasuk', className: 'dt-right' },
                { data: 'keterangan', name: 'keterangan', className: 'dt-left' },
            ]
        });
    }
    
    function tampil_labarugimasukmova1(){
        let i = 1;	
        return $('#labarugimasukmova1').DataTable({
            responsive : true,
            retrieve: true,
            autoWidth : true,
            buttons : [ {extend: 'colvis', postfixButtons: [ 'colvisRestore' ] }, {extend:'copy'}, {extend:'csv'}, {extend: 'pdf', orientation: 'portrait', pageSize: 'A4', title:'{{ $caption }}'}, {extend: 'excel', title: '{{ $caption }}'}, {extend:'print', orientation: 'portrait', pageSize: 'A4', title: '{{ $caption }}'}, ],        
            dom: 'lBfrtip',
            lengthMenu: [
                [ 10, 25, 50, 100, 500, 1000, 5000, -1 ],
                [ '10', '25', '50', '100', '500','1000','5000', 'All' ]
            ],

            footerCallback: function (row, data, start, end, display) {
            let api = this.api();
    
            // Remove the formatting to get integer data for summation
            let intVal = function (i) {
                return typeof i === 'string'
                    ? i.replace(/[\$,]/g, '') * 1
                    : typeof i === 'number'
                    ? i
                    : 0;
            };
    
            // Total over all pages
            totalhpp = api
                .column(9)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Total over this page
            pagetotalhpp = api
                .column(9, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(5).footer().innerHTML = 'SUB TOTAL :';
            api.column(9).footer().innerHTML = formatAngka(pagetotalhpp,'');
            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.stokkeluarmasuk_showstokmasukmova')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nomorstatus', name: 'nomorstatus', className: 'dt-center' },
                { data: 'tglstatus', name: 'tglstatus', className: 'dt-center' },
                { data: 'kode', name: 'barang.kode', className: 'dt-center' },
                { data: 'barcode', name: 'barang.barcode', className: 'dt-center' },
                { data: 'barang', name: 'barang.barang' },
                { data: 'satuan', name: 'barang.satuan.kode', className: 'dt-center' },
                { data: 'masuk', name: 'masuk', className: 'dt-center' },
                { data: 'hbsmasuk', name: 'hbsmasuk', className: 'dt-right' },
                { data: 'hppmasuk', name: 'hppmasuk', className: 'dt-right' },
                { data: 'keterangan', name: 'keterangan', className: 'dt-left' },
            ]
        });
    }
    
    function tampil_labarugimasuklifo1(){
        let i = 1;	
        return $('#labarugimasuklifo1').DataTable({
            responsive : true,
            retrieve: true,
            autoWidth : true,
            buttons : [ {extend: 'colvis', postfixButtons: [ 'colvisRestore' ] }, {extend:'copy'}, {extend:'csv'}, {extend: 'pdf', orientation: 'portrait', pageSize: 'A4', title:'{{ $caption }}'}, {extend: 'excel', title: '{{ $caption }}'}, {extend:'print', orientation: 'portrait', pageSize: 'A4', title: '{{ $caption }}'}, ],        
            dom: 'lBfrtip',
            lengthMenu: [
                [ 10, 25, 50, 100, 500, 1000, 5000, -1 ],
                [ '10', '25', '50', '100', '500','1000','5000', 'All' ]
            ],

            footerCallback: function (row, data, start, end, display) {
            let api = this.api();
    
            // Remove the formatting to get integer data for summation
            let intVal = function (i) {
                return typeof i === 'string'
                    ? i.replace(/[\$,]/g, '') * 1
                    : typeof i === 'number'
                    ? i
                    : 0;
            };
    
            // Total over all pages
            totalhpp = api
                .column(9)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Total over this page
            pagetotalhpp = api
                .column(9, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(5).footer().innerHTML = 'SUB TOTAL :';
            api.column(9).footer().innerHTML = formatAngka(pagetotalhpp,'');
            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.stokkeluarmasuk_showstokmasuklifo')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nomorstatus', name: 'nomorstatus', className: 'dt-center' },
                { data: 'tglstatus', name: 'tglstatus', className: 'dt-center' },
                { data: 'kode', name: 'barang.kode', className: 'dt-center' },
                { data: 'barcode', name: 'barang.barcode', className: 'dt-center' },
                { data: 'barang', name: 'barang.barang' },
                { data: 'satuan', name: 'barang.satuan.kode', className: 'dt-center' },
                { data: 'masuk', name: 'masuk', className: 'dt-center' },
                { data: 'hbsmasuk', name: 'hbsmasuk', className: 'dt-right' },
                { data: 'hppmasuk', name: 'hppmasuk', className: 'dt-right' },
                { data: 'keterangan', name: 'keterangan', className: 'dt-left' },
            ]
        });
    }
    
    function tampil_labarugikeluar1(){
        let i = 1;	
        return $('#labarugikeluar1').DataTable({
            responsive : true,
            retrieve: true,
            autoWidth : true,
            buttons : [ {extend: 'colvis', postfixButtons: [ 'colvisRestore' ] }, {extend:'copy'}, {extend:'csv'}, {extend: 'pdf', orientation: 'portrait', pageSize: 'A4', title:'{{ $caption }}'}, {extend: 'excel', title: '{{ $caption }}'}, {extend:'print', orientation: 'portrait', pageSize: 'A4', title: '{{ $caption }}'}, ],        
            dom: 'lBfrtip',
            lengthMenu: [
                [ 10, 25, 50, 100, 500, 1000, 5000, -1 ],
                [ '10', '25', '50', '100', '500','1000','5000', 'All' ]
            ],

            footerCallback: function (row, data, start, end, display) {
            let api = this.api();
    
            // Remove the formatting to get integer data for summation
            let intVal = function (i) {
                return typeof i === 'string'
                    ? i.replace(/[\$,]/g, '') * 1
                    : typeof i === 'number'
                    ? i
                    : 0;
            };
    
            // Total over all pages
            totalhpp = api
                .column(9)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Total over this page
            pagetotalhpp = api
                .column(9, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(5).footer().innerHTML = 'SUB TOTAL :';
            api.column(9).footer().innerHTML = formatAngka(pagetotalhpp,'');
            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.stokkeluarmasuk_showstokkeluar')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nomorstatus', name: 'nomorstatus', className: 'dt-center' },
                { data: 'tglstatus', name: 'tglstatus', className: 'dt-center' },
                { data: 'kode', name: 'barang.kode', className: 'dt-center' },
                { data: 'barcode', name: 'barang.barcode', className: 'dt-center' },
                { data: 'barang', name: 'barang.barang' },
                { data: 'satuan', name: 'barang.satuan.kode', className: 'dt-center' },
                { data: 'keluar', name: 'keluar', className: 'dt-center' },
                { data: 'hbskeluar', name: 'hbskeluar', className: 'dt-right' },
                { data: 'hppkeluar', name: 'hppkeluar', className: 'dt-right' },
                { data: 'keterangan', name: 'keterangan', className: 'dt-left' },
            ]
        });
    }
   
    function tampil_dataTable(){        
        labarugimasuk1Datatable.draw(null, false);        
        labarugimasukfifo1Datatable.draw(null, false);        
        labarugimasuklifo1Datatable.draw(null, false);        
        labarugimasukmova1Datatable.draw(null, false);        
        labarugikeluar1Datatable.draw(null, false);        
    }

    function koneksi_datatable(){
        labarugimasuk1Datatable = tampil_labarugimasuk1();    
        labarugimasukfifo1Datatable = tampil_labarugimasukfifo1();    
        labarugimasukmova1Datatable = tampil_labarugimasukmova1();    
        labarugimasuklifo1Datatable = tampil_labarugimasuklifo1();    
        labarugikeluar1Datatable = tampil_labarugikeluar1();    
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