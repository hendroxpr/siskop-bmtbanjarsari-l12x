@extends('admin.layouts.main')

@section('contents')


@php
    
    $idproduktabungan = session('idproduktabunganx1');
    $desain1 = session('desain1');
    $tandapengenal1 = session('tandapengenal1');

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
                        <h6>Produk Tabungan</h6>
                    </div>
                    <div class="col-md-5">
                        <select name="idproduktabunganx1" id="idproduktabunganx1" class="w3-input w3-border" value="{{ $idproduktabungan }}"></select> 
                    </div>
                </div>
                
            </div>

            <div class="col-md-6">
                <div class="w3-row" align="right"><i class="fa fa-refresh" aria-hidden="true"></i>            
                    <a id="btn_refresh1" name="btn_refresh1" href="{{ url('/') }}{{ $link }}" class="btn bg-success rounded-0" style="text-decoration: none;"><i style="font-size:18px" class="fa">&#xf021;</i> Refresh</a>            
                    <button id="btn_tambah1" name="btn_tambah1" type="button" class="btn bg-primary rounded-0"><i class="fas fa-plus"></i> Tambah</button>
                    <button id="btn_importexcel1" name="btn_importexcel1" type="button" class="w3-button w3-orange" style="color:#7FFF00; height:36px; display: none;"><i style="font-size:18px" class="fa">&#xf1c3;</i> Import</button>
                    <button id="btn_update1x" name="btn_update1x" type="button" class="w3-button w3-purple" style="color:#7FFF00; height:36px; display: none;"><i style="font-size:18px" class="fa">&#xf56e;</i> Update</button>
                </div> 
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
                        <th style="width:50px">Norek</th>							
                        <th style="width:50px">NIA</th>
                        <th style="width:50px">NIK</th>
                        <th style="width:50px">Ecard</th>							
                        <th style="width:150px">Nama</th>							
                        <th style="width:50px">Tgl Lahir</th>							
                        <th style="width:50px">Desain</th>							
                        <th style="width:50px">Tgl Daftar</th>							
                        <th style="width:50px">Tgl Keluar</th>							
                        <th style="width:200px">Alamat</th>							
                        <th style="width:50px">Telp</th>							                                    
                        <th style="width:50px">Aktif</th>							                                    
                        <th style="width:50px">Tanda Pengenal</th>							                                    
                        <th style="width:50px">Saldo</th>							                                    
                        <th style="width:50px">Action</th>
                    </tr>
                </thead>
                <tfoot id="show_footer1">
                    
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
                                    <h6 class="mt-2">Tgl. Daftar *)</h6>
                                </div>
                                <div class="col-md-8">                                
                                    <input name="tgldaftar1" id="tgldaftar1" class="w3-input w3-border" type="search" placeholder="Tanggal Daftar">
                                </div>								  
                            </div> 

                            <div class="row">
                                <div class="col-md-4 mt-0" align="right">									
                                    <h6 class="mt-2">Norek *)</h6>
                                </div>
                                <div class="col-md-8 input-group mt-0">
                                    <input name="norek1" id="norek1" class="form-control w3-input w3-border rounded-0" maxlength="25" type="search" placeholder="Norek"  value="{{ old('norek1') }}" required> 
                                    <div class="input-group-append">
                                        <button id="btn_norek1" name="btn_norek1" type="button" style="border-radius:0px; border:none;" title="Generate Nomor Rekening" disabled><i style="font-size:24" class="fa">&#xf013;</i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mt-0" align="right">									
                                    <h6 class="mt-2">Nama *)</h6>
                                </div>
                                <div class="col-md-8 input-group mt-0">
                                    <input name="nama1" id="nama1" class="form-control w3-input w3-border rounded-0" maxlength="200" type="search" placeholder="Nama"  value="{{ old('nama1') }}" readonly style="background: white;"> 
                                    <div class="input-group-append">
                                        <button id="btn_carianggota1" name="btn_carianggota1" type="button" style="border-radius:0px; border:none;" title="Cari Anggota" disabled><i style="font-size:24" class="fas">&#xf002;</i></button>
                                        <input name="cek1" id="cek1" class="" type="hidden">                                
                                        <input name="id1" id="id1" class="" type="hidden">
                                        <input name="idanggota1" id="idanggota1" class="" type="hidden">
                                    </div>
                                </div>
                            </div>
                           
                            <div class="row">
                                <div class="col-md-4 mt-1" align="right">									
                                    <h6 class="mt-2">Ecard *)</h6>
                                </div>
                                <div class="col-md-8">                                
                                    <input name="ecard1" id="ecard1" class="w3-input w3-border" maxlength="25" type="search" placeholder="Ecard" value="{{ old('ecard1') }}" readonly>
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-4 mt-1" align="right">									
                                    <h6 class="mt-2">NIA *)</h6>
                                </div>
                                <div class="col-md-8">                                
                                    <input name="nia1" id="nia1" class="w3-input w3-border" maxlength="25" type="search" placeholder="NIA" value="{{ old('nia1') }}" readonly>
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-4 mt-1" align="right">									
                                    <h6 class="mt-2">NIK *)</h6>
                                </div>
                                <div class="col-md-8">                                
                                    <input name="nik1" id="nik1" class="w3-input w3-border" maxlength="25" type="search" placeholder="NIK" value="{{ old('nik1') }}" readonly>
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-4 mt-0" align="right">
                                    <h6 class="mt-2">Tanda Pengenal</h6>
                                </div>
                                <div class="col-md-8">
                                    <select name="tandapengenal1" id="tandapengenal1" class="w3-input w3-border"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mt-0" align="right">
                                    <h6 class="mt-2">Desain</h6>
                                </div>
                                <div class="col-md-8">
                                    <select name="desain1" id="desain1" class="w3-input w3-border"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mt-1" align="right">									
                                    <h6 class="mt-2">Tgl. Lahir</h6>
                                </div>
                                <div class="col-md-8">                                
                                    <input name="tgllahir1" id="tgllahir1" class="w3-input w3-border" type="search" placeholder="Tanggal Lahir" readonly>
                                </div>								  
                            </div>
                            
                            <div class="row" id="div-tglkeluar" name="div-tglkeluar">
                                <div class="col-md-4 mt-1" align="right">									
                                    <h6 class="mt-2">Tgl. Keluar</h6>
                                </div>
                                <div class="col-md-8">                                
                                    <input name="tglkeluar1" id="tglkeluar1" class="w3-input w3-border" type="search" placeholder="Tanggal Keluar">
                                </div>								  
                            </div>                           
                            <div class="row">
                                <div class="col-md-4 mt-1" align="right">
                                    <h6 class="mt-2">Alamat</h6>
                                </div>
                                <div class="col-md-8">
                                    <input name="alamat1" id="alamat1" maxlength="50" class="w3-input w3-border" type="search" placeholder="Alamat">                                
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mt-1" align="right">
                                    <h6 class="mt-2">Telp/HP</h6>
                                </div>
                                <div class="col-md-8">
                                    <input name="telp1" id="telp1" maxlength="50" class="w3-input w3-border" type="search" placeholder="Telp/HP">                                
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
                                <div class="col-md-4 mt-1" align="right">
                                    <h6 class="mt-2">Saldo</h6>
                                </div>
                                <div class="col-md-8">
                                    <input name="saldo1" id="saldo1" class="w3-input w3-border text-right" type="text" value="0" placeholder="Saldo" readonly>
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

    {{-- <!-- ModalImport modal fade-->
    <div class="modal fade" id="ModalImport" data-backdrop="static">
        <div class="modal-dialog modal-sm">  <!-- modal-(sm, lg, xl) ukuran lebar modal -->
            <div id="modalx" nama="modalx"  class="modal-content w3-orange w3-animate-zoom">
            
                <div class="modal-header">
                <h3 class="modal-title"><i style="font-size:24px" class="fa">&#xf1c3;</i><b><span> Import Data</span></b></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="form-group">
                    <form class="form-horizontal" id="formSimpan" nama="formSimpan" action="" method="">
                        @csrf	
                        <div class="modal-body" id="bodyAdd" name="bodyAdd">                        
                            <div class="row py-1 justify-content-center">                                
                                <h5 class="pt-1" style="color: yellow">File yang diimport : xls, xlsx</h5>                                
                            </div>

                            <div class="row">
                                <div class="col-md-12 mt-1" align="right">
                                    <input style="width: 100%;" name="file1" id="file1" type="file" class="w3-input w3-border mb-1" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                </div>                                
                            </div>

                            <div class="row">                                
                                <div class="col-md-12 mt-1" align="right">                                
                                    <button style="width: 100%; height: 100%; margin-right: 0px;" id="btn_download1" name="btn_download1" type="button" class="w3-button w3-border w3-border-white"><i class="fa fa-download" aria-hidden="true" style="font-size:18px"></i> Download Form</button>
                                </div>
                            </div>

                            <div class="row mb-0 mt-3">
                                <div class="col-md-5 mt-1" align="right">									
                                    <h6 class="mt-2">Tanggal Daftar</h6>
                                </div>
                                <div class="col-md-7">                                
                                    <input name="tgldaftarx" id="tgldaftarx" class="w3-input w3-border" type="text" placeholder="Tanggal Daftar">
                                    <input name="tgllahirx" id="tgllahirx" type="hidden">
                                </div>								  
                            </div>  
                            <div class="row mt-1 mb-2">
                                <div class="col-md-5 mt-1" align="right">									
                                    <h6 class="mt-2">Desain</h6>
                                </div>
                                <div class="col-md-7">                                
                                    <input name="desainx" id="desainx" class="w3-input w3-border" type="text" placeholder="Desain" maxlength="10">
                                </div>								  
                            </div>  
                            
                        </div>
                        <div class="modal-footer justify-content-between" style="padding-bottom: 0px">
                            <button type="button" class="w3-button w3-border w3-border-white" data-dismiss="modal">Tutup</button>                            
                            <button id="btn_upload1" name="btn_upload1" type="button" class="w3-button w3-border w3-border-white"><i class="fa fa-upload" aria-hidden="true" style="font-size:18px"></i> Upload</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->      
    </div>  
    <!-- end ModalImport --> --}}

    <!-- ModalCariAnggota1 modal fade-->
	<div class="modal fade" id="ModalCariAnggota1"  data-backdrop="static">
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
							<div id="reloadcarianggota1" class="table-responsive">
                                <table id="carianggota1" class="table table-bordered table-striped table-hover" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th style="width:10px;">#</th>                            
                                            <th style="width:100px">Nama</th>							
                                            <th style="width:100px">NIA</th>							
                                            <th style="width:100px">NIK</th>							
                                            <th style="width:100px">Ecard</th>							
                                            <th style="width:100px">Alamat</th>							
                                            <th style="width:100px">Desa</th>							
                                            <th style="width:100px">Kecamatan</th>							
                                            <th style="width:100px">Kabupaten</th>							
                                            <th style="width:100px">Propinsi</th>							
                                        </tr>
                                    </thead>
                                    <tfoot id="show_footercarianggota1">
                                        
                                    </tfoot>
                                    <tbody id="show_carianggota1">
                                         
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

    <!-- khusus menyimpan data yang akan dihapus -->
    <input name="id3" id="id3"type="hidden">	
    <input name="data3a" id="data3a"type="hidden">	
    <input name="data3b" id="data3b"type="hidden">	
    <input name="data3c" id="data3c"type="hidden">	

</div>


<script type="text/javascript">
    var data1Datatable;
    var carianggota1Datatable;

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
			$('#tgldaftar1').val(tglsekarang);
			$('#tgllahir1').val(tglsekarang2);
		}

    start_persiapan()
    function start_persiapan(){
        carianggota1Datatable = tampil_carianggota1();
        setTimeout(() => {
             data1Datatable = tampil_data1();    
             tampil_listdesain1();
             tampil_listtandapengenal1();
             tampil_listproduktabunganx()
        }, 1000);
    }    

    function addZero(i) {
        if (i < 10) {i = "0" + i}
        return i;
    }
    $("#btn_norek1").on('click',function(){
        
        var prod = $('#idproduktabunganx1 option:selected').text();
        var prodx = prod.substr(0,2);

        var tgldaftar=$('#tgldaftar1').val();
        let tglx = tgldaftar.replace(/[-]/g,'');

        const d = new Date();
        let milidetik ='';
        let tahun = addZero(d.getFullYear());
        let bulan = addZero(d.getMonth());
        let tanggal = addZero(d.getDate());        
        let jam = addZero(d.getHours());
        let menit = addZero(d.getMinutes());
        let detik = addZero(d.getSeconds());        
        let milidetikx = d.getMilliseconds();
        
        if(milidetikx<10){
            milidetik = '00'+milidetikx;
        }else if(milidetikx<100){
            milidetik = '0'+milidetikx;
        }else{
            milidetik = milidetikx;
        }
        
        let norek = tglx + prodx + jam + menit + detik + milidetik;
        $("#norek1").val(norek);
    });

    //menampilkan combo produktabunganx
    function tampil_listproduktabunganx(){				
        $.ajax({
            type: 'get',
            url   : '{{route('admin.listproduktabungan11')}}',
            
            success: function(data){				    
                $("#idproduktabunganx1").html(data);
                $("#idproduktabunganx1").val({{ $idproduktabungan }});
            }
        })                    
    }

    //menampilkan combo desain1
    function tampil_listdesain1(){				
        $.ajax({
            type: 'get',
            url   : '{{route('admin.listdesain10')}}',
            
            success: function(data){				    
                $("#desain1").html(data);
                $("#desain1").val({{ $desain1 }});
            }
        })                    
    }
    //menampilkan combo tandapengenal1
    function tampil_listtandapengenal1(){				
        $.ajax({
            type: 'get',
            url   : '{{route('admin.listtandapengenal10')}}',
            
            success: function(data){				    
                $("#tandapengenal1").html(data);
                // $("#tandapengenal1").val({{ $desain1 }});
            }
        })                    
    }
    
     function tampil_data1(){
        let i = 1;	
        return $('#data1').DataTable({
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
            ajax   : `{{route('tabungan01.master.nasabahtabungan_show')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'norek', name: 'norek' },
                { data: 'nia', name: 'nia'},
                { data: 'nik', name: 'nik'},
                { data: 'ecard', name: 'ecard' },
                { data: 'nama', name: 'nama'},                
                { data: 'tgllahir', name: 'tgllahir'},                
                { data: 'desain', name: 'desain'},                
                { data: 'tgldaftar', name: 'tgldaftar'},                
                { data: 'tglkeluar', name: 'tglkeluar'},                
                { data: 'alamat', name: 'alamat'},                
                { data: 'telp', name: 'telp'},                
                { data: 'aktif', name: 'aktif'},                
                { data: 'tandapengenal', name: 'tandapengenal', orderable: false},                
                { data: 'saldo', name: 'saldo', class: 'text-right'},                
                { data: 'action', name: 'action'},
            ]
        });
    }

    $('#btn_carianggota1').on('click',function(){
        $('#ModalCariAnggota1').modal('show');
    });

    function tampil_carianggota1(){
        let i = 1;	
        return $('#carianggota1').DataTable({
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
            ajax   : `{{route('tabungan01.master.nasabahtabungan_showanggota')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nama', name: 'nama' },
                { data: 'nia', name: 'nia' },
                { data: 'nik', name: 'nik' },
                { data: 'ecard', name: 'ecard' },
                { data: 'alamat', name: 'alamat' },
                { data: 'desa', name: 'desa' },
                { data: 'kecamatan', name: 'desa.kecamatan.kecamatan' },
                { data: 'kabupaten', name: 'desa.kecamatan.kabupaten.kabupaten' },
                { data: 'propinsi', name: 'desa.kecamatan.kabupaten.propinsi.propinsi' },
            ]
        });
    }

    $('#show_carianggota1').on('click','.item_nama',function(){ 
        ambilcarianggota1(this);
    });
    $('#show_carianggota1').on('click','.item_nia',function(){ 
        ambilcarianggota1(this);
    });
    $('#show_carianggota1').on('click','.item_nik',function(){ 
        ambilcarianggota1(this);
    });
    $('#show_carianggota1').on('click','.item_ecard',function(){ 
        ambilcarianggota1(this);
    });

    function ambilcarianggota1(t){

        var id1 = $(t).attr('data1');
        var nama1 = $(t).attr('data2');
        var nia1 = $(t).attr('data3');
        var nik1 = $(t).attr('data4');
        var ecard1 = $(t).attr('data5');
        var alamat1 = $(t).attr('data6');
        var desa1 = $(t).attr('data7');
        var kecamatan1 = $(t).attr('data8');
        var kabupaten1 = $(t).attr('data9');
        var propinsi1 = $(t).attr('data10');
        var alamat1 = $(t).attr('data11');
        var tgllahir1 = $(t).attr('data12');
        var telp1 = $(t).attr('data13');
       
        $('#idanggota1').val(id1);
        $('#nama1').val(nama1);
        $('#nia1').val(nia1);
        $('#nik1').val(nik1);
        $('#ecard1').val(ecard1);
        $('#alamat1').val(alamat1);
        $('#tgllahir1').val(tgllahir1);
        $('#telp1').val(telp1);
        
        $('#ModalCariAnggota1').modal('hide');
    }


   
    function data_import(){
        const file1 = $('#file1').prop('files')[0];        
        var tgldaftarx=$('#tgldaftarx').val();
        var tgllahirx=$('#tgllahirx').val();
        var desainx=$('#desainx').val();
        
        let formData = new FormData();
            formData.append('file1', file1);            
            formData.append('tgldaftarx', tgldaftarx);            
            formData.append('tgllahirx', tgllahirx);            
            formData.append('desainx', desainx);            
          
        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('tabungan01.master.nasabahtabungan_import')}}',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },				 				
            success : function(formData){                                         
                    tampil_dataTable();
                    swalsuksesimport('Data nasabah');
                    $('#ModalImport').modal('hide'); 
                },
            error : function(formData){                    
                    swalgagalimport('Data nasabah');                 
                }
        });        
    }
   
    $("#idproduktabunganx1").on('change',function(){ 
        setTimeout(() => {
            kirimsyarat();
        }, 500);
        setTimeout(() => {
            tampil_dataTable();
        }, 200);
    });

    function tampil_dataTable(){        
        data1Datatable.draw(null, false);        
    }

    function kirimsyarat(){
        var idproduktabunganx1 = $('#idproduktabunganx1').val(); 

        let formData = new FormData();
            formData.append('idproduktabunganx1', idproduktabunganx1);

        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('tabungan01.master.nasabahtabungan_kirimsyarat')}}',
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

    $("#tgldaftarx").datepicker({
			dateFormat  : "yy-mm-dd",
			changeMonth : true,
			changeYear  : true         
    });
    
    $("#tgldaftar1").datepicker({
			dateFormat  : "yy-mm-dd",
			changeMonth : true,
			changeYear  : true         
    });
    $("#tglkeluar1").datepicker({
			dateFormat  : "yy-mm-dd",
			changeMonth : true,
			changeYear  : true         
    });

    function download(fileUrl, fileName) {
        var a = document.createElement("a");
        a.href = fileUrl;
        a.setAttribute("download", fileName);
        a.click();
    }

    $('#btn_download1').on('click',function(){        
        // var gbr3=$('#logo').val();
        var data3 = 'form-excel/import_datasiswa.xlsx';
        var url3="{{ asset('storage/') }}/"+data3;
        var file3='import_datasiswa.xlsx';
        download(url3, file3);
    });

    $('#aktif1xy').on('change',function(){
        $('#aktif1').val("Y");
    });

    $('#aktif1xn').on('change',function(){
        $('#aktif1').val("N");
    });

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
        $('[name="aktif1"]').val("Y");
        if ($('[name="aktif1"]').val()=='Y'){
            document.getElementById("aktif1xy").checked = true
        }else{
            document.getElementById("aktif1xn").checked = true
        }

        $('#tglkeluar1').val('');

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
        var idanggota1=$('#idanggota1').val();			
        var nama1=$('#nama1').val();
        var norek1=$('#norek1').val();
        var nia1=$('#nia1').val();
        var nik1=$('#nik1').val();
        var ecard1=$('#ecard1').val();
        var desain1=$('#desain1').val();
        var tandapengenal1=$('#tandapengenal1').val();
        var tgllahir1=$('#tgllahir1').val();
        var tgldaftar1=$('#tgldaftar1').val();
        var tglkeluar1=$('#tglkeluar1').val();
        var alamat1=$('#alamat1').val();
        var telp1=$('#telp1').val();
        var aktif1=$('#aktif1').val();
        var idproduktabunganx1=$('#idproduktabunganx1').val();
        
        let formData = new FormData();
            formData.append('id1', id1);
            formData.append('idanggota1', idanggota1);
            formData.append('nama1', nama1);
            formData.append('norek1', norek1);
            formData.append('nia1', nia1);
            formData.append('nik1', nik1);
            formData.append('ecard1', ecard1);
            formData.append('tgllahir1', tgllahir1);
            formData.append('tgldaftar1', tgldaftar1);
            formData.append('tglkeluar1', tglkeluar1);
            formData.append('desain1', desain1);
            formData.append('tandapengenal1', tandapengenal1);
            formData.append('alamat1', alamat1);
            formData.append('telp1', telp1);
            formData.append('aktif1', aktif1);
            formData.append('idproduktabunganx1', idproduktabunganx1);
          
        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('tabungan01.master.nasabahtabungan_create')}}',
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
                swalgagaltambah(nama1);                 
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
    }

    $("#btn_update").on('click',function(){	        
        data_simpan();
    });                                         
    
    function data_edit(idx){
        var id1=idx;			
            $.ajax({
		        type  : 'get',
		        url   : `{{ url('tabungan01/master/nasabahtabunganedit')}}/${id1}`,
		        async : false,
		        dataType : 'json',	
				
		        success : function(data){
                    var i;                
                    var resultData = data.data;	                			
                    for(i=0; i<resultData.length; i++){

                        $('#id1').val(resultData[i].id);
                        $('#idanggota1').val(resultData[i].idanggota);
                        $('#nama1').val(resultData[i].nama);
                        $('#norek1').val(resultData[i].norek);
                        $('#ecard1').val(resultData[i].ecard);
                        $('#nia1').val(resultData[i].nia);
                        $('#nik1').val(resultData[i].nik);
                        $('#desain1').val(resultData[i].desain);
                        $('#tandapengenal1').val(resultData[i].tandapengenal);
                        $('#tgldaftar1').val(resultData[i].tgldaftar);
                        $('#tglkeluar1').val(resultData[i].tglkeluar);
                        $('#tgllahir1').val(resultData[i].tgllahir);
                        $('#alamat1').val(resultData[i].alamat);
                        $('#telp1').val(resultData[i].telp);
                        $('#aktif1').val(resultData[i].aktif);
                        $('#idproduktabunganx1').val(resultData[i].idproduktabungan);
                        $('#saldo1').val(formatAngka(parseInt(resultData[i].saldo),''));
                        
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


    $("#btn_update1x").on('click',function(){                
        modal_update();
    });  

    function modal_update(){
        Swal.fire({
        title: 'Are you sure update?',
        text: 'Update Data from Datasiswa',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: "Yes, update !",
		cancelButtonText: "No, cancel !",
        }).then((result) => {
            if (result.isConfirmed) {
                data_update();            
            }
        })
    } 

    function data_update(){
        var id1=$('#id1').val();
        
        
        let formData = new FormData();
            formData.append('id1', id1);            
          
        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('tabungan01.master.nasabahtabungan_update')}}',
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
                swalgagaltambah('nasabah Perpustakaan');                 
                }
        });
    }

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
            url   : '{{url('tabungan01/master/nasabahtabungandestroy')}}/'+id3,
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

    $('#show_data1').on('click','.item_printcover',function(){
        var norekx = $(this).attr('data5');        
        window.open('{{ url('tabungan01/master/nasabahtabunganprintcover') }}/'+ norekx);     
    });

    $('#show_data1').on('click','.item_printheader',function(){
        var norekx = $(this).attr('data5');        
        window.open('{{ url('tabungan01/master/nasabahtabunganprintheader') }}/'+ norekx);      
    });

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

    function swalsuksesimport(x){
        Swal.fire({
            icon: 'success',
            title: 'Import successfully',
            text: x,
            timer:1000
        })
    }

    function swalgagalimport(x){
        Swal.fire({
            icon: 'error',
            title: 'Oops...failed to import',
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