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
                    <div class="col-md-3 text-right">
                        <h6 class="mt-2">Jenis Simpanan</h6>
                    </div>
                    <div class="col-md-5">
                        <select name="idjenissimpananx1" id="idjenissimpananx1" class="js-select-auto__select form-control" style="border-radius:0px; height:40px; display: block;" autocomplete="off"></select> 
                    </div>
                </div>
                
            </div>

            <div class="col-md-6">
                <div class="w3-row" align="right"><i class="fa fa-refresh" aria-hidden="true"></i>            
                    <a href="{{ url('/') }}{{ $link }}" class="btn bg-success rounded-0"><i style="font-size:18px" class="fa">&#xf021;</i> Refresh</a>            
                    {{-- <button id="btn_tambah1" name="btn_tambah1" type="button" class="btn bg-primary rounded-0"><i class="fas fa-plus"></i> Tambah</button> --}}
                    {{-- <button id="btn_posting1" name="btn_posting1" type="button" class="btn bg-warning rounded-0"><i class="fa fa-upload"></i> Posting</button>	             --}}
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
							<th style="width:50px">Tgl.Buka Rek.</th>
							<th style="width:50px">Nomor Bukti</th>
							<th style="width:50px">No Rekening</th>
							<th style="width:50px">NIA</th>
							<th style="width:50px">NAMA</th>
							<th style="width:50px">Jenis Simpanan</th>
							<th style="width:50px">Debet</th>
							<th style="width:50px">Kredit</th>
							<th style="width:50px">Saldo</th>							
                            <th style="width:100px">Keterangan</th>
                        </tr>
                    </thead>
                    <tfoot id="show_footerdata1">
                        <tr>
                            <th></th>                            
                            <th></th>                            
                            <th style="align-items: center;">TOTAL :</th>                            
                            <th></th>                            
                            <th></th>                            
                            <th id="totaldebet1" name="totaldebet1"></th>                            
                            <th id="totalkredit1" name="totalkredit1"></th>                            
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

    <!-- khusus menyimpan data yang akan sementara -->
    <input name="id3" id="id3" type="hidden">	
    <input name="data3a" id="data3a" type="hidden">	
    <input name="data3b" id="data3b" type="hidden">	
    <input name="data3c" id="data3c" type="hidden">	
    <input name="data3d" id="data3d" type="hidden">	
    <input name="data3e" id="data3e" type="hidden">	
    <input name="data3f" id="data3f" type="hidden">	
    <input name="data3g" id="data3g" type="hidden">	
    <input name="data3h" id="data3h" type="hidden">	
    <input name="data3i" id="data3i" type="hidden">	
    <input name="data3j" id="data3j" type="hidden">	
    <input name="data3k" id="data3k" type="hidden">	
    <input name="data3l" id="data3l" type="hidden">	
    <input name="data3m" id="data3m" type="hidden">	
    <input name="data3o" id="data3o" type="hidden">	
    <input name="data3p" id="data3p" type="hidden">	
    <input name="data3q" id="data3q" type="hidden">	
    <input name="data3r" id="data3r" type="hidden">
    <input name="data3s" id="data3s" type="hidden">	
    <input name="data3t" id="data3t" type="hidden">	
    <input name="data3u" id="data3u" type="hidden">	
    <input name="data3v" id="data3v" type="hidden">	
    <input name="data3w" id="data3w" type="hidden">	
    <input name="data3x" id="data3x" type="hidden">	
    <input name="data3y" id="data3y" type="hidden">	
    <input name="data3z" id="data3z" type="hidden">	

</div>


<script type="text/javascript">
   var data1Datatable;
   $(document).ready(function(){

    // tglhariini();        
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
        // $('#tglposting5').text(tglsekarang);        
        // $('#tgltransaksix1').val(tglsekarang);
    }

    function jatuhtempo(tglawal,jmlhari){
        //tanggalawal
        let tgl = new Date(tglawal); 
        // Tambahkan jumlah hari ke tglawal 
        tgl.setDate(tgl.getDate () + jmlhari);
        let hari=tgl.getDate();
        if(hari<10){
            hari='0'+hari;
        }
        
        let bulan=tgl.getMonth()+1;
        if(bulan<10){
            bulan='0'+bulan;
        }
        let tahun=tgl.getFullYear();
        let jt = tglsekarang=tahun+'-'+bulan+'-'+hari;
        return jt;
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

    function terbilang(bilangan) {
            let x = bilangan.replace(/[^,\d]/g, '').toString();
            if (x==''){
                x = 0;
            }
            let y = parseFloat(x);
            if (y>=999999999999999){
                x = y;
            } 

			//  bilangan    = String(bilangan);
			 bilangan    = String(x);
			 var angka   = new Array('0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0');
			 var kata    = new Array('','Satu','Dua','Tiga','Empat','Lima','Enam','Tujuh','Delapan','Sembilan');
			 var tingkat = new Array('','Ribu','Juta','Milyar','Triliun');

			 var panjang_bilangan = bilangan.length;

			//pengujian panjang bilangan
			 if (panjang_bilangan > 15) {
			   kaLimat = "Diluar Batas";
			   return kaLimat;
			 }

			 //mengambil angka-angka yang ada dalam bilangan, dimasukkan ke dalam array
			 for (i = 1; i <= panjang_bilangan; i++) {
			   angka[i] = bilangan.substr(-(i),1);
			 }

			 i = 1;
			 j = 0;
			 kaLimat = "";

			//mulai proses iterasi terhadap array angka
			 while (i <= panjang_bilangan) {

			   subkaLimat = "";
			   kata1 = "";
			   kata2 = "";
			   kata3 = "";

			   //untuk Ratusan
			   if (angka[i+2] != "0") {
				 if (angka[i+2] == "1") {
				   kata1 = "Seratus";
				 } else {
				   kata1 = kata[angka[i+2]] + " Ratus";
				 }
			   }

			   //untuk Puluhan atau Belasan
			   if (angka[i+1] != "0") {
				 if (angka[i+1] == "1") {
				   if (angka[i] == "0") {
					 kata2 = "Sepuluh";
				   } else if (angka[i] == "1") {
					 kata2 = "Sebelas";
				   } else {
					 kata2 = kata[angka[i]] + " Belas";
				   }
				 } else {
				   kata2 = kata[angka[i+1]] + " Puluh";
				 }
			   }

			   //untuk Satuan
			   if (angka[i] != "0") {
				 if (angka[i+1] != "1") {
				   kata3 = kata[angka[i]];
				 }
			   }

			   //pengujian angka apakah tidak nol semua, lalu ditambahkan tingkat
			   if ((angka[i] != "0") || (angka[i+1] != "0") || (angka[i+2] != "0")) {
				 subkaLimat = kata1+" "+kata2+" "+kata3+" "+tingkat[j]+" ";
			   }

			   //gabungkan variabe sub kaLimat (untuk Satu blok 3 angka) ke variabel kaLimat
			   kaLimat = subkaLimat + kaLimat;
			   i = i + 3;
			   j = j + 1;

			 }

			//mengganti Satu Ribu jadi Seribu jika diperlukan
			 if ((angka[5] == "0") && (angka[6] == "0")) {
			   kaLimat = kaLimat.replace("Satu Ribu","Seribu");
			 }

			 return kaLimat;
		}
  

    // data1Datatable = tampil_data1();    
     function tampil_data1(){
        let i = 1;	
        return $('#data1').DataTable({
            responsive : true,
            retrieve: true,
            autoWidth : true,
            // buttons : [ {extend: 'colvis', postfixButtons: [ 'colvisRestore' ] }, {extend:'copy'}, {extend:'csv'}, {extend: 'pdf', orientation: 'portrait', pageSize: 'A4', title:'{{ $caption }}'}, {extend: 'excel', title: '{{ $caption }}'}, {extend:'print', orientation: 'portrait', pageSize: 'A4', title: '{{ $caption }}'}, ],        
            dom: 'lBfrtip',
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
                .column(7)
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                    }, 0 );
                    
            var totk2 = api
                .column(8)
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            var saldo12 = api
                .column(9)
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
           
                    
                // Update footer by showing the total with the reference of the column index
                
                $( api.column(7).footer() ).html(formatAngka(totd1,''));
                $( api.column(8).footer() ).html(formatAngka(totk2,''));
                $( api.column(9).footer() ).html(formatAngka(saldo12,''));
                
                
            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('simpanan01.laporan.rekapsimpanan_show')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'tgltransaksi', name: 'tgltransaksi', class: 'text-center' },
                { data: 'nomorbukti', name: 'nomorbukti' },
                { data: 'norek', name: 'norek', class: 'text-center' },
                { data: 'nia', name: 'anggota.nia', class: 'text-center',orderable: false },
                { data: 'nama', name: 'anggota.nama', orderable: false },
                { data: 'jenissimpanan', name: 'jenissimpanan.jenissimpanan', orderable: false },
                { data: 'debet', name: 'debet', class: 'text-right'},
                { data: 'kredit', name: 'kredit', class: 'text-right'},
                { data: 'saldo', name: 'saldo', class: 'text-right'},
                { data: 'keterangan', name: 'keterangan' },
            ],
                "createdRow": function( row, data, dataIndex){
                                                                          
                    if( data['lb']== 'Lunas'){                        
                        $('td', row).css('background-color', 'lightgreen');
                    }

                }
        });
    }

    //menampilkan combo idjenissimpananx1
    tampil_jenissimpananx1();
    function tampil_jenissimpananx1(){				
        $.ajax({
            type: 'get',
            url   : '{{route('admin.listjenissimpanan11')}}',
            
            success: function(data){				    
                $("#idjenissimpananx1").html(data);
                $("#idjenissimpananx1").val({{ session('idjenissimpananx1') }});
            }
        })                    
    }
   
    $('#idjenissimpananx1').on('click',function(){
        kirimsyarat();
        setTimeout(() => {
            data1Datatable = tampil_data1();  
            setTimeout(() => {
                data1Datatable.ajax.url('{{route('simpanan01.laporan.rekapsimpanan_show')}}').load();                
                data1Datatable.draw(null, false);

            }, 500);
        }, 500);
       					
    });

    setTimeout(() => {
        $('#idjenissimpananx1').click(); 
    }, 2000);
    
    function tampil_datatable(){        
        data1Datatable.draw(null, false);               
    }

    function kirimsyarat(){        
        var idjenissimpananx1=$('#idjenissimpananx1').val();

        let formData = new FormData();
            formData.append('idjenissimpananx1', idjenissimpananx1);
    
        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('simpanan01.laporan.rekapsimpanan_kirimsyarat')}}',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },				 				
            success : function(formData){                                                       
                tampil_datatable();                
                    

                },
            error : function(formData){                                                       
                // alert('error');
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
    
    
    //tambah data -> ok
    $('#btn_baru').on('click',function(){
        btn_baru_click();            
    });
    
    $('#btn_tambah1').on('click',function(){
        let cek = $('#cek1').val();
        if(cek==''){

            let x = $('#nomorbuktix1').val();         
    
            if(x==''){
                let comp = 'Please complete the data...!';             
                swaltidaklengkap(comp);
            }else{
                btn_baru_click();
                $("#iconx").removeClass("fas fa-edit");
                $("#iconx").addClass("fas fa-plus-square");
                $("#modalx").removeClass("modal-content bg-success w3-animate-zoom");
                $("#modalx").addClass("modal-content bg-primary w3-animate-zoom");
                document.getElementById("btn_simpan").disabled = false;
                $('#ModalAdd').modal('show');
                $('#id1').val('0');
                $('#judulx').html(' Tambah Data');
            }
        }else{
           swalpraposting('','Maaf, data sudah diposting.....'); 
        }

        
    }); 
    
    $('#show_data1').on('click','.item_edit',function(){
        $("#iconx").removeClass("fas fa-plus-square");
        $("#iconx").addClass("fas fa-edit");
        $("#modalx").removeClass("modal-content bg-primary w3-animate-zoom");
        $("#modalx").addClass("modal-content bg-success w3-animate-zoom");
        $('#judulx').html(' Edit Data');
        btn_edit_click();

        var id1=$(this).attr('data');

        $('#id1').val(id1);
        // data_edit(id1);
        
        let kode1 = $(this).attr('data2');
        let idcoa1 = $(this).attr('data3');
        let idsandi1 = $(this).attr('data4');
        let idjenisjurnal1 = $(this).attr('data5');
        let idjenissimpanan1 = $(this).attr('data6');
        let idanggota1 = $(this).attr('data7');
        let idtarget1 = $(this).attr('data8');
        let nama1 = $(this).attr('data9');
        let nia1 = $(this).attr('data10');
        let tgltransaksi1 = $(this).attr('data11');
        let nomorbukti1 = $(this).attr('data12');
        let debet1 = $(this).attr('data13');
        let kredit1 = $(this).attr('data14');
        let tipe1 = $(this).attr('data15');
        let xangsuran1 = $(this).attr('data16');
        let nilaiangsuran1 = $(this).attr('data17');
        let ke1 = $(this).attr('data18');
        let ujroh1 = $(this).attr('data19');
        let jatuhtempo1 = $(this).attr('data20');
        let keterangan1 = $(this).attr('data21');

        
        $('#ModalAdd').modal('show');         
    });

    $('#show_data1').on('click','.item_posting',function(){
        var id1 = $(this).attr('data'); 
        var tgltransaksi5 = $(this).attr('data3'); 
        var tgltransaksi5x = $(this).attr('data4'); 
        var nomorbukti5 = $(this).attr('data5'); 
        var jml5 = formatAngka(parseFloat($(this).attr('data6')),'');

        setTimeout(() => {
            nomorposting();
            setTimeout(() => {
                $('#id1').val(id1);        		
                $('#tgltransaksi5').text(tgltransaksi5);        		
                $('#tgltransaksi5x').text(tgltransaksi5x);        		
                $('#nomorbukti5').text(nomorbukti5);        		                       		
                $('#jml5').text(jml5);        		                       		
                $('#ModalPosting').modal('show');						                
            }, 10);
        }, 200);
    });

    $("#btn_update").on('click',function(){	        
        data_simpan();
    });                                         

    //modal sweet art posting		
    function modal_posting(){
        Swal.fire({
        title: 'Are you sure posting',
        text: $('#nomorposting5').text(),
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: "Yes, posting !",
        cancelButtonText: "No, cancel !",
        }).then((result) => {
            if (result.isConfirmed) {
                data_posting();                           
            }
        })
    } 
    
    $('#show_data1').on('click','.item_hapus',function(){
        var id3=$(this).attr('data');
        var data3a=$(this).attr('data2');
        var data3b=$(this).attr('data3');
        var data3c=$(this).attr('data4');
        
        $('#id3').val(id3);
        $('#data3a').val(data3a + ' (' + data3c + ')');
        $('#data3b').val(data3b);
        $('#data3c').val(data3c);
        modal_hapus();
    });
   
    $('#show_data1').on('click','.item_print',function(){ 
        $('#cekprint').val("1");
        var tglposting=$(this).attr('data7');
        $('#data3z').val(tglposting);
        var id1=$(this).attr('data');
        $('#id1').val(id1);
        kirimsyarat();
        setTimeout(() => {
            window.open('{{ route('pinjaman01.laporan.rekappinjaman_printkwitansi') }}');
        }, 100);
    });

    $('#show_data1').on('click','.item_printxxx',function(){
        var id=$(this).attr('data');
        var nomorstatus=$(this).attr('data2');
        $('#id6').val(id);
        $('#cekprint6').val('1');
        $('#nomorstatus6').text(nomorstatus);

        // kirimsyarat();
        setTimeout(() => {
            $('#ModalPrintdetail').modal('show');
        }, 200);
    });

    function swalgagalnorek(x){
        Swal.fire({
            icon: 'info',
            title: 'Oops...not found record',
            text: x,
            timer:1000
        })
    }


    function swalposting(x){
        Swal.fire({
            icon: 'success',
            title: 'Posting successfully',
            text: x,
            timer:1000
        })
    }

    function swalgagalposting(x){
        Swal.fire({
            icon: 'error',
            title: 'Oops...failed to posting record',
            text: x,
            timer:1000
        })
    }

    function swalpraposting(x,y){
        Swal.fire({
            icon: 'info',
            title: y,
            text: x,
            timer:1000
        })
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

    function swaltidaklengkap(x){
        Swal.fire({
            icon: 'error',
            title: 'Oops...incomplete data',
            text: x,
            timer:5000
        })
    }

    function swalnomorbuktisalah(x){
        Swal.fire({
            icon: 'error',
            title: 'Oops...Nomor Bukti "' + x + '" ilegal !',
            text: 'Klik Generate Nomor Bukti (icon gear)',
            timer:5000
        })
    }

   

});

</script>	



@endsection