@extends('admin.layouts.main')

@section('contents')

<div class="container-fluid px-0">
    <div class="box-header mb-3">  
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-3">
                        {{--  --}}
                    </div>
                    <div class="col-md-7">
                        {{--  --}}
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
        <div class="card mt-5" style="display: none;">
            <h3 class="card-header p-3">Laravel 12 Simple Pagination Example - ItSolutionStuff.com</h3>
            <div class="card-body">
                
                <form action="{{ route('admin.menuutama_index') }}" method="GET" class= 'mb-2'>
                    <input type="search" name="search" id="search" value="{{ $search ?? '' }}" class="w3-input w3-border" placeholder="Cari desa/kelurahan..." autocomplete="off">
                    <button type="submit" name="btn_cari" id="btn_cari" style="display:none;">Cari</button>
                </form>

                <table id="" class="table table-bordered table-striped table-hover mb-2" style="width: 100%">
                    
                    <thead>
                        <tr style="background-color:lightblue">
                            <th style="width:10px;"># </th>                            
							<th style="width:100px">Desa</th>							
							<th style="width:100px">Kecamatan</th>							
							<th style="width:100px">Kabupaten</th>							
							<th style="width:100px">Propinsi</th>							
							
                        </tr>
                    </thead>
                    <tfoot id="show_footer">
                        
                    </tfoot>
                    <tbody id="show_datax">
                        @php
                            $x=1;
                            $nomor = $tabelx->firstItem();
                        @endphp
                        
                        @forelse ($tabelx as $item)
                            <tr>
                                <td align="center">{{ $nomor++ }}</td>
                                <td>{{ $item->desa }}</td>
                                <td>{{ $item->kecamatan->kecamatan }}</td>
                                <td>{{ $item->kecamatan->kabupaten->kabupaten }}</td>
                                <td>{{ $item->kecamatan->kabupaten->propinsi->propinsi }}</td>
                            </tr>
                         @empty
                            <tr>
                                <td colspan="5">Maaf data yang dicari tidak ada..</td>
                            </tr>
                            @php
                                $x++;
                            @endphp        
                        @endforelse
                    </tbody>
                </table>				
                {!! $tabelx->withQueryString()->links('pagination::bootstrap-5') !!}
                
            </div>
        </div>    
    <!--akhir tabel-->




    <!--awal tabel-->        
        <div class="box-body" id="headerjudul" style="display: block;">
            <div id="reload" class="table-responsive">
                <table id="example1" class="table table-bordered table-striped table-hover" style="width: 100%">
                    <thead>
                        <tr style="background-color:lightblue">
                            <th style="width:10px;">#</th>                            
							<th style="width:100px">Nama Menu</th>							
							<th style="width:10px">Aktif</th>
							<th style="width:10px">Admin<br>Menu</th>
							<th style="width:10px">User<br>Menu</th>
							<th style="width:10px">Entry<br>Menu</th>
							<th style="width:10px">Urutan</th>							
                            <th style="width:10px">Action</th>
                        </tr>
                    </thead>
                    <tfoot id="show_footer">
                        
                    </tfoot>
                    <tbody id="show_data">
                    
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
                                    <h6 class="mt-2">Nama Menu *)</h6>
                                </div>
                                <div class="col-md-8">                                
                                    <input name="menu1" id="menu1" class="w3-input w3-border" type="text" placeholder="Nama Menu" autofocus value="{{ old('menu1') }}" required>
                                    <input name="cek1" id="cek1" class="" type="hidden">                                
                                    <input name="id1" id="id1" class="" type="hidden">                                
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
                                <div class="col-md-4" align="right">									
                                    <h6 class="mt-2">Admin Menu</h6>
                                </div>

                                <div class="col-md-8 mt-1" style="padding-left: 20px;">
                                    <div class="icheck-primary-white d-inline">
                                      <input type="radio" value='Y' id="admin1xy" name="admin1x">
                                      <label for="admin1xy">
                                        <span class="text" style="padding-left: 2px; padding-right: 15px;">Y</span>
                                      </label>
                                    </div>
                                    <div class="icheck-primary-white d-inline text-white">
                                      <input type="radio" value='N' id="admin1xn" name="admin1x"  checked>
                                      <label for="admin1xn">
                                        <span class="text" style="padding-left: 2px; padding-right: 15px;">N</span>
                                      </label>
                                    </div>
                                    <input name="admin1" id="admin1" type="hidden">
                                </div>
                            </div>                     
                                                    
                            <div class="row">
                                <div class="col-md-4" align="right">									
                                    <h6 class="mt-2">User Menu</h6>
                                </div>

                                <div class="col-md-8 mt-1" style="padding-left: 20px;">
                                    <div class="icheck-primary-white d-inline">
                                      <input type="radio" value='Y' id="user1xy" name="user1x">
                                      <label for="user1xy">
                                        <span class="text" style="padding-left: 2px; padding-right: 15px;">Y</span>
                                      </label>
                                    </div>
                                    <div class="icheck-primary-white d-inline text-white">
                                      <input type="radio" value='N' id="user1xn" name="user1x"  checked>
                                      <label for="user1xn">
                                        <span class="text" style="padding-left: 2px; padding-right: 15px;">N</span>
                                      </label>
                                    </div>
                                    <input name="user1" id="user1" type="hidden">
                                </div>

                            </div> 
    
                            <div class="row">
                                <div class="col-md-4" align="right">									
                                    <h6 class="mt-2">Entry Menu</h6>
                                </div>

                                <div class="col-md-8 mt-1" style="padding-left: 20px;">
                                    <div class="icheck-primary-white d-inline">
                                      <input type="radio" value='Y' id="entry1xy" name="entry1x">
                                      <label for="entry1xy">
                                        <span class="text" style="padding-left: 2px; padding-right: 15px;">Y</span>
                                      </label>
                                    </div>
                                    <div class="icheck-primary-white d-inline text-white">
                                      <input type="radio" value='N' id="entry1xn" name="entry1x"  checked>
                                      <label for="entry1xn">
                                        <span class="text" style="padding-left: 2px; padding-right: 15px;">N</span>
                                      </label>
                                    </div>
                                    <input name="entry1" id="entry1" type="hidden">
                                </div>

                            </div> 
                             
                            <div class="row">
                                <div class="col-md-4" align="right">										
                                    <h6 class="mt-2">Urutan</h6>
                                </div>
                                <div class="col-md-8">                                
                                    <input name="urutan1" id="urutan1" class="w3-input w3-border" type="number" placeholder="urutan" onkeydown='return numbersonly(this, event);' style="text-align:right;">
                                </div>								  
                            </div>
                                
                                <div class="row">
                                    <div class="col-md-4" align="right">										
                                        <label class="control-label"><h6 class="mt-3"><b>*) Wajib diisi.</b></h6></label>
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
   
$(document).ready(function(){
    
    
    tampil_data();  
    tampil_tombol();

    function tampil_tombol(){
        $('#example1').DataTable( {
            "responsive": true, "lengthChange": false, "autoWidth": false, "serverSide": false,
            buttons : [ {extend: 'colvis', postfixButtons: [ 'colvisRestore' ] }, {extend:'copy'}, {extend:'csv'}, {extend: 'pdf', orientation: 'portrait', pageSize: 'A4', title:'{{ $caption }}'}, {extend: 'excel', title: '{{ $caption }}'}, {extend:'print', orientation: 'portrait', pageSize: 'A4', title: '{{ $caption }}'}, ],
		    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');   
     } 

    //tampilkan dalam tabel ->OK
    function tampil_data(){	
        $.ajax({
            type  : 'get',
            url   : `{{route('admin.menuutama_show')}}`,
            async : false,
            dataType : 'json',
            				 				
            success : function(data){
                var html = '';
                var i;                
                var resultData = data.data;	                			
                for(i=0; i<resultData.length; i++){
                    
                    html += '<tr>'+
                            '<td align="center">'+ (i+1) +'</td>'+                            								
                            '<td>'+resultData[i].namamenu+'</td>'+
                            '<td>'+resultData[i].aktif+'</td>'+
                            '<td>'+resultData[i].adminmenu+'</td>'+
                            '<td>'+resultData[i].usermenu+'</td>'+
                            '<td>'+resultData[i].entrymenu+'</td>'+
                            '<td>'+resultData[i].urutan+'</td>'+  
                            '<td style="text-align:center;">'+
                                '<a href="javascript:;" title="Edit Data"  class="btn btn-success btn-xs item_edit" data="'+resultData[i].id+'" data2="'+resultData[i].namamenu+'" data3="'+resultData[i].namamenu+'" data4="'+resultData[i].urutan+'"><i style="font-size:18px" class="fa">&#xf044;</i></a>'+ ' ' +
                                '<a href="javascript:;" title="Hapus Data"  class="btn btn-danger btn-xs item_hapus" ' +
                                    'data="'+resultData[i].id+'" data2="'+resultData[i].namamenu+'" data3="'+resultData[i].namamenu+'" data4="'+resultData[i].urutan+'" ' +
                                    '><i style="font-size:18px" class="fa">&#xf00d;</i></a>'+
                            '</td>'+
                            '</tr>';

                }

                $('#show_data').html(html); 
                                            
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
    
    

     $('#btn_baru').on('click',function(){
        btn_baru_click();            
    });
    
    //tambah data -> ok
    $('#btn_baru').on('click',function(){
        btn_baru_click();            
    });

    $('#btn_tambah1').on('click',function(){
        btn_baru_click();

        $('[name="menu1"]').val("");
			
			$('[name="aktif1"]').val("N");
			if ($('[name="aktif1"]').val()=='Y'){
				document.getElementById("aktif1xy").checked = true
			}else{
				document.getElementById("aktif1xn").checked = true
			}
			$('[name="admin1"]').val("Y");
			if ($('[name="admin1"]').val()=='Y'){
				document.getElementById("admin1xy").checked = true
			}else{
				document.getElementById("admin1xn").checked = true
			}			
			$('[name="user1"]').val("Y");
			if ($('[name="user1"]').val()=='Y'){
				document.getElementById("user1xy").checked = true
			}else{
				document.getElementById("user1xn").checked = true
			}
			$('[name="entry1"]').val("Y");
			if ($('[name="entry1"]').val()=='Y'){
				document.getElementById("entry1xy").checked = true
			}else{
				document.getElementById("entry1xn").checked = true
			}
			$('[name="urutan1"]').val("0");


        $("#iconx").removeClass("fas fa-edit");
        $("#iconx").addClass("fas fa-plus-square");
        $("#modalx").removeClass("modal-content bg-success w3-animate-zoom");
        $("#modalx").addClass("modal-content bg-primary w3-animate-zoom");
        document.getElementById("btn_simpan").disabled = false;
        $('#ModalAdd').modal('show');
        $('#id1').val('0');
        $('#judulx').html(' Tambah Data');
    }); 

        $('#aktif1xy').on('change',function(){				
			$('#aktif1').val("Y");						
        });
		$('#aktif1xn').on('change',function(){				
			$('#aktif1').val("N");						
        });
		
		$('#admin1xy').on('change',function(){				
			$('#admin1').val("Y");						
        });
		$('#admin1xn').on('change',function(){				
			$('#admin1').val("N");						
        });
		
		$('#user1xy').on('change',function(){				
			$('#user1').val("Y");						
        });
		$('#user1xn').on('change',function(){				
			$('#user1').val("N");						
        });
		
		$('#entry1xy').on('change',function(){				
			$('#entry1').val("Y");						
        });
		$('#entry1xn').on('change',function(){				
			$('#entry1').val("N");						
        });
		
		$('#urutan1').on('keyup',function(){
			if($('#urutan1').val().length==0){
				$('#urutan1').val('0');
			}
		});	

    function data_simpan(){
        var id1=$('#id1').val();			
        var menu1=$('#menu1').val();
        var aktif1=$('#aktif1').val();
        var admin1=$('#admin1').val();
        var user1=$('#user1').val();
        var entry1=$('#entry1').val();
        var urutan1=$('#urutan1').val();        

        let formData = new FormData();
            formData.append('id1', id1);
            formData.append('menu1', menu1);
            formData.append('aktif1', aktif1);
            formData.append('admin1', admin1);
            formData.append('user1', user1);
            formData.append('entry1', entry1);
            formData.append('urutan1', urutan1);
          
        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('admin.menuutama_create')}}',
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
                swalgagaltambah(); 
                
                }
        });
        
    }   

    $("#btn_simpan").on('click',function(){
        data_simpan();	
    });

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
		        url   : '{{url('/admin/menuutamaedit')}}/'+id1,
		        async : false,
		        dataType : 'json',	
				
		        success : function(data){
                    var i;                
                    var resultData = data.data;	                			
                    for(i=0; i<resultData.length; i++){

						$('#menu1').val(resultData[i].namamenu);						
						$('#aktif1').val(resultData[i].aktif);
						$('#admin1').val(resultData[i].adminmenu);
						$('#user1').val(resultData[i].usermenu);
						$('#entry1').val(resultData[i].entrymenu);
						$('#urutan1').val(resultData[i].urutan);
					
						if ($('[name="aktif1"]').val()=='Y'){
							document.getElementById("aktif1xy").checked = true
						}else{
							document.getElementById("aktif1xn").checked = true
						}
						
						if ($('[name="admin1"]').val()=='Y'){
							document.getElementById("admin1xy").checked = true
						}else{
							document.getElementById("admin1xn").checked = true
						}			
						
						if ($('[name="user1"]').val()=='Y'){
							document.getElementById("user1xy").checked = true
						}else{
							document.getElementById("user1xn").checked = true
						}
						
						if ($('[name="entry1"]').val()=='Y'){
							document.getElementById("entry1xy").checked = true
						}else{
							document.getElementById("entry1xn").checked = true
						}	
                    }
                    
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

    function data_hapus(){			
        var id3=$('#id3').val();       
        
        $.ajax({
            type  : 'get',
            url   : '{{url('/admin/menuutamadelete')}}/'+id3,
            async : false,
            dataType : 'json',					
            success : function(data){
                tampil_data();
                swalhapus(); 
            },
            error : function(data){
                swalgagalhapus(); 
            }
        }); 

    }

    function swaltambah(){
        Swal.fire({
            icon: 'success',
            title: 'Save successfully',
            text: $('#menu1').val(),
            timer:1000
        })
    }

    function swalgagaltambah(){
        Swal.fire({
            icon: 'error',
            title: 'Oops...failed to add/update record',
            text: $('#menu1').val(),
            timer:1000
        })
    }

    function swalupdate(){
        Swal.fire({
            icon: 'success',
            title: 'Update successfully',
            text: $('#menu1').val(),
            timer:1000
        })
    }

    function swalgagalupdate(){
        Swal.fire({
            icon: 'error',
            title: 'Oops...failed to update',
            text: $('#menu1').val(),
            timer:1000
        })
    }

    function swalhapus(){
        Swal.fire({
            icon: 'success',
            title: 'Delete successfully',
            text: $('#menu1').val(),
            timer:1000
        })
    }

    function swalgagalhapus(){
        Swal.fire({
            icon: 'error',
            title: 'Oops...failed to delete',
            text: $('#menu1').val(),
            timer:1000
        })
    }

});
</script>	



@endsection