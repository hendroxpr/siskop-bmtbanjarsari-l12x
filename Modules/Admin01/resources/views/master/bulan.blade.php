@extends('admin.layouts.main')

@section('contents')

@php
    $tahunx1 = session('tahunx1');    
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
                        <h6>Tahun</h6>
                    </div>
                    <div class="col-md-4">
                        <input name="tahunx1" id="tahunx1" class="w3-input w3-border" maxlength="4" type="number" placeholder="Tahun" autofocus value="{{ session('tahunx1') }}" onkeypress="return hanyaangka(event)"> 
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
                <table id="example1" class="table table-bordered table-striped table-hover" style="width: 100%">
                    <thead>
                        <tr>
                            <th style="width:10px;">#</th>                            
							<th style='width:50px'>Tahun</th>
							<th style='width:100px'>Bulan</th>
							<th style='width:50px'>Tanggal Awal</th>
							<th style='width:50px'>Tanggal Akhir</th>
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

      
    <!-- khusus menyimpan data yang akan dihapus -->
    <input name="id3" id="id3"type="hidden">	
    <input name="data3a" id="data3a"type="hidden">	
    <input name="data3b" id="data3b"type="hidden">	
    <input name="data3c" id="data3c"type="hidden">	

</div>


<script type="text/javascript">
   
   function hanyaAngka(event) {
         var angka = (event.which) ? event.which : event.keyCode
         if (angka != 46 && (angka > 31) && (angka < 48 || angka > 57) && angka != 47 )
           return false;
         return true;
       }
    //47 = /
    //48 - 57 = 0 - 9
$(document).ready(function(){

    tampil_data();  
    tampil_tombol();

    function tampil_tombol(){
        $('#example1').DataTable( {
            "responsive": true, "lengthChange": false, "autoWidth": false,
            buttons : [ {extend: 'colvis', postfixButtons: [ 'colvisRestore' ] }, {extend:'copy'}, {extend:'csv'}, {extend: 'pdf', orientation: 'portrait', pageSize: 'A4', title:'{{ $caption }}'}, {extend: 'excel', title: '{{ $caption }}'}, {extend:'print', orientation: 'portrait', pageSize: 'A4', title: '{{ $caption }}'}, ],
		    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');   
     } 
         

    //tampilkan dalam tabel ->OK
    function tampil_data(){	
        $.ajax({
            type  : 'get',
            url   : '{{route('admin01.master.bulan_show')}}',
            async : false,
            dataType : 'json',
            				 				
            success : function(data){
                var html = '';
                var i;
                var x1;                
                var x2;                
                var x3;                
                var resultData = data.data;	                			
                for(i=0; i<resultData.length; i++){
                    x1 = resultData[i].namabulan;
                    x2 = x1.split(" ");
                    x3 = x2[1];
                    html += '<tr>'+
                                '<td align="center">'+ (i+1) +'</td>'+                            								
                                '<td>'+x3+'</td>'+
                                '<td>'+resultData[i].namabulan+'</td>'+                            
                                '<td>'+resultData[i].tglawal+'</td>'+                            
                                '<td>'+resultData[i].tglakhir+'</td>'+                            
                            '</tr>';
                }

                $('#show_data').html(html); 
                                            
            }
        }); 
    
    }

    $('#tahunx1').on('change',function(){
        setTimeout(() => {
            kirimsyarat();	
        }, 500);                
    });

    function kirimsyarat(){
        var tahunx1=$('#tahunx1').val();
        
        let formData = new FormData();
            formData.append('tahunx1', tahunx1);

        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('admin01.master.bulan_kirimsyarat')}}',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },				 				
            success : function(formData){                                                       
                tampil_data();                   
                }
        });
    }

    $('#btn_tambah1').on('click',function(){
        data_simpan();
    }); 

       
    function data_simpan(){
        var tahunx1=$('#tahunx1').val();

        let formData = new FormData();
            formData.append('tahunx1', tahunx1);
          
        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('admin01.master.bulan_create')}}',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },				 				
            success : function(formData){                                         
                tampil_data();
                swaltambah(tahunx1);
                },
            error : function(formData){                    
                swalgagaltambah(tahunx1); 
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

});
</script>	



@endsection