@extends('admin.layouts.main')

@section('contents')

@php
    $idkelompok = session('idkelompok1');    
    $idkategori = session('idkategori1');    
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
                <input name="idtab1" id="idtab1" type="hidden" value="0">
            </div>

            <div class="col-md-6">
                <div class="w3-row" align="right"><i class="fa fa-refresh" aria-hidden="true"></i>            
                    <a id="btn_refresh1" name="btn_refresh1" href="{{ url('/') }}{{ $link }}" class="btn bg-success rounded-0" style="text-decoration: none;"><i style="font-size:18px" class="fa">&#xf021;</i> Refresh</a>            
                    <button id="btn_tambah1" name="btn_tambah1" type="button" class="btn bg-primary rounded-0"><i class="fas fa-plus"></i> Tambah</button>
                </div> 
            </div>
        </div>

    </div>

    <ul class="nav nav-tabs" id="tab-coa" role="tablist">
        <li class="nav-item">
            <a class="nav-link" id="tab-all" data-toggle="pill" href="#isi-tab-all" role="tab" aria-controls="tab-all" aria-selected="false">All</a>
        </li>              
        <li class="nav-item">
            <a class="nav-link" id="tab-assets" data-toggle="pill" href="#isi-tab-assets" role="tab" aria-controls="tab-assets" aria-selected="false">1-Assets</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-liability" data-toggle="pill" href="#isi-tab-liability" role="tab" aria-controls="tab-liability" aria-selected="false">2-Liability</a>
        </li>        
        <li class="nav-item">
            <a class="nav-link" id="tab-equity" data-toggle="pill" href="#isi-tab-equity" role="tab" aria-controls="tab-equity" aria-selected="false">3-Equity</a>
        </li>        
        <li class="nav-item">
            <a class="nav-link" id="tab-income" data-toggle="pill" href="#isi-tab-income" role="tab" aria-controls="tab-income" aria-selected="false">4-Income</a>
        </li>        
        <li class="nav-item">
            <a class="nav-link" id="tab-costofsales" data-toggle="pill" href="#isi-tab-costofsales" role="tab" aria-controls="tab-costofsales" aria-selected="false">5-Cost Of Sales</a>
        </li>        
        <li class="nav-item">
            <a class="nav-link" id="tab-expense" data-toggle="pill" href="#isi-tab-expense" role="tab" aria-controls="tab-expense" aria-selected="false">6-Expense</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-otherincome" data-toggle="pill" href="#isi-tab-otherincome" role="tab" aria-controls="tab-otherincome" aria-selected="false">8-Other Income</a>
        </li> 
        <li class="nav-item">
            <a class="nav-link" id="tab-otherexpense" data-toggle="pill" href="#isi-tab-otherexpense" role="tab" aria-controls="tab-otherexpense" aria-selected="false">9-Other Expense</a>
        </li>        
    </ul>

    <!--awal tabel-->        
    <div class="box-body" id="headerjudul" style="display: block;">
        <div class="tab-content mt-3" id="tab-coa-tabContent">
            <!--tab all -->
            <div class="tab-pane fade" id="isi-tab-all" role="tabpanel" aria-labelledby="tab-all">
                <div id="reload" class="table-responsive">
                    <table id="all" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>
                                <th style="width:20px">Norek</th>
                                <th style="width:200px">Rekening</th>
                                <th style="width:100px">Kelompok</th>
                                <th style="width:100px">Kategori</th>
                                <th style="width:5px">H/D</th>
                                <th style="width:50px">Debet</th>
                                <th style="width:50px">Kredit</th>
                                <th style="width:100px">Keterangan</th>
                                <th style="width:50px">Action</th>
                            </tr>
                        </thead>
                        <tfoot id="show_footer">
                            
                        </tfoot>
                        <tbody id="show_all">
                        
                        </tbody>
                    </table>		
                </div>
            </div>

            <!--tab assets -->
            <div class="tab-pane fade" id="isi-tab-assets" role="tabpanel" aria-labelledby="tab-assets">
                <div id="reload" class="table-responsive">
                    <table id="assets" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>
                                <th style="width:20px">Norek</th>
                                <th style="width:200px">Rekening</th>
                                <th style="width:100px">Kelompok</th>
                                <th style="width:100px">Kategori</th>
                                <th style="width:5px">H/D</th>
                                <th style="width:50px">Debet</th>
                                <th style="width:50px">Kredit</th>
                                <th style="width:100px">Keterangan</th>
                                <th style="width:50px">Action</th>
                            </tr>
                        </thead>
                        <tfoot id="show_footer">
                            
                        </tfoot>
                        <tbody id="show_assets">
                        
                        </tbody>
                    </table>		
                </div>
            </div>

            <!--tab liability -->
            <div class="tab-pane fade" id="isi-tab-liability" role="tabpanel" aria-labelledby="tab-liability">
                <div id="reload" class="table-responsive">
                    <table id="liability" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>
                                <th style="width:20px">Norek</th>
                                <th style="width:200px">Rekening</th>
                                <th style="width:100px">Kelompok</th>
                                <th style="width:100px">Kategori</th>
                                <th style="width:5px">H/D</th>
                                <th style="width:50px">Debet</th>
                                <th style="width:50px">Kredit</th>
                                <th style="width:100px">Keterangan</th>
                                <th style="width:50px">Action</th>
                            </tr>
                        </thead>
                        <tfoot id="show_footer">
                            
                        </tfoot>
                        <tbody id="show_liability">
                        
                        </tbody>
                    </table>		
                </div>
            </div>

            <!--tab equity -->
            <div class="tab-pane fade" id="isi-tab-equity" role="tabpanel" aria-labelledby="tab-equity">
                <div id="reload" class="table-responsive">
                    <table id="equity" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>
                                <th style="width:20px">Norek</th>
                                <th style="width:200px">Rekening</th>
                                <th style="width:100px">Kelompok</th>
                                <th style="width:100px">Kategori</th>
                                <th style="width:5px">H/D</th>
                                <th style="width:50px">Debet</th>
                                <th style="width:50px">Kredit</th>
                                <th style="width:100px">Keterangan</th>
                                <th style="width:50px">Action</th>
                            </tr>
                        </thead>
                        <tfoot id="show_footer">
                            
                        </tfoot>
                        <tbody id="show_equity">
                        
                        </tbody>
                    </table>		
                </div>
            </div>

            <!--tab income -->
            <div class="tab-pane fade" id="isi-tab-income" role="tabpanel" aria-labelledby="tab-income">
                <div id="reload" class="table-responsive">
                    <table id="income" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>
                                <th style="width:20px">Norek</th>
                                <th style="width:200px">Rekening</th>
                                <th style="width:100px">Kelompok</th>
                                <th style="width:100px">Kategori</th>
                                <th style="width:5px">H/D</th>
                                <th style="width:50px">Debet</th>
                                <th style="width:50px">Kredit</th>
                                <th style="width:100px">Keterangan</th>
                                <th style="width:50px">Action</th>
                            </tr>
                        </thead>
                        <tfoot id="show_footer">
                            
                        </tfoot>
                        <tbody id="show_income">
                        
                        </tbody>
                    </table>		
                </div>
            </div>

            <!--tab costofsales -->
            <div class="tab-pane fade" id="isi-tab-costofsales" role="tabpanel" aria-labelledby="tab-costofsales">
                <div id="reload" class="table-responsive">
                    <table id="costofsales" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>
                                <th style="width:20px">Norek</th>
                                <th style="width:200px">Rekening</th>
                                <th style="width:100px">Kelompok</th>
                                <th style="width:100px">Kategori</th>
                                <th style="width:5px">H/D</th>
                                <th style="width:50px">Debet</th>
                                <th style="width:50px">Kredit</th>
                                <th style="width:100px">Keterangan</th>
                                <th style="width:50px">Action</th>
                            </tr>
                        </thead>
                        <tfoot id="show_footer">
                            
                        </tfoot>
                        <tbody id="show_costofsales">
                        
                        </tbody>
                    </table>		
                </div>
            </div>

            <!--tab expense -->
            <div class="tab-pane fade" id="isi-tab-expense" role="tabpanel" aria-labelledby="tab-expense">
                <div id="reload" class="table-responsive">
                    <table id="expense" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>
                                <th style="width:20px">Norek</th>
                                <th style="width:200px">Rekening</th>
                                <th style="width:100px">Kelompok</th>
                                <th style="width:100px">Kategori</th>
                                <th style="width:5px">H/D</th>
                                <th style="width:50px">Debet</th>
                                <th style="width:50px">Kredit</th>
                                <th style="width:100px">Keterangan</th>
                                <th style="width:50px">Action</th>
                            </tr>
                        </thead>
                        <tfoot id="show_footer">
                            
                        </tfoot>
                        <tbody id="show_expense">
                        
                        </tbody>
                    </table>		
                </div>
            </div>

            <!--tab otherincome -->
            <div class="tab-pane fade" id="isi-tab-otherincome" role="tabpanel" aria-labelledby="tab-otherincome">
                <div id="reload" class="table-responsive">
                    <table id="otherincome" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>
                                <th style="width:20px">Norek</th>
                                <th style="width:200px">Rekening</th>
                                <th style="width:100px">Kelompok</th>
                                <th style="width:100px">Kategori</th>
                                <th style="width:5px">H/D</th>
                                <th style="width:50px">Debet</th>
                                <th style="width:50px">Kredit</th>
                                <th style="width:100px">Keterangan</th>
                                <th style="width:50px">Action</th>
                            </tr>
                        </thead>
                        <tfoot id="show_footer">
                            
                        </tfoot>
                        <tbody id="show_otherincome">
                        
                        </tbody>
                    </table>		
                </div>
            </div>

            <!--tab otherexpense -->
            <div class="tab-pane fade" id="isi-tab-otherexpense" role="tabpanel" aria-labelledby="tab-otherexpense">
                <div id="reload" class="table-responsive">
                    <table id="otherexpense" class="table table-bordered table-striped table-hover" style="width: 100%">
                        <thead>
                            <tr>
                                <th style="width:10px;">#</th>
                                <th style="width:20px">Norek</th>
                                <th style="width:200px">Rekening</th>
                                <th style="width:100px">Kelompok</th>
                                <th style="width:100px">Kategori</th>
                                <th style="width:5px">H/D</th>
                                <th style="width:50px">Debet</th>
                                <th style="width:50px">Kredit</th>
                                <th style="width:100px">Keterangan</th>
                                <th style="width:50px">Action</th>
                            </tr>
                        </thead>
                        <tfoot id="show_footer">
                            
                        </tfoot>
                        <tbody id="show_otherexpense">
                        
                        </tbody>
                    </table>		
                </div>
            </div>


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
                                <div class="col-md-4" align="right">									
                                    <h6 class="mt-2">H/D</h6>
                                </div>            
                                <div class="col-md-8 mt-1" style="padding-left: 20px;">
                                    <div class="icheck-primary-white d-inline">
                                      <input type="radio" value='H' id="golongan1xh" name="golongan1x">
                                      <label for="golongan1xh">
                                        <span class="text" style="padding-left: 2px; padding-right: 15px;">Header</span>
                                      </label>
                                    </div>
                                    <div class="icheck-primary-white d-inline text-white">
                                      <input type="radio" value='D' id="golongan1xd" name="golongan1x" checked>
                                      <label for="golongan1xd">
                                        <span class="text" style="padding-left: 2px; padding-right: 15px;">Detail</span>
                                      </label>
                                    </div>
                                    <input name="golongan1" id="golongan1" type="hidden" value="D">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mt-1" align="right">
                                    <h6 class="mt-2">Kelompok</h6>
                                </div>
                                <div class="col-md-8">
                                    <select name="idkelompok1" id="idkelompok1" class="w3-input w3-border"></select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mt-1" align="right">
                                    <h6 class="mt-2">Kategori</h6>
                                </div>
                                <div class="col-md-8">
                                    <select name="idkategori1" id="idkategori1" class="w3-input w3-border"></select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mt-1" align="right">									
                                    <h6 class="mt-2">Nomor Rekening</h6>
                                </div>
                                <div class="col-md-8">                                
                                    <input name="kode1" id="kode1" class="w3-input w3-border" title="Nomor Rekening harus 7 angka" maxlength="7" type="search" placeholder="Nomer Rekening" value="{{ old('kode1') }}" onkeypress="return hanyaAngka(event)">
                                    <input name="cek1" id="cek1" class="" type="hidden">                                
                                    <input name="id1" id="id1" class="" type="hidden">
                                </div>								  
                            </div>
                            <div class="row">
                                <div class="col-md-4 mt-1" align="right">
                                    <h6 class="mt-2">Rekening</h6>
                                </div>
                                <div class="col-md-8">                                
                                    <input name="coa1" id="coa1" class="w3-input w3-border" maxlength="50" type="search" placeholder="Rekening" value="{{ old('coa1') }}">
                                </div>								  
                            </div>

                            <div class="row">
                                <div class="col-md-4 mt-1" align="right">
                                    <h6 class="mt-2">Debet</h6>
                                </div>
                                <div class="col-md-8">
                                    <input name="debet1" id="debet1" class="w3-input w3-border text-right" type="text" value="0" placeholder="Debet" readonly>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mt-1" align="right">
                                    <h6 class="mt-2">Kredit</h6>
                                </div>
                                <div class="col-md-8">
                                    <input name="kredit1" id="kredit1" class="w3-input w3-border text-right" type="text" value="0" placeholder="Kredit" readonly>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mt-1" align="right">
                                    <h6 class="mt-2">Keterangan</h6>
                                </div>
                                <div class="col-md-8">
                                    <input name="keterangan1" id="keterangan1" maxlength="50" class="w3-input w3-border" type="search" placeholder="Keterangan">                                
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
    <input name="data3d" id="data3d"type="hidden">	
    <input name="data3e" id="data3e"type="hidden">	
    <input name="data3f" id="data3f"type="hidden">	
    <input name="data3g" id="data3g"type="hidden">	
    <input name="data3h" id="data3h"type="hidden">	

</div>


<script type="text/javascript">
    var allDatatable;
    var assetsDatatable;
    var liabilityDatatable;
    var equityDatatable;
    var incomeDatatable;
    var costofsalesDatatable;
    var expenseDatatable;
    var otherincomeDatatable;
    var otherexpenseDatatable;

    function hanyaAngka(event) {
         var angka = (event.which) ? event.which : event.keyCode
         if (angka != 46 && (angka > 31) && (angka < 48 || angka > 57) )
           return false;
         return true;
         //47 = /
         //48 - 57 = 0 - 9
       }

$(document).ready(function(){
        
    $('#tab-all').click();

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

    $('#tab-all').on('click',function(){
        $('#idtab1').val('0');
        kirimsyarat();
        setTimeout(() => {
            allDatatable = tampil_data_all();
            tampil_dataTable();            
        }, 500);
    });

    $('#tab-assets').on('click',function(){
        $('#idtab1').val('1');
        $("#idkelompok1").val($('#idtab1').val());
        $("#idkelompok1").click();
        kirimsyarat();
        setTimeout(() => {            
            assetsDatatable = tampil_data_assets();
            tampil_dataTable();          
        }, 500);
    });
    $('#tab-liability').on('click',function(){
        $('#idtab1').val('2');
        $("#idkelompok1").val($('#idtab1').val());
        kirimsyarat();
        setTimeout(() => { 
            liabilityDatatable = tampil_data_liability(); 
            tampil_dataTable();        
        }, 500);
    });
    $('#tab-equity').on('click',function(){
        $('#idtab1').val('3');
        $("#idkelompok1").val($('#idtab1').val());
        $("#idkelompok1").click();
        kirimsyarat();
        setTimeout(() => {             
            equityDatatable = tampil_data_equity(); 
            tampil_dataTable();        
        }, 500);
    });
    $('#tab-income').on('click',function(){
        $('#idtab1').val('4');
        $("#idkelompok1").val($('#idtab1').val());
        $("#idkelompok1").click();
        kirimsyarat();
        setTimeout(() => {             
            incomeDatatable = tampil_data_income();
            tampil_dataTable();         
        }, 500);
    });
    $('#tab-costofsales').on('click',function(){
        $('#idtab1').val('5');
        $("#idkelompok1").val($('#idtab1').val());
        $("#idkelompok1").click();
        kirimsyarat();
        setTimeout(() => {             
            costofsalesDatatable = tampil_data_costofsales();
            tampil_dataTable();
        }, 500);
    });
    $('#tab-expense').on('click',function(){
        $('#idtab1').val('6');
        $("#idkelompok1").val($('#idtab1').val());
        $("#idkelompok1").click();
        kirimsyarat();
        setTimeout(() => {             
            expenseDatatable = tampil_data_expense();
            tampil_dataTable(); 
        }, 500);
    });
    $('#tab-otherincome').on('click',function(){
        $('#idtab1').val('8');
        $("#idkelompok1").val($('#idtab1').val());
        $("#idkelompok1").click();
        kirimsyarat();
        setTimeout(() => {             
            otherincomeDatatable = tampil_data_otherincome();
            tampil_dataTable();         
        }, 500);
    });
    $('#tab-otherexpense').on('click',function(){
        $('#idtab1').val('9');
        $("#idkelompok1").val($('#idtab1').val());
        $("#idkelompok1").click();
        kirimsyarat();
        setTimeout(() => {             
            otherexpenseDatatable = tampil_data_otherexpense();
            tampil_dataTable(); 
        }, 500);
    });
    
    //menampilkan combo kelompok
    function tampil_listkelompok(){				
        $.ajax({
            type: 'get',
            url   : '{{route('akuntansi01.master.coa_listkelompok')}}',
            
            success: function(data){				    
                $("#idkelompok1").html(data);
                $("#idkelompok1").val({{ $idkelompok }});
            }
        })                    
    }

    //menampilkan combo kategori
    function tampil_listkategori(idx){
        var id1=idx;			
			
            $.ajax({
		        type  : 'get',
		        url   : `{{ url('akuntansi01/master/coalistkategori')}}/${id1}`,
		        async : false,
		        dataType : 'json',	
				
		        success : function(data){
                var html = '';
                var i;                
                var resultData = data.data;	                			
                for(i=0; i<resultData.length; i++){                    
                    html +='<option value='+resultData[i].id+'>'+resultData[i].kode+ ' - ' +resultData[i].kategori+'<ption>';                    
                }

                $('#idkategori1').html(html); 
                $("#idkategori1").val({{ $idkategori }});
                                            
            },
                error : function(data){
                    alert('id1');
                }
		    }); 
    }

    //menampilkan combo kategori2    
    function tampil_listkategori2(){				
        $.ajax({
            type: 'get',
            url   : '{{route('akuntansi01.master.coa_listkategori2')}}',
            
            success: function(data){				    
                $("#idkategori1").html(data);
            }
        })                    
    }

    $('#idkelompok1').on('click',function(){
        setTimeout(() => {
            kirimsyarat();	
        }, 500);
        var idx = $('#idkelompok1').val();

        setTimeout(() => {
            tampil_listkategori(idx);        
        }, 500);

        setTimeout(() => {
            var x = $('#idkelompok1 option:selected').text();        
            const xArray = x.split(" ");
            var x1 = xArray[0];
    
            var y = $('#idkategori1 option:selected').text();
            const yArray = y.split(" ");
            var y1 = yArray[0];
    
            $('#kode1').val(x1+y1);
        }, 1000);
    });

    $('#idkategori1').on('click',function(){
        setTimeout(() => {
            kirimsyarat();	
        }, 500);

        setTimeout(() => {
            var x = $('#idkelompok1 option:selected').text();        
            const xArray = x.split(" ");
            var x1 = xArray[0];
    
            var y = $('#idkategori1 option:selected').text();
            const yArray = y.split(" ");
            var y1 = yArray[0];
    
            $('#kode1').val(x1+y1);
        }, 1000);
    });

    function kirimsyarat(){
        var idkelompok1=$('#idkelompok1').val();
        var idkategori1=$('#idkategori1').val();
        var idtab1=$('#idtab1').val();
        
        let formData = new FormData();
            formData.append('idkelompok1', idkelompok1);
            formData.append('idkategori1', idkategori1);
            formData.append('idtab1', idtab1);

        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('akuntansi01.master.coa_kirimsyarat')}}',
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

    setTimeout(() => {
        tampil_listkelompok();

        setTimeout(() => {
            $('#idkelompok1').click();
            setTimeout(() => {
                $('#idkategori1').click();
            }, 500);
        }, 500);

        allDatatable = tampil_data_all(); 
        
           
    }, 1000);
    
     function tampil_data_all(){
        let i = 1;	
        return $('#all').DataTable({
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
            ajax   : '{{route('akuntansi01.master.coa_show')}}',
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'kode' },
                { data: 'coa', name: 'coa' },
                { data: 'kelompok', name: 'kelompok' },
                { data: 'kategori', name: 'kategori.kategori' },
                { data: 'hd', name: 'hd'},                
                { data: 'debet', name: 'debet', class: 'text-right'},
                { data: 'kredit', name: 'kredit', class: 'text-right'},
                { data: 'keterangan', name: 'keterangan'},                
                { data: 'action', name: 'action'},
            ],

            "createdRow": function( row, data, dataIndex){                
                if( data['hd'] ==  'H'){
                    $(row).css('font-weight', 'bold');                    
                }
            }

        });
    }
    
    function tampil_data_assets(){
        let i = 1;	
        return $('#assets').DataTable({
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
            ajax   : `{{route('akuntansi01.master.coa_show')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'kode' },
                { data: 'coa', name: 'coa' },
                { data: 'kelompok', name: 'kelompok' },
                { data: 'kategori', name: 'kategori.kategori' },
                { data: 'hd', name: 'hd'},                
                { data: 'debet', name: 'debet', class: 'text-right'},
                { data: 'kredit', name: 'kredit', class: 'text-right'},
                { data: 'keterangan', name: 'keterangan'},                
                
                { data: 'action', name: 'action'},
                ],

                "createdRow": function( row, data, dataIndex){                
                    if( data['hd'] ==  'H'){
                        $(row).css('font-weight', 'bold');                    
                    }
                }
        });
    }

    function tampil_data_liability(){
        let i = 1;	
        return $('#liability').DataTable({
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
            ajax   : `{{route('akuntansi01.master.coa_show')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'kode' },
                { data: 'coa', name: 'coa' },
                { data: 'kelompok', name: 'kelompok' },
                { data: 'kategori', name: 'kategori.kategori' },
                { data: 'hd', name: 'hd'},                
                { data: 'debet', name: 'debet', class: 'text-right'},
                { data: 'kredit', name: 'kredit', class: 'text-right'},
                { data: 'keterangan', name: 'keterangan'},                
                
                { data: 'action', name: 'action'},
                ],

                "createdRow": function( row, data, dataIndex){                
                    if( data['hd'] ==  'H'){
                        $(row).css('font-weight', 'bold');                    
                    }
                }
        });
    }

    function tampil_data_equity(){
        let i = 1;	
        return $('#equity').DataTable({
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
            ajax   : `{{route('akuntansi01.master.coa_show')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'kode' },
                { data: 'coa', name: 'coa' },
                { data: 'kelompok', name: 'kelompok' },
                { data: 'kategori', name: 'kategori.kategori' },
                { data: 'hd', name: 'hd'},                
                { data: 'debet', name: 'debet', class: 'text-right'},
                { data: 'kredit', name: 'kredit', class: 'text-right'},
                { data: 'keterangan', name: 'keterangan'},                
                
                { data: 'action', name: 'action'},
                ],

                "createdRow": function( row, data, dataIndex){                
                    if( data['hd'] ==  'H'){
                        $(row).css('font-weight', 'bold');                    
                    }
                }
        });
    }

    function tampil_data_income(){
        let i = 1;	
        return $('#income').DataTable({
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
            ajax   : `{{route('akuntansi01.master.coa_show')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'kode' },
                { data: 'coa', name: 'coa' },
                { data: 'kelompok', name: 'kelompok' },
                { data: 'kategori', name: 'kategori.kategori' },
                { data: 'hd', name: 'hd'},                
                { data: 'debet', name: 'debet', class: 'text-right'},
                { data: 'kredit', name: 'kredit', class: 'text-right'},
                { data: 'keterangan', name: 'keterangan'},                
                
                { data: 'action', name: 'action'},
                ],

                "createdRow": function( row, data, dataIndex){                
                    if( data['hd'] ==  'H'){
                        $(row).css('font-weight', 'bold');                    
                    }
                }
        });
    }

    function tampil_data_costofsales(){
        let i = 1;	
        return $('#costofsales').DataTable({
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
            ajax   : `{{route('akuntansi01.master.coa_show')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'kode' },
                { data: 'coa', name: 'coa' },
                { data: 'kelompok', name: 'kelompok' },
                { data: 'kategori', name: 'kategori.kategori' },
                { data: 'hd', name: 'hd'},                
                { data: 'debet', name: 'debet', class: 'text-right'},
                { data: 'kredit', name: 'kredit', class: 'text-right'},
                { data: 'keterangan', name: 'keterangan'},                
                
                { data: 'action', name: 'action'},
                ],

                "createdRow": function( row, data, dataIndex){                
                    if( data['hd'] ==  'H'){
                        $(row).css('font-weight', 'bold');                    
                    }
                }
        });
    }

    function tampil_data_expense(){
        let i = 1;	
        return $('#expense').DataTable({
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
            ajax   : `{{route('akuntansi01.master.coa_show')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'kode' },
                { data: 'coa', name: 'coa' },
                { data: 'kelompok', name: 'kelompok' },
                { data: 'kategori', name: 'kategori.kategori' },
                { data: 'hd', name: 'hd'},                
                { data: 'debet', name: 'debet', class: 'text-right'},
                { data: 'kredit', name: 'kredit', class: 'text-right'},
                { data: 'keterangan', name: 'keterangan'},                
                
                { data: 'action', name: 'action'},
                ],

                "createdRow": function( row, data, dataIndex){                
                    if( data['hd'] ==  'H'){
                        $(row).css('font-weight', 'bold');                    
                    }
                }
        });
    }

    function tampil_data_otherincome(){
        let i = 1;	
        return $('#otherincome').DataTable({
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
            ajax   : `{{route('akuntansi01.master.coa_show')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'kode' },
                { data: 'coa', name: 'coa' },
                { data: 'kelompok', name: 'kelompok' },
                { data: 'kategori', name: 'kategori.kategori' },
                { data: 'hd', name: 'hd'},                
                { data: 'debet', name: 'debet', class: 'text-right'},
                { data: 'kredit', name: 'kredit', class: 'text-right'},
                { data: 'keterangan', name: 'keterangan'},                
                
                { data: 'action', name: 'action'},
                ],

                "createdRow": function( row, data, dataIndex){                
                    if( data['hd'] ==  'H'){
                        $(row).css('font-weight', 'bold');                    
                    }
                }
        });
    }

    function tampil_data_otherexpense(){
        let i = 1;	
        return $('#otherexpense').DataTable({
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
            ajax   : `{{route('akuntansi01.master.coa_show')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'kode' },
                { data: 'coa', name: 'coa' },
                { data: 'kelompok', name: 'kelompok' },
                { data: 'kategori', name: 'kategori.kategori' },
                { data: 'hd', name: 'hd'},                
                { data: 'debet', name: 'debet', class: 'text-right'},
                { data: 'kredit', name: 'kredit', class: 'text-right'},
                { data: 'keterangan', name: 'keterangan'},                
                
                { data: 'action', name: 'action'},
                ],

                "createdRow": function( row, data, dataIndex){                
                    if( data['hd'] ==  'H'){
                        $(row).css('font-weight', 'bold');                    
                    }
                }
        });
    }

    function tampil_dataTable(){ 
        var x = $('#idtab1').val();
        if(x=='0'){
            allDatatable.draw(null, false);
        }else if(x=='1'){
            assetsDatatable.draw(null, false);
        }else if(x=='2'){
            liabilityDatatable.draw(null, false);
        }else if(x=='3'){
            equityDatatable.draw(null, false);
        }else if(x=='4'){
            incomeDatatable.draw(null, false);
        }else if(x=='5'){
            costofsalesDatatable.draw(null, false);
        }else if(x=='6'){
            expenseDatatable.draw(null, false);                
        }else if(x=='8'){
            otherincomeDatatable.draw(null, false);
        }else {
            otherexpenseDatatable.draw(null, false);                
        }

    }
    

    $('#golongan1xh').on('change',function(){
        $('#golongan1').val("H");
    });

    $('#golongan1xd').on('change',function(){
        $('#golongan1').val("D");
    });

    $('#btn_download1').on('click',function(){        
        // var gbr3=$('#logo').val();
        var data3 = 'form-excel/import_databuku.xlsx';
        var url3="{{ asset('storage/') }}/"+data3;
        var file3='import_databuku.xlsx';
        download(url3, file3);
    });

    function btn_baru_click(){      
        $('#bodyAdd :input').prop('disabled', false);
        document.getElementById("btn_simpan").style.display='block';        
        document.getElementById("btn_baru").style.display='none';
    }
    
    function btn_simpan_click(){      
        $('#bodyAdd :input').prop('disabled', true);
        document.getElementById("btn_simpan").style.display='none';        
        document.getElementById("btn_baru").style.display='block';        
        swaltambah($('#coa1').val());
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

        var gbrx="<h6 class='mt-2' align='center'>Belum ada image yang diupload<h6>";                    
        document.getElementById("gambarimage1").innerHTML=gbrx;
       
    }); 

    function data_simpan(){
        var id1=parseFloat($('#id1').val());			        
        var golongan1=$('#golongan1').val();
        var idkelompok1=$('#idkelompok1').val();
        var idkategori1=$('#idkategori1').val();
        var kode1=$('#kode1').val();
        var coa1=$('#coa1').val();
        var keterangan1=$('#keterangan1').val();
        
        let formData = new FormData();
            formData.append('id1', id1);
            formData.append('golongan1', golongan1);
            formData.append('idkelompok1', idkelompok1);
            formData.append('idkategori1', idkategori1);
            formData.append('kode1', kode1);
            formData.append('coa1', coa1);            
            formData.append('keterangan1', keterangan1);
          
        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('akuntansi01.master.coa_create')}}',
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
                swalgagaltambah(coa1);                 
                }
        });
        
    }   

    $("#btn_simpan").on('click',function(){
        data_simpan();	
    });

    $('#show_all').on('click','.item_edit',function(){        
        var idkelompok = $(this).attr('data5');        
        var debet = formatAngka($(this).attr('data6'),'');
        var kredit = formatAngka($(this).attr('data7'),'');
        $('#data3g').val(debet);
        $('#data3h').val(kredit);
        $('#idkelompok1').val(idkelompok);        
        var id1 = $(this).attr('data');
        item_edit_click(id1);       
    });

    $('#show_assets').on('click','.item_edit',function(){        
        var idkelompok = $(this).attr('data5');
        var debet = formatAngka($(this).attr('data6'),'');
        var kredit = formatAngka($(this).attr('data7'),'');
        $('#data3g').val(debet);
        $('#data3h').val(kredit);
        $('#idkelompok1').val(idkelompok);        
        var id1 = $(this).attr('data');
        item_edit_click(id1);       
    });

    $('#show_liability').on('click','.item_edit',function(){        
        var idkelompok = $(this).attr('data5');
        var debet = formatAngka($(this).attr('data6'),'');
        var kredit = formatAngka($(this).attr('data7'),'');
        $('#data3g').val(debet);
        $('#data3h').val(kredit);
        $('#idkelompok1').val(idkelompok);        
        var id1 = $(this).attr('data');
        item_edit_click(id1);       
    });

    $('#show_equity').on('click','.item_edit',function(){        
        var idkelompok = $(this).attr('data5');
        var debet = formatAngka($(this).attr('data6'),'');
        var kredit = formatAngka($(this).attr('data7'),'');
        $('#data3g').val(debet);
        $('#data3h').val(kredit);
        $('#idkelompok1').val(idkelompok);        
        var id1 = $(this).attr('data');
        item_edit_click(id1);       
    });

    $('#show_income').on('click','.item_edit',function(){        
        var idkelompok = $(this).attr('data5');
        var debet = formatAngka($(this).attr('data6'),'');
        var kredit = formatAngka($(this).attr('data7'),'');
        $('#data3g').val(debet);
        $('#data3h').val(kredit);
        $('#idkelompok1').val(idkelompok);        
        var id1 = $(this).attr('data');
        item_edit_click(id1);       
    });

    $('#show_costofsales').on('click','.item_edit',function(){        
        var idkelompok = $(this).attr('data5');
        var debet = formatAngka($(this).attr('data6'),'');
        var kredit = formatAngka($(this).attr('data7'),'');
        $('#data3g').val(debet);
        $('#data3h').val(kredit);
        $('#idkelompok1').val(idkelompok);        
        var id1 = $(this).attr('data');
        item_edit_click(id1);       
    });
    $('#show_expense').on('click','.item_edit',function(){        
        var idkelompok = $(this).attr('data5');
        var debet = formatAngka($(this).attr('data6'),'');
        var kredit = formatAngka($(this).attr('data7'),'');
        $('#data3g').val(debet);
        $('#data3h').val(kredit);
        $('#idkelompok1').val(idkelompok);        
        var id1 = $(this).attr('data');
        item_edit_click(id1);       
    });
    $('#show_otherincome').on('click','.item_edit',function(){        
        var idkelompok = $(this).attr('data5');
        var debet = formatAngka($(this).attr('data6'),'');
        var kredit = formatAngka($(this).attr('data7'),'');
        $('#data3g').val(debet);
        $('#data3h').val(kredit);
        $('#idkelompok1').val(idkelompok);        
        var id1 = $(this).attr('data');
        item_edit_click(id1);       
    });
    $('#show_otherexpense').on('click','.item_edit',function(){        
        var idkelompok = $(this).attr('data5');
        var debet = formatAngka($(this).attr('data6'),'');
        var kredit = formatAngka($(this).attr('data7'),'');        
        $('#data3g').val(debet);
        $('#data3h').val(kredit);
        $('#idkelompok1').val(idkelompok);        
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
		        url   : `{{ url('akuntansi01/master/coaedit')}}/${id1}`,
		        async : false,
		        dataType : 'json',	
				
		        success : function(data){
                    var i;                
                    var resultData = data.data;	                			
                    for(i=0; i<resultData.length; i++){

                        $('#id1').val(resultData[i].id);

                        $('#golongan1').val(resultData[i].hd);
                        if ($('[name="golongan1"]').val()=='H'){
							document.getElementById("golongan1xh").checked = true
						}else{
							document.getElementById("golongan1xd").checked = true
						}

                        $('#kode1').val(resultData[i].kode);
                        var x = resultData[i].idkategori;
                        setTimeout(() => {
                            $.ajax({
                                type: 'get',
                                url   : '{{route('akuntansi01.master.coa_listkategori2')}}',
                                
                                success: function(data){				    
                                    $("#idkategori1").html(data);
                                    $("#idkategori1").val(x);
                                }
                            })    
                        }, 500);
                                                                      
                        $('#debet1').val($('#data3g').val()); 
                        $('#kredit1').val($('#data3h').val()); 
                        $('#coa1').val(resultData[i].coa); 
                        $('#keterangan1').val(resultData[i].keterangan); 
                    }
		        },
                error : function(data){
                    alert(id1);
                }
		    }); 
    }
        
    $('#show_all').on('click','.item_hapus',function(){
        var id3=$(this).attr('data');
        var data3b=$(this).attr('data2');
        var data3c=$(this).attr('data3');
        var data3d=$(this).attr('data4');
        item_hapus_click(id3,data3b,data3c,data3d);
    });
    $('#show_assets').on('click','.item_hapus',function(){
        var id3=$(this).attr('data');
        var data3b=$(this).attr('data2');
        var data3c=$(this).attr('data3');
        var data3d=$(this).attr('data4');
        item_hapus_click(id3,data3b,data3c,data3d);
    });
    $('#show_liability').on('click','.item_hapus',function(){
        var id3=$(this).attr('data');
        var data3b=$(this).attr('data2');
        var data3c=$(this).attr('data3');
        var data3d=$(this).attr('data4');
        item_hapus_click(id3,data3b,data3c,data3d);
    });
    $('#show_income').on('click','.item_hapus',function(){
        var id3=$(this).attr('data');
        var data3b=$(this).attr('data2');
        var data3c=$(this).attr('data3');
        var data3d=$(this).attr('data4');
        item_hapus_click(id3,data3b,data3c,data3d);
    });
    $('#show_costofsales').on('click','.item_hapus',function(){
        var id3=$(this).attr('data');
        var data3b=$(this).attr('data2');
        var data3c=$(this).attr('data3');
        var data3d=$(this).attr('data4');
        item_hapus_click(id3,data3b,data3c,data3d);
    });
    $('#show_expense').on('click','.item_hapus',function(){
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
            url   : '{{url('akuntansi01/master/coadestroy')}}/'+id3,
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