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
                        <th style="width:10px;">Kode</th>                            
                        <th style="width:50px">Jenis Pinjaman</th>							
                        <th style="width:50px">Ujroh</th>							
                        <th style="width:50px">Pokok-D</th>							
                        <th style="width:50px">Pokok-K</th>							
                        <th style="width:50px">Angs-D</th>							
                        <th style="width:50px">Angs-K</th>							
                        <th style="width:50px">Ujr-D</th>							
                        <th style="width:50px">Ujr-K</th>							
                        <th style="width:50px">Adm-D</th>							
                        <th style="width:50px">Adm-K</th>							
                        <th style="width:50px">Dis-D</th>							
                        <th style="width:50px">Dis-K</th>							
                        <th style="width:50px">Den-D</th>							
                        <th style="width:50px">Den-K</th>							
                        <th style="width:50px">Keterangan</th>							
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
                                    <h6 class="mt-2">Kode *)</h6>
                                </div>
                                <div class="col-md-5">                                
                                    <input name="kode1" id="kode1" class="w3-input w3-border" maxlength="2" type="search" placeholder="kode" autofocus value="{{ old('kode1') }}" required>
                                    <input name="cek1" id="cek1" class="" type="hidden">                                
                                    <input name="id1" id="id1" class="" type="hidden">
                                </div>
                               
                                
                                {{-- <div class="col-md-2">                                
                                    <input name="ujroha1" id="ujoroh1" class="w3-input w3-border text-right" type="search" maxlength="15" placeholder="ujroh pembilang" value="1" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required>
                                </div>
                                <div class="col-md-2">                                
                                    <input name="ujroha1" id="ujoroh1" class="w3-input w3-border text-right" type="search" maxlength="15" placeholder="ujroh pembilang" value="1" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required>
                                </div> --}}

                            </div>
                            <div class="row">
                                <div class="col-md-2 mt-1" align="right">									
                                    <h6 class="mt-2">Jenis Pinjaman*)</h6>
                                </div>
                                <div class="col-md-5">                                
                                    <input name="jenispinjaman1" id="jenispinjaman1" class="w3-input w3-border" maxlength="30" type="search" placeholder="jenis pinjaman"  value="{{ old('jenispinjaman1') }}" required>
                                </div>
                                <div class="col-md-5 mt-1">
                                    <div class="row">
                                        <div class="col-md-3 mt-1" align="right">
                                            <h6 class="mt-2">Ujroh</h6>
                                        </div>								
                                        <div class="col-md-4">
                                            <input name="ujroha1" id="ujroha1" class="w3-input w3-border text-right" type="search" maxlength="15" placeholder="ujroh pembilang" value="1" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required>
                                        </div>									
                                        <div class="col-md-1 mt-1" align="center">
                                            <h6 class="mt-2">/</h6>
                                        </div>									
                                        <div class="col-md-4">
                                            <input name="ujrohb1" id="ujrohb1" class="w3-input w3-border text-right" type="search" maxlength="15" placeholder="ujroh penyebut" value="1" onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);" required>
                                        </div>
                                    </div>									
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2 mt-1" align="right">
                                    <h6 class="mt-2">Pokok-D</h6>
                                </div>
                                <div class="col-md-5">
                                    <select name="idcoa01d1" id="idcoa01d1" class="w3-input w3-border"></select>
                                </div>
                                <div class="col-md-5">
                                    <select name="idjenisjurnal01d1" id="idjenisjurnal01d1" class="w3-input w3-border"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 mt-1" align="right">
                                    <h6 class="mt-2">Pokok-K</h6>
                                </div>
                                <div class="col-md-5">
                                    <select name="idcoa01k1" id="idcoa01k1" class="w3-input w3-border"></select>
                                </div>
                                <div class="col-md-5">
                                    <select name="idjenisjurnal01k1" id="idjenisjurnal01k1" class="w3-input w3-border"></select>
                                </div>
                            </div>
                            
                            <div class="row mt-2">
                                <div class="col-md-2 mt-1" align="right">
                                    <h6 class="mt-2">Angs-D</h6>
                                </div>
                                <div class="col-md-5">
                                    <select name="idcoa02d1" id="idcoa02d1" class="w3-input w3-border"></select>
                                </div>
                                <div class="col-md-5">
                                    <select name="idjenisjurnal02d1" id="idjenisjurnal02d1" class="w3-input w3-border"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 mt-1" align="right">
                                    <h6 class="mt-2">Angs-K</h6>
                                </div>
                                <div class="col-md-5">
                                    <select name="idcoa02k1" id="idcoa02k1" class="w3-input w3-border"></select>
                                </div>
                                <div class="col-md-5">
                                    <select name="idjenisjurnal02k1" id="idjenisjurnal02k1" class="w3-input w3-border"></select>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2 mt-1" align="right">
                                    <h6 class="mt-2">Ujroh-D</h6>
                                </div>
                                <div class="col-md-5">
                                    <select name="idcoa03d1" id="idcoa03d1" class="w3-input w3-border"></select>
                                </div>
                                <div class="col-md-5">
                                    <select name="idjenisjurnal03d1" id="idjenisjurnal03d1" class="w3-input w3-border"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 mt-1" align="right">
                                    <h6 class="mt-2">Ujroh-K</h6>
                                </div>
                                <div class="col-md-5">
                                    <select name="idcoa03k1" id="idcoa03k1" class="w3-input w3-border"></select>
                                </div>
                                <div class="col-md-5">
                                    <select name="idjenisjurnal03k1" id="idjenisjurnal03k1" class="w3-input w3-border"></select>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2 mt-1" align="right">
                                    <h6 class="mt-2">Admin-D</h6>
                                </div>
                                <div class="col-md-5">
                                    <select name="idcoa04d1" id="idcoa04d1" class="w3-input w3-border"></select>
                                </div>
                                <div class="col-md-5">
                                    <select name="idjenisjurnal04d1" id="idjenisjurnal04d1" class="w3-input w3-border"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 mt-1" align="right">
                                    <h6 class="mt-2">Admin-K</h6>
                                </div>
                                <div class="col-md-5">
                                    <select name="idcoa04k1" id="idcoa04k1" class="w3-input w3-border"></select>
                                </div>
                                <div class="col-md-5">
                                    <select name="idjenisjurnal04k1" id="idjenisjurnal04k1" class="w3-input w3-border"></select>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2 mt-1" align="right">
                                    <h6 class="mt-2">Diskon-D</h6>
                                </div>
                                <div class="col-md-5">
                                    <select name="idcoa05d1" id="idcoa05d1" class="w3-input w3-border"></select>
                                </div>
                                <div class="col-md-5">
                                    <select name="idjenisjurnal05d1" id="idjenisjurnal05d1" class="w3-input w3-border"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 mt-1" align="right">
                                    <h6 class="mt-2">Diskon-K</h6>
                                </div>
                                <div class="col-md-5">
                                    <select name="idcoa05k1" id="idcoa05k1" class="w3-input w3-border"></select>
                                </div>
                                <div class="col-md-5">
                                    <select name="idjenisjurnal05k1" id="idjenisjurnal05k1" class="w3-input w3-border"></select>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2 mt-1" align="right">
                                    <h6 class="mt-2">Denda-D</h6>
                                </div>
                                <div class="col-md-5">
                                    <select name="idcoa06d1" id="idcoa06d1" class="w3-input w3-border"></select>
                                </div>
                                <div class="col-md-5">
                                    <select name="idjenisjurnal06d1" id="idjenisjurnal06d1" class="w3-input w3-border"></select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2 mt-1" align="right">
                                    <h6 class="mt-2">denda-K</h6>
                                </div>
                                <div class="col-md-5">
                                    <select name="idcoa06k1" id="idcoa06k1" class="w3-input w3-border"></select>
                                </div>
                                <div class="col-md-5">
                                    <select name="idjenisjurnal06k1" id="idjenisjurnal06k1" class="w3-input w3-border"></select>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-2 mt-1" align="right">									
                                    <h6 class="mt-2">Keterangan</h6>
                                </div>
                                <div class="col-md-10">                                
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
            url   : '{{route('akuntansi01.master.jenispinjaman_listcoa')}}',
            
            success: function(data){
                $("#idcoa01d1").html(data);
                $("#idcoa01k1").html(data);
                $("#idcoa02d1").html(data);
                $("#idcoa02k1").html(data);
                $("#idcoa03d1").html(data);
                $("#idcoa03k1").html(data);
                $("#idcoa04d1").html(data);
                $("#idcoa04k1").html(data);
                $("#idcoa05d1").html(data);
                $("#idcoa05k1").html(data);
                $("#idcoa06d1").html(data);
                $("#idcoa06k1").html(data);
            }
        })                    
    }
    //menampilkan combo jenisjurnal
    function tampil_listjenisjurnal(){				
        $.ajax({
            type: 'get',
            url   : '{{route('akuntansi01.master.jenispinjaman_listjenisjurnal')}}',
            
            success: function(data){
                $("#idjenisjurnal01d1").html(data);
                $("#idjenisjurnal01k1").html(data);
                $("#idjenisjurnal02d1").html(data);
                $("#idjenisjurnal02k1").html(data);
                $("#idjenisjurnal03d1").html(data);
                $("#idjenisjurnal03k1").html(data);
                $("#idjenisjurnal04d1").html(data);
                $("#idjenisjurnal04k1").html(data);
                $("#idjenisjurnal05d1").html(data);
                $("#idjenisjurnal05k1").html(data);
                $("#idjenisjurnal06d1").html(data);
                $("#idjenisjurnal06k1").html(data);
            }
        })                    
    }
    
    $("#ujroha1").on('change',function(){
        var x = $('#ujroha1').val().replace(/[^,\d]/g, '').toString();
        if (x==''||x=='0'){
            $("#ujroha1").val('1');
        }
        var y = parseFloat(x);
        if (y>=999999999999999){
            $("#ujroha1").val(y);
        } 
    });
    $("#ujroha1").on('keydown',function(){
        var x = $('#ujroha1').val().replace(/[^,\d]/g, '').toString();
        if (x==''||x=='0'){
            $("#ujroha1").val('1');
        }
        var y = parseFloat(x);
        if (y>=999999999999999){
            $("#ujroha1").val(y);
        } 
    });
    $("#ujrohb1").on('change',function(){
        var x = $('#ujrohb1').val().replace(/[^,\d]/g, '').toString();
        if (x==''||x=='0'){
            $("#ujrohb1").val('1');
        }
        var y = parseFloat(x);
        if (y>=999999999999999){
            $("#ujrohb1").val(y);
        } 
    });
     $("#ujrohb1").on('keydown',function(){
        var x = $('#ujrohb1').val().replace(/[^,\d]/g, '').toString();
        if (x==''||x=='0'){
            $("#ujrohb1").val('1');
        }
        var y = parseFloat(x);
        if (y>=999999999999999){
            $("#ujrohb1").val(y);
        } 
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
            ajax   : `{{route('akuntansi01.master.jenispinjaman_show')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'kode', class: 'text-center', },
                { data: 'jenispinjaman', name: 'jenispinjaman',  },
                { data: 'ujroh', name: 'ujroh', class: 'text-center', },
                { data: 'coa01d', name: 'coa01d', orderable: false },
                { data: 'coa01k', name: 'coa01k', orderable: false },
                { data: 'coa02d', name: 'coa02d', orderable: false },
                { data: 'coa02k', name: 'coa02k', orderable: false },
                { data: 'coa03d', name: 'coa03d', orderable: false },
                { data: 'coa03k', name: 'coa03k', orderable: false },
                { data: 'coa04d', name: 'coa04d', orderable: false },
                { data: 'coa04k', name: 'coa04k', orderable: false },
                { data: 'coa05d', name: 'coa04d', orderable: false },
                { data: 'coa05k', name: 'coa05k', orderable: false },
                { data: 'coa06d', name: 'coa04d', orderable: false },
                { data: 'coa06k', name: 'coa06k', orderable: false },
                { data: 'keterangan', name: 'keterangan', orderable: false },
                                                
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
        var kode1=$('#kode1').val();
        var jenispinjaman1=$('#jenispinjaman1').val();
        var ujroha1=$('#ujroha1').val().replace(/[^,\d]/g, '').toString();;
        var ujrohb1=$('#ujrohb1').val().replace(/[^,\d]/g, '').toString();;
        var idcoa01d1=$('#idcoa01d1').val();
        var idcoa01k1=$('#idcoa01k1').val();
        var idcoa02d1=$('#idcoa02d1').val();
        var idcoa02k1=$('#idcoa02k1').val();
        var idcoa03d1=$('#idcoa03d1').val();
        var idcoa03k1=$('#idcoa03k1').val(); 
        var idcoa04d1=$('#idcoa04d1').val();
        var idcoa04k1=$('#idcoa04k1').val();
        var idcoa05d1=$('#idcoa05d1').val();
        var idcoa05k1=$('#idcoa05k1').val();
        var idcoa06d1=$('#idcoa06d1').val();
        var idcoa06k1=$('#idcoa06k1').val();
        
        var idjenisjurnal01d1=$('#idjenisjurnal01d1').val();
        var idjenisjurnal01k1=$('#idjenisjurnal01k1').val();
        var idjenisjurnal02d1=$('#idjenisjurnal02d1').val();
        var idjenisjurnal02k1=$('#idjenisjurnal02k1').val();
        var idjenisjurnal03d1=$('#idjenisjurnal03d1').val();
        var idjenisjurnal03k1=$('#idjenisjurnal03k1').val();
        var idjenisjurnal04d1=$('#idjenisjurnal04d1').val();
        var idjenisjurnal04k1=$('#idjenisjurnal04k1').val();
        var idjenisjurnal05d1=$('#idjenisjurnal05d1').val();
        var idjenisjurnal05k1=$('#idjenisjurnal05k1').val();
        var idjenisjurnal06d1=$('#idjenisjurnal06d1').val();
        var idjenisjurnal06k1=$('#idjenisjurnal06k1').val();

        var keterangan1=$('#keterangan1').val();        
        
        let formData = new FormData();
            formData.append('id1', id1);
            formData.append('kode1', kode1);
            formData.append('jenispinjaman1', jenispinjaman1);
            formData.append('ujroha1', ujroha1);
            formData.append('ujrohb1', ujrohb1);
            formData.append('idcoa01d1', idcoa01d1);
            formData.append('idcoa01k1', idcoa01k1);
            formData.append('idcoa02d1', idcoa02d1);
            formData.append('idcoa02k1', idcoa02k1);
            formData.append('idcoa03d1', idcoa03d1);
            formData.append('idcoa03k1', idcoa03k1);
            formData.append('idcoa04d1', idcoa04d1);
            formData.append('idcoa04k1', idcoa04k1);
            formData.append('idcoa05d1', idcoa05d1);
            formData.append('idcoa05k1', idcoa05k1);
            formData.append('idcoa06d1', idcoa06d1);
            formData.append('idcoa06k1', idcoa06k1);

            formData.append('idjenisjurnal01d1', idjenisjurnal01d1);
            formData.append('idjenisjurnal01k1', idjenisjurnal01k1);
            formData.append('idjenisjurnal02d1', idjenisjurnal02d1);
            formData.append('idjenisjurnal02k1', idjenisjurnal02k1);
            formData.append('idjenisjurnal03d1', idjenisjurnal03d1);
            formData.append('idjenisjurnal03k1', idjenisjurnal03k1);
            formData.append('idjenisjurnal04d1', idjenisjurnal04d1);
            formData.append('idjenisjurnal04k1', idjenisjurnal04k1);
            formData.append('idjenisjurnal05d1', idjenisjurnal05d1);
            formData.append('idjenisjurnal05k1', idjenisjurnal05k1);
            formData.append('idjenisjurnal06d1', idjenisjurnal06d1);
            formData.append('idjenisjurnal06k1', idjenisjurnal06k1);

            formData.append('keterangan1', keterangan1);
            
        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('akuntansi01.master.jenispinjaman_create')}}',
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
                                  
                swalgagaltambah(jenispinjaman1);                 
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
		        url   : `{{ url('akuntansi01/master/jenispinjamanedit')}}/${id1}`,
		        async : false,
		        dataType : 'json',	
				
		        success : function(data){
                    var i;                
                    var resultData = data.data;	                			
                    for(i=0; i<resultData.length; i++){

                        $('#id1').val(resultData[i].id);
                        $('#kode1').val(resultData[i].kode);
                        $('#jenispinjaman1').val(resultData[i].jenispinjaman);
                        $('#ujroha1').val(resultData[i].ujroha);
                        $('#ujrohb1').val(resultData[i].ujrohb);
                        $('#idcoa01d1').val(resultData[i].idcoa01d);
                        $('#idcoa01k1').val(resultData[i].idcoa01k);
                        $('#idcoa02d1').val(resultData[i].idcoa02d);
                        $('#idcoa02k1').val(resultData[i].idcoa02k);
                        $('#idcoa03d1').val(resultData[i].idcoa03d);
                        $('#idcoa03k1').val(resultData[i].idcoa03k);
                        $('#idcoa04d1').val(resultData[i].idcoa04d);
                        $('#idcoa04k1').val(resultData[i].idcoa04k);
                        $('#idcoa05d1').val(resultData[i].idcoa05d);
                        $('#idcoa05k1').val(resultData[i].idcoa05k);
                        $('#idcoa06d1').val(resultData[i].idcoa06d);
                        $('#idcoa06k1').val(resultData[i].idcoa06k);

                        $('#idjenisjurnal01d1').val(resultData[i].idjenisjurnal01d);
                        $('#idjenisjurnal01k1').val(resultData[i].idjenisjurnal01k);
                        $('#idjenisjurnal02d1').val(resultData[i].idjenisjurnal02d);
                        $('#idjenisjurnal02k1').val(resultData[i].idjenisjurnal02k);
                        $('#idjenisjurnal03d1').val(resultData[i].idjenisjurnal03d);
                        $('#idjenisjurnal03k1').val(resultData[i].idjenisjurnal03k);
                        $('#idjenisjurnal04d1').val(resultData[i].idjenisjurnal04d);
                        $('#idjenisjurnal04k1').val(resultData[i].idjenisjurnal04k);
                        $('#idjenisjurnal05d1').val(resultData[i].idjenisjurnal05d);
                        $('#idjenisjurnal05k1').val(resultData[i].idjenisjurnal05k);
                        $('#idjenisjurnal06d1').val(resultData[i].idjenisjurnal06d);
                        $('#idjenisjurnal06k1').val(resultData[i].idjenisjurnal06k);

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
            url   : '{{url('akuntansi01/master/jenispinjamandestroy')}}/'+id3,
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