@extends('admin.layouts.main')

@section('contents')

@php
    $tgltransaksi = session('tgltransaksi1');   
    if($tgltransaksi==''){
        $tgltransaksi='';  
    }
    $nomorbuktia = session()->get('nomorbuktia1');
    if($nomorbuktia==''){
        $nomorbuktia='';  
    }
    $idruang = session()->get('idruang1');
    if($idruang==''){
        $idruang='1';  
    }
    
    $jmlitem = session()->get('jmlitem1');
    if($jmlitem=='0'){
        $jmlitem='0';  
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


<style>
    .block {
      width: 100%;
      border: none;
      /* background-color: #04AA6D; */
      color: white;
      /* padding: 5px 10px; */
      font-size: 1em;
      cursor: pointer;
      text-align: center;
    }
    
    .block:hover {
      background-color: #ddd;
      color: black;
    }

    a {
        color: white;
    }
   .table-hover tbody tr:hover {
        background-color: rgb(87, 181, 188) !important;
        color: black;
    }

   
    
</style>

    <div class="box-header mb-3">  
        <div class="row">
            
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-4 text-right">
                        <h6 class="mt-2">Tgl. Transaksi</h6>
                    </div>
                    <div class="col-md-7">
                        <input name="nomorpostingnya1" id="nomorpostingnya1" type="hidden"> 
                        <input name="tglpostingnya1" id="tglpostingnya1" type="hidden"> 
                        <input name="tgltransaksi1" id="tgltransaksi1" class="w3-input w3-border" maxlength="10" type="text" placeholder="Tgl Transaksi" required autocomplete="off" value="{{ $tgltransaksi }}">                       
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <h6 class="mt-2  text-right">Nomor Bukti-a</h6>
                    </div>
                    <div class="col-md-7 input-group">
                        <input name="nomorbuktia1" id="nomorbuktia1" class="form-control w3-input w3-border rounded-0" type="search" placeholder="KMM.001.20230131.001" disabled value="{{ $nomorbuktia }}">                        
                        <div class="input-group-append">
                          <button id="btn_nomorbuktia1" name="btn_nomorbuktia1" type="button" style="border-radius:0px; border:none;" title="Generate Nomor Bukti-a" disabled><i style="font-size:24" class="fa">&#xf013;</i></button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mt-2 text-right">
                        <h6>Lokasi</h6>
                    </div>
                    <div class="col-md-7">
                        <select name="idruang1" id="idruang1" class="w3-input w3-border" value="{{ $idruang }}"></select>  
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mt-2 text-right">
                        <h6>ID</h6>                        
                    </div>
                    <div class="col-md-7 input-group">
                        <input name="cariid1x" id="cariid1x" class="form-control w3-input w3-border rounded-0" type="search" placeholder="Search" autocomplete="off" autofocus>                        
                        <div class="input-group-append">
                          <button id="btn_carianggota1" name="btn_carianggota1" type="button" style="border-radius:0px; border:none;"><i style="font-size:24" class="fas">&#xf002;</i></button>
                        </div>
                    </div>
                </div> 
                
                <div class="row">
                    <div class="col-md-4  text-right">
                        <h6 class="mt-2">Nama</h6>
                    </div>
                    <div class="col-md-7">
                        <select name="idanggota1" id="idanggota1" style="display: none;"></select> 
                        <input name="nama1" id="nama1" class="w3-input w3-border" maxlength="200" type="text" placeholder="nama" value="{{ old('nama1') }}"  readonly>                       
                    </div>
                </div>               
            </div>
            
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-4  text-right">
                        <h6 class="mt-2">Ecard</h6>
                    </div>
                    <div class="col-md-7">
                        <input name="ecard1" id="ecard1" class="w3-input w3-border" maxlength="30" type="text" placeholder="ecard" value="{{ old('ecard1') }}" readonly>                       
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4  text-right">
                        <h6 class="mt-2">NIA</h6>
                    </div>
                    <div class="col-md-7">
                        <input name="nia1" id="nia1" class="w3-input w3-border" maxlength="30" type="text" placeholder="nia" value="{{ old('nia1') }}"  readonly>                       
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4  text-right">
                        <h6 class="mt-2">KTP</h6>
                    </div>
                    <div class="col-md-7">
                        <input name="ktp1" id="ktp1" class="w3-input w3-border" maxlength="30" type="text" placeholder="ktp" value="{{ old('ktp1') }}"  readonly>                       
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4  text-right">
                        <h6 class="mt-2">Lembaga</h6>
                    </div>
                    <div class="col-md-7">
                        <input name="lembaga1" id="lembaga1" class="w3-input w3-border" maxlength="50" type="text" placeholder="Lembaga" value="{{ old('lembaga1') }}"  readonly>                       
                    </div>
                </div>
                <div class="row" id="angsuranx" name="angsuranx">
                    <div class="col-md-4  text-right">
                        <h6 class="mt-2">Saldo Saving</h6>
                    </div>
                    <div class="col-md-7">
                        <input name="saldo1" id="saldo1" class="w3-input w3-border" maxlength="50" type="text" placeholder="Saldo" value="{{ old('saldo1') }}"  readonly>                       
                    </div>
                </div>
                
                
            </div>
           

            <div class="col-md-4">
                <div class="w3-row" align="right"><i class="fa fa-refresh" aria-hidden="true"></i>            
                    <a href="{{ url('/') }}{{ $link }}" class="btn bg-success rounded-0"><i style="font-size:18px" class="fa">&#xf021;</i> Refresh</a>            
                    <button id="btn_tambah1" name="btn_tambah1" type="button" class="btn bg-primary rounded-0"><i class="fas fa-plus"></i> Tambah</button>	            
                    <button id="btn_posting1" name="btn_posting1" type="button" class="btn bg-warning rounded-0"><i class="fa fa-upload"></i> Posting</button>	            
                </div>
                
                <div class="row mt-1">                    
                    <div class="col-md-12 text-center" style="font-size: 4em; color:red;">
                        <b><span id='displaysubtotal1' name='displaysubtotal1'></span></b>
                    </div>
                    <div class="block">
                        <button class="block bg-primary" id="btn_pembayaran1" name="btn_pembayaran1" style="padding-top: 15px; padding-bottom: 15px;"> Pembayaran <i style='font-size:18px' class='fas'>&#xf02f;</i></button>
                    </div>
                </div>
                
            </div>
        </div>

    </div>

    <!--awal tabel-->        
        <div class="box-body" id="headerjudul" style="display: block;">
            <div id="reload" class="table-responsive">
                
                <table id="example1" class="table table-bordered table-striped table-hover" style="width: 100%">
                    <thead>
                        <tr>
                            <th style="width:10px;">#</th>                            
							<th style="width:50px">Kode</th>
							<th style="width:50px">Barcode</th>
							<th style="width:200px">Nama Barang</th>														
							<th style="width:50px">Qty</th>
							<th style="width:50px">HJS</th>
							<th style="width:50px">HPPJ</th>
							<th style="width:50px">PPN</th>
							<th style="width:50px">Diskon</th>
							<th style="width:50px">Jumlah</th>
							<th style="width:50px">Tgl.Posting</th>
							<th style="width:50px">No.Posting</th>
							<th style="width:100px">Keterangan</th>							
                            <th style="width:10px">Action</th>
                        </tr>
                    </thead>
                    <tbody id="show_data">
                        
                    </tbody>
                    <tfoot id="show_footer">
                        <tr style="background-color: rgb(220, 220, 220)">
                            <td></td>                            
                            <td></td>
                            <td></td>
                            <td style="text-align: center"><b>TOTAL :</b></td>														
                            <td></td>
                            <td></td>
                            <td style="text-align: right"><b><span id='totalhpp1' name='totalhpp1'></span></b></td>
                            <td style="text-align: right"><b><span id='totalppn1' name='totalppn1'></span></b></td>
                            <td style="text-align: right"><b><span id='totaldiskon1' name='totaldiskon1'></span></b></td>
                            <td style="text-align: right"><b><span id='totaljumlah1' name='totaljumlah1'></span></b></td>
                            <td></td>
                            <td></td>
                            <td></td>							
                            <td></td>
                        </tr>
                    </tfoot>
                </table>				
                
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
                                <div class="col-md-3" align="right">									
                                    <h6 class="mt-2">Nama Barang</h6>
                                </div>
                                <div class="col-md-9 input-group">
                                    {{-- <input name="barang1" id="barang1" class="form-control w3-input w3-border" type="text" placeholder="Nama Barang" style="border-radius:0px; border:none; display:none;" readonly> --}}
                                    <select  name="idbarang1" id="idbarang1" class=" form-control w3-input w3-border" style="border-radius:0px; border:none; display:block;"></select>
                                    <div class="input-group-append">
                                      <button id="btn_caribarang1" name="btn_caribarang1" type="button" style="border-radius:0px; border:none;"><i style="font-size:24" class="fas">&#xf002;</i></button>
                                      <input name="idbarang1x" id="idbarang1x" type="hidden">
                                    </div>
                                    <input name="cek1" id="cek1" class="" type="hidden">                                
                                    <input name="id1" id="id1" class="" type="hidden"> 
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 mt-1" align="right">									
                                    <h6 class="mt-2">Kode</h6>
                                </div>
                                <div class="col-md-9">                                
                                    <input name="kode1" id="kode1" class="w3-input w3-border" type="text" placeholder="kode" readonly>
                                </div>								  
                            </div>

                            <div class="row">
                                <div class="col-md-3 mt-1" align="right">									
                                    <h6 class="mt-2">Barcode</h6>
                                </div>
                                <div class="col-md-9">                                
                                    <input name="barcode1" id="barcode1" class="w3-input w3-border" type="text" placeholder="barcode" readonly>
                                </div>								  
                            </div>
                             
                            <div class="row">
                                <div class="col-md-3 mt-1" align="right">									
                                    <h6 class="mt-2">Qty</h6>
                                </div>
                                <div class="col-md-9">
                                    <input name="qtycek1" id="qtycek1" class="" type="hidden">                                
                                    <input name="qty1" id="qty1" class="w3-input w3-border text-right" type="search" placeholder="qty" value="1"  onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-3 mt-1" align="right">									
                                    <h6 class="mt-2">HJS</h6>
                                </div>
                                <div class="col-md-9"> 
                                    <input name="hjs1" id="hjs1" class="w3-input w3-border text-right" type="search" placeholder="Harga Jual Satuan" value="0"  onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">                             
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-3 mt-1" align="right">
                                    <h6 class="mt-2">HPPJ</h6>
                                </div>
                                <div class="col-md-9">
                                    <input name="hppj1" id="hppj1" class="w3-input w3-border" type="search" placeholder="Harga Pokok Penjualan" style="text-align: right" value="0" readonly>
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-3 mt-1" align="right">									
                                    <h6 class="mt-2">PPN</h6>
                                </div>
                                <div class="col-md-9">
                                    <input name="ppn1" id="ppn1" class="w3-input w3-border text-right" type="search" placeholder="PPN" value="0"  onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                    <input name="ppnpersen1" id="ppnpersen1" class="" type="hidden" value="0">                                
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-3 mt-1" align="right">									
                                    <h6 class="mt-2">Diskon</h6>
                                </div>
                                <div class="col-md-9">                                
                                    <input name="diskon1" id="diskon1" class="w3-input w3-border text-right" type="search" placeholder="Diskon" value="0"  onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                    <input name="diskonpersen1" id="diskonpersen1" class="" type="hidden" value="0">
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-3 mt-1" align="right">									
                                    <h6 class="mt-2">Jumlah</h6>
                                </div>
                                <div class="col-md-9">                                
                                    <input name="jumlah1" id="jumlah1" class="w3-input w3-border" type="search" placeholder="Jumlah" style="text-align: right" value="0" readonly>
                                </div>								  
                            </div>
                            
                            <div class="row">
                                <div class="col-md-3" align="right">										
                                    <h6 class="mt-2">Keterangan</h6>
                                </div>
                                <div class="col-md-9">                                
                                    <input name="keterangan1" id="keterangan1" class="w3-input w3-border" type="search" maxlength="100" placeholder="Keterangan" value="{{ old('keterangan1') }}">
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

    <!-- ModalCariBarang modal fade-->
	<div class="modal fade" id="ModalCariBarang"  data-backdrop="static">
		<div class="modal-dialog modal-lg">  <!-- modal-(sm, lg, xl) ukuran lebar modal -->
			<div class="modal-content bg-info w3-animate-zoom">
				
				<div class="modal-header">
						<h3 class="modal-title"><i style="font-size:18" class="fas">&#xf002;</i><b> Cari Data</b></h3>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
						
				</div>
				<form class="form-horizontal">
                    @csrf
					<div class="modal-body">
												
						<div class="row">
							<div id="reload" class="table-responsive">
								<table id="barang" class="table table-bordered table-striped table-hover" style="width: 100%">
                                    <thead>
                                        <tr style="align-content: center">
                                            <th style="width:10px;">#</th>
                                            <th style="width:20px">Kode</th>
                                            <th style="width:50px">Barcode</th>
                                            <th style="width:200px">Nama Menu</th>
                                            <th style="width:20px">Satuan</th>
                                            <th style="width:100px">Kategori</th>
                                            <th style="width:20px">HJS</th>
                                        </tr>
                                    </thead>
                                    <tfoot id="show_footer">
                                        
                                    </tfoot>
                                    <tbody id="show_barang">
                                    
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
	<!-- end ModalCariBarang -->

    <!-- ModalCariAnggota modal fade-->
	<div class="modal fade" id="ModalCariAnggota"  data-backdrop="static">
		<div class="modal-dialog modal-xl">  <!-- modal-(sm, lg, xl) ukuran lebar modal -->
			<div class="modal-content bg-info w3-animate-zoom">
				
				<div class="modal-header">
						<h3 class="modal-title"><i style="font-size:18" class="fas">&#xf002;</i><b> Cari Data</b></h3>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
						
				</div>
				<form class="form-horizontal">
                    @csrf
					<div class="modal-body">
												
						<div class="row">
							<div id="reload" class="table-responsive">
								<table id="anggota" class="table table-bordered table-striped table-hover" style="width: 100%;">
                                    <thead>
                                        <tr style="align-content: center">
                                            <th style="width:10px;">#</th>
                                            <th style="width:50px">Ecard</th>
                                            <th style="width:50px">NIA</th>
                                            <th style="width:50px">KTP</th>
                                            <th style="width:200px">Nama</th>
                                            <th style="width:100px">Lembaga</th>
                                            <th style="width:50px">Telp</th>
                                        </tr>
                                    </thead>
                                    <tfoot id="show_footer">
                                        
                                    </tfoot>
                                    <tbody id="show_anggota">
                                    
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
	<!-- end ModalCariAnggota -->
   
<!-- ModalPosting modal fade-->
<div class="modal fade" id="ModalPosting"  data-backdrop="static">
    <div class="modal-dialog modal-default">  <!-- modal-(sm, lg, xl) ukuran lebar modal -->
        <div class="modal-content bg-warning w3-animate-zoom">
            
            <div class="modal-header">
                    <h3 class="modal-title"><i style="font-size:18" class="fa">&#xf093;</i><b> Posting Barang Keluar</b></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    
            </div>
            <form class="form-horizontal">
                @csrf
                <div class="modal-body">
                    
                        <div class="row">
                            <div class="col-md-5" align="right" style="padding-right: 0px; padding-left: 0px;">										
                                <h4 class="mt-2">Tgl. Transaksi</h4>
                            </div>
                            <div class="col-md-1" align="center">                                
                                <h4 class="mt-2">:</h4>
                            </div>								  
                            <div class="col-md-6" style="padding-right: 0px; padding-left: 0px;">                                
                                <h4 class="mt-2"><span  id="tgltransaksi5" name="tgltransaksi5"></span></h4>
                            </div>								  
                        </div> 			
                        <div class="row">
                            <div class="col-md-5" align="right" style="padding-right: 0px; padding-left: 0px;">										
                                <h4 class="mt-2">Nomor Bukti-a</h4>
                            </div>
                            <div class="col-md-1" align="center">                                
                                <h4 class="mt-2">:</h4>
                            </div>								  
                            <div class="col-md-6" style="padding-right: 0px; padding-left: 0px;">                                
                                <h4 class="mt-2"><span  id="nomorbuktia5" name="nomorbuktia5"></span></h4>
                            </div>								  
                        </div> 			
                         			
                        <div class="row">
                            <div class="col-md-5" align="right" style="padding-right: 0px; padding-left: 0px;">										
                                <h4 class="mt-2">Tgl. Posting</h4>
                            </div>
                            <div class="col-md-1" align="center">                                
                                <h4 class="mt-2">:</h4>
                            </div>								  
                            <div class="col-md-6" style="padding-right: 0px; padding-left: 0px;"> 
                                <h4 class="mt-2"><span  id="tglposting5" name="tglposting5"></span></h4> 
                            </div>								  
                        </div>  			
                        <div class="row">
                            <div class="col-md-5" align="right" style="padding-right: 0px; padding-left: 0px;">										
                                <h4 class="mt-2">Nomor Posting</h4>
                            </div>
                            <div class="col-md-1" align="center">                                
                                <h4 class="mt-2">:</h4>
                            </div>								  
                            <div class="col-md-6" style="padding-right: 0px; padding-left: 0px;">
                                <h4 class="mt-2"><span id="nomorposting5" name="nomorposting5"></span></h4>
                            </div>								  
                        </div>
                        <div class="row">
                            <div class="col-md-5" align="right" style="padding-right: 0px; padding-left: 0px;">										
                                <h4 class="mt-2">Jumlah Item</h4>
                            </div>
                            <div class="col-md-1" align="center">                                
                                <h4 class="mt-2">:</h4>
                            </div>								  
                            <div class="col-md-6" style="padding-right: 0px; padding-left: 0px;">
                                <h4 class="mt-2"><span  id="jmlitem5" name="jmlitem5"></span></h4>                                                                
                            </div>								  
                        </div>
                        <div class="row">
                            <div class="col-md-5" align="right" style="padding-right: 0px; padding-left: 0px;">										
                                <h4 class="mt-2">Jumlah Barang</h4>
                            </div>
                            <div class="col-md-1" align="center">                                
                                <h4 class="mt-2">:</h4>
                            </div>								  
                            <div class="col-md-6" style="padding-right: 0px; padding-left: 0px;">
                                <h4 class="mt-2"><span  id="jmlbarang5" name="jmlbarang5"></span></h4>
                            </div>								  
                        </div>  			
                        <div class="row">
                            <div class="col-md-5" align="right" style="padding-right: 0px; padding-left: 0px;">										
                                <h4 class="mt-2">Total Nilai</h4>
                            </div>
                            <div class="col-md-1" align="center">                                
                                <h4 class="mt-2">:</h4>
                            </div>								  
                            <div class="col-md-6" style="padding-right: 0px; padding-left: 0px;">
                                <h4 class="mt-2"><span  id="totalnilai5" name="totalnilai5"></span></h4>
                            </div>								  
                        </div>  			
                    
                </div>
                <div class="modal-footer justify-content-between" align="right">
                    <button type="button" class="w3-button w3-border w3-border-white" data-dismiss="modal">Tutup</button>
                    <button id="btn_posting" name="btn_posting" type="button" class="w3-button w3-border w3-border-white"><i style="font-size:18px" class="fa">&#xf093;</i> Posting</button>
                </div>
            </form>
        </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- end ModalPosting -->

<!-- ModalPembayaran modal fade-->
<div class="modal fade" id="ModalPembayaran"  data-backdrop="static">
    <div class="modal-dialog modal-default">  <!-- modal-(sm, lg, xl) ukuran lebar modal -->
        <div class="modal-content bg-primary w3-animate-zoom">
            
            <div class="modal-header">
                    <h3 class="modal-title"><i style="font-size:18" class='fas'>&#xf02f;</i><b> Pembayaran</b></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    
            </div>
            <form class="form-horizontal">
                @csrf
                <div class="modal-body" style="font-size: 1.5em;" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-5 mt-2" align="right" style="padding-right: 10px; padding-left: 0px;">										
                                    <h4 class="mt-2">Sub Total</h4>
                                </div>
                                <div class="col-md-7" style="padding-right: 20px; padding-left: 0px;">
                                    <input name="subtotals1" id="subtotals1" class="w3-input w3-border text-right" type="search" placeholder="" value="{{ old('subtotals1') }}"  onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"  readonly>
                                </div>                            								  
                            </div>
                            <div class="row">
                                <div class="col-md-5 mt-2" align="right" style="padding-right: 10px; padding-left: 0px;">										
                                    <h4 class="mt-2">PPN</h4>
                                </div>                                						  
                                <div class="col-md-7" style="padding-right: 20px; padding-left: 0px;">
                                    <input name="ppns1" id="ppns1" class="w3-input w3-border text-right" type="search" placeholder="" value="{{ old('ppns1') }}"  onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"  readonly>
                                </div>  								  
                            </div> 			
                                         
                            <div class="row">
                                <div class="col-md-5 mt-2" align="right" style="padding-right: 10px; padding-left: 0px;">										
                                    <h4 class="mt-2">Diskon</h4>
                                </div>							  
                                <div class="col-md-7" style="padding-right: 20px; padding-left: 0px;">
                                    <input name="diskons1" id="diskons1" class="w3-input w3-border text-right" type="search" placeholder="" value="{{ old('diskons1') }}"  onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"  readonly>
                                </div>								  
                            </div>  			
                            
                            <div class="row">
                                <div class="col-md-5 mt-2" align="right" style="padding-right: 10px; padding-left: 0px;">										
                                    <h4 class="mt-2">Total</h4>
                                </div>								  
                                <div class="col-md-7" style="padding-right: 20px; padding-left: 0px;" align="right">
                                    <input name="totals1" id="totals1" class="w3-input w3-border text-right" type="search" placeholder="" value="{{ old('totals1') }}"  onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"  readonly>
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-5 mt-2" align="right" style="padding-right: 10px; padding-left: 0px;">										
                                    <h4 class="mt-2">Bayar</h4>
                                </div>                                							  
                                <div class="col-md-7" style="padding-right: 20px; padding-left: 0px;" align="right">
                                    <input name="bayars1" id="bayars1" class="w3-input w3-border text-right" style=" background-color: aqua;" type="search" placeholder="bayar" value="0"  onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" autofocus>
                                </div>								  
                            </div>  			
                            <div class="row">
                                <div class="col-md-5 mt-2" align="right" style="padding-right: 10px; padding-left: 0px;">										
                                    <h4 class="mt-2">Voucher</h4>
                                </div>							  
                                <div class="col-md-7" style="padding-right: 20px; padding-left: 0px;" align="right">
                                    <input name="vouchers1" id="vouchers1" class="w3-input w3-border text-right" style=" background-color: aqua;" type="search" placeholder="voucher" value="0"  onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                </div>								  
                            </div>  			
                            <div class="row">
                                <div class="col-md-5 mt-2" align="right" style="padding-right: 10px; padding-left: 0px;">										
                                    <h4 class="mt-2">Ambil Savings</h4>
                                </div>								  
                                <div class="col-md-7" style="padding-right: 20px; padding-left: 0px;" align="right">
                                    <input name="ambilsavings1" id="ambilsavings1" class="w3-input w3-border text-right" style=" background-color: aqua;" type="search" placeholder="ambil savings" value="0"  onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                </div>								  
                            </div>  			
                            <div class="row">
                                <div class="col-md-5 mt-2" align="right" style="padding-right: 10px; padding-left: 0px;">										
                                    <h4 class="mt-2">Kembalian</h4>
                                </div>							  
                                <div class="col-md-7" style="padding-right: 20px; padding-left: 0px;">
                                    <input name="kembalis1" id="kembalis1" class="w3-input w3-border text-right" style="background-color: red;" type="search" placeholder="" value="{{ old('kembalis1') }}" readonly>
                                </div>								  
                            </div>  			
                            <div class="row">
                                <div class="col-md-5 mt-2" align="right" style="padding-right: 10px; padding-left: 0px;">										
                                    <h4 class="mt-2">Savings</h4>
                                </div>                                								  
                                <div class="col-md-7" style="padding-right: 20px; padding-left: 0px;">
                                    <input name="savings1" id="savings1" class="w3-input w3-border text-right" style="background-color: red;" type="search" placeholder="" value="{{ old('savings1') }}" readonly>
                                </div>								  
                            </div> 
                            <div class="row">
                                <div class="col-md-5 mt-2" align="right" style="padding-right: 10px; padding-left: 0px;">										
                                    <h4 class="mt-2">Jenis Pembayaran</h4>
                                </div>                                								  
                                <div class="col-md-7" style="padding-right: 20px; padding-left: 0px;">
                                    <select name="idjenispembayaran1" id="idjenispembayaran1" class="w3-input w3-border" style=" background-color: aqua;"></select>
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-5 mt-2" align="right" style="padding-right: 10px; padding-left: 0px;">										
                                    <h4 class="mt-2" name="judulkali1" id="judulkali1">Angsuran (X)</h4>
                                </div>                                								  
                                <div class="col-md-7" style="padding-right: 20px; padding-left: 0px;">
                                    <input name="jmls1" id="jmls1" class="" type="hidden">
                                    <input name="kali1" id="kali1" class="w3-input w3-border text-right" style=" background-color: aqua;" type="number" min="1" max="60" placeholder="" value="1">
                                </div>								  
                            </div>  
                            <div class="row">
                                <div class="col-md-5 mt-2" align="right" style="padding-right: 10px; padding-left: 0px;">										
                                    <h4 class="mt-2" name="judulpersenjasa1" id="judulpersenjasa1">% Jasa</h4>
                                </div>                                								  
                                <div class="col-md-7" style="padding-right: 20px; padding-left: 0px;">
                                    <input name="persenjasa1" id="persenjasa1" class="w3-input w3-border text-right" style=" background-color: aqua;" type="search" placeholder="persen jasa" value="0.00" onkeypress="return hanyaAngkaTitik(event)">
                                </div>								  
                            </div>  
                            <div class="row">
                                <div class="col-md-5 mt-2" align="right" style="padding-right: 10px; padding-left: 0px;">										
                                    <h4 class="mt-2" name="judulnilaihutang1" id="judulnilaihutang1">Nilai Hutang</h4>
                                </div>                                								  
                                <div class="col-md-7" style="padding-right: 20px; padding-left: 0px;">
                                    <input name="nilaihutang1" id="nilaihutang1" class="w3-input w3-border text-right" style=" background-color: aqua;" type="search" placeholder="nilai hutang" value="0"  onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                </div>								  
                            </div>  
                            
                        </div>
                        {{-- <div class="col-md-6">

                        </div> --}}
                    </div>    
                    
                </div>
                <div class="modal-footer justify-content-between" align="right">
                    <button id ="btn_tutup" name="btn_tutup"type="button" class="w3-button w3-border w3-border-white" data-dismiss="modal">Tutup</button>
                    <button id="btn_cetak" name="btn_cetak" type="button" class="w3-button w3-border w3-border-white" ><i style="font-size:18px" class='fas'>&#xf02f;</i> Print</button>
                    <button id="btn_proses" name="btn_proses" type="button" class="w3-button w3-border w3-border-white" disabled><i style="font-size:18" class="fa">&#xf013;</i> Proses</button>
                </div>
            </form>
        </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- end ModalPembayaran -->


    <!-- khusus menyimpan data yang akan dihapus -->
    <input name="id3" id="id3"type="hidden">	
    <input name="data3a" id="data3a"type="hidden">	
    <input name="data3b" id="data3b"type="hidden">	
    <input name="data3c" id="data3c"type="hidden">	

</div>


<script type="text/javascript">
    function hanyaAngkaTitik(evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))

            return false;
        return true;
    }

   var barangDatatable;
   var anggotaDatatable;
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
        $('#tglposting5').text(tglsekarang);
    }

   setTimeout(() => {
    document.getElementById('kali1').setAttribute("hidden","hidden");
    document.getElementById('judulkali1').setAttribute("hidden","hidden");    
    document.getElementById('persenjasa1').setAttribute("hidden","hidden");
    document.getElementById('judulpersenjasa1').setAttribute("hidden","hidden");    
    document.getElementById('nilaihutang1').setAttribute("hidden","hidden");
    document.getElementById('judulnilaihutang1').setAttribute("hidden","hidden");    
   }, 1000);

    tampil_listbarang();
    //menampilkan combo barang
    function tampil_listbarang(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.transaksi.mkeluar_listmamin')}}',
            
            success: function(data){				    
                $("#idbarang1").html(data);                
            }
        })                    
    }
    
    tampil_listanggota();
    //menampilkan combo anggota
    function tampil_listanggota(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.transaksi.mkeluar_listanggota')}}',
            
            success: function(data){				    
                $("#idanggota1").html(data);                
            }
        })                    
    }

    tampil_listjenispembayaran();
    //menampilkan combo jenispembayaran
    function tampil_listjenispembayaran(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.transaksi.mkeluar_listjenispembayaran')}}',
            
            success: function(data){				    
                $("#idjenispembayaran1").html(data);                
            }
        })                    
    }
    
    tampil_data();  
    tampil_tombol();
    
    function tampil_tombol(){
        $('#example1').DataTable( {
            
            "responsive": true, "lengthChange": false, "autoWidth": false, "retrieve": true,
            buttons : [ {extend: 'colvis', postfixButtons: [ 'colvisRestore' ] }, {extend:'copy'}, {extend:'csv'}, {extend: 'pdf', orientation: 'portrait', pageSize: 'A4', title:'{{ $caption }}'}, {extend: 'excel', title: '{{ $caption }}'}, {extend:'print', orientation: 'portrait', pageSize: 'A4', title: '{{ $caption }}'}, ],
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');   
     }
     
    
    //tampilkan dalam tabel ->OK
    function tampil_data(){	
        
        $.ajax({
            type  : 'get',
            url   : `{{route('pos01.transaksi.mkeluar_show')}}`,
            async : false,
            dataType : 'json',
                                             
            success : function(data){
                var nomorpostingx;
                var idruangx;
                var idanggotax;
                var jumlahy;
                var hppjx = 0;
                var tglposting;
                var qtyx = 0;
                var ppnx = 0;
                var diskonx = 0;
                var jumlahx = 0;
                var html = '';
                var aksi;
                var i;                
                var resultData = data.data;	   
                console.log(resultData);
                for(i=0; i<resultData.length; i++){
                    nomorpostingx = resultData[i].nomorposting;
                    idruangx = resultData[i].idruang;
                    idanggotax = resultData[i].idanggota;
                    qtyx = parseInt(qtyx) + parseInt(resultData[i].qty);
                    hppjx = parseInt(hppjx) + parseInt(resultData[i].hppj);
                    ppnx = parseInt(ppnx) + parseInt(resultData[i].ppn);
                    diskonx = parseInt(diskonx) + parseInt(resultData[i].diskon);
                    jumlahy = parseInt(resultData[i].hppj)+parseInt(resultData[i].ppn)-parseInt(resultData[i].diskon); 
                    jumlahx = parseInt(jumlahx) + parseInt(jumlahy);

                    tglposting = (resultData[i].nomorposting ? resultData[i].nomorposting : '').length;
                    
                    if(tglposting=='0'){
                        html += '<tr>';
                        aksi = '<td style="text-align:center;">' +
                                    '<a href="javascript:;" title="Edit Data"  class="btn btn-success btn-xs item_edit" data="'+resultData[i].id+'" data2="'+resultData[i].mamin.kode+'" data3="'+resultData[i].mamin.namamin+'" data4="'+resultData[i].keterangan+'"><i style="font-size:18px;" class="fa" >&#xf044;</i></a>'+ ' ' +
                                    '<a href="javascript:;" title="Hapus Data"  class="btn btn-danger btn-xs item_hapus" ' +
                                        'data="'+resultData[i].id+'" data2="'+resultData[i].mamin.kode+'" data3="'+resultData[i].mamin.namamin+'" data4="'+resultData[i].keterangan+'" ' +
                                        '><i style="font-size:18px" class="fa">&#xf00d;</i></a>' +   
                                '</td>';
                        $('#btn_tambah1').removeAttr('disabled');
                        $('#btn_posting1').removeAttr('disabled');
                        
                    }else{
                        html += '<tr style="background:goldenrod">';
                        aksi = '<td style="text-align:center;">' +
                                '<a href="javascript:;" title="Edit Data"  class="btn btn-success btn-xs item_edit  disabled" data="'+resultData[i].id+'" data2="'+resultData[i].mamin.kode+'" data3="'+resultData[i].mamin.namamin+'" data4="'+resultData[i].keterangan+'"><i style="font-size:18px;" class="fa">&#xf044;</i></a>'+ ' ' +
                                '<a href="javascript:;" title="Hapus Data"  class="btn btn-danger btn-xs item_hapus  disabled" ' +
                                    'data="'+resultData[i].id+'" data2="'+resultData[i].mamin.kode+'" data3="'+resultData[i].mamin.nabara+'" data4="'+resultData[i].keterangan+'" ' +
                                    '><i style="font-size:18px" class="fa">&#xf00d;</i></a>' +   
                            '</td>';
                        $('#btn_tambah1').attr('disabled', '');
                        $('#btn_posting1').attr('disabled', '');                                                        
                    }

                    html += 
                                '<td align="center">'+ (i+1) +'</td>'+                            								
                                '<td>'+resultData[i].mamin.kode+'</td>'+
                                '<td>'+resultData[i].mamin.barcode+'</td>'+
                                '<td>'+resultData[i].mamin.namamin+'</td>'+
                                '<td align="center">'+formatAngka(resultData[i].qty,'')+'</td>'+
                                '<td align="right">'+formatAngka(resultData[i].hjs,'')+'</td>'+
                                '<td align="right">'+formatAngka(resultData[i].hppj,'')+'</td>'+
                                '<td align="right">'+formatAngka(resultData[i].ppn,'')+'</td>'+
                                '<td align="right">'+formatAngka(resultData[i].diskon,'')+'</td>'+
                                '<td align="right">'+formatAngka(jumlahy,'')+'</td>'+
                                '<td>'+(resultData[i].tglposting ? resultData[i].tglposting : '')+'</td>'+
                                '<td>'+(resultData[i].nomorposting ? resultData[i].nomorposting : '')+'</td>'+
                                '<td>'+(resultData[i].keterangan ? resultData[i].keterangan : '')+'</td>'+
                                aksi +
                            '</tr>';

                        $('#nomorpostingnya1').val(resultData[i].nomorposting);            
                        $('#tglpostingnya1').val(resultData[i].tglposting);            
                    
                }
                
                $('#show_data').html(html); 
                $('#jmlitem5').text(i);                            
                $('#jmlbarang5').text(qtyx);
                $('#totalhpp1').text(formatAngka(hppjx,''));
                $('#totalppn1').text(formatAngka(ppnx,''));
                $('#totaldiskon1').text(formatAngka(diskonx,''));
                $('#totaljumlah1').text(formatAngka(jumlahx,''));
                $('#totalnilai5').text(formatAngka(jumlahx,''));
                $('#displaysubtotal1').text($('#totaljumlah1').text());
                $('#subtotals1').val(formatAngka(hppjx,''));
                $('#ppns1').val(formatAngka(ppnx,''));
                $('#diskons1').val(formatAngka(diskonx,''));
                $('#totals1').val(formatAngka(jumlahx,''));
                $('#idruang1').val(idruangx);
                
                if(i=='0'){
                    $('#btn_tambah1').removeAttr('disabled');
                    $('#btn_posting1').removeAttr('disabled');
                    $( "#cariid1x" ).prop( "disabled", false ); 
                    $( "#btn_carianggota1" ).prop( "disabled", false );
                    $( "#idruang1" ).prop( "disabled", false );
                    
                }else{
                    $( "#cariid1x" ).prop( "disabled", true ); 
                    $( "#btn_carianggota1" ).prop( "disabled", true );
                    $( "#idruang1" ).prop( "disabled", true );
                    setTimeout(() => {
                        customer(idanggotax);
                    }, 100);
                    
                }                            
            }

    
        }); 
    }

    $('#btn_posting1').on('click',function(){
        setTimeout(() => {
            nomorposting();
            setTimeout(() => {
                $('#tgltransaksi5').text($('#tgltransaksi1').val());        		
                $('#nomorbuktia5').text($('#nomorbuktia1').val());        		
                $('#ModalPosting').modal('show');						                
            }, 10);
        }, 200);
        
    });
    
    $('#btn_posting').on('click',function(){
        modal_posting();        
    });
    
    $('#btn_caribarang1').on('click',function(){        		
        $('#ModalCariBarang').modal('show');						
    });
    $('#btn_carianggota1').on('click',function(){        		
        $('#ModalCariAnggota').modal('show');						
    });
    
    setTimeout(() => {
         barangDatatable = tampil_data_barang();    
         anggotaDatatable = tampil_data_anggota();    
    }, 1000);

    function tampil_data_barang(){
       let i = 1;	
       return $('#barang').DataTable({
           responsive : true,
           retrieve: true,
           autoWidth : true,
           buttons : [ {extend: 'colvis', postfixButtons: [ 'colvisRestore' ] }, {extend:'copy'}, {extend:'csv'}, {extend: 'pdf', orientation: 'portrait', pageSize: 'A4', title:'{{ $caption }}'}, {extend: 'excel', title: '{{ $caption }}'}, {extend:'print', orientation: 'portrait', pageSize: 'A4', title: '{{ $caption }}'}, ],        
           dom: 'lfrtip',
           lengthMenu: [
               [ 10, 25, 50, 100, 500, 1000, 5000, -1 ],
               [ '10', '25', '50', '100', '500','1000','5000', 'All' ]
           ],
           processing: true,
           serverSide: true,
           ajax   : `{{route('pos01.transaksi.mkeluar_showmamin')}}`,
           columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                   orderable: false, 
                   searchable: false },
                { data: 'kode', name: 'kode', className: 'dt-center' },
                { data: 'barcode', name: 'barcode', className: 'dt-center' },
                { data: 'namamin', name: 'namamin'},
                { data: 'satuan', name: 'satuan.satuan'},
                { data: 'kategori', name: 'kategori.kategori'},
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
               
           ]
       });
    }

    $('#show_barang').on('click','.item_namamin',function(){ 
        ambilcaribarang(this);        
    });
   
    $('#show_barang').on('click','.item_barcode',function(){        
        ambilcaribarang(this);        
    });
    $('#show_barang').on('click','.item_kode',function(){        
        ambilcaribarang(this);        
    });

    function ambilcaribarang(t){
        var id1 = $(this).attr('data');
        var idbarang1 = $(t).attr('data1');
        var kode1 = $(t).attr('data2');
        var barcode1 = $(t).attr('data3');
        var hjs1 = $(t).attr('data5');
        var ppnpersen1 = $(t).attr('data9');
        var diskonpersen1 = $(t).attr('data10');
        
        ambil_mamin(idbarang1);

        // $('#idbarang1').val(idbarang1);
        // $('#barang1').val($('#idbarang1 option:selected').text());
        // $('#kode1').val(kode1);
        // $('#qtycek1').val(qtycek1);
        // $('#hjs1').val(formatAngka(hjs1,''));
        // $('#barcode1').val(barcode1);
        // $('#ppnpersen1').val(ppnpersen1);
        // $('#diskonpersen1').val(diskonpersen1);

        setTimeout(() => {
            var qtyx = parseFloat(qty1.replace(/[^,\d]/g, '').toString());
            var hjsx = parseFloat(hjs1.replace(/[^,\d]/g, '').toString());
            var ppnpersenx = parseFloat(ppnpersen1);
            var diskonpersenx = parseFloat(diskonpersen1);

            var hppjx = qtyx * hjsx;
            $('#hppj1').val(formatAngka(hppjx,''));
            var ppnx = Math.round(hppjx * ppnpersenx / 100);
            var diskonx = Math.round(hppjx * diskonpersenx / 100);
            var jumlahx = hppjx + ppnx - diskonx;
            $('#ppn1').val(formatAngka(ppnx,''));
            $('#diskon1').val(formatAngka(diskonx,''));
            $('#jumlah1').val(formatAngka(jumlahx,''));


        }, 100);

        $('#ModalCariBarang').modal('hide');
    }

    function tampil_data_anggota(){
       let i = 1;	
       return $('#anggota').DataTable({
           responsive : true,
           retrieve: true,
           autoWidth : true,
           buttons : [ {extend: 'colvis', postfixButtons: [ 'colvisRestore' ] }, {extend:'copy'}, {extend:'csv'}, {extend: 'pdf', orientation: 'portrait', pageSize: 'A4', title:'{{ $caption }}'}, {extend: 'excel', title: '{{ $caption }}'}, {extend:'print', orientation: 'portrait', pageSize: 'A4', title: '{{ $caption }}'}, ],        
           dom: 'lfrtip',
           lengthMenu: [
               [ 10, 25, 50, 100, 500, 1000, 5000, -1 ],
               [ '10', '25', '50', '100', '500','1000','5000', 'All' ]
           ],
           processing: true,
           serverSide: true,
           ajax   : `{{route('pos01.transaksi.mkeluar_showanggota')}}`,
           columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                   orderable: false, 
                   searchable: false },
                { data: 'ecard', name: 'ecard', className: 'dt-center' },
                { data: 'nia', name: 'nia', className: 'dt-center' },
                { data: 'ktp', name: 'ktp', className: 'dt-center' },
                { data: 'nama', name: 'nama'},
                { data: 'lembaga', name: 'anggota.lembaga'},
                { data: 'telp', name: 'telp'},
               
           ]
       });
    }

    $('#show_anggota').on('click','.item_ecard',function(){ 
        ambilcarianggota(this);        
    });
    $('#show_anggota').on('click','.item_nia',function(){        
        ambilcarianggota(this);        
    });
    $('#show_anggota').on('click','.item_ktp',function(){        
        ambilcarianggota(this);        
    });
    $('#show_anggota').on('click','.item_nama',function(){        
        ambilcarianggota(this);        
    });

    function ambilcarianggota(t){
        var id1x = $(t).attr('data100');
        $('#idanggota1').val(id1x);
        var a = $('#idanggota1 option:selected').text();
        const aArray = a.split("|");
        var ecard1 = aArray[0];
        var nia1 = aArray[1];
        var ktp1 = aArray[2];
        var nama1 = aArray[3];
        var lembaga1 = aArray[4];

        $('#ecard1').val(ecard1);
        $('#nia1').val(nia1);
        $('#ktp1').val(ktp1);
        $('#nama1').val(nama1);
        $('#lembaga1').val(lembaga1);


        $('#ModalCariAnggota').modal('hide');
    }

    $('#cariid1x').keypress(function (e) {        
        $('#ecard1').val('');
        $('#nia1').val('');
        $('#ktp1').val('');
        $('#nama1').val('');
        $('#lembaga1').val('');
        if (e.which == 13) {
            var x = $('#cariid1x').val();
            // cariid(x);
            if(x==''){
                $('#btn_carianggota1').click();
            }else{
                cariid(x);
            }
        }

    });

    function cariid(cari){
        var cari1=cari;
        
        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pos01.transaksi.mkeluar_cariid')}}',
            async : false,
            dataType : 'json',
            // data : FormData,
            data : {cari1:cari1},
            // cache: false,
            // processData: false,
            // contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },				 				
            success : function(data){ 
                var resultData = data.data;	
                var x=resultData.length; 
                var y;                
                var i;                
                 for(i=0; i<resultData.length; i++){                         
                        $('#idanggota1').val(resultData[i].id);
                        $('#ecard1').val(resultData[i].ecard);
                        $('#nia1').val(resultData[i].nia);
                        $('#ktp1').val(resultData[i].ktp);
                        $('#nama1').val(resultData[i].nama);
                        $('#lembaga1').val(resultData[i].lembaga.lembaga); 
                }
                
                if(i=='0'){
                    $('#btn_carianggota1').click(); 
                }

                },
            error : function(data){ 
                    // alert('error cariid(cari)');
                }
        });
        
    }

    function customer(idx){
        var id1=idx;
        var nomorbuktia1 = $('#nomorbuktia1').val();			
            
            $.ajax({
                type  : 'get',
                url   : `{{ url('pos01/master/anggotaedit')}}/${id1}`,
                async : false,
                dataType : 'json',	
                
                success : function(data){
                    var idanggotax;
                    var i;                
                    var resultData = data.data;	                			
                    for(i=0; i<resultData.length; i++){
                        idanggotax = resultData[i].id;
                        $('#idanggota1').val(resultData[i].id);
                        $('#ecard1').val(resultData[i].ecard);
                        $('#nia1').val(resultData[i].nia);
                        $('#ktp1').val(resultData[i].ktp);
                        $('#nama1').val(resultData[i].nama);
                        $('#lembaga1').val(resultData[i].lembaga.lembaga);
                        $('#saldo1').val(resultData[i].akhir);
                    }
                    
                },
                error : function(data){
                    
                }
            }); 
            displaypembayaran(nomorbuktia1);

    }
   
    function displaypembayaran(idx){
        var id1=idx;
        
            $.ajax({
                type  : 'get',
                url   : `{{ url('pos01/transaksi/mkeluardisplaypembayaran//')}}/${id1}`,
                async : false,
                dataType : 'json',	
                
                success : function(data){
                    
                    var i;                
                    var resultData = data.data;	                			
                    for(i=0; i<resultData.length; i++){
                        $('#bayars1').val(formatAngka(resultData[i].bayars,''));  
                        $('#vouchers1').val(formatAngka(resultData[i].vouchers,''));  
                        $('#ambilsavings1').val(formatAngka(resultData[i].ambilsavings,''));  
                        $('#kembalis1').val(formatAngka(resultData[i].kembalis,''));  
                        $('#savings1').val(formatAngka(resultData[i].savings,''));  
                        $('#idjenispembayaran1').val(formatAngka(resultData[i].idjenispembayaran,''));  
                        $('#kali1').val(resultData[i].xangsuran);  
                        $('#jmls1').val(resultData[i].jml); 
                        $('#persenjasa1').val(resultData[i].persenjasa); 
                        $('#nilaihutang1').val(formatAngka(resultData[i].nilaihutang,'')); 

                        setTimeout(() => {
                            var x =parseFloat($("#idjenispembayaran1").val());
                            if(x=='99'){
                                document.getElementById('kali1').removeAttribute("hidden");
                                document.getElementById('judulkali1').removeAttribute("hidden");
                                document.getElementById('persenjasa1').removeAttribute("hidden");
                                document.getElementById('judulpersenjasa1').removeAttribute("hidden");
                                document.getElementById('nilaihutang1').removeAttribute("hidden");
                                document.getElementById('judulnilaihutang1').removeAttribute("hidden");
                            }else{
                               
                                document.getElementById('kali1').setAttribute("hidden","hidden");
                                document.getElementById('judulkali1').setAttribute("hidden","hidden");
                                document.getElementById('persenjasa1').setAttribute("hidden","hidden");
                                document.getElementById('judulpersenjasa1').setAttribute("hidden","hidden");
                                document.getElementById('nilaihutang1').setAttribute("hidden","hidden");
                                document.getElementById('judulnilaihutang1').setAttribute("hidden","hidden");
                            }
                            
                        }, 100);

                    }
                },
                error : function(data){
                    alert('salah');
                }
            }); 
    }

    function lihatpersen(idx){
        var id1=idx;
        
            $.ajax({
                type  : 'get',
                url   : `{{ url('pos01/transaksi/mkeluarlihatpersen//')}}/${id1}`,
                async : false,
                dataType : 'json',	
                
                success : function(data){
                    
                    var i;                
                    var resultData = data.data;	                			
                    for(i=0; i<resultData.length; i++){
                        $('#persenjasa1').val(resultData[i].jasahutangbarang);                         

                        setTimeout(() => {
                            $('#persenjasa1').keydown();
                        }, 500);
                    }
                },
                error : function(data){
                    
                }
            }); 
    }
    
        
    $("#tgltransaksi1").datepicker({
           dateFormat  : "yy-mm-dd",
           changeMonth : true,
           changeYear  : true,         
    });
    
    $('#tgltransaksi1').on('click',function(){
       var x = $('#tgltransaksi1').val().length;
       if(x==10){
           $( "#nomorbuktia1" ).prop( "disabled", false ); 
           $( "#btn_nomorbuktia1" ).prop( "disabled", false );         
       }else{
           $( "#nomorbuktia1" ).prop( "disabled", true ); 
           $( "#btn_nomorbuktia1" ).prop( "disabled", true );         
       }
       setTimeout(() => {
           kirimsyarat();
           setTimeout(() => {
               tampil_data();  
               tampil_tombol();               
           }, 200);
       }, 200);     				
    });
    
    $('#tgltransaksi1').on('change',function(){				
       var x = $('#tgltransaksi1').val().length;
       if(x>=10){
           $( "#nomorbuktia1" ).prop( "disabled", false ); 
           $( "#btn_nomorbuktia1" ).prop( "disabled", false );         
       }else{
           $( "#nomorbuktia1" ).prop( "disabled", true ); 
           $( "#btn_nomorbuktia1" ).prop( "disabled", true );         
       }  	
       setTimeout(() => {
           kirimsyarat();
           setTimeout(() => {
               tampil_data();  
               tampil_tombol();               
           }, 200);
       }, 200);					
    });
    
    $("#btn_nomorbuktia1").on('click',function(){        
       setTimeout(() => {
           kirimsyarat();
           setTimeout(() => {
               tampil_data();  
               tampil_tombol();
               $('#btn_tambah1').removeAttr(disabled);
               $('#btn_posting1').removeAttr(disabled);               
               //refresh barang
                setTimeout(() => {
                    tampil_dataTable();	
                }, 200); 
           }, 200);
       }, 200);
    });
    
    $("#nomorbuktia1").on('keyup',function(){  
       setTimeout(() => {
           kirimsyarat();
           setTimeout(() => {            
               tampil_data();  
               tampil_tombol(); 
               //refresh barang
               setTimeout(() => {
                    tampil_dataTable();    
                }, 200);              
           }, 200);        
       }, 200);
    });
    $("#nomorbuktia1").on('change',function(){  
       setTimeout(() => {
           kirimsyarat();
           setTimeout(() => {            
               tampil_data();  
               tampil_tombol();
               //refresh barang
               setTimeout(() => {
                    barangDatatable = tampil_data_barang();    
                }, 200);               
           }, 200);        
       }, 200);
    });
    
    $("#qty1").on('change',function(){ 
        if($("#qty1").val()==''){
            $("#qty1").val('0');
        }
        var qty = parseFloat($("#qty1").val());

        setTimeout(() => {
            var j1 = $("#qty1").val().replace(/[^,\d]/g, '').toString();
            var k1 = $("#hjs1").val().replace(/[^,\d]/g, '').toString();  
            var j2 = $("#qty1").val().length;
            var k2 = $("#hjs1").val().length;

            if(j2=='0'||k2=='0'){
                
                if(j2=='0'){
                    $("#qty1").val('0');
                }
                
                $("#hppj1").val('0');
                $("#ppn1").val('0');
                $("#diskon1").val('0');
                $("#jumlah1").val('0');
            }else{
                var j3 = parseFloat(j1); 
                var k3 = parseFloat(k1); 
                var l3 = formatAngka(j3*k3,'');
                $("#hppj1").val(l3);
                var ppnpersenx = parseFloat($("#ppnpersen1").val());
                var diskonpersenx = parseFloat($("#diskonpersen1").val());
                var ppnx = Math.round(j3 * k3 * ppnpersenx / 100);
                var diskonx = Math.round(j3 * k3 * diskonpersenx / 100);
                var jumlahx = (j3 * k3) + ppnx - diskonx;
                $("#ppn1").val(formatAngka(ppnx,''));
                $("#diskon1").val(formatAngka(diskonx,''));
                $("#jumlah1").val(formatAngka(jumlahx,''));

            }

        }, 500);

        if(j1==''||j2=='0'){
            document.getElementById("btn_simpan").disabled = true;
        }else{
            document.getElementById("btn_simpan").disabled = false;
        }
    });
    
    $("#qty1").on('keyup',function(){ 
        if($("#qty1").val()==''){
            $("#qty1").val('0');
        }
        var qty = parseFloat($("#qty1").val());

        setTimeout(() => {
            var j1 = $("#qty1").val().replace(/[^,\d]/g, '').toString();
            var k1 = $("#hjs1").val().replace(/[^,\d]/g, '').toString();  
            var j2 = $("#qty1").val().length;
            var k2 = $("#hjs1").val().length;

            if(j2=='0'||k2=='0'){
                
                if(j2=='0'){
                    $("#qty1").val('0');
                }
                
                $("#hppj1").val('0');
                $("#ppn1").val('0');
                $("#diskon1").val('0');
                $("#jumlah1").val('0');
            }else{
                var j3 = parseFloat(j1); 
                var k3 = parseFloat(k1); 
                var l3 = formatAngka(j3*k3,'');
                $("#hppj1").val(l3);
                var ppnpersenx = parseFloat($("#ppnpersen1").val());
                var diskonpersenx = parseFloat($("#diskonpersen1").val());
                var ppnx = Math.round(j3 * k3 * ppnpersenx / 100);
                var diskonx = Math.round(j3 * k3 * diskonpersenx / 100);
                var jumlahx = (j3 * k3) + ppnx - diskonx;
                $("#ppn1").val(formatAngka(ppnx,''));
                $("#diskon1").val(formatAngka(diskonx,''));
                $("#jumlah1").val(formatAngka(jumlahx,''));

            }

        }, 500);

        if(j1==''||j2=='0'){
            document.getElementById("btn_simpan").disabled = true;
        }else{
            document.getElementById("btn_simpan").disabled = false;
        }
    });
    
    $("#hjs1").on('change',function(){  
        var j1 = $("#qty1").val().replace(/[^,\d]/g, '').toString();
        var k1 = $("#hjs1").val().replace(/[^,\d]/g, '').toString();  
        var j2 = $("#qty1").val().length;
        var k2 = $("#hjs1").val().length;
        if(j2=='0'||k2=='0'){
            if(k2=='0'){
                $("#hjs1").val('0');
            }
            $("#hppj1").val('0');
            $("#ppn1").val('0');
            $("#diskon1").val('0');
            $("#jumlah1").val('0');
        }else{
            var j3 = parseFloat(j1); 
            var k3 = parseFloat(k1); 
            var l3 = formatAngka(j3*k3,'');
            $("#hppj1").val(l3);
            var ppnpersenx = parseFloat($("#ppnpersen1").val());
            var diskonpersenx = parseFloat($("#diskonpersen1").val());
            var ppnx = Math.round(j3 * k3 * ppnpersenx / 100);
            var diskonx = Math.round(j3 * k3 * diskonpersenx / 100);
            var jumlahx = (j3 * k3) + ppnx - diskonx;
            $("#ppn1").val(formatAngka(ppnx,''));
            $("#diskon1").val(formatAngka(diskonx,''));
            $("#jumlah1").val(formatAngka(jumlahx,''));

        }

        if(j1==''||j2=='0'){
            document.getElementById("btn_simpan").disabled = true;
        }else{
            document.getElementById("btn_simpan").disabled = false;
        }
    });

    $("#hjs1").on('keyup',function(){  
        var j1 = $("#qty1").val().replace(/[^,\d]/g, '').toString();
        var k1 = $("#hjs1").val().replace(/[^,\d]/g, '').toString();  
        var j2 = $("#qty1").val().length;
        var k2 = $("#hjs1").val().length;
        if(j2=='0'||k2=='0'){
            if(k2=='0'){
                $("#hjs1").val('0');
            }
            $("#hppj1").val('0');
            $("#ppn1").val('0');
            $("#diskon1").val('0');
            $("#jumlah1").val('0');
        }else{
            var j3 = parseFloat(j1); 
            var k3 = parseFloat(k1); 
            var l3 = formatAngka(j3*k3,'');
            $("#hppj1").val(l3);
            var ppnpersenx = parseFloat($("#ppnpersen1").val());
            var diskonpersenx = parseFloat($("#diskonpersen1").val());
            var ppnx = Math.round(j3 * k3 * ppnpersenx / 100);
            var diskonx = Math.round(j3 * k3 * diskonpersenx / 100);
            var jumlahx = (j3 * k3) + ppnx - diskonx;
            $("#ppn1").val(formatAngka(ppnx,''));
            $("#diskon1").val(formatAngka(diskonx,''));
            $("#jumlah1").val(formatAngka(jumlahx,''));

        }

        if(j1==''||j2=='0'){
            document.getElementById("btn_simpan").disabled = true;
        }else{
            document.getElementById("btn_simpan").disabled = false;
        }
    });
    
    $("#ppn1").on('change',function(){  
        var hppjx =parseFloat($("#hppj1").val().replace(/[^,\d]/g, '').toString());
        var diskonx =parseFloat($("#diskon1").val().replace(/[^,\d]/g, '').toString());
        var j1 = $("#ppn1").val().replace(/[^,\d]/g, '').toString();
        var j2 = $("#ppn1").val().length;
        
        if(j1==''||j2=='0'){
            $("#ppn1").val('0');
            var jumlahx = hppjx - diskonx;
        }else{
            var ppnx =parseFloat($("#ppn1").val().replace(/[^,\d]/g, '').toString());
            var jumlahx = hppjx + ppnx - diskonx;
        }
        $("#jumlah1").val(formatAngka(jumlahx,''));

    });
    $("#ppn1").on('keyup',function(){  
        var hppjx =parseFloat($("#hppj1").val().replace(/[^,\d]/g, '').toString());
        var diskonx =parseFloat($("#diskon1").val().replace(/[^,\d]/g, '').toString());
        var j1 = $("#ppn1").val().replace(/[^,\d]/g, '').toString();
        var j2 = $("#ppn1").val().length;
        
        if(j1==''||j2=='0'){
            $("#ppn1").val('0');
            var jumlahx = hppjx - diskonx;
        }else{
            var ppnx =parseFloat($("#ppn1").val().replace(/[^,\d]/g, '').toString());
            var jumlahx = hppjx + ppnx - diskonx;
        }
        $("#jumlah1").val(formatAngka(jumlahx,''));

    });
    $("#diskon1").on('change',function(){  
        var hppjx =parseFloat($("#hppj1").val().replace(/[^,\d]/g, '').toString());
        var ppnx =parseFloat($("#ppn1").val().replace(/[^,\d]/g, '').toString());
        var j1 = $("#diskon1").val().replace(/[^,\d]/g, '').toString();
        var j2 = $("#diskon1").val().length;
        
        if(j1==''||j2=='0'){
            $("#diskon1").val('0');
            var jumlahx = hppjx + ppnx;
        }else{
            var diskonx =parseFloat($("#diskon1").val().replace(/[^,\d]/g, '').toString());
            var jumlahx = hppjx + ppnx - diskonx;
        }
        $("#jumlah1").val(formatAngka(jumlahx,''));

    });

    $("#diskon1").on('keyup',function(){  
        var hppjx =parseFloat($("#hppj1").val().replace(/[^,\d]/g, '').toString());
        var ppnx =parseFloat($("#ppn1").val().replace(/[^,\d]/g, '').toString());
        var j1 = $("#diskon1").val().replace(/[^,\d]/g, '').toString();
        var j2 = $("#diskon1").val().length;
        
        if(j1==''||j2=='0'){
            $("#diskon1").val('0');
            var jumlahx = hppjx + ppnx;
        }else{
            var diskonx =parseFloat($("#diskon1").val().replace(/[^,\d]/g, '').toString());
            var jumlahx = hppjx + ppnx - diskonx;
        }
        $("#jumlah1").val(formatAngka(jumlahx,''));

    });
    
    setTimeout(() => {
        var x = $('#tgltransaksi1').val().length;
        if(x>=10){
            $( "#nomorbuktia1" ).prop( "disabled", false ); 
            $( "#btn_nomorbuktia1" ).prop( "disabled", false );         
        }else{
            $( "#nomorbuktia1" ).prop( "disabled", true ); 
            $( "#btn_nomorbuktia1" ).prop( "disabled", true );         
        } 
    }, 1000);
    

    function tampil_dataTable(){        
        barangDatatable.draw(null, false);               
    }

    function kirimsyarat(){
        var tgltransaksi1=$('#tgltransaksi1').val();
        var nomorbuktia1=$('#nomorbuktia1').val();
    
        let formData = new FormData();
            formData.append('tgltransaksi1', tgltransaksi1);
            formData.append('nomorbuktia1', nomorbuktia1);
    
        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pos01.transaksi.mkeluar_kirimsyarat')}}',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },				 				
            success : function(formData){                                                       
                tampil_dataTable();                
                },
            error : function(formData){                                                       
                alert('error');
                }
        });
    }
    
    //menampilkan combo ruang
    tampil_listruang();
    function tampil_listruang(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.transaksi.mkeluar_listruang')}}',
            
            success: function(data){				    
                $("#idruang1").html(data);

            }
        })                    
    }

    function btn_baru_click(){      
        $('#bodyAdd :input').prop('disabled', false);
        document.getElementById("btn_simpan").style.display='block';        
        document.getElementById("btn_baru").style.display='none';
    }
    
    function btn_simpan_click(){      
        $('#bodyAdd :input').prop('disabled', true);
        document.getElementById("btn_simpan").style.display='none';        
        document.getElementById("btn_baru").style.display='block';
    
        swaltambah();
    }
    
    function btn_edit_click(){      
        $('#bodyAdd :input').prop('disabled', false);
        document.getElementById("btn_simpan").style.display='block';        
        document.getElementById("btn_baru").style.display='none';
    
    }
    
    
    //tambah data -> ok
    $('#btn_baru').on('click',function(){
        btn_baru_click();            
    });
    
    $('#btn_tambah1').on('click',function(){
        let a = $('#tgltransaksi1').val();
        const aArray = a.split("-");
        let thn = aArray[0];
        let bln = aArray[1];
        let tgl = aArray[2];
        let b = thn+bln+tgl;

        let x = $('#nomorbuktia1').val();
        const xArray = x.split(".");
        let w = xArray[0];
        let y = xArray[1];
        let z = xArray[2];
        let j = xArray[3];
        let k1 = y.length;
        let k2 = z.length;
        let k3 = j.length;
        let nama1 = $("#nama1").val(); 
        let nama2 = nama1.trim();

            if(w=='KMM'&&b==z&&k1=='3'&&k3=='4'){
                if(nama2==''){
                    swalpelanggankosong();
                }else{
                    btn_baru_click();        
                    $("#iconx").removeClass("fas fa-edit");
                    $("#iconx").addClass("fas fa-plus-square");
                    $("#modalx").removeClass("modal-content bg-success w3-animate-zoom");
                    $("#modalx").addClass("modal-content bg-primary w3-animate-zoom");
                    document.getElementById("btn_simpan").disabled = false;
                    $('#ModalAdd').modal('show');
                    $('#id1').val('0');
                    $('#judulx').html(' Tambah Data');
                }
            
            }else{
                swalnomorbuktisalah(x);
            }
    }); 
    
    function data_simpan(){
        var judul = $('#idbarang1 option:selected').text();
        var id1=$('#id1').val();			
        var tgltransaksi1=$('#tgltransaksi1').val();
        var nomorbuktia1=$('#nomorbuktia1').val();
        var idruang1=$('#idruang1').val();
        var idbarang1=$('#idbarang1').val();
        var idanggota1=$('#idanggota1').val();
        var qty1=$('#qty1').val().replace(/[^,\d]/g, '').toString();
        var hjs1=$('#hjs1').val().replace(/[^,\d]/g, '').toString();
        var hppj1=$('#hppj1').val().replace(/[^,\d]/g, '').toString();
        var ppn1=$('#ppn1').val().replace(/[^,\d]/g, '').toString();;
        var diskon1=$('#diskon1').val().replace(/[^,\d]/g, '').toString();
        var ppnpersen1=$('#ppnpersen1').val();
        var diskonpersen1=$('#diskonpersen1').val();
        var keterangan1=$('#keterangan1').val();
        
        let formData = new FormData();
            formData.append('id1', id1);
            formData.append('tgltransaksi1', tgltransaksi1);
            formData.append('nomorbuktia1', nomorbuktia1);
            formData.append('idruang1', idruang1);
            formData.append('idbarang1', idbarang1);
            formData.append('idanggota1', idanggota1);
            formData.append('hjs1', hjs1);
            formData.append('qty1', qty1);
            formData.append('hppj1', hppj1);
            formData.append('ppn1', ppn1);
            formData.append('diskon1', diskon1);
            formData.append('ppnpersen1', ppnpersen1);
            formData.append('diskonpersen1', diskonpersen1);
            formData.append('keterangan1', keterangan1);            
          
        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pos01.transaksi.mkeluar_create')}}',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },				 				
            success : function(formData){                                         
                tampil_data();
                    btn_simpan_click();
                    if(id1>0){
                        $('#ModalAdd').modal('hide'); 
                    }
                },
            error : function(formData){                    
                swalgagaltambah(judul); 
                
                }
        });
    }   

    $("#btn_simpan").on('click',function(){
        data_simpan();	
    });

    $("#btn_nomorbuktia1").on('click',function(){
        nomorbukti();
        //refresh barang
        setTimeout(() => {
            tampil_dataTable();	
        }, 200);
    });

    function nomorbukti(){        
        var tgltransaksi1=$('#tgltransaksi1').val();

        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pos01.transaksi.mkeluar_nomorbukti')}}',
            async : false,
            dataType : 'json',
            // data : FormData,
            data : {tgltransaksi1:tgltransaksi1},
            // cache: false,
            // processData: false,
            // contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },				 				
            success : function(data){ 
                var resultData = data.data;	                
                    $('#nomorbuktia1').val(resultData[0].nomorbuktia);                                        
                },
            error : function(data){ 
                
                }

        });
    }

    function nomorposting(){        
        var tglposting1=$('#tglposting5').text();

        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pos01.transaksi.mkeluar_nomorposting')}}',
            async : false,
            dataType : 'json',
            // data : FormData,
            data : {tglposting1:tglposting1},
            // cache: false,
            // processData: false,
            // contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
            				 				
            success : function(data){ 
                var resultData = data.data;	                
                    $('#nomorposting5').text(resultData[0].nomorposting);                                                            
                },
            error : function(data){ 
                
                }
        });
    }

    $('#show_data').on('click','.item_edit',function(){
        $("#iconx").removeClass("fas fa-plus-square");
        $("#iconx").addClass("fas fa-edit");
        $("#modalx").removeClass("modal-content bg-primary w3-animate-zoom");
        $("#modalx").addClass("modal-content bg-success w3-animate-zoom");
        $('#judulx').html(' Edit Data');
        btn_edit_click();

        var id1=$(this).attr('data');
        
        $('#id1').val(id1);
        data_edit(id1);

        $('#ModalAdd').modal('show');         
    });

    $("#btn_update").on('click',function(){	        
        data_simpan();
    });                                         

    function data_edit(idx){
        var id1=idx;			
            
            $.ajax({
                type  : 'get',
                url   : `{{ url('pos01/transaksi/mkeluaredit')}}/${id1}`,
                async : false,
                dataType : 'json',	
                
                success : function(data){
                    var jumlahx;
                    var i;                
                    var resultData = data.data;	                			
                    for(i=0; i<resultData.length; i++){
                        jumlahx = parseFloat(resultData[i].hppj)+parseFloat(resultData[i].ppn)-parseFloat(resultData[i].diskon);
                        $('#idbarang1').val(resultData[i].idmamin);
                        $('#barang1').val($('#idbarang1 option:selected').text());
                        $('#kode1').val(resultData[i].mamin.kode);
                        $('#barcode1').val(resultData[i].mamin.barcode);
                       
                        $('#qty1').val(formatAngka(resultData[i].qty,''));						
                        $('#hjs1').val(formatAngka(resultData[i].hjs,''));						
                        $('#hppj1').val(formatAngka(resultData[i].hppj,''));						
                        $('#ppn1').val(formatAngka(resultData[i].ppn,''));						
                        $('#diskon1').val(formatAngka(resultData[i].diskon,''));						
                        $('#ppnpersen1').val(resultData[i].ppnpersen);
                        $('#diskonpersen1').val(resultData[i].diskonpersen);
                        $('#jumlah1').val(formatAngka(jumlahx,''));					
                        $('#keterangan1').val(resultData[i].keterangan);
                        
                    }
                    
                },
                error : function(data){
                    alert(id1);
                }
            }); 
    }

    function floorn(angka,n){
        var angka1=parseFloat(angka);
        var n1=parseFloat(n);
        var nx=Math.floor(angka1/n1);
        var hasil=nx*n1;
        return n1>1 ? hasil :'0';
    }
    $("#btn_pembayaran1").on('click',function(){ 
       var nomorbuktia1 = $('#nomorbuktia1').val();       
       var a1 = $("#displaysubtotal1").text();
       var b1 = $("#nomorpostingnya1").val();
       if(a1=='0'||a1==''){
            swaldatakosong('- Data belum ada -');
       }else{
            if(b1==''){
                swaldatakosong('- Data belum diposting -');
                // $("#ModalPembayaran").modal('show');
            }else{
                
                setTimeout(() => {
                    displaypembayaran(nomorbuktia1);                    
                }, 100);

                setTimeout(() => {
                    var x =parseFloat($("#idjenispembayaran1").val());
                    if(x=='99'){
                        document.getElementById('kali1').removeAttribute("hidden");
                        document.getElementById('judulkali1').removeAttribute("hidden");
                        document.getElementById('persenjasa1').removeAttribute("hidden");
                        document.getElementById('judulpersenjasa1').removeAttribute("hidden");
                        document.getElementById('nilaihutang1').removeAttribute("hidden");
                        document.getElementById('judulnilaihutang1').removeAttribute("hidden");
                    }else{
                        $("#kali1").val('1');
                        document.getElementById('kali1').setAttribute("hidden","hidden");
                        document.getElementById('judulkali1').setAttribute("hidden","hidden");
                        document.getElementById('persenjasa1').setAttribute("hidden","hidden");
                        document.getElementById('judulpersenjasa1').setAttribute("hidden","hidden");
                        document.getElementById('nilaihutang1').setAttribute("hidden","hidden");
                        document.getElementById('judulnilaihutang1').setAttribute("hidden","hidden");
                    }
                    $("#ModalPembayaran").modal('show');
                }, 100);
            }
       }
       
    });

    $("#bayars1").on('change',function(){
        if($("#bayars1").val()==''){
            $("#bayars1").val('0');
        }
        if($("#vouchers1").val()==''){
            $("#vouchers1").val('0');
        }
        if($("#ambilsavings1").val()==''){
            $("#ambilsavings1").val('0');
        }
        var totals1=parseFloat($('#totals1').val().replace(/[^,\d]/g, '').toString());        
        var bayars1=parseFloat($('#bayars1').val().replace(/[^,\d]/g, '').toString());        
        var vouchers1=parseFloat($('#vouchers1').val().replace(/[^,\d]/g, '').toString());
        var ambilsavings1=parseFloat($('#ambilsavings1').val().replace(/[^,\d]/g, '').toString());
        var kembalix = bayars1 + vouchers1 + ambilsavings1 - totals1;  
        if(kembalix>='0'){
            document.getElementById('kembalis1').setAttribute("style","background-color:white");
            document.getElementById('savings1').setAttribute("style","background-color:white");
            $("#btn_proses").prop("disabled",false);
        }else{
            document.getElementById('kembalis1').setAttribute("style","background-color:red");
            document.getElementById('savings1').setAttribute("style","background-color:red");
            $("#btn_proses").prop("disabled",true);
        } 
        var kembali500 = floorn(kembalix,500);
        
        $('#kembalis1').val(formatAngka(kembali500,''));

        var kembalis1=parseFloat($('#kembalis1').val().replace(/[^,\d]/g, '').toString());        
        
        
    });
    $("#bayars1").on('keydown',function(){
        if($("#bayars1").val()==''){
            $("#bayars1").val('0');
        }
        if($("#vouchers1").val()==''){
            $("#vouchers1").val('0');
        }
        if($("#ambilsavings1").val()==''){
            $("#ambilsavings1").val('0');
        }
        var totals1=parseFloat($('#totals1').val().replace(/[^,\d]/g, '').toString());        
        var bayars1=parseFloat($('#bayars1').val().replace(/[^,\d]/g, '').toString());        
        var vouchers1=parseFloat($('#vouchers1').val().replace(/[^,\d]/g, '').toString());
        var ambilsavings1=parseFloat($('#ambilsavings1').val().replace(/[^,\d]/g, '').toString());
        var kembalix = bayars1 + vouchers1 + ambilsavings1 - totals1; 
        if(kembalix>='0'){
            document.getElementById('kembalis1').setAttribute("style","background-color:white");
            document.getElementById('savings1').setAttribute("style","background-color:white");
            $("#btn_proses").prop("disabled",false);
        }else{
            document.getElementById('kembalis1').setAttribute("style","background-color:red");
            document.getElementById('savings1').setAttribute("style","background-color:red");
            $("#btn_proses").prop("disabled",true);
        }  
        var kembali500 = floorn(kembalix,500);
        var savings1 = kembalix - kembali500;

        $('#kembalis1').val(formatAngka(kembali500,''));
        var kembalis1=parseFloat($('#kembalis1').val().replace(/[^,\d]/g, '').toString()); 

        $('#savings1').val(formatAngka(savings1,''));
        var savings1=parseFloat($('#savings1').val().replace(/[^,\d]/g, '').toString());        
    });

    $("#vouchers1").on('change',function(){
        if($("#bayars1").val()==''){
            $("#bayars1").val('0');
        }
        if($("#vouchers1").val()==''){
            $("#vouchers1").val('0');
        }
        if($("#ambilsavings1").val()==''){
            $("#ambilsavings1").val('0');
        }
        var totals1=parseFloat($('#totals1').val().replace(/[^,\d]/g, '').toString());        
        var bayars1=parseFloat($('#bayars1').val().replace(/[^,\d]/g, '').toString());        
        var vouchers1=parseFloat($('#vouchers1').val().replace(/[^,\d]/g, '').toString());
        var ambilsavings1=parseFloat($('#ambilsavings1').val().replace(/[^,\d]/g, '').toString());
        var kembalix = bayars1 + vouchers1 + ambilsavings1 - totals1;   
        if(kembalix>='0'){
            document.getElementById('kembalis1').setAttribute("style","background-color:white");
            document.getElementById('savings1').setAttribute("style","background-color:white");
            $("#btn_proses").prop("disabled",false);
        }else{
            document.getElementById('kembalis1').setAttribute("style","background-color:red");
            document.getElementById('savings1').setAttribute("style","background-color:red");
            $("#btn_proses").prop("disabled",true);
        }    
        var kembali500 = floorn(kembalix,500);
        var savings1 = kembalix - kembali500;

        $('#kembalis1').val(formatAngka(kembali500,''));
        var kembalis1=parseFloat($('#kembalis1').val().replace(/[^,\d]/g, '').toString()); 

        $('#savings1').val(formatAngka(savings1,''));
        var savings1=parseFloat($('#savings1').val().replace(/[^,\d]/g, '').toString());        
        
    });
    $("#vouchers1").on('keydown',function(){
        if($("#bayars1").val()==''){
            $("#bayars1").val('0');
        }
        if($("#vouchers1").val()==''){
            $("#vouchers1").val('0');
        }
        if($("#ambilsavings1").val()==''){
            $("#ambilsavings1").val('0');
        }
        var totals1=parseFloat($('#totals1').val().replace(/[^,\d]/g, '').toString());        
        var bayars1=parseFloat($('#bayars1').val().replace(/[^,\d]/g, '').toString());        
        var vouchers1=parseFloat($('#vouchers1').val().replace(/[^,\d]/g, '').toString());
        var ambilsavings1=parseFloat($('#ambilsavings1').val().replace(/[^,\d]/g, '').toString());
        var kembalix = bayars1 + vouchers1 + ambilsavings1 - totals1;   
        if(kembalix>='0'){
            document.getElementById('kembalis1').setAttribute("style","background-color:white");
            document.getElementById('savings1').setAttribute("style","background-color:white");
            $("#btn_proses").prop("disabled",false);
        }else{
            document.getElementById('kembalis1').setAttribute("style","background-color:red");
            document.getElementById('savings1').setAttribute("style","background-color:red");
            $("#btn_proses").prop("disabled",true);
        }  
        var kembali500 = floorn(kembalix,500);
        var savings1 = kembalix - kembali500;

        $('#kembalis1').val(formatAngka(kembali500,''));
        var kembalis1=parseFloat($('#kembalis1').val().replace(/[^,\d]/g, '').toString()); 

        $('#savings1').val(formatAngka(savings1,''));
        var savings1=parseFloat($('#savings1').val().replace(/[^,\d]/g, '').toString());        
    });

    $("#ambilsavings1").on('change',function(){
        
        var saldo1=parseFloat($('#saldo1').val().replace(/[^,\d]/g, '').toString());
        
        if($("#bayars1").val()==''){
            $("#bayars1").val('0');
        }
        if($("#vouchers1").val()==''){
            $("#vouchers1").val('0');
        }
        if($("#ambilsavings1").val()==''){
            $("#ambilsavings1").val('0');
        }
        var totals1=parseFloat($('#totals1').val().replace(/[^,\d]/g, '').toString());        
        var bayars1=parseFloat($('#bayars1').val().replace(/[^,\d]/g, '').toString());        
        var vouchers1=parseFloat($('#vouchers1').val().replace(/[^,\d]/g, '').toString());
        var ambilsavings1=parseFloat($('#ambilsavings1').val().replace(/[^,\d]/g, '').toString());
        if(ambilsavings1>saldo1){
            $("#ambilsavings1").val(saldo1);
        }


        var kembalix = bayars1 + vouchers1 + ambilsavings1 - totals1;   
        if(kembalix>='0'){
            document.getElementById('kembalis1').setAttribute("style","background-color:white");
            document.getElementById('savings1').setAttribute("style","background-color:white");
            $("#btn_proses").prop("disabled",false);
        }else{
            document.getElementById('kembalis1').setAttribute("style","background-color:red");
            document.getElementById('savings1').setAttribute("style","background-color:red");
            $("#btn_proses").prop("disabled",true);
        }  
        var kembali500 = floorn(kembalix,500);
        var savings1 = kembalix - kembali500;

        $('#kembalis1').val(formatAngka(kembali500,''));
        var kembalis1=parseFloat($('#kembalis1').val().replace(/[^,\d]/g, '').toString()); 

        $('#savings1').val(formatAngka(savings1,''));
        var savings1=parseFloat($('#savings1').val().replace(/[^,\d]/g, '').toString());        
    });
    $("#ambilsavings1").on('keydown',function(){
        var saldo1=parseFloat($('#saldo1').val().replace(/[^,\d]/g, '').toString());
        
        if($("#bayars1").val()==''){
            $("#bayars1").val('0');
        }
        if($("#vouchers1").val()==''){
            $("#vouchers1").val('0');
        }
        if($("#ambilsavings1").val()==''){
            $("#ambilsavings1").val('0');
        }
        var totals1=parseFloat($('#totals1').val().replace(/[^,\d]/g, '').toString());        
        var bayars1=parseFloat($('#bayars1').val().replace(/[^,\d]/g, '').toString());        
        var vouchers1=parseFloat($('#vouchers1').val().replace(/[^,\d]/g, '').toString());
        var ambilsavings1=parseFloat($('#ambilsavings1').val().replace(/[^,\d]/g, '').toString());
        if(ambilsavings1>saldo1){
            $("#ambilsavings1").val(saldo1);
        }
        var kembalix = bayars1 + vouchers1 + ambilsavings1 - totals1;   
        if(kembalix>='0'){
            document.getElementById('kembalis1').setAttribute("style","background-color:white");
            document.getElementById('savings1').setAttribute("style","background-color:white");
            $("#btn_proses").prop("disabled",false);
        }else{
            document.getElementById('kembalis1').setAttribute("style","background-color:red");
            document.getElementById('savings1').setAttribute("style","background-color:red");
            $("#btn_proses").prop("disabled",true);
        }  
        var kembali500 = floorn(kembalix,500);
        var savings1 = kembalix - kembali500;

        $('#kembalis1').val(formatAngka(kembali500,''));
        var kembalis1=parseFloat($('#kembalis1').val().replace(/[^,\d]/g, '').toString()); 

        $('#savings1').val(formatAngka(savings1,''));
        var savings1=parseFloat($('#savings1').val().replace(/[^,\d]/g, '').toString());        
    });

    $("#kali1").on('change',function(){
        var kalix = parseFloat($("#kali1").val());
        if($("#kali1").val()==''){
            $("#kali1").val('0');
        }
        if(kalix>'60'){
            $("#kali1").val('60');
        }
        lihatpersen(kalix);
    });
    $("#kali1").on('keydown',function(){
        var kalix = parseFloat($("#kali1").val());
        if($("#kali1").val()==''){
            $("#kali1").val('0');
        }
        if(kalix>'60'){
            $("#kali1").val('60');
        }
        lihatpersen(kalix);
    });
    
    $("#idbarang1").on('change',function(){
        setTimeout(() => {
            ambil_mamin($("#idbarang1").val());
        }, 100);
    });

    function ambil_mamin(idx){
        var id1=idx;			
            $.ajax({
		        type  : 'get',
		        url   : `{{ url('pos01/master/maminedit')}}/${id1}`,
		        async : false,
		        dataType : 'json',	
				
		        success : function(data){
                    var i;                
                    var resultData = data.data;	                			
                    for(i=0; i<resultData.length; i++){
                        
                        $('#kode1').val(resultData[i].kode);
                        $('#barcode1').val(resultData[i].barcode);
                        $('#hjs1').val(formatAngka(resultData[i].hjs,''));;
                        $('#ppnpersen1').val(resultData[i].ppnjual);
                        $('#diskonpersen1').val(resultData[i].diskonjual);
                        setTimeout(() => {
                            $('#hjs1').change();
                        }, 100);
                        
                    }
                    
		        },
                error : function(data){
                    alert(id1);
                }
		    }); 
    }

    
    $("#idjenispembayaran1").on('change',function(){
        setTimeout(() => {
            var x =parseFloat($("#idjenispembayaran1").val());
            if(x=='99'){
                document.getElementById('kali1').removeAttribute("hidden");
                document.getElementById('judulkali1').removeAttribute("hidden");
                document.getElementById('persenjasa1').removeAttribute("hidden");
                document.getElementById('judulpersenjasa1').removeAttribute("hidden");
                document.getElementById('nilaihutang1').removeAttribute("hidden");
                document.getElementById('judulnilaihutang1').removeAttribute("hidden");
            }else{
                $("#kali1").val('1');
                document.getElementById('kali1').setAttribute("hidden","hidden");
                document.getElementById('judulkali1').setAttribute("hidden","hidden");
                document.getElementById('persenjasa1').setAttribute("hidden","hidden");
                document.getElementById('judulpersenjasa1').setAttribute("hidden","hidden");
                document.getElementById('nilaihutang1').setAttribute("hidden","hidden");
                document.getElementById('judulnilaihutang1').setAttribute("hidden","hidden");
            }
            
        }, 500);
        
    });

    $("#persenjasa1").on('change',function(){
        var totals1=parseFloat($('#totals1').val().replace(/[^,\d]/g, '').toString());

        if($("#persenjasa1").val()==''){
            $("#persenjasa1").val('0');
            var x = parseFloat($("#persenjasa1").val())
        }else{
            var x = parseFloat($("#persenjasa1").val())
        }

        var totaljasa1=totals1*parseFloat(x)/100;
        var nilaihutangx = totals1 + totaljasa1;
        var nilaihutang = formatAngka(Math.round(nilaihutangx));

        $("#nilaihutang1").val(nilaihutang);
        
    });
    $("#persenjasa1").on('keydown',function(){
        var totals1=parseFloat($('#totals1').val().replace(/[^,\d]/g, '').toString());

        if($("#persenjasa1").val()==''){
            $("#persenjasa1").val('0');
            var x = parseFloat($("#persenjasa1").val())
        }else{
            var x = parseFloat($("#persenjasa1").val())
        }

        var totaljasa1=totals1*parseFloat(x)/100;
        var nilaihutangx = totals1 + totaljasa1;
        var nilaihutang = formatAngka(Math.round(nilaihutangx));

        $("#nilaihutang1").val(nilaihutang);
        
    });
    
    $("#btn_cetak").on('click',function(){        
       var cek = $("#jmls1").val();
       if(cek=='0'||cek==''){
            modal_proses()
       }else{
            alert('cetak');
       }
       setTimeout(() => {
           $("#ModalPembayaran").modal('hide');
       }, 100);
    });
    //modal sweet art posting		
    function modal_posting(){
        Swal.fire({
        title: 'Are you sure posting',
        text: $('#nomorposting5').text(),
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: "Yes, posting !",
        cancelButtonText: "No, cancel !",
        }).then((result) => {
            if (result.isConfirmed) {
                data_posting();                           
            }
        })
    } 
    
    function data_posting(){
        var tgltransaksi1=$('#tgltransaksi1').val();
        var nomorbuktia1=$('#nomorbuktia1').val();
        var tglposting1=$('#tglposting5').text();
        var nomorposting1=$('#nomorposting5').text();
    
        let formData = new FormData();
            formData.append('tgltransaksi1', tgltransaksi1);
            formData.append('nomorbuktia1', nomorbuktia1);
            formData.append('tglposting1', tglposting1);
            formData.append('nomorposting1', nomorposting1);
          
        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pos01.transaksi.mkeluar_posting')}}',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },				 				
            success : function(formData){                                         
                    tampil_data();
                    swalposting(nomorposting1);
                    $('#ModalPosting').modal('hide');
                },
            error : function(formData){ 
                    swalgagalposting(nomorposting1);                                                        
                }
        });
    }

    $('#show_data').on('click','.item_hapus',function(){
        var id3=$(this).attr('data');
        var data3b=$(this).attr('data2');
        var data3c=$(this).attr('data3');
        var data3d=$(this).attr('data4');
        
        $('#id3').val(id3);
        $('#data3a').val(data3b);
        $('#data3b').val(data3c);
        $('#data3c').val(data3d);
        modal_hapus();
    });

    //modal sweet art hapus		
    function modal_hapus(){
        Swal.fire({
        title: 'Are you sure delete?',
        text: $('#data3b').val(),
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

    function modal_proses(){
        Swal.fire({
        title: 'Are you sure process?',
        text: 'Data not yet processed',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: "Yes, process !",
		cancelButtonText: "No, cancel !",
        }).then((result) => {
            if (result.isConfirmed) {
                data_proses();
            }
        })
    } 

    function data_hapus(){			
        var id3=$('#id3').val();       
        var data3b=$('#data3b').val();
        
        $.ajax({
            type  : 'get',
            url   : '{{url('pos01/transaksi/mkeluardestroy')}}/'+id3,
            async : false,
            dataType : 'json',					
            success : function(data){
                tampil_data();
                swalhapus(data3b); 
            },
            error : function(data){
                swalgagalhapus(data3b); 
            }
        }); 

    }

    $('#btn_proses').on('click',function(){
        data_proses();
    });

    function data_proses(){       
        var tgl1 = $('#tglposting5').text();

        var subtotals1 = $('#subtotals1').val().replace(/[^,\d]/g, '').toString();
        var ppns1 = $('#ppns1').val().replace(/[^,\d]/g, '').toString();
        var diskons1 = $('#diskons1').val().replace(/[^,\d]/g, '').toString();
        var totals1 = $('#totals1').val().replace(/[^,\d]/g, '').toString();
        var bayars1 = $('#bayars1').val().replace(/[^,\d]/g, '').toString();
        var vouchers1 = $('#vouchers1').val().replace(/[^,\d]/g, '').toString();
        var ambilsavings1 = $('#ambilsavings1').val().replace(/[^,\d]/g, '').toString();
        var kembalis1 = $('#kembalis1').val().replace(/[^,\d]/g, '').toString();
        var savings1 = $('#savings1').val().replace(/[^,\d]/g, '').toString();
        var idjenispembayaran1 = $('#idjenispembayaran1').val();
        var nomorpostingnya1 = $('#nomorpostingnya1').val();
        var tglpostingnya1 = $('#tglpostingnya1').val();
        var nomorbuktia1 = $('#nomorbuktia1').val();
        var tgltransaksi1 = $('#tgltransaksi1').val();
        var idanggota1 = $('#idanggota1').val();
        var kali1 = $('#kali1').val();
        var persenjasa1 = $('#persenjasa1').val();
        var nilaihutang1 = $('#nilaihutang1').val().replace(/[^,\d]/g, '').toString();
                
        let formData = new FormData();
        
            formData.append('subtotals1', subtotals1);
            formData.append('ppns1', ppns1);
            formData.append('diskons1', diskons1);
            formData.append('totals1', totals1);
            formData.append('bayars1', bayars1);
            formData.append('vouchers1', vouchers1);
            formData.append('ambilsavings1', ambilsavings1);
            formData.append('kembalis1', kembalis1);
            formData.append('savings1', savings1);
            formData.append('idjenispembayaran1', idjenispembayaran1);
            formData.append('nomorpostingnya1', nomorpostingnya1);
            formData.append('tglpostingnya1', tglpostingnya1);
            formData.append('nomorbuktia1', nomorbuktia1);
            formData.append('tgltransaksi1', tgltransaksi1);
            formData.append('idanggota1', idanggota1);                     
            formData.append('kali1', kali1);                     
            formData.append('persenjasa1', persenjasa1);                     
            formData.append('nilaihutang1', nilaihutang1);                     
          
        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pos01.transaksi.mkeluar_proses')}}',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },				 				
            success : function(formData){   
                    if(tgl1==tgltransaksi1){          
                        swalproses('');
                    }else{
                        swalproses2('');
                    }
                },
            error : function(formData){                    
                swalgagaltambah(idanggota1); 
                
                }
        });

        setTimeout(() => {
            displaypembayaran(nomorbuktia1);
        }, 500);
        
    }

    function swalproses(x){
        Swal.fire({
            icon: 'success',
            title: 'Process successfully',
            text: x,
            timer:1000
        })
    }

    function swalproses2(x){
        Swal.fire({
            icon: 'info',
            title: 'Oops...transaction date expired',
            text: x,
            timer:1000
        })
    }

    function swalposting(x){
        Swal.fire({
            icon: 'success',
            title: 'Posting successfully',
            text: x,
            timer:1000
        })
    }

    function swalgagalposting(x){
        Swal.fire({
            icon: 'error',
            title: 'Oops...failed to posting record',
            text: x,
            timer:1000
        })
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
    
    function swalnomorbuktisalah(x){
        Swal.fire({
            icon: 'error',
            title: 'Oops...Nomor Bukti "' + x + '" ilegal !',
            html: '<p>Klik Generate Nomor Bukti ( <i style="font-size:24" class="fa">&#xf013;</i> )</p>',
            timer:5000
        })
    }

    function swalpelanggankosong(){
        Swal.fire({
            icon: 'error',
            title: 'Oops...Nama pelanggan belum dipilih',
            html: '<p>Isi kolom search atau Klik <i style="font-size:24" class="fas">&#xf002;</i></p>',
            timer:5000
        })
    }

    function swaldatakosong(x){
        Swal.fire({
            icon: 'info',
            title: 'Maaf, pembayaran tidak  bisa dilakukan...',
            text: x,
            timer:5000
        })
    }

});

</script>	



@endsection