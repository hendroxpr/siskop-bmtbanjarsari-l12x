@extends('admin.layouts.main')

@section('contents')


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
                    {{--  --}}
                </div>
                
            </div>

            <div class="col-md-6">
                <div class="w3-row" align="right"><i class="fa fa-refresh" aria-hidden="true"></i>            
                    <a id="btn_refresh1" name="btn_refresh1" href="{{ url('/') }}{{ $link }}" class="btn bg-success rounded-0" style="text-decoration: none;"><i style="font-size:18px" class="fa">&#xf021;</i> Refresh</a>            
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
                        <th style="width:50px">Produk Tabungan</th>							
                        <th style="width:50px">Setor-D</th>
                        <th style="width:50px">Setor-K</th>
                        <th style="width:50px">Tarik-D</th>
                        <th style="width:50px">Tarik-K</th>
                        <th style="width:50px">Transfermasuk-D</th>
                        <th style="width:50px">Transfermasuk-K</th>
                        <th style="width:50px">Transferkeluar-D</th>
                        <th style="width:50px">Transferkeluar-K</th>
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
        <div class="modal-dialog modal-lg">  <!-- modal-(sm, lg, xl) ukuran lebar modal -->
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
                                <div class="col-md-2 mt-1" align="right">									
                                    <h6 class="mt-2"></h6>
                                </div>
                                <div class="col-md-5">                                
                                    <h6 class="mt-2">COA</h6>
                                </div>								  
                                <div class="col-md-5">                                
                                    <h6 class="mt-2">Jenis Jurnal</h6>
                                </div>								  
                            </div>

                            <div class="row">
                                <div class="col-md-2 mt-1" align="right">									
                                    <h6 class="mt-2">Produk Tabungan *)</h6>
                                </div>
                                <div class="col-md-5">                                
                                    <input name="produk1" id="produk1" class="w3-input w3-border" maxlength="30" type="search" placeholder="Produk" autofocus value="{{ old('produk1') }}" required>
                                    <input name="cek1" id="cek1" class="" type="hidden">                                
                                    <input name="id1" id="id1" class="" type="hidden">
                                </div>								  
                            </div>

                            <div class="row">
                                <div class="col-md-2 mt-1" align="right">
                                    <h6 class="mt-2">Setor-D</h6>
                                </div>
                                <div class="col-md-5">
                                    <select name="idcoasetord1" id="idcoasetord1" class="w3-input w3-border"></select>
                                </div>
                                <div class="col-md-5">
                                    <select name="idjenisjurnalsetord1" id="idjenisjurnalsetord1" class="w3-input w3-border"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 mt-1" align="right">
                                    <h6 class="mt-2">Setor-K</h6>
                                </div>
                                <div class="col-md-5">
                                    <select name="idcoasetork1" id="idcoasetork1" class="w3-input w3-border"></select>
                                </div>
                                <div class="col-md-5">
                                    <select name="idjenisjurnalsetork1" id="idjenisjurnalsetork1" class="w3-input w3-border"></select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-2 mt-1" align="right">
                                    <h6 class="mt-2">Tarik-D</h6>
                                </div>
                                <div class="col-md-5">
                                    <select name="idcoatarikd1" id="idcoatarikd1" class="w3-input w3-border"></select>
                                </div>
                                <div class="col-md-5">
                                    <select name="idjenisjurnaltarikd1" id="idjenisjurnaltarikd1" class="w3-input w3-border"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 mt-1" align="right">
                                    <h6 class="mt-2">Tarik-K</h6>
                                </div>
                                <div class="col-md-5">
                                    <select name="idcoatarikk1" id="idcoatarikk1" class="w3-input w3-border"></select>
                                </div>
                                <div class="col-md-5">
                                    <select name="idjenisjurnaltarikk1" id="idjenisjurnaltarikk1" class="w3-input w3-border"></select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2 mt-1" align="right">
                                    <h6 class="mt-2">Transfer Masuk-D</h6>
                                </div>
                                <div class="col-md-5">
                                    <select name="idcoatfmasukd1" id="idcoatfmasukd1" class="w3-input w3-border"></select>
                                </div>
                                <div class="col-md-5">
                                    <select name="idjenisjurnaltfmasukd1" id="idjenisjurnaltfmasukd1" class="w3-input w3-border"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 mt-1" align="right">
                                    <h6 class="mt-2">Transfer Masuk-K</h6>
                                </div>
                                <div class="col-md-5">
                                    <select name="idcoatfmasukk1" id="idcoatfmasukk1" class="w3-input w3-border"></select>
                                </div>
                                <div class="col-md-5">
                                    <select name="idjenisjurnaltfmasukk1" id="idjenisjurnaltfmasukk1" class="w3-input w3-border"></select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2 mt-1" align="right">
                                    <h6 class="mt-2">Transfer Keluar-D</h6>
                                </div>
                                <div class="col-md-5">
                                    <select name="idcoatfkeluard1" id="idcoatfkeluard1" class="w3-input w3-border"></select>
                                </div>
                                <div class="col-md-5">
                                    <select name="idjenisjurnaltfkeluard1" id="idjenisjurnaltfkeluard1" class="w3-input w3-border"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 mt-1" align="right">
                                    <h6 class="mt-2">Transfer Keluar-K</h6>
                                </div>
                                <div class="col-md-5">
                                    <select name="idcoatfkeluark1" id="idcoatfkeluark1" class="w3-input w3-border"></select>
                                </div>
                                <div class="col-md-5">
                                    <select name="idjenisjurnaltfkeluark1" id="idjenisjurnaltfkeluark1" class="w3-input w3-border"></select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2 mt-1" align="right">									
                                    <h6 class="mt-2">Keterangan</h6>
                                </div>
                                <div class="col-md-5">                                
                                    <input name="keterangan1" id="keterangan1" class="w3-input w3-border" maxlength="50" type="search" placeholder="Keterangan" value="{{ old('keterangan1') }}" required>
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

    <!-- khusus menyimpan data yang akan dihapus -->
    <input name="id3" id="id3"type="hidden">	
    <input name="data3a" id="data3a"type="hidden">	
    <input name="data3b" id="data3b"type="hidden">	
    <input name="data3c" id="data3c"type="hidden">	

</div>


<script type="text/javascript">
    var data1Datatable;

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
			
		}
    
    setTimeout(() => {
         data1Datatable = tampil_data1();    
         tampil_listcoa();
         tampil_listjenisjurnal();
    }, 1000);

    //menampilkan combo coa
    function tampil_listcoa(){				
        $.ajax({
            type: 'get',
            url   : '{{route('akuntansi01.master.produktabungan_listcoa')}}',
            
            success: function(data){
                $("#idcoasetord1").html(data);
                $("#idcoasetork1").html(data);
                $("#idcoatarikd1").html(data);
                $("#idcoatarikk1").html(data);
                $("#idcoatfkeluard1").html(data);
                $("#idcoatfkeluark1").html(data);
                $("#idcoatfmasukd1").html(data);
                $("#idcoatfmasukk1").html(data);
            }
        })                    
    }
    //menampilkan combo jenisjurnal
    function tampil_listjenisjurnal(){				
        $.ajax({
            type: 'get',
            url   : '{{route('akuntansi01.master.produktabungan_listjenisjurnal')}}',
            
            success: function(data){
                $("#idjenisjurnalsetord1").html(data);
                $("#idjenisjurnalsetork1").html(data);
                $("#idjenisjurnaltarikd1").html(data);
                $("#idjenisjurnaltarikk1").html(data);
                $("#idjenisjurnaltfkeluard1").html(data);
                $("#idjenisjurnaltfkeluark1").html(data);
                $("#idjenisjurnaltfmasukd1").html(data);
                $("#idjenisjurnaltfmasukk1").html(data);
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
            dom: 'lBrtip',
            lengthMenu: [
                [ 10, 25, 50, 100, 500, 1000, 5000, -1 ],
                [ '10', '25', '50', '100', '500','1000','5000', 'All' ]
            ],
            processing: true,
            serverSide: true,
            ajax   : `{{route('akuntansi01.master.produktabungan_show')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'produk', name: 'produk',  },
                { data: 'coasetord', name: 'coasetord', orderable: false },
                { data: 'coasetork', name: 'coasetork', orderable: false },
                { data: 'coatarikd', name: 'coatarikd', orderable: false },
                { data: 'coatarikk', name: 'coatarikk', orderable: false },
                { data: 'coatfmasukd', name: 'coatfmasukd', orderable: false },
                { data: 'coatfmasukk', name: 'coatfmasukk', orderable: false },
                { data: 'coatfkeluard', name: 'coatfkeluard', orderable: false },
                { data: 'coatfkeluark', name: 'coatfkeluark', orderable: false },
                                                
                { data: 'action', name: 'action'},
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
        var produk1=$('#produk1').val();
        var idcoasetord1=$('#idcoasetord1').val();
        var idcoasetork1=$('#idcoasetork1').val();
        var idcoatarikd1=$('#idcoatarikd1').val();
        var idcoatarikk1=$('#idcoatarikk1').val();
        var idcoatfkeluard1=$('#idcoatfkeluard1').val();
        var idcoatfkeluark1=$('#idcoatfkeluark1').val();
        var idcoatfmasukd1=$('#idcoatfmasukd1').val();
        var idcoatfmasukk1=$('#idcoatfmasukk1').val(); 
        
        var idjenisjurnalsetord1=$('#idjenisjurnalsetord1').val();
        var idjenisjurnalsetork1=$('#idjenisjurnalsetork1').val();
        var idjenisjurnaltarikd1=$('#idjenisjurnaltarikd1').val();
        var idjenisjurnaltarikk1=$('#idjenisjurnaltarikk1').val();
        var idjenisjurnaltfkeluard1=$('#idjenisjurnaltfkeluard1').val();
        var idjenisjurnaltfkeluark1=$('#idjenisjurnaltfkeluark1').val();
        var idjenisjurnaltfmasukd1=$('#idjenisjurnaltfmasukd1').val();
        var idjenisjurnaltfmasukk1=$('#idjenisjurnaltfmasukk1').val();

        var keterangan1=$('#keterangan1').val();        
        
        let formData = new FormData();
            formData.append('id1', id1);
            formData.append('produk1', produk1);
            formData.append('idcoasetord1', idcoasetord1);
            formData.append('idcoasetork1', idcoasetork1);
            formData.append('idcoatarikd1', idcoatarikd1);
            formData.append('idcoatarikk1', idcoatarikk1);
            formData.append('idcoatfkeluard1', idcoatfkeluard1);
            formData.append('idcoatfkeluark1', idcoatfkeluark1);
            formData.append('idcoatfmasukd1', idcoatfmasukd1);
            formData.append('idcoatfmasukk1', idcoatfmasukk1);

            formData.append('idjenisjurnalsetord1', idjenisjurnalsetord1);
            formData.append('idjenisjurnalsetork1', idjenisjurnalsetork1);
            formData.append('idjenisjurnaltarikd1', idjenisjurnaltarikd1);
            formData.append('idjenisjurnaltarikk1', idjenisjurnaltarikk1);
            formData.append('idjenisjurnaltfkeluard1', idjenisjurnaltfkeluard1);
            formData.append('idjenisjurnaltfkeluark1', idjenisjurnaltfkeluark1);
            formData.append('idjenisjurnaltfmasukd1', idjenisjurnaltfmasukd1);
            formData.append('idjenisjurnaltfmasukk1', idjenisjurnaltfmasukk1);

            formData.append('keterangan1', keterangan1);
            
        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('akuntansi01.master.produktabungan_create')}}',
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
                                  
                swalgagaltambah(produk1);                 
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
		        url   : `{{ url('akuntansi01/master/produktabunganedit')}}/${id1}`,
		        async : false,
		        dataType : 'json',	
				
		        success : function(data){
                    var i;                
                    var resultData = data.data;	                			
                    for(i=0; i<resultData.length; i++){

                        $('#id1').val(resultData[i].id);
                        $('#produk1').val(resultData[i].produk);
                        $('#idcoasetord1').val(resultData[i].idcoasetord);
                        $('#idcoasetork1').val(resultData[i].idcoasetork);
                        $('#idcoatarikd1').val(resultData[i].idcoatarikd);
                        $('#idcoatarikk1').val(resultData[i].idcoatarikk);
                        $('#idcoatfkeluard1').val(resultData[i].idcoatfkeluard);
                        $('#idcoatfkeluark1').val(resultData[i].idcoatfkeluark);
                        $('#idcoatfmasukd1').val(resultData[i].idcoatfmasukd);
                        $('#idcoatfmasukk1').val(resultData[i].idcoatfmasukk);

                        $('#idjenisjurnalsetord1').val(resultData[i].idjenisjurnalsetord);
                        $('#idjenisjurnalsetork1').val(resultData[i].idjenisjurnalsetork);
                        $('#idjenisjurnaltarikd1').val(resultData[i].idjenisjurnaltarikd);
                        $('#idjenisjurnaltarikk1').val(resultData[i].idjenisjurnaltarikk);
                        $('#idjenisjurnaltfkeluard1').val(resultData[i].idjenisjurnaltfkeluard);
                        $('#idjenisjurnaltfkeluark1').val(resultData[i].idjenisjurnaltfkeluark);
                        $('#idjenisjurnaltfmasukd1').val(resultData[i].idjenisjurnaltfmasukd);
                        $('#idjenisjurnaltfmasukk1').val(resultData[i].idjenisjurnaltfmasukk);

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
            url   : '{{url('akuntansi01/master/produktabungandestroy')}}/'+id3,
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