@extends('admin.layouts.main')

@section('contents')

@php
    $idruang = session('idruang1');    
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
                    <div class="col-md-2 mt-2 text-right">
                        <h6>Ruang</h6>
                    </div>
                    <div class="col-md-7">
                        <select name="idruang1" id="idruang1" class="w3-input w3-border" value="{{ $idruang }}"></select> 
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
                        <th style="width:50px">Kode</th>
                        <th style="width:50px">Barcode</th>
                        <th style="width:150px">Nama Barang</th>							
                        <th style="width:20px">HPP</th>							
                        <th style="width:20px">Qty</th>							
                        <th style="width:20px">Stok Min</th>							
                        <th style="width:20px">Stok Max</th>							
                        <th style="width:20px">Aktif</th>
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
                                    <h6 class="mt-2">Ruang</h6>
                                </div>
                                <div class="col-md-8">
                                    <select name="idruangx1" id="idruangx1" class="w3-input w3-border"></select>
                                </div>								  
                            </div>
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

    //menampilkan combo ruang
    setTimeout(() => {
        tampil_listruang();
        tampil_listruangx();
    }, 500);
    
    setTimeout(() => {
        $('#idruang1').change();
        data1Datatable = tampil_data1();   
    }, 1000);

    $('#idruang1').on('change',function(){
        setTimeout(() => {
            $('#idruangx1').val($('#idruang1').val());
            kirimsyarat();	
        }, 500);
    });
    
    function kirimsyarat(){
        var idruang1=$('#idruang1').val();
        
        let formData = new FormData();
            formData.append('idruang1', idruang1);

        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pos01.master.barangruang_kirimsyarat')}}',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },				 				
            success : function(formData){ 
                $("#idruang1").val(idruang1);                                        
                tampil_dataTable();                   
                }
        });
    }
 
    //menampilkan combo ruang
    function tampil_listruang(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.master.barangruang_listruang')}}',
            
            success: function(data){				    
                $("#idruang1").html(data);
                $("#idruang1").val({{ $idruang }});
                $("#idruangx1").val({{ $idruang }});

            }
        })                    
    }
    //menampilkan combo ruangx
    function tampil_listruangx(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.master.barangruang_listruang')}}',
            
            success: function(data){				    
                $("#idruangx1").html(data);

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
            ajax   : `{{route('pos01.master.barangruang_show')}}`,
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
                { data: 'hbs', name: 'barang.hbs', className: 'dt-right' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'stokmin', name: 'stokmin', className: 'dt-center' },
                { data: 'stokmax', name: 'stokmax', className: 'dt-center' },
                { data: 'aktif', name: 'aktif', className: 'dt-center' },
                { data: 'action', name: 'action', className: 'dt-center' },
            ]
        });
    }

    function tampil_dataTable(){        
        data1Datatable.draw(null, false);        
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
        var idruang1=$('#idruangx1').val();
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
                            $('#idruangx1').val(resultData[i].idruang);

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