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

<style>
    #barcode1, #namamin1 {
            text-transform: uppercase; /* Memastikan tampilan awal huruf kapital */
        }

</style>

    <div class="box-header mb-3">  
        <div class="row">
            <div class="col-md-6">
                                
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
                        <th style="width:10px">Kode</th>                            
                        <th style="width:50px">Barcode</th>							
                        <th style="width:100px">Nama Mamin</th>
                        <th style="width:50px">Kategori</th>
                        <th style="width:50px">Satuan</th>
                        <th style="width:50px">Harga Jual</th>
                        <th style="width:50px">PPN Jual(%)</th>
                        <th style="width:50px">Diskon Jual(%)</th>
                        <th style="width:100px">Spek</th>
                        <th style="width:50px">Image</th>
                        <th style="width:50px">Expired</th>
                        <th style="width:200px">Keterangan</th>
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
                                {{-- kiri --}}
                                <div class="col-md-6">
                                    
                                    <div class="row">
                                        <div class="col-md-4 mt-1" align="right">									
                                            <h6 class="mt-2">Kode</h6>
                                        </div>
                                        <div class="col-md-8">                                
                                            <input name="kode1" id="kode1" class="w3-input w3-border" type="number" placeholder="Kode" autofocus value="{{ old('kode1') }}" >
                                            <input name="cek1" id="cek1" class="" type="hidden">                                
                                            <input name="id1" id="id1" class="" type="hidden">
        
                                        </div>								  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mt-1" align="right">									
                                            <h6 class="mt-2">Barcode *)</h6>
                                        </div>
                                        <div class="col-md-8">                                                                           
                                            <input name="barcode1" id="barcode1" class="w3-input w3-border" maxlength="15" type="search" placeholder="Barcode" autofocus value="{{ old('barcode1') }}" required>
                                        </div>								  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mt-1" align="right">									
                                            <h6 class="mt-2">Nama Mamin *)</h6>
                                        </div>
                                        <div class="col-md-8">                                
                                            <input name="namamin1" id="namamin1" class="w3-input w3-border" maxlength="40" type="search" placeholder="Nama Barang" value="{{ old('namamin1') }}" required>
                                        </div>								  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mt-1" align="right">									
                                            <h6 class="mt-2">Kategori *)</h6>
                                        </div>
                                        <div class="col-md-8">                                
                                            <select name="idkategori1" id="idkategori1" class="w3-input w3-border"></select>
                                        </div>								  
                                    </div>       
                                    <div class="row">
                                        <div class="col-md-4 mt-1" align="right">									
                                            <h6 class="mt-2">Satuan *)</h6>
                                        </div>
                                        <div class="col-md-8">                                
                                            <select name="idsatuan1" id="idsatuan1" class="w3-input w3-border"></select>
                                        </div>								  
                                    </div> 
                                        
                                    <div class="row">
                                        <div class="col-md-4 mt-1" align="right">									
                                            <h6 class="mt-2">Harga Jual</h6>
                                        </div>
                                        <div class="col-md-8">                                
                                            <input name="hjs1" id="hjs1" class="w3-input w3-border text-right" type="search" placeholder="Harga Jual Satuan" value="{{ old('hjs1') }}"  onkeydown="return numbersonly(this, event);" onkeyup="javascript:tandaPemisahTitik(this);">
                                        </div>								  
                                    </div>    
                                                                             
                                    <div class="row">
                                        <div class="col-md-4 mt-1" align="right">									
                                            <h6 class="mt-2">PPN Jual(%)</h6>
                                        </div>
                                        <div class="col-md-8">                                
                                            <input name="ppnjual1" id="ppnjual1" class="w3-input w3-border text-right" maxlength="" type="number" placeholder="PPN Jual(%)" value="{{ old('ppnjual1') }}">
                                        </div>								  
                                    </div>                                        
                                    <div class="row">
                                        <div class="col-md-4 mt-1" align="right">									
                                            <h6 class="mt-2">Diskon Jual(%)</h6>
                                        </div>
                                        <div class="col-md-8">                                
                                            <input name="diskonjual1" id="diskonjual1" class="w3-input w3-border text-right" maxlength="" type="number" placeholder="Diskon Jual (%)" value="{{ old('diskonjual1') }}">
                                        </div>								  
                                    </div> 
                                    
                                    <div class="row">
                                        <div class="col-md-4 mt-1" align="right">									
                                            <h6 class="mt-2">Spek</h6>
                                        </div>
                                        <div class="col-md-8">                                
                                            <input name="spek1" id="spek1" class="w3-input w3-border" maxlength="" type="search" placeholder="Spek" value="{{ old('spek1') }}">
                                        </div>								  
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-4 mt-1" align="right">									
                                            <h6 class="mt-2">Expired</h6>
                                        </div>
                                        <div class="col-md-8">                                
                                            <input name="expired1" id="expired1" class="w3-input w3-border" maxlength="10" type="search" placeholder="expired" value="{{ old('expired1') }}">
                                        </div>								  
                                    </div>
                                     
                                          
                                        
                                </div>
                                
                                
                                {{-- kanan --}}
                                <div class="col-md-6">

                                    
                                    
                                    <div class="row">
                                        <div class="col-md-4 mt-1" align="right">									
                                            <h6 class="mt-2">Image</h6>
                                        </div>
                                        <div class="col-md-8">                                
                                            <input name="image1" id="image1" type="file" class="w3-input w3-border mb-1">
                                            <input name="image1x" id="image1x" type="hidden">
                                            <div class="w3-card w3-border" style="min-height:150px; height: auto;">                                              
                                                <div id="gambarimage1" name="gambarimage1" class="mb-1" style="width: 100%;max-height: 250px; overflow:hidden;">                                                                         
                                                </div> 
                                            </div>
                                        </div>								  
                                    </div>   
                                    <div class="row">
                                        <div class="col-md-4 mt-1" align="right">									
                                            <h6 class="mt-2">Keterangan</h6>
                                        </div>
                                        <div class="col-md-8">                                
                                            {{-- <input name="keterangan1" id="keterangan1" class="w3-input w3-border" maxlength="255" type="search" placeholder="Keterangan" value="{{ old('keterangan1') }}" > --}}
                                            <textarea name="keterangan1" id="keterangan1" rows="5" style="width: 100%; padding-left:10px; padding-right:10px;" class="w3-textarea w3-border"></textarea>
        
                                        </div>								  
                                    </div>   
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

    function readURLimage1(input) {
    if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('.img-previewimage1').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#image1").change(function() {
        var gbrx="<img class='img-previewimage1 img-fluid col-sm-12 mb-1 mt-1 d-block' id='img-previewimage1' name='img-previewimage1' style='width: 100%;'>";
        document.getElementById("gambarimage1").innerHTML=gbrx;
        readURLimage1(this);
    });

    setTimeout(() => {
        tampil_listkategori();
        tampil_listsatuan();
    }, 1000);
    //menampilkan combo kategori
    function tampil_listkategori(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.master.mamin_listkategori')}}',
            
            success: function(data){				    
                $("#idkategori1").html(data);
            }
        })                    
    }
    //menampilkan combo satuan
    function tampil_listsatuan(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.master.mamin_listsatuan')}}',
            
            success: function(data){				    
                $("#idsatuan1").html(data);
            }
        })                    
    }
    
    setTimeout(() => {
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
            ajax   : `{{route('pos01.master.mamin_show')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'kode' },
                { data: 'barcode', name: 'barcode' },
                { data: 'namamin', name: 'namamin' },
                { data: 'kategori', name: 'kategori.kategori' },
                { data: 'satuan', name: 'satuan.satuan' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'ppnjual', name: 'ppnjual' },
                { data: 'diskonjual', name: 'diskonjual' },
                { data: 'spek', name: 'spek' },
                { data: 'image', name: 'image' },
                { data: 'expired', name: 'expired' },
                { data: 'keterangan', name: 'keterangan' },
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
        $('#image1').val('');
        var gbrx="<h5 class='mt-2' align='center'>Belum ada image yang diupload<h5>";                        
        document.getElementById("gambarimage1").innerHTML=gbrx;
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

    $("#expired1").datepicker({
           dateFormat  : "yy-mm-dd",
           changeMonth : true,
           changeYear  : true,         
    });
    
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
        $('#image1').val('');
        var gbrx="<h5 class='mt-2' align='center'>Belum ada image yang diupload<h5>";                        
        document.getElementById("gambarimage1").innerHTML=gbrx;
       
    }); 

    function data_simpan(){
        var id1=$('#id1').val();
        var kode1=$('#kode1').val();			
        var barcode1=$('#barcode1').val();
        var namamin1=$('#namamin1').val();
        var idkategori1=$('#idkategori1').val();
        var idsatuan1=$('#idsatuan1').val();
        var hjs1=$('#hjs1').val().replace(/[^,\d]/g, '').toString();
        var ppnjual1=$('#ppnjual1').val();
        var diskonjual1=$('#diskonjual1').val();
        var spek1=$('#spek1').val();
        var expired1=$('#expired1').val();
        const image1 = $('#image1').prop('files')[0];
        var keterangan1=$('#keterangan1').val();
        
        let formData = new FormData();
            formData.append('id1', id1);
            formData.append('kode1', kode1);
            formData.append('barcode1', barcode1);
            formData.append('namamin1', namamin1);
            formData.append('idkategori1', idkategori1);
            formData.append('idsatuan1', idsatuan1);
            formData.append('hjs1', hjs1);
            formData.append('ppnjual1', ppnjual1);
            formData.append('diskonjual1', diskonjual1);
            formData.append('spek1', spek1);
            formData.append('expired1', expired1);
            formData.append('image1', image1);
            formData.append('keterangan1', keterangan1);
          
        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pos01.master.mamin_create')}}',
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
                swalgagaltambah(namamin1);                 
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
		        url   : `{{ url('pos01/master/maminedit')}}/${id1}`,
		        async : false,
		        dataType : 'json',	
				
		        success : function(data){
                    var i;                
                    var resultData = data.data;	                			
                    for(i=0; i<resultData.length; i++){

                        $('#id1').val(resultData[i].id);
                        $('#kode1').val(resultData[i].kode);
                        $('#barcode1').val(resultData[i].barcode);
                        $('#namamin1').val(resultData[i].namamin);
                        $('#idkategori1').val(resultData[i].idkategori);
                        $('#idsatuan1').val(resultData[i].idsatuan);
                        $('#hjs1').val(formatAngka(resultData[i].hjs,''));;
                        $('#diskonjual1').val(resultData[i].diskonjual);
                        $('#ppnjual1').val(resultData[i].ppnjual);
                        $('#spek1').val(resultData[i].spek);
                        $('#expired1').val(resultData[i].expired);
                        
                        $('#image1').val('');
						$('#image1x').val(resultData[i].image);                
                        var gbr2=$('#image1x').val();
                        if(gbr2){                        
                            var url2="{{ asset('storage/') }}/"+gbr2;
                            var gbrx="<img src='"+url2+"' class='img-previewimage1 img-fluid col-sm-12 mb-1 mt-1 d-block' id='img-previewimage1' name='img-previewimage1' style='width: 100%;' alt='"+url2+"'>";
                        }else{
                            var gbrx="<h6 class='mt-2' align='center'>Belum ada image yang diupload<h6>";
                        }
                        document.getElementById("gambarimage1").innerHTML=gbrx;
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
            url   : '{{url('pos01/master/mamindestroy')}}/'+id3,
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