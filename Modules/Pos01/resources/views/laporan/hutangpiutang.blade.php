@extends('admin.layouts.main')

@section('contents')

@php
    $idruang = session('idruang1');
    if($idruang==''){
        $idruang = '1';
    }    
    $tabhutang = session('tabhutang1');
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
                <input name="tabhutang1" id="tabhutang1" class="" type="hidden" value="{{ $tabhutang }}">
                {{-- <div class="row">
                    <div class="col-md-3 mt-2 text-right">
                        <h6>Lokasi</h6>
                    </div>
                    <div class="col-md-5">
                        <select name="idruang1" id="idruang1" class="w3-input w3-border" value="{{ $idruang }}"></select>
                        <input name="tabhutang1" id="tabhutang1" class="" type="hidden" value="{{ $tabstok }}"> 
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
            <div class="col-md-6">
                <div class="w3-row" align="right"><i class="fa fa-refresh" aria-hidden="true"></i>            
                    <a href="{{ url('/') }}{{ $link }}" class="btn bg-success rounded-0"><i style="font-size:18px" class="fa">&#xf021;</i> Refresh</a>            
                    <button id="btn_tambah1" name="btn_tambah1" type="button" class="btn bg-primary rounded-0" style="display: none;"><i class="fas fa-plus"></i> Tambah</button>	            
                </div> 
            </div>
        </div>

    </div>

    <ul class="nav nav-tabs" id="tab-hutangbelum" role="tablist">
        <li class="nav-item">
            <a class="nav-link" id="tab-hutangbelumsupplier" data-toggle="pill" href="#isi-tab-hutangbelumsupplier" role="tab" aria-controls="tab-hutangbelumsupplier" aria-selected="true">Hutang (Supplier) Belum Lunas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-hutangbelumcustomer" data-toggle="pill" href="#isi-tab-hutangbelumcustomer" role="tab" aria-controls="tab-hutangbelumcustomer" aria-selected="false">Piutang (Customer) Belum Lunas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-hutangsudahsupplier" data-toggle="pill" href="#isi-tab-hutangsudahsupplier" role="tab" aria-controls="tab-hutangsudahsupplier" aria-selected="false">Hutang (Supplier) Sudah Lunas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-hutangsudahcustomer" data-toggle="pill" href="#isi-tab-hutangsudahcustomer" role="tab" aria-controls="tab-hutangsudahcustomer" aria-selected="false">Piutang (Customer) Sudah Lunas</a>
        </li>
                 
    </ul>

    <!--awal tabel-->        
    <div class="box-body" id="headerjudul" style="display: block;">
        <div class="tab-content mt-3" id="tab-hutangbelum-tabContent">

            <!--tab-hutangbelumsupplier -->
            <div class="tab-pane fade" id="isi-tab-hutangbelumsupplier" role="tabpanel" aria-labelledby="tab-hutangbelumsupplier">
                <div id="reload" class="table-responsive">
                    <table id="hutangbelumsupplier1" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>                            
                                <th style="width:20px">Faktur</th>							
                                <th style="width:10px">Tanggal</th>							
                                <th style="width:10px">Kode</th>
                                <th style="width:50px">Supplier</th>
                                <th style="width:50px">Alamat</th>							
                                <th style="width:10px">X Angs</th>							
                                <th style="width:20px">@ Angsuran</th>							
                                <th style="width:20px">Nilai Hutang</th>							
                                <th style="width:20px">Sudah Bayar</th>							
                                <th style="width:20px">Saldo</th>							
                            </tr>
                        </thead>
                                
                        <tfoot id="show_footerhutangbelumsupplier1">
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
                        
                        <tbody id="show_hutangbelumsupplier1">
                            
                        </tbody>
                    </table>            
                </div>
            </div>
            <!--/tab-hutangbelumsupplier -->
            
            <!--tab-hutangbelumcustomer -->
            <div class="tab-pane fade" id="isi-tab-hutangbelumcustomer" role="tabpanel" aria-labelledby="tab-hutangbelumcustomer">
                <div id="reload" class="table-responsive">
                    <table id="hutangbelumcustomer1" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>                            
                                <th style="width:20px">Faktur</th>							
                                <th style="width:10px">Tanggal</th>							
                                <th style="width:10px">NIA</th>
                                <th style="width:50px">Customer</th>
                                <th style="width:50px">Lembaga</th>							
                                <th style="width:10px">X Angs</th>							
                                <th style="width:20px">@ Angsuran</th>							
                                <th style="width:20px">Nilai Hutang</th>							
                                <th style="width:20px">Sudah Bayar</th>							
                                <th style="width:20px">Saldo</th>							
                            </tr>
                        </thead>
                        <tfoot id="show_footerhutangbelumcustomer1">
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
                        <tbody id="show_hutangbelumcustomer1">
                        
                        </tbody>
                    </table>            
                </div>
            </div>
            <!--/tab-hutangbelumcustomer -->

            <!--tab-hutangsudahsupplier -->
            <div class="tab-pane fade" id="isi-tab-hutangsudahsupplier" role="tabpanel" aria-labelledby="tab-hutangsudahsupplier">
                <div id="reload" class="table-responsive">
                    <table id="hutangsudahsupplier1" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>                            
                                <th style="width:20px">Faktur</th>							
                                <th style="width:10px">Tanggal</th>							
                                <th style="width:10px">Kode</th>
                                <th style="width:50px">Supplier</th>
                                <th style="width:50px">Alamat</th>							
                                <th style="width:10px">X Angs</th>							
                                <th style="width:20px">@ Angsuran</th>							
                                <th style="width:20px">Nilai Hutang</th>							
                                <th style="width:20px">Sudah Bayar</th>							
                                <th style="width:20px">Saldo</th>							
                            </tr>
                        </thead>
                        <tfoot id="show_footerhutangsudahsupplier1">
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
                        <tbody id="show_hutangsudahsupplier1">
                        
                        </tbody>
                    </table>            
                </div>
            </div>
            <!--/tab-hutangsudahsupplier -->
            
            <!--tab-hutangsudahcustomer -->
            <div class="tab-pane fade" id="isi-tab-hutangsudahcustomer" role="tabpanel" aria-labelledby="tab-hutangsudahcustomer">
                <div id="reload" class="table-responsive">
                    <table id="hutangsudahcustomer1" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>                            
                                <th style="width:20px">Faktur</th>							
                                <th style="width:10px">Tanggal</th>							
                                <th style="width:10px">NIA</th>
                                <th style="width:50px">Customer</th>
                                <th style="width:50px">Lembaga</th>							
                                <th style="width:10px">X Angs</th>							
                                <th style="width:20px">@ Angsuran</th>							
                                <th style="width:20px">Nilai Hutang</th>							
                                <th style="width:20px">Sudah Bayar</th>							
                                <th style="width:20px">Saldo</th>							
                            </tr>
                        </thead>
                        <tfoot id="show_footerhutangsudahcustomer1">
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
                        <tbody id="show_hutangsudahcustomer1">
                        
                        </tbody>
                    </table>            
                </div>
            </div>
            <!--/tab-hutangsudahcustomer -->

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
    var hutangbelumsupplier1Datatable;
    var hutangbelumcustomer1Datatable;
    var hutangsudahsupplier1Datatable;
    var hutangsudahcustomer1Datatable;

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
        if($('#tabhutang1').val()=='tab-hutangsudahsupplier'){
            $('#tab-hutangsudahsupplier').click();            
        }else if($('#tabhutangsudah1').val()=='tab-hutangsudahcustomer'){
            $('#tab-hutangsudahcustomer').click();            
        }else if($('#tabhutang1').val()=='tab-hutangbelumsupplier'){
            $('#tab-hutangbelumsupplier').click();            
        }else if($('#tabhutang1').val()=='tab-hutangbelumcustomer'){
            $('#tab-hutangbelumcustomer').click();            
        }else{
            $('#tab-hutangbelumsupplier').click();            
        }
    }, 500);

    $('#tab-hutangbelumsupplier').on('click',function(){
        $('#tabhutang1').val('tab-hutangbelumsupplier');
        setTimeout(() => {
            kirimsyarat();	
        }, 500);
    });
    $('#tab-hutangbelumcustomer').on('click',function(){
        $('#tabhutang1').val('tab-hutangbelumcustomer');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 500);
    });
    $('#tab-hutangsudahsupplier').on('click',function(){
        $('#tabhutang1').val('tab-hutangsudahsupplier');
        setTimeout(() => {
            kirimsyarat();	
        }, 500);
    });
    $('#tab-hutangsudahcustomer').on('click',function(){
        $('#tabhutang1').val('tab-hutangsudahcustomer');
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
        var tabhutang1=$('#tabhutang1').val();
        
        let formData = new FormData();
            formData.append('tabhutang1', tabhutang1);

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

    function tampil_hutangbelumsupplier1(){
        let i = 1;	
        return $('#hutangbelumsupplier1').DataTable({
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
            totalhutang = api
                .column(8)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            sudahbayar = api
                .column(9)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            saldo = api
                .column(10)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Total over this page
            pagetotalhutang = api
                .column(8, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagesudahbayar = api
                .column(9, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagesaldo = api
                .column(10, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(3).footer().innerHTML = 'SUB TOTAL :';
            api.column(8).footer().innerHTML = formatAngka(pagetotalhutang,'');
            api.column(9).footer().innerHTML = formatAngka(pagesudahbayar,'');
            api.column(10).footer().innerHTML = formatAngka(pagesaldo,'');
            },
            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.hutangpiutang_showhutangpiutangbelumsupplier')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nomorstatus', name: 'nomorstatus', className: 'dt-center' },
                { data: 'tglstatus', name: 'tglstatus', className: 'dt-center' },
                { data: 'kode', name: 'supplier.kode', className: 'dt-center' },
                { data: 'supplier', name: 'supplier.supplier', className: 'dt-left' },
                { data: 'alamat', name: 'supplier.alamat', className: 'dt-left' },
                { data: 'xangsuran', name: 'xangsuran', className: 'dt-center' },
                { data: 'nilaiangsuran', name: 'nilaiangsuran', className: 'dt-right' },
                { data: 'asli', name: 'asli', className: 'dt-right' },
                { data: 'sudahbayar', name: 'sudahbayar', className: 'dt-right' },
                { data: 'saldo', name: 'saldo', className: 'dt-right' },
            ]
        });
    }
    
    function tampil_hutangbelumcustomer1(){
        let i = 1;	
        return $('#hutangbelumcustomer1').DataTable({
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
            totalhutang = api
                .column(8)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            sudahbayar = api
                .column(9)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            saldo = api
                .column(10)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Total over this page
            pagetotalhutang = api
                .column(8, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagesudahbayar = api
                .column(9, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagesaldo = api
                .column(10, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(3).footer().innerHTML = 'SUB TOTAL :';
            api.column(8).footer().innerHTML = formatAngka(pagetotalhutang,'');
            api.column(9).footer().innerHTML = formatAngka(pagesudahbayar,'');
            api.column(10).footer().innerHTML = formatAngka(pagesaldo,'');
            },
            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.hutangpiutang_showhutangpiutangbelumcustomer')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nomorstatus', name: 'nomorstatus', className: 'dt-center' },
                { data: 'tglstatus', name: 'tglstatus', className: 'dt-center' },
                { data: 'nia', name: 'anggota.nia', className: 'dt-center' },
                { data: 'nama', name: 'anggota.nama', className: 'dt-left' },
                { data: 'lembaga', name: 'anggota.lembaga.lembaga', className: 'dt-left' },
                { data: 'xangsuran', name: 'xangsuran', className: 'dt-center' },
                { data: 'nilaiangsuran', name: 'nilaiangsuran', className: 'dt-right' },
                { data: 'asli', name: 'asli', className: 'dt-right' },
                { data: 'sudahbayar', name: 'sudahbayar', className: 'dt-right' },
                { data: 'saldo', name: 'saldo', className: 'dt-right' },
            ]
        });
    }
   
    function tampil_hutangsudahsupplier1(){
        let i = 1;	
        return $('#hutangsudahsupplier1').DataTable({
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
            totalhutang = api
                .column(8)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            sudahbayar = api
                .column(9)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            saldo = api
                .column(10)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Total over this page
            pagetotalhutang = api
                .column(8, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagesudahbayar = api
                .column(9, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagesaldo = api
                .column(10, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(3).footer().innerHTML = 'SUB TOTAL :';
            api.column(8).footer().innerHTML = formatAngka(pagetotalhutang,'');
            api.column(9).footer().innerHTML = formatAngka(pagesudahbayar,'');
            api.column(10).footer().innerHTML = formatAngka(pagesaldo,'');
            },
            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.hutangpiutang_showhutangpiutangsudahsupplier')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nomorstatus', name: 'nomorstatus', className: 'dt-center' },
                { data: 'tglstatus', name: 'tglstatus', className: 'dt-center' },
                { data: 'kode', name: 'supplier.kode', className: 'dt-center' },
                { data: 'supplier', name: 'supplier.supplier', className: 'dt-left' },
                { data: 'alamat', name: 'supplier.alamat', className: 'dt-left' },
                { data: 'xangsuran', name: 'xangsuran', className: 'dt-center' },
                { data: 'nilaiangsuran', name: 'nilaiangsuran', className: 'dt-right' },
                { data: 'asli', name: 'asli', className: 'dt-right' },
                { data: 'sudahbayar', name: 'sudahbayar', className: 'dt-right' },
                { data: 'saldo', name: 'saldo', className: 'dt-right' },
            ]
        });
    }
    
    function tampil_hutangsudahcustomer1(){
        let i = 1;	
        return $('#hutangsudahcustomer1').DataTable({
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
            totalhutang = api
                .column(8)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            sudahbayar = api
                .column(9)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            saldo = api
                .column(10)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Total over this page
            pagetotalhutang = api
                .column(8, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagesudahbayar = api
                .column(9, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagesaldo = api
                .column(10, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(3).footer().innerHTML = 'SUB TOTAL :';
            api.column(8).footer().innerHTML = formatAngka(pagetotalhutang,'');
            api.column(9).footer().innerHTML = formatAngka(pagesudahbayar,'');
            api.column(10).footer().innerHTML = formatAngka(pagesaldo,'');
            },
            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.hutangpiutang_showhutangpiutangsudahcustomer')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nomorstatus', name: 'nomorstatus', className: 'dt-center' },
                { data: 'tglstatus', name: 'tglstatus', className: 'dt-center' },
                { data: 'nia', name: 'anggota.nia', className: 'dt-center' },
                { data: 'nama', name: 'anggota.nama', className: 'dt-left' },
                { data: 'lembaga', name: 'anggota.lembaga.lembaga', className: 'dt-left' },
                { data: 'xangsuran', name: 'xangsuran', className: 'dt-center' },
                { data: 'nilaiangsuran', name: 'nilaiangsuran', className: 'dt-right' },
                { data: 'asli', name: 'asli', className: 'dt-right' },
                { data: 'sudahbayar', name: 'sudahbayar', className: 'dt-right' },
                { data: 'saldo', name: 'saldo', className: 'dt-right' },
            ]
        });
    }
   
    function tampil_dataTable(){        
        hutangbelumsupplier1Datatable.draw(null, false);        
        hutangbelumcustomer1Datatable.draw(null, false);        
        hutangsudahsupplier1Datatable.draw(null, false);        
        hutangsudahcustomer1Datatable.draw(null, false);        
                      
    }

    function koneksi_datatable(){
        hutangbelumsupplier1Datatable = tampil_hutangbelumsupplier1();    
        hutangbelumcustomer1Datatable = tampil_hutangbelumcustomer1();    
        hutangsudahsupplier1Datatable = tampil_hutangsudahsupplier1();    
        hutangsudahcustomer1Datatable = tampil_hutangsudahcustomer1();    
        
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