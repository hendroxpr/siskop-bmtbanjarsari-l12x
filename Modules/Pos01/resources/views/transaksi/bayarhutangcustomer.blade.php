@extends('admin.layouts.main')

@section('contents')

@php
    $tgltransaksi = session('tgltransaksi1');   
    if($tgltransaksi==''){
        $tgltransaksi='';  
    }
    $nomorbukti = session()->get('nomorbukti1');
    if($nomorbukti==''){
        $nomorbukti='';  
    }
    $idanggota = session()->get('idanggota1');
    if($idanggota==''){
        $idanggota='0';  
    }
    $saldo = session()->get('saldo1');
    if($saldo==''){
        $saldo='0';  
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
</style>

    <div class="box-header mb-3">  
        <div class="row">
            
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-4  text-right">
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
                        <h6 class="mt-2  text-right">Nomor Bukti</h6>
                    </div>
                    <div class="col-md-7 input-group">
                        <input name="nomorbukti1" id="nomorbukti1" class="form-control w3-input w3-border rounded-0" type="search" placeholder="BHC.001.20230131.001" disabled value="{{ $nomorbukti }}">                        
                        <div class="input-group-append">
                          <button id="btn_nomorbukti1" name="btn_nomorbukti1" type="button" style="border-radius:0px; border:none;" title="Generate Nomor Bukti-a" disabled><i style="font-size:24" class="fa">&#xf013;</i></button>
                        </div>
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
                        <select name="idanggota1" id="idanggota1" style="display: none;" value="{{ $idanggota }}"></select> 
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
                        <h6 class="mt-2">Saldo Hutang</h6>
                    </div>
                    <div class="col-md-7">
                        <input name="saldo1" id="saldo1" class="w3-input w3-border" maxlength="50" type="text" placeholder="Saldo" value="{{ $saldo }}"  readonly>                       
                        <input name="saldosaving1" id="saldosaving1" class="w3-input w3-border" maxlength="50" type="hidden" placeholder="Saldo" value="{{ old('saldosaving1') }}"  readonly>                       
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
							<th style="width:50px">Nomor Hutang</th>
							<th style="width:50px">Tgl.Hutang</th>
							<th style="width:50px">Nilai Hutang</th>														
							<th style="width:50px">Sudah Bayar</th>														
							<th style="width:50px">Bayar</th>
							<th style="width:50px">Saldo</th>
							<th style="width:50px">Angs.ke</th>
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
                            <td style="text-align: right"><b><span id='totalsaldo1' name='totalsaldo1'></span></b></td>
                            <td></td>                            
                            <td></td>
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
        <div class="modal-dialog" style="width: 400px;">  <!-- modal-(sm, lg, xl) ukuran lebar modal -->
            <div id="modalx" nama="modalx"  class="modal-content bg-primary w3-animate-zoom">
            
                <div class="modal-header">
                <h3 class="modal-title"><i id="iconx" name="iconx" class="fas fa-plus-square"></i><b><span id="judulx" name="judulx"> Tambah Data</span></b></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="form-group">
                    <form class="form-horizontal" id="formSimpan" nama="formSimpan" action="" method="">
                        @csrf	
                        <div class="modal-body" id="bodyAdd" name="bodyAdd">                        

                            <div class="row">
                                <div class="col-md-4" align="right">									
                                    <h6 class="mt-2">Nomor Hutang</h6>
                                </div>
                                <div class="col-md-8 input-group">
                                    <select  name="idhutang1" id="idhutang1" class=" form-control w3-input w3-border" style="border-radius:0px; border:none; display:block;"></select>
                                    <div class="input-group-append">
                                      <button id="btn_carihutang1" name="btn_carihutang1" type="button" style="border-radius:0px; border:none;"><i style="font-size:24" class="fas">&#xf002;</i></button>
                                    </div>
                                    <select  name="idhutangx1" id="idhutangx1" class="" style="border-radius:0px; border:none; display:none;"></select>
                                    <input name="cek1" id="cek1" class="" type="hidden">                                
                                    <input name="id1" id="id1" class="" type="hidden"> 
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mt-1" align="right">									
                                    <h6 class="mt-2">Tgl.Hutang</h6>
                                </div>
                                <div class="col-md-8">                                
                                    <input name="tglhutang1" id="tglhutang1" class="w3-input w3-border" type="text" placeholder="tgl hutang" readonly>
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-4 mt-1" align="right">									
                                    <h6 class="mt-2">Nilai Hutang</h6>
                                </div>
                                <div class="col-md-8">                                
                                    <input name="nilaihutang1" id="nilaihutang1" class="w3-input w3-border text-right" type="search" placeholder="" value="{{ old('nilaihutang1') }}"  onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"  readonly>
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-4 mt-1" align="right">									
                                    <h6 class="mt-2">Sudah Bayar</h6>
                                </div>
                                <div class="col-md-8">                                
                                    <input name="sudahbayar1" id="sudahbayar1" class="w3-input w3-border text-right" type="search" placeholder="" value="{{ old('sudahbayar1') }}"  onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"  readonly>
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-4 mt-1" align="right">									
                                    <h6 class="mt-2">Saldo Hutang</h6>
                                </div>
                                <div class="col-md-8">                                
                                    <input name="saldohutang1" id="saldohutang1" class="w3-input w3-border text-right" type="search" placeholder="" value="{{ old('saldohutang1') }}"  onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"  readonly>
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-4 mt-1" align="right">									
                                    <h6 class="mt-2">Bayar</h6>
                                </div>
                                <div class="col-md-8">                                
                                    <input name="bayar1" id="bayar1" class="w3-input w3-border text-right" style=" background-color: aqua;" type="search" placeholder="" value="{{ old('bayar1') }}"  onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-4 mt-1" align="right">									
                                    <h6 class="mt-2">Sisa Saldo</h6>
                                </div>
                                <div class="col-md-8">                                
                                    <input name="sisasaldo1" id="sisasaldo1" class="w3-input w3-border text-right" type="search" placeholder="" value="0"  onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);"  readonly>
                                </div>								  
                            </div>

                            <div class="row">
                                <div class="col-md-4" align="right">										
                                    <h6 class="mt-2">Keterangan</h6>
                                </div>
                                <div class="col-md-8">                                
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

    <!-- ModalCariHutang modal fade-->
	<div class="modal fade" id="ModalCariHutang"  data-backdrop="static">
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
								<table id="hutang" class="table table-bordered table-striped table-hover" style="width: 100%">
                                    <thead>
                                        <tr style="align-content: center">
                                            <th style="width:10px;">#</th>
                                            <th style="width:50px">Tanggal Hutang</th>
                                            <th style="width:50px">Nomor Hutang</th>
                                            <th style="width:20px">Angs (X)</th>
                                            <th style="width:20px">Nilai Angsuran</th>
                                            <th style="width:200px">Nilai Hutang</th>
                                            <th style="width:20px">Sudah Bayar</th>
                                            <th style="width:20px">Saldo</th>
                                        </tr>
                                    </thead>
                                    <tfoot id="show_footer">
                                        
                                    </tfoot>
                                    <tbody id="show_hutang">
                                    
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
	<!-- end ModalCariHutang -->

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
                                            <th style="width:50px">Saldo</th>
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
                    <h3 class="modal-title"><i style="font-size:18" class="fa">&#xf093;</i><b> Posting Bayar Hutang</b></h3>
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
                                <h4 class="mt-2">Nomor Bukti</h4>
                            </div>
                            <div class="col-md-1" align="center">                                
                                <h4 class="mt-2">:</h4>
                            </div>								  
                            <div class="col-md-6" style="padding-right: 0px; padding-left: 0px;">                                
                                <h4 class="mt-2"><span  id="nomorbukti5" name="nomorbukti5"></span></h4>
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
                    <button id="btn_posting5" name="btn_posting5" type="button" class="w3-button w3-border w3-border-white"><i style="font-size:18px" class="fa">&#xf093;</i> Posting</button>
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
                                    <input name="ppns1" id="ppns1" class="w3-input w3-border text-right" style=" background-color: aqua;" type="search" placeholder="" value="{{ old('ppns1') }}"  onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                </div>  								  
                            </div> 			
                                         
                            <div class="row">
                                <div class="col-md-5 mt-2" align="right" style="padding-right: 10px; padding-left: 0px;">										
                                    <h4 class="mt-2">Diskon</h4>
                                </div>							  
                                <div class="col-md-7" style="padding-right: 20px; padding-left: 0px;">
                                    <input name="diskons1" id="diskons1" class="w3-input w3-border text-right" style=" background-color: aqua;" type="search" placeholder="" value="{{ old('diskons1') }}"  onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
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
                                    <input name="jmls1" id="jmls1" class="" type="hidden">
                                    
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

   var hutangDatatable;
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

    
    //menampilkan combo hutang
    function tampil_listhutang(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.transaksi.bayarhutangcustomer_listhutang')}}',
            
            success: function(data){				    
                $("#idhutang1").html(data);                
            }
        })                    
    }
    //menampilkan combo hutangx
    function tampil_listhutangx(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.transaksi.bayarhutangcustomer_listhutangx')}}',
            
            success: function(data){				    
                $("#idhutangx1").html(data);                
            }
        })                    
    }

    tampil_listanggota();
    //menampilkan combo anggota
    function tampil_listanggota(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.transaksi.bayarhutangcustomer_listanggota')}}',
            
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
            url   : '{{route('pos01.transaksi.bayarhutangcustomer_listjenispembayaran')}}',
            
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
            url   : '{{route('pos01.transaksi.bayarhutangcustomer_show')}}',
            async : false,
            dataType : 'json',
            				 				
            success : function(data){
                var nomorpostingx;
                var idanggotax;
                var tglposting;
                var sudahbayarx;
                var jumlahx = 0;
                var html = '';
                var aksi;
                var i;                                
                var resultData = data.data;	                			
                for(i=0; i<resultData.length; i++){
                    nomorpostingx = resultData[i].nomorposting;
                    idanggotax = resultData[i].idanggota;
                     
                    jumlahx = jumlahx + parseFloat(resultData[i].bayar);

                    tglposting = (resultData[i].nomorposting ? resultData[i].nomorposting : '').length;

                    if(tglposting=='0'){
                        html += '<tr>';
                        aksi = '<td style="text-align:center;">' +
                                    '<a href="javascript:;" title="Edit Data"  class="btn btn-success btn-xs item_edit" data="'+resultData[i].id+'" data2="'+resultData[i].hutang.nomorstatus+'" data3="'+resultData[i].hutang.tglstatus+'" data4="'+resultData[i].keterangan+'"><i style="font-size:18px;" class="fa" >&#xf044;</i></a>'+ ' ' +
                                    '<a href="javascript:;" title="Hapus Data"  class="btn btn-danger btn-xs item_hapus" ' +
                                        'data="'+resultData[i].id+'" data2="'+resultData[i].hutang.nomorstatus+'" data3="'+resultData[i].hutang.tglstatus+'" data4="'+resultData[i].keterangan+'" ' +
                                        '><i style="font-size:18px" class="fa">&#xf00d;</i></a>' +   
                                '</td>';
                        $('#btn_tambah1').removeAttr('disabled');
                        $('#btn_posting1').removeAttr('disabled');
                        
                    }else{
                        html += '<tr style="background:goldenrod">';
                        aksi = '<td style="text-align:center;">' +
                                '<a href="javascript:;" title="Edit Data"  class="btn btn-success btn-xs item_edit" data="'+resultData[i].id+'" data2="'+resultData[i].hutang.nomorstatus+'" data3="'+resultData[i].hutang.tglstatus+'" data4="'+resultData[i].keterangan+'"><i style="font-size:18px;" class="fa" >&#xf044;</i></a>'+ ' ' +
                                    '<a href="javascript:;" title="Hapus Data"  class="btn btn-danger btn-xs item_hapus" ' +
                                        'data="'+resultData[i].id+'" data2="'+resultData[i].hutang.nomorstatus+'" data3="'+resultData[i].hutang.tglstatus+'" data4="'+resultData[i].keterangan+'" ' +
                                        '><i style="font-size:18px" class="fa">&#xf00d;</i></a>' +   
                                '</td>';
                        $('#btn_tambah1').attr('disabled', '');
                        $('#btn_posting1').attr('disabled', '');                                                        
                    }

                    html += 
                                '<td align="center">'+ (i+1) +'</td>'+                            								
                                '<td>'+resultData[i].hutang.nomorstatus+'</td>'+
                                '<td>'+resultData[i].hutang.tglstatus+'</td>'+
                                '<td align="right">'+ formatAngka(resultData[i].hutang.asli)+'</td>'+
                                '<td align="right">'+ formatAngka(resultData[i].hutang.asli - resultData[i].bayar - resultData[i].akhir)+'</td>'+
                                '<td align="right">'+ formatAngka(resultData[i].bayar)+'</td>'+
                                '<td align="right">'+ formatAngka(resultData[i].akhir)+'</td>'+
                                '<td align="center">'+ resultData[i].angsuranke +'</td>'+
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
                $('#totalsaldo1').text(formatAngka(jumlahx,''));
                $('#totalnilai5').text(formatAngka(jumlahx,''));
                $('#displaysubtotal1').text($('#totalsaldo1').text());
                $('#subtotals1').val(formatAngka(jumlahx,''));

                $('#totals1').val(formatAngka(jumlahx,''));
                if(i=='0'){
                    $('#btn_tambah1').removeAttr('disabled');
                    $('#btn_posting1').removeAttr('disabled');
                    $( "#cariid1x" ).prop( "disabled", false ); 
                    $( "#btn_carianggota1" ).prop( "disabled", false );
                    
                }else{
                    $( "#cariid1x" ).prop( "disabled", true ); 
                    $( "#btn_carianggota1" ).prop( "disabled", true );
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
                $('#nomorbukti5').text($('#nomorbukti1').val());        		
                $('#ModalPosting').modal('show');						                
            }, 10);
        }, 200);
    });
    
    $('#btn_posting5').on('click',function(){
        modal_posting();        
    });
    
    $('#btn_carihutang1').on('click',function(){        		
        $('#ModalCariHutang').modal('show');						
    });
    $('#btn_carianggota1').on('click',function(){        		
        $('#ModalCariAnggota').modal('show');						
    });
    
    setTimeout(() => {
         hutangDatatable = tampil_data_hutang();    
         anggotaDatatable = tampil_data_anggota();    
    }, 1000);

    function tampil_data_hutang(){
       let i = 1;	
       return $('#hutang').DataTable({
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
           ajax   : `{{route('pos01.transaksi.bayarhutangcustomer_showhutang')}}`,
           columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                   orderable: false, 
                   searchable: false },
                { data: 'tglstatus', name: 'tglstatus', className: 'dt-center' },
                { data: 'nomorstatus', name: 'nomorstatus', className: 'dt-center' },
                { data: 'xangsuran', name: 'xangsuran', className: 'dt-center' },
                { data: 'nilaiangsuran', name: 'nilaiangsuran', className: 'dt-right' },
                { data: 'nilaihutang', name: 'nilaihutang', className: 'dt-right' },
                { data: 'sudahbayar', name: 'sudahbayar', className: 'dt-right' },
                { data: 'saldo', name: 'saldo', className: 'dt-right' },
               
           ]
       });
    }

    $('#show_hutang').on('click','.item_nomorstatus',function(){ 
        ambilcari(this);        
    });
    $('#show_hutang').on('click','.item_tglstatus',function(){        
        ambilcari(this);        
    });
    
    function ambilcari(t){
        var id1 = $(this).attr('data');
        var idhutang1 = $(t).attr('data1');
        $('#idhutang1').val(idhutang1);
        displayhutang(idhutang1);
        $('#ModalCariHutang').modal('hide');
    }

    function tampil_data_anggota(){
       let i = 1;	
       return $('#anggota').DataTable({
           responsive : true,
           retrieve: true,
           autoWidth : true,
           buttons : [ {extend: 'colvis', postfixButtons: [ 'colvisRestore' ] }, {extend:'copy'}, {extend:'csv'}, {extend: 'pdf', orientation: 'portrait', pageSize: 'A4', title:'{{ $caption }}'}, {extend: 'excel', title: '{{ $caption }}'}, {extend:'print', orientation: 'portrait', pageSize: 'A4', title: '{{ $caption }}'}, ],        
           dom: 'lfrtip',
        //    dom: 'lBfrtip',
           lengthMenu: [
               [ 10, 25, 50, 100, 500, 1000, 5000, -1 ],
               [ '10', '25', '50', '100', '500','1000','5000', 'All' ]
           ],
           processing: true,
           serverSide: true,
           ajax   : `{{route('pos01.transaksi.bayarhutangcustomer_showanggota')}}`,
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
                { data: 'saldo', name: 'saldo', className: 'dt-right'},
            
               
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
        var saldo1 = aArray[5];

        $('#ecard1').val(ecard1);
        $('#nia1').val(nia1);
        $('#ktp1').val(ktp1);
        $('#nama1').val(nama1);
        $('#lembaga1').val(lembaga1);
        $('#saldo1').val(formatAngka(saldo1,''));

        $('#cariid1x').val('');

        $('#ModalCariAnggota').modal('hide');
        setTimeout(() => {
            kirimsyarat();
        }, 1000);
    }

    $('#cariid1x').keypress(function (e) {        
        $('#ecard1').val('');
        $('#nia1').val('');
        $('#ktp1').val('');
        $('#nama1').val('');
        $('#lembaga1').val('');
        $('#saldo1').val('0');
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
            url    : '{{route('pos01.transaksi.bayarhutangcustomer_cariid')}}',
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
                        // $('#ecard1').val(resultData[i].ecard);
                        // $('#nia1').val(resultData[i].nia);
                        // $('#ktp1').val(resultData[i].ktp);
                        // $('#nama1').val(resultData[i].nama);
                        // $('#lembaga1').val(resultData[i].lembaga.lembaga);
                        setTimeout(() => {                            
                            var a = $('#idanggota1 option:selected').text();
                            const aArray = a.split("|");
                            var ecard1 = aArray[0];
                            var nia1 = aArray[1];
                            var ktp1 = aArray[2];
                            var nama1 = aArray[3];
                            var lembaga1 = aArray[4];
                            var saldo1 = aArray[5];
                            $('#ecard1').val(ecard1);
                            $('#nia1').val(nia1);
                            $('#ktp1').val(ktp1);
                            $('#nama1').val(nama1);
                            $('#lembaga1').val(lembaga1);
                            $('#saldo1').val(formatAngka(saldo1,''));

                            $('#cariid1x').val('');
                            kirimsyarat();

                            setTimeout(() => {
                                hutangDatatable = tampil_data_hutang(); 
                                setTimeout(() => {
                                    hutangDatatable.ajax.url('{{route('pos01.transaksi.bayarhutangcustomer_showhutang')}}').load();                
                                    hutangDatatable.draw(null, false);
                                                                        
                                }, 500);           
                            }, 500);

                        }, 500); 
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

    setTimeout(() => {
        // var a = {{ $saldo }};
        // var b = {{ $idanggota }};
        // var c = $('#saldo1').val();
        // if(c<>'0'||c<>''){
        //     $('#cariid1x').val('');
        // }
        // setTimeout(() => {
        //     if(a<>'0'||a<>''){
        //         // alert(b);
        //     }
        // }, 100);
    }, 10000);

    function customer(idx){
        var id1=idx;
        var nomorbukti1 = $('#nomorbukti1').val();			
            
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
                        $('#saldo1').val(formatAngka(resultData[i].saldohutang,''));
                        $('#saldosaving1').val(resultData[i].akhir);
                    }
                    
                },
                error : function(data){
                    
                }
            }); 
            displaypembayaran(nomorbukti1);

    }

    function displaypembayaran(idx){
        var id1=idx;
        
            $.ajax({
                type  : 'get',
                url   : `{{ url('pos01/transaksi/bayarhutangcustomerdisplaypembayaran//')}}/${id1}`,
                async : false,
                dataType : 'json',	
                
                success : function(data){
                    
                    var i;
                    var resultData = data.data;	                			
                    for(i=0; i<resultData.length; i++){
                        $('#subtotals1').val(formatAngka(resultData[i].subtotals,''));  
                        $('#ppns1').val(formatAngka(resultData[i].ppns,''));  
                        $('#diskons1').val(formatAngka(resultData[i].diskons,''));  
                        $('#totals1').val(formatAngka(resultData[i].totals,''));  
                        $('#bayars1').val(formatAngka(resultData[i].bayars,''));  
                        $('#vouchers1').val(formatAngka(resultData[i].vouchers,''));  
                        $('#ambilsavings1').val(formatAngka(resultData[i].ambilsavings,''));  
                        $('#kembalis1').val(formatAngka(resultData[i].kembalis,''));  
                        $('#savings1').val(formatAngka(resultData[i].savings,''));
                        $('#jmls1').val(resultData[i].jml);  
                        $('#idjenispembayaran1').val(formatAngka(resultData[i].idjenispembayaran,''));  
                         
                        if(resultData[i].subtotals=='0'){
                            $('#subtotals1').val($('#displaysubtotal1').text());
                        }
                        if(resultData[i].totals=='0'){
                            $('#totals1').val($('#displaysubtotal1').text());
                        }
                        if(resultData[i].idjenispembayaran=='0'){
                            $('#idjenispembayaran1').val('1');
                        }
                        
                    }
                },
                error : function(data){
                    
                }
            }); 
    }

    
    
    $("#tgltransaksi1").datepicker({
           dateFormat  : "yy-mm-dd",
           changeMonth : true,
           changeYear  : true         
    });
    
    $('#tgltransaksi1').on('click',function(){
       var x = $('#tgltransaksi1').val().length;
       if(x==10){
           $( "#nomorbukti1" ).prop( "disabled", false ); 
           $( "#btn_nomorbukti1" ).prop( "disabled", false );         
       }else{
           $( "#nomorbukti1" ).prop( "disabled", true ); 
           $( "#btn_nomorbukti1" ).prop( "disabled", true );         
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
           $( "#nomorbukti1" ).prop( "disabled", false ); 
           $( "#btn_nomorbukti1" ).prop( "disabled", false );         
       }else{
           $( "#nomorbukti1" ).prop( "disabled", true ); 
           $( "#btn_nomorbukti1" ).prop( "disabled", true );         
       }  	
       setTimeout(() => {
           kirimsyarat();
           setTimeout(() => {
               tampil_data();  
               tampil_tombol();               
           }, 200);
       }, 200);					
    });
    
    $("#btn_nomorbukti1").on('click',function(){        
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
    
    $("#nomorbukti1").on('keyup',function(){  
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
    $("#nomorbukti1").on('change',function(){  
       setTimeout(() => {
           kirimsyarat();
           setTimeout(() => {            
               tampil_data();  
               tampil_tombol();
               //refresh barang
               setTimeout(() => {
                    hutangDatatable = tampil_data_hutang();
                    setTimeout(() => {
                        hutangDatatable.ajax.url('{{route('pos01.transaksi.bayarhutangcustomer_showhutang')}}').load();                
                        hutangDatatable.draw(null, false);                         
                    }, 200);    
                }, 200);               
           }, 200);        
       }, 200);
    });
    
   
    
    setTimeout(() => {
        var x = $('#tgltransaksi1').val().length;
        if(x>=10){
            $( "#nomorbukti1" ).prop( "disabled", false ); 
            $( "#btn_nomorbukti1" ).prop( "disabled", false );         
        }else{
            $( "#nomorbukti1" ).prop( "disabled", true ); 
            $( "#btn_nomorbukti1" ).prop( "disabled", true );         
        } 
    }, 1000);
    

    function tampil_dataTable(){        
        hutangDatatable.draw(null, false);               
    }

    function kirimsyarat(){
        var tgltransaksi1=$('#tgltransaksi1').val();
        var nomorbukti1=$('#nomorbukti1').val();
        var idanggota1=$('#idanggota1').val();
        var saldo1=$('#saldo1').val();
    
        let formData = new FormData();
            formData.append('tgltransaksi1', tgltransaksi1);
            formData.append('nomorbukti1', nomorbukti1);
            formData.append('idanggota1', idanggota1);
            formData.append('saldo1', saldo1);
    
        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pos01.transaksi.bayarhutangcustomer_kirimsyarat')}}',
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
    
    function displayhutangx(){
        var a = $('#idhutangx1').text();
        const aArray = a.split("|");
        var tglstatus = aArray[0];
        var angsuranke = aArray[2];
        var xangsuran = aArray[3];
        var jmlangsuran = aArray[4];
        var hutangsemula = aArray[5];
        var sudahbayar = aArray[6];
        var saldohutang = aArray[7];    
        // $("#tglhutang1").val($("#idhutang1").val());     
        // $("#keterangan1").val($("#idhutang1").val());
        // setTimeout(() => {
        //     $("#keterangan1").val($('#idhutangx1').text());
        // }, 500);     
    }
    
    $("#idhutang1").on('click',function(){
        $('#idhutangx1').val($("#idhutang1").val());
        setTimeout(() => {
            displayhutang($('#idhutang1').val())
        }, 100);
    });

    function displayhutang(idx){
        var id1=idx;			
            
            $.ajax({
                type  : 'get',
                url   : `{{ url('pos01/transaksi/bayarhutangcustomerdisplayhutang')}}/${id1}`,
                async : false,
                dataType : 'json',	
                
                success : function(data){
                    var jumlahx;
                    var i;                
                    var resultData = data.data;	                			
                    for(i=0; i<resultData.length; i++){
                        $('#tglhutang1').val(resultData[i].tglstatus);
                        $('#nilaihutang1').val(formatAngka(resultData[i].asli));
                        $('#sudahbayar1').val(formatAngka(resultData[i].asli - resultData[i].pokok));
                        $('#saldohutang1').val(formatAngka(resultData[i].pokok));
                        $('#sisasaldo1').val(formatAngka(resultData[i].pokok));                        
                    }
                    
                },
                error : function(data){
                    alert(id1);
                }
            }); 
    }
    
    $('#btn_tambah1').on('click',function(){
        
        tampil_listhutang();
        tampil_listhutangx();

        $("#idhutang1").click(); 
        displayhutang($('#idhutang1').val());
        let a = $('#tgltransaksi1').val();
        const aArray = a.split("-");
        let thn = aArray[0];
        let bln = aArray[1];
        let tgl = aArray[2];
        let b = thn+bln+tgl;

        let x = $('#nomorbukti1').val();
        const xArray = x.split(".");
        let w = xArray[0];
        let y = xArray[1];
        let z = xArray[2];
        let j = xArray[3];
        let k1 = y.length;
        let k2 = z.length;
        let k3 = j.length;

        if(w=='BHC'&&b==z&&k1=='3'&&k3=='4'){
            btn_baru_click();
        
            $("#iconx").removeClass("fas fa-edit");
            $("#iconx").addClass("fas fa-plus-square");
            $("#modalx").removeClass("modal-content bg-success w3-animate-zoom");
            $("#modalx").addClass("modal-content bg-primary w3-animate-zoom");
            document.getElementById("btn_simpan").disabled = false;
            $('#ModalAdd').modal('show');
            $('#id1').val('0');
            $('#judulx').html(' Tambah Data');
        
        }else{
            swalnomorbuktisalah(x);
        }

        if ($('#saldo1').val()=='0'||$('#saldo1').val()==''){

        }
    }); 
    
    function data_simpan(){
        var judul = $('#idhutang1 option:selected').text();
        var id1=$('#id1').val();			
        var tgltransaksi1=$('#tgltransaksi1').val();
        var nomorbukti1=$('#nomorbukti1').val();
        var idhutang1=$('#idhutang1').val();
        var idanggota1=$('#idanggota1').val();
        var bayar1 = $('#bayar1').val().replace(/[^,\d]/g, '').toString()
        var saldohutang1 = $('#saldohutang1').val().replace(/[^,\d]/g, '').toString()
        var keterangan1=$('#keterangan1').val();
        
        let formData = new FormData();
            formData.append('id1', id1);
            formData.append('tgltransaksi1', tgltransaksi1);
            formData.append('nomorbukti1', nomorbukti1);
            formData.append('idhutang1', idhutang1);
            formData.append('idanggota1', idanggota1);
            formData.append('bayar1', bayar1);
            formData.append('saldohutang1', saldohutang1);
            formData.append('keterangan1', keterangan1);            
          
        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pos01.transaksi.bayarhutangcustomer_create')}}',
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

    $("#btn_nomorbukti1").on('click',function(){
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
            url    : '{{route('pos01.transaksi.bayarhutangcustomer_nomorbukti')}}',
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
                    $('#nomorbukti1').val(resultData[0].nomorbukti);                                        
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
            url    : '{{route('pos01.transaksi.bayarhutangcustomer_nomorposting')}}',
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

        tampil_listhutang();
        tampil_listhutangx();

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
                url   : `{{ url('pos01/transaksi/bayarhutangcustomeredit')}}/${id1}`,
                async : false,
                dataType : 'json',	
                
                success : function(data){                   
                    var i;
                    var idhutang;                
                    var idhutangx;                
                    var resultData = data.data;	                			
                    for(i=0; i<resultData.length; i++){
                        idhutang = resultData[i].idhutang;
                        idhutang1 = resultData[i].idhutang;
                        
                        $('#bayar1').val(formatAngka(resultData[i].bayar,''));
                        $('#keterangan1').val(resultData[i].keterangan);
                        setTimeout(() => {
                            $('#idhutang1').val(idhutang);
                        }, 50);
                        displayhutang(idhutang);
                        $('#bayar1').change();
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
        var nomorbukti1 = $('#nomorbukti1').val();       
        var a1 = $("#displaysubtotal1").text();
        var b1 = $("#nomorpostingnya1").val();

        let x = $('#nomorbukti1').val();
        const xArray = x.split(".");
        let w = xArray[0];

       if(a1=='0'||a1==''){
            swaldatakosong('- Data belum ada -');
       }else{
            if(b1==''){
                swaldatakosong('- Data belum diposting -');
                // $("#ModalPembayaran").modal('show');
            }else{                
                if(w=='BHC'){
                    setTimeout(() => {
                        displaypembayaran(nomorbukti1);
                    }, 100);
                    $("#ModalPembayaran").modal('show');
                    document.getElementById('kembalis1').setAttribute("style","background-color:red");
                    document.getElementById('savings1').setAttribute("style","background-color:red");
                }else{
                    swaldatakosong('- Data salah -');
                }

                
            }
       }       
    });


    $("#bayar1").on('change',function(){
        var saldohutang1=parseFloat($('#saldohutang1').val().replace(/[^,\d]/g, '').toString()); 
        if($("#bayar1").val()==''){
            $("#bayar1").val('0');
        }
        var bayar1=parseFloat($('#bayar1').val().replace(/[^,\d]/g, '').toString());

        if(bayar1>=saldohutang1){
            $("#bayar1").val(formatAngka(saldohutang1,''));
        }
        var bayar1=parseFloat($('#bayar1').val().replace(/[^,\d]/g, '').toString());
        var sisasaldo1 = formatAngka(saldohutang1 - bayar1,''); 
        $("#sisasaldo1").val(sisasaldo1);
    });
    $("#bayar1").on('keydown',function(){
        var saldohutang1=parseFloat($('#saldohutang1').val().replace(/[^,\d]/g, '').toString()); 
        if($("#bayar1").val()==''){
            $("#bayar1").val('0');
        }
        var bayar1=parseFloat($('#bayar1').val().replace(/[^,\d]/g, '').toString());
        if(bayar1>=saldohutang1){
            $("#bayar1").val(formatAngka(saldohutang1,''));
        }
        var bayar1=parseFloat($('#bayar1').val().replace(/[^,\d]/g, '').toString());
        var sisasaldo1 = formatAngka(saldohutang1 - bayar1,''); 
        $("#sisasaldo1").val(sisasaldo1);
    });

    $("#ppns1").on('change',function(){
        if($("#subtotals1").val()==''){
            $("#subtotals1").val('0');
        }
        var subtotals1=parseFloat($('#subtotals1').val().replace(/[^,\d]/g, '').toString()); 
        
        if($("#ppns1").val()==''){
            $("#ppns1").val('0');
        }
        var ppns1=parseFloat($('#ppns1').val().replace(/[^,\d]/g, '').toString()); 
        
        if($("#diskons1").val()==''){
            $("#diskons1").val('0');
        }
        var diskons1=parseFloat($('#diskons1').val().replace(/[^,\d]/g, '').toString()); 
        
        if(ppns1>=20/100*subtotals1){
            var ppns1 = parseFloat(20/100*subtotals1);
            var totals1 = subtotals1 + ppns1 - diskons1;
            setTimeout(() => {                
                $("#ppns1").val(formatAngka(ppns1,''));
            }, 10);
        }
        var totals1 = subtotals1 +  ppns1 -  diskons1;    
        $("#totals1").val(formatAngka(totals1,''));
        $("#bayars1").change();
    });
    $("#ppns1").on('keydown',function(){
        if($("#subtotals1").val()==''){
            $("#subtotals1").val('0');
        }
        var subtotals1=parseFloat($('#subtotals1').val().replace(/[^,\d]/g, '').toString()); 
        
        if($("#ppns1").val()==''){
            $("#ppns1").val('0');
        }
        var ppns1=parseFloat($('#ppns1').val().replace(/[^,\d]/g, '').toString()); 
        
        if($("#diskons1").val()==''){
            $("#diskons1").val('0');
        }
        var diskons1=parseFloat($('#diskons1').val().replace(/[^,\d]/g, '').toString()); 
        
        if(ppns1>=20/100*subtotals1){
            var ppns1 = parseFloat(20/100*subtotals1);
            var totals1 = subtotals1 + ppns1 - diskons1;
            setTimeout(() => {                
                $("#ppns1").val(formatAngka(ppns1,''));
            }, 10);
        }
        var totals1 = subtotals1 +  ppns1 -  diskons1;    
        $("#totals1").val(formatAngka(totals1,''));
        $("#bayars1").change();
    });
    $("#diskons1").on('change',function(){
        if($("#subtotals1").val()==''){
            $("#subtotals1").val('0');
        }
        var subtotals1=parseFloat($('#subtotals1').val().replace(/[^,\d]/g, '').toString()); 
        
        if($("#ppns1").val()==''){
            $("#ppns1").val('0');
        }
        var ppns1=parseFloat($('#ppns1').val().replace(/[^,\d]/g, '').toString()); 
        
        if($("#diskons1").val()==''){
            $("#diskons1").val('0');
        }
        var diskons1=parseFloat($('#diskons1').val().replace(/[^,\d]/g, '').toString()); 
        
        if(diskons1>=100/100*subtotals1){
            var diskons1 = parseFloat(100/100*subtotals1);
            var totals1 = subtotals1 + ppns1 - diskons1;
            setTimeout(() => {                
                $("#diskons1").val(formatAngka(diskons1,''));
            }, 10);
        }
        var totals1 = subtotals1 +  ppns1 -  diskons1;    
        $("#totals1").val(formatAngka(totals1,''));
        $("#bayars1").change();
    });
    $("#diskons1").on('keydown',function(){
        if($("#subtotals1").val()==''){
            $("#subtotals1").val('0');
        }
        var subtotals1=parseFloat($('#subtotals1').val().replace(/[^,\d]/g, '').toString()); 
        
        if($("#ppns1").val()==''){
            $("#ppns1").val('0');
        }
        var ppns1=parseFloat($('#ppns1').val().replace(/[^,\d]/g, '').toString()); 
        
        if($("#diskons1").val()==''){
            $("#diskons1").val('0');
        }
        var diskons1=parseFloat($('#diskons1').val().replace(/[^,\d]/g, '').toString()); 
        
        if(diskons1>=100/100*subtotals1){
            var diskons1 = parseFloat(100/100*subtotals1);
            var totals1 = subtotals1 + ppns1 - diskons1;
            setTimeout(() => {                
                $("#diskons1").val(formatAngka(diskons1,''));
            }, 10);
        }
        var totals1 = subtotals1 +  ppns1 -  diskons1;    
        $("#totals1").val(formatAngka(totals1,''));
        $("#bayars1").change();    
    });

    $("#bayars1").on('change',function(){
        if($("#totals1").val()==''){
            $("#totals1").val('0');
        }
        var totals1=parseFloat($('#totals1').val().replace(/[^,\d]/g, '').toString());

        if($("#bayars1").val()==''){
            $("#bayars1").val('0');
        }
        var bayars1=parseFloat($('#bayars1').val().replace(/[^,\d]/g, '').toString()); 

        if($("#vouchers1").val()==''){
            $("#vouchers1").val('0');
        }
        var vouchers1=parseFloat($('#vouchers1').val().replace(/[^,\d]/g, '').toString()); 

        if($("#ambilsavings1").val()==''){
            $("#ambilsavings1").val('0');
        }
        var ambilsavings1=parseFloat($('#ambilsavings1').val().replace(/[^,\d]/g, '').toString());
        
        var bayarx = bayars1 + vouchers1 + ambilsavings1;
        var kembalix = bayarx - totals1; 
        if(kembalix>='0'){
            document.getElementById('kembalis1').setAttribute("style","background-color:white");
            document.getElementById('savings1').setAttribute("style","background-color:white");
            $("#btn_proses").prop("disabled",false);
        }else{
            document.getElementById('kembalis1').setAttribute("style","background-color:red");
            document.getElementById('savings1').setAttribute("style","background-color:red");
            $("#btn_proses").prop("disabled",true);
            $("#btn_cetak").prop("disabled",true);
        } 
        var kembali500 = floorn(kembalix,500);        
        $('#kembalis1').val(formatAngka(kembali500,''));
        var kembalis1=parseFloat($('#kembalis1').val().replace(/[^,\d]/g, '').toString());
        var savingx = kembalix - kembali500;
        $('#savings1').val(formatAngka(savingx,''));
    });

    $("#bayars1").on('keydown',function(){
        if($("#totals1").val()==''){
            $("#totals1").val('0');
        }
        var totals1=parseFloat($('#totals1').val().replace(/[^,\d]/g, '').toString());

        if($("#bayars1").val()==''){
            $("#bayars1").val('0');
        }
        var bayars1=parseFloat($('#bayars1').val().replace(/[^,\d]/g, '').toString()); 

        if($("#vouchers1").val()==''){
            $("#vouchers1").val('0');
        }
        var vouchers1=parseFloat($('#vouchers1').val().replace(/[^,\d]/g, '').toString()); 

        if($("#ambilsavings1").val()==''){
            $("#ambilsavings1").val('0');
        }
        var ambilsavings1=parseFloat($('#ambilsavings1').val().replace(/[^,\d]/g, '').toString());
        
        var bayarx = bayars1 + vouchers1 + ambilsavings1;
        var kembalix = bayarx - totals1; 
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
        var savingx = kembalix - kembali500;
        $('#savings1').val(formatAngka(savingx,''));        
    });

    $("#vouchers1").on('change',function(){
        if($("#totals1").val()==''){
            $("#totals1").val('0');
        }
        var totals1=parseFloat($('#totals1').val().replace(/[^,\d]/g, '').toString());

        if($("#bayars1").val()==''){
            $("#bayars1").val('0');
        }
        var bayars1=parseFloat($('#bayars1').val().replace(/[^,\d]/g, '').toString()); 

        if($("#vouchers1").val()==''){
            $("#vouchers1").val('0');
        }
        var vouchers1=parseFloat($('#vouchers1').val().replace(/[^,\d]/g, '').toString()); 

        if($("#ambilsavings1").val()==''){
            $("#ambilsavings1").val('0');
        }
        var ambilsavings1=parseFloat($('#ambilsavings1').val().replace(/[^,\d]/g, '').toString());
        
        var bayarx = bayars1 + vouchers1 + ambilsavings1;
        var kembalix = bayarx - totals1; 
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
        var savingx = kembalix - kembali500;
        $('#savings1').val(formatAngka(savingx,''));
    });
    $("#vouchers1").on('keydown',function(){
        if($("#totals1").val()==''){
            $("#totals1").val('0');
        }
        var totals1=parseFloat($('#totals1').val().replace(/[^,\d]/g, '').toString());

        if($("#bayars1").val()==''){
            $("#bayars1").val('0');
        }
        var bayars1=parseFloat($('#bayars1').val().replace(/[^,\d]/g, '').toString()); 

        if($("#vouchers1").val()==''){
            $("#vouchers1").val('0');
        }
        var vouchers1=parseFloat($('#vouchers1').val().replace(/[^,\d]/g, '').toString()); 

        if($("#ambilsavings1").val()==''){
            $("#ambilsavings1").val('0');
        }
        var ambilsavings1=parseFloat($('#ambilsavings1').val().replace(/[^,\d]/g, '').toString());
        
        var bayarx = bayars1 + vouchers1 + ambilsavings1;
        var kembalix = bayarx - totals1; 
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
        var savingx = kembalix - kembali500;
        $('#savings1').val(formatAngka(savingx,''));       
    });

    $("#ambilsavings1").on('change',function(){
        if($("#totals1").val()==''){
            $("#totals1").val('0');
        }
        var totals1=parseFloat($('#totals1').val().replace(/[^,\d]/g, '').toString());

        if($("#bayars1").val()==''){
            $("#bayars1").val('0');
        }
        var bayars1=parseFloat($('#bayars1').val().replace(/[^,\d]/g, '').toString()); 

        if($("#vouchers1").val()==''){
            $("#vouchers1").val('0');
        }
        var vouchers1=parseFloat($('#vouchers1').val().replace(/[^,\d]/g, '').toString()); 

        if($("#ambilsavings1").val()==''){
            $("#ambilsavings1").val('0');
        }
        var ambilsavings1=parseFloat($('#ambilsavings1').val().replace(/[^,\d]/g, '').toString());       
        var saldosaving1=parseFloat($('#saldosaving1').val().replace(/[^,\d]/g, '').toString());       
        if(ambilsavings1>=saldosaving1){
            var ambilsavings1 = saldosaving1;
            var bayarx = bayars1 + vouchers1 + ambilsavings1;
            setTimeout(() => {                
                $("#ambilsavings1").val(formatAngka(ambilsavings1,''));
            }, 10);
        }
        var bayarx = bayars1 + vouchers1 + ambilsavings1;
        var kembalix = bayarx - totals1; 
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
        var savingx = kembalix - kembali500;
        $('#savings1').val(formatAngka(savingx,''));       
    });
    $("#ambilsavings1").on('keydown',function(){
        if($("#totals1").val()==''){
            $("#totals1").val('0');
        }
        var totals1=parseFloat($('#totals1').val().replace(/[^,\d]/g, '').toString());

        if($("#bayars1").val()==''){
            $("#bayars1").val('0');
        }
        var bayars1=parseFloat($('#bayars1').val().replace(/[^,\d]/g, '').toString()); 

        if($("#vouchers1").val()==''){
            $("#vouchers1").val('0');
        }
        var vouchers1=parseFloat($('#vouchers1').val().replace(/[^,\d]/g, '').toString()); 

        if($("#ambilsavings1").val()==''){
            $("#ambilsavings1").val('0');
        }
        var ambilsavings1=parseFloat($('#ambilsavings1').val().replace(/[^,\d]/g, '').toString());
        var saldosaving1=parseFloat($('#saldosaving1').val().replace(/[^,\d]/g, '').toString());       
        if(ambilsavings1>=saldosaving1){
            var ambilsavings1 = saldosaving1;
            var bayarx = bayars1 + vouchers1 + ambilsavings1;
            setTimeout(() => {                
                $("#ambilsavings1").val(formatAngka(ambilsavings1,''));
            }, 10);
        }
        var bayarx = bayars1 + vouchers1 + ambilsavings1;
        var kembalix = bayarx - totals1; 
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
        var savingx = kembalix - kembali500;
        $('#savings1').val(formatAngka(savingx,''));       
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
        var nomorbukti1=$('#nomorbukti1').val();
        var tglposting1=$('#tglposting5').text();
        var nomorposting1=$('#nomorposting5').text();
    
        let formData = new FormData();
            formData.append('tgltransaksi1', tgltransaksi1);
            formData.append('nomorbukti1', nomorbukti1);
            formData.append('tglposting1', tglposting1);
            formData.append('nomorposting1', nomorposting1);
          
        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pos01.transaksi.bayarhutangcustomer_posting')}}',
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
        var data3b=$('#data3a').val();
        
        $.ajax({
            type  : 'get',
            url   : '{{url('pos01/transaksi/bayarhutangcustomerdestroy')}}/'+id3,
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
        var nomorbukti1 = $('#nomorbukti1').val();
        var tgltransaksi1 = $('#tgltransaksi1').val();
        var idanggota1 = $('#idanggota1').val();
                
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
            formData.append('nomorbukti1', nomorbukti1);
            formData.append('tgltransaksi1', tgltransaksi1);
            formData.append('idanggota1', idanggota1);                     
          
        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pos01.transaksi.bayarhutangcustomer_proses')}}',
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
            displaypembayaran(nomorbukti1);
        }, 500);
        
    }

    function swalgagalposting(x){
        Swal.fire({
            icon: 'error',
            title: 'Oops...failed to posting record',
            text: x,
            timer:1000
        })
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
            text: 'Klik Generate Nomor Bukti (icon gear)',
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