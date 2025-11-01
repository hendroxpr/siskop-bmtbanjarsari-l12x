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
            
            <div class="col-md-5">
                
                <div class="row">
                    <div class="col-md-4 mt-2 text-right">
                        <h6>ID</h6>                        
                    </div>
                    <div class="col-md-7 input-group">
                        <input name="cekprint" id="cekprint" type="hidden" value="0">
                        <input name="cariid1x" id="cariid1x" class="form-control w3-input w3-border rounded-0" type="search" value="{{ session('cariid1x') }}" placeholder="Search" autocomplete="off" autofocus>                        
                        <div class="input-group-append">
                          <button id="btn_cariid1x" name="btn_cariid1x" type="button" style="border-radius:0px; border:none;"><i style="font-size:24" class="fas">&#xf002;</i></button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mt-2 text-right">
                        <h6>Nama Siswa</h6> 
                        <input name="idnasabah1x" id="idnasabah1x" type="hidden" value="{{ session('idnasabah1x') }}">
                    </div>
                    <div class="col-md-7">
                        <input name="namalengkap1x" id="namalengkap1x" class="w3-input w3-border" type="text" placeholder="Nama Siswa" value="{{ session('namalengkap1x') }}" readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mt-2 text-right">
                        <h6>No.Rekening</h6>                        
                    </div>
                    <div class="col-md-7">
                        <input name="ecard1x" id="ecard1x" class="w3-input w3-border" type="text" placeholder="Nomoe Rekening" value="{{ session('ecard1x') }}" readonly>                         
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mt-2 text-right">
                        <h6>Produk</h6> 
                        <input name="idproduk1x" id="idproduk1x" type="hidden" value="{{ session('idproduk1x') }}">
                    </div>
                    <div class="col-md-7">
                        <input name="produk1x" id="produk1x" class="w3-input w3-border" type="text" placeholder="Produk" value="{{ session('produk1x') }}" readonly>
                    </div>
                </div>

            </div>
            
            <div class="col-md-3">
                
                <div class="row">
                    <div class="col-md-3 mt-2 text-right">
                        <h6>NIS</h6>                        
                    </div>
                    <div class="col-md-8">
                        <input name="nis1x" id="nis1x" class="w3-input w3-border" type="text" placeholder="NIS" value="{{ session('nis1x') }}" readonly>                         
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mt-2 text-right">
                        <h6>NISN</h6>                        
                    </div>
                    <div class="col-md-8">
                        <input name="nisn1x" id="nisn1x" class="w3-input w3-border" type="text" placeholder="NISN" value="{{ session('nisn1x') }}" readonly>  
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mt-2 text-right">
                        <h6>Kelas</h6>                        
                    </div>
                    <div class="col-md-8">
                        <input name="kelas1x" id="kelas1x" class="w3-input w3-border" type="text" placeholder="Kelas" value="{{ session('kelas1x') }}" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mt-2 text-right">
                        <h6>Saldo</h6>
                    </div>
                    <div class="col-md-8">
                        <input name="saldo1x" id="saldo1x" class="w3-input w3-border" type="text" placeholder="saldo" value="{{ session('saldo1x') }}" readonly>
                    </div>
                </div>
                
            </div>

            <div class="col-md-4">
                <div class="w3-row" align="right"><i class="fa fa-refresh" aria-hidden="true"></i>            
                    <a href="{{ url('/') }}{{ $link }}" class="btn bg-success rounded-0"><i style="font-size:18px" class="fa">&#xf021;</i> Refresh</a>            
                    <button id="btn_tambah1" name="btn_tambah1" type="button" class="btn bg-primary rounded-0"><i class="fas fa-plus"></i> Tambah</button>	            
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
							<th style="width:50px">Tanggal Transaksi</th>
							<th style="width:50px">Nomor Bukti</th>
							<th style="width:50px">Jumlah</th>
							<th style="width:50px">Tanggal Posting</th>
							<th style="width:50px">Nomor Posting</th>
							<th style="width:100px">Keterangan</th>							
                            <th style="width:10px">Action</th>
                        </tr>
                    </thead>
                    <tfoot id="show_footerdata1">
                        
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
                                <div class="col-md-4 text-right">
                                    <h6 class="mt-2">Tanggal Transaksi</h6>
                                </div>
                                <div class="col-md-8">
                                    <input name="tgltransaksi1" id="tgltransaksi1" class="w3-input w3-border" maxlength="10" type="text" placeholder="Tgl Transaksi" required autocomplete="off" value="{{ session('tgltransaksi1') }}">                       
                                    <input name="cek1" id="cek1" class="" type="hidden">                                
                                    <input name="id1" id="id1" class="" type="hidden">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 text-right">
                                    <h6 class="mt-2">Nomor Bukti</h6>
                                </div>
                                <div class="col-md-8 input-group">
                                    <input name="nomorbukti1" id="nomorbukti1" class="form-control w3-input w3-border rounded-0" type="search" placeholder="PJM.20230131.001" disabled value="{{ session('nomorbukti1') }}">                        
                                    <div class="input-group-append">
                                      <button id="btn_nomorbukti1" name="btn_nomorbukti1" type="button" style="border-radius:0px; border:none;" title="Generate Nomor Bukti" disabled><i style="font-size:24" class="fa">&#xf013;</i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mt-1" align="right">									
                                    <h6 class="mt-2">Jumlah</h6>
                                </div>
                                <div class="col-md-8">                                
                                    <input name="jml1" id="jml1" class="w3-input w3-border text-right" type="search" placeholder="harga" value="0" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                </div>								  
                            </div>
                            
                            
                            <div class="row">
                                <div class="col-md-4" align="right">										
                                    <h6 class="mt-2">Keterangan</h6>
                                </div>
                                <div class="col-md-8">                                
                                    <input name="keterangan1" id="keterangan1" class="w3-input w3-border" type="search" placeholder="Keterangan">
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
						<h3 class="modal-title"><i style="font-size:18" class="fas">&#xf002;</i><b> Cari Data Siswa</b></h3>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
						
				</div>
				<form class="form-horizontal">
                    @csrf
					<div class="modal-body">
												
						<div class="row">
							<div id="reload" class="table-responsive">
								<table id="carisiswa" width="100%" class="table table-bordered table-striped table-hover">
									<thead>
									<tr>
										<th width="10px">#</th>
										<th width="50px">No.Rekening</th>																									
										<th width="50px">NIS</th>																									
										<th width="50px">NISN</th>																									
										<th width="150px">Nama Siswa</th>																									
										<th width="30px">Kelas</th>																									
										<th width="30px">Saldo</th>																									
									</tr>
								</thead>
								
								<tfoot id="show_footercarisiswa">
									
								</tfoot>
								<tbody id="show_datacarisiswa">
								
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
                    <h3 class="modal-title"><i style="font-size:18" class="fa">&#xf093;</i><b> Posting Tabungan Tarik</b></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    
            </div>
            <form class="form-horizontal">
                @csrf
                <div class="modal-body">
                    
                        <div class="row">
                            <div class="col-md-5" align="right">										
                                <h4 class="mt-2">Tgl. Transaksi</h4>
                            </div>
                            <div class="col-md-1" align="center">                                
                                <h4 class="mt-2">:</h4>
                            </div>								  
                            <div class="col-md-6">                                
                                <h4 class="mt-2"></h4>
                                <h4 class="mt-2">
                                    <span  id="tgltransaksi5" name="tgltransaksi5" style="display: none"></span>
                                    <span  id="tgltransaksi5x" name="tgltransaksi5x" style="display: block"></span>
                                </h4>
                            </div>								  
                        </div> 			
                        <div class="row">
                            <div class="col-md-5" align="right">										
                                <h4 class="mt-2">Nomor Bukti</h4>
                            </div>
                            <div class="col-md-1" align="center">                                
                                <h4 class="mt-2">:</h4>
                            </div>								  
                            <div class="col-md-6">                                
                                <h4 class="mt-2"><span  id="nomorbukti5" name="nomorbukti5"></span></h4>
                            </div>								  
                        </div> 			
                        <div class="row">
                            <div class="col-md-5" align="right">										
                                <h4 class="mt-2">Tgl. Posting</h4>
                            </div>
                            <div class="col-md-1" align="center">                                
                                <h4 class="mt-2">:</h4>
                            </div>								  
                            <div class="col-md-6"> 
                                <h4 class="mt-2"><span  id="tglposting5" name="tglposting5"></span></h4> 
                            </div>								  
                        </div>  			
                        <div class="row">
                            <div class="col-md-5" align="right">										
                                <h4 class="mt-2">Nomor Posting</h4>
                            </div>
                            <div class="col-md-1" align="center">                                
                                <h4 class="mt-2">:</h4>
                            </div>								  
                            <div class="col-md-6">
                                <h4 class="mt-2"><span  id="nomorposting5" name="nomorposting5"></span></h4>
                            </div>								  
                        </div>
                        <div class="row">
                            <div class="col-md-5" align="right">										
                                <h4 class="mt-2">Jumlah</h4>
                            </div>
                            <div class="col-md-1" align="center">                                
                                <h4 class="mt-2">:</h4>
                            </div>								  
                            <div class="col-md-6">
                                <h4 class="mt-2"><span  id="jml5" name="jml5"></span></h4>                                                                
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

    <!-- khusus menyimpan data yang akan dihapus -->
    <input name="id3" id="id3"type="hidden">	
    <input name="data3a" id="data3a"type="hidden">	
    <input name="data3b" id="data3b"type="hidden">	
    <input name="data3c" id="data3c"type="hidden">	
    <input name="data3d" id="data3d"type="hidden">	
    <input name="data3e" id="data3e"type="hidden">	
    <input name="data3f" id="data3f"type="hidden">	
    <input name="data3g" id="data3g"type="hidden">	
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
$(document).ready(function(){

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
            processing: true,
            serverSide: true,
            ajax   : `{{route('sibm01.transaksi.ttarik_show')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'updated_at', name: 'updated_at' },
                { data: 'nomorbukti', name: 'nomorbukti' },
                { data: 'jml', name: 'jml', class: 'text-right'},
                { data: 'tglposting', name: 'tglposting' },
                { data: 'nomorposting', name: 'nomorposting' },
                { data: 'keterangan', name: 'keterangan' },
                { data: 'action', name: 'action', class: 'text-center' },
            ],
                "createdRow": function( row, data, dataIndex){                                                        
                    if( data['nomorposting']== null){                        
                        $(row).css('background-color', 'pink');                    
                    }
                }
        });
    }

   
     carisiswaDatatable = tampil_data_carisiswa();    
     function tampil_data_carisiswa(){
        let i = 1;	
        return $('#carisiswa').DataTable({
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
            ajax   : `{{route('sibm01.transaksi.ttarik_shownasabah')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'ecard', name: 'ecard' },
                { data: 'nis', name: 'nis' },
                { data: 'nisn', name: 'nisn'},
                { data: 'nama', name: 'namalengkap'},
                { data: 'kelas', name: 'kelas'},
                { data: 'saldo', name: 'saldo', class: 'text-right'}
                
                               
                // { data: 'action', name: 'action'},
            ]
        });
    }

    $('#cariid1x').keypress(function (e) {        
        $('#ecard1x').val('');
        $('#nis1x').val('');
        $('#nisn1x').val('');
        $('#namalengkap1x').val('');
        $('#kelas1x').val('');
        $('#produk1x').val('');
        if (e.which == 13) {
        var x = $('#cariid1x').val();
            cariid(x);
            setTimeout(() => {
                var x = $('#ecard1x').val();
                if (x==''){
                    $('#btn_cariid1x').click();  
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
            url    : '{{route('sibm01.transaksi.ttarik_cariid')}}',
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
                        $('#idnasabah1x').val(resultData[i].id);
                        $('#namalengkap1x').val(resultData[i].namalengkap); 
                        $('#ecard1x').val(resultData[i].ecard);
                        $('#nis1x').val(resultData[i].nis);
                        $('#nisn1x').val(resultData[i].nisn);
                        $('#kelas1x').val(resultData[i].kelas);
                        $('#idproduk1x').val(resultData[i].idproduk);
                        $('#produk1x').val(resultData[i].produk.produk);
                        $('#saldo1x').val(formatAngka(resultData[i].saldo,''));
                }
                               
                },
            error : function(data){ 
                    alert('error');
                }
        });
    }

    $('#btn_cariid1x').on('click',function(){
        carisiswaDatatable.draw(null, false);
        setTimeout(() => {
            $('#ModalCariID').modal('show');
        }, 300);
    });

    $('#show_datacarisiswa').on('click','.item_ecard',function(){
        ambilcari(this);
    });

    $('#show_datacarisiswa').on('click','.item_nis',function(){
        ambilcari(this);
    });
    $('#show_datacarisiswa').on('click','.item_nisn',function(){
        ambilcari(this);
    });
    $('#show_datacarisiswa').on('click','.item_nama',function(){
        ambilcari(this);
    });
    $('#show_datacarisiswa').on('click','.item_kelas',function(){
        ambilcari(this);        
    });

    function ambilcari(t){
        var data1 = $(t).attr('data1');
        var data2 = $(t).attr('data2');
        var data3 = $(t).attr('data3');
        var data4 = $(t).attr('data4');
        var data5 = $(t).attr('data5');
        var data6 = $(t).attr('data6');
        var data7 = $(t).attr('data7');
        var data8 = $(t).attr('data8');
        var data9 = $(t).attr('data9');
       
        $('#id3').val(data1);
        $('#data3a').val(data2);
        $('#data3b').val(data3);
        $('#data3c').val(data4);
        $('#data3d').val(data5);
        $('#data3e').val(data6);
        $('#data3f').val(data7);
        $('#data3g').val(data8);
        $('#data3h').val(data9);
        $('#ModalCariID').modal('hide');
        
        $('#saldo1x').val(formatAngka(data9,''));
        $('#kelas1x').val(data6);
        $('#ecard1x').val(data5);
        $('#nis1x').val(data2);
        $('#nisn1x').val(data3);
        $('#namalengkap1x').val(data4);
        $('#idnasabah1x').val(data1);
        $('#idproduk1x').val(data7);
        $('#produk1x').val(data8);
        
        $('#cariid1x').val(data5);    
        setTimeout(() => {
            kirimsyarat();
            setTimeout(() => {
                tampil_datatable();
            }, 300);
        }, 300);
    }


    $('#btn_posting5').on('click',function(){
        modal_posting();
    });
    
    $("#tgltransaksi1").datepicker({
           dateFormat  : "yy-mm-dd",
           changeMonth : true,
           changeYear  : true         
    });
    
    $('#tgltransaksi1').on('click',function(){
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
        nomorbukti();              
    });
    
    $("#nomorbukti1").on('keyup',function(){  
       setTimeout(() => {
           kirimsyarat();
           setTimeout(() => {                            
               setTimeout(() => {
                    tampil_datatable();    
                }, 100);
           }, 100);        
       }, 100);
    });
    $("#nomorbukti1").on('change',function(){  
       setTimeout(() => {
           kirimsyarat();
           setTimeout(() => {            
               tampil_data();  
               tampil_tombol();
               setTimeout(() => {
                    tampil_datatable();    
                }, 100);               
           }, 100);        
       }, 100);
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
    
    function tampil_datatable(){        
        data1Datatable.draw(null, false);               
    }

    function kirimsyarat(){
        var cariid1x=$('#cariid1x').val();
        var namalengkap1x=$('#namalengkap1x').val();
        var nis1x=$('#nis1x').val();
        var nisn1x=$('#nisn1x').val();
        var kelas1x=$('#kelas1x').val();
        var saldo1x=$('#saldo1x').val();
        var idnasabah1x=$('#idnasabah1x').val();
        var ecard1x=$('#ecard1x').val();
        var idproduk1x=$('#idproduk1x').val();
        var produk1x=$('#produk1x').val();
        var tgltransaksi1=$('#tgltransaksi1').val();
        var nomorbukti1=$('#nomorbukti1').val();
        var tglawal1x=$('#data3z').val();
        var tglakhir1x=$('#data3z').val();

        let formData = new FormData();
            formData.append('cariid1x', cariid1x);
            formData.append('namalengkap1x', namalengkap1x);
            formData.append('nis1x', nis1x);
            formData.append('nisn1x', nisn1x);
            formData.append('kelas1x', kelas1x);
            formData.append('saldo1x', saldo1x);
            formData.append('idnasabah1x', idnasabah1x);
            formData.append('ecard1x', ecard1x);
            formData.append('idproduk1x', idproduk1x);
            formData.append('produk1x', produk1x);
            formData.append('tgltransaksi1', tgltransaksi1);
            formData.append('nomorbukti1', nomorbukti1);
            formData.append('tglawal1x', tglawal1x);
            formData.append('tglakhir1x', tglakhir1x);
    
        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('sibm01.transaksi.ttarik_kirimsyarat')}}',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },				 				
            success : function(formData){                                                       
                if($('#cekprint').val()=='1'){
                    $('#cekprint').val("0");
                }else{
                    tampil_datatable();                
                }                
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
    
    $('#btn_tambah1').on('click',function(){
        var saldo1x =$('#saldo1x').val().replace(/[^,\d]/g, '').toString();
        if(saldo1x<=0){
            swalsaldotidakcukup('');
        }else{
            let x = $('#namalengkap1x').val();
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
        }
        
    }); 
    
    function data_simpan(){
        
        var id1=$('#id1').val();			
        var tgltransaksi1=$('#tgltransaksi1').val();
        var nomorbukti1=$('#nomorbukti1').val();
        var idnasabah1=$('#idnasabah1x').val();
        var jml1=$('#jml1').val().replace(/[^,\d]/g, '').toString();
        var keterangan1=$('#keterangan1').val();
        
        let formData = new FormData();
            formData.append('id1', id1);
            formData.append('tgltransaksi1', tgltransaksi1);
            formData.append('nomorbukti1', nomorbukti1);
            formData.append('idnasabah1', idnasabah1);
            formData.append('jml1', jml1);
            formData.append('keterangan1', keterangan1);            
          
        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('sibm01.transaksi.ttarik_create')}}',
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
                swalgagaltambah(nomorbukti1);                 
                }
        });

    }   

    $("#btn_simpan").on('click',function(){
        
        let a = $('#tgltransaksi1').val();
        const aArray = a.split("-");
        let thn = aArray[0];
        let bln = aArray[1];
        let tgl = aArray[2];
        let b = thn+bln+tgl;

        let x = $('#nomorbukti1').val();
        const xArray = x.split(".");
        let y = xArray[0];
        let z = xArray[1];
        let j = xArray[2];
        let k = j.length;

        if(y=='TRK'&&b==z&&k=='4'){
            let saldo1x =$('#saldo1x').val().replace(/[^,\d]/g, '').toString();
            let jml1 =$('#jml1').val().replace(/[^,\d]/g, '').toString();
            let totalx = parseFloat(saldo1x) - parseFloat(jml1);
            if(totalx<0){
                swalsaldotidakcukup('');
            }else{
                data_simpan();
                setTimeout(() => {
                    tampil_datatable();
                }, 300); 
            }
        }else{            
            swalnomorbuktisalah(x);            
        }
       	
    });

    

    function nomorbukti(){        
        var tgltransaksi1=$('#tgltransaksi1').val();

        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('sibm01.transaksi.ttarik_nomorbukti')}}',
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
            url    : '{{route('sibm01.transaksi.ttarik_nomorposting')}}',
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
        data_edit(id1);

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
                url   : `{{ url('sibm01/transaksi/ttarikedit')}}/${id1}`,
                async : false,
                dataType : 'json',	
                
                success : function(data){
                    var i;                
                    var resultData = data.data;	                			
                    for(i=0; i<resultData.length; i++){
    
                        $('#tgltransaksi1').val(resultData[i].tgltransaksi);
                        $('#nomorbukti1').val(resultData[i].nomorbukti);
                        $('#jml1').val(formatAngka(resultData[i].jml,''));						
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
        var id1 = $('#id1').val();        
        var tglposting1=$('#tglposting5').text();
        var nomorposting1=$('#nomorposting5').text();        
    
        let formData = new FormData();
            formData.append('id1', id1);            
            formData.append('tglposting1', tglposting1);
            formData.append('nomorposting1', nomorposting1);            
          
        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('sibm01.transaksi.ttarik_posting')}}',
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
        var data3b=$(this).attr('data2');
        var data3c=$(this).attr('data5');
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
                setTimeout(() => {
                    tampil_datatable();
                }, 300);            
            }
        })
    } 

    function data_hapus(){			
        var id3=$('#id3').val();       
        var data3b=$('#data3b').val();
        
        $.ajax({
            type  : 'get',
            url   : '{{url('sibm01/transaksi/ttarikdestroy')}}/'+id3,
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

    $('#show_data1').on('click','.item_print',function(){
        $('#cekprint').val("1");
        var tglposting=$(this).attr('data7');
        $('#data3z').val(tglposting);
        kirimsyarat();
        setTimeout(() => {
            window.open('{{ route('sibm01.laporan.rekeningkoran.index') }}');
        }, 100);
    });

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

    function swaltidaklengkap(x){
        Swal.fire({
            icon: 'error',
            title: 'Oops...incomplete data',
            text: x,
            timer:5000
        })
    }

    function swalsaldotidakcukup(x){
        Swal.fire({
            icon: 'error',
            title: 'Oops...Insufficient balance',
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