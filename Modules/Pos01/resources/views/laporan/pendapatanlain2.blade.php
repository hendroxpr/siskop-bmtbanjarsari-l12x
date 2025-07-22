@extends('admin.layouts.main')

@section('contents')

@php
    $tgltransaksi1 = session('tgltransaksi1');   
    if($tgltransaksi1==''){
        $tgltransaksi1=session('memtanggal');;  
    }    
    $tgltransaksi2 = session('tgltransaksi2');   
    if($tgltransaksi2==''){
        $tgltransaksi2=session('memtanggal');;  
    }

    $idruang = session('idruangx1');
    if($idruang==''){
        $idruang = 0;
    }    
    $idkategoribiaya = session('idketegoribiayax1');
    if($idkategoribiaya==''){
        $idkategoribiaya = 0;
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
            <div class="col-md-6">
                
                <div class="row">
                    <div class="col-md-3 mt-2 text-right">
                        <h6>Lokasi</h6>
                    </div>
                    <div class="col-md-5">
                        <select name="idruangx1" id="idruangx1" class="w3-input w3-border" value="{{ $idruang }}"></select> 
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-md-3 mt-2 text-right">
                        <h6>Kategori Pendapatan</h6>
                    </div>
                    <div class="col-md-5">
                        <select name="idkategoribiayax1" id="idkategoribiayax1" class="w3-input w3-border" value="{{ $idkategoribiaya }}"></select> 
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-md-3 text-right">
                        <h6 class="mt-2">Periode Tanggal</h6>
                    </div>
                    <div class="col-md-2">
                        <input name="tgltransaksi1" id="tgltransaksi1" class="w3-input w3-border" maxlength="10" type="text" placeholder="Tanggal awal" autocomplete="off" value="{{ $tgltransaksi1 }}">                       
                    </div>
                    <div class="col-md-1 text-center">
                        <h6 class="mt-2">s/d</h6>
                    </div>
                    <div class="col-md-2">
                        <input name="tgltransaksi2" id="tgltransaksi2" class="w3-input w3-border" maxlength="10" type="text" placeholder="Tanggal akhir" autocomplete="off" value="{{ $tgltransaksi2 }}">                       
                    </div>
                </div>
                
            </div>
            <div class="col-md-6">
                <div class="w3-row" align="right"><i class="fa fa-refresh" aria-hidden="true"></i>            
                    <a href="{{ url('/') }}{{ $link }}" class="btn bg-success rounded-0"><i style="font-size:18px" class="fa">&#xf021;</i> Refresh</a>            
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
                        <th style="width:100px">Lokasi</th>
                        <th style="width:20px">Tanggal</th>
                        <th style="width:20px">Kategori</th>
                        <th style="width:20px">No.Bukti</th>
                        <th style="width:200px">Item Pendapatan</th>
                        <th style="width:10px">Satuan</th>							
                        <th style="width:10px">Qty</th>							
                        <th style="width:20px">HJS</th>							
                        <th style="width:20px">Sub Total</th>							
                        <th style="width:20px">PPN</th>							
                        <th style="width:20px">Diskon</th>							
                        <th style="width:20px">Total Jual</th>							
                        <th style="width:100px">Keterangan</th>							
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
        tampil_listkategoribiaya();
    }, 500);
    
    setTimeout(() => {
        $('#idruangx1').change();
        $('#idkategoribiayax1').change();
        data1Datatable = tampil_data1();    
    }, 1000);

    $("#tgltransaksi1").datepicker({
           dateFormat  : "yy-mm-dd",
           changeMonth : true,
           changeYear  : true         
    });
    $("#tgltransaksi2").datepicker({
           dateFormat  : "yy-mm-dd",
           changeMonth : true,
           changeYear  : true         
    });

    $('#tgltransaksi1').on('change',function(){
        setTimeout(() => {
            kirimsyarat();	
        }, 500);
    });
    $('#tgltransaksi2').on('change',function(){
        setTimeout(() => {
            kirimsyarat();	
        }, 500);
    });

    $('#idruangx1').on('change',function(){
        setTimeout(() => {
            kirimsyarat();	
        }, 500);
    });
    $('#idkategoribiayax1').on('change',function(){
        setTimeout(() => {
            kirimsyarat();	
        }, 500);
    });

    function kirimsyarat(){
        var idruangx1=$('#idruangx1').val();
        var idkategoribiayax1=$('#idkategoribiayax1').val();
        var tgltransaksi1=$('#tgltransaksi1').val();
        var tgltransaksi2=$('#tgltransaksi2').val();
        
        let formData = new FormData();
            formData.append('idruangx1', idruangx1);
            formData.append('idkategoribiayax1', idkategoribiayax1);
            formData.append('tgltransaksi1', tgltransaksi1);
            formData.append('tgltransaksi2', tgltransaksi2);

        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pos01.laporan.pendapatanlain2_kirimsyarat')}}',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },				 				
            success : function(formData){ 
                $("#idruangx1").val(idruangx1);                                        
                $("#idkategoribiayax1").val(idkategoribiayax1);                                        
                $("#tgltransaksi1").val(tgltransaksi1);                                        
                $("#tgltransaksi2").val(tgltransaksi2);                                        
                tampil_dataTable();                   
                }
        });
    }
 
    //menampilkan combo ruang
    function tampil_listruang(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.laporan.pendapatanlain2_listruang')}}',
            
            success: function(data){				    
                $("#idruangx1").html(data);
                $("#idruangx1").val({{ $idruang }});

            }
        })                    
    }
    //menampilkan combo kategoribiaya
    function tampil_listkategoribiaya(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.laporan.pendapatanlain2_listkategoribiaya')}}',
            
            success: function(data){				    
                $("#idkategoribiayax1").html(data);
                $("#idkategoribiayax1").val({{ $idkategoribiaya }});

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
            subtotal = api
                .column(9)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            ppn = api
                .column(10)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            diskon = api
                .column(11)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            totaljual = api
                .column(12)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Total over this page
            pagesubtotal = api
                .column(9, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pageppn = api
                .column(10, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagediskon = api
                .column(11, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagetotaljual = api
                .column(12, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(5).footer().innerHTML = 'SUB TOTAL :';
            api.column(9).footer().innerHTML = formatAngka(pagesubtotal,'');            
            api.column(10).footer().innerHTML = formatAngka(pageppn,'');            
            api.column(11).footer().innerHTML = formatAngka(pagediskon,'');            
            api.column(12).footer().innerHTML = formatAngka(pagetotaljual,'');            
            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.pendapatanlain2_show')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'ruang', name: 'ruang.ruang', className: 'dt-left' },
                { data: 'tgltransaksi', name: 'tgltransaksi', className: 'dt-center' },
                { data: 'kategoribiaya', name: 'kategoribiaya.kategoribiaya', className: 'dt-left' },
                { data: 'nomorbukti', name: 'nomorbukti', className: 'dt-center' },
                { data: 'pendapatan', name: 'pendapatan', className: 'dt-left' },
                { data: 'satuan', name: 'satuan.satuan', className: 'dt-center' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'subtotal', name: 'subtotal', className: 'dt-right' },
                { data: 'ppn', name: 'ppn', className: 'dt-right' },
                { data: 'diskon', name: 'diskon', className: 'dt-right' },
                { data: 'totaljual', name: 'totaljual', className: 'dt-right' },
                { data: 'keterangan', name: 'keterangan', className: 'dt-left' },
            ]
        });
    }

    function tampil_dataTable(){        
        data1Datatable.draw(null, false);        
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