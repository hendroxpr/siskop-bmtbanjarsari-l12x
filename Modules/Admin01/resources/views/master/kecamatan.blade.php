@extends('admin.layouts.main')

@section('contents')

@php
    $idpropinsi = session('idpropinsix1');    
    $idpropinsi1 = session('idpropinsi1');    
    $idkabupaten = session('idkabupatenx1');    
    $idkabupaten1 = session('idkabupaten1');    
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
                        <h6>Propinsi</h6>
                    </div>
                    <div class="col-md-7">
                        <select name="idpropinsix1" id="idpropinsix1" class="w3-input w3-border" value="{{ $idpropinsi }}"></select> 
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-md-3 mt-2 text-right">
                        <h6>Kabupaten</h6>
                    </div>
                    <div class="col-md-7">
                        <select name="idkabupatenx1" id="idkabupatenx1" class="w3-input w3-border" value="{{ $idkabupaten }}"></select> 
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
                        <th style="width:10px">Kode</th>							
                        <th style="width:200px">Kecamatan</th>
                        <th style="width:10px">Inisial</th>							
                        <th style="width:200px">Ibukota</th>							
                        <th style="width:10px">Action</th>
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
                                <div class="col-md-4" align="right">									
                                    <h6 class="mt-2">Propinsi</h6>
                                </div>

                                <div class="col-md-8">                                
                                    <select name="idpropinsi1" id="idpropinsi1" class="w3-input w3-border" value="{{ $idpropinsi1 }}"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4" align="right">									
                                    <h6 class="mt-2">Kabupaten</h6>
                                </div>

                                <div class="col-md-8">                                
                                    <select name="idkabupaten1" id="idkabupaten1" class="w3-input w3-border" value="{{ $idkabupaten1 }}"></select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mt-1" align="right">									
                                    <h6 class="mt-2">Kode</h6>
                                </div>
                                <div class="col-md-8">                                
                                    <input name="kode1" id="kode1" class="w3-input w3-border" type="text" maxlength="10" placeholder="Kode" autofocus value="{{ old('kode1') }}" required>
                                    <input name="cek1" id="cek1" class="" type="hidden">                                
                                    <input name="id1" id="id1" class="" type="hidden">                                
                                </div>								  
                            </div>
    
                            <div class="row">
                                <div class="col-md-4" align="right">									
                                    <h6 class="mt-2">Kecamatan</h6>
                                </div>

                                <div class="col-md-8">                                
                                    <input name="kecamatan1" id="kecamatan1" class="w3-input w3-border" type="text" maxlength="50" placeholder="Kecamatan" value="{{ old('kecamatan1') }}" required>
                                </div>
                            </div> 
                             
                            <div class="row">
                                <div class="col-md-4" align="right">										
                                    <h6 class="mt-2">Inisial</h6>
                                </div>
                                <div class="col-md-8">                                
                                    <input name="inisial1" id="inisial1" class="w3-input w3-border" type="text" maxlength="10" placeholder="Inisial" value="{{ old('inisial1') }}">
                                </div>								  
                            </div>

                            <div class="row">
                                <div class="col-md-4" align="right">										
                                    <h6 class="mt-2">Ibukota</h6>
                                </div>
                                <div class="col-md-8">                                
                                    <input name="ibukota1" id="ibukota1" class="w3-input w3-border" type="text" maxlength="50" placeholder="Ibukota" value="{{ old('ibukota1') }}">
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

    <!-- khusus menyimpan data yang akan dihapus -->
    <input name="id3" id="id3"type="hidden">	
    <input name="data3a" id="data3a"type="hidden">	
    <input name="data3b" id="data3b"type="hidden">	
    <input name="data3c" id="data3c"type="hidden">	

</div>


<script type="text/javascript">
    var data1Datatable;
    var listkabupatenx1Datatable;
    var listkabupaten1Datatable;

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

    //menampilkan combo propinsix
    setTimeout(() => {
        tampil_listpropinsix();
        tampil_listpropinsi1();
        setTimeout(() => {
            $('#idpropinsix1').click();    
        }, 500);
    }, 500);

    $('#idpropinsix1').on('click',function(){
        setTimeout(() => {
            $('#idpropinsi1').val($('#idpropinsix1').val());
            kirimsyarat();            	
            setTimeout(() => {
                listkabupatenx1Datatable = tampil_listkabupatenx();
                listkabupaten1Datatable = tampil_listkabupaten1();
                setTimeout(() => {
                    listkabupatenx1Datatable.ajax.url('{{route('admin.listkabupatenx21')}}').load();                
                    listkabupatenx1Datatable.draw(null, false);                                                    
                    listkabupaten1Datatable.ajax.url('{{route('admin.listkabupaten21')}}').load();                
                    listkabupaten1Datatable.draw(null, false);
                }, 500);            
            }, 500);
        }, 500);        
    });

    $('#idkabupatenx1').on('click',function(){
        setTimeout(() => {
            kirimsyarat();            	
            setTimeout(() => {
                $('#idkabupaten1').val($('#idkabupatenx1').val());
                data1Datatable = tampil_data1();           
            }, 500);
        }, 500);        
    });
    
    $('#idpropinsi1').on('click',function(){
        setTimeout(() => {
            kirimsyarat2();            	
            setTimeout(() => {
                listkabupaten1Datatable = tampil_listkabupaten1();
                setTimeout(() => {
                    listkabupaten1Datatable.ajax.url('{{route('admin.listkabupaten21')}}').load();                
                    listkabupaten1Datatable.draw(null, false);
                }, 500);            
            }, 500);
        }, 500);        
    });
 
    //menampilkan combo propinsix
    function tampil_listpropinsix(){				
        $.ajax({
            type: 'get',
            url   : '{{route('admin.listpropinsi11')}}',
            
            success: function(data){				    
                $("#idpropinsix1").html(data);
                $("#idpropinsix1").val({{ $idpropinsi }});
            }
        })                    
    }

    //menampilkan combo propinsi1
    function tampil_listpropinsi1(){				
        $.ajax({
            type: 'get',
            url   : '{{route('admin.listpropinsi11')}}',
            
            success: function(data){				    
                $("#idpropinsi1").html(data);
                $("#idpropinsi1").val({{ $idpropinsi }});
            }
        })                    
    }

    //menampilkan combo kabupatenx
    function tampil_listkabupatenx(){				
        $.ajax({
            type: 'get',
            url   : '{{route('admin.listkabupatenx21')}}',
            
            success: function(data){				    
                $("#idkabupatenx1").html(data);
                $("#idkabupatenx1").val({{ $idkabupaten }});
            }
        })                    
    }

    //menampilkan combo kabupaten1
    function tampil_listkabupaten1(){				
        $.ajax({
            type: 'get',
            url   : '{{route('admin.listkabupaten21')}}',
            
            success: function(data){				    
                $("#idkabupaten1").html(data);
                $("#idkabupaten1").val({{ $idkabupaten1 }});
            }
        })                    
    }

    function kirimsyarat(){
        var idpropinsix1=$('#idpropinsix1').val();
        var idkabupatenx1=$('#idkabupatenx1').val();
        
        let formData = new FormData();
            formData.append('idpropinsix1', idpropinsix1);
            formData.append('idkabupatenx1', idkabupatenx1);

        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('admin01.master.kecamatan_kirimsyarat')}}',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },				 				
            success : function(formData){ 
                $("#idkabupaten1").val(idkabupatenx1);                                        
                tampil_dataTable();
                }
        });
    }

    function kirimsyarat2(){
        var idpropinsi1=$('#idpropinsi1').val();
        var idkabupaten1=$('#idkabupaten1').val();
        
        let formData = new FormData();
            formData.append('idpropinsi1', idpropinsi1);
            formData.append('idkabupaten1', idkabupaten1);

        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('admin01.master.kecamatan_kirimsyarat2')}}',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },				 				
            success : function(formData){ 
                // tampil_dataTable();                   
                }
        });
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
            ajax   : `{{route('admin01.master.kecamatan_show')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'kode', className: 'dt-center' },
                { data: 'kecamatan', name: 'kecamatan' },
                { data: 'inisial', name: 'inisial' },
                { data: 'ibukota', name: 'ibukota' },
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
        swaltambah($('#idbarang1 option:selected').text());
        
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
        var kode1=$('#kode1').val();
        var idkabupaten1=$('#idkabupaten1').val();
        var kecamatan1=$('#kecamatan1').val();
        var inisial1=$('#inisial1').val();
        var ibukota1=$('#ibukota1').val();
        
        let formData = new FormData();
            formData.append('id1', id1);
            formData.append('kode1', kode1);
            formData.append('idkabupaten1', idkabupaten1);
            formData.append('kecamatan1', kecamatan1);
            formData.append('inisial1', inisial1);
            formData.append('ibukota1', ibukota1);
          
        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('admin01.master.kecamatan_create')}}',
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
                swalgagaltambah($('#kecamatan1').val());                 
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
		        url   : `{{ url('admin01/master/kecamatanedit')}}/${id1}`,
		        async : false,
		        dataType : 'json',	
				
		        success : function(data){
                    var i;                
                    var resultData = data.data;	                			
                    for(i=0; i<resultData.length; i++){
                        $('#id1').val(resultData[i].id);
                        $('#idpropinsi1').val($('#idpropinsix1').val());
                        $('#idkabupaten1').val(resultData[i].idkabupaten);
                        $('#kode1').val(resultData[i].kode);
                        $('#kecamatan1').val(resultData[i].kecamatan);
                        $('#inisial1').val(resultData[i].inisial);
                        $('#ibukota1').val(resultData[i].ibukota);
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
            url   : '{{url('admin01/master/kecamatandestroy')}}/'+id3,
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