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
            <div class="col-md-4">
                <div class="row mt-2">
                    <div class="col-md-3" align="right">									
                        <h6 class="mt-2">No. Faktur</h6>
                    </div>
                    <div class="col-md-7 input-group">
                        <select  name="idhutang1" id="idhutang1" class=" form-control w3-input w3-border" style="border-radius:0px; border:none; display:block;"></select>
                        <div class="input-group-append">
                            <button id="btn_carihutang1" name="btn_carihutang1" type="button" style="border-radius:0px; border:none;"><i style="font-size:24" class="fas">&#xf002;</i></button>
                        </div>
                        <select  name="idhutangx1" id="idhutangx1" class="" style="border-radius:0px; border:none; display:none;"></select>
                        <input name="cek1" id="cek1" class="" type="hidden">                                
                        <input name="id1" id="id1" class="" type="hidden"> 
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-3 text-right">
                        <h6 class="mt-2">Supplier</h6>
                    </div>
                    <div class="col-md-7">
                        <input name="supplier1" id="supplier1" class="w3-input w3-border" type="text" placeholder="Supplier" disabled>                       
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-3 text-right">
                        <h6 class="mt-2">Alamat</h6>
                    </div>
                    <div class="col-md-7">
                        <input name="alamat1" id="alamat1" class="w3-input w3-border" type="text" placeholder="Alamat" disabled>                       
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
            <div class="col-md-4">
                 {{--  --}}
            </div>
            <div class="col-md-4">
                <div class="w3-row" align="right"><i class="fa fa-refresh" aria-hidden="true"></i>            
                    <a href="{{ url('/') }}{{ $link }}" class="btn bg-success rounded-0"><i style="font-size:18px" class="fa">&#xf021;</i> Refresh</a>            
                </div> 
            </div>
        </div>

    </div>
    
    <!--awal tabel-->        
    <div class="box-body" id="headerjudul" style="display: block;">
       
                <div id="reload" class="table-responsive">
                    <table id="kartuhutang1" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>                            
                                <th style="width:30px">Faktur</th>
                                <th style="width:20px">Tanggal</th>
                                <th style="width:100px">Supplier</th>
                                <th style="width:100px">Alamat</th>							
                                <th style="width:10px">x Angs</th>							
                                <th style="width:20px">Awal</th>							
                                <th style="width:20px">Hutang</th>							
                                <th style="width:20px">Bayar</th>							
                                <th style="width:20px">Saldo</th>							
                                							
                            </tr>
                        </thead>
                        <tfoot id="show_footerkartuhutang1">
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
                            </tr>
                        </tfoot>
                        <tbody id="show_kartuhutang1">
                        
                        </tbody>
                    </table>            
                </div>
            </div>
        </div>
            

  
<!--akhir tabel-->

<!-- ModalCariHutang modal fade-->
	<div class="modal fade" id="ModalCariHutang"  data-backdrop="static">
		<div class="modal-dialog modal-lg">  <!-- modal-(sm, lg, xl) ukuran lebar modal -->
			<div class="modal-content bg-info w3-animate-zoom">
				
				<div class="modal-header">
						<h3 class="modal-title"><i style="font-size:18" class="fas">&#xf002;</i><b> Cari Data Hutang</b></h3>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
						
				</div>
				<form class="form-horizontal">
                    @csrf
					<div class="modal-body">
												
						<div class="row">
							<div id="reload" class="table-responsive">
								<table id="carihutang" width="100%" class="table table-bordered table-striped table-hover">
									<thead>
									<tr>
										<th width="5px">#</th>
										<th width="20px">Faktur</th>																									
										<th width="50px">Supplier</th>																									
										<th width="100px">Alamat</th>																									
										<th width="10px">x Angs</th>																									
										<th width="20px">Hutang</th>																									
										<th width="20px">Keterangan</th>																									
									</tr>
								</thead>
								
								<tfoot id="show_footercarihutang">
									
								</tfoot>
								<tbody id="show_datacarihutang">
								
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
    var kartuhutang1Datatable;

    var listhutangDatatable;
    var carihutangDatatable;
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

    function tgl_sekarang(){
        var x = new Date();
        var tgl = x.getDate();
        if(tgl<10){
            tgl='0'+tgl;
        }
        var bln = x.getMonth()+1;
        if(bln<10){
            bln='0'+bln;
        }
        var thn = x.getFullYear();

			return thn+'-'+bln+'-'+tgl;

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

    //menampilkan combo hutang
    tampil_listhutang();
    function tampil_listhutang(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.laporan.kartuhutang_listhutang')}}',
            
            success: function(data){				    
                $("#idhutang1").html(data);                
            }
        })                    
    }

    //menampilkan combo hutangx
    tampil_listhutangx1();
    function tampil_listhutangx1(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.laporan.kartuhutang_listhutangx')}}',
            
            success: function(data){				    
                $("#idhutangx1").html(data); 
                setTimeout(() => {
                    var b = $('#idhutangx1 option:selected').text();
                    setTimeout(() => {
                        const bArray = b.split("|");
                        $('#supplier1').val(bArray[1]);
                        $('#alamat1').val(bArray[2]);
                    }, 100);
                }, 100);               
            }
        })                    
    }
   
    koneksi_datatable();

    $('#idhutang1').on('change',function(){	
        var a = $('#idhutang1').val();
        $('#idhutangx1').val(a);
        var b = $('#idhutangx1 option:selected').text();
        setTimeout(() => {
            const bArray = b.split("|");
            $('#supplier1').val(bArray[1]);
            $('#alamat1').val(bArray[2]);
            kirimsyarat();
        }, 100);
    });
    
    function kirimsyarat(){
        var idhutang1=$('#idhutang1').val();

        let formData = new FormData();
            formData.append('idhutang1', idhutang1);

        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pos01.laporan.kartuhutang_kirimsyarat')}}',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },				 				
            success : function(formData){ 
                    tampil_dataTable();                  
                }
        });
    }
    
    function tampil_kartuhutang1(){
        let i = 1;	
        return $('#kartuhutang1').DataTable({
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
            hutang = api
                .column(7)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            
            bayar = api
                .column(8)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            
            // Total over this page
            pagehutang = api
                .column(7, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            pagebayar = api
                .column(8, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(1).footer().innerHTML = 'SUB TOTAL :';
            api.column(7).footer().innerHTML = formatAngka(pagehutang,'');
            api.column(8).footer().innerHTML = formatAngka(pagebayar,'');
            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.kartuhutang_showkartuhutang')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nomorstatus', name: 'nomorstatus', className: 'dt-center' },
                { data: 'tglstatus', name: 'tglstatus', className: 'dt-center' },
                { data: 'supplier', name: 'supplier.supplier', className: 'dt-left' },
                { data: 'alamat', name: 'supplier.alamat', className: 'dt-center' },
                { data: 'xangsuran', name: 'xangsuran', className: 'dt-center' },
                { data: 'awal', name: 'awal', className: 'dt-right' },
                { data: 'masuk', name: 'masuk', className: 'dt-right' },
                { data: 'keluar', name: 'keluar', className: 'dt-right' },
                { data: 'akhir', name: 'akhir', className: 'dt-right' },
            ]
        });
    }

    function tampil_dataTable(){        
        kartuhutang1Datatable.draw(null, false);        
            
    }

    function koneksi_datatable(){
        kartuhutang1Datatable = tampil_kartuhutang1();    
    }

   $('#btn_carihutang1').on('click',function(){
        setTimeout(() => {
            $('#ModalCariHutang').modal('show');						
        }, 300);
    });

tampil_carihutang();    
function tampil_carihutang(){
        let i = 1;	
        return $('#carihutang').DataTable({
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
            ajax   : `{{route('pos01.laporan.kartuhutang_showhutang')}}`,
            "createdRow": function(row, data, dataIndex) {
                if (data["keterangan"] == "Sudah Lunas") {
                    $(row).css("background-color", "red");
                    $(row).addClass("warning");
                }
            },
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nomorstatus', name: 'nomorstatus', className: 'dt-center' },
                { data: 'supplier', name: 'supplier' },
                { data: 'alamat', name: 'alamat' },
                { data: 'xangsuran', name: 'xangsuran', className: 'dt-center' },
                { data: 'asli', name: 'asli', className: 'dt-right' },
                { data: 'keterangan', name: 'keterangan', className: 'dt-center' },
                               
            ]
        });
    }

     $('#show_datacarihutang').on('click','.item_nomorstatus',function(){
        ambilcari(this);        
    });
    
    function ambilcari(t){
        var data1 = $(t).attr('data1');
        var data2 = $(t).attr('data2');
        var data3 = $(t).attr('data3');
        var data4 = $(t).attr('data4');
        $('#idhutang1').val(data1);
        setTimeout(() => {
             $('#idhutang1').change();
            $('#ModalCariHutang').modal('hide');
        }, 100);

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

    function swalgagalpilih(x){
        Swal.fire({
            icon: 'info',
            title: 'Oops...failed to select',
            html: x,
            timer:5000
        })
    }

});

</script>	



@endsection