@extends('admin.layouts.main')

@section('contents')

@php
    $tgltransaksix1 = session('tgltransaksix1');   
    if($tgltransaksix1==''){
        $tgltransaksix1='';  
    }
    $nomorbuktix1 = session()->get('nomorbuktix1');
    if($nomorbuktix1==''){
        $nomorbuktix1='';  
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

    <div class="box-header mb-3">  
        <div class="row">
            
            <div class="col-md-3">
                
                <div class="row">
                    <div class="col-md-4 text-right">
                        <h6 class="mt-2">Tanggal Transaksi</h6>
                        
                    </div>
                    <div class="col-md-8">
                        <input name="tgltransaksix1" id="tgltransaksix1" class="w3-input w3-border" maxlength="10" type="text" placeholder="Tgl Transaksi" required autocomplete="off" value="{{ session('tgltransaksix1') }}" required>                       
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 text-right">
                        <h6 class="mt-2">Nomor Bukti</h6>
                    </div>
                    <div class="col-md-8 input-group">
                        <input name="nomorbuktix1" id="nomorbuktix1" class="form-control w3-input w3-border rounded-0" type="search" placeholder="ANG.001.20230131.0001" disabled value="{{ session('nomorbuktix1') }}">                        
                        <div class="input-group-append">
                            <button id="btn_nomorbuktix1" name="btn_nomorbuktix1" type="button" style="border-radius:0px; border:none;" title="Generate Nomor Bukti" disabled><i style="font-size:24" class="fa">&#xf013;</i></button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 text-right">
                        <h6 class="mt-2">Jenis Pinjaman</h6>
                    </div>
                    <div class="col-md-8">
                        <select name="idjenispinjamanx1" id="idjenispinjamanx1" class="js-select-auto__select form-control" style="border-radius:0px; height:40px; display: block;" autocomplete="off"></select> 
                        <input name="ujrohax1" id="ujrohax1" type="hidden">                      
                        <input name="ujrohbx1" id="ujrohbx1" type="hidden">                      
                        <input name="idtargetx1" id="idtargetx1" type="hidden">                      
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-4 mt-2 text-right">
                        <h6>NIA</h6>                        
                    </div>
                    <div class="col-md-8 input-group">
                        <input name="niax1" id="niax1" type="hidden">
                        <input name="cekprint" id="cekprint" type="hidden" value="0">
                        <input name="cariidx1" id="cariidx1" class="form-control w3-input w3-border rounded-0" type="search" value="{{ session('niax1') }}" placeholder="NIA" autocomplete="off" autofocus>                        
                        <div class="input-group-append">
                          <button id="btn_cariidx1" name="btn_cariidx1" type="button" style="border-radius:0px; border:none;"><i style="font-size:24" class="fas">&#xf002;</i></button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mt-2 text-right">
                        <h6>Nama</h6> 
                        <input name="idanggotax1" id="idanggotax1" type="hidden" value="{{ session('idanggotax1') }}">
                    </div>
                    <div class="col-md-8">
                        <input name="namax1" id="namax1" class="w3-input w3-border" type="text" placeholder="Nama" value="{{ session('namax1') }}" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mt-2 text-right">
                        <h6>Kode Pinjaman</h6>                         
                    </div>
                    <div class="col-md-8">
                        <input name="kodepinjamanx1" id="kodepinjamanx1" class="w3-input w3-border" type="text" placeholder="kode" value="{{ session('kodepinjamanx1') }}" readonly>
                    </div>
                </div>
                
            </div>

            <div class="col-md-6">
                <div class="w3-row" align="right"><i class="fa fa-refresh" aria-hidden="true"></i>            
                    <a href="{{ url('/') }}{{ $link }}" class="btn bg-success rounded-0"><i style="font-size:18px" class="fa">&#xf021;</i> Refresh</a>            
                    <button id="btn_tambah1" name="btn_tambah1" type="button" class="btn bg-primary rounded-0"><i class="fas fa-plus"></i> Tambah</button>
                    <button id="btn_posting1" name="btn_posting1" type="button" class="btn bg-warning rounded-0"><i class="fa fa-upload"></i> Posting</button>	            
                </div> 
            </div>
        </div>

        <div class="row">
            <div class="col-md-1">
            </div>
            <div class="col-md-10 mt-2">
                <marquee id="pesanjatuhtempox1" name="pesanjatuhtempox1" behavior="alternate" direction="left" scrollamount="10" style="color: red; font-weight: bold; font-size: 1.5em;"></marquee>
            </div>
            <div class="col-md-1">
            </div>
        </div>

    </div>

    <!--awal tabel-->        
        <div class="box-body" id="headerjudul" style="display: block;">
            <div id="reload" class="table-responsive">
                
                <table id="data1" class="table table-bordered table-striped table-hover" style="width: 100%">
                    <thead>
                        <tr>
                            <th style="width:10px;">#</th>                            
							<th style="width:50px">Tanggal Transaksi</th>
							<th style="width:50px">Nomor Bukti</th>
							<th style="width:50px">Sandi</th>
							<th style="width:50px">Coa</th>
							<th style="width:50px">Debet</th>
							<th style="width:50px">Kredit</th>
							<th style="width:50px">Tanggal Posting</th>
							<th style="width:50px">Nomor Posting</th>
							<th style="width:100px">Keterangan</th>							
                            <th style="width:10px">Action</th>
                        </tr>
                    </thead>
                    <tfoot id="show_footerdata1">
                        <tr>
                            <th></th>                            
                            <th></th>                            
                            <th style="align-items: center;">TOTAL :</th>                            
                            <th></th>                            
                            <th></th>                            
                            <th id="totaldebet1" name="totaldebet1"></th>                            
                            <th id="totalkredit1" name="totalkredit1"></th>                            
                            <th colspan="4"></th>                            
                                                        
                        </tr>
                    </tfoot>
                    <tbody id="show_data1">
                    
                    </tbody>
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
                                <div class="col-md-4 mt-1" align="right">									
                                    <h6 class="mt-2">Sandi</h6>
                                </div>
                                <div class="col-md-8">  
                                    <input name="cek1" id="cek1" class="" type="hidden">                               
                                    <input name="id1" id="id1" class="" type="hidden">
                                    <select name="idsandi1" id="idsandi1" class="js-select-auto__select form-control" style="border-radius:0px; height:40px; display: block;" autocomplete="off"></select>                              
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-4 mt-1" align="right">									
                                    <h6 class="mt-2">COA</h6>
                                </div>
                                <div class="col-md-8">  
                                    <select name="idcoa1" id="idcoa1" class="js-select-auto__select form-control" style="border-radius:0px; height:40px; display: block;" autocomplete="off"></select> 
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-4 mt-1" align="right">									
                                    <h6 class="mt-2">Jenis Jurnal</h6>
                                </div>
                                <div class="col-md-8">  
                                    <input name="cek1" id="cek1" class="" type="hidden">                                
                                    <input name="id1" id="id1" class="" type="hidden">
                                    <select name="idjenisjurnal1" id="idjenisjurnal1" class="js-select-auto__select form-control" style="border-radius:0px; height:40px; display: block;" autocomplete="off"></select>                              
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-4 mt-1" align="right">									
                                    <h6 class="mt-2">Debet</h6>
                                </div>
                                <div class="col-md-8">  
                                    <input name="debet1" id="debet1" class="w3-input w3-border text-right" type="search" placeholder="Debet" value="0" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                    <span id="terbilangd1" name="terbilangd1" style="color: yellow">nol</span>
                                </div>								  
                            </div>

                            <div class="row">
                                <div class="col-md-4 mt-1" align="right">									
                                    <h6 class="mt-2">Kredit</h6>
                                </div>
                                <div class="col-md-8">  
                                    <input name="kredit1" id="kredit1" class="w3-input w3-border text-right" style="background-color:gold" type="search" placeholder="Kredit" value="0" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                    <span id="terbilangk1" name="terbilangk1" style="color: yellow">nol</span>
                                </div>								  
                            </div>
                            
                            
                            <div class="row">
                                <div class="col-md-4" align="right">										
                                    <h6 class="mt-2">Tipe</h6>
                                </div>
                                <div class="col-md-5">                                
                                    <input name="tipe1" id="tipe1" maxlength="40" class="w3-input w3-border text-right" type="number" value="1" placeholder="tipe pinjaman" autocomplete="off" readonly>
                                </div>								  
                                <div class="col-md-3">                                
                                    <h6 class="mt-2">Bulanan</h6>
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-4" align="right">										
                                    <h6 class="mt-2">X Angsuran</h6>
                                </div>
                                <div class="col-md-2">                                
                                    <input name="ke1" id="ke1" maxlength="40" class="w3-input w3-border text-right" type="number" value="1" placeholder="ke" autocomplete="off" readonly>
                                </div>
                                <div class="col-md-1 x-0" align="center">                                
                                    <h6 class="mt-2">/</h6>
                                </div>
                                <div class="col-md-2">                                
                                    <input name="xangsuran1" id="xangsuran1" maxlength="40" class="w3-input w3-border text-right" type="number" value="1" placeholder="x angsuran" autocomplete="off" readonly>
                                </div>								  
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4" align="right">										
                                    <h6 class="mt-2">Nilai Angsuran</h6>
                                </div>
                                
                                <div class="col-md-8 input-group">
                                    <input name="nilaiangsuran1" id="nilaiangsuran1" class="form-control w3-input w3-border rounded-0 text-right" type="search" placeholder="nilai angsuran" value="0" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" readonly>                        
                                    <div class="input-group-append">
                                        <button id="btn_nilaiangsuran1" name="btn_nilaiangsuran1" type="button" style="border-radius:0px; border:none; display: none;" title="Generate Nilai Angsuran" disabled><i style="font-size:24" class="fa">&#xf013;</i></button>
                                    </div>                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4" align="right">										
                                    {{--  --}}
                                </div>
                                <div class="col-md-8">                                
                                    <span id="terbilangnilaiangsuran1" name="terbilangnilaiangsuran1" style="color: yellow">nol</span>
                                </div>	
                            </div>

                            <div class="row">
                                <div class="col-md-4" align="right">										
                                    <h6 class="mt-2">Ujroh</h6>
                                </div>
                                <div class="col-md-8 input-group">                                
                                    <input name="ujroh1" id="ujroh1" class="form-control w3-input w3-input w3-border rounded-0 text-right" type="search" placeholder="ujroh" value="0" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" readonly>
                                    <button id="btn_ujroh1" name="btn_ujroh1" type="button" style="border-radius:0px; border:none; display: none;" title="Generate Ujroh" disabled><i style="font-size:24" class="fa">&#xf013;</i></button>
                                    
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-4" align="right">										
                                    {{--  --}}
                                </div>
                                <div class="col-md-8">                                
                                    <span id="terbilangujroh1" name="terbilangujroh1" style="color: yellow">nol</span>
                                </div>	
                            </div>

                            <div class="row">
                                <div class="col-md-4" align="right">										
                                    <h6 class="mt-2">Jatuh Tempo</h6>
                                </div>
                                <div class="col-md-8">                                
                                    <input name="jatuhtempo1" id="jatuhtempo1" maxlength="40" class="w3-input w3-border" type="search" placeholder="jatuh tempo" autocomplete="off" readonly>
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-4" align="right">										
                                    <h6 class="mt-2">Keterangan</h6>
                                </div>
                                <div class="col-md-8">                                
                                    <input name="keterangan1" id="keterangan1" maxlength="100" class="w3-input w3-border" type="search" placeholder="Keterangan" autocomplete="off">
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
								<table id="carianggota" width="100%" class="table table-bordered table-striped table-hover">
									<thead>
									<tr>
										<th style="width:10px;">#</th>                            
                                        <th style="width:100px">Kode Pinjaman</th>							
                                        <th style="width:100px">Nama</th>							
                                        <th style="width:100px">NIA</th>							
                                        <th style="width:100px">Jenis Pinjaman</th>																									
                                        <th style="width:100px">Nilai Pinjaman</th>							
                                        <th style="width:100px">Sudah Bayar</th>							
                                        <th style="width:100px">Saldo</th>							
                                        <th style="width:100px">x Angsuran</th>							
                                        <th style="width:100px">Nilai Angsuran</th>							
                                        <th style="width:100px">Ujroh</th>							
									</tr>
								</thead>
								
								<tfoot id="show_footercarianggota">
									
								</tfoot>
								<tbody id="show_datacarianggota">
								
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

<!-- ModalPosting modal fade-->
<div class="modal fade" id="ModalPosting"  data-backdrop="static">
    <div class="modal-dialog modal-default">  <!-- modal-(sm, lg, xl) ukuran lebar modal -->
        <div class="modal-content bg-warning w3-animate-zoom">
            
            <div class="modal-header">
                    <h3 class="modal-title"><i style="font-size:18" class="fa">&#xf093;</i><b> Posting</b></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    
            </div>
            <form class="form-horizontal">
                @csrf
                <div class="modal-body">
                    
                        <div class="row">
                            <div class="col-md-4" align="right">										
                                <h4 class="mt-2">Tgl. Transaksi</h4>
                            </div>
                            <div class="col-md-1" align="center">                                
                                <h4 class="mt-2">:</h4>
                            </div>								  
                            <div class="col-md-7">                                
                                <h4 class="mt-2"></h4>
                                <h4 class="mt-2">
                                    <span  id="tgltransaksi5" name="tgltransaksi5" style="display: block"></span>
                                    <span  id="tgltransaksi5x" name="tgltransaksi5x" style="display: none"></span>
                                </h4>
                            </div>								  
                        </div> 			
                        <div class="row">
                            <div class="col-md-4" align="right">										
                                <h4 class="mt-2">No. Bukti</h4>
                            </div>
                            <div class="col-md-1" align="center">                                
                                <h4 class="mt-2">:</h4>
                            </div>								  
                            <div class="col-md-7">                                
                                <h4 class="mt-2"><span  id="nomorbukti5" name="nomorbukti5"></span></h4>
                            </div>								  
                        </div> 			
                        <div class="row">
                            <div class="col-md-4" align="right">										
                                <h4 class="mt-2">Tgl. Posting</h4>
                            </div>
                            <div class="col-md-1" align="center">                                
                                <h4 class="mt-2">:</h4>
                            </div>								  
                            <div class="col-md-7"> 
                                <h4 class="mt-2"><span  id="tglposting5" name="tglposting5"></span></h4> 
                            </div>								  
                        </div>  			
                        <div class="row">
                            <div class="col-md-4" align="right">										
                                <h4 class="mt-2">No. Posting</h4>
                            </div>
                            <div class="col-md-1" align="center">                                
                                <h4 class="mt-2">:</h4>
                            </div>								  
                            <div class="col-md-7">
                                <h4 class="mt-2"><span  id="nomorposting5" name="nomorposting5"></span></h4>
                            </div>								  
                        </div>
                        <div class="row">
                            <div class="col-md-4" align="right">										
                                <h4 class="mt-2">Debet</h4>
                            </div>
                            <div class="col-md-1" align="center">                                
                                <h4 class="mt-2">:</h4>
                            </div>								  
                            <div class="col-md-7">
                                <h4 class="mt-2"><span  id="debet5" name="debet5"></span></h4>                                                                
                            </div>								  
                        </div>                        			
                        <div class="row">
                            <div class="col-md-4" align="right">										
                                <h4 class="mt-2">Kredit</h4>
                            </div>
                            <div class="col-md-1" align="center">                                
                                <h4 class="mt-2">:</h4>
                            </div>								  
                            <div class="col-md-7">
                                <h4 class="mt-2"><span  id="kredit5" name="kredit5"></span></h4>                                                                
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

    <!-- khusus menyimpan data yang akan sementara -->
    <input name="id3" id="id3" type="hidden">	
    <input name="data3a" id="data3a" type="hidden">	
    <input name="data3b" id="data3b" type="hidden">	
    <input name="data3c" id="data3c" type="hidden">	
    <input name="data3d" id="data3d" type="hidden">	
    <input name="data3e" id="data3e" type="hidden">	
    <input name="data3f" id="data3f" type="hidden">	
    <input name="data3g" id="data3g" type="hidden">	
    <input name="data3h" id="data3h" type="hidden">	
    <input name="data3i" id="data3i" type="hidden">	
    <input name="data3j" id="data3j" type="hidden">	
    <input name="data3k" id="data3k" type="hidden">	
    <input name="data3l" id="data3l" type="hidden">	
    <input name="data3m" id="data3m" type="hidden">	
    <input name="data3o" id="data3o" type="hidden">	
    <input name="data3p" id="data3p" type="hidden">	
    <input name="data3q" id="data3q" type="hidden">	
    <input name="data3r" id="data3r" type="hidden">
    <input name="data3s" id="data3s" type="hidden">	
    <input name="data3t" id="data3t" type="hidden">	
    <input name="data3u" id="data3u" type="hidden">	
    <input name="data3v" id="data3v" type="hidden">	
    <input name="data3w" id="data3w" type="hidden">	
    <input name="data3x" id="data3x" type="hidden">	
    <input name="data3y" id="data3y" type="hidden">	
    <input name="data3z" id="data3z" type="hidden">	

</div>


<script type="text/javascript">
   var data1Datatable;
   var carianggotaDatatable;
   var listcoa1Datatable;
$(document).ready(function(){

    // tglhariini();        
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
        $('#tgltransaksix1').val(tglsekarang);
    }

    function jatuhtempo(tglawal,jmlhari){
        //tanggalawal
        let tgl = new Date(tglawal); 
        // Tambahkan jumlah hari ke tglawal 
        tgl.setDate(tgl.getDate () + jmlhari);
        let hari=tgl.getDate();
        if(hari<10){
            hari='0'+hari;
        }
        
        let bulan=tgl.getMonth()+1;
        if(bulan<10){
            bulan='0'+bulan;
        }
        let tahun=tgl.getFullYear();
        let jt = tglsekarang=tahun+'-'+bulan+'-'+hari;
        return jt;
    }
    
    
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

    function terbilang(bilangan) {
            let x = bilangan.replace(/[^,\d]/g, '').toString();
            if (x==''){
                x = 0;
            }
            let y = parseFloat(x);
            if (y>=999999999999999){
                x = y;
            } 

			//  bilangan    = String(bilangan);
			 bilangan    = String(x);
			 var angka   = new Array('0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0');
			 var kata    = new Array('','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan');
			 var tingkat = new Array('','Ribu','Juta','Milyar','Triliun');

			 var panjang_bilangan = bilangan.length;

			//pengujian panjang bilangan
			 if (panjang_bilangan > 15) {
			   kaLimat = "Diluar Batas";
			   return kaLimat;
			 }

			 //mengambil angka-angka yang ada dalam bilangan, dimasukkan ke dalam array
			 for (i = 1; i <= panjang_bilangan; i++) {
			   angka[i] = bilangan.substr(-(i),1);
			 }

			 i = 1;
			 j = 0;
			 kaLimat = "";

			//mulai proses iterasi terhadap array angka
			 while (i <= panjang_bilangan) {

			   subkaLimat = "";
			   kata1 = "";
			   kata2 = "";
			   kata3 = "";

			   //untuk Ratusan
			   if (angka[i+2] != "0") {
				 if (angka[i+2] == "1") {
				   kata1 = "Seratus";
				 } else {
				   kata1 = kata[angka[i+2]] + " Ratus";
				 }
			   }

			   //untuk Puluhan atau Belasan
			   if (angka[i+1] != "0") {
				 if (angka[i+1] == "1") {
				   if (angka[i] == "0") {
					 kata2 = "Sepuluh";
				   } else if (angka[i] == "1") {
					 kata2 = "Sebelas";
				   } else {
					 kata2 = kata[angka[i]] + " Belas";
				   }
				 } else {
				   kata2 = kata[angka[i+1]] + " Puluh";
				 }
			   }

			   //untuk Satuan
			   if (angka[i] != "0") {
				 if (angka[i+1] != "1") {
				   kata3 = kata[angka[i]];
				 }
			   }

			   //pengujian angka apakah tidak nol semua, lalu ditambahkan tingkat
			   if ((angka[i] != "0") || (angka[i+1] != "0") || (angka[i+2] != "0")) {
				 subkaLimat = kata1+" "+kata2+" "+kata3+" "+tingkat[j]+" ";
			   }

			   //gabungkan variabe sub kaLimat (untuk Satu blok 3 angka) ke variabel kaLimat
			   kaLimat = subkaLimat + kaLimat;
			   i = i + 3;
			   j = j + 1;

			 }

			//mengganti Satu Ribu jadi Seribu jika diperlukan
			 if ((angka[5] == "0") && (angka[6] == "0")) {
			   kaLimat = kaLimat.replace("Satu Ribu","Seribu");
			 }

			 return kaLimat;
		}
  

    data1Datatable = tampil_data1();    
     function tampil_data1(){
        let i = 1;	
        return $('#data1').DataTable({
            responsive : true,
            retrieve: true,
            autoWidth : true,
            // buttons : [ {extend: 'colvis', postfixButtons: [ 'colvisRestore' ] }, {extend:'copy'}, {extend:'csv'}, {extend: 'pdf', orientation: 'portrait', pageSize: 'A4', title:'{{ $caption }}'}, {extend: 'excel', title: '{{ $caption }}'}, {extend:'print', orientation: 'portrait', pageSize: 'A4', title: '{{ $caption }}'}, ],        
            dom: 'lBfrtip',
            lengthMenu: [
                [ 10, 25, 50, 100, 500, 1000, 5000, -1 ],
                [ '10', '25', '50', '100', '500','1000','5000', 'All' ]
            ],

            "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
           
            // converting to interger to find total
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // computing column Total of the complete result 
           
            var totd1 = api
                .column(5)
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                    }, 0 );
                    
            var totk1 = api
                .column(6)
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
           
                    
                // Update footer by showing the total with the reference of the column index
                
                if(totd1!=totk1){
                    document.getElementById("totaldebet1").style.backgroundColor="red"; 
                    document.getElementById("totalkredit1").style.backgroundColor="red"; 
                }else{
                   document.getElementById("totaldebet1").style.backgroundColor=""; 
                    document.getElementById("totalkredit1").style.backgroundColor="";  
                }
                
                $( api.column(5).footer() ).html(formatAngka(totd1,''));
                $( api.column(6).footer() ).html(formatAngka(totk1,''));
                var jml = totd1 + totk1;
                if(jml==0){
                    $( api.column(7).footer() ).html('');
                }else{
                    $( api.column(7).footer() ).html('<marquee style="color:red;">Pastikan debet dan kredit harus balance sebelum posting...</marquee>');
                }
                
            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pinjaman01.transaksi.jurnalangsuran_show')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'updated_at', name: 'updated_at' },
                { data: 'nomorbukti', name: 'nomorbukti' },
                { data: 'sandi', name: 'sandi.sandi' },
                { data: 'coa', name: 'coa.coa' },
                { data: 'debet', name: 'debet', class: 'text-right'},
                { data: 'kredit', name: 'kredit', class: 'text-right'},
                { data: 'tglposting', name: 'tglposting' },
                { data: 'nomorposting', name: 'nomorposting' },
                { data: 'keterangan', name: 'keterangan' },
                { data: 'action', name: 'action', class: 'text-center' },
            ],
                "createdRow": function( row, data, dataIndex){
                    let idx = data['id'];
                    $("#cek1").val(data['nomorposting']);                                                        
                    if( data['nomorposting']== null){                        
                        $(row).css('background-color', 'pink');
                    }else{
                        // $("#cek1").val('isi');
                    }

                    setTimeout(() => {
                        data_edit(idx);
                    }, 100);

                }
        });
    }

     function tampil_data_carianggota(){
        let i = 1;	
        return $('#carianggota').DataTable({
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
            ajax   : `{{route('pinjaman01.transaksi.jurnalangsuran_showanggota')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'kode', className: 'dt-center' },
                { data: 'nama', name: 'anggota.nama' },
                { data: 'nia', name: 'anggota.nia', className: 'dt-center' },
                { data: 'jenispinjaman', name: 'jenispinjaman.jenispinjaman' },
                { data: 'debet', name: 'debet', className: 'dt-right' },
                { data: 'kredit', name: 'kredit', className: 'dt-right' },
                { data: 'saldo', name: 'saldo', className: 'dt-right' },
                { data: 'xangsuran', name: 'xangsuran', className: 'dt-center' },
                { data: 'nilaiangsuran', name: 'nilaiangsuran', className: 'dt-right' },
                { data: 'ujroh', name: 'ujroh', className: 'dt-right' },
                               
                // { data: 'action', name: 'action'},
            ]
        });
    }

    $('#cariidx1').keypress(function (e) {        
        $('#niax1').val('');
        $('#namax1').val('');
        if (e.which == 13) {
        var x = $('#cariidx1').val();
            cariid(x);
            setTimeout(() => {
                var x = $('#niax1').val();
                if (x==''){
                    $('#btn_cariidx1').click();  
                }
                setTimeout(() => {
                    kirimsyarat();
                    setTimeout(() => {
                        tampil_datatable();
                    }, 300);
                }, 300);
            }, 100);
        }

    });

    function cariid(cari){
        var cari1=cari;

        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pinjaman01.transaksi.jurnalangsuran_cariid')}}',
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
                 for(i=0; i<resultData.length; i++){                         
                        $('#idanggotax1').val(resultData[i].id);
                        $('#namax1').val(resultData[i].nama); 
                        $('#niax1').val(resultData[i].nia);
                      
                }
                               
                },
            error : function(data){ 
                    swalgagalnorek('');
                }
        });
    }

    $('#btn_cariidx1').on('click',function(){
        
        carianggotaDatatable.draw(null, false);
        setTimeout(() => {
            $('#ModalCariID').modal('show');
        }, 300);
    });

    $('#show_datacarianggota').on('click','.item_kode',function(){
        ambilcari(this);
    });
    $('#show_datacarianggota').on('click','.item_nia',function(){
        ambilcari(this);
    });
    
    $('#show_datacarianggota').on('click','.item_nama',function(){
        ambilcari(this);
    });

    function ambilcari(t){

        var id1 = $(t).attr('data1');
        var idanggota1 = $(t).attr('data2');
        var nama1 = $(t).attr('data3');
        var nia1 = $(t).attr('data4');
        var kode1 = $(t).attr('data5');
        var tgltransaksi1 = $(t).attr('data6');
        var tipe1 = $(t).attr('data7');
        var ke1 = $(t).attr('data8');
        var xangsuran1 = $(t).attr('data9');
        var nilaiangsuran1 = $(t).attr('data10');
        var ujroh1 = $(t).attr('data11');
        var idtarget1 = $(t).attr('data12');

        $('#ModalCariID').modal('hide');
        
        $('#id3').val(data1);

        $('#niax1').val(nia1);
        $('#cariidx1').val(nia1);
        $('#namax1').val(nama1);
        $('#kodepinjamanx1').val(kode1);
        $('#idanggotax1').val(idanggota1);
        $('#idtargetx1').val(idtarget1);
        
        $('#tipe1').val(tipe1);
        let kex1 = parseFloat(ke1)+1;
        $('#ke1').val(kex1);
        $('#xangsuran1').val(xangsuran1);
        $('#nilaiangsuran1').val(formatAngka(nilaiangsuran1,''));
        if(nilaiangsuran1=='0'){
            $('#terbilangnilaiangsuran1').html('Nol');    
        }else{
            $('#terbilangnilaiangsuran1').html(terbilang($('#nilaiangsuran1').val()));
        }
        $('#ujroh1').val(formatAngka(ujroh1,''));
        if(ujroh1=='0'){
            $('#terbilangujroh1').html('Nol');    
        }else{
            $('#terbilangujroh1').html(terbilang($('#ujroh1').val()));
        }
        
        //jatuhtempo
        $('#jatuhtempo1').val(jatuhtempo(tgltransaksi1, parseFloat(kex1)*30));

        let jp = $('#idjenispinjamanx1 option:selected').text();
        const jpArray = jp.split(" ");
        let jpq =  jpArray[2];
        $('#keterangan1').val('Angsuran Pinjaman ' + jpq + ' ' + kex1 + '/' + xangsuran1);
        
            // $('#pesanjatuhtempox1').text('Maaf, angsuran anda sudah melewati jatuh tempo....');

        let tglskg=$('#tgltransaksix1').val().replace(/[^,\d]/g, '').toString();
        let tglskgx=parseFloat(tglskg);
        let tgljt=$('#jatuhtempo1').val().replace(/[^,\d]/g, '').toString();
        let tgljpx=parseFloat(tgljt);
        if(tglskgx > tgljpx){
            $('#pesanjatuhtempox1').text('Maaf, angsuran anda sudah melewati jatuh tempo....');
        }else{
            $('#pesanjatuhtempox1').text('');
        }
        

    }

    //menampilkan combo idjenispinjamanx1
    tampil_jenispinjamanx1();
    function tampil_jenispinjamanx1(){				
        $.ajax({
            type: 'get',
            url   : '{{route('admin.listjenispinjaman11')}}',
            
            success: function(data){				    
                $("#idjenispinjamanx1").html(data);
                $("#idjenispinjamanx1").val({{ session('idjenispinjamanx1') }});
            }
        })                    
    }
    //menampilkan combo idsandi1
    tampil_listsandi1();
    function tampil_listsandi1(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pinjaman01.transaksi.jurnalangsuran_listsandi')}}',
            
            success: function(data){				    
                $("#idsandi1").html(data);
            }
        })                    
    }
    //menampilkan combo idcoa1
    // tampil_listcoa1();
    function tampil_listcoa1(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pinjaman01.transaksi.jurnalangsuran_listcoa')}}',
            
            success: function(data){				    
                $("#idcoa1").html(data);
            }
        })                    
    }

    //menampilkan combo idjenisjurnal1
    tampil_listjenisjurnal1();
    function tampil_listjenisjurnal1(){				
        $.ajax({
            type: 'get',
            url   : '{{route('admin.listjenisjurnal11')}}',
            
            success: function(data){				    
                $("#idjenisjurnal1").html(data);
            }
        })                    
    }

    $('#btn_posting1').on('click',function(){
        let cek = $('#cek1').val();
        if(cek==''){
            var totd1=$('#totaldebet1').text().replace(/[^,\d]/g, '').toString();
            var totk1=$('#totalkredit1').text().replace(/[^,\d]/g, '').toString();
            var totdx=parseFloat(totd1);
            var totkx=parseFloat(totk1);
            var jmlx = totdx + totkx;
           
            if(jmlx==0){
               swalpraposting('','Maaf, data belum ada...');
            }else{
               if(totdx!=totkx){
                   swalpraposting('','Maaf, Nilai debet dan kredit tidak sama...');
               }else{
                   setTimeout(() => {
                       nomorposting();
                       setTimeout(() => {
                           $('#tgltransaksi5').text($('#tgltransaksix1').val());        		
                           $('#tgltransaksi5x').text($('#tgltransaksix1').val());        		
                           $('#nomorbukti5').text($('#nomorbuktix1').val());        		
                           $('#tglposting5').text($('#tgltransaksix1').val());        		
                           $('#debet5').text($('#totaldebet1').text());        		
                           $('#kredit5').text($('#totalkredit1').text());        		
                       }, 100);
                   }, 200);
                   $('#ModalPosting').modal('show');
               }
            }
        }else{
            swalpraposting('','Maaf, data sudah diposting.....');
        }

    });

    $('#btn_posting5').on('click',function(){
        modal_posting();
    });
    
    $("#tgltransaksix1").datepicker({
           dateFormat  : "yy-mm-dd",
           changeMonth : true,
           changeYear  : true         
    });
    // $("#jatuhtempo1").datepicker({
    //        dateFormat  : "yy-mm-dd",
    //        changeMonth : true,
    //        changeYear  : true         
    // });
    
    $('#tgltransaksix1').on('click',function(){
        $('#cek1').val('');
       var x = $('#tgltransaksix1').val().length;
       if(x>=10){
           $( "#nomorbuktix1" ).prop( "disabled", false ); 
           $( "#btn_nomorbuktix1" ).prop( "disabled", false );         
       }else{
           $( "#nomorbuktix1" ).prop( "disabled", true ); 
           $( "#btn_nomorbuktix1" ).prop( "disabled", true );         
       }
       setTimeout(() => {
           kirimsyarat();
           setTimeout(() => {
                    tampil_datatable();    
                }, 200);
       }, 200);
       let idx = $('#idjenispinjamanx1').val();
       setTimeout(() => {
            data_jenispinjaman(idx)
        }, 500); 
        
       
        
    });
    
    $('#tgltransaksix1').on('change',function(){
       $('#cek1').val('');				
       var x = $('#tgltransaksix1').val().length;
       if(x>=10){           
           $( "#nomorbuktix1" ).prop( "disabled", false ); 
           $( "#btn_nomorbuktix1" ).prop( "disabled", false );         
       }else{
           $( "#nomorbuktix1" ).prop( "disabled", true ); 
           $( "#btn_nomorbuktix1" ).prop( "disabled", true );         
       }  	
       setTimeout(() => {
           kirimsyarat();
           setTimeout(() => {
                    tampil_datatable();    
                }, 200);
       }, 200);
       let idx = $('#idjenispinjamanx1').val();
       setTimeout(() => {
            data_jenispinjaman(idx)
        }, 500);


    });
    
    $("#btn_nomorbuktix1").on('click',function(){ 
        $('#cek1').val('');
        nomorbukti();
        setTimeout(() => {
           kirimsyarat();
           setTimeout(() => {
                tampil_datatable();    
            }, 200);
       }, 200);              
    });
    
    $("#nomorbuktix1").on('keyup',function(){
        $('#cek1').val('');  
       setTimeout(() => {
           kirimsyarat();
           setTimeout(() => {                            
               setTimeout(() => {
                    tampil_datatable();    
                }, 200);
           }, 100);        
       }, 100);
    });
    $("#nomorbuktix1").on('change',function(){  
        $('#cek1').val('');
       setTimeout(() => {
           kirimsyarat();
           setTimeout(() => {           
               setTimeout(() => {
                    tampil_datatable();    
                }, 200);               
           }, 100);        
       }, 100);
    });
    
    $('#idjenispinjamanx1').on('click',function(){
        kirimsyarat();
        setTimeout(() => {
            listcoa1Datatable = tampil_listcoa1();
            carianggotaDatatable = tampil_data_carianggota();  
            setTimeout(() => {
                listcoa1Datatable.ajax.url('{{route('pinjaman01.transaksi.jurnalangsuran_listcoa')}}').load();                
                listcoa1Datatable.draw(null, false);
                carianggotaDatatable.ajax.url('{{route('pinjaman01.transaksi.jurnalangsuran_showanggota')}}').load();                
                carianggotaDatatable.draw(null, false);

            }, 100);
        }, 100);
       let idx = $('#idjenispinjamanx1').val();
       setTimeout(() => {
            data_jenispinjaman(idx)
        }, 500);					
    });
    
    $('#idjenispinjamanx1').on('change',function(){
        
        let idx = $('#idjenispinjamanx1').val();
        setTimeout(() => {
            data_jenispinjaman(idx)
        }, 500);					
    });

    function data_jenispinjaman(idx){
        var id1=idx;
        if(id1==''){
            id1=0;
        }			
            $.ajax({
                type  : 'get',
                url   : `{{ url('pinjaman01/transaksi/jurnalangsuranjenispinjaman')}}/${id1}`,
                async : false,
                dataType : 'json',	
                
                success : function(data){
                    var i;                
                    var resultData = data.data;	                			
                    for(i=0; i<resultData.length; i++){
    
                        // $('#ujrohax1').val(resultData[i].ujroha);
                        // $('#ujrohbx1').val(resultData[i].ujrohb);
                           
                    }
                    
                },
                error : function(data){
                    // alert(id1);
                }
            }); 
    }

    setTimeout(() => {
        var x = $('#tgltransaksix1').val().length;
        if(x>=10){
            $( "#nomorbuktix1" ).prop( "disabled", false ); 
            $( "#btn_nomorbuktix1" ).prop( "disabled", false );         
        }else{
            $( "#nomorbuktix1" ).prop( "disabled", true ); 
            $( "#btn_nomorbuktix1" ).prop( "disabled", true );         
        }
        $('#idjenispinjamanx1').click(); 
    }, 2000);
    
    function tampil_datatable(){        
        data1Datatable.draw(null, false);               
    }

    function kirimsyarat(){        
        var tgltransaksix1=$('#tgltransaksix1').val();
        var nomorbuktix1=$('#nomorbuktix1').val();
        var idjenispinjamanx1=$('#idjenispinjamanx1').val();
        var idanggotax1=$('#idanggotax1').val();
        var niax1=$('#niax1').val();
        var namax1=$('#namax1').val();
        var kodepinjamanx1=$('#kodepinjamanx1').val();

        let formData = new FormData();
            formData.append('tgltransaksix1', tgltransaksix1);
            formData.append('nomorbuktix1', nomorbuktix1);
            formData.append('idjenispinjamanx1', idjenispinjamanx1);
            formData.append('idanggotax1', idanggotax1);
            formData.append('niax1', niax1);
            formData.append('namax1', namax1);
            formData.append('kodepinjamanx1', kodepinjamanx1);
    
        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pinjaman01.transaksi.jurnalangsuran_kirimsyarat')}}',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },				 				
            success : function(formData){                                                       
                tampil_datatable();                
                    

                },
            error : function(formData){                                                       
                // alert('error');
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
    
    $('#btn_tambah1').on('click',function(){
        let cek = $('#cek1').val();
        if(cek==''){

            let x = $('#nomorbuktix1').val();         
    
            if(x==''){
                let comp = 'Please complete the data...!';             
                swaltidaklengkap(comp);
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
           swalpraposting('','Maaf, data sudah diposting.....'); 
        }

        
    }); 

    $("#debet1").on('change',function(){
        var x = $('#debet1').val().replace(/[^,\d]/g, '').toString();
        if (x==''){
            $("#debet1").val('0');
        }
        var y = parseFloat(x);
        if (y>=999999999999999){
            $("#debet1").val(y);
        }
        if (y>0){
            $("#kredit1").val('0');
            $("#terbilangk1").html('Nol');
        } 
        var x1 = terbilang(x);
        $("#terbilangd1").html(x1);       
    });
    $("#debet1").on('keydown',function(){
        var x = $('#debet1').val().replace(/[^,\d]/g, '').toString();
        if (x==''){
            $("#debet1").val('0');
        }
        var y = parseFloat(x);
        if (y>=999999999999999){
            $("#debet1").val(y);
        } 
        if (y>0){
            $("#kredit1").val('0');
            $("#terbilangk1").html('Nol');
        }
        var x1 = terbilang(x);
        $("#terbilangd1").html(x1);       
    });

    $("#kredit1").on('change',function(){
        var x = $('#kredit1').val().replace(/[^,\d]/g, '').toString();
        if (x==''){
            $("#kredit1").val('0');
        }
        var y = parseFloat(x);
        if (y>=999999999999999){
            $("#kredit1").val(y);
        } 
        if (y>0){
            $("#debet1").val('0');
            $("#terbilangd1").html('Nol');
        }
        var x1 = terbilang(x);
        $("#terbilangk1").html(x1);       
    });
    $("#kredit1").on('keydown',function(){
        var x = $('#kredit1').val().replace(/[^,\d]/g, '').toString();
        if (x==''){
            $("#kredit1").val('0');
        }
        var y = parseFloat(x);
        if (y>=999999999999999){
            $("#kredit1").val(y);
        } 
         if (y>0){
            $("#debet1").val('0');
            $("#terbilangd1").html('Nol');
        }
        var x1 = terbilang(x);
        $("#terbilangk1").html(x1);       
    });

    $("#nilaiangsuran1").on('change',function(){
        var x = $('#nilaiangsuran1').val().replace(/[^,\d]/g, '').toString();
        if (x==''){
            $("#nilaiangsuran1").val('0');
        }
        var y = parseFloat(x);
        if (y>=999999999999999){
            $("#nilaiangsuran1").val(y);
        } 
         
        var x1 = terbilang(x);
        $("#terbilangnilaiangsuran1").html(x1);       
    });
    $("#nilaiangsuran1").on('keydown',function(){
        var x = $('#nilaiangsuran1').val().replace(/[^,\d]/g, '').toString();
        if (x==''){
            $("#nilaiangsuran1").val('0');
        }
        var y = parseFloat(x);
        if (y>=999999999999999){
            $("#nilaiangsuran1").val(y);
        } 
         
        var x1 = terbilang(x);
        $("#terbilangnilaiangsuran1").html(x1);       
    });
    $("#ujroh1").on('change',function(){
        var x = $('#ujroh1').val().replace(/[^,\d]/g, '').toString();
        if (x==''){
            $("#ujroh1").val('0');
        }
        var y = parseFloat(x);
        if (y>=999999999999999){
            $("#ujroh1").val(y);
        } 
         
        var x1 = terbilang(x);
        $("#terbilangujroh1").html(x1);       
    });
    $("#ujroh1").on('keydown',function(){
        var x = $('#ujroh1').val().replace(/[^,\d]/g, '').toString();
        if (x==''){
            $("#ujroh1").val('0');
        }
        var y = parseFloat(x);
        if (y>=999999999999999){
            $("#ujroh1").val(y);
        } 
         
        var x1 = terbilang(x);
        $("#terbilangujroh1").html(x1);       
    });

    $("#btn_nilaiangsuran1").on('click',function(){
        let d = $('#debet1').val().replace(/[^,\d]/g, '').toString(); 
        let xa = $('#xangsuran1').val().replace(/[^,\d]/g, '').toString();
        let dx = parseFloat(d); 
        let xax = parseFloat(xa); 
        let hasilx = formatAngka(dx/xax,'');
        $("#nilaiangsuran1").val(hasilx);
        $("#terbilangnilaiangsuran1").html(terbilang(hasilx)); 
                    
    });

    $("#btn_ujroh1").on('click',function(){
        let d = $('#debet1').val().replace(/[^,\d]/g, '').toString(); 
        let ua = $('#ujrohax1').val().replace(/[^,\d]/g, '').toString(); 
        let ub = $('#ujrohbx1').val().replace(/[^,\d]/g, '').toString(); 
        let t = $('#tipe1').val().replace(/[^,\d]/g, '').toString();
        let dx = parseFloat(d); 
        let uax = parseFloat(ua); 
        let ubx = parseFloat(ub); 
        let tx = parseFloat(t); 
        let hasilx = formatAngka((uax/ubx)*tx*dx,'');
        $("#ujroh1").val(hasilx);
        $("#terbilangujroh1").html(terbilang(hasilx)); 
                    
    });
    
    function data_simpan(){
        
        var id1=$('#id1').val();			
        var tgltransaksix1=$('#tgltransaksix1').val();
        var nomorbuktix1=$('#nomorbuktix1').val();
        var idjenispinjamanx1=$('#idjenispinjamanx1').val();
        var idanggotax1=$('#idanggotax1').val();
        var idtargetx1=$('#idtargetx1').val();
        var kodepinjamanx1=$('#kodepinjamanx1').val();
        var idsandi1=$('#idsandi1').val();
        var idcoa1=$('#idcoa1').val();
        var idjenisjurnal1=$('#idjenisjurnal1').val();
        var debet1=$('#debet1').val().replace(/[^,\d]/g, '').toString();
        var kredit1=$('#kredit1').val().replace(/[^,\d]/g, '').toString();
        var ke1=$('#ke1').val().replace(/[^,\d]/g, '').toString();
        var tipe1=$('#tipe1').val().replace(/[^,\d]/g, '').toString();
        var xangsuran1=$('#xangsuran1').val().replace(/[^,\d]/g, '').toString();
        var nilaiangsuran1=$('#nilaiangsuran1').val().replace(/[^,\d]/g, '').toString();
        var ujroh1=$('#ujroh1').val().replace(/[^,\d]/g, '').toString();
        var jatuhtempo1=$('#jatuhtempo1').val();
        var keterangan1=$('#keterangan1').val();
        
        let formData = new FormData();
            formData.append('id1', id1);
            formData.append('tgltransaksix1', tgltransaksix1);
            formData.append('nomorbuktix1', nomorbuktix1);
            formData.append('idjenispinjamanx1', idjenispinjamanx1);
            formData.append('idanggotax1', idanggotax1);
            formData.append('idtargetx1', idtargetx1);
            formData.append('kodepinjamanx1', kodepinjamanx1);
            formData.append('idsandi1', idsandi1);
            formData.append('idcoa1', idcoa1);
            formData.append('idjenisjurnal1', idjenisjurnal1);
            formData.append('debet1', debet1);
            formData.append('kredit1', kredit1);
            formData.append('ke1', ke1);            
            formData.append('tipe1', tipe1);            
            formData.append('xangsuran1', xangsuran1);            
            formData.append('nilaiangsuran1', nilaiangsuran1);            
            formData.append('ujroh1', ujroh1);            
            formData.append('jatuhtempo1', jatuhtempo1);            
            formData.append('keterangan1', keterangan1);            
          
        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pinjaman01.transaksi.jurnalangsuran_create')}}',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },				 				
            success : function(formData){                                         
                    tampil_datatable();
                    btn_simpan_click();
                    if(id1>0){
                        $('#ModalAdd').modal('hide'); 
                    }
                },
            error : function(formData){                    
                swalgagaltambah(nomorbuktix1);                 
                }
        });

    }   

    $("#btn_simpan").on('click',function(){
        
        let a = $('#tgltransaksix1').val();
        const aArray = a.split("-");
        let thn = aArray[0];
        let bln = aArray[1];
        let tgl = aArray[2];
        let b = thn+bln+tgl;

        let x = $('#nomorbuktix1').val();
        const xArray = x.split(".");
        let y = xArray[0];
        let z = xArray[2];
        let j = xArray[3];
        let k = j.length;

        if(y=='ANG'&&b==z&&k=='4'){            
            data_simpan();
            setTimeout(() => {
                tampil_datatable();
            }, 300); 
        }else{            
            swalnomorbuktisalah(x);            
        }
       	
    });

    function nomorbukti(){        
        var tgltransaksix1=$('#tgltransaksix1').val();

        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pinjaman01.transaksi.jurnalangsuran_nomorbukti')}}',
            async : false,
            dataType : 'json',
            // data : FormData,
            data : {tgltransaksix1:tgltransaksix1},
            // cache: false,
            // processData: false,
            // contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },				 				
            success : function(data){ 
                
                var resultData = data.data;	                
                    $('#nomorbuktix1').val(resultData[0].nomorbukti); 
                                                          
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
            url    : '{{route('pinjaman01.transaksi.jurnalangsuran_nomorposting')}}',
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

    $('#show_data1').on('click','.item_edit',function(){
        $("#iconx").removeClass("fas fa-plus-square");
        $("#iconx").addClass("fas fa-edit");
        $("#modalx").removeClass("modal-content bg-primary w3-animate-zoom");
        $("#modalx").addClass("modal-content bg-success w3-animate-zoom");
        $('#judulx').html(' Edit Data');
        btn_edit_click();

        var id1=$(this).attr('data');

        $('#id1').val(id1);
        // data_edit(id1);
        
        let kode1 = $(this).attr('data2');
        let idcoa1 = $(this).attr('data3');
        let idsandi1 = $(this).attr('data4');
        let idjenisjurnal1 = $(this).attr('data5');
        let idjenispinjaman1 = $(this).attr('data6');
        let idanggota1 = $(this).attr('data7');
        let idtarget1 = $(this).attr('data8');
        let nama1 = $(this).attr('data9');
        let nia1 = $(this).attr('data10');
        let tgltransaksi1 = $(this).attr('data11');
        let nomorbukti1 = $(this).attr('data12');
        let debet1 = $(this).attr('data13');
        let kredit1 = $(this).attr('data14');
        let tipe1 = $(this).attr('data15');
        let xangsuran1 = $(this).attr('data16');
        let nilaiangsuran1 = $(this).attr('data17');
        let ke1 = $(this).attr('data18');
        let ujroh1 = $(this).attr('data19');
        let jatuhtempo1 = $(this).attr('data20');
        let keterangan1 = $(this).attr('data21');

        // $('#cariidx1').val(nia1);
        // $('#niax1').val(nia1);
        // $('#namax1').val(nama1);
        $('#kodepinjamanx1').val(kode1);

        $('#idcoa1').val(idcoa1);
        $('#idsandi1').val(idsandi1);
        $('#idjenisjurnal1').val(idjenisjurnal1);
        $('#debet1').val(formatAngka(debet1,''));
        if(debet1=='0'){
            $('#terbilangd1').html('Nol');    
        }else{
            $('#terbilangd1').html(terbilang($('#debet1').val()));
        }
        $('#kredit1').val(formatAngka(kredit1,''));
        if(kredit1=='0'){
            $('#terbilangk1').html('Nol');    
        }else{
            $('#terbilangk1').html(terbilang($('#kredit1').val()));
        }

        $('#tipe1').val(tipe1);
        $('#ke1').val(ke1);
        $('#xangsuran1').val(xangsuran1);
        $('#nilaiangsuran1').val(formatAngka(nilaiangsuran1,''));
        if(nilaiangsuran1=='0'){
            $('#terbilangnilaiangsuran1').html('Nol');    
        }else{
            $('#terbilangnilaiangsuran1').html(terbilang($('#nilaiangsuran1').val()));
        }
        $('#ujroh1').val(formatAngka(ujroh1,''));
        if(ujroh1=='0'){
            $('#terbilangujroh1').html('Nol');    
        }else{
            $('#terbilangujroh1').html(terbilang($('#ujroh1').val()));
        }

        $('#jatuhtempo1').val(jatuhtempo1);
        $('#keterangan1').val(keterangan1);

        $('#ModalAdd').modal('show');         
    });

    $('#show_data1').on('click','.item_posting',function(){
        var id1 = $(this).attr('data'); 
        var tgltransaksi5 = $(this).attr('data3'); 
        var tgltransaksi5x = $(this).attr('data4'); 
        var nomorbukti5 = $(this).attr('data5'); 
        var jml5 = formatAngka(parseFloat($(this).attr('data6')),'');

        setTimeout(() => {
            nomorposting();
            setTimeout(() => {
                $('#id1').val(id1);        		
                $('#tgltransaksi5').text(tgltransaksi5);        		
                $('#tgltransaksi5x').text(tgltransaksi5x);        		
                $('#nomorbukti5').text(nomorbukti5);        		                       		
                $('#jml5').text(jml5);        		                       		
                $('#ModalPosting').modal('show');						                
            }, 10);
        }, 200);
    });

    $("#btn_update").on('click',function(){	        
        data_simpan();
    });                                         

    function data_edit(idx){
        var id1=idx;			
            $.ajax({
                type  : 'get',
                url   : `{{ url('pinjaman01/transaksi/jurnalangsuranedit')}}/${id1}`,
                async : false,
                dataType : 'json',	
                
                success : function(data){
                    var i;                
                    var resultData = data.data;	                			
                    for(i=0; i<resultData.length; i++){
    
                        $('#tgltransaksix1').val(resultData[i].tgltransaksi);
                        $('#nomorbuktix1').val(resultData[i].nomorbukti);
                        $('#kodepinjamanx1').val(resultData[i].kode);
                        $('#namax1').val(resultData[i].anggota.nama);
                        $('#cariidx1').val(resultData[i].anggota.nix);
                        $('#idanggotax1').val(resultData[i].idanggota);
                        $('#idjenispinjamanx1').val(resultData[i].idjenispinjaman);
                        
                        $('#idsandi1').val(resultData[i].idsandi);
                        $('#idcoa1').val(resultData[i].idcoa);
                        $('#idjenisjurnal1').val(resultData[i].idjenisjurnal);
                        $('#debet1').val(formatAngka(resultData[i].debet,''));
                        $('#kredit1').val(formatAngka(resultData[i].kredit,''));
                        
                        var x = $('#debet1').val().replace(/[^,\d]/g, '').toString();
                        if (x==''){
                            $("#debet1").val('0');
                        }
                        var y = parseFloat(x);
                        if (y>=999999999999999){
                            $("#debet1").val(y);
                        } 
                        if(y==0){
                            $("#terbilangd1").html('Nol');
                        }else{
                            $("#terbilangd1").html(terbilang(x));
                        }

                        var x = $('#kredit1').val().replace(/[^,\d]/g, '').toString();
                        if (x==''){
                            $("#kredit1").val('0');
                        }
                        var y = parseFloat(x);
                        if (y>=999999999999999){
                            $("#kredit1").val(y);
                        } 
                        
                        if(y==0){
                            $("#terbilangk1").html('Nol');
                        }else{
                            $("#terbilangk1").html(terbilang(x));
                        }

                        $('#tipe1').val(resultData[i].tipe);    
                        $('#xangsuran1').val(formatAngka(resultData[i].xangsuran,''));    
                        $('#nilaiangsuran1').val(formatAngka(resultData[i].nilaiangsuran,''));
                        var x = $('#nilaiangsuran1').val().replace(/[^,\d]/g, '').toString();
                        if (x==''){
                            $("#nilaiangsuran1").val('0');
                        }
                        var y = parseFloat(x);
                        if (y>=999999999999999){
                            $("#nilaiangsuran1").val(y);
                        } 
                        if(y==0){
                            $("#terbilangnilaiangsuran1").html('Nol');
                        }else{
                            $("#terbilangnilaiangsuran1").html(terbilang(x));
                        }    
                        $('#ujroh1').val(formatAngka(resultData[i].ujroh,''));
                        var x = $('#ujroh1').val().replace(/[^,\d]/g, '').toString();
                        if (x==''){
                            $("#ujroh1").val('0');
                        }
                        var y = parseFloat(x);
                        if (y>=999999999999999){
                            $("#ujroh1").val(y);
                        } 
                        if(y==0){
                            $("#terbilangujroh1").html('Nol');
                        }else{
                            $("#terbilangujroh1").html(terbilang(x));
                        }     
                        $('#jatuhtempo1').val(resultData[i].jatuhtempo);    
                        $('#keterangan1').val(resultData[i].keterangan);    
                    }
                    
                },
                error : function(data){
                    alert(id1);
                }
            }); 
    }
    
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
        var tgltransaksi1=$('#tgltransaksi5').text();
        var nomorbukti1=$('#nomorbukti5').text();
        var tglposting1=$('#tglposting5').text();
        var nomorposting1=$('#nomorposting5').text();        
        var idjenispinjaman1=$('#idjenispinjamanx1').val();        
        var nama1=$('#namax1').val();        
    
        let formData = new FormData();
            formData.append('tgltransaksi1', tgltransaksi1);            
            formData.append('nomorbukti1', nomorbukti1);            
            formData.append('tglposting1', tglposting1);
            formData.append('nomorposting1', nomorposting1);            
            formData.append('idjenispinjaman1', idjenispinjaman1);            
            formData.append('nama1', nama1);            
          
        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pinjaman01.transaksi.jurnalangsuran_posting')}}',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },				 				
            success : function(formData){ 
                    tampil_datatable();                                        
                    swalposting(nomorposting1);
                    $('#ModalPosting').modal('hide');
                },
            error : function(formData){ 
                    swalgagalposting(nomorposting1);                                                        
                }
        });
    }

    $('#show_data1').on('click','.item_hapus',function(){
        var id3=$(this).attr('data');
        var data3a=$(this).attr('data2');
        var data3b=$(this).attr('data3');
        var data3c=$(this).attr('data4');
        
        $('#id3').val(id3);
        $('#data3a').val(data3a + ' (' + data3c + ')');
        $('#data3b').val(data3b);
        $('#data3c').val(data3c);
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
                setTimeout(() => {
                    tampil_datatable();
                }, 300);            
            }
        })
    } 

    function data_hapus(){			
        var id3=$('#id3').val();       
        var data3a=$('#data3a').val();
        
        $.ajax({
            type  : 'get',
            url   : '{{url('pinjaman01/transaksi/jurnalangsurandestroy')}}/'+id3,
            async : false,
            dataType : 'json',					
            success : function(data){
                tampil_data();
                swalhapus(data3a); 
            },
            error : function(data){
                swalgagalhapus(data3a); 
            }
        }); 

    }

    $('#show_data1').on('click','.item_print',function(){ 
        $('#cekprint').val("1");
        var tglposting=$(this).attr('data7');
        $('#data3z').val(tglposting);
        var id1=$(this).attr('data');
        $('#id1').val(id1);
        kirimsyarat();
        setTimeout(() => {
            window.open('{{ route('pinjaman01.transaksi.jurnalangsuran_printkwitansi') }}');
        }, 100);
    });

    $('#show_data1').on('click','.item_printxxx',function(){
        var id=$(this).attr('data');
        var nomorstatus=$(this).attr('data2');
        $('#id6').val(id);
        $('#cekprint6').val('1');
        $('#nomorstatus6').text(nomorstatus);

        // kirimsyarat();
        setTimeout(() => {
            $('#ModalPrintdetail').modal('show');
        }, 200);
    });

    $("#noprint6").on('click',function(){
        var x = $("#noprint6").val();
        if(x<='0'){
            $("#noprint6").val('1')
        }
        $('#cekprint6').val('1');
        kirimsyarat();
    });

    $("#noprint6").on('keyup',function(){
        var x = $("#noprint6").val();
        if(x<='0'){
            $("#noprint6").val('1')
        }
        $('#cekprint6').val('1');
        kirimsyarat();
    });

    $("#btn_print6").on('click',function(){ 
        $('#ModalPrintdetail').modal('hide');
        window.open('{{ route('simpanan01.laporan.rekeningkoran_printdetail') }}');
    });

    function swalgagalnorek(x){
        Swal.fire({
            icon: 'info',
            title: 'Oops...not found record',
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

    function swalpraposting(x,y){
        Swal.fire({
            icon: 'info',
            title: y,
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

    function swaltidaklengkap(x){
        Swal.fire({
            icon: 'error',
            title: 'Oops...incomplete data',
            text: x,
            timer:5000
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

   

});

</script>	



@endsection