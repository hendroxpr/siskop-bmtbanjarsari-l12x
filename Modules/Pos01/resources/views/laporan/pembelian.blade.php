@extends('admin.layouts.main')

@section('contents')

@php
    $idruang = session('idruang1');
    if($idruang==''){
        $idruang = '1';
    }    
    $idjenispembayaran = session('idjenispembayaran1');
    if($idjenispembayaran==''){
        $idjenispembayaran = '1';
    }    
   
    $tabpembelian = session('tabpembelian1');
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
                        <input name="tabpembelian1" id="tabpembelian1" class="" type="hidden" value="{{ $tabpembelian }}"> 
                        <input name="event1" id="event1" class="" type="hidden" value="0"> 
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-3 text-right">
                        <h6 class="mt-2">Periode Tanggal</h6>
                    </div>
                    <div class="col-md-3" style="padding-right: 0px;">
                        <input name="tgltransaksi1" id="tgltransaksi1" class="w3-input w3-border" maxlength="10" type="text" placeholder="Tanggal awal" autocomplete="off" value="{{ $tgltransaksi1 }}">                       
                    </div>
                    <div class="col-md-1 text-center" style="padding-right: 0px; padding-left: 0px;">
                        <h6 class="mt-2">s/d</h6>
                    </div>
                    <div class="col-md-3" style="padding-left: 0px;">
                        <input name="tgltransaksi2" id="tgltransaksi2" class="w3-input w3-border" maxlength="10" type="text" placeholder="Tanggal akhir" autocomplete="off" value="{{ $tgltransaksi2 }}">                       
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-3" align="right">									
                        <h6 class="mt-2">Jenis Pembayaran</h6>
                    </div>
                    <div class="col-md-7 input-group">
                        <select  name="idjenispembayaran1" id="idjenispembayaran1" class=" form-control w3-input w3-border" style="border-radius:0px; border:none; display:block;" value="{{ $idjenispembayaran }}"></select>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-3" align="right">									
                        <h6 class="mt-2">Supplier</h6>
                    </div>
                    <div class="col-md-7 input-group">
                        <select  name="idsupplier1" id="idsupplier1" class=" form-control w3-input w3-border" style="border-radius:0px; border:none; display:block;"></select>
                        <div class="input-group-append">
                            <button id="btn_carisupplier1" name="btn_carisupplier1" type="button" style="border-radius:0px; border:none;"><i style="font-size:24" class="fas">&#xf002;</i></button>
                        </div>
                        <select  name="idsupplierx1" id="idsupplierx1" class="" style="border-radius:0px; border:none; display:none;"></select>
                        <input name="cek1" id="cek1" class="" type="hidden">                                
                        <input name="id1" id="id1" class="" type="hidden"> 
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

    <ul class="nav nav-tabs" id="tab-pembelian" role="tablist">
        <li class="nav-item">
            <a class="nav-link" id="tab-pembeliandetail" data-toggle="pill" href="#isi-tab-pembeliandetail" role="tab" aria-controls="tab-pembeliandetail" aria-selected="true">Pembelian Detail</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-pembelianperitem" data-toggle="pill" href="#isi-tab-pembelianperitem" role="tab" aria-controls="tab-pembelianperitem" aria-selected="false">Pembelian Per Item</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-pembelianpersupplier" data-toggle="pill" href="#isi-tab-pembelianpersupplier" role="tab" aria-controls="tab-pembelianpersupplier" aria-selected="false">Pembelian Per Supplier</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-pembelianperfaktur" data-toggle="pill" href="#isi-tab-pembelianperfaktur" role="tab" aria-controls="tab-pembelianperfaktur" aria-selected="false">Pembelian Per Faktur</a>
        </li>               
        <li class="nav-item">
            <a class="nav-link" id="tab-pembelianperjenispembayaran" data-toggle="pill" href="#isi-tab-pembelianperjenispembayaran" role="tab" aria-controls="tab-pembelianperjenispembayaran" aria-selected="false">Pembelian Per Jenis Pembayaran</a>
        </li>               
    </ul>

    <!--awal tabel-->        
    <div class="box-body" id="headerjudul" style="display: block;">
        <div class="tab-content mt-3" id="tab-pembelian-tabContent">
            
            <!--tab-pembeliandetail -->
            <div class="tab-pane fade" id="isi-tab-pembeliandetail" role="tabpanel" aria-labelledby="tab-pembeliandetail">
                <div id="reload" class="table-responsive">
                    <table id="pembeliandetail1" class="table table-bordered table-striped table-hover" style="width: 100%">
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
                        <tfoot id="show_footerpembeliandetail1">
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
                        <tbody id="show_pembeliandetail1">
                        
                        </tbody>
                    </table>            
                </div>
            </div>
            <!--/tab-pembeliandetail -->

            <!--tab-pembelianperitem -->
            <div class="tab-pane fade" id="isi-tab-pembelianperitem" role="tabpanel" aria-labelledby="tab-pembelianperitem">
                <div id="reload" class="table-responsive">
                    <table id="pembelianperitem1" class="table table-bordered table-striped table-hover" style="width: 100%">
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
                        <tfoot id="show_footerpembelianperitem1">
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
                        <tbody id="show_pembelianperitem1">
                        
                        </tbody>
                    </table>            
                </div>
            </div>
            <!--/tab-pembelianperitem -->

            <!--tab-pembelianpersupplier -->
            <div class="tab-pane fade" id="isi-tab-pembelianpersupplier" role="tabpanel" aria-labelledby="tab-pembelianpersupplier">
                <div id="reload" class="table-responsive">
                    <table id="pembelianpersupplier1" class="table table-bordered table-striped table-hover" style="width: 100%">
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
                        <tfoot id="show_footerpembelianpersupplier1">
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
                        <tbody id="show_pembelianpersupplier1">
                        
                        </tbody>
                    </table>            
                </div>
            </div>
            <!--/tab-pembelianpersupplier -->

            <!--tab-pembelianperfaktur -->
            <div class="tab-pane fade" id="isi-tab-pembelianperfaktur" role="tabpanel" aria-labelledby="tab-pembelianperfaktur">
                <div id="reload" class="table-responsive">
                    <table id="pembelianperfaktur1" class="table table-bordered table-striped table-hover" style="width: 100%">
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
                        <tfoot id="show_footerpembelianperfaktur1">
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
                        <tbody id="show_pembelianperfaktur1">
                        
                        </tbody>
                    </table>            
                </div>
            </div>
            <!--/tab-pembelianperfaktur -->


        </div>
    </div>    
<!--akhir tabel-->

<!-- ModalCariSupplier modal fade-->
	<div class="modal fade" id="ModalCariSupplier"  data-backdrop="static">
		<div class="modal-dialog modal-lg">  <!-- modal-(sm, lg, xl) ukuran lebar modal -->
			<div class="modal-content bg-info w3-animate-zoom">
				
				<div class="modal-header">
						<h3 class="modal-title"><i style="font-size:18" class="fas">&#xf002;</i><b> Cari Data Supplier</b></h3>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
						
				</div>
				<form class="form-horizontal">
                    @csrf
					<div class="modal-body">
												
						<div class="row">
							<div id="reload" class="table-responsive">
								<table id="carisupplier" width="100%" class="table table-bordered table-striped table-hover">
									<thead>
									<tr>
										<th width="5px">#</th>
										<th width="20px">Kode</th>																									
										<th width="100px">Supplier</th>																									
										<th width="100px">Alamat</th>																									
									</tr>
								</thead>
								
								<tfoot id="show_footercarisupplier">
									
								</tfoot>
								<tbody id="show_datacarisupplier">
								
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
    var pembeliandetail1Datatable;
    var pembelianpersupplier1Datatable;
    var pembelianperitem1Datatable;
    var pembelianperfaktur1Datatable;

    var listsupplierDatatable;
    var carisupplierDatatable;
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

    //menampilkan combo jenispembayaran
    tampil_listjenispembayaran();
    function tampil_listjenispembayaran(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.laporan.pembelian_listjenispembayaran')}}',
            
            success: function(data){				    
                $("#idjenispembayaran1").html(data); 
                $("#idjenispembayaran1").val({{ $idjenispembayaran }});               
            }
        })                    
    }
    //menampilkan combo supplier
    function tampil_listsupplier(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.laporan.pembelian_listsupplier')}}',
            
            success: function(data){				    
                $("#idsupplier1").html(data);                
            }
        })                    
    }
    
    setTimeout(() => {
        if($('#tabpembelian1').val()=='tab-pembeliandetail'){
            $('#tab-pembeliandetail').click();          
        }else if($('#tabpembelian1').val()=='tab-pembelianperitem'){
            $('#tab-pembelianperitem').click();            
        }else if($('#tabpembelian1').val()=='tab-pembelianpersupplier'){
            $('#tab-pembelianpersupplier').click();            
        }else if($('#tabpembelian1').val()=='tab-pembelianperfaktur'){
            $('#tab-pembelianperfaktur').click();            
        }else{
            $('#tab-pembeliandetail').click();            
        }
    }, 100);
    
    $('#tab-pembeliandetail').on('click',function(){
        $('#tabpembelian1').val('tab-pembeliandetail');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-pembelianperitem').on('click',function(){
        $('#tabpembelian1').val('tab-pembelianperitem');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-pembelianpersupplier').on('click',function(){
        $('#tabpembelian1').val('tab-pembelianpersupplier');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-pembelianperfaktur').on('click',function(){
        $('#tabpembelian1').val('tab-pembelianperfaktur');
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
            setTimeout(() => {
                listsupplierDatatable = tampil_listsupplier(); 
                carisupplierDatatable = tampil_carisupplier();                  
                setTimeout(() => {
                    listsupplierDatatable.ajax.url('{{route('pos01.laporan.pembelian_listsupplier')}}').load();                
                    listsupplierDatatable.draw(null, false);                                                    
                    carisupplierDatatable.ajax.url('{{route('pos01.laporan.pembelian_showsupplier')}}').load();                
                    carisupplierDatatable.draw(null, false);
                }, 500);            
            }, 500);
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
    $('#idsupplier1').on('change',function(){	
        $('#event1').val('1');	
        setTimeout(() => {
            kirimsyarat();
        }, 100);					
    });

    $('#idjenispembayaran1').on('change',function(){	
        $('#event1').val('1');	
        setTimeout(() => {
            kirimsyarat();
        }, 100);					
    });
    
    function kirimsyarat(){
        var event1=$('#event1').val();
        
        var idruang1=$('#idruang1').val();
        var tabpembelian1=$('#tabpembelian1').val();
        var tgltransaksi1=$('#tgltransaksi1').val();
        var tgltransaksi2=$('#tgltransaksi2').val();
        var idsupplier1=$('#idsupplier1').val();
        var idjenispembayaran1=$('#idjenispembayaran1').val();
        
        let formData = new FormData();
            formData.append('idruang1', idruang1);
            formData.append('tabpembelian1', tabpembelian1);
            formData.append('tgltransaksi1', tgltransaksi1);
            formData.append('tgltransaksi2', tgltransaksi2);
            formData.append('idsupplier1', idsupplier1);
            formData.append('idjenispembayaran1', idjenispembayaran1);

        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pos01.laporan.pembelian_kirimsyarat')}}',
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
                        $("#idjenispembayaran1").val(idjenispembayaran1);                                        
                        $("#idsupplier1").val(idsupplier1);                                        
                        tampil_dataTable();                   
                    }
                }
        });
    }
 
    //menampilkan combo ruang
    function tampil_listruang(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.laporan.pembelian_listruang')}}',
            
            success: function(data){				    
                $("#idruang1").html(data);
                $("#idruang1").val({{ $idruang }});

            }
        })                    
    }

    function tampil_pembeliandetail1(){
        let i = 1;	
        return $('#pembeliandetail1').DataTable({
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

    function tampil_pembelianperitem1(){
        let i = 1;	
        return $('#pembelianperitem1').DataTable({
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

    function tampil_pembelianpersupplier1(){
        let i = 1;	
        return $('#pembelianpersupplier1').DataTable({
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

    function tampil_pembelianperfaktur1(){
        let i = 1;	
        return $('#pembelianperfaktur1').DataTable({
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
        pembeliandetail1Datatable.draw(null, false);        
        pembelianperitem1Datatable.draw(null, false);        
        pembelianpersupplier1Datatable.draw(null, false);        
        pembelianperfaktur1Datatable.draw(null, false);        
    }

    function koneksi_datatable(){
        pembeliandetail1Datatable = tampil_pembeliandetail1();    
        pembelianperitem1Datatable = tampil_pembelianperitem1();    
        pembelianpersupplier1Datatable = tampil_pembelianpersupplier1();    
        pembelianperfaktur1Datatable = tampil_pembelianperfaktur1(); 
    }

   $('#btn_carisupplier1').on('click',function(){
        setTimeout(() => {
            $('#ModalCariSupplier').modal('show');						
        }, 300);
    });

function tampil_carisupplier(){
        let i = 1;	
        return $('#carisupplier').DataTable({
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
            ajax   : `{{route('pos01.laporan.pembelian_showsupplier')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'kode' },
                { data: 'supplier', name: 'supplier' },
                { data: 'alamat', name: 'alamat' },
                               
            ]
        });
    }

     $('#show_datacarisupplier').on('click','.item_kode',function(){
        ambilcarisupplier(this);        
    });
    $('#show_datacarisupplier').on('click','.item_supplier',function(){
        ambilcarisupplier(this);        
    });
    $('#show_datacarisupplier').on('click','.item_alamat',function(){
        ambilcarisupplier(this);        
    });

    function ambilcarisupplier(t){
        var data1 = $(t).attr('data1');
        var data2 = $(t).attr('data2');
        var data3 = $(t).attr('data3');
        var data4 = $(t).attr('data4');
        var data5 = $(t).attr('data5');
        $('#id1').val(data1);
        $('#idsupplier1').val(data1);
        var a = $('#idruang1 option:selected').text();
        var x = $('#idsupplier1 option:selected').text();
        var y = x.trim();
        var z = '<p title="Cari spesifik data pada kolom search ...">Data yang dipilih tidak terdapat pada</br><b>'+a+'</b></p>';
        var k = $('#komen1').html(z);
        $('#event1').val('1');
        
        setTimeout(() => {
           ceksupplier();           
           
        }, 200);

        setTimeout(() => {
            var jmlx = $('#cek1').val();
            if(jmlx=='0'){
                swalgagalpilih(z); 
            }else{
                kirimsyarat();           
                $('#ModalCariSupplier').modal('hide');
            }
            
        }, 200);


    }

    function ceksupplier(){        
        var idsup1=$('#id1').val();

        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pos01.laporan.pembelian_ceksupplier')}}',
            async : false,
            dataType : 'json',
            // data : FormData,
            data : {idsup1:idsup1},
            // cache: false,
            // processData: false,
            // contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },				 				
            success : function(data){ 
                var resultData = data.data;	                
                    $('#cek1').val(resultData[0].jmlx);                                        
                },
            error : function(data){ 
                
                }

        });
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