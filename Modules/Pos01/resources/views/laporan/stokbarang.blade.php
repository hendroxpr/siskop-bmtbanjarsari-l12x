@extends('admin.layouts.main')

@section('contents')

@php
    $idruang = session('idruang1');
    if($idruang==''){
        $idruang = '1';
    }    
    $tabstok = session('tabstok1');
    $tgltransaksi = session('tgltransaksi1');   
    if($tgltransaksi==''){
        $tgltransaksi=session('memtanggal');  
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
                    <div class="col-md-5">
                        <select name="idruang1" id="idruang1" class="w3-input w3-border" value="{{ $idruang }}"></select>
                        <input name="tabstok1" id="tabstok1" class="" type="hidden" value="{{ $tabstok }}"> 
                        <input name="event1" id="event1" class="" type="hidden" value="0">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-3 text-right">
                        <h6 class="mt-2">Per Tanggal</h6>
                    </div>
                    <div class="col-md-5">
                        <input name="tgltransaksi1" id="tgltransaksi1" class="w3-input w3-border" maxlength="10" type="text" placeholder="Per Tanggal" autocomplete="off" value="{{ $tgltransaksi }}">                       
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
                    <button id="btn_tambah1" name="btn_tambah1" type="button" class="btn bg-primary rounded-0" style="display: none;"><i class="fas fa-plus"></i> Tambah</button>	            
                </div> 
            </div>
        </div>

    </div>

    <ul class="nav nav-tabs" id="tab-stok" role="tablist">
        <li class="nav-item">
            <a class="nav-link" id="tab-stokbarang" data-toggle="pill" href="#isi-tab-stokbarang" role="tab" aria-controls="tab-stokbarang" aria-selected="true">Stok</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-stokfifo" data-toggle="pill" href="#isi-tab-stokfifo" role="tab" aria-controls="tab-stokfifo" aria-selected="false">Stok FIFO</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-stokmova" data-toggle="pill" href="#isi-tab-stokmova" role="tab" aria-controls="tab-stokmova" aria-selected="false">Stok Moving Average</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-stoklifo" data-toggle="pill" href="#isi-tab-stoklifo" role="tab" aria-controls="tab-stoklifo" aria-selected="false">Stok LIFO</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-stokexpired" data-toggle="pill" href="#isi-tab-stokexpired" role="tab" aria-controls="tab-stokexpired" aria-selected="false">Stok Expired</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-stokmin" data-toggle="pill" href="#isi-tab-stokmin" role="tab" aria-controls="tab-stokmin" aria-selected="false">Stok Minimal</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-stokmax" data-toggle="pill" href="#isi-tab-stokmax" role="tab" aria-controls="tab-stokmax" aria-selected="false">Stok Maximal</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-stokhabis" data-toggle="pill" href="#isi-tab-stokhabis" role="tab" aria-controls="tab-stokhabis" aria-selected="false">Stok Habis</a>
        </li>               
    </ul>

    <!--awal tabel-->        
    <div class="box-body" id="headerjudul" style="display: block;">
        <div class="tab-content mt-3" id="tab-stok-tabContent">

            <!--tab-stokbarang -->
            <div class="tab-pane fade" id="isi-tab-stokbarang" role="tabpanel" aria-labelledby="tab-stokbarang">
                <div id="reload" class="table-responsive">
                    <table id="stokbarang1" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>                            
                                <th style="width:50px">Kode</th>
                                <th style="width:50px">Barcode</th>
                                <th style="width:300px">Nama Barang</th>							
                                <th style="width:20px">Qty</th>							
                                <th style="width:20px">Satuan</th>							
                                <th style="width:20px">HPP</th>							
                                <th style="width:20px">Total HPP</th>							
                            </tr>
                        </thead>
                        <tfoot id="show_footerstokbarang1">
                            <tr>
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
                        <tbody id="show_stokbarang1">
                        
                        </tbody>
                    </table>            
                </div>
            </div>
            <!--/tab-stokbarang -->
            
            <!--tab-stokfifo -->
            <div class="tab-pane fade" id="isi-tab-stokfifo" role="tabpanel" aria-labelledby="tab-stokfifo">
                <div id="reload" class="table-responsive">
                    <table id="stokfifo1" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>                            
                                <th style="width:50px">Kode</th>
                                <th style="width:50px">Barcode</th>
                                <th style="width:300px">Nama Barang</th>							
                                <th style="width:20px">Qty</th>							
                                <th style="width:20px">Satuan</th>							
                                <th style="width:20px">HPP</th>							
                                <th style="width:20px">Total HPP</th>							
                            </tr>
                        </thead>
                        <tfoot id="show_footerstokfifo1">
                            <tr>
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
                        <tbody id="show_stokfifo1">
                        
                        </tbody>
                    </table>            
                </div>
            </div>
            <!--/tab-stokfifo -->
            
            <!--tab-stokmova -->
            <div class="tab-pane fade" id="isi-tab-stokmova" role="tabpanel" aria-labelledby="tab-stokmova">
                <div id="reload" class="table-responsive">
                    <table id="stokmova1" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>                            
                                <th style="width:50px">Kode</th>
                                <th style="width:50px">Barcode</th>
                                <th style="width:3200px">Nama Barang</th>							
                                <th style="width:20px">Qty</th>							
                                <th style="width:20px">Satuan</th>							
                                <th style="width:20px">HPP</th>							
                                <th style="width:20px">Total HPP</th>							
                            </tr>
                        </thead>
                        <tfoot id="show_footerstokmova1">
                            <tr>
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
                        <tbody id="show_stokmova1">
                        
                        </tbody>
                    </table>            
                </div>
            </div>
            <!--/tab-stokmova -->

            <!--tab-stoklifo -->
            <div class="tab-pane fade" id="isi-tab-stoklifo" role="tabpanel" aria-labelledby="tab-stoklifo">
                <div id="reload" class="table-responsive">
                    <table id="stoklifo1" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>                            
                                <th style="width:50px">Kode</th>
                                <th style="width:50px">Barcode</th>
                                <th style="width:300px">Nama Barang</th>							
                                <th style="width:20px">Qty</th>							
                                <th style="width:20px">Satuan</th>							
                                <th style="width:20px">HPP</th>							
                                <th style="width:20px">Total HPP</th>							
                            </tr>
                        </thead>
                        <tfoot id="show_footerstoklifo1">
                            <tr>
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
                        <tbody id="show_stoklifo1">
                        
                        </tbody>
                    </table>            
                </div>
            </div>
            <!--/tab-stoklifo -->

            <!--tab-stokexpired -->
            <div class="tab-pane fade" id="isi-tab-stokexpired" role="tabpanel" aria-labelledby="tab-stokexpired">
                <div id="reload" class="table-responsive">
                    <table id="stokexpired1" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>                            
                                <th style="width:50px">Kode</th>
                                <th style="width:50px">Barcode</th>
                                <th style="width:300px">Nama Barang</th>							
                                <th style="width:20px">Qty</th>							
                                <th style="width:20px">Satuan</th>							
                                <th style="width:20px">HPP</th>							
                                <th style="width:20px">Total HPP</th>							
                                <th style="width:20px">Expired</th>							
                            </tr>
                        </thead>
                        <tfoot id="show_footerstokexpired1">
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
                            </tr>
                        </tfoot>
                        <tbody id="show_stokexpired1">
                        
                        </tbody>
                    </table>            
                </div>
            </div>
            <!--/tab-stokexpired -->

            <!--tab-stokmin -->
            <div class="tab-pane fade" id="isi-tab-stokmin" role="tabpanel" aria-labelledby="tab-stokmin">
                <div id="reload" class="table-responsive">
                    <table id="stokmin1" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>                            
                                <th style="width:50px">Kode</th>
                                <th style="width:50px">Barcode</th>
                                <th style="width:300px">Nama Barang</th>							
                                <th style="width:20px">Qty</th>							
                                <th style="width:20px">Satuan</th>							
                                <th style="width:20px">HPP</th>							
                                <th style="width:20px">Total HPP</th>							
                                <th style="width:20px">Stok Min</th>							
                            </tr>
                        </thead>
                        <tfoot id="show_footerstokmin1">
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
                            </tr>
                        </tfoot>
                        <tbody id="show_stokmin1">
                        
                        </tbody>
                    </table>            
                </div>
            </div>
            <!--/tab-stokmin -->

            <!--tab-stokmax -->
            <div class="tab-pane fade" id="isi-tab-stokmax" role="tabpanel" aria-labelledby="tab-stokmax">
                <div id="reload" class="table-responsive">
                    <table id="stokmax1" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>                            
                                <th style="width:50px">Kode</th>
                                <th style="width:50px">Barcode</th>
                                <th style="width:300px">Nama Barang</th>							
                                <th style="width:20px">Qty</th>							
                                <th style="width:20px">Satuan</th>							
                                <th style="width:20px">HPP</th>							
                                <th style="width:20px">Total HPP</th>							
                                <th style="width:20px">Stok Max</th>							
                            </tr>
                        </thead>
                        <tfoot id="show_footerstokmax1">
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
                            </tr>
                        </tfoot>
                        <tbody id="show_stokmax1">
                        
                        </tbody>
                    </table>            
                </div>
            </div>
            <!--/tab-stokmax -->

            <!--tab-stokhabis -->
            <div class="tab-pane fade" id="isi-tab-stokhabis" role="tabpanel" aria-labelledby="tab-stokhabis">
                <div id="reload" class="table-responsive">
                    <table id="stokhabis1" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>                            
                                <th style="width:50px">Kode</th>
                                <th style="width:50px">Barcode</th>
                                <th style="width:300px">Nama Barang</th>							
                                <th style="width:20px">Qty</th>							
                                <th style="width:20px">Satuan</th>							
                                <th style="width:20px">HPP</th>							
                                <th style="width:20px">Total HPP</th>							
                            </tr>
                        </thead>
                        <tfoot id="show_footerstokhabis1">
                            <tr>
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
                        <tbody id="show_stokhabis1">
                        
                        </tbody>
                    </table>            
                </div>
            </div>
            <!--/tab-stokhabis -->


        </div>
    </div>    
<!--akhir tabel-->

    <!-- ModalAdd modal fade-->
    <div class="modal fade" id="ModalAdd" data-backdrop="static">
        <div class="modal-dialog modal-default">  <!-- modal-(sm, lg, xl) ukuran lebar modal -->
            <div id="modalx" nama="modalx"  class="modal-content bg-primary w3-animate-zoom">
            
                <div class="modal-header">
                    <h3 class="modal-title"><i id="iconx" name="iconx" class="fas fa-plus-square"></i><b><span id="judulx" name="judulx">Tambah Data</span></b></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="form-group">
                    <form class="form-horizontal" id="formSimpan" nama="formSimpan" action="" method="">
                        @csrf	
                        <div class="modal-body" id="bodyAdd" name="bodyAdd">                        

                            <div class="row">
                                <div class="col-md-4" align="right">										
                                    <h6 class="mt-2">Nama Barang *)</h6>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group">
                                            <select name="idbarang1" id="idbarang1" class="js-select-auto__select form-control" style="border-radius:0px; height:40px;" autocomplete="true">                                   
                                            </select>
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <button id="btn_cariid1x" name="btn_cariid1x" type="button" style="border-radius:0px; border:none;"><i style="font-size:24" class="fas">&#xf002;</i></button>
                                            </span>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <input name="idbarangx1" id="idbarangx1" class="" type="hidden">
                                </div>								  
                            </div>

                            <div class="row">
                                <div class="col-md-4 mt-1" align="right">									
                                    <h6 class="mt-2">Stok Min</h6>
                                </div>
                                <div class="col-md-8">
                                    <input name="stokmin1" id="stokmin1" class="w3-input w3-border" maxlength="15" type="number" placeholder="Stok Min" value="{{ old('stokmin1') }}" required>
                                    <input name="cek1" id="cek1" class="" type="hidden">                                
                                    <input name="id1" id="id1" class="" type="hidden">

                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-4 mt-1" align="right">									
                                    <h6 class="mt-2">Stok Max</h6>
                                </div>
                                <div class="col-md-8">
                                    <input name="stokmax1" id="stokmax1" class="w3-input w3-border" maxlength="15" type="number" placeholder="Stok Max" value="{{ old('stokmax1') }}" required>
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-4" align="right">									
                                    <h6 class="mt-2">Aktif</h6>
                                </div>

                                <div class="col-md-8 mt-1" style="padding-left: 20px;">
                                    <div class="icheck-primary-white d-inline">
                                      <input type="radio" value='Y' id="aktif1xy" name="aktif1x">
                                      <label for="aktif1xy">
                                        <span class="text" style="padding-left: 2px; padding-right: 15px;">Y</span>
                                      </label>
                                    </div>
                                    <div class="icheck-primary-white d-inline text-white">
                                      <input type="radio" value='N' id="aktif1xn" name="aktif1x"  checked>
                                      <label for="aktif1xn">
                                        <span class="text" style="padding-left: 2px; padding-right: 15px;">N</span>
                                      </label>
                                    </div>
                                    <input name="aktif1" id="aktif1" type="hidden">
                                </div>
                            </div> 

                            <div class="row">
                                <div class="col-md-4" align="left" style="color: yellow;">									
                                    <h6 class="mt-2"><b>*) Wajib diisi</b></h6>
                                </div>                                                        
                            </div>
                            
                        </div>
                        <div class="modal-footer justify-content-between" style="padding-bottom: 0px">
                            <button type="button" class="w3-button w3-border w3-border-white" data-dismiss="modal">Tutup</button>
                            <button id="btn_simpan" name="btn_simpan" type="button" class="w3-button w3-border w3-border-white"><i style="font-size:18px" class="fa">&#xf0c7;</i> Simpan</button>
                            <button id="btn_baru" name="btn_baru" type="button" class="w3-button w3-border w3-border-white"><i style="font-size:18px" class="fas fa-plus"></i> Baru</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->      
    </div>  
    <!-- end ModalAdd -->

    <!-- ModalCariID modal fade-->
	<div class="modal fade" id="ModalCariID"  data-backdrop="static">
		<div class="modal-dialog modal-lg">  <!-- modal-(sm, lg, xl) ukuran lebar modal -->
			<div class="modal-content bg-info w3-animate-zoom">
				
				<div class="modal-header">
						<h3 class="modal-title"><i style="font-size:18" class="fas">&#xf002;</i><b> Cari Data Barang</b></h3>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
						
				</div>
				<form class="form-horizontal">
                    @csrf
					<div class="modal-body">
												
						<div class="row">
							<div id="reload" class="table-responsive">
								<table id="caribarang" width="100%" class="table table-bordered table-striped table-hover">
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
								
								<tfoot id="show_footercaribarang">
									
								</tfoot>
								<tbody id="show_datacaribarang">
								
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
    var stokbarang1Datatable;
    var stokfifo1Datatable;
    var stokmova1Datatable;
    var stoklifo1Datatable;
    var stokexpired1Datatable;
    var stokmax1Datatable;
    var stokmin1Datatable;
    var stokhabis1Datatable;

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
    
    setTimeout(() => {
        if($('#tabstok1').val()=='tab-stokbarang'){
            $('#tab-stokbarang').click();            
        }else if($('#tabstok1').val()=='tab-stokfifo'){
            $('#tab-stokfifo').click();            
        }else if($('#tabstok1').val()=='tab-stokmova'){
            $('#tab-stokmova').click();            
        }else if($('#tabstok1').val()=='tab-stoklifo'){
            $('#tab-stoklifo').click();            
        }else if($('#tabstok1').val()=='tab-stokexpired'){
            $('#tab-stokexpired').click();            
        }else if($('#tabstok1').val()=='tab-stokmin'){
            $('#tab-stokmin').click();            
        }else if($('#tabstok1').val()=='tab-stokmax'){
            $('#tab-stokmax').click();            
        }else if($('#tabstok1').val()=='tab-stokhabis'){
            $('#tab-stokhabis').click();            
        }else{
            $('#tab-stokbarang').click();            
        }
    }, 500);

    $('#tab-stokbarang').on('click',function(){
        $('#tabstok1').val('tab-stokbarang');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 500);
    });
    $('#tab-stokfifo').on('click',function(){
        $('#tabstok1').val('tab-stokfifo');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 500);
    });
    $('#tab-stokmova').on('click',function(){
        $('#tabstok1').val('tab-stokmova');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 500);
    });
    $('#tab-stoklifo').on('click',function(){
        $('#tabstok1').val('tab-stoklifo');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 500);
    });
    $('#tab-stokexpired').on('click',function(){
        $('#tabstok1').val('tab-stokexpired');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 500);
    });
    $('#tab-stokmin').on('click',function(){
        $('#tabstok1').val('tab-stokmin');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 500);
    });
    $('#tab-stokmax').on('click',function(){
        $('#tabstok1').val('tab-stokmax');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 500);
    });
    $('#tab-stokhabis').on('click',function(){
        $('#tabstok1').val('tab-stokhabis');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 500);
    });
        
    //menampilkan combo ruang
    setTimeout(() => {
        tampil_listruang();
    }, 500);
    
    koneksi_datatable()

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
    
    function kirimsyarat(){
        var event1 = $('#event1').val();
        var idruang1=$('#idruang1').val();
        var tabstok1=$('#tabstok1').val();
        var tgltransaksi1=$('#tgltransaksi1').val();
        
        let formData = new FormData();
            formData.append('idruang1', idruang1);
            formData.append('tabstok1', tabstok1);
            formData.append('tgltransaksi1', tgltransaksi1);

        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pos01.laporan.stokbarang_kirimsyarat')}}',
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
            url   : '{{route('pos01.laporan.stokbarang_listruang')}}',
            
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

    $('#aktif1xy').on('change',function(){				
        $('#aktif1').val("Y");						
    });
    $('#aktif1xn').on('change',function(){				
        $('#aktif1').val("N");						
    });

    function tampil_stokbarang1(){
        let i = 1;	
        return $('#stokbarang1').DataTable({
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
                .column(7)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Total over this page
            pagetotalhpp = api
                .column(7, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(3).footer().innerHTML = 'SUB TOTAL :';
            api.column(7).footer().innerHTML = formatAngka(pagetotalhpp,'');
            },


            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.stokbarang_showstokbarang')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'barang.kode', className: 'dt-center' },
                { data: 'barcode', name: 'barang.barcode', className: 'dt-center' },
                { data: 'barang', name: 'barang.nabara' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'satuan', name: 'barang.satuan.kode', className: 'dt-left' },
                { data: 'hbs', name: 'barang.hbs', className: 'dt-right' },
                { data: 'totalhpp', name: 'totalhpp', className: 'dt-right' },
            ]
        });
    }
    
    function tampil_stokfifo1(){
        let i = 1;	
        return $('#stokfifo1').DataTable({
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
                .column(7)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Total over this page
            pagetotalhpp = api
                .column(7, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(3).footer().innerHTML = 'SUB TOTAL :';
            api.column(7).footer().innerHTML = formatAngka(pagetotalhpp,'');
            },
            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.stokbarang_showstokfifo')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'barang.kode', className: 'dt-center' },
                { data: 'barcode', name: 'barang.barcode', className: 'dt-center' },
                { data: 'barang', name: 'barang.barang' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'satuan', name: 'barang.satuan.kode', className: 'dt-left' },
                { data: 'hbs', name: 'barang.hbs', className: 'dt-right' },
                { data: 'totalhpp', name: 'totalhpp', className: 'dt-right' },
            ]
        });
    }
    
    function tampil_stokmova1(){
        let i = 1;	
        return $('#stokmova1').DataTable({
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
                .column(7)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Total over this page
            pagetotalhpp = api
                .column(7, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(3).footer().innerHTML = 'SUB TOTAL :';
            api.column(7).footer().innerHTML = formatAngka(pagetotalhpp,'');
            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.stokbarang_showstokmova')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'barang.kode', className: 'dt-center' },
                { data: 'barcode', name: 'barang.barcode', className: 'dt-center' },
                { data: 'barang', name: 'barang.barang' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'satuan', name: 'barang.satuan.kode', className: 'dt-left' },
                { data: 'hbs', name: 'barang.hbs', className: 'dt-right' },
                { data: 'totalhpp', name: 'totalhpp', className: 'dt-right' },
            ]
        });
    }
    
    function tampil_stoklifo1(){
        let i = 1;	
        return $('#stoklifo1').DataTable({
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
                .column(7)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Total over this page
            pagetotalhpp = api
                .column(7, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(3).footer().innerHTML = 'SUB TOTAL :';
            api.column(7).footer().innerHTML = formatAngka(pagetotalhpp,'');
            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.stokbarang_showstoklifo')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'barang.kode', className: 'dt-center' },
                { data: 'barcode', name: 'barang.barcode', className: 'dt-center' },
                { data: 'barang', name: 'barang.barang' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'satuan', name: 'barang.satuan.kode', className: 'dt-left' },
                { data: 'hbs', name: 'barang.hbs', className: 'dt-right' },
                { data: 'totalhpp', name: 'totalhpp', className: 'dt-right' },
            ]
        });
    }
    
    function tampil_stokexpired1(){
        let i = 1;	
        return $('#stokexpired1').DataTable({
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
                .column(7)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Total over this page
            pagetotalhpp = api
                .column(7, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(3).footer().innerHTML = 'SUB TOTAL :';
            api.column(7).footer().innerHTML = formatAngka(pagetotalhpp,'');
            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.stokbarang_showstokexpired')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'barang.kode', className: 'dt-center' },
                { data: 'barcode', name: 'barang.barcode', className: 'dt-center' },
                { data: 'barang', name: 'barang.barang' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'satuan', name: 'barang.satuan.kode', className: 'dt-left' },
                { data: 'hbs', name: 'barang.hbs', className: 'dt-right' },
                { data: 'totalhpp', name: 'totalhpp', className: 'dt-right' },
                { data: 'expired', name: 'barang.expired', className: 'dt-center' },
            ]
        });
    }
    
    function tampil_stokmin1(){
        let i = 1;	
        return $('#stokmin1').DataTable({
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
                .column(7)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Total over this page
            pagetotalhpp = api
                .column(7, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(3).footer().innerHTML = 'SUB TOTAL :';
            api.column(7).footer().innerHTML = formatAngka(pagetotalhpp,'');
            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.stokbarang_showstokmin')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'barang.kode', className: 'dt-center' },
                { data: 'barcode', name: 'barang.barcode', className: 'dt-center' },
                { data: 'barang', name: 'barang.barang' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'satuan', name: 'barang.satuan.kode', className: 'dt-left' },
                { data: 'hbs', name: 'barang.hbs', className: 'dt-right' },
                { data: 'totalhpp', name: 'totalhpp', className: 'dt-right' },
                { data: 'stokmin', name: 'stokmin', className: 'dt-center' },
            ]
        });
    }

    function tampil_stokmax1(){
        let i = 1;	
        return $('#stokmax1').DataTable({
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
                .column(7)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Total over this page
            pagetotalhpp = api
                .column(7, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(3).footer().innerHTML = 'SUB TOTAL :';
            api.column(7).footer().innerHTML = formatAngka(pagetotalhpp,'');
            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.stokbarang_showstokmax')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'barang.kode', className: 'dt-center' },
                { data: 'barcode', name: 'barang.barcode', className: 'dt-center' },
                { data: 'barang', name: 'barang.barang' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'satuan', name: 'barang.satuan.kode', className: 'dt-left' },
                { data: 'hbs', name: 'barang.hbs', className: 'dt-right' },
                { data: 'totalhpp', name: 'totalhpp', className: 'dt-right' },
                { data: 'stokmax', name: 'stokmax', className: 'dt-center' },
            ]
        });
    }

    function tampil_stokhabis1(){
        let i = 1;	
        return $('#stokhabis1').DataTable({
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
                .column(7)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Total over this page
            pagetotalhpp = api
                .column(7, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(3).footer().innerHTML = 'SUB TOTAL :';
            api.column(7).footer().innerHTML = formatAngka(pagetotalhpp,'');
            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.stokbarang_showstokhabis')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'barang.kode', className: 'dt-center' },
                { data: 'barcode', name: 'barang.barcode', className: 'dt-center' },
                { data: 'barang', name: 'barang.barang' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'satuan', name: 'barang.satuan.kode', className: 'dt-left' },
                { data: 'hbs', name: 'barang.hbs', className: 'dt-right' },
                { data: 'totalhpp', name: 'totalhpp', className: 'dt-right' },
            ]
        });
    }

    function tampil_dataTable(){        
        stokbarang1Datatable.draw(null, false);        
        stokfifo1Datatable.draw(null, false);        
        stoklifo1Datatable.draw(null, false);        
        stokmova1Datatable.draw(null, false);        
        stokexpired1Datatable.draw(null, false);        
        stokmin1Datatable.draw(null, false);        
        stokmax1Datatable.draw(null, false);        
        stokhabis1Datatable.draw(null, false);               
    }

    function koneksi_datatable(){
        stokbarang1Datatable = tampil_stokbarang1();    
        stokfifo1Datatable = tampil_stokfifo1();    
        stokmova1Datatable = tampil_stokmova1();    
        stoklifo1Datatable = tampil_stoklifo1();    
        stokexpired1Datatable = tampil_stokexpired1();    
        stokmin1Datatable = tampil_stokmin1();    
        stokmax1Datatable = tampil_stokmax1();    
        stokhabis1Datatable = tampil_stokhabis1()
    }


    $('#btn_cariid1x').on('click',function(){
        // caribarangDatatable.draw(null, false);
        setTimeout(() => {
            $('#ModalCariID').modal('show');						
        }, 300);
    });

    caribarangDatatable = tampil_data_caribarang();    
     function tampil_data_caribarang(){
        let i = 1;	
        return $('#caribarang').DataTable({
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
            ajax   : `{{route('pos01.master.barangruang_showbarang')}}`,
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
                               
                // { data: 'action', name: 'action'},
            ]
        });
    }

    $('#show_datacaribarang').on('click','.item_kode',function(){
        ambilcari(this);        
    });
    $('#show_datacaribarang').on('click','.item_barcode',function(){
        ambilcari(this);        
    });
    $('#show_datacaribarang').on('click','.item_kategori',function(){
        ambilcari(this);        
    });
    $('#show_datacaribarang').on('click','.item_satuan',function(){
        ambilcari(this);        
    });
    $('#show_datacaribarang').on('click','.item_nabara',function(){
        ambilcari(this);        
    });
    $('#show_datacaribarang').on('click','.item_image',function(){
        ambilcari(this);        
    });

    function ambilcari(t){
        var data1 = $(t).attr('data1');
        var data2 = $(t).attr('data2');
        var data3 = $(t).attr('data3');
        var data4 = $(t).attr('data4');
        var data5 = $(t).attr('data5');
        $('#ModalCariID').modal('hide');
        $('#idbarang1').val(data1);

    }

    function btn_baru_click(){ 
        $('[name="aktif1"]').val("Y");
        if ($('[name="aktif1"]').val()=='Y'){
            document.getElementById("aktif1xy").checked = true
        }else{
            document.getElementById("aktif1xn").checked = true
        }

        tampil_listbarang();

        $('#bodyAdd :input').prop('disabled', false);
        document.getElementById("btn_simpan").style.display='block';        
        document.getElementById("btn_baru").style.display='none';
    }
    
    function btn_simpan_click(){      
        $('#bodyAdd :input').prop('disabled', true);
        document.getElementById("btn_simpan").style.display='none';        
        document.getElementById("btn_baru").style.display='block';
        swaltambah($('#idbarang1 option:selected').text());
        
    }

    function btn_edit_click(){
        tampil_listbarangedit();      
        $('#bodyAdd :input').prop('disabled', false);
        document.getElementById("btn_simpan").style.display='block';        
        document.getElementById("btn_baru").style.display='none';
    }
    
    //tambah data -> ok
    $('#btn_baru').on('click',function(){
        btn_baru_click();            
    });

    $('#btn_tambah1').on('click',function(){

        btn_baru_click();

        $("#iconx").removeClass("fas fa-edit");
        $("#iconx").addClass("fas fa-plus-square");
        $("#modalx").removeClass("modal-content bg-success w3-animate-zoom");
        $("#modalx").addClass("modal-content bg-primary w3-animate-zoom");
        document.getElementById("btn_simpan").disabled = false;
        $('#ModalAdd').modal('show');
        $('#id1').val('0');
        $('#judulx').html(' Tambah Data');
       
    }); 

    function data_simpan(){
        var id1=$('#id1').val();			
        var idruang1=$('#idruang1').val();
        var idbarang1=$('#idbarang1').val();
        var stokmin1=$('#stokmin1').val();
        var stokmax1=$('#stokmax1').val();
        var aktif1=$('#aktif1').val();
        
        let formData = new FormData();
            formData.append('id1', id1);
            formData.append('idruang1', idruang1);
            formData.append('idbarang1', idbarang1);
            formData.append('stokmin1', stokmin1);
            formData.append('stokmax1', stokmax1);
            formData.append('aktif1', aktif1);
          
        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pos01.master.barangruang_create')}}',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },				 				
            success : function(formData){                                         
                    tampil_dataTable();
                    btn_simpan_click();
                    if(id1>0){
                        $('#ModalAdd').modal('hide'); 
                    }
                },
            error : function(formData){                    
                swalgagaltambah($('#idbarang1 option:selected').text());                 
                }
        });
        
    }   

    $("#btn_simpan").on('click',function(){
        data_simpan();        
    });


    $('#show_data1').on('click','.item_edit',function(){
        var id1 = $(this).attr('data');
        item_edit_click(id1);
              
    });

    function item_edit_click(id1){
        $("#iconx").removeClass("fas fa-plus-square");
        $("#iconx").addClass("fas fa-edit");
        $("#modalx").removeClass("modal-content bg-primary w3-animate-zoom");
        $("#modalx").addClass("modal-content bg-success w3-animate-zoom");
        $('#judulx').html(' Edit Data');
        btn_edit_click();

        // var id1=$(this).attr('data');

        $('#id1').val(id1);
        data_edit(id1);

        $('#ModalAdd').modal('show'); 
        setTimeout(() => {
            var idb = $('#idbarangx1').val(); 
            $('#idbarang1').val(idb);
        }, 500);
    }
    
    function data_edit(idx){
        
        var id1=idx;			
            $.ajax({
		        type  : 'get',
		        url   : `{{ url('pos01/master/barangruangedit')}}/${id1}`,
		        async : false,
		        dataType : 'json',	
				
		        success : function(data){
                    var i;                
                    var resultData = data.data;	                			
                    for(i=0; i<resultData.length; i++){

                            $('#id1').val(resultData[i].id);
                            $('#idbarang1').val(resultData[i].idbarang);
                            $('#idbarangx1').val(resultData[i].idbarang);

                            $('#stokmin1').val(resultData[i].stokmin);
                            $('#stokmax1').val(resultData[i].stokmax);
                            $('#aktif1').val(resultData[i].aktif);
                            if ($('[name="aktif1"]').val()=='Y'){
                                document.getElementById("aktif1xy").checked = true
                            }else{
                                document.getElementById("aktif1xn").checked = true
                            }
                            
                    }
                    
		        },
                error : function(data){
                    alert(id1);
                }
		    }); 
    }

    $('#show_data1').on('click','.item_hapus',function(){
        var id3=$(this).attr('data');
        var data3b=$(this).attr('data2');
        var data3c=$(this).attr('data3');
        var data3d=$(this).attr('data4');
        item_hapus_click(id3,data3b,data3c,data3d);
    });

    function item_hapus_click(id3,data3b,data3c,data3d){
        $('#id3').val(id3);
        $('#data3a').val(data3b);
        $('#data3b').val(data3c);
        $('#data3c').val(data3d);
        modal_hapus();
    }

    //modal sweet art hapus		
    function modal_hapus(){
        Swal.fire({
        title: 'Are you sure delete?',
        text: $('#data3a').val(),
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: "Yes, delete !",
		cancelButtonText: "No, cancel !",
        }).then((result) => {
            if (result.isConfirmed) {
                data_hapus();            
            }
        })
    } 

    function data_hapus(){			
        var id3=$('#id3').val();       
        var data3b=$('#data3b').val();
        $.ajax({
            type  : 'get',
            url   : '{{url('pos01/master/barangruangdestroy')}}/'+id3,
            async : false,
            dataType : 'json',					
            success : function(data){
                tampil_dataTable();
                swalhapus(data3b); 
            },
            error : function(data){
                swalgagalhapus(data3a); 
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

});

</script>	



@endsection