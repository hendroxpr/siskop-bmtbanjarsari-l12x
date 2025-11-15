@extends('admin.layouts.main')

@section('contents')

@php
    $idbulanx1 = session('idbulanx1');
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
            <div class="col-md-5">                
                
                <div class="row">
                    <div class="col-md-3 mt-2 text-right">                        
                        <h6 class="mt-0">Tahun</h6>
                    </div>
                    <div class="col-md-5 mb-1">
                        <input name="tahunx1" id="tahunx1" class="w3-input w3-border" maxlength="4" type="number" placeholder="Tahun" autofocus value="{{ session('tahunx1') }}" onkeypress="return hanyaangka(event)">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mt-2 text-right">
                        <h6 class="mt-0">Bulan</h6>
                    </div>
                    <div class="col-md-5 mb-1">
                        <select name="idbulanx1" id="idbulanx1" class="w3-input w3-border" value="{{ session('idbulanx1') }}"></select>
                        <input name="bulanx1" id="bulanx1" type="hidden" value="{{ session('idbulanx1') }}"> 
                    </div>
                </div>
                             
            </div>

            <div class="col-md-5">
                {{--  --}}
                
            </div>
            
            <div class="col-md-2">
                <div class="w3-row" align="right"><i class="fa fa-refresh" aria-hidden="true"></i>            
                    <a id="btn_refresh1" name="btn_refresh1" href="{{ url('/') }}{{ $link }}" class="btn bg-success rounded-0" style="text-decoration: none;"><i style="font-size:18px" class="fa">&#xf021;</i> Refresh</a>            
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
                        <th style="width:10px;" rowspan="2" class="text-center">#</th>                            
                        <th style="width:10px" rowspan="2" class="text-center">Norek</th>
                        <th style="width:100px" rowspan="2" class="text-center">Rekening</th>
                        <th style="width:50px" colspan="2" class="text-center">Saldo Awal</th>
                        <th style="width:50px" colspan="2" class="text-center">Perubahan</th>
                        <th style="width:50px" colspan="2" class="text-center">Penyesuaian</th>
                        <th style="width:50px" colspan="2" class="text-center">Perbaikan</th>
                        <th style="width:50px" colspan="2" class="text-center">Laba Rugi</th>
                        <th style="width:50px" colspan="2" class="text-center">Saldo Akhir</th>                        
                    </tr>
                    <tr>
                        <th style="width:50px" class="text-center">Debet</th>
                        <th style="width:50px" class="text-center">Kredit</th>
                        <th style="width:50px" class="text-center">Debet</th>
                        <th style="width:50px" class="text-center">Kredit</th>
                        <th style="width:50px" class="text-center">Debet</th>
                        <th style="width:50px" class="text-center">Kredit</th>
                        <th style="width:50px" class="text-center">Debet</th>
                        <th style="width:50px" class="text-center">Kredit</th>
                        <th style="width:50px" class="text-center">Debet</th>
                        <th style="width:50px" class="text-center">Kredit</th>
                        <th style="width:50px" class="text-center">Debet</th>
                        <th style="width:50px" class="text-center">Kredit</th>                                               
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
    var data1Datatable;
    var dataneracalajur1;
    var listbulanx1Datatable

    function hanyaangka(event) {
         var angka = (event.which) ? event.which : event.keyCode
         if (angka != 46 && (angka > 31) && (angka < 48 || angka > 57)  )
           return false;
         return true;
         //47 = /
         //48 - 57 = 0 - 9
       }

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
			$('#tahunx1').val(tahun);
		}

    setTimeout(() => {
        $('#tahunx1').click();
         data1Datatable = tampil_data1();         
         setTimeout(() => {
                $('#idbulanx1').val('{{ session('idbulanx1') }}');
         }, 800);
         
    }, 1000);
    
     function tampil_data1(){
        let i = 1;        	
        dataneracalajur1 = $('#data1').DataTable({
            responsive : true,
            retrieve: true,
            autoWidth : true,
            buttons : [ {extend: 'colvis', postfixButtons: [ 'colvisRestore' ] }, {extend:'copy'}, {extend:'csv'}, {extend: 'pdf', orientation: 'portrait', pageSize: 'A4', title:'{{ $caption }}'}, {extend: 'excel', title: '{{ $caption }}'}, {extend:'print', orientation: 'portrait', pageSize: 'A4', title: '{{ $caption }}'}, ],        
            dom: 'lBrtip',
            lengthMenu: [
                [ 10, 25, 50, 100, 500, 1000, 5000, -1 ],
                [ '10', '25', '50', '100', '500','1000','5000', 'All' ]
            ],

            "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // converting to interger to find total
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // computing column Total of the complete result 
            var totd1 = api
                .column(3)
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                    }, 0 );
                    
            var totk1 = api
                    .column( 4 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                    
            var totd2 = api
                    .column(5)
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                    
            var totk2 = api
                    .column( 6 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                    
            var totd3 = api
                    .column( 7 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
            var totk3 = api
                    .column( 8 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
            var totd4 = api
                    .column( 9 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
            var totk4 = api
                    .column( 10 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
            var totd5 = api
                    .column( 11 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
            var totk5 = api
                    .column( 12 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
            var totd7 = api
                    .column( 13 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
            var totk7 = api
                    .column( 14 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                
                    
                // Update footer by showing the total with the reference of the column index 
                $( api.column(2).footer() ).html('Total :');
                $( api.column(3).footer() ).html(formatAngka(totd1,''));
                $( api.column(4).footer() ).html(formatAngka(totk1,''));
                $( api.column(5).footer() ).html(formatAngka(totd2,''));
                $( api.column(6).footer() ).html(formatAngka(totk2,''));
                $( api.column(7).footer() ).html(formatAngka(totd3,''));
                $( api.column(8).footer() ).html(formatAngka(totk3,''));
                $( api.column(9).footer() ).html(formatAngka(totd4,''));
                $( api.column(10).footer() ).html(formatAngka(totk4,''));
                $( api.column(11).footer() ).html(formatAngka(totd5,''));
                $( api.column(12).footer() ).html(formatAngka(totk5,''));
                $( api.column(13).footer() ).html(formatAngka(totd7,''));
                $( api.column(14).footer() ).html(formatAngka(totk7,''));
            },

            processing: true,
            serverSide: true,
            ajax   : '{{route('akuntansi01.laporan.neracalajur_show')}}',
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                { data: 'DT_RowIndex', orderable: false, searchable: false },                
                { data: 'kode', nama: 'kode'},
                { data: 'coa', name: 'coa' },
                { data: 'd1', name: 'd1', className: 'text-right' },
                { data: 'k1', name: 'k1', className: 'text-right' },
                { data: 'd2', name: 'd2', className: 'text-right' },
                { data: 'k2', name: 'k2', className: 'text-right' },
                { data: 'd3', name: 'd3', className: 'text-right' },
                { data: 'k3', name: 'k3', className: 'text-right' },
                { data: 'd4', name: 'd4', className: 'text-right' },
                { data: 'k4', name: 'k4', className: 'text-right' },
                { data: 'd5', name: 'd5', className: 'text-right' },
                { data: 'k5', name: 'k5', className: 'text-right' },
                { data: 'd6', name: 'd6', className: 'text-right' },
                { data: 'k6', name: 'k6', className: 'text-right' },
                
                
            ],
            
        });


            return dataneracalajur1;

    }

    add_row();
   
    $('#tahunx1').on('click',function(){
        setTimeout(() => {
            kirimsyarat();            	
            setTimeout(() => {
                listbulan1xDatatable = tampil_listbulanx();
                setTimeout(() => {
                    listbulan1xDatatable.ajax.url('{{route('akuntansi01.laporan.neracalajur_listbulan')}}').load();                
                    listbulan1xDatatable.draw(null, false);
                }, 500);            
            }, 500);
        }, 500);        
    });
    $('#tahunx1').on('change',function(){
        setTimeout(() => {
            kirimsyarat();            	
            setTimeout(() => {
                listbulan1xDatatable = tampil_listbulanx();
                setTimeout(() => {
                    listbulan1xDatatable.ajax.url('{{route('akuntansi01.laporan.neracalajur_listbulan')}}').load();                
                    listbulan1xDatatable.draw(null, false);
                }, 500);            
            }, 500);
        }, 500);        
    });

    $('#tahunx1').on('keyup',function(){
        setTimeout(() => {
            kirimsyarat();            	
            setTimeout(() => {
                listbulan1xDatatable = tampil_listbulanx();
                setTimeout(() => {
                    listbulan1xDatatable.ajax.url('{{route('akuntansi01.laporan.neracalajur_listbulan')}}').load();                
                    listbulan1xDatatable.draw(null, false);
                }, 500);            
            }, 500);
        }, 500);        
    });

    //menampilkan combo bulanx
    function tampil_listbulanx(){				
        $.ajax({
            type: 'get',
            url   : '{{route('akuntansi01.laporan.neracalajur_listbulan')}}',
            
            success: function(data){				    
                $("#idbulanx1").html(data);
                $("#idbulanx1").val({{ $idbulanx1 }});
            }
        })                    
    }

    $("#idbulanx1").on('click',function(){
                 
        $("#bulanx1").val($("#idbulanx1").val());        
        setTimeout(() => {
            kirimsyarat(); 
        }, 500);
       
    });

    // $("#idbulanx1").on('change',function(){         
    //     setTimeout(() => {
    //         kirimsyarat(); 
    //     }, 500);
        
    //     setTimeout(() => {            
    //         setTimeout(() => {
    //             tampil_dataTable();
    //             add_row();
    //         }, 500);
    //     }, 500);

    // });

    // //menampilkan combo bulan
    // function tampil_listbulan(tahunx){
    //     var tahunx1=tahunx;			
			
    //         $.ajax({
	// 	        type  : 'get',
	// 	        url   : `{{ url('akuntansi01/laporan/neracalajurlistbulan')}}/${tahunx}`,
	// 	        async : false,
	// 	        dataType : 'json',	
				
	// 	        success : function(data){
    //             var html = '';
    //             var i;                
    //             var resultData = data.data;	                			
    //             for(i=0; i<resultData.length; i++){                    
    //                 html +='<option value='+resultData[i].tglakhir+'>'+resultData[i].namabulan+'<ption>';                    
    //             }

    //             $('#idbulanx1').html(html); 
    //             $("#idbulanx1").val({{ $idbulanx1 }});
                                            
    //         },
    //             error : function(data){
    //                 // alert(tahunx1);
    //             }
	// 	    }); 
    // }

    function tampil_dataTable(){                
        data1Datatable.draw(null, false);
    }

    function add_row(){
        // var DT_RowIndex = 'saya';
        // dataneracalajur1.row
        // .add({
        //     'DT_RowIndex': 'val1',
        //     'kode': 'val1',
        //     'coa': 'val1',
        //     'd1': 'val1',
        //     'k1': 'val1',
        //     'd2': 'val1',
        //     'k2': 'val1',
        //     'd2': 'val1',
        //     'k2': 'val1',
        // })
        // .draw();
    }

    function kirimsyarat(){              
        var tahunx1=$('#tahunx1').val();
        var idbulanx1=$('#idbulanx1').val();        

        let formData = new FormData();                       
            formData.append('idbulanx1', idbulanx1);            
            formData.append('tahunx1', tahunx1);            

        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('akuntansi01.laporan.neracalajur_kirimsyarat')}}',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },				 				
            success : function(formData){                                                       
                tampil_dataTable();
                add_row();                
                },
            error : function(formData){                                                       
                // alert('error');
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