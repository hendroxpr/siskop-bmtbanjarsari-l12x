@extends('admin.layouts.main')

@section('contents')

@php
    $idtp = session('idtp');
    $a1 = session()->get('memtanggal1');
    if($a1==''){
        $a1=session()->get('memtanggal');  
    }
    $a2 = session()->get('memtanggal2');
    if($a2==''){
        $a2=session()->get('memtanggal');  
    }
    $idcoa1 = session()->get('idcoa1');

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
                    <div class="col-md-3 mt-2" align="right">									
                        <label for="tanggalx1"><h6>Tanggal Posting</h6></label>
                    </div>
                    <div class="col-md-3">
                        <input name="tanggalx1" id="tanggalx1" class="w3-input w3-border" type="text"  placeholder="Tanggal Awal" value="{{ $a1 }}">
                    </div>
                    <div class="col-md-1 mt-2" align="center">									
                        <label for="tanggalx2"><h6>s/d</h6></label>
                    </div>
                    <div class="col-md-3 mb-1">
                        <input name="tanggalx2" id="tanggalx2" class="w3-input w3-border" type="text"  placeholder="Tanggal Akhir" value="{{ $a2 }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mt-2 text-right">
                        <h6>COA</h6>                        
                    </div>
                    <div class="col-md-7 mb-1">                        
                        <select name="idcoa1" id="idcoa1" class="w3-input w3-border" value="{{ $idcoa1 }}"></select>
                    </div>
                </div> 

                <div class="row">
                    <div class="col-md-3 mt-2 text-right">
                        <h6>Keterangan</h6> 
                    </div>
                    <div class="col-md-7 mb-1">
                        <input name="keterangan1" id="keterangan1" class="w3-input w3-border" type="text" placeholder="Keterangan" value="{{ session('keterangan1') }}" autocomplete="off">
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="w3-row" align="right"><i class="fa fa-refresh" aria-hidden="true"></i>            
                    <a id="btn_refresh1" name="btn_refresh1" href="{{ url('/') }}{{ $link }}" class="btn bg-success rounded-0" style="text-decoration: none;"><i style="font-size:18px" class="fa">&#xf021;</i> Refresh</a>            
                    {{-- <button id="btn_tambah1" name="btn_tambah1" type="button" class="btn bg-primary rounded-0"><i class="fas fa-plus"></i> Tambah</button>
                    <button id="btn_update1x" name="btn_update1x" type="button" class="w3-button w3-purple" style="color:#7FFF00; height:36px;"><i style="font-size:18px" class="fa">&#xf56e;</i> Update</button> --}}
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
                        <th style="width:50px">Nama</th>							
                        <th style="width:50px">Status</th>
                        <th style="width:50px">Tgl Posting</th>							
                        <th style="width:50px">Nomor Posting</th>							
                        <th style="width:50px">Nomor Bukti</th>
                        <th style="width:10px">Awal-D</th>
                        <th style="width:10px">Awal-K</th>
                        <th style="width:10px">Debet</th>
                        <th style="width:10px">Kredit</th>
                        <th style="width:10px">Saldo-D</th>
                        <th style="width:10px">Saldo-K</th>
                        <th style="width:20px">Keterangan</th>
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

    <!-- khusus menyimpan data yang akan dihapus -->
    <input name="id3" id="id3"type="hidden">	
    <input name="data3a" id="data3a"type="hidden">	
    <input name="data3b" id="data3b"type="hidden">	
    <input name="data3c" id="data3c"type="hidden">	

</div>


<script type="text/javascript">
    var data1Datatable;

$(document).ready(function(){
    
    setTimeout(() => {
        tampil_listcoa();    
        data1Datatable = tampil_data1();
        
    }, 1000);
    
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
            ajax   : `{{route('sibm01.laporan.historicoa_show')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nama', name: 'nama' },
                { data: 'status', name: 'status' },
                { data: 'tglposting', name: 'tglposting' },
                { data: 'nomorposting', name: 'nomorposting' },
                { data: 'nomorstatus', name: 'nomorstatus' },
                { data: 'awald', name: 'awald', orderable: false, searchable: false, className: 'text-right' },
                { data: 'awalk', name: 'awalk', orderable: false, searchable: false, className: 'text-right' },
                { data: 'debet', name: 'debet', orderable: false, searchable: false, className: 'text-right' },
                { data: 'kredit', name: 'kredit', orderable: false, searchable: false, className: 'text-right' },
                { data: 'saldod', name: 'saldod', orderable: false, searchable: false, className: 'text-right' },
                { data: 'saldok', name: 'saldok', orderable: false, searchable: false, className: 'text-right' },
                { data: 'keterangan', name: 'keterangan' },
                
            ]
        });
    }

    
    //menampilkan combo coa
    function tampil_listcoa(){				
        $.ajax({
            type: 'get',
            url   : '{{route('sibm01.laporan.historicoa_listcoa')}}',
            
            success: function(data){				    
                $("#idcoa1").html(data);
                $("#idcoa1").val({{ $idcoa1 }});
            }
        })                    
    }

    $("#tanggalx1").on('change',function(){ 
        setTimeout(() => {
            kirimsyarat(); 
        }, 500);
        
        setTimeout(() => {
            tampil_dataTable();
        }, 200);
    });

    $("#tanggalx1").on('keydown',function(){
        setTimeout(() => {
            kirimsyarat(); 
        }, 500);
        setTimeout(() => {
            tampil_dataTable();
        }, 200);
    });

    $("#tanggalx2").on('change',function(){ 
        setTimeout(() => {
            kirimsyarat();
        }, 500);
        setTimeout(() => {
            tampil_dataTable();
        }, 200);
    });

    $("#tanggalx2").on('keydown',function(){
        setTimeout(() => {
            kirimsyarat();
        }, 500);
        setTimeout(() => {
            tampil_dataTable();
        }, 200);
    });

    $("#idcoa1").on('click',function(){
        setTimeout(() => {
            kirimsyarat();
        }, 500);
        setTimeout(() => {
            tampil_dataTable();
        }, 200);
    });

    $("#idcoa1").on('change',function(){
        setTimeout(() => {
            kirimsyarat();
        }, 500);
        setTimeout(() => {
            tampil_dataTable();
        }, 200);
    });

    $("#keterangan1").on('change',function(){ 
        setTimeout(() => {
            kirimsyarat();
        }, 500);        
    });

    $("#keterangan1").on('keydown',function(){
        setTimeout(() => {
            kirimsyarat();
        }, 500);        
    });


    function tampil_dataTable(){        
        data1Datatable.draw(null, false);        
    }

    function kirimsyarat(){
        var tanggalx1=$('#tanggalx1').val();
        var tanggalx2=$('#tanggalx2').val();
        var idcoa1=$('#idcoa1').val();        
        var keterangan1=$('#keterangan1').val();

        let formData = new FormData();
            formData.append('tanggalx1', tanggalx1);
            formData.append('tanggalx2', tanggalx2);
            formData.append('idcoa1', idcoa1);            
            formData.append('keterangan1', keterangan1);

        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('sibm01.laporan.historicoa_kirimsyarat')}}',
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
                // alert('error');
                }
        });
    }


    $("#tanggalx1").datepicker({
			dateFormat  : "yy-mm-dd",
			changeMonth : true,
			changeYear  : true         
    });

    $("#tanggalx2").datepicker({
			dateFormat  : "yy-mm-dd",
			changeMonth : true,
			changeYear  : true         
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