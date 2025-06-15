@extends('admin.layouts.main')

@section('contents')

@php
    $tgltransaksi = session('tgltransaksi1');   
    if($tgltransaksi==''){
        $tgltransaksi=session('memtanggal');  
    }
    $idruang = session('idruangx1');    
    $idjenisbiaya = session('idjenisbiayax1');    
    $idsupplier = session('idsupplierx1');    
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
                    <div class="col-md-3 text-right">
                        <h6 class="mt-2">Tgl. Transaksi</h6>
                    </div>
                    <div class="col-md-6">
                        <input name="tgltransaksi1" id="tgltransaksi1" class="w3-input w3-border" maxlength="10" type="text" placeholder="Tgl Transaksi" required autocomplete="off" value="{{ $tgltransaksi }}">                       
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mt-2 text-right">
                        <h6>Lokasi</h6>
                    </div>
                    <div class="col-md-6">
                        <select name="idruangx1" id="idruangx1" class="w3-input w3-border" value="{{ $idruang }}"></select> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mt-2 text-right">
                        <h6>Jenis Biaya</h6>
                    </div>
                    <div class="col-md-6">
                        <select name="idjenisbiayax1" id="idjenisbiayax1" class="w3-input w3-border" value="{{ $idjenisbiaya }}"></select> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mt-2 text-right">
                        <h6>Supplier</h6>
                    </div>
                    <div class="col-md-6">
                        <select name="idsupplierx1" id="idsupplierx1" class="w3-input w3-border" value="{{ $idsupplier }}"></select> 
                    </div>
                </div>
            </div>
            <div class="col-md-6">
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
                        <th style="width:20px">No.Bukti</th>
                        <th style="width:200px">Item Biaya</th>
                        <th style="width:10px">Satuan</th>							
                        <th style="width:10px">Qty</th>							
                        <th style="width:20px">HBS</th>							
                        <th style="width:20px">Jumlah</th>							
                        <th style="width:100px">Keterangan</th>							
                        <th style="width:50px">Action</th>
                    </tr>
                </thead>
                <tfoot id="show_footer1">
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
                                <div class="col-md-3 mt-1" align="right">									
                                    <h6 class="mt-2">Lokasi</h6>
                                </div>
                                <div class="col-md-9">
                                    <select name="idruang1" id="idruang1" class="w3-input w3-border"></select>
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-3 mt-1" align="right">									
                                    <h6 class="mt-2">Jenis Biaya</h6>
                                </div>
                                <div class="col-md-9">
                                    <select name="idjenisbiaya1" id="idjenisbiaya1" class="w3-input w3-border"></select>
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-3 mt-1" align="right">									
                                    <h6 class="mt-2">Supplier</h6>
                                </div>
                                <div class="col-md-9">
                                    <select name="idsupplier1" id="idsupplier1" class="w3-input w3-border"></select>
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-3 mt-1" align="right">									
                                    <h6 class="mt-2">Nomor Bukti</h6>
                                </div>
                                <div class="col-md-9">
                                    <input name="nomorbukti1" id="nomorbukti1" class="w3-input w3-border" maxlength="30" type="search" placeholder="nomor bukti" value="-">
                                    <input name="cek1" id="cek1" class="" type="hidden">                                
                                    <input name="id1" id="id1" class="" type="hidden">

                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-3 mt-1" align="right">									
                                    <h6 class="mt-2">Item Biaya</h6>
                                </div>
                                <div class="col-md-9">
                                    <input name="biaya1" id="biaya1" class="w3-input w3-border" maxlength="100" type="search" placeholder="item  biaya" value="{{ old('biaya1') }}" required>
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-3 mt-1" align="right">									
                                    <h6 class="mt-2">Satuan</h6>
                                </div>
                                <div class="col-md-9">
                                    <select name="idsatuan1" id="idsatuan1" class="w3-input w3-border"></select>
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-3 mt-1" align="right">									
                                    <h6 class="mt-2">Qty</h6>
                                </div>
                                <div class="col-md-9">                                
                                    <input name="qty1" id="qty1" class="w3-input w3-border text-right" maxlength="10" type="search" placeholder="qty" value="1"  onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-3 mt-1" align="right">									
                                    <h6 class="mt-2">HBS</h6>
                                </div>
                                <div class="col-md-9">   
                                    <input name="hbs1" id="hbs1" class="w3-input w3-border text-right" type="search" placeholder="Harga Beli Satuan" value="0"  onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">                             
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-3 mt-1" align="right">									
                                    <h6 class="mt-2">Jumlah</h6>
                                </div>
                                <div class="col-md-9">                                
                                    <input name="totalbeli1" id="totalbeli1" class="w3-input w3-border" type="search" placeholder="Jumlah" style="text-align: right" value="0" readonly>
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-3 mt-1" align="right">									
                                    <h6 class="mt-2">Keterangan</h6>
                                </div>
                                <div class="col-md-9">
                                    <input name="keterangan1" id="keterangan1" class="w3-input w3-border" maxlength="250" type="search" placeholder="Keterangan" value="-">
                                </div>								  
                            </div>
                            

                            {{-- <div class="row">
                                <div class="col-md-4" align="left" style="color: yellow;">									
                                    <h6 class="mt-2"><b>*) Wajib diisi</b></h6>
                                </div>                                                        
                            </div> --}}
                            
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

    <!-- khusus menyimpan data yang akan dihapus -->
    <input name="id3" id="id3"type="hidden">	
    <input name="data3a" id="data3a"type="hidden">	
    <input name="data3b" id="data3b"type="hidden">	
    <input name="data3c" id="data3c"type="hidden">	

</div>


<script type="text/javascript">
    var data1Datatable, caribarangDatatable;

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

    //menampilkan combo ruang
    setTimeout(() => {
        tampil_listruang();
        tampil_listjenisbiaya();
        tampil_listsupplier();
        tampil_listsatuan();
    }, 500);
    
    setTimeout(() => {
        $('#idruangx1').change();
        $('#idjenisbiayax1').change();
        $('#idsupplierx1').change();
        data1Datatable = tampil_data1();    
    }, 1000);

    $("#tgltransaksi1").datepicker({
           dateFormat  : "yy-mm-dd",
           changeMonth : true,
           changeYear  : true         
    });

    $('#tgltransaksi1').on('change',function(){
        setTimeout(() => {
            kirimsyarat();	
        }, 500);
    });
    $('#idruangx1').on('change',function(){
        setTimeout(() => {
            $('#idruang1').val($('#idruangx1').val());
            kirimsyarat();	
        }, 500);
    });
    $('#idjenisbiayax1').on('change',function(){
        setTimeout(() => {
            $('#idjenisbiaya1').val($('#idjenisbiayax1').val());
            kirimsyarat();	
        }, 500);
    });
    $('#idsupplierx1').on('change',function(){
        setTimeout(() => {
            $('#idsupplier1').val($('#idsupplierx1').val());
            kirimsyarat();	
        }, 500);
    });

    $("#qty1").on('change',function(){ 
        var j1 = $("#qty1").val().replace(/[^,\d]/g, '').toString();
        var k1 = $("#hbs1").val().replace(/[^,\d]/g, '').toString();  
        var j2 = $("#qty1").val().length;
        var k2 = $("#hbs1").val().length;
        if(j2=='0'||k2=='0'){
            if(j2=='0'){
                $("#qty1").val('0');
            }
            $("#totalbeli1").val('0');
        }else{
            var j3 = parseFloat(j1); 
            var k3 = parseFloat(k1); 
            $("#totalbeli1").val(formatAngka(j3*k3,''));

        }
            var totalx = $("#totalbeli1").val();

        if(j1==''||j2=='0'||k1==''||k2=='0'||totalx=='0'){
            document.getElementById("btn_simpan").disabled = true;
        }else{
            document.getElementById("btn_simpan").disabled = false;
        }
    });

    $("#qty1").on('keyup',function(){ 
        var j1 = $("#qty1").val().replace(/[^,\d]/g, '').toString();
        var k1 = $("#hbs1").val().replace(/[^,\d]/g, '').toString();  
        var j2 = $("#qty1").val().length;
        var k2 = $("#hbs1").val().length;
        if(j2=='0'||k2=='0'){
            if(j2=='0'){
                $("#qty1").val('0');
            }
            $("#totalbeli1").val('0');
        }else{
            var j3 = parseFloat(j1); 
            var k3 = parseFloat(k1); 
            $("#totalbeli1").val(formatAngka(j3*k3,''));

        }
            var totalx = $("#totalbeli1").val();

        if(j1==''||j2=='0'||k1==''||k2=='0'||totalx=='0'){
            document.getElementById("btn_simpan").disabled = true;
        }else{
            document.getElementById("btn_simpan").disabled = false;
        }
    });

    $("#hbs1").on('change',function(){ 
        var j1 = $("#qty1").val().replace(/[^,\d]/g, '').toString();
        var k1 = $("#hbs1").val().replace(/[^,\d]/g, '').toString();  
        var j2 = $("#qty1").val().length;
        var k2 = $("#hbs1").val().length;
        if(j2=='0'||k2=='0'){
            if(k2=='0'){
                $("#hbs1").val('0');
            }
            $("#totalbeli1").val('0');
        }else{
            var j3 = parseFloat(j1); 
            var k3 = parseFloat(k1); 
            $("#totalbeli1").val(formatAngka(j3*k3,''));

        }
            var totalx = $("#totalbeli1").val();

        if(j1==''||j2=='0'||k1==''||k2=='0'||totalx=='0'){
            document.getElementById("btn_simpan").disabled = true;
        }else{
            document.getElementById("btn_simpan").disabled = false;
        }
    });
    $("#hbs1").on('keyup',function(){ 
        var j1 = $("#qty1").val().replace(/[^,\d]/g, '').toString();
        var k1 = $("#hbs1").val().replace(/[^,\d]/g, '').toString();  
        var j2 = $("#qty1").val().length;
        var k2 = $("#hbs1").val().length;
        if(j2=='0'||k2=='0'){
            if(k2=='0'){
                $("#hbs1").val('0');
            }
            $("#totalbeli1").val('0');
        }else{
            var j3 = parseFloat(j1); 
            var k3 = parseFloat(k1); 
            $("#totalbeli1").val(formatAngka(j3*k3,''));

        }
            var totalx = $("#totalbeli1").val();

        if(j1==''||j2=='0'||k1==''||k2=='0'||totalx=='0'){
            document.getElementById("btn_simpan").disabled = true;
        }else{
            document.getElementById("btn_simpan").disabled = false;
        }
    });
    
    function kirimsyarat(){
        var idruangx1=$('#idruangx1').val();
        var idjenisbiayax1=$('#idjenisbiayax1').val();
        var idsupplierx1=$('#idsupplierx1').val();
        var tgltransaksi1=$('#tgltransaksi1').val();
        
        let formData = new FormData();
            formData.append('idruangx1', idruangx1);
            formData.append('idjenisbiayax1', idjenisbiayax1);
            formData.append('idsupplierx1', idsupplierx1);
            formData.append('tgltransaksi1', tgltransaksi1);

        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pos01.transaksi.biayalain_kirimsyarat')}}',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },				 				
            success : function(formData){ 
                $("#idruangx1").val(idruangx1);                                        
                $("#idjenisbiayax1").val(idjenisbiayax1);                                        
                $("#idsupplierx1").val(idsupplierx1);                                        
                $("#tgltransaksi1").val(tgltransaksi1);                                        
                tampil_dataTable();                   
                }
        });
    }
 
    //menampilkan combo ruang
    function tampil_listruang(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.transaksi.biayalain_listruang')}}',
            
            success: function(data){				    
                $("#idruangx1").html(data);
                $("#idruang1").html(data);
                $("#idruangx1").val({{ $idruang }});
                $("#idruang1").val({{ $idruang }});

            }
        })                    
    }
    //menampilkan combo jenisbiaya
    function tampil_listjenisbiaya(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.transaksi.biayalain_listjenisbiaya')}}',
            
            success: function(data){				    
                $("#idjenisbiayax1").html(data);
                $("#idjenisbiaya1").html(data);
                $("#idjenisbiayax1").val({{ $idjenisbiaya }});
                $("#idjenisbiaya1").val({{ $idjenisbiaya }});

            }
        })                    
    }
    //menampilkan combo supplier
    function tampil_listsupplier(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.transaksi.biayalain_listsupplier')}}',
            
            success: function(data){				    
                $("#idsupplierx1").html(data);
                $("#idsupplier1").html(data);
                $("#idsupplierx1").val({{ $idsupplier }});
                $("#idsupplier1").val({{ $idsupplier }});

            }
        })                    
    }
    //menampilkan combo satuan
    function tampil_listsatuan(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.transaksi.biayalain_listsatuan')}}',
            
            success: function(data){				    
                $("#idsatuan1").html(data);

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
            totalbeli = api
                .column(6)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Total over this page
            pagetotalbeli = api
                .column(6, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(2).footer().innerHTML = 'SUB TOTAL :';
            api.column(6).footer().innerHTML = formatAngka(pagetotalbeli,'');            
            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.transaksi.biayalain_show')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nomorbukti', name: 'nomorbukti', className: 'dt-center' },
                { data: 'biaya', name: 'biaya', className: 'dt-left' },
                { data: 'satuan', name: 'satuan.satuan', className: 'dt-center' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'hbs', name: 'hbs', className: 'dt-right' },
                { data: 'totalbeli', name: 'totalbeli', className: 'dt-right' },
                { data: 'keterangan', name: 'keterangan', className: 'dt-left' },
                { data: 'action', name: 'action', className: 'dt-center' },
            ]
        });
    }

    function tampil_dataTable(){        
        data1Datatable.draw(null, false);        
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
        swaltambah( $("#biaya1").val());
        
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
       
    }); 

    function data_simpan(){
        var id1=$('#id1').val();			
        var tgltransaksi1=$('#tgltransaksi1').val();
        var idruang1=$('#idruang1').val();
        var idsupplier1=$('#idsupplier1').val();
        var idjenisbiaya1=$('#idjenisbiaya1').val();
        var idsatuan1=$('#idsatuan1').val();
        var nomorbukti1=$('#nomorbukti1').val();
        var biaya1=$('#biaya1').val();
        var qty1=$('#qty1').val().replace(/[^,\d]/g, '').toString();
        var hbs1=$('#hbs1').val().replace(/[^,\d]/g, '').toString();
        var totalbeli1=$('#totalbeli1').val().replace(/[^,\d]/g, '').toString();
        var keterangan1=$('#keterangan1').val();
        
        let formData = new FormData();
            formData.append('id1', id1);
            formData.append('tgltransaksi1', tgltransaksi1);
            formData.append('idruang1', idruang1);
            formData.append('idsupplier1', idsupplier1);
            formData.append('idjenisbiaya1', idjenisbiaya1);
            formData.append('idsatuan1', idsatuan1);
            formData.append('nomorbukti1', nomorbukti1);
            formData.append('biaya1', biaya1);
            formData.append('qty1', qty1);
            formData.append('hbs1', hbs1);
            formData.append('totalbeli1', totalbeli1);
            formData.append('keterangan1', keterangan1);
          
        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pos01.transaksi.biayalain_create')}}',
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
                swalgagaltambah($('#biaya1').val());                 
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
    
    function data_edit(idx){
        
        var id1=idx;			
            $.ajax({
		        type  : 'get',
		        url   : `{{ url('pos01/transaksi/biayalainedit')}}/${id1}`,
		        async : false,
		        dataType : 'json',	
				
		        success : function(data){
                    var i;                
                    var resultData = data.data;	                			
                    for(i=0; i<resultData.length; i++){

                            $('#id1').val(resultData[i].id);
                            $('#idruang1').val(resultData[i].idruang);
                            $('#idjenisbiaya1').val(resultData[i].idjenisbiaya);
                            $('#idsupplier1').val(resultData[i].idsupplier);
                            $('#idsatuan1').val(resultData[i].idsatuan);
                            $('#nomorbukti1').val(resultData[i].nomorbukti);
                            $('#biaya1').val(resultData[i].biaya);
                            $('#qty1').val(resultData[i].qty);
                            $('#hbs1').val(formatAngka(resultData[i].hbs,''));
                            $('#totalbeli1').val(formatAngka(resultData[i].totalbeli,''));
                            $('#keterangan1').val(resultData[i].keterangan);
                            
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
            url   : '{{url('pos01/transaksi/biayalaindestroy')}}/'+id3,
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