@extends('admin.layouts.main')

@section('contents')

@php
    $iddesa1 = session('iddesa1');    
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
    #gambarimg_foto1:hover img{
        transform: scale(1.2)
    }
    #gambarimg_foto1 img{
        transition: all 0.4s ease 0s;
    }

    #gambarimg_kk1:hover iframe{
        transform: scale(1.1)
    }
    #gambarimg_kk1 iframe{
        transition: all 0.4s ease 0s;
    }
    #gambarimg_ktp1:hover iframe{
        transform: scale(1.1)
    }
    #gambarimg_ktp1 iframe{
        transition: all 0.4s ease 0s;
    }
    #gambarimg_bukunikah1:hover iframe{
        transform: scale(1.1)
    }
    #gambarimg_bukunikah1 iframe{
        transition: all 0.4s ease 0s;
    }
    #gambarimg_nomorbuktiud1:hover iframe{
        transform: scale(1.1)
    }
    #gambarimg_nomorbuktiud1 iframe{
        transition: all 0.4s ease 0s;
    }
    
    
</style>


    <div class="box-header mb-3">  
        <div class="row">
            <div class="col-md-6">
                                
            </div>

            <div class="col-md-6">
                <div class="w3-row" align="right"><i class="fa fa-refresh" aria-hidden="true"></i>            
                    <a id="btn_refresh1" name="btn_refresh1" href="{{ url('/') }}{{ $link }}" class="btn bg-success rounded-0" style="text-decoration: none;"><i style="font-size:18px" class="fa">&#xf021;</i> Refresh</a>            
                    <button id="btn_tambah1" name="btn_tambah1" type="button" class="btn bg-primary rounded-0"><i class="fas fa-plus"></i> Tambah</button>
                </div> 
            </div>
        </div>

    </div>

    <ul class="nav nav-tabs" id="tab-anggota" role="tablist">
        <li class="nav-item">
            <a class="nav-link" id="tab-identitas" data-toggle="pill" href="#isi-tab-identitas" role="tab" aria-controls="tab-identitas" aria-selected="true">Identitas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-syarat" data-toggle="pill" href="#isi-tab-syarat" role="tab" aria-controls="tab-syarat" aria-selected="false">Persyaratan</a>
        </li>
    </ul>

    <!--awal tabel-->        
    <div class="box-body" id="headerjudul" style="display: block;">
        <div class="tab-content mt-3" id="tab-anggota-tabContent">

            <!--tab-identitas -->
            <div class="tab-pane fade" id="isi-tab-identitas" role="tabpanel" aria-labelledby="tab-identitas">
                <div id="reload" class="table-responsive">
                    <table id="identitas1" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>                            
                                <th style="width:20px">Tgl.Daftar</th>							
                                <th style="width:20px">NIA</th>							
                                <th style="width:20px">NIK</th>							
                                <th style="width:20px">Ecard</th>							
                                <th style="width:200px">Nama</th>
                                <th style="width:200px">Alamat</th>
                                <th style="width:50px">Desa</th>							
                                <th style="width:50px">Kecamatan</th>							
                                <th style="width:50px">Kabupaten</th>							
                                <th style="width:50px">Propinsi</th>							
                                <th style="width:50px">Telp.</th>							
                                <th style="width:50px">Email</th>							
                                <th style="width:20px">Tgl.Lahir</th>							
                                <th style="width:2S0px">Tgl.Keluar</th>							
                                <th style="width:10px">Aktif</th>							
                                <th style="width:10px">Action</th>
                            </tr>
                        </thead>
                        <tfoot id="show_footeridentitas1">
                            
                        </tfoot>
                        <tbody id="show_identitas1">
                        
                        </tbody>
                    </table>
                </div>
            </div>
            <!--/tab-identitas -->
           
            <!--tab-syarat -->
            <div class="tab-pane fade" id="isi-tab-syarat" role="tabpanel" aria-labelledby="tab-syarat">
                <div id="reload" class="table-responsive">
                    <table id="syarat1" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>                            
                                <th style="width:20px">NIA</th>							
                                <th style="width:200px">Nama</th>
                                <th style="width:50px">Foto</th>							
                                <th style="width:50px">KTP</th>							
                                <th style="width:50px">KK</th>							
                                <th style="width:50px">Buku Nikah</th>							
                                <th style="width:50px">Uang Daftar</th>							
                                <th style="width:50px">No.Bukti</th>							
                                <th style="width:10px">Action</th>
                            </tr>
                        </thead>
                        <tfoot id="show_footersyarat1">
                            
                        </tfoot>
                        <tbody id="show_syarat1">
                        
                        </tbody>
                    </table>
                </div>
            </div>
            <!--/tab-syarat -->

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
                            <ul class="nav nav-tabs" id="tab-anggota2" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link w3-button w3-border w3-border-white" id="tab-identitas2" data-toggle="pill" href="#isi-tab-identitas2" role="tab" aria-controls="tab-identitas2" aria-selected="true">Identitas</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link w3-button w3-border w3-border-white" id="tab-syarat2" data-toggle="pill" href="#isi-tab-syarat2" role="tab" aria-controls="tab-syarat2" aria-selected="false">Persyaratan</a>
                                </li>
                            </ul>

                            <div class="tab-content mt-2" id="tab-anggota1-tabContent">
                                <!--tab-identitas2 -->
                                <div class="tab-pane fade" id="isi-tab-identitas2" role="tabpanel" aria-labelledby="tab-identitas2">

                                    <div class="row">
                                        <div class="col-md-4" align="right">									
                                            <h6 class="mt-2">Tgl.Daftar *)</h6>
                                        </div>

                                        <div class="col-md-8">                                
                                            <input name="tgldaftar1" id="tgldaftar1" class="w3-input w3-border" type="text" maxlength="10" placeholder="tgl daftar" value="{{ old('tgldaftar1') }}" required>
                                            <input name="cek1" id="cek1" class="" type="hidden">                                
                                            <input name="id1" id="id1" class="" type="hidden">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mt-0" align="right">									
                                            <h6 class="mt-2">NIA *)</h6>
                                        </div>
                                        <div class="col-md-8 input-group mt-0">
                                            <input name="nia1" id="nia1" class="form-control w3-input w3-border rounded-0" type="search" placeholder="202509050001"  value="{{ old('nia1') }}">                        
                                            <div class="input-group-append">
                                                <button id="btn_nia1" name="btn_nia1" type="button" style="border-radius:0px; border:none;" title="Generate NIA" ><i style="font-size:24" class="fa">&#xf013;</i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mt-1" align="right">									
                                            <h6 class="mt-2">NIK *)</h6>
                                        </div>
                                        <div class="col-md-8">                                
                                            <input name="nik1" id="nik1" class="w3-input w3-border" type="text" maxlength="30" placeholder="NIK" value="{{ old('nik1') }}" required>
                                        </div>								  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mt-1" align="right">									
                                            <h6 class="mt-2">Ecard *)</h6>
                                        </div>
                                        <div class="col-md-8">                                
                                            <input name="ecard1" id="ecard1" class="w3-input w3-border" type="text" maxlength="30" placeholder="ecard" value="{{ old('ecard1') }}" required>
                                        </div>								  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mt-1" align="right">									
                                            <h6 class="mt-2">Nama *)</h6>
                                        </div>
                                        <div class="col-md-8">                                
                                            <input name="nama1" id="nama1" class="w3-input w3-border" type="text" maxlength="200" placeholder="nama" value="{{ old('nama1') }}" required>
                                        </div>								  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mt-1" align="right">									
                                            <h6 class="mt-2">Alamat</h6>
                                        </div>
                                        <div class="col-md-8">                                
                                            <input name="alamat1" id="alamat1" class="w3-input w3-border" type="text" maxlength="100" placeholder="alamat" value="{{ old('alamat1') }}">
                                        </div>								  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mt-0" align="right">									
                                            <h6 class="mt-2">Desa *)</h6>
                                        </div>
                                        <select name="iddesa1" id="iddesa1" class="js-select-auto__select form-control" style="border-radius:0px; height:40px; display: none;" autocomplete="false">
                                            </select>
                                        <div class="col-md-8 input-group mt-0">
                                            <input name="desa1" id="desa1" class="form-control w3-input w3-border rounded-0" type="text" placeholder="desa"  value="{{ old('desa1') }}">                        
                                            <div class="input-group-append">
                                                <button id="btn_caridesa1" name="btn_caridesa1" type="button" style="border-radius:0px; border:none;" title="Cari desa" ><i style="font-size:24" class="fas">&#xf002;</i></button>
                                            </div>
                                        </div>
                                    </div>                                    
                                    <div class="row">
                                        <div class="col-md-4 mt-1" align="right">									
                                            <h6 class="mt-2">Kecamatan</h6>
                                        </div>
                                        <div class="col-md-8">                                
                                            <input name="kecamatan1" id="kecamatan1" class="w3-input w3-border" type="text" maxlength="50" placeholder="kecamatan" value="{{ old('kecamatan1') }}" readonly>
                                        </div>								  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mt-1" align="right">									
                                            <h6 class="mt-2">Kabupaten</h6>
                                        </div>
                                        <div class="col-md-8">                                
                                            <input name="kabupaten1" id="kabupaten1" class="w3-input w3-border" type="text" maxlength="50" placeholder="kabupaten" value="{{ old('kabupaten1') }}" readonly>
                                        </div>								  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4" align="right">									
                                            <h6 class="mt-2">Propinsi</h6>
                                        </div>
                                        <div class="col-md-8">                                
                                            <input name="propinsi1" id="propinsi1" class="w3-input w3-border" type="text" maxlength="50" placeholder="Propinsi" value="{{ old('propinsi1') }}" readonly>
                                        </div>
                                    </div> 
                                    
                                    <div class="row">
                                        <div class="col-md-4" align="right">										
                                            <h6 class="mt-2">Telp</h6>
                                        </div>
                                        <div class="col-md-8">                                
                                            <input name="telp1" id="telp1" class="w3-input w3-border" type="text" maxlength="20" placeholder="telp" value="{{ old('telp1') }}">
                                        </div>								  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4" align="right">										
                                            <h6 class="mt-2">Email</h6>
                                        </div>
                                        <div class="col-md-8">                                
                                            <input name="email1" id="email1" class="w3-input w3-border" type="email" maxlength="50" placeholder="email" value="{{ old('email1') }}">
                                        </div>								  
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4" align="right">										
                                            <h6 class="mt-2">Tgl.Lahir</h6>
                                        </div>
                                        <div class="col-md-8">                                
                                            <input name="tgllahir1" id="tgllahir1" class="w3-input w3-border" type="search" maxlength="10" placeholder="tgl lahir" value="{{ old('tgllahir1') }}">
                                        </div>								  
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4" align="right">										
                                            <h6 class="mt-2">Tgl.Keluar</h6>
                                        </div>
                                        <div class="col-md-8">                                
                                            <input name="tglkeluar1" id="tglkeluar1" class="w3-input w3-border" type="search" maxlength="10" placeholder="tgl keluar" value="{{ old('tglkeluar1') }}">
                                        </div>								  
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4" align="right">									
                                            <h6 class="mt-2">Aktif</h6>
                                        </div>            
                                        <div class="col-md-8 mt-1" style="padding-left: 20px;">
                                            <div class="icheck-primary-white d-inline">
                                                <input type="radio" value='Y' id="aktif1xy" name="aktif1x" checked>
                                                <label for="aktif1xy">
                                                    <span class="text" style="padding-left: 2px; padding-right: 15px;">Y</span>
                                                </label>
                                            </div>
                                            <div class="icheck-primary-white d-inline text-white">
                                                <input type="radio" value='N' id="aktif1xn" name="aktif1x">
                                                <label for="aktif1xn">
                                                    <span class="text" style="padding-left: 2px; padding-right: 15px;">N</span>
                                                </label>
                                            </div>
                                            <input name="aktif1" id="aktif1" type="hidden" value="Y">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12" align="left" style="color: yellow;">									
                                            <h6 class="mt-2"><b>*) Wajib diisi</b></h6>
                                        </div>                                                        
                                    </div>
                                        
                                </div>
                                <!--/tab-identitas2 -->

                                <!--tab-syarat2 -->
                                <div class="tab-pane fade" id="isi-tab-syarat2" role="tabpanel" aria-labelledby="tab-syarat2">
                                        

                                        <div class="row">
                                            
                                            <div class="col-md-6 mt-1 mb-2">                                                
                                                <div class="row">                                                   
                                                    <div class="col-md-12">                                                        
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <h6 class="mt-2">Uang Pendaftaran</h6>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12 mb-0">
                                                                <input name="uangdaftar1" id="uangdaftar1" class="w3-input w3-border text-right" type="search" maxlength="15" placeholder="uang pendaftaran" value="0" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required>
                                                                <span id="terbilang1" name="terbilang1" style="color: yellow">nol</span>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <h6 class="mt-2">No.Bukti</h6>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <input name="nomorbuktiud1" id="nomorbuktiud1" class="w3-input w3-border" type="text" maxlength="text" placeholder="nomor bukti" value="{{ old('nomorbuktiud1') }}">
                                                            </div>
                                                        </div>
                                                    </div>								  
                                                </div>                                                                                                  
                                            </div>

                                            <div class="col-md-6 mt-1 mb-2">                                                
                                                <div class="row">                                                   
                                                    <div class="col-md-12">                                                        
                                                        <div class="w3-card w3-border text-center px-1 pt-2 pb-1" style="background-color:aqua; color:black;">                                              
                                                            <h6>Nomor Bukti (*.pdf)</h6>
                                                        </div>
                                                        <input name="img_nomorbuktiud1" id="img_nomorbuktiud1" type="file" class="w3-input w3-border mb-1">
                                                        <input name="img_nomorbuktiud1x" id="img_nomorbuktiud1x" type="hidden">
                                                        <div class="w3-card w3-border" style="min-height:200px; height: auto;">                                              
                                                            <div id="gambarimg_nomorbuktiud1" name="gambarimg_nomorbuktiud1" class="mb-1" style="width: 100%;max-height: 200px; overflow:hidden;">                                                                         
                                                            </div> 
                                                        </div>
                                                    </div>								  
                                                </div>                                                                                                  
                                            </div>
                                            <div class="col-md-6 mt-1 mb-2">                                                
                                                <div class="row">                                                   
                                                    <div class="col-md-12">                                                        
                                                        <div class="w3-card w3-border text-center px-1 pt-2 pb-1" style="background-color: aqua; color:black;">                                              
                                                            <h6>Foto (*.jpg;jpeg;png;bmp)</h6>
                                                        </div>
                                                        <input name="img_foto1" id="img_foto1" type="file" class="w3-input w3-border mb-1">
                                                        <input name="img_foto1x" id="img_foto1x" type="hidden">
                                                        <div class="w3-card w3-border" style="min-height:200px; height: auto;">                                              
                                                            <div id="gambarimg_foto1" name="gambarimg_foto1" class="mb-1" style="width: 100%;max-height: 200px; overflow:hidden;">                                                                         
                                                            </div> 
                                                        </div>
                                                    </div>								  
                                                </div>                                                                                                  
                                            </div> 

                                            <div class="col-md-6 mt-1 mb-2">                                                
                                                <div class="row">                                                   
                                                    <div class="col-md-12">                                                        
                                                        <div class="w3-card w3-border text-center px-1 pt-2 pb-1" style="background-color: aqua; color:black;">                                              
                                                            <h6>KK (*.pdf)</h6>
                                                        </div>
                                                        <input name="img_kk1" id="img_kk1" type="file" class="w3-input w3-border mb-1">
                                                        <input name="img_kk1x" id="img_kk1x" type="hidden">
                                                        <div class="w3-card w3-border" style="min-height:200px; height: auto;">                                              
                                                            <div id="gambarimg_kk1" name="gambarimg_kk1" class="mb-1" style="width: 100%;max-height: 200px; overflow:hidden;">                                                                         
                                                            </div> 
                                                        </div>
                                                    </div>								  
                                                </div>                                                                                                  
                                            </div>
                                            
                                            <div class="col-md-6 mt-1 mb-2">                                                
                                                <div class="row">                                                   
                                                    <div class="col-md-12">                                                        
                                                        <div class="w3-card w3-border text-center px-1 pt-2 pb-1" style="background-color: aqua; color:black;">                                              
                                                            <h6>KTP (*.pdf)</h6>
                                                        </div>
                                                        <input name="img_ktp1" id="img_ktp1" type="file" class="w3-input w3-border mb-1">
                                                        <input name="img_ktp1x" id="img_ktp1x" type="hidden">
                                                        <div class="w3-card w3-border" style="min-height:200px; height: auto;">                                              
                                                            <div id="gambarimg_ktp1" name="gambarimg_ktp1" class="mb-1" style="width: 100%;max-height: 200px; overflow:hidden;">                                                                         
                                                            </div> 
                                                        </div>
                                                    </div>								  
                                                </div>                                                                                                  
                                            </div>
                                            
                                            <div class="col-md-6 mt-1 mb-2">                                                
                                                <div class="row">                                                   
                                                    <div class="col-md-12">                                                        
                                                        <div class="w3-card w3-border text-center px-1 pt-2 pb-1" style="background-color: aqua; color:black;">                                              
                                                            <h6>Buku Nikah (*.pdf)</h6>
                                                        </div>
                                                        <input name="img_bukunikah1" id="img_bukunikah1" type="file" class="w3-input w3-border mb-1">
                                                        <input name="img_bukunikah1x" id="img_bukunikah1x" type="hidden">
                                                        <div class="w3-card w3-border" style="min-height:200px; height: auto;">                                              
                                                            <div id="gambarimg_bukunikah1" name="gambarimg_bukunikah1" class="mb-1" style="width: 100%;max-height: 200px; overflow:hidden;">                                                                         
                                                            </div> 
                                                        </div>
                                                    </div>								  
                                                </div>                                                                                                  
                                            </div> 

                                        </div> 
                                    
                                    
                                </div>
                                <!--/tab-identitas2 -->

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

    <!-- ModalCariDesa1 modal fade-->
	<div class="modal fade" id="ModalCariDesa1"  data-backdrop="static">
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
							<div id="reloadcaridesa1" class="table-responsive">
                                <table id="caridesa1" class="table table-bordered table-striped table-hover" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th style="width:10px;">#</th>                            
                                            <th style="width:100px">Desa</th>							
                                            <th style="width:100px">Kecamatan</th>							
                                            <th style="width:100px">Kabupaten</th>							
                                            <th style="width:100px">Propinsi</th>							
                                        </tr>
                                    </thead>
                                    <tfoot id="show_footercaridesa1">
                                        
                                    </tfoot>
                                    <tbody id="show_caridesa1">
                                         
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
	<!-- end ModalCariDesa -->

    <!-- khusus menyimpan data yang akan dihapus -->
    <input name="id3" id="id3"type="hidden">	
    <input name="data3a" id="data3a"type="hidden">	
    <input name="data3b" id="data3b"type="hidden">	
    <input name="data3c" id="data3c"type="hidden">
    <!-- khusus menyimpan data yang akan diedit -->	
    <input name="data6" id="data6"type="hidden">	
    <input name="data7" id="data7"type="hidden">	
    <input name="data8" id="data8"type="hidden">	
    <input name="data9" id="data9"type="hidden">	

</div>


<script type="text/javascript">
    var caridesa1Datatable;
    var identitas1Datatable;
    var syarat1Datatable;

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

    function terbilang(bilangan) {
			 bilangan    = String(bilangan);
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
			$('#tgldaftar1').val(tglsekarang);
		}
    
    start_persiapan();

    function setting_image(){
        //img_foto
        function readURLimg_foto1(input) {
        if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('.img-previewimg_foto1').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#img_foto1").change(function() {
            var gbrx="<img class='img-previewimg_foto1 img-fluid col-sm-12 mb-1 mt-1 d-block' id='img-previewimg_foto1' name='img-previewimg_foto1' style='width: 100%;'>";
            document.getElementById("gambarimg_foto1").innerHTML=gbrx;
            readURLimg_foto1(this);
        });    
    
        //img_kk
        function readURLimg_kk1(input) {
        if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('.img-previewimg_kk1').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);            
            }
        }
        $("#img_kk1").change(function() {        
            var gbrx="<iframe class='img-previewimg_kk1 img-fluid col-sm-12 mb-0 mt-0 d-block p-0' id='img-previewimg_kk1' name='img-previewimg_kk1' style='width: 100%; height: 195px;'></iframe>";
            document.getElementById("gambarimg_kk1").innerHTML=gbrx;
            readURLimg_kk1(this);
        });
        
        //img_ktp
        function readURLimg_ktp1(input) {
        if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('.img-previewimg_ktp1').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);            
            }
        }
        $("#img_ktp1").change(function() {        
            var gbrx="<iframe class='img-previewimg_ktp1 img-fluid col-sm-12 mb-0 mt-0 d-block p-0' id='img-previewimg_ktp1' name='img-previewimg_ktp1' style='width: 100%; height: 195px;'></iframe>";
            document.getElementById("gambarimg_ktp1").innerHTML=gbrx;
            readURLimg_ktp1(this);
        });
    
        //img_bukunikah
        function readURLimg_bukunikah1(input) {
        if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('.img-previewimg_bukunikah1').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);            
            }
        }
        $("#img_bukunikah1").change(function() {        
            var gbrx="<iframe class='img-previewimg_bukunikah1 img-fluid col-sm-12 mb-0 mt-0 d-block p-0' id='img-previewimg_bukunikah1' name='img-previewimg_bukunikah1' style='width: 100%; height: 195px;'></iframe>";
            document.getElementById("gambarimg_bukunikah1").innerHTML=gbrx;
            readURLimg_bukunikah1(this);
        });
    
        //img_nomorbuktiud
        function readURLimg_nomorbuktiud1(input) {
        if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('.img-previewimg_nomorbuktiud1').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);            
            }
        }
        $("#img_nomorbuktiud1").change(function() {        
            var gbrx="<iframe class='img-previewimg_nomorbuktiud1 img-fluid col-sm-12 mb-0 mt-0 d-block p-0' id='img-previewimg_nomorbuktiud1' name='img-previewimg_nomorbuktiud1' style='width: 100%; height: 195px;'></iframe>";
            document.getElementById("gambarimg_nomorbuktiud1").innerHTML=gbrx;
            readURLimg_nomorbuktiud1(this);
        });
    }
    
    function start_persiapan(){
        tglhariini();
        setting_image();
        tampil_listdesa1();
        identitas1Datatable = tampil_identitas1();    
        syarat1Datatable = tampil_syarat1();
        caridesa1Datatable = tampil_caridesa1(); 
        setTimeout(() => {        
                $('#tab-identitas').click();
                $('#tab-identitas2').click();
                
                setTimeout(() => {
                    // let x = $('#iddesa1 option:selected').text();
                    // const xArray = x.split("|");
                    // let w = xArray[0];
                    // let y = xArray[1];
                    // let z = xArray[2];
                    // let j = xArray[3];
        
                    // $("#kecamatan1").val(y);
                    // $("#kabupaten1").val(z);
                    //$("#propinsi1").val(j);
        
                }, 1000);
                
                // identitas1Datatable = tampil_identitas1();    
                // syarat1Datatable = tampil_syarat1();    
            }, 1000);
    }

    //menampilkan combo iddesa1 admin.listdesa109
    function tampil_listdesa1(){				
        $.ajax({
            type: 'get',
            url   : '{{route('admin.listdesa109')}}',
            
            success: function(data){				    
                $("#iddesa1").html(data);
                $("#iddesa1").val('47780');
            }
        })                    
    }

    $('#iddesa1').on('change',function(){
        setTimeout(() => {
            var x = $('#iddesa1 option:selected').text();
            const xArray = x.split("|");
            let w = xArray[0];
            let y = xArray[1];
            let z = xArray[2];
            let j = xArray[3];

            $("#desa1").val(w);
            $("#kecamatan1").val(y);
            $("#kabupaten1").val(z);
            $("#propinsi1").val(j);

        }, 1000);
    });

    $("#tgldaftar1").datepicker({
           dateFormat  : "yy-mm-dd",
           changeMonth : true,
           changeYear  : true         
    });
    $("#tgllahir1").datepicker({
           dateFormat  : "yy-mm-dd",
           changeMonth : true,
           changeYear  : true         
    });
    
    $("#tglkeluar1").datepicker({
           dateFormat  : "yy-mm-dd",
           changeMonth : true,
           changeYear  : true         
    });

    $("#btn_nia1").on('click',function(){
        var x = $('#tgldaftar1').val().length;

        var a = $('#tgldaftar1').val();
        const aArray = a.split("-");
        var thn = aArray[0];
        var bln = aArray[1];
        var tgl = aArray[2];

        var nthn = parseFloat(thn);
        var nbln = parseFloat(bln);
        var ntgl = parseFloat(tgl);
        
        if(x==10){
            var pjx = 'b';
        }else{
            var pjx = 's';
        }
        
        if(nthn>=1900){
            var thx = 'b';
        }else{
            var thx = 's';
        }
        
        if((nbln<=12)&&(nbln>0)){
            var blx = 'b';
        }else{
            var blx = 's';
        }
        
        var kabisat = parseFloat(nthn % 4);
        if(kabisat==0){
            if((nbln==1)||(nbln==3)||(nbln==5)||(nbln==7)||(nbln==8)||(nbln==10)||(nbln==12)){
                if((ntgl<32)&&(ntgl>0)){
                    var tgx = 'b';
                }else{
                    var tgx = 's';
                }
            }
            if((nbln==4)||(nbln==6)||(nbln==9)||(nbln==11)){
                if((ntgl<31)&&(ntgl>0)){
                    var tgx = 'b';
                }else{
                    var tgx = 's';
                }
            }
            if((nbln==2)){
                if((ntgl<30)&&(ntgl>0)){
                    var tgx = 'b';
                }else{
                    var tgx = 's';
                }
            }
        }else{
           if((nbln==1)||(nbln==3)||(nbln==5)||(nbln==7)||(nbln==8)||(nbln==10)||(nbln==12)){
                if((ntgl<32)&&(ntgl>0)){
                    var tgx = 'b';
                }else{
                    var tgx = 's';
                }
            }
            if((nbln==4)||(nbln==6)||(nbln==9)||(nbln==11)){
                if((ntgl<31)&&(ntgl>0)){
                    var tgx = 'b';
                }else{
                    var tgx = 's';
                }
            }
            if((nbln==2)){
                if((ntgl<29)&&(ntgl>0)){
                    var tgx = 'b';
                }else{
                    var tgx = 's';
                }
            } 
        }
        
        if((pjx=='s')||(thx=='s')||(blx=='s')||(tgx=='s')){
            swalsalahtgldaftar();
        }else{
            nia();
        }
    });

    function nia(){        
        var tgldaftar1=$('#tgldaftar1').val();

        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('admin01.master.anggota_nia')}}',
            async : false,
            dataType : 'json',
            // data : FormData,
            data : {tgldaftar1:tgldaftar1},
            // cache: false,
            // processData: false,
            // contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },				 				
            success : function(data){ 
                var resultData = data.data;	                
                    $('#nia1').val(resultData[0].nia);                                        
                },
            error : function(data){ 
                
                }

        });
    }

    $('#btn_caridesa1').on('click',function(){
        $('#ModalCariDesa1').modal('show');
    });

    function tampil_caridesa1(){
        let i = 1;	
        return $('#caridesa1').DataTable({
            responsive : true,
            retrieve: true,
            autoWidth : true,
            paging : true,
            buttons : [ {extend: 'colvis', postfixButtons: [ 'colvisRestore' ] }, {extend:'copy'}, {extend:'csv'}, {extend: 'pdf', orientation: 'portrait', pageSize: 'A4', title:'{{ $caption }}'}, {extend: 'excel', title: '{{ $caption }}'}, {extend:'print', orientation: 'portrait', pageSize: 'A4', title: '{{ $caption }}'}, ],        
            dom: 'lfrtip',
            lengthMenu: [
                [ 10, 25, 50, 100, 500, 1000, 5000, -1 ],
                [ '10', '25', '50', '100', '500','1000','5000', 'All' ]
            ],
            processing: true,
            serverSide: true,
            ajax   : `{{route('admin01.master.anggota_showdesa')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'desa', name: 'desa' },
                { data: 'kecamatan', name: 'kecamatan.kecamatan' },
                { data: 'kabupaten', name: 'kecamatan.kabupaten.kabupaten' },
                { data: 'propinsi', name: 'kecamatan.kabupaten.propinsi.propinsi' },
            ]
        });
    }

    $('#show_caridesa1').on('click','.item_desa',function(){ 
        ambilcaridesa1(this);
    });

    function ambilcaridesa1(t){
        
        var id1 = $(t).attr('data1');
        var desa1 = $(t).attr('data2');
        var kecamatan1 = $(t).attr('data3');
        var kabupaten1 = $(t).attr('data4');
        var propinsi1 = $(t).attr('data5');
        // alert(id1 + ' ' + desa1 + ' ' + kecamatan1 + ' ' + kabupaten1 + ' ' + propinsi1)
        $('#iddesa1').val(id1);
        $('#desa1').val(desa1);
        $('#kecamatan1').val(kecamatan1);
        $('#kabupaten1').val(kabupaten1);
        $('#propinsi1').val(propinsi1);

        

        $('#ModalCariDesa1').modal('hide');
    }

    $('#aktif1xy').on('change',function(){
        $('#aktif1').val("Y");
    });

    $('#aktif1xn').on('change',function(){
        $('#aktif1').val("N");
    });

    $("#uangdaftar1").on('change',function(){
        var x = $('#uangdaftar1').val().replace(/[^,\d]/g, '').toString();
        if (x==''){
            $("#uangdaftar1").val('0');
        }
        var y = parseFloat(x);
        if (y>=999999999999999){
            $("#uangdaftar1").val(y);
        } 
        var x1 = terbilang(x);
        $("#terbilang1").html(x1);       
    });

    $("#uangdaftar1").on('keydown',function(){
        var x = $('#uangdaftar1').val().replace(/[^,\d]/g, '').toString();
        if (x==''){
            $("#uangdaftar1").val('0');
        }
        var y = parseFloat(x);
        if (y>=999999999999999){
            $("#uangdaftar1").val(y);
        } 
        var x1 = terbilang(x);
        $("#terbilang1").html(x1);       
    });

    function tampil_identitas1(){
        let i = 1;	
        return $('#identitas1').DataTable({
            responsive : true,
            retrieve: true,
            autoWidth : true,
            buttons : [ {extend: 'colvis', postfixButtons: [ 'colvisRestore' ] }, {extend:'copy'}, {extend:'csv'}, {extend: 'pdf', orientation: 'portrait', pageSize: 'A4', title:'{{ $caption }}'}, {extend: 'excel', title: '{{ $caption }}'}, {extend:'print', orientation: 'portrait', pageSize: 'A4', title: '{{ $caption }}'}, ],        
            dom: 'lBfrtip',
            lengthMenu: [
                [ 10, 25, 50, 100, 500, 1000, 5000, -1 ],
                [ '10', '25', '50', '100', '500','1000','5000', 'All' ]
            ],
            processing: true,
            serverSide: true,
            ajax   : `{{route('admin01.master.anggota_show')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'tgldaftar', name: 'tgldaftar' },
                { data: 'nia', name: 'nia', className: 'dt-center' },
                { data: 'nik', name: 'nik', className: 'dt-center' },
                { data: 'ecard', name: 'ecard', className: 'dt-center' },
                { data: 'nama', name: 'nama' },
                { data: 'alamat', name: 'alamat' },
                { data: 'desa', name: 'desa.desa' },
                { data: 'kecamatan', name: 'desa.kecamatan.kecamatan' },
                { data: 'kabupaten', name: 'desa.kecamatan.kabupaten.kabupaten' },
                { data: 'propinsi', name: 'desa.kecamatan.kabupaten.propinsi.propinsi' },
                { data: 'telp', name: 'telp' },
                { data: 'email', name: 'email' },
                { data: 'tgllahir', name: 'tgllahir' },
                { data: 'tglkeluar', name: 'tglkeluar' },
                { data: 'aktif', name: 'aktif' },
                { data: 'action', name: 'action', className: 'dt-center' },
            ]
        });
    }

     function tampil_syarat1(){
        let i = 1;	
        return $('#syarat1').DataTable({
            responsive : true,
            retrieve: true,
            autoWidth : true,
            buttons : [ {extend: 'colvis', postfixButtons: [ 'colvisRestore' ] }, {extend:'copy'}, {extend:'csv'}, {extend: 'pdf', orientation: 'portrait', pageSize: 'A4', title:'{{ $caption }}'}, {extend: 'excel', title: '{{ $caption }}'}, {extend:'print', orientation: 'portrait', pageSize: 'A4', title: '{{ $caption }}'}, ],        
            dom: 'lBfrtip',
            lengthMenu: [
                [ 10, 25, 50, 100, 500, 1000, 5000, -1 ],
                [ '10', '25', '50', '100', '500','1000','5000', 'All' ]
            ],
            processing: true,
            serverSide: true,
            ajax   : `{{route('admin01.master.anggota_show')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nia', name: 'nia', className: 'dt-center' },
                { data: 'nama', name: 'nama' },
                { data: 'img_foto', name: 'img_foto' },
                { data: 'img_ktp', name: 'img_ktp' },
                { data: 'img_kk', name: 'img_kk' },
                { data: 'img_bukunikah', name: 'img_bukunikah' },
                { data: 'uangdaftar', name: 'uangdaftar' },
                { data: 'img_nomorbuktiud', name: 'img_nomorbuktiud' },
                { data: 'action', name: 'action', className: 'dt-center' },
            ]
        });
    }

    function tampil_dataTable(){        
        identitas1Datatable.draw(null, false);        
        syarat1Datatable.draw(null, false);
    }
       
    function btn_baru_click(){ 
        $('#bodyAdd :input').prop('disabled', false);
        document.getElementById("btn_simpan").style.display='block';        
        document.getElementById("btn_baru").style.display='none';
        
        $('#img_foto1').val('');
        var gbrx="<h5 class='mt-2' align='center'>Belum ada image yang diupload<h5>";                        
        document.getElementById("gambarimg_foto1").innerHTML=gbrx;

        $('#img_ktp1').val('');
        var gbrx="<h5 class='mt-2' align='center'>Belum ada pdf yang diupload<h5>";                        
        document.getElementById("gambarimg_ktp1").innerHTML=gbrx;

        $('#img_kk1').val('');
        var gbrx="<h5 class='mt-2' align='center'>Belum ada pdf yang diupload<h5>";                        
        document.getElementById("gambarimg_kk1").innerHTML=gbrx;

        $('#img_bukunikah1').val('');
        var gbrx="<h5 class='mt-2' align='center'>Belum ada pdf yang diupload<h5>";                        
        document.getElementById("gambarimg_bukunikah1").innerHTML=gbrx;

        $('#img_nomorbuktiud1').val('');
        var gbrx="<h5 class='mt-2' align='center'>Belum ada pdf yang diupload<h5>";                        
        document.getElementById("gambarimg_nomorbuktiud1").innerHTML=gbrx;
    }
    
    function btn_simpan_click(){      
        $('#bodyAdd :input').prop('disabled', true);
        document.getElementById("btn_simpan").style.display='none';        
        document.getElementById("btn_baru").style.display='block';
        swaltambah($('#nama1').val());
        
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

        btn_baru_click();

        $("#iconx").removeClass("fas fa-edit");
        $("#iconx").addClass("fas fa-plus-square");
        $("#modalx").removeClass("modal-content bg-success w3-animate-zoom");
        $("#modalx").addClass("modal-content bg-primary w3-animate-zoom");
        document.getElementById("btn_simpan").disabled = false;
        $('#ModalAdd').modal('show');
        $('#id1').val('0');
        $('#judulx').html(' Tambah Data');

        $('#img_foto1').val('');
        var gbrx="<h5 class='mt-2' align='center'>Belum ada image yang diupload<h5>";                        
        document.getElementById("gambarimg_foto1").innerHTML=gbrx;

        $('#img_ktp1').val('');
        var gbrx="<h5 class='mt-2' align='center'>Belum ada pdf yang diupload<h5>";                        
        document.getElementById("gambarimg_ktp1").innerHTML=gbrx;

        $('#img_kk1').val('');
        var gbrx="<h5 class='mt-2' align='center'>Belum ada pdf yang diupload<h5>";                        
        document.getElementById("gambarimg_kk1").innerHTML=gbrx;

        $('#img_bukunikah1').val('');
        var gbrx="<h5 class='mt-2' align='center'>Belum ada pdf yang diupload<h5>";                        
        document.getElementById("gambarimg_bukunikah1").innerHTML=gbrx;

        $('#img_nomorbuktiud1').val('');
        var gbrx="<h5 class='mt-2' align='center'>Belum ada pdf yang diupload<h5>";                        
        document.getElementById("gambarimg_nomorbuktiud1").innerHTML=gbrx;
       
    }); 
    
    function data_simpan(){
        var id1=$('#id1').val();
        var tgldaftar1=$('#tgldaftar1').val();
        var tgllahir1=$('#tgllahir1').val();
        var tglkeluar1=$('#tglkeluar1').val();
        var nama1=$('#nama1').val();
        var nia1=$('#nia1').val();
        var nik1=$('#nik1').val();
        var ecard1=$('#ecard1').val();
        var alamat1=$('#alamat1').val();
        var iddesa1=$('#iddesa1').val();
        var telp1=$('#telp1').val();
        var email1=$('#email1').val();
        var aktif1=$('#aktif1').val();
        var uangdaftar1=$('#uangdaftar1').val().replace(/[^,\d]/g, '').toString();
        var nomorbuktiud1=$('#nomorbuktiud1').val();
        const img_foto1 = $('#img_foto1').prop('files')[0];
        const img_ktp1 = $('#img_ktp1').prop('files')[0];
        const img_kk1 = $('#img_kk1').prop('files')[0];
        const img_bukunikah1 = $('#img_bukunikah1').prop('files')[0];
        const img_nomorbuktiud1 = $('#img_nomorbuktiud1').prop('files')[0];

        let formData = new FormData();

            formData.append('id1', id1);
            formData.append('tgldaftar1', tgldaftar1);
            formData.append('tgllahir1', tgllahir1);
            formData.append('tglkeluar1', tglkeluar1);
            formData.append('nama1', nama1);
            formData.append('nia1', nia1);
            formData.append('nik1', nik1);
            formData.append('ecard1', ecard1);
            formData.append('alamat1', alamat1);
            formData.append('iddesa1', iddesa1);
            formData.append('telp1', telp1);
            formData.append('email1', email1);
            formData.append('aktif1', aktif1);
            formData.append('uangdaftar1', uangdaftar1);
            formData.append('nomorbuktiud1', nomorbuktiud1);
            formData.append('img_foto1', img_foto1);
            formData.append('img_kk1', img_kk1);
            formData.append('img_ktp1', img_ktp1);
            formData.append('img_bukunikah1', img_bukunikah1);
            formData.append('img_nomorbuktiud1', img_nomorbuktiud1);
          
        $.ajax({

            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('admin01.master.anggota_create')}}',
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
                swalgagaltambah($('#nama1').val());                 
                }

                
        });
        
    }   

    $("#btn_simpan").on('click',function(){
        data_simpan();
    });

    $('#show_identitas1').on('click','.item_edit',function(){
        var id1 = $(this).attr('data');
        $('#desa1').val($(this).attr('data6'));
        $('#kecamatan1').val($(this).attr('data7'));
        $('#kabupaten1').val($(this).attr('data8'));
        $('#propinsi1').val($(this).attr('data9'));
        item_edit_click(id1);       
    });
    
    $('#show_syarat1').on('click','.item_edit',function(){
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
       
    }
    
    function data_edit(idx){
        var id1=idx;			
            $.ajax({
		        type  : 'get',
		        url   : `{{ url('admin01/master/anggotaedit')}}/${id1}`,
		        async : false,
		        dataType : 'json',	
				
		        success : function(data){
                    var i;                
                    var resultData = data.data;	                			
                    for(i=0; i<resultData.length; i++){

                        $('#id1').val(resultData[i].id);
                        $('#tgldaftar1').val(resultData[i].tgldaftar);
                        $('#tgllahir1').val(resultData[i].tgllahir);
                        $('#tglkeluar1').val(resultData[i].tglkeluar);
                        $('#nama1').val(resultData[i].nama);
                        $('#nia1').val(resultData[i].nia);
                        $('#nik1').val(resultData[i].nik);
                        $('#ecard1').val(resultData[i].ecard);
                        $('#alamat1').val(resultData[i].alamat);
                        $('#iddesa1').val(resultData[i].iddesa);

                        $('#telp1').val(resultData[i].telp);
                        $('#email1').val(resultData[i].email);
                        $('#aktif1').val(resultData[i].aktif);
                        if ($('[name="aktif1"]').val()=='Y'){
							document.getElementById("aktif1xy").checked = true;
						}else{
							document.getElementById("aktif1xn").checked = true;
						}
                        
                        var ud = resultData[i].uangdaftar;
                        var udx = formatAngka(ud,'');
                        $('#uangdaftar1').val(udx);
                        var x = $('#uangdaftar1').val().replace(/[^,\d]/g, '').toString();
                        if (x==''){
                            $("#uangdaftar1").val('0');
                        }
                        var y = parseFloat(x);
                        if (y>=999999999999999){
                            $("#uangdaftar1").val(y);
                        } 
                        var x1 = terbilang(x);
                        $("#terbilang1").html(x1);

                        $('#nomorbuktiud1').val(resultData[i].nomorbuktiud);

                        $('#img_foto1').val('');
						$('#img_foto1x').val(resultData[i].img_foto);
                        var gbr2=$('#img_foto1x').val();
                        if(gbr2){                        
                            var url2="{{ asset('storage/') }}/"+gbr2;
                            var gbrx="<img src='"+url2+"' class='img-previewimg_foto1 img-fluid col-sm-12 mb-1 mt-1 d-block' id='img-previewimg_foto1' name='img-previewimg_foto1' style='width: 100%;' alt='"+url2+"'>";
                        }else{
                            var gbrx="<h5 class='mt-2' align='center'>Belum ada file image yang diupload<h5>";
                        }
                        document.getElementById("gambarimg_foto1").innerHTML=gbrx;

                        $('#img_ktp1').val('');
						$('#img_ktp1x').val(resultData[i].img_ktp);
                        var gbr2=$('#img_ktp1x').val();
                        if(gbr2){                        
                            var url2="{{ asset('storage/') }}/"+gbr2;
                            var gbrx="<iframe src='"+url2+"' class='img-previewimg_ktp1 img-fluid col-sm-12 mb-0 mt-0 d-block' id='img-previewimg_ktp1' name='img-previewimg_ktp1' style='width: 100%; height: 195px' alt='"+url2+"'></iframe>";
                        }else{
                            var gbrx="<h5 class='mt-2' align='center'>Belum ada file pdf yang diupload<h5>";
                        }
                        document.getElementById("gambarimg_ktp1").innerHTML=gbrx;

                        $('#img_kk1').val('');
						$('#img_kk1x').val(resultData[i].img_kk);
                        var gbr2=$('#img_kk1x').val();
                        if(gbr2){                        
                            var url2="{{ asset('storage/') }}/"+gbr2;
                            var gbrx="<iframe src='"+url2+"' class='img-previewimg_kk1 img-fluid col-sm-12 mb-0 mt-0 d-block' id='img-previewimg_kk1' name='img-previewimg_kk1' style='width: 100%; height: 195px' alt='"+url2+"'></iframe>";
                        }else{
                            var gbrx="<h5 class='mt-2' align='center'>Belum ada file pdf yang diupload<h5>";
                        }
                        document.getElementById("gambarimg_kk1").innerHTML=gbrx;

                        $('#img_bukunikah1').val('');
						$('#img_bukunikah1x').val(resultData[i].img_bukunikah);
                        var gbr2=$('#img_bukunikah1x').val();
                        if(gbr2){                        
                            var url2="{{ asset('storage/') }}/"+gbr2;
                            var gbrx="<iframe src='"+url2+"' class='img-previewimg_bukunikah1 img-fluid col-sm-12 mb-0 mt-0 d-block' id='img-previewimg_bukunikah1' name='img-previewimg_bukunikah1' style='width: 100%; height: 195px' alt='"+url2+"'></iframe>";
                        }else{
                            var gbrx="<h5 class='mt-2' align='center'>Belum ada file pdf yang diupload<h5>";
                        }
                        document.getElementById("gambarimg_bukunikah1").innerHTML=gbrx;

                        $('#img_nomorbuktiud1').val('');
						$('#img_nomorbuktiud1x').val(resultData[i].img_nomorbuktiud);
                        var gbr2=$('#img_nomorbuktiud1x').val();
                        if(gbr2){                        
                            var url2="{{ asset('storage/') }}/"+gbr2;
                            var gbrx="<iframe src='"+url2+"' class='img-previewimg_nomorbuktiud1 img-fluid col-sm-12 mb-0 mt-0 d-block' id='img-previewimg_nomorbuktiud1' name='img-previewimg_nomorbuktiud1' style='width: 100%; height: 195px' alt='"+url2+"'></iframe>";
                        }else{
                            var gbrx="<h5 class='mt-2' align='center'>Belum ada file pdf yang diupload<h5>";
                        }
                        document.getElementById("gambarimg_nomorbuktiud1").innerHTML=gbrx;

                    }
                    
		        },
                error : function(data){
                    alert(id1);
                }
		    }); 
            
    }

    $('#show_identitas1').on('click','.item_hapus',function(){
        var id3=$(this).attr('data');
        var data3b=$(this).attr('data2');
        var data3c=$(this).attr('data5');
        var data3d=$(this).attr('data4');
        item_hapus_click(id3,data3b,data3c,data3d);
    });

    $('#show_syarat1').on('click','.item_hapus',function(){
        var id3=$(this).attr('data');
        var data3b=$(this).attr('data2');
        var data3c=$(this).attr('data5');
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

    function data_hapus(){			
        var id3=$('#id3').val();       
        var data3b=$('#data3b').val();
        $.ajax({
            type  : 'get',
            url   : '{{url('admin01/master/anggotadestroy')}}/'+id3,
            async : false,
            dataType : 'json',					
            success : function(data){
                tampil_dataTable();
                swalhapus(data3b); 
            },
            error : function(data){
                swalgagalhapus(data3b); 
            }
        });
    }

    function swaltambah(x){
        Swal.fire({
            icon: 'success',
            title: 'Save/update successfully',
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

    function swalsalahtgldaftar(){
        Swal.fire({
            icon: 'info',
            title: 'Maaf, tgl daftar salah !!!',
            text: '',
            timer:5000
        })
    }

});

</script>	



@endsection