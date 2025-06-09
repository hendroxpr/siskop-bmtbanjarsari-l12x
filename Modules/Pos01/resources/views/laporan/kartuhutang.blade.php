@extends('admin.layouts.main')

@section('contents')

@php
    $idruang = session('idruang1');
    if($idruang==''){
        $idruang = '1';
    }    
   
    $tabstok = session('tabstok1');
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
            <div class="col-md-4">
                {{-- <div class="row">
                    <div class="col-md-3 mt-2 text-right">
                        <h6>Lokasi</h6>
                    </div>
                    <div class="col-md-7">
                        <select name="idruang1" id="idruang1" class="w3-input w3-border" value="{{ $idruang }}"></select>
                        <input name="tabstok1" id="tabstok1" class="" type="hidden" value="{{ $tabstok }}"> 
                        <input name="event1" id="event1" class="" type="hidden" value="0"> 
                    </div>
                </div> --}}

                <div class="row mt-2">
                    <div class="col-md-3" align="right">									
                        <h6 class="mt-2">No. Faktur</h6>
                    </div>
                    <div class="col-md-7 input-group">
                        <select  name="idhutang1" id="idhutang1" class=" form-control w3-input w3-border" style="border-radius:0px; border:none; display:block;"></select>
                        <div class="input-group-append">
                            <button id="btn_carihutang1" name="btn_carihutang1" type="button" style="border-radius:0px; border:none;"><i style="font-size:24" class="fas">&#xf002;</i></button>
                        </div>
                        <select  name="idhutangx1" id="idhutangx1" class="" style="border-radius:0px; border:none; display:block;"></select>
                        <input name="cek1" id="cek1" class="" type="hidden">                                
                        <input name="id1" id="id1" class="" type="hidden"> 
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-3 text-right">
                        <h6 class="mt-2">Supplier</h6>
                    </div>
                    <div class="col-md-7">
                        <input name="supplier1" id="supplier1" class="w3-input w3-border" type="text" placeholder="Supplier" disabled>                       
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-3 text-right">
                        <h6 class="mt-2">Alamat</h6>
                    </div>
                    <div class="col-md-7">
                        <input name="alamat1" id="alamat1" class="w3-input w3-border" type="text" placeholder="Alamat" disabled>                       
                    </div>
                </div>
                {{-- <div class="row mt-2">
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
                </div> --}}
                <div class="row">
                    <div class="col-md-3">
                        {{--  --}}
                    </div>
                    <div class="col-md-7">
                        {{--  --}}
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                 {{--  --}}
            </div>
            <div class="col-md-4">
                <div class="w3-row" align="right"><i class="fa fa-refresh" aria-hidden="true"></i>            
                    <a href="{{ url('/') }}{{ $link }}" class="btn bg-success rounded-0"><i style="font-size:18px" class="fa">&#xf021;</i> Refresh</a>            
                </div> 
            </div>
        </div>

    </div>

    <ul class="nav nav-tabs" id="tab-stok" role="tablist">
        <li class="nav-item">
            <a class="nav-link" id="tab-stokrekap" data-toggle="pill" href="#isi-tab-stokrekap" role="tab" aria-controls="tab-stokrekap" aria-selected="true">Kartu Stok</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-stokrekapfifo" data-toggle="pill" href="#isi-tab-stokrekapfifo" role="tab" aria-controls="tab-stokrekapfifo" aria-selected="false">Kartu Stok FIFO</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-stokrekapmova" data-toggle="pill" href="#isi-tab-stokrekapmova" role="tab" aria-controls="tab-stokrekapmova" aria-selected="false">Kartu Stok Moving Average</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-storekaplifo" data-toggle="pill" href="#isi-tab-stokrekaplifo" role="tab" aria-controls="tab-stokrekaplifo" aria-selected="false">Kartu Stok LIFO</a>
        </li>               
    </ul>

    <!--awal tabel-->        
    <div class="box-body" id="headerjudul" style="display: block;">
        <div class="tab-content mt-3" id="tab-stok-tabContent">
            
            <!--tab-stokrekap -->
            <div class="tab-pane fade" id="isi-tab-stokrekap" role="tabpanel" aria-labelledby="tab-stokrekap">
                <div id="reload" class="table-responsive">
                    <table id="stokrekap1" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>                            
                                <th style="width:20px">Faktur</th>
                                <th style="width:20px">Tanggal</th>
                                <th style="width:20px">Waktu</th>
                                <th style="width:20px">Satuan</th>							
                                <th style="width:10px">Qty</br>Awal</th>							
                                <th style="width:20px">HPP</br>Awal</th>							
                                <th style="width:20px">Total HPP</br>Awal</th>							
                                <th style="width:10px">Qty</br>Masuk</th>							
                                <th style="width:20px">HPP</br>Masuk</th>							
                                <th style="width:20px">Total HPP</br>Masuk</th>							
                                <th style="width:10px">Qty</br>Keluar</th>							
                                <th style="width:20px">HPP</br>Keluar</th>							
                                <th style="width:20px">Total HPP</br>Keluar</th>							
                                <th style="width:10px">Qty</br>Akhir</th>							
                                <th style="width:20px">HPP</br>Akhir</th>							
                                <th style="width:20px">Total HPP</br>Akhir</th>							
                                <th style="width:50px">Keterangan</th>							
                            </tr>
                        </thead>
                        <tfoot id="show_footerstokrekap1">
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
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody id="show_stokrekap1">
                        
                        </tbody>
                    </table>            
                </div>
            </div>
            <!--/tab-stokrekap -->

            <!--tab-stokrekapfifo -->
            <div class="tab-pane fade" id="isi-tab-stokrekapfifo" role="tabpanel" aria-labelledby="tab-stokrekapfifo">
                <div id="reload" class="table-responsive">
                    <table id="stokrekapfifo1" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>                            
                                <th style="width:20px">Faktur</th>
                                <th style="width:20px">Tanggal</th>
                                <th style="width:20px">Waktu</th>
                                <th style="width:20px">Satuan</th>							
                                <th style="width:10px">Qty</br>Awal</th>							
                                <th style="width:20px">HPP</br>Awal</th>							
                                <th style="width:20px">Total HPP</br>Awal</th>							
                                <th style="width:10px">Qty</br>Masuk</th>							
                                <th style="width:20px">HPP</br>Masuk</th>							
                                <th style="width:20px">Total HPP</br>Masuk</th>							
                                <th style="width:10px">Qty</br>Keluar</th>							
                                <th style="width:20px">HPP</br>Keluar</th>							
                                <th style="width:20px">Total HPP</br>Keluar</th>							
                                <th style="width:10px">Qty</br>Akhir</th>							
                                <th style="width:20px">HPP</br>Akhir</th>							
                                <th style="width:20px">Total HPP</br>Akhir</th>							
                                <th style="width:50px">Keterangan</th>							
                            </tr>
                        </thead>
                        <tfoot id="show_footerstokrekapfifo1">
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
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody id="show_stokrekapfifo1">
                        
                        </tbody>
                    </table>            
                </div>
            </div>
            <!--/tab-stokrekapfifo -->

            <!--tab-stokrekapmova -->
            <div class="tab-pane fade" id="isi-tab-stokrekapmova" role="tabpanel" aria-labelledby="tab-stokrekapmova">
                <div id="reload" class="table-responsive">
                    <table id="stokrekapmova1" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>                            
                                <th style="width:20px">Faktur</th>
                                <th style="width:20px">Tanggal</th>
                                <th style="width:20px">Waktu</th>
                                <th style="width:20px">Satuan</th>							
                                <th style="width:10px">Qty</br>Awal</th>							
                                <th style="width:20px">HPP</br>Awal</th>							
                                <th style="width:20px">Total HPP</br>Awal</th>							
                                <th style="width:10px">Qty</br>Masuk</th>							
                                <th style="width:20px">HPP</br>Masuk</th>							
                                <th style="width:20px">Total HPP</br>Masuk</th>							
                                <th style="width:10px">Qty</br>Keluar</th>							
                                <th style="width:20px">HPP</br>Keluar</th>							
                                <th style="width:20px">Total HPP</br>Keluar</th>							
                                <th style="width:10px">Qty</br>Akhir</th>							
                                <th style="width:20px">HPP</br>Akhir</th>							
                                <th style="width:20px">Total HPP</br>Akhir</th>							
                                <th style="width:50px">Keterangan</th>							
                            </tr>
                        </thead>
                        <tfoot id="show_footerstokrekapmova1">
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
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody id="show_stokrekapmova1">
                        
                        </tbody>
                    </table>            
                </div>
            </div>
            <!--/tab-stokrekapmova -->

            <!--tab-stokrekaplifo -->
            <div class="tab-pane fade" id="isi-tab-stokrekaplifo" role="tabpanel" aria-labelledby="tab-stokrekaplifo">
                <div id="reload" class="table-responsive">
                    <table id="stokrekaplifo1" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>                            
                                <th style="width:20px">Faktur</th>
                                <th style="width:20px">Tanggal</th>
                                <th style="width:20px">Waktu</th>
                                <th style="width:20px">Satuan</th>							
                                <th style="width:10px">Qty</br>Awal</th>							
                                <th style="width:20px">HPP</br>Awal</th>							
                                <th style="width:20px">Total HPP</br>Awal</th>							
                                <th style="width:10px">Qty</br>Masuk</th>							
                                <th style="width:20px">HPP</br>Masuk</th>							
                                <th style="width:20px">Total HPP</br>Masuk</th>							
                                <th style="width:10px">Qty</br>Keluar</th>							
                                <th style="width:20px">HPP</br>Keluar</th>							
                                <th style="width:20px">Total HPP</br>Keluar</th>							
                                <th style="width:10px">Qty</br>Akhir</th>							
                                <th style="width:20px">HPP</br>Akhir</th>							
                                <th style="width:20px">Total HPP</br>Akhir</th>							
                                <th style="width:50px">Keterangan</th>							
                            </tr>
                        </thead>
                        <tfoot id="show_footerstokrekaplifo1">
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
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                        <tbody id="show_stokrekaplifo1">
                        
                        </tbody>
                    </table>            
                </div>
            </div>
            <!--/tab-stokrekaplifo -->


        </div>
    </div>    
<!--akhir tabel-->

<!-- ModalCariHutang modal fade-->
	<div class="modal fade" id="ModalCariHutang"  data-backdrop="static">
		<div class="modal-dialog modal-lg">  <!-- modal-(sm, lg, xl) ukuran lebar modal -->
			<div class="modal-content bg-info w3-animate-zoom">
				
				<div class="modal-header">
						<h3 class="modal-title"><i style="font-size:18" class="fas">&#xf002;</i><b> Cari Data Hutang</b></h3>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
						
				</div>
				<form class="form-horizontal">
                    @csrf
					<div class="modal-body">
												
						<div class="row">
							<div id="reload" class="table-responsive">
								<table id="carihutang" width="100%" class="table table-bordered table-striped table-hover">
									<thead>
									<tr>
										<th width="5px">#</th>
										<th width="20px">Kode</th>																									
										<th width="20px">Barcode</th>																									
										<th width="50px">Nama Barang</th>																									
										<th width="30px">Kategori</th>																									
										<th width="20px">Satuan</th>																									
										<th width="10px">image</th>																									
									</tr>
								</thead>
								
								<tfoot id="show_footercarihutang">
									
								</tfoot>
								<tbody id="show_datacarihutang">
								
								</tbody>
								</table>
							</div>			
						</div>
						
					</div>
					<div class="modal-footer justify-content-between" align="right">
						<button type="button" class="w3-button w3-border w3-border-white" data-dismiss="modal">Tutup</button>
					</div>
				</form>
			</div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
	</div>
	<!-- end ModalCariID-->

    <!-- khusus menyimpan data yang akan dihapus -->
    <input name="id3" id="id3"type="hidden">	
    <input name="data3a" id="data3a"type="hidden">	
    <input name="data3b" id="data3b"type="hidden">	
    <input name="data3c" id="data3c"type="hidden">	

</div>


<script type="text/javascript">
    var stokrekap1Datatable;
    var stokrekapmova1Datatable;
    var stokrekapfifo1Datatable;
    var stokrekaplifo1Datatable;

    var listhutangDatatable;
    var carihutangDatatable;
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

    //menampilkan combo hutang
    tampil_listhutang();
    function tampil_listhutang(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.laporan.kartuhutang_listhutang')}}',
            
            success: function(data){				    
                $("#idhutang1").html(data);                
            }
        })                    
    }

    //menampilkan combo hutangx
    tampil_listhutangx1();
    function tampil_listhutangx1(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.laporan.kartuhutang_listhutangx')}}',
            
            success: function(data){				    
                $("#idhutangx1").html(data);                
            }
        })                    
    }
    
    setTimeout(() => {
        if($('#tabstok1').val()=='tab-stokrekap'){
            $('#tab-stokrekap').click();          
        }else if($('#tabstok1').val()=='tab-stokrekapfifo'){
            $('#tab-stokrekapfifo').click();            
        }else if($('#tabstok1').val()=='tab-stokrekapmova'){
            $('#tab-stokrekapmova').click();            
        }else if($('#tabstok1').val()=='tab-stokrekaplifo'){
            $('#tab-stokrekaplifo').click();            
        }else{
            $('#tab-stokrekap').click();            
        }
    }, 100);
    
    $('#tab-stokrekap').on('click',function(){
        $('#tabstok1').val('tab-stokrekap');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-stokrekapfifo').on('click',function(){
        $('#tabstok1').val('tab-stokrekapfifo');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-stokrekapmova').on('click',function(){
        $('#tabstok1').val('tab-stokrekapmova');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-stokrekaplifo').on('click',function(){
        $('#tabstok1').val('tab-stokrekaplifo');
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
    $('#idhutang1').on('change',function(){	
        var x = $('#idhutang1').val();
        $('#idhutangx1').val(x);
        setTimeout(() => {
            var x = $('#idhutangx1 option:selected').text();
            // $('#supplier1').val('x1');
            // $('#alamat1').val('x2');
            const xArray = x.split("|");
            var nomorstatus = aArray[0];
            var x1 = aArray[1];
            let x2 = aArray[2];
            document.getElementById("supplier1").innerHTML = x1;
            document.getElementById("alamat1").innerHTML = x2;
            kirimsyarat();
        }, 100);
    });
    
    function kirimsyarat(){
        var event1=$('#event1').val();
        
        var idruang1=$('#idruang1').val();
        var tabstok1=$('#tabstok1').val();
        var tgltransaksi1=$('#tgltransaksi1').val();
        var tgltransaksi2=$('#tgltransaksi2').val();
        var idhutang1=$('#idhutang1').val();
        
        let formData = new FormData();
            formData.append('idruang1', idruang1);
            formData.append('tabstok1', tabstok1);
            formData.append('tgltransaksi1', tgltransaksi1);
            formData.append('tgltransaksi2', tgltransaksi2);
            formData.append('idhutang1', idhutang1);

        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pos01.laporan.kartustok_kirimsyarat')}}',
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
            url   : '{{route('pos01.laporan.kartustok_listruang')}}',
            
            success: function(data){				    
                $("#idruang1").html(data);
                $("#idruang1").val({{ $idruang }});

            }
        })                    
    }

    function tampil_stokrekap1(){
        let i = 1;	
        return $('#stokrekap1').DataTable({
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
            totalhppawal = api
                .column(7)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            
            totalhppmasuk = api
                .column(10)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            
            totalhppkeluar = api
                .column(13)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            totalhppakhir = api
                .column(16)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Total over this page
            pagetotalhppawal = api
                .column(7, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            pagetotalhppmasuk = api
                .column(10, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            pagetotalhppkeluar = api
                .column(13, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            pagetotalhppakhir = api
                .column(16, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(1).footer().innerHTML = 'SUB TOTAL :';
            api.column(7).footer().innerHTML = formatAngka(pagetotalhppawal,'');
            api.column(10).footer().innerHTML = formatAngka(pagetotalhppmasuk,'');
            api.column(13).footer().innerHTML = formatAngka(pagetotalhppkeluar,'');
            api.column(16).footer().innerHTML = formatAngka(pagetotalhppakhir,'');
            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.kartustok_showstokrekap')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nomorstatus', name: 'nomorstatus', className: 'dt-center' },
                { data: 'tanggal', name: 'created_at', className: 'dt-center' },
                { data: 'waktu', name: 'created_at', className: 'dt-center' },
                { data: 'satuan', name: 'barang.satuan.kode', className: 'dt-center' },
                { data: 'awal', name: 'awal', className: 'dt-center' },
                { data: 'hbsawal', name: 'hbsawal', className: 'dt-right' },
                { data: 'hppawal', name: 'hppawal', className: 'dt-right' },
                { data: 'masuk', name: 'masuk', className: 'dt-center' },
                { data: 'hbsmasuk', name: 'hbsmasuk', className: 'dt-right' },
                { data: 'hppmasuk', name: 'hppmasuk', className: 'dt-right' },
                { data: 'keluar', name: 'keluar', className: 'dt-center' },
                { data: 'hbskeluar', name: 'hbskeluar', className: 'dt-right' },
                { data: 'hppkeluar', name: 'hppkeluar', className: 'dt-right' },
                { data: 'akhir', name: 'akhir', className: 'dt-center' },
                { data: 'hbsakhir', name: 'hbsakhir', className: 'dt-right' },
                { data: 'hppakhir', name: 'hppakhir', className: 'dt-right' },
                { data: 'keterangan', name: 'keterangan', className: 'dt-left' },
            ]
        });
    }

    function tampil_stokrekapfifo1(){
        let i = 1;	
        return $('#stokrekapfifo1').DataTable({
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
            totalhppawal = api
                .column(7)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            
            totalhppmasuk = api
                .column(10)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            
            totalhppkeluar = api
                .column(13)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            totalhppakhir = api
                .column(16)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Total over this page
            pagetotalhppawal = api
                .column(7, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            pagetotalhppmasuk = api
                .column(10, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            pagetotalhppkeluar = api
                .column(13, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            pagetotalhppakhir = api
                .column(16, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(1).footer().innerHTML = 'SUB TOTAL :';
            api.column(7).footer().innerHTML = formatAngka(pagetotalhppawal,'');
            api.column(10).footer().innerHTML = formatAngka(pagetotalhppmasuk,'');
            api.column(13).footer().innerHTML = formatAngka(pagetotalhppkeluar,'');
            api.column(16).footer().innerHTML = formatAngka(pagetotalhppakhir,'');
            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.kartustok_showstokrekapfifo')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nomorstatus', name: 'nomorstatus', className: 'dt-center' },
                { data: 'tanggal', name: 'created_at', className: 'dt-center' },
                { data: 'waktu', name: 'created_at', className: 'dt-center' },
                { data: 'satuan', name: 'barang.satuan.kode', className: 'dt-center' },
                { data: 'awal', name: 'awal', className: 'dt-center' },
                { data: 'hbsawal', name: 'hbsawal', className: 'dt-right' },
                { data: 'hppawal', name: 'hppawal', className: 'dt-right' },
                { data: 'masuk', name: 'masuk', className: 'dt-center' },
                { data: 'hbsmasuk', name: 'hbsmasuk', className: 'dt-right' },
                { data: 'hppmasuk', name: 'hppmasuk', className: 'dt-right' },
                { data: 'keluar', name: 'keluar', className: 'dt-center' },
                { data: 'hbskeluar', name: 'hbskeluar', className: 'dt-right' },
                { data: 'hppkeluar', name: 'hppkeluar', className: 'dt-right' },
                { data: 'akhir', name: 'akhir', className: 'dt-center' },
                { data: 'hbsakhir', name: 'hbsakhir', className: 'dt-right' },
                { data: 'hppakhir', name: 'hppakhir', className: 'dt-right' },
                { data: 'keterangan', name: 'keterangan', className: 'dt-left' },
            ]
        });
    }

    function tampil_stokrekapmova1(){
        let i = 1;	
        return $('#stokrekapmova1').DataTable({
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
            totalhppawal = api
                .column(7)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            
            totalhppmasuk = api
                .column(10)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            
            totalhppkeluar = api
                .column(13)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            totalhppakhir = api
                .column(16)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Total over this page
            pagetotalhppawal = api
                .column(7, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            pagetotalhppmasuk = api
                .column(10, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            pagetotalhppkeluar = api
                .column(13, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            pagetotalhppakhir = api
                .column(16, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(1).footer().innerHTML = 'SUB TOTAL :';
            api.column(7).footer().innerHTML = formatAngka(pagetotalhppawal,'');
            api.column(10).footer().innerHTML = formatAngka(pagetotalhppmasuk,'');
            api.column(13).footer().innerHTML = formatAngka(pagetotalhppkeluar,'');
            api.column(16).footer().innerHTML = formatAngka(pagetotalhppakhir,'');
            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.kartustok_showstokrekapmova')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nomorstatus', name: 'nomorstatus', className: 'dt-center' },
                { data: 'tanggal', name: 'created_at', className: 'dt-center' },
                { data: 'waktu', name: 'created_at', className: 'dt-center' },
                { data: 'satuan', name: 'barang.satuan.kode', className: 'dt-center' },
                { data: 'awal', name: 'awal', className: 'dt-center' },
                { data: 'hbsawal', name: 'hbsawal', className: 'dt-right' },
                { data: 'hppawal', name: 'hppawal', className: 'dt-right' },
                { data: 'masuk', name: 'masuk', className: 'dt-center' },
                { data: 'hbsmasuk', name: 'hbsmasuk', className: 'dt-right' },
                { data: 'hppmasuk', name: 'hppmasuk', className: 'dt-right' },
                { data: 'keluar', name: 'keluar', className: 'dt-center' },
                { data: 'hbskeluar', name: 'hbskeluar', className: 'dt-right' },
                { data: 'hppkeluar', name: 'hppkeluar', className: 'dt-right' },
                { data: 'akhir', name: 'akhir', className: 'dt-center' },
                { data: 'hbsakhir', name: 'hbsakhir', className: 'dt-right' },
                { data: 'hppakhir', name: 'hppakhir', className: 'dt-right' },
                { data: 'keterangan', name: 'keterangan', className: 'dt-left' },
            ]
        });
    }

    function tampil_stokrekaplifo1(){
        let i = 1;	
        return $('#stokrekaplifo1').DataTable({
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
            totalhppawal = api
                .column(7)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            
            totalhppmasuk = api
                .column(10)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            
            totalhppkeluar = api
                .column(13)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            totalhppakhir = api
                .column(16)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Total over this page
            pagetotalhppawal = api
                .column(7, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            pagetotalhppmasuk = api
                .column(10, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            pagetotalhppkeluar = api
                .column(13, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            pagetotalhppakhir = api
                .column(16, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(1).footer().innerHTML = 'SUB TOTAL :';
            api.column(7).footer().innerHTML = formatAngka(pagetotalhppawal,'');
            api.column(10).footer().innerHTML = formatAngka(pagetotalhppmasuk,'');
            api.column(13).footer().innerHTML = formatAngka(pagetotalhppkeluar,'');
            api.column(16).footer().innerHTML = formatAngka(pagetotalhppakhir,'');
            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.kartustok_showstokrekaplifo')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nomorstatus', name: 'nomorstatus', className: 'dt-center' },
                { data: 'tanggal', name: 'created_at', className: 'dt-center' },
                { data: 'waktu', name: 'created_at', className: 'dt-center' },
                { data: 'satuan', name: 'barang.satuan.kode', className: 'dt-center' },
                { data: 'awal', name: 'awal', className: 'dt-center' },
                { data: 'hbsawal', name: 'hbsawal', className: 'dt-right' },
                { data: 'hppawal', name: 'hppawal', className: 'dt-right' },
                { data: 'masuk', name: 'masuk', className: 'dt-center' },
                { data: 'hbsmasuk', name: 'hbsmasuk', className: 'dt-right' },
                { data: 'hppmasuk', name: 'hppmasuk', className: 'dt-right' },
                { data: 'keluar', name: 'keluar', className: 'dt-center' },
                { data: 'hbskeluar', name: 'hbskeluar', className: 'dt-right' },
                { data: 'hppkeluar', name: 'hppkeluar', className: 'dt-right' },
                { data: 'akhir', name: 'akhir', className: 'dt-center' },
                { data: 'hbsakhir', name: 'hbsakhir', className: 'dt-right' },
                { data: 'hppakhir', name: 'hppakhir', className: 'dt-right' },
                { data: 'keterangan', name: 'keterangan', className: 'dt-left' },
            ]
        });
    }

    function tampil_dataTable(){        
        stokrekap1Datatable.draw(null, false);        
        stokrekapfifo1Datatable.draw(null, false);        
        stokrekapmova1Datatable.draw(null, false);        
        stokrekaplifo1Datatable.draw(null, false);        
    }

    function koneksi_datatable(){
        stokrekap1Datatable = tampil_stokrekap1();    
        stokrekapfifo1Datatable = tampil_stokrekapfifo1();    
        stokrekapmova1Datatable = tampil_stokrekapmova1();    
        stokrekaplifo1Datatable = tampil_stokrekaplifo1(); 
    }

   $('#btn_carihutang1').on('click',function(){
        setTimeout(() => {
            $('#ModalCariHutang').modal('show');						
        }, 300);
    });

function tampil_carihutang(){
        let i = 1;	
        return $('#carihutang').DataTable({
            responsive : true,
            retrieve: true,
            autoWidth : true,
            // buttons : [ {extend: 'colvis', postfixButtons: [ 'colvisRestore' ] }, {extend:'copy'}, {extend:'csv'}, {extend: 'pdf', orientation: 'portrait', pageSize: 'A4', title:'{{ $caption }}'}, {extend: 'excel', title: '{{ $caption }}'}, {extend:'print', orientation: 'portrait', pageSize: 'A4', title: '{{ $caption }}'}, ],        
            // dom: 'lBfrtip',
            lengthMenu: [
                [ 10, 25, 50, 100, 500, 1000, 5000, -1 ],
                [ '10', '25', '50', '100', '500','1000','5000', 'All' ]
            ],
            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.kartustok_showbarang')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'kode' },
                { data: 'barcode', name: 'barcode' },
                { data: 'nabara', name: 'nabara' },
                { data: 'kategori', name: 'kategori' },
                { data: 'satuan', name: 'satuan' },
                { data: 'image', name: 'image' },
                               
            ]
        });
    }

     $('#show_datacarihutang').on('click','.item_kode',function(){
        ambilcari(this);        
    });
    $('#show_datacarihutang').on('click','.item_barcode',function(){
        ambilcari(this);        
    });
    $('#show_datacarihutang').on('click','.item_kategori',function(){
        ambilcari(this);        
    });
    $('#show_datacarihutang').on('click','.item_satuan',function(){
        ambilcari(this);        
    });
    $('#show_datacarihutang').on('click','.item_nabara',function(){
        ambilcari(this);        
    });
    $('#show_datacarihutang').on('click','.item_image',function(){
        ambilcari(this);        
    });

    function ambilcari(t){
        var data1 = $(t).attr('data1');
        var data2 = $(t).attr('data2');
        var data3 = $(t).attr('data3');
        var data4 = $(t).attr('data4');
        var data5 = $(t).attr('data5');
        $('#idhutang1').val(data1);
        var a = $('#idruang1 option:selected').text();
        var x = $('#idhutang1 option:selected').text();
        var y = x.trim();
        var z = '<p title="Cari spesifik data pada kolom search ...">Data yang dipilih tidak terdapat pada</br><b>'+a+'</b></p>';
        var k = $('#komen1').html(z);
        $('#event1').val('1');
        setTimeout(() => {
            if(y==''){
                swalgagalpilih(z); 
            }else{
                kirimsyarat();           
                $('#ModalCariHutang').modal('hide');
            }
            
        }, 500);

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

    function swalgagalpilih(x){
        Swal.fire({
            icon: 'info',
            title: 'Oops...failed to select',
            html: x,
            timer:5000
        })
    }

});

</script>	



@endsection