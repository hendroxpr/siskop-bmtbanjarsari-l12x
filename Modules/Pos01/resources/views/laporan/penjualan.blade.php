@extends('admin.layouts.main')

@section('contents')

@php
    $idruang = session('idruang1');
    if($idruang==''){
        $idruang = '-1';
    }    
    $idjenispembayaran = session('idjenispembayaran1');
    if($idjenispembayaran==''){
        $idjenispembayaran = '-1';
    }    

    $idcustomer = session('idcustomer1');
    if($idcustomer==''){
        $idcustomer = '-1';
    }   
    
    $idoperator = session('idoperator1');
    if($idoperator==''){
        $idoperator = '-1';
    } 
   
    $tabpenjualanutama = session('tabpenjualanutama1');
    $tabpenjualansub = session('tabpenjualansub1');
    $tgltransaksi1 = session('tgltransaksi1');   
    if($tgltransaksi1==''){
        $tgltransaksi1=session('memtanggal');;  
    }    
    $tgltransaksi2 = session('tgltransaksi2');   
    if($tgltransaksi2==''){
        $tgltransaksi2=session('memtanggal');;  
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
                    <div class="col-md-7">
                        <select name="idruang1" id="idruang1" class="w3-input w3-border" value="{{ $idruang }}"></select>
                        <input name="tabpenjualanutama1" id="tabpenjualanutama1" class="" type="hidden" value="{{ $tabpenjualanutama }}"> 
                        <input name="tabpenjualansub1" id="tabpenjualansub1" class="" type="hidden" value="{{ $tabpenjualansub }}"> 
                        <input name="event1" id="event1" class="" type="hidden" value="0"> 
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-3 text-right">
                        <h6 class="mt-2">Periode Tanggal</h6>
                    </div>
                    <div class="col-md-3" style="padding-right: 0px;">
                        <input name="tgltransaksi1" id="tgltransaksi1" class="w3-input w3-border" maxlength="10" type="text" placeholder="Tanggal awal" autocomplete="off" value="{{ $tgltransaksi1 }}">                       
                    </div>
                    <div class="col-md-1 text-center" style="padding-right: 0px; padding-left: 0px;">
                        <h6 class="mt-2">s/d</h6>
                    </div>
                    <div class="col-md-3" style="padding-left: 0px;">
                        <input name="tgltransaksi2" id="tgltransaksi2" class="w3-input w3-border" maxlength="10" type="text" placeholder="Tanggal akhir" autocomplete="off" value="{{ $tgltransaksi2 }}">                       
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-3" align="right">									
                        <h6 class="mt-2">Jenis Pembayaran</h6>
                    </div>
                    <div class="col-md-7 input-group">
                        <select  name="idjenispembayaran1" id="idjenispembayaran1" class=" form-control w3-input w3-border" style="border-radius:0px; border:none; display:block;" value="{{ $idjenispembayaran }}"></select>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-3" align="right">									
                        <h6 class="mt-2">Customer</h6>
                    </div>
                    <div class="col-md-7 input-group">
                        <select  name="idcustomer1" id="idcustomer1" class=" form-control w3-input w3-border" style="border-radius:0px; border:none; display:block;" value="{{ $idcustomer }}"></select>
                        <div class="input-group-append">
                            <button id="btn_caricustomer1" name="btn_caricustomer1" type="button" style="border-radius:0px; border:none;"><i style="font-size:24" class="fas">&#xf002;</i></button>
                        </div>
                        <select  name="idcustomerx1" id="idcustomerx1" class="" style="border-radius:0px; border:none; display:none;"></select>
                        <input name="cek1" id="cek1" class="" type="hidden">                                
                        <input name="id1" id="id1" class="" type="hidden"> 
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-3" align="right">									
                        <h6 class="mt-2">Operator</h6>
                    </div>
                    <div class="col-md-7 input-group">
                        <select  name="idoperator1" id="idoperator1" class=" form-control w3-input w3-border" style="border-radius:0px; border:none; display:block;" value="{{ $idoperator }}"></select>
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
                </div> 
            </div>
        </div>

    </div>

    <ul class="nav nav-tabs" id="tab-utama" role="tablist">
        <li class="nav-item">
            <a class="nav-link" id="tab-upenjualansaja" data-toggle="pill" href="#isi-tab-upenjualansaja" role="tab" aria-controls="tab-upenjualansaja" aria-selected="true">Penjualan</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-upenjualanfifo" data-toggle="pill" href="#isi-tab-upenjualanfifo" role="tab" aria-controls="tab-upenjualanfifo" aria-selected="false">Penjualan FIFO</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-upenjualanmova" data-toggle="pill" href="#isi-tab-upenjualanmova" role="tab" aria-controls="tab-upenjualanmova" aria-selected="false">Penjualan Moving Average</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-upenjualanlifo" data-toggle="pill" href="#isi-tab-upenjualanlifo" role="tab" aria-controls="tab-upenjualanlifo" aria-selected="false">Penjualan LIFO</a>
        </li>               
        <li class="nav-item">
            <a class="nav-link" id="tab-upenjualanlain" data-toggle="pill" href="#isi-tab-upenjualanlain" role="tab" aria-controls="tab-upenjualanlain" aria-selected="false">Penjualan Lain</a>
        </li>              
    </ul>

    

    <!--awal tabel-->        
    <div class="box-body" id="headerjudul" style="display: block;">
         <div class="tab-content mt-3" id="tab-utama-tabContent">
            <!--tab-upenjualansaja -->
            <div class="tab-pane fade" id="isi-tab-upenjualansaja" role="tabpanel" aria-labelledby="tab-upenjualansaja">
                
                <ul class="nav nav-tabs" id="tab-penjualansaja" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualansajadetail" data-toggle="pill" href="#isi-tab-penjualansajadetail" role="tab" aria-controls="tab-penjualansajadetail" aria-selected="true">Penjualan Detail</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualansajaperitem" data-toggle="pill" href="#isi-tab-penjualansajaperitem" role="tab" aria-controls="tab-penjualansajaperitem" aria-selected="false">Penjualan Per Item</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualansajapercustomer" data-toggle="pill" href="#isi-tab-penjualansajapercustomer" role="tab" aria-controls="tab-penjualansajapercustomer" aria-selected="false">Penjualan Per Customer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualansajaperfaktur" data-toggle="pill" href="#isi-tab-penjualansajaperfaktur" role="tab" aria-controls="tab-penjualansajaperfaktur" aria-selected="false">Penjualan Per Faktur</a>
                    </li>               
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualansajaperjenispembayaran" data-toggle="pill" href="#isi-tab-penjualansajaperjenispembayaran" role="tab" aria-controls="tab-penjualansajaperjenispembayaran" aria-selected="false">Penjualan Per Jenis Pembayaran</a>
                    </li>               
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualansajapertanggal" data-toggle="pill" href="#isi-tab-penjualansajapertanggal" role="tab" aria-controls="tab-penjualansajapertanggal" aria-selected="false">Penjualan Per Tanggal</a>
                    </li>               
                </ul>
                <div class="tab-content mt-3" id="tab-penjualansaja-tabContent">
                    
                    <!--tab-penjualansajadetail -->
                    <div class="tab-pane fade" id="isi-tab-penjualansajadetail" role="tabpanel" aria-labelledby="tab-penjualansajadetail">
                        <div id="reload" class="table-responsive">
                            <table id="penjualansajadetail1" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:10px">Faktur</th>
                                        <th style="width:10px">Tanggal</th>
                                        <th style="width:10px">Kode</th>
                                        <th style="width:10px">Barcode</th>							
                                        <th style="width:200px">Nama Barang</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">Satuan</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>				
                                        <th style="width:20px">HJS</th>							
                                        <th style="width:20px">Sub Total HJ</th>							
                                        <th style="width:20px">PPN Jual</th>							
                                        <th style="width:20px">Diskon Jual</th>							
                                        <th style="width:20px">Total HJ</th>							
                                        <th style="width:20px">Laba</th>							
                                        <th style="width:20px">Jenis</br>Pembayaran</th>							
                                        <th style="width:50px">Customer</th>							
                                        <th style="width:100px">Lokasi</th>							
                                        <th style="width:50px">Operator</th>							
                                        <th style="width:50px">Keterangan</th>						
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualansajadetail1">
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
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                <tbody id="show_penjualansajadetail1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualansajadetail -->

                    <!--tab-penjualansajaperitem -->
                    <div class="tab-pane fade" id="isi-tab-penjualansajaperitem" role="tabpanel" aria-labelledby="tab-penjualansajaperitem">
                        <div id="reload" class="table-responsive">
                            <table id="penjualansajaperitem1" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:10px">Kode</th>
                                        <th style="width:10px">Barcode</th>							
                                        <th style="width:200px">Nama Barang</th>							
                                        <th style="width:10px">Jml Record</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">Satuan</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>						
                                        <th style="width:20px">HJS</th>						
                                        <th style="width:20px">Sub Total Hj</th>						
                                        <th style="width:20px">PPN Jual</th>						
                                        <th style="width:20px">Diskon Jual</th>						
                                        <th style="width:20px">Total HJ</th>						
                                        <th style="width:20px">Laba</th>						
                                    
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualansajaperitem1">
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
                                <tbody id="show_penjualansajaperitem1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualansajaperitem -->

                    <!--tab-penjualansajapercustomer -->
                    <div class="tab-pane fade" id="isi-tab-penjualansajapercustomer" role="tabpanel" aria-labelledby="tab-penjualansajapercustomer">
                        <div id="reload" class="table-responsive">
                            <table id="penjualansajapercustomer1" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:10px">Kode</th>
                                        <th style="width:200px">Customer</th>							
                                        <th style="width:200px">Alamat</th>							
                                        <th style="width:10px">Jml record</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>							
                                        <th style="width:20px">HJS</th>							
                                        <th style="width:20px">Sub Total HJ</th>							
                                        <th style="width:20px">PPN Jual</th>							
                                        <th style="width:20px">Diskon Jual</th>							
                                        <th style="width:20px">Total HJ</th>							
                                        <th style="width:20px">Laba</th>							
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualansajapercustomer1">
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
                                <tbody id="show_penjualansajapercustomer1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualansajapercustomer -->

                    <!--tab-penjualansajaperfaktur -->
                    <div class="tab-pane fade" id="isi-tab-penjualansajaperfaktur" role="tabpanel" aria-labelledby="tab-penjualansajaperfaktur">
                        <div id="reload" class="table-responsive">
                            <table id="penjualansajaperfaktur1" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:10px">Faktur</th>
                                        <th style="width:200px">Tanggal</th>							
                                        <th style="width:10px">Jml Record</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>							
                                        <th style="width:20px">HJS</th>							
                                        <th style="width:20px">Sub Total HJ</th>							
                                        <th style="width:20px">PPN Jual</th>							
                                        <th style="width:20px">Diskon Jual</th>							
                                        <th style="width:20px">Total HJ</th>							
                                        <th style="width:20px">Laba</th>							
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualansajaperfaktur1">
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
                                    </tr>
                                </tfoot>
                                <tbody id="show_penjualansajaperfaktur1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualansajaperfaktur -->

                    <!--tab-penjualansajaperjenispembayaran -->
                    <div class="tab-pane fade" id="isi-tab-penjualansajaperjenispembayaran" role="tabpanel" aria-labelledby="tab-penjualansajaperjenispembayaran">
                        <div id="reload" class="table-responsive">
                            <table id="penjualansajaperjenispembayaran1" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:10px">Kode</th>
                                        <th style="width:200px">Jenis Pembayaran</th>							
                                        <th style="width:10px">Jml Record</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>							
                                        <th style="width:20px">HJS</th>							
                                        <th style="width:20px">Sub Total HJ</th>							
                                        <th style="width:20px">PPN Jual</th>							
                                        <th style="width:20px">Diskon Jual</th>							
                                        <th style="width:20px">Total HJ</th>							
                                        <th style="width:20px">Laba</th>							
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualansajaperjenispembayaran1">
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
                                    </tr>
                                </tfoot>
                                <tbody id="show_penjualansajaperjenispembayaran1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualansajaperjenispembayaran -->

                    <!--tab-penjualansajapertanggal -->
                    <div class="tab-pane fade" id="isi-tab-penjualansajapertanggal" role="tabpanel" aria-labelledby="tab-penjualansajapertanggal">
                        <div id="reload" class="table-responsive">
                            <table id="penjualansajapertanggal1" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:20px">Tanggal</th>							
                                        <th style="width:10px">Jml Record</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>							
                                        <th style="width:20px">HJS</th>							
                                        <th style="width:20px">Sub Total HJ</th>							
                                        <th style="width:20px">PPN Jual</th>							
                                        <th style="width:20px">Diskon Jual</th>							
                                        <th style="width:20px">Total HJ</th>							
                                        <th style="width:20px">Laba</th>							
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualansajapertanggal1">
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
                                    </tr>
                                </tfoot>
                                <tbody id="show_penjualansajapertanggal1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualansajapertanggal -->
                </div>

            </div>
            <!--/tab-upenjualansaja -->

            <!--tab-upenjualanfifo -->
            <div class="tab-pane fade" id="isi-tab-upenjualanfifo" role="tabpanel" aria-labelledby="tab-upenjualanfifo">
                
                <ul class="nav nav-tabs" id="tab-penjualanfifo" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualanfifodetail" data-toggle="pill" href="#isi-tab-penjualanfifodetail" role="tab" aria-controls="tab-penjualanfifodetail" aria-selected="true">Penjualan Detail (FIFO)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualanfifoperitem" data-toggle="pill" href="#isi-tab-penjualanfifoperitem" role="tab" aria-controls="tab-penjualanfifoperitem" aria-selected="false">Penjualan Per Item  (FIFO)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualanfifopercustomer" data-toggle="pill" href="#isi-tab-penjualanfifopercustomer" role="tab" aria-controls="tab-penjualanfifopercustomer" aria-selected="false">Penjualan Per Customer (FIFO)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualanfifoperfaktur" data-toggle="pill" href="#isi-tab-penjualanfifoperfaktur" role="tab" aria-controls="tab-penjualanfifoperfaktur" aria-selected="false">Penjualan Per Faktur (FIFO)</a>
                    </li>               
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualanfifoperjenispembayaran" data-toggle="pill" href="#isi-tab-penjualanfifoperjenispembayaran" role="tab" aria-controls="tab-penjualanfifoperjenispembayaran" aria-selected="false">Penjualan Per Jenis Pembayaran (FIFO)</a>
                    </li>               
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualanfifopertanggal" data-toggle="pill" href="#isi-tab-penjualanfifopertanggal" role="tab" aria-controls="tab-penjualanfifopertanggal" aria-selected="false">Penjualan Per Tanggal (FIFO)</a>
                    </li>               
                </ul>
                <div class="tab-content mt-3" id="tab-penjualanfifo-tabContent">
                    
                    <!--tab-penjualanfifodetail -->
                    <div class="tab-pane fade" id="isi-tab-penjualanfifodetail" role="tabpanel" aria-labelledby="tab-penjualanfifodetail">
                        <div id="reload" class="table-responsive">
                            <table id="penjualanfifodetail1" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:10px">Faktur</th>
                                        <th style="width:10px">Tanggal</th>
                                        <th style="width:10px">Kode</th>
                                        <th style="width:10px">Barcode</th>							
                                        <th style="width:200px">Nama Barang</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">Satuan</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>				
                                        <th style="width:20px">HJS</th>							
                                        <th style="width:20px">Sub Total HJ</th>							
                                        <th style="width:20px">PPN Jual</th>							
                                        <th style="width:20px">Diskon Jual</th>							
                                        <th style="width:20px">Total HJ</th>							
                                        <th style="width:20px">Laba</th>							
                                        <th style="width:20px">Jenis</br>Pembayaran</th>							
                                        <th style="width:50px">Customer</th>							
                                        <th style="width:100px">Lokasi</th>							
                                        <th style="width:50px">Operator</th>							
                                        <th style="width:50px">Keterangan</th>						
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualanfifodetail1">
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
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                <tbody id="show_penjualanfifodetail1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualanfifodetail -->

                    <!--tab-penjualanfifoperitem -->
                    <div class="tab-pane fade" id="isi-tab-penjualanfifoperitem" role="tabpanel" aria-labelledby="tab-penjualanfifoperitem">
                        <div id="reload" class="table-responsive">
                            <table id="penjualanfifoperitem1" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:10px">Kode</th>
                                        <th style="width:10px">Barcode</th>							
                                        <th style="width:200px">Nama Barang</th>							
                                        <th style="width:10px">Jml Record</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">Satuan</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>						
                                        <th style="width:20px">HJS</th>						
                                        <th style="width:20px">Sub Total Hj</th>						
                                        <th style="width:20px">PPN Jual</th>						
                                        <th style="width:20px">Diskon Jual</th>						
                                        <th style="width:20px">Total HJ</th>						
                                        <th style="width:20px">Laba</th>						
                                    
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualanfifoperitem1">
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
                                <tbody id="show_penjualanfifoperitem1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualanfifoperitem -->

                    <!--tab-penjualanfifopercustomer -->
                    <div class="tab-pane fade" id="isi-tab-penjualanfifopercustomer" role="tabpanel" aria-labelledby="tab-penjualanfifopercustomer">
                        <div id="reload" class="table-responsive">
                            <table id="penjualanfifopercustomer1" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:10px">Kode</th>
                                        <th style="width:200px">Customer</th>							
                                        <th style="width:200px">Alamat</th>							
                                        <th style="width:10px">Jml record</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>							
                                        <th style="width:20px">HJS</th>							
                                        <th style="width:20px">Sub Total HJ</th>							
                                        <th style="width:20px">PPN Jual</th>							
                                        <th style="width:20px">Diskon Jual</th>							
                                        <th style="width:20px">Total HJ</th>							
                                        <th style="width:20px">Laba</th>							
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualanfifopercustomer1">
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
                                <tbody id="show_penjualanfifopercustomer1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualanfifopercustomer -->

                    <!--tab-penjualanfifoperfaktur -->
                    <div class="tab-pane fade" id="isi-tab-penjualanfifoperfaktur" role="tabpanel" aria-labelledby="tab-penjualanfifoperfaktur">
                        <div id="reload" class="table-responsive">
                            <table id="penjualanfifoperfaktur1" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:10px">Faktur</th>
                                        <th style="width:200px">Tanggal</th>							
                                        <th style="width:10px">Jml Record</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>							
                                        <th style="width:20px">HJS</th>							
                                        <th style="width:20px">Sub Total HJ</th>							
                                        <th style="width:20px">PPN Jual</th>							
                                        <th style="width:20px">Diskon Jual</th>							
                                        <th style="width:20px">Total HJ</th>							
                                        <th style="width:20px">Laba</th>							
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualanfifoperfaktur1">
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
                                    </tr>
                                </tfoot>
                                <tbody id="show_penjualanfifoperfaktur1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualanfifoperfaktur -->

                    <!--tab-penjualanfifoperjenispembayaran -->
                    <div class="tab-pane fade" id="isi-tab-penjualanfifoperjenispembayaran" role="tabpanel" aria-labelledby="tab-penjualanfifoperjenispembayaran">
                        <div id="reload" class="table-responsive">
                            <table id="penjualanfifoperjenispembayaran1" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:10px">Kode</th>
                                        <th style="width:200px">Jenis Pembayaran</th>							
                                        <th style="width:10px">Jml Record</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>							
                                        <th style="width:20px">HJS</th>							
                                        <th style="width:20px">Sub Total HJ</th>							
                                        <th style="width:20px">PPN Jual</th>							
                                        <th style="width:20px">Diskon Jual</th>							
                                        <th style="width:20px">Total HJ</th>							
                                        <th style="width:20px">Laba</th>							
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualanfifoperjenispembayaran1">
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
                                    </tr>
                                </tfoot>
                                <tbody id="show_penjualanfifoperjenispembayaran1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualanfifoperjenispembayaran -->

                    <!--tab-penjualanfifopertanggal -->
                    <div class="tab-pane fade" id="isi-tab-penjualanfifopertanggal" role="tabpanel" aria-labelledby="tab-penjualanfifopertanggal">
                        <div id="reload" class="table-responsive">
                            <table id="penjualanfifopertanggal1" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:20px">Tanggal</th>							
                                        <th style="width:10px">Jml Record</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>							
                                        <th style="width:20px">HJS</th>							
                                        <th style="width:20px">Sub Total HJ</th>							
                                        <th style="width:20px">PPN Jual</th>							
                                        <th style="width:20px">Diskon Jual</th>							
                                        <th style="width:20px">Total HJ</th>							
                                        <th style="width:20px">Laba</th>							
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualanfifopertanggal1">
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
                                    </tr>
                                </tfoot>
                                <tbody id="show_penjualanfifopertanggal1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualanfifopertanggal -->
                </div>

            </div>
            <!--/tab-upenjualanfifo -->
            
            <!--tab-upenjualanmova -->
            <div class="tab-pane fade" id="isi-tab-upenjualanmova" role="tabpanel" aria-labelledby="tab-upenjualanmova">
                
                <ul class="nav nav-tabs" id="tab-penjualanmova" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualanmovadetail" data-toggle="pill" href="#isi-tab-penjualanmovadetail" role="tab" aria-controls="tab-penjualanmovadetail" aria-selected="true">Penjualan Detail (Moving Average)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualanmovaperitem" data-toggle="pill" href="#isi-tab-penjualanmovaperitem" role="tab" aria-controls="tab-penjualanmovaperitem" aria-selected="false">Penjualan Per Item  (Moving Average)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualanmovapercustomer" data-toggle="pill" href="#isi-tab-penjualanmovapercustomer" role="tab" aria-controls="tab-penjualanmovapercustomer" aria-selected="false">Penjualan Per Customer (Moving Average)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualanmovaperfaktur" data-toggle="pill" href="#isi-tab-penjualanmovaperfaktur" role="tab" aria-controls="tab-penjualanmovaperfaktur" aria-selected="false">Penjualan Per Faktur (Moving Average)</a>
                    </li>               
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualanmovaperjenispembayaran" data-toggle="pill" href="#isi-tab-penjualanmovaperjenispembayaran" role="tab" aria-controls="tab-penjualanmovaperjenispembayaran" aria-selected="false">Penjualan Per Jenis Pembayaran (Moving Average)</a>
                    </li>               
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualanmovapertanggal" data-toggle="pill" href="#isi-tab-penjualanmovapertanggal" role="tab" aria-controls="tab-penjualanmovapertanggal" aria-selected="false">Penjualan Per Tanggal (Moving Average)</a>
                    </li>               
                </ul>
                <div class="tab-content mt-3" id="tab-penjualanmova-tabContent">
                    
                    <!--tab-penjualanmovadetail -->
                    <div class="tab-pane fade" id="isi-tab-penjualanmovadetail" role="tabpanel" aria-labelledby="tab-penjualanmovadetail">
                        <div id="reload" class="table-responsive">
                            <table id="penjualanmovadetail1" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:10px">Faktur</th>
                                        <th style="width:10px">Tanggal</th>
                                        <th style="width:10px">Kode</th>
                                        <th style="width:10px">Barcode</th>							
                                        <th style="width:200px">Nama Barang</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">Satuan</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>				
                                        <th style="width:20px">HJS</th>							
                                        <th style="width:20px">Sub Total HJ</th>							
                                        <th style="width:20px">PPN Jual</th>							
                                        <th style="width:20px">Diskon Jual</th>							
                                        <th style="width:20px">Total HJ</th>							
                                        <th style="width:20px">Laba</th>							
                                        <th style="width:20px">Jenis</br>Pembayaran</th>							
                                        <th style="width:50px">Customer</th>							
                                        <th style="width:100px">Lokasi</th>							
                                        <th style="width:50px">Operator</th>							
                                        <th style="width:50px">Keterangan</th>						
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualanmovadetail1">
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
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                <tbody id="show_penjualanmovadetail1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualanmovadetail -->

                    <!--tab-penjualanmovaperitem -->
                    <div class="tab-pane fade" id="isi-tab-penjualanmovaperitem" role="tabpanel" aria-labelledby="tab-penjualanmovaperitem">
                        <div id="reload" class="table-responsive">
                            <table id="penjualanmovaperitem1" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:10px">Kode</th>
                                        <th style="width:10px">Barcode</th>							
                                        <th style="width:200px">Nama Barang</th>							
                                        <th style="width:10px">Jml Record</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">Satuan</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>						
                                        <th style="width:20px">HJS</th>						
                                        <th style="width:20px">Sub Total Hj</th>						
                                        <th style="width:20px">PPN Jual</th>						
                                        <th style="width:20px">Diskon Jual</th>						
                                        <th style="width:20px">Total HJ</th>						
                                        <th style="width:20px">Laba</th>						
                                    
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualanmovaperitem1">
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
                                <tbody id="show_penjualanmovaperitem1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualanmovaperitem -->

                    <!--tab-penjualanmovapercustomer -->
                    <div class="tab-pane fade" id="isi-tab-penjualanmovapercustomer" role="tabpanel" aria-labelledby="tab-penjualanmovapercustomer">
                        <div id="reload" class="table-responsive">
                            <table id="penjualanmovapercustomer1" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:10px">Kode</th>
                                        <th style="width:200px">Customer</th>							
                                        <th style="width:200px">Alamat</th>							
                                        <th style="width:10px">Jml record</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>							
                                        <th style="width:20px">HJS</th>							
                                        <th style="width:20px">Sub Total HJ</th>							
                                        <th style="width:20px">PPN Jual</th>							
                                        <th style="width:20px">Diskon Jual</th>							
                                        <th style="width:20px">Total HJ</th>							
                                        <th style="width:20px">Laba</th>							
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualanmovapercustomer1">
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
                                <tbody id="show_penjualanmovapercustomer1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualanmovapercustomer -->

                    <!--tab-penjualanmovaperfaktur -->
                    <div class="tab-pane fade" id="isi-tab-penjualanmovaperfaktur" role="tabpanel" aria-labelledby="tab-penjualanmovaperfaktur">
                        <div id="reload" class="table-responsive">
                            <table id="penjualanmovaperfaktur1" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:10px">Faktur</th>
                                        <th style="width:200px">Tanggal</th>							
                                        <th style="width:10px">Jml Record</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>							
                                        <th style="width:20px">HJS</th>							
                                        <th style="width:20px">Sub Total HJ</th>							
                                        <th style="width:20px">PPN Jual</th>							
                                        <th style="width:20px">Diskon Jual</th>							
                                        <th style="width:20px">Total HJ</th>							
                                        <th style="width:20px">Laba</th>							
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualanmovaperfaktur1">
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
                                    </tr>
                                </tfoot>
                                <tbody id="show_penjualanmovaperfaktur1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualanmovaperfaktur -->

                    <!--tab-penjualanmovaperjenispembayaran -->
                    <div class="tab-pane fade" id="isi-tab-penjualanmovaperjenispembayaran" role="tabpanel" aria-labelledby="tab-penjualanmovaperjenispembayaran">
                        <div id="reload" class="table-responsive">
                            <table id="penjualanmovaperjenispembayaran1" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:10px">Kode</th>
                                        <th style="width:200px">Jenis Pembayaran</th>							
                                        <th style="width:10px">Jml Record</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>							
                                        <th style="width:20px">HJS</th>							
                                        <th style="width:20px">Sub Total HJ</th>							
                                        <th style="width:20px">PPN Jual</th>							
                                        <th style="width:20px">Diskon Jual</th>							
                                        <th style="width:20px">Total HJ</th>							
                                        <th style="width:20px">Laba</th>							
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualanmovaperjenispembayaran1">
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
                                    </tr>
                                </tfoot>
                                <tbody id="show_penjualanmovaperjenispembayaran1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualanmovaperjenispembayaran -->

                    <!--tab-penjualanmovapertanggal -->
                    <div class="tab-pane fade" id="isi-tab-penjualanmovapertanggal" role="tabpanel" aria-labelledby="tab-penjualanmovapertanggal">
                        <div id="reload" class="table-responsive">
                            <table id="penjualanmovapertanggal1" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:20px">Tanggal</th>							
                                        <th style="width:10px">Jml Record</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>							
                                        <th style="width:20px">HJS</th>							
                                        <th style="width:20px">Sub Total HJ</th>							
                                        <th style="width:20px">PPN Jual</th>							
                                        <th style="width:20px">Diskon Jual</th>							
                                        <th style="width:20px">Total HJ</th>							
                                        <th style="width:20px">Laba</th>							
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualanmovapertanggal1">
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
                                    </tr>
                                </tfoot>
                                <tbody id="show_penjualanmovapertanggal1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualanmovapertanggal -->
                </div>

            </div>
            <!--/tab-upenjualanmova -->
            
            <!--tab-upenjualanlifo -->
            <div class="tab-pane fade" id="isi-tab-upenjualanlifo" role="tabpanel" aria-labelledby="tab-upenjualanlifo">
                
                <ul class="nav nav-tabs" id="tab-penjualanlifo" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualanlifodetail" data-toggle="pill" href="#isi-tab-penjualanlifodetail" role="tab" aria-controls="tab-penjualanlifodetail" aria-selected="true">Penjualan Detail (LIFO)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualanlifoperitem" data-toggle="pill" href="#isi-tab-penjualanlifoperitem" role="tab" aria-controls="tab-penjualanlifoperitem" aria-selected="false">Penjualan Per Item  (LIFO)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualanlifopercustomer" data-toggle="pill" href="#isi-tab-penjualanlifopercustomer" role="tab" aria-controls="tab-penjualanlifopercustomer" aria-selected="false">Penjualan Per Customer (LIFO)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualanlifoperfaktur" data-toggle="pill" href="#isi-tab-penjualanlifoperfaktur" role="tab" aria-controls="tab-penjualanlifoperfaktur" aria-selected="false">Penjualan Per Faktur (LIFO)</a>
                    </li>               
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualanlifoperjenispembayaran" data-toggle="pill" href="#isi-tab-penjualanlifoperjenispembayaran" role="tab" aria-controls="tab-penjualanlifoperjenispembayaran" aria-selected="false">Penjualan Per Jenis Pembayaran (LIFO)</a>
                    </li>               
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualanlifopertanggal" data-toggle="pill" href="#isi-tab-penjualanlifopertanggal" role="tab" aria-controls="tab-penjualanlifopertanggal" aria-selected="false">Penjualan Per Tanggal (LIFO)</a>
                    </li>               
                </ul>
                <div class="tab-content mt-3" id="tab-penjualanlifo-tabContent">
                    
                    <!--tab-penjualanlifodetail -->
                    <div class="tab-pane fade" id="isi-tab-penjualanlifodetail" role="tabpanel" aria-labelledby="tab-penjualanlifodetail">
                        <div id="reload" class="table-responsive">
                            <table id="penjualanlifodetail1" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:10px">Faktur</th>
                                        <th style="width:10px">Tanggal</th>
                                        <th style="width:10px">Kode</th>
                                        <th style="width:10px">Barcode</th>							
                                        <th style="width:200px">Nama Barang</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">Satuan</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>				
                                        <th style="width:20px">HJS</th>							
                                        <th style="width:20px">Sub Total HJ</th>							
                                        <th style="width:20px">PPN Jual</th>							
                                        <th style="width:20px">Diskon Jual</th>							
                                        <th style="width:20px">Total HJ</th>							
                                        <th style="width:20px">Laba</th>							
                                        <th style="width:20px">Jenis</br>Pembayaran</th>							
                                        <th style="width:50px">Customer</th>							
                                        <th style="width:100px">Lokasi</th>							
                                        <th style="width:50px">Operator</th>							
                                        <th style="width:50px">Keterangan</th>						
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualanlifodetail1">
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
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                <tbody id="show_penjualanlifodetail1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualanlifodetail -->

                    <!--tab-penjualanlifoperitem -->
                    <div class="tab-pane fade" id="isi-tab-penjualanlifoperitem" role="tabpanel" aria-labelledby="tab-penjualanlifoperitem">
                        <div id="reload" class="table-responsive">
                            <table id="penjualanlifoperitem1" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:10px">Kode</th>
                                        <th style="width:10px">Barcode</th>							
                                        <th style="width:200px">Nama Barang</th>							
                                        <th style="width:10px">Jml Record</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">Satuan</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>						
                                        <th style="width:20px">HJS</th>						
                                        <th style="width:20px">Sub Total Hj</th>						
                                        <th style="width:20px">PPN Jual</th>						
                                        <th style="width:20px">Diskon Jual</th>						
                                        <th style="width:20px">Total HJ</th>						
                                        <th style="width:20px">Laba</th>						
                                    
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualanlifoperitem1">
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
                                <tbody id="show_penjualanlifoperitem1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualanlifoperitem -->

                    <!--tab-penjualanlifopercustomer -->
                    <div class="tab-pane fade" id="isi-tab-penjualanlifopercustomer" role="tabpanel" aria-labelledby="tab-penjualanlifopercustomer">
                        <div id="reload" class="table-responsive">
                            <table id="penjualanlifopercustomer1" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:10px">Kode</th>
                                        <th style="width:200px">Customer</th>							
                                        <th style="width:200px">Alamat</th>							
                                        <th style="width:10px">Jml record</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>							
                                        <th style="width:20px">HJS</th>							
                                        <th style="width:20px">Sub Total HJ</th>							
                                        <th style="width:20px">PPN Jual</th>							
                                        <th style="width:20px">Diskon Jual</th>							
                                        <th style="width:20px">Total HJ</th>							
                                        <th style="width:20px">Laba</th>							
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualanlifopercustomer1">
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
                                <tbody id="show_penjualanlifopercustomer1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualanlifopercustomer -->

                    <!--tab-penjualanlifoperfaktur -->
                    <div class="tab-pane fade" id="isi-tab-penjualanlifoperfaktur" role="tabpanel" aria-labelledby="tab-penjualanlifoperfaktur">
                        <div id="reload" class="table-responsive">
                            <table id="penjualanlifoperfaktur1" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:10px">Faktur</th>
                                        <th style="width:200px">Tanggal</th>							
                                        <th style="width:10px">Jml Record</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>							
                                        <th style="width:20px">HJS</th>							
                                        <th style="width:20px">Sub Total HJ</th>							
                                        <th style="width:20px">PPN Jual</th>							
                                        <th style="width:20px">Diskon Jual</th>							
                                        <th style="width:20px">Total HJ</th>							
                                        <th style="width:20px">Laba</th>							
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualanlifoperfaktur1">
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
                                    </tr>
                                </tfoot>
                                <tbody id="show_penjualanlifoperfaktur1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualanlifoperfaktur -->

                    <!--tab-penjualanlifoperjenispembayaran -->
                    <div class="tab-pane fade" id="isi-tab-penjualanlifoperjenispembayaran" role="tabpanel" aria-labelledby="tab-penjualanlifoperjenispembayaran">
                        <div id="reload" class="table-responsive">
                            <table id="penjualanlifoperjenispembayaran1" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:10px">Kode</th>
                                        <th style="width:200px">Jenis Pembayaran</th>							
                                        <th style="width:10px">Jml Record</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>							
                                        <th style="width:20px">HJS</th>							
                                        <th style="width:20px">Sub Total HJ</th>							
                                        <th style="width:20px">PPN Jual</th>							
                                        <th style="width:20px">Diskon Jual</th>							
                                        <th style="width:20px">Total HJ</th>							
                                        <th style="width:20px">Laba</th>							
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualanlifoperjenispembayaran1">
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
                                    </tr>
                                </tfoot>
                                <tbody id="show_penjualanlifoperjenispembayaran1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualanlifoperjenispembayaran -->

                    <!--tab-penjualanlifopertanggal -->
                    <div class="tab-pane fade" id="isi-tab-penjualanlifopertanggal" role="tabpanel" aria-labelledby="tab-penjualanlifopertanggal">
                        <div id="reload" class="table-responsive">
                            <table id="penjualanlifopertanggal1" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:20px">Tanggal</th>							
                                        <th style="width:10px">Jml Record</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>							
                                        <th style="width:20px">HJS</th>							
                                        <th style="width:20px">Sub Total HJ</th>							
                                        <th style="width:20px">PPN Jual</th>							
                                        <th style="width:20px">Diskon Jual</th>							
                                        <th style="width:20px">Total HJ</th>							
                                        <th style="width:20px">Laba</th>							
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualanlifopertanggal1">
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
                                    </tr>
                                </tfoot>
                                <tbody id="show_penjualanlifopertanggal1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualanlifopertanggal -->
                </div>

            </div>
            <!--/tab-upenjualanlilo -->
            
            <!--tab-upenjualanlain -->
            <div class="tab-pane fade" id="isi-tab-upenjualanlain" role="tabpanel" aria-labelledby="tab-upenjualanlain">
                
                <ul class="nav nav-tabs" id="tab-penjualanlain" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualanlaindetail" data-toggle="pill" href="#isi-tab-penjualanlaindetail" role="tab" aria-controls="tab-penjualanlaindetail" aria-selected="true">Penjualan Detail (Lain)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualanlainperitem" data-toggle="pill" href="#isi-tab-penjualanlainperitem" role="tab" aria-controls="tab-penjualanlainperitem" aria-selected="false">Penjualan Per Item  (Lain)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualanlainpercustomer" data-toggle="pill" href="#isi-tab-penjualanlainpercustomer" role="tab" aria-controls="tab-penjualanlainpercustomer" aria-selected="false">Penjualan Per Customer (Lain)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualanlainperfaktur" data-toggle="pill" href="#isi-tab-penjualanlainperfaktur" role="tab" aria-controls="tab-penjualanlainperfaktur" aria-selected="false">Penjualan Per Faktur (Lain)</a>
                    </li>               
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualanlainperjenispembayaran" data-toggle="pill" href="#isi-tab-penjualanlainperjenispembayaran" role="tab" aria-controls="tab-penjualanlainperjenispembayaran" aria-selected="false">Penjualan Per Jenis Pembayaran (lain)</a>
                    </li>               
                    <li class="nav-item">
                        <a class="nav-link" id="tab-penjualanlainpertanggal" data-toggle="pill" href="#isi-tab-penjualanlainpertanggal" role="tab" aria-controls="tab-penjualanlainpertanggal" aria-selected="false">Penjualan Per Tanggal (Lain)</a>
                    </li>               
                </ul>
                <div class="tab-content mt-3" id="tab-penjualanlain-tabContent">
                    
                    <!--tab-penjualanlaindetail -->
                    <div class="tab-pane fade" id="isi-tab-penjualanlaindetail" role="tabpanel" aria-labelledby="tab-penjualanlaindetail">
                        <div id="reload" class="table-responsive">
                            <table id="penjualanlaindetail1x" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:10px">Faktur</th>
                                        <th style="width:10px">Tanggal</th>
                                        <th style="width:10px">Kode</th>
                                        <th style="width:10px">Barcode</th>							
                                        <th style="width:200px">Nama Barang</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">Satuan</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>				
                                        <th style="width:20px">HJS</th>							
                                        <th style="width:20px">Sub Total HJ</th>							
                                        <th style="width:20px">PPN Jual</th>							
                                        <th style="width:20px">Diskon Jual</th>							
                                        <th style="width:20px">Total HJ</th>							
                                        <th style="width:20px">Laba</th>							
                                        <th style="width:20px">Jenis</br>Pembayaran</th>							
                                        <th style="width:50px">Customer</th>							
                                        <th style="width:100px">Lokasi</th>							
                                        <th style="width:50px">Operator</th>							
                                        <th style="width:50px">Keterangan</th>						
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualanlaindetail1">
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
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                <tbody id="show_penjualanlaindetail1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualanlaindetail -->

                    <!--tab-penjualanlainperitem -->
                    <div class="tab-pane fade" id="isi-tab-penjualanlainperitem" role="tabpanel" aria-labelledby="tab-penjualanlainperitem">
                        <div id="reload" class="table-responsive">
                            <table id="penjualanlainperitem1x" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:10px">Kode</th>
                                        <th style="width:10px">Barcode</th>							
                                        <th style="width:200px">Nama Barang</th>							
                                        <th style="width:10px">Jml Record</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">Satuan</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>						
                                        <th style="width:20px">HJS</th>						
                                        <th style="width:20px">Sub Total Hj</th>						
                                        <th style="width:20px">PPN Jual</th>						
                                        <th style="width:20px">Diskon Jual</th>						
                                        <th style="width:20px">Total HJ</th>						
                                        <th style="width:20px">Laba</th>						
                                    
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualanlainperitem1">
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
                                <tbody id="show_penjualanlainperitem1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualanlainperitem -->

                    <!--tab-penjualanlainpercustomer -->
                    <div class="tab-pane fade" id="isi-tab-penjualanlainpercustomer" role="tabpanel" aria-labelledby="tab-penjualanlainpercustomer">
                        <div id="reload" class="table-responsive">
                            <table id="penjualanlainpercustomer1x" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:10px">Kode</th>
                                        <th style="width:200px">Customer</th>							
                                        <th style="width:200px">Alamat</th>							
                                        <th style="width:10px">Jml record</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>							
                                        <th style="width:20px">HJS</th>							
                                        <th style="width:20px">Sub Total HJ</th>							
                                        <th style="width:20px">PPN Jual</th>							
                                        <th style="width:20px">Diskon Jual</th>							
                                        <th style="width:20px">Total HJ</th>							
                                        <th style="width:20px">Laba</th>							
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualanlainpercustomer1">
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
                                <tbody id="show_penjualanlainpercustomer1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualanlainpercustomer -->

                    <!--tab-penjualanlainperfaktur -->
                    <div class="tab-pane fade" id="isi-tab-penjualanlainperfaktur" role="tabpanel" aria-labelledby="tab-penjualanlainperfaktur">
                        <div id="reload" class="table-responsive">
                            <table id="penjualanlainperfaktur1x" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:10px">Faktur</th>
                                        <th style="width:200px">Tanggal</th>							
                                        <th style="width:10px">Jml Record</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>							
                                        <th style="width:20px">HJS</th>							
                                        <th style="width:20px">Sub Total HJ</th>							
                                        <th style="width:20px">PPN Jual</th>							
                                        <th style="width:20px">Diskon Jual</th>							
                                        <th style="width:20px">Total HJ</th>							
                                        <th style="width:20px">Laba</th>							
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualanlainperfaktur1">
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
                                    </tr>
                                </tfoot>
                                <tbody id="show_penjualanlainperfaktur1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualanlainperfaktur -->

                    <!--tab-penjualanlainperjenispembayaran -->
                    <div class="tab-pane fade" id="isi-tab-penjualanlainperjenispembayaran" role="tabpanel" aria-labelledby="tab-penjualanlainperjenispembayaran">
                        <div id="reload" class="table-responsive">
                            <table id="penjualanlainperjenispembayaran1x" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:10px">Kode</th>
                                        <th style="width:200px">Jenis Pembayaran</th>							
                                        <th style="width:10px">Jml Record</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>							
                                        <th style="width:20px">HJS</th>							
                                        <th style="width:20px">Sub Total HJ</th>							
                                        <th style="width:20px">PPN Jual</th>							
                                        <th style="width:20px">Diskon Jual</th>							
                                        <th style="width:20px">Total HJ</th>							
                                        <th style="width:20px">Laba</th>							
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualanlainperjenispembayaran1">
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
                                    </tr>
                                </tfoot>
                                <tbody id="show_penjualanlainperjenispembayaran1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualanlainperjenispembayaran -->

                    <!--tab-penjualanlainpertanggal -->
                    <div class="tab-pane fade" id="isi-tab-penjualanlainpertanggal" role="tabpanel" aria-labelledby="tab-penjualanlainpertanggal">
                        <div id="reload" class="table-responsive">
                            <table id="penjualanlainpertanggal1x" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:20px">Tanggal</th>							
                                        <th style="width:10px">Jml Record</th>							
                                        <th style="width:10px">Qty</th>							
                                        <th style="width:20px">HBS</th>							
                                        <th style="width:20px">Total HB</th>							
                                        <th style="width:20px">HJS</th>							
                                        <th style="width:20px">Sub Total HJ</th>							
                                        <th style="width:20px">PPN Jual</th>							
                                        <th style="width:20px">Diskon Jual</th>							
                                        <th style="width:20px">Total HJ</th>							
                                        <th style="width:20px">Laba</th>							
                                    </tr>
                                </thead>
                                <tfoot id="show_footerpenjualanlainpertanggal1">
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
                                    </tr>
                                </tfoot>
                                <tbody id="show_penjualanlainpertanggal1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualanlainpertanggal -->
                </div>

            </div>
            <!--/tab-upenjualanlain -->
         </div>
    </div>    
<!--akhir tabel-->

    <!-- ModalCariCustomer modal fade-->
	<div class="modal fade" id="ModalCariCustomer"  data-backdrop="static">
		<div class="modal-dialog modal-xl">  <!-- modal-(sm, lg, xl) ukuran lebar modal -->
			<div class="modal-content bg-info w3-animate-zoom">
				
				<div class="modal-header">
						<h3 class="modal-title"><i style="font-size:18" class="fas">&#xf002;</i><b> Cari Data</b></h3>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
						
				</div>
				<form class="form-horizontal">
                    @csrf
					<div class="modal-body">
												
						<div class="row">
							<div id="reload" class="table-responsive">
								<table id="customer" class="table table-bordered table-striped table-hover" style="width: 100%;">
                                    <thead>
                                        <tr style="align-content: center">
                                            <th style="width:10px;">#</th>
                                            <th style="width:50px">Ecard</th>
                                            <th style="width:50px">NIA</th>
                                            <th style="width:50px">KTP</th>
                                            <th style="width:200px">Nama</th>
                                            <th style="width:100px">Lembaga</th>
                                            <th style="width:50px">Telp</th>
                                        </tr>
                                    </thead>
                                    <tfoot id="show_footercustomer">
                                        
                                    </tfoot>
                                    <tbody id="show_customer">
                                    
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
	<!-- end ModalCariCustomer -->

    <!-- khusus menyimpan data yang akan dihapus -->
    <input name="id3" id="id3"type="hidden">	
    <input name="data3a" id="data3a"type="hidden">	
    <input name="data3b" id="data3b"type="hidden">	
    <input name="data3c" id="data3c"type="hidden">	

</div>


<script type="text/javascript">
    var penjualansajadetail1Datatable;
    var penjualansajaperitem1Datatable;
    var penjualansajapercustomer1Datatable;
    var penjualansajaperfaktur1Datatable;
    var penjualansajaperjenispembayaran1Datatable;
    var penjualansajapertanggal1Datatable;

    var penjualanfifodetail1Datatable;
    var penjualanfifoperitem1Datatable;
    var penjualanfifopercustomer1Datatable;
    var penjualanfifoperfaktur1Datatable;
    var penjualanfifoperjenispembayaran1Datatable;
    var penjualanfifopertanggal1Datatable;

    var penjualanmovadetail1Datatable;
    var penjualanmovaperitem1Datatable;
    var penjualanmovapercustomer1Datatable;
    var penjualanmovaperfaktur1Datatable;
    var penjualanmovaperjenispembayaran1Datatable;
    var penjualanmovapertanggal1Datatable;

    var penjualanlifodetail1Datatable;
    var penjualanlifoperitem1Datatable;
    var penjualanlifopercustomer1Datatable;
    var penjualanlifoperfaktur1Datatable;
    var penjualanlifoperjenispembayaran1Datatable;
    var penjualanlifopertanggal1Datatable;

    var penjualanlaindetail1Datatable;
    var penjualanlainperitem1Datatable;
    var penjualanlainpercustomer1Datatable;
    var penjualanlainperfaktur1Datatable;
    var penjualanlainperjenispembayaran1Datatable;
    var penjualanlainpertanggal1Datatable;

    var listcustomerDatatable;
    var listjenispembayaranDatatable;
    var caricustomerDatatable;
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

    //menampilkan combo jenispembayaran
    // tampil_listjenispembayaran();
    function tampil_listjenispembayaran(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.laporan.penjualan_listjenispembayaran')}}',
            
            success: function(data){				    
                $("#idjenispembayaran1").html(data); 
                $("#idjenispembayaran1").val({{ $idjenispembayaran }});               
            }
        })                    
    }
    //menampilkan combo customer
    // tampil_listcustomer();
    function tampil_listcustomer(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.laporan.penjualan_listcustomer')}}',
            
            success: function(data){				    
                $("#idcustomer1").html(data);
                $("#idcustomer1").val({{ $idcustomer }});                
            }
        })                    
    }

    // tampil_listoperator();
    function tampil_listoperator(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.laporan.penjualan_listoperator')}}',
            
            success: function(data){				    
                $("#idoperator1").html(data); 
                $("#idoperator1").val({{ $idoperator }});               
            }
        })                    
    }
    
    setTimeout(() => {
        if($('#tabpenjualanutama1').val()=='tab-upenjualansaja'){
            $('#tab-upenjualansaja').click();          
        }else if($('#tabpenjualanutama1').val()=='tab-upenjualanfifo'){
            $('#tab-upenjualanfifo').click();            
        }else if($('#tabpenjualanutama1').val()=='tab-upenjualanmova'){
            $('#tab-upenjualanmova').click();            
        }else if($('#tabpenjualanutama1').val()=='tab-upenjualanlifo'){
            $('#tab-upenjualanlifo').click();            
        }else if($('#tabpenjualanutama1').val()=='tab-upenjualanlain'){
            $('#tab-upenjualanlain').click();            
        }else{
            $('#tab-upenjualansaja').click();            
        }
        
        if($('#tabpenjualansub1').val()=='tab-penjualansajadetail'){
            $('#tab-penjualansajadetail').click();          
        }else if($('#tabpenjualansub1').val()=='tab-penjualansajaperitem'){
            $('#tab-penjualansajaperitem').click();            
        }else if($('#tabpenjualansub1').val()=='tab-penjualansajapercustomer'){
            $('#tab-penjualansajapercustomer').click();            
        }else if($('#tabpenjualansub1').val()=='tab-penjualansajaperfaktur'){
            $('#tab-penjualansajaperfaktur').click();            
        }else if($('#tabpenjualansub1').val()=='tab-penjualansajaperjenispembayaran'){
            $('#tab-penjualansajaperjenispembayaran').click();            
        }else if($('#tabpenjualansub1').val()=='tab-penjualansajapertanggal'){
            $('#tab-penjualansajapertanggal').click();            
        }else{
            $('#tab-penjualansajadetail').click();            
        }

        if($('#tabpenjualansub1').val()=='tab-penjualanfifodetail'){
            $('#tab-penjualanfifodetail').click();          
        }else if($('#tabpenjualansub1').val()=='tab-penjualanfifoperitem'){
            $('#tab-penjualanfifoperitem').click();            
        }else if($('#tabpenjualansub1').val()=='tab-penjualanfifopercustomer'){
            $('#tab-penjualanfifopercustomer').click();            
        }else if($('#tabpenjualansub1').val()=='tab-penjualanfifoperfaktur'){
            $('#tab-penjualanfifoperfaktur').click();            
        }else if($('#tabpenjualansub1').val()=='tab-penjualanfifoperjenispembayaran'){
            $('#tab-penjualanfifoperjenispembayaran').click();            
        }else if($('#tabpenjualansub1').val()=='tab-penjualanfifopertanggal'){
            $('#tab-penjualanfifopertanggal').click();            
        }else{
            $('#tab-penjualanfifodetail').click();            
        }

        if($('#tabpenjualansub1').val()=='tab-penjualanmovadetail'){
            $('#tab-penjualanmovadetail').click();          
        }else if($('#tabpenjualansub1').val()=='tab-penjualanmovaperitem'){
            $('#tab-penjualanmovaperitem').click();            
        }else if($('#tabpenjualansub1').val()=='tab-penjualanmovapercustomer'){
            $('#tab-penjualanmovapercustomer').click();            
        }else if($('#tabpenjualansub1').val()=='tab-penjualanmovaperfaktur'){
            $('#tab-penjualanmovaperfaktur').click();            
        }else if($('#tabpenjualansub1').val()=='tab-penjualanmovaperjenispembayaran'){
            $('#tab-penjualanmovaperjenispembayaran').click();            
        }else if($('#tabpenjualansub1').val()=='tab-penjualanmovapertanggal'){
            $('#tab-penjualanmovapertanggal').click();            
        }else{
            $('#tab-penjualanmovadetail').click();            
        }

        if($('#tabpenjualansub1').val()=='tab-penjualanlifodetail'){
            $('#tab-penjualanlifodetail').click();          
        }else if($('#tabpenjualansub1').val()=='tab-penjualanlifoperitem'){
            $('#tab-penjualanlifoperitem').click();            
        }else if($('#tabpenjualansub1').val()=='tab-penjualanlifopercustomer'){
            $('#tab-penjualanlifopercustomer').click();            
        }else if($('#tabpenjualansub1').val()=='tab-penjualanlifoperfaktur'){
            $('#tab-penjualanlifoperfaktur').click();            
        }else if($('#tabpenjualansub1').val()=='tab-penjualanlifoperjenispembayaran'){
            $('#tab-penjualanlifoperjenispembayaran').click();            
        }else if($('#tabpenjualansub1').val()=='tab-penjualanlifopertanggal'){
            $('#tab-penjualanlifopertanggal').click();            
        }else{
            $('#tab-penjualanlifodetail').click();            
        }

        if($('#tabpenjualansub1').val()=='tab-penjualanlaindetail'){
            $('#tab-penjualanlaindetail').click();          
        }else if($('#tabpenjualansub1').val()=='tab-penjualanlainperitem'){
            $('#tab-penjualanlainperitem').click();            
        }else if($('#tabpenjualansub1').val()=='tab-penjualanlainpercustomer'){
            $('#tab-penjualanlainpercustomer').click();            
        }else if($('#tabpenjualansub1').val()=='tab-penjualanlainperfaktur'){
            $('#tab-penjualanlainperfaktur').click();            
        }else if($('#tabpenjualansub1').val()=='tab-penjualanlainperjenispembayaran'){
            $('#tab-penjualanlainperjenispembayaran').click();            
        }else if($('#tabpenjualansub1').val()=='tab-penjualanlainpertanggal'){
            $('#tab-penjualanlainpertanggal').click();            
        }else{
            $('#tab-penjualanlaindetail').click();            
        }
    }, 100);
    
    $('#tab-penjualansajadetail').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualansajadetail');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-penjualansajaperitem').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualansajaperitem');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-penjualansajapercustomer').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualansajapercustomer');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-penjualansajaperfaktur').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualansajaperfaktur');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-penjualansajaperjenispembayaran').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualansajaperjenispembayaran');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-penjualansajapertanggal').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualansajapertanggal');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });

    $('#tab-penjualanfifodetail').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualanfifodetail');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-penjualanfifoperitem').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualanfifoperitem');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-penjualanfifopercustomer').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualanfifopercustomer');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-penjualanfifoperfaktur').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualanfifoperfaktur');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-penjualanfifoperjenispembayaran').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualanfifoperjenispembayaran');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-penjualanfifopertanggal').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualanfifopertanggal');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });

    $('#tab-penjualanmovadetail').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualanmovadetail');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-penjualanmovaperitem').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualanmovaperitem');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-penjualanmovapercustomer').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualanmovapercustomer');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-penjualanmovaperfaktur').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualanmovaperfaktur');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-penjualanmovaperjenispembayaran').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualanmovaperjenispembayaran');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-penjualanmovapertanggal').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualanmovapertanggal');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });

    $('#tab-penjualanlifodetail').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualanlifodetail');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-penjualanlifoperitem').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualanlifoperitem');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-penjualanlifopercustomer').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualanlifopercustomer');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-penjualanlifoperfaktur').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualanlifoperfaktur');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-penjualanlifoperjenispembayaran').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualanlifoperjenispembayaran');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-penjualanlifopertanggal').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualanlifopertanggal');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });

    $('#tab-penjualanlaindetail').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualanlaindetail');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-penjualanlainperitem').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualanlainperitem');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-penjualanlainpercustomer').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualanlainpercustomer');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-penjualanlainperfaktur').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualanlainperfaktur');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-penjualanlainperjenispembayaran').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualanlainperjenispembayaran');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
    $('#tab-penjualanlainpertanggal').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualanlainpertanggal');
        $('#event1').val('0');
        setTimeout(() => {
            kirimsyarat();	
        }, 100);
    });
        
    //menampilkan combo ruang
    setTimeout(() => {
        tampil_listruang();
        tampil_listcustomer();
        tampil_listjenispembayaran();
        tampil_listoperator();
              
    }, 100);

    koneksi_datatable();

    $('#idruang1').on('change',function(){
        $('#event1').val('1');
        setTimeout(() => {
            kirimsyarat();
        }, 500);
    });

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
    
    $('#tgltransaksi1').on('click',function(){
        $('#event1').val('1');       
        setTimeout(() => {
            kirimsyarat();           
        }, 500);     				
    });
    
    $('#tgltransaksi1').on('change',function(){	
        $('#event1').val('1');			
        setTimeout(() => {
            kirimsyarat();
        }, 500);					
    });
    $('#tgltransaksi2').on('click',function(){  
        $('#event1').val('1');     
        setTimeout(() => {
            kirimsyarat();           
        }, 100);     				
    });
    
    $('#tgltransaksi2').on('change',function(){	
        $('#event1').val('1');			
        setTimeout(() => {
            kirimsyarat();
        }, 100);					
    });
    $('#idcustomer1').on('change',function(){	
        $('#event1').val('1');	
        setTimeout(() => {
            kirimsyarat();
        }, 100);					
    });

    $('#idjenispembayaran1').on('change',function(){	
        $('#event1').val('1');	
        setTimeout(() => {
            kirimsyarat();
        }, 100);					
    });
    
    $('#idoperator1').on('change',function(){	
        $('#event1').val('1');	
        setTimeout(() => {
            kirimsyarat();
        }, 100);					
    });
    
    function kirimsyarat(){
        var event1=$('#event1').val();
        
        var idruang1=$('#idruang1').val();
        var tabpenjualanutama1=$('#tabpenjualanutama1').val();
        var tabpenjualansub1=$('#tabpenjualansub1').val();
        var tgltransaksi1=$('#tgltransaksi1').val();
        var tgltransaksi2=$('#tgltransaksi2').val();
        var idcustomer1=$('#idcustomer1').val();
        var idoperator1=$('#idoperator1').val();
        var idjenispembayaran1=$('#idjenispembayaran1').val();
        
        let formData = new FormData();
            formData.append('idruang1', idruang1);
            formData.append('tabpenjualanutama1', tabpenjualanutama1);
            formData.append('tabpenjualansub1', tabpenjualansub1);
            formData.append('tgltransaksi1', tgltransaksi1);
            formData.append('tgltransaksi2', tgltransaksi2);
            formData.append('idcustomer1', idcustomer1);
            formData.append('idoperator1', idoperator1);
            formData.append('idjenispembayaran1', idjenispembayaran1);

        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pos01.laporan.penjualan_kirimsyarat')}}',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },				 				
            success : function(formData){ 
                    if(event1=='1'){
                        $("#idruang1").val(idruang1);                                        
                        $("#idjenispembayaran1").val(idjenispembayaran1);                                        
                        $("#idcustomer1").val(idcustomer1);                                        
                        tampil_dataTable();                   
                    }
                }
        });
    } 
 
    //menampilkan combo ruang
    function tampil_listruang(){				
        $.ajax({
            type: 'get',
            url   : '{{route('pos01.laporan.penjualan_listruang')}}',
            
            success: function(data){				    
                $("#idruang1").html(data);
                $("#idruang1").val({{ $idruang }});

            }
        })                    
    }

    function tampil_penjualansajadetail1(){
        let i = 1;	
        return $('#penjualansajadetail1').DataTable({
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
            totalhb = api
                .column(9)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            subtotalhj = api
                .column(11)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            ppnjual = api
                .column(12)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            diskonjual = api
                .column(13)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            totalhj = api
                .column(14)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            laba = api
                .column(15)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Total over this page
            pagetotalhb = api
                .column(9, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagesubtotalhj = api
                .column(11, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pageppnjual = api
                .column(12, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagediskonjual = api
                .column(13, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagetotalhj = api
                .column(14, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagelaba = api
                .column(15, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(1).footer().innerHTML = 'SUB TOTAL :';
            api.column(9).footer().innerHTML = formatAngka(pagetotalhb,'');            
            api.column(11).footer().innerHTML = formatAngka(pagesubtotalhj,'');            
            api.column(12).footer().innerHTML = formatAngka(pageppnjual,'');            
            api.column(13).footer().innerHTML = formatAngka(pagediskonjual,'');            
            api.column(14).footer().innerHTML = formatAngka(pagetotalhj,'');            
            api.column(15).footer().innerHTML = formatAngka(pagelaba,'');            
        },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualansajadetail')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nomorstatus', name: 'nomorstatus', className: 'dt-center' },
                { data: 'tanggal', name: 'created_at', className: 'dt-center' },
                { data: 'kode', name: 'barang.code', className: 'dt-center' },
                { data: 'barcode', name: 'barang.barcode', className: 'dt-center' },
                { data: 'nabara', name: 'barang.nabara', className: 'dt-left' },
                { data: 'keluar', name: 'keluar', className: 'dt-center' },
                { data: 'satuan', name: 'barang.satuan.kode', className: 'dt-center' },
                { data: 'hbskeluar', name: 'hbskeluar', className: 'dt-right' },
                { data: 'hppkeluar', name: 'hppkeluar', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'hppj', name: 'hppj', className: 'dt-right' },
                { data: 'ppnkeluar', name: 'ppnkeluar', className: 'dt-right' },
                { data: 'diskonkeluar', name: 'diskonkeluar', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
                { data: 'jenispembayaran', name: 'jenispembayaran.jenispembayaran', className: 'dt-left' },
                { data: 'customer', name: 'anggota.nama', className: 'dt-left' },
                { data: 'ruang', name: 'ruang.ruang', className: 'dt-left' },
                { data: 'users', name: 'users.name', className: 'dt-left' },
                { data: 'keterangan', name: 'keterangan', className: 'dt-left' },
                
                
            ]
        });
    }

    function tampil_penjualansajaperitem1(){
        let i = 1;	
        return $('#penjualansajaperitem1').DataTable({
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
                totalhb = api
                    .column(8)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                subtotalhj = api
                    .column(10)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                ppnjual = api
                    .column(11)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                diskonjual = api
                    .column(12)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                totalhj = api
                    .column(13)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                laba = api
                    .column(14)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Total over this page
                pagetotalhb = api
                    .column(8, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagesubtotalhj = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pageppnjual = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagediskonjual = api
                    .column(12, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagetotalhj = api
                    .column(13, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagelaba = api
                    .column(14, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Update footer
                api.column(1).footer().innerHTML = 'SUB TOTAL :';
                api.column(8).footer().innerHTML = formatAngka(pagetotalhb,'');  
                api.column(10).footer().innerHTML = formatAngka(pagesubtotalhj,'');  
                api.column(11).footer().innerHTML = formatAngka(pageppnjual,'');  
                api.column(12).footer().innerHTML = formatAngka(pagediskonjual,'');  
                api.column(13).footer().innerHTML = formatAngka(pagetotalhj,'');  
                api.column(14).footer().innerHTML = formatAngka(pagelaba,'');  

            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualansajaperitem')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'barang.code', className: 'dt-center' },
                { data: 'barcode', name: 'barang.barcode', className: 'dt-center' },
                { data: 'nabara', name: 'barang.nabara', className: 'dt-left' },
                { data: 'jmlrecord', name: 'jmlrecord', className: 'dt-center' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'satuan', name: 'barang.satuan.kode', className: 'dt-center' },
                { data: 'hbs', name: 'hbs', className: 'dt-right' },
                { data: 'totalhb', name: 'totalhb', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'subtotalhj', name: 'subtotalhj', className: 'dt-right' },
                { data: 'ppnjual', name: 'ppnjual', className: 'dt-right' },
                { data: 'diskonjual', name: 'diskonjual', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
            ]
        });
    }

    function tampil_penjualansajapercustomer1(){
        let i = 1;	
        return $('#penjualansajapercustomer1').DataTable({
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
                totalhb = api
                    .column(7)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                subtotalhj = api
                    .column(9)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                ppnjual = api
                    .column(10)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                diskonjual = api
                    .column(11)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                totalhj = api
                    .column(12)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                laba = api
                    .column(13)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Total over this page
                pagetotalhb = api
                    .column(7, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagesubtotalhj = api
                    .column(9, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pageppnjual = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagediskonjual = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagetotalhj = api
                    .column(12, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagelaba = api
                    .column(13, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Update footer
                api.column(1).footer().innerHTML = 'SUB TOTAL :';
                api.column(7).footer().innerHTML = formatAngka(pagetotalhb,'');  
                api.column(9).footer().innerHTML = formatAngka(pagesubtotalhj,'');  
                api.column(10).footer().innerHTML = formatAngka(pageppnjual,'');  
                api.column(11).footer().innerHTML = formatAngka(pagediskonjual,'');  
                api.column(12).footer().innerHTML = formatAngka(pagetotalhj,'');  
                api.column(13).footer().innerHTML = formatAngka(pagelaba,''); 

            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualansajapercustomer')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nia', name: 'anggota.nia', className: 'dt-center' },
                { data: 'nama', name: 'anggota.nama', className: 'dt-left' },
                { data: 'lembaga', name: 'anggota.lembaga.lembaga', className: 'dt-left' },
                { data: 'jmlrecord', name: 'jmlrecord', className: 'dt-center' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'hbs', name: 'hbs', className: 'dt-right' },
                { data: 'totalhb', name: 'totalhb', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'subtotalhj', name: 'subtotalhj', className: 'dt-right' },
                { data: 'ppnjual', name: 'ppnjual', className: 'dt-right' },
                { data: 'diskonjual', name: 'diskonjual', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
            ]
        });
    }

    function tampil_penjualansajaperfaktur1(){
        let i = 1;	
        return $('#penjualansajaperfaktur1').DataTable({
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
                totalhb = api
                    .column(6)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                subtotalhj = api
                    .column(8)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                ppnjual = api
                    .column(9)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                diskonjual = api
                    .column(10)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                totalhj = api
                    .column(11)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                laba = api
                    .column(12)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Total over this page
                pagetotalhb = api
                    .column(6, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagesubtotalhj = api
                    .column(8, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pageppnjual = api
                    .column(9, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagediskonjual = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagetotalhj = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagelaba = api
                    .column(12, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Update footer
                api.column(1).footer().innerHTML = 'SUB TOTAL :';
                api.column(6).footer().innerHTML = formatAngka(pagetotalhb,'');  
                api.column(8).footer().innerHTML = formatAngka(pagesubtotalhj,'');  
                api.column(9).footer().innerHTML = formatAngka(pageppnjual,'');  
                api.column(10).footer().innerHTML = formatAngka(pagediskonjual,'');  
                api.column(11).footer().innerHTML = formatAngka(pagetotalhj,'');  
                api.column(12).footer().innerHTML = formatAngka(pagelaba,''); 

            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualansajaperfaktur')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nomorstatus', name: 'nomorstatus', className: 'dt-center' },
                { data: 'tglstatus', name: 'tglstatus', className: 'dt-center' },
                { data: 'jmlrecord', name: 'jmlrecord', className: 'dt-center' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'hbs', name: 'hbs', className: 'dt-right' },
                { data: 'totalhb', name: 'totalhb', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'subtotalhj', name: 'subtotalhj', className: 'dt-right' },
                { data: 'ppnjual', name: 'ppnjual', className: 'dt-right' },
                { data: 'diskonjual', name: 'diskonjual', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
            ]
        });
    }

    function tampil_penjualansajaperjenispembayaran1(){
        let i = 1;	
        return $('#penjualansajaperjenispembayaran1').DataTable({
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
                totalhb = api
                    .column(6)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                subtotalhj = api
                    .column(8)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                ppnjual = api
                    .column(9)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                diskonjual = api
                    .column(10)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                totalhj = api
                    .column(11)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                laba = api
                    .column(12)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Total over this page
                pagetotalhb = api
                    .column(6, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagesubtotalhj = api
                    .column(8, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pageppnjual = api
                    .column(9, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagediskonjual = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagetotalhj = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagelaba = api
                    .column(12, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Update footer
                api.column(1).footer().innerHTML = 'SUB TOTAL :';
                api.column(6).footer().innerHTML = formatAngka(pagetotalhb,'');  
                api.column(8).footer().innerHTML = formatAngka(pagesubtotalhj,'');  
                api.column(9).footer().innerHTML = formatAngka(pageppnjual,'');  
                api.column(10).footer().innerHTML = formatAngka(pagediskonjual,'');  
                api.column(11).footer().innerHTML = formatAngka(pagetotalhj,'');  
                api.column(12).footer().innerHTML = formatAngka(pagelaba,''); 

            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualansajaperjenispembayaran')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'jenispembayaran.kode', className: 'dt-center' },
                { data: 'jenispembayaran', name: 'jenispembayaran.jenispembayaran', className: 'dt-left' },
                { data: 'jmlrecord', name: 'jmlrecord', className: 'dt-center' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'hbs', name: 'hbs', className: 'dt-right' },
                { data: 'totalhb', name: 'totalhb', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'subtotalhj', name: 'subtotalhj', className: 'dt-right' },
                { data: 'ppnjual', name: 'ppnjual', className: 'dt-right' },
                { data: 'diskonjual', name: 'diskonjual', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
            ]
        });
    }

    function tampil_penjualansajapertanggal1(){
        let i = 1;	
        return $('#penjualansajapertanggal1').DataTable({
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
                totalhb = api
                    .column(5)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                subtotalhj = api
                    .column(7)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                ppnjual = api
                    .column(8)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                diskonjual = api
                    .column(9)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                totalhj = api
                    .column(10)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                laba = api
                    .column(11)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Total over this page
                pagetotalhb = api
                    .column(5, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagesubtotalhj = api
                    .column(7, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pageppnjual = api
                    .column(8, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagediskonjual = api
                    .column(9, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagetotalhj = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagelaba = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Update footer
                api.column(1).footer().innerHTML = 'SUB TOTAL :';
                api.column(5).footer().innerHTML = formatAngka(pagetotalhb,'');  
                api.column(7).footer().innerHTML = formatAngka(pagesubtotalhj,'');  
                api.column(8).footer().innerHTML = formatAngka(pageppnjual,'');  
                api.column(9).footer().innerHTML = formatAngka(pagediskonjual,'');  
                api.column(10).footer().innerHTML = formatAngka(pagetotalhj,'');  
                api.column(11).footer().innerHTML = formatAngka(pagelaba,'');  

            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualansajapertanggal')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'tglstatus', name: 'tglstatus', className: 'dt-center' },
                { data: 'jmlrecord', name: 'jmlrecord', className: 'dt-center' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'hbs', name: 'hbs', className: 'dt-right' },
                { data: 'totalhb', name: 'totalhb', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'subtotalhj', name: 'subtotalhj', className: 'dt-right' },
                { data: 'ppnjual', name: 'ppnjual', className: 'dt-right' },
                { data: 'diskonjual', name: 'diskonjual', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
            ]
        });
    }


    function tampil_penjualanfifodetail1(){
        let i = 1;	
        return $('#penjualanfifodetail1').DataTable({
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
            totalhb = api
                .column(9)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            subtotalhj = api
                .column(11)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            ppnjual = api
                .column(12)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            diskonjual = api
                .column(13)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            totalhj = api
                .column(14)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            laba = api
                .column(15)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Total over this page
            pagetotalhb = api
                .column(9, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagesubtotalhj = api
                .column(11, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pageppnjual = api
                .column(12, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagediskonjual = api
                .column(13, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagetotalhj = api
                .column(14, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagelaba = api
                .column(15, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(1).footer().innerHTML = 'SUB TOTAL :';
            api.column(9).footer().innerHTML = formatAngka(pagetotalhb,'');            
            api.column(11).footer().innerHTML = formatAngka(pagesubtotalhj,'');            
            api.column(12).footer().innerHTML = formatAngka(pageppnjual,'');            
            api.column(13).footer().innerHTML = formatAngka(pagediskonjual,'');            
            api.column(14).footer().innerHTML = formatAngka(pagetotalhj,'');            
            api.column(15).footer().innerHTML = formatAngka(pagelaba,'');            
        },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualanfifodetail')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nomorstatus', name: 'nomorstatus', className: 'dt-center' },
                { data: 'tanggal', name: 'created_at', className: 'dt-center' },
                { data: 'kode', name: 'barang.code', className: 'dt-center' },
                { data: 'barcode', name: 'barang.barcode', className: 'dt-center' },
                { data: 'nabara', name: 'barang.nabara', className: 'dt-left' },
                { data: 'keluar', name: 'keluar', className: 'dt-center' },
                { data: 'satuan', name: 'barang.satuan.kode', className: 'dt-center' },
                { data: 'hbskeluar', name: 'hbskeluar', className: 'dt-right' },
                { data: 'hppkeluar', name: 'hppkeluar', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'hppj', name: 'hppj', className: 'dt-right' },
                { data: 'ppnkeluar', name: 'ppnkeluar', className: 'dt-right' },
                { data: 'diskonkeluar', name: 'diskonkeluar', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
                { data: 'jenispembayaran', name: 'jenispembayaran.jenispembayaran', className: 'dt-left' },
                { data: 'customer', name: 'anggota.nama', className: 'dt-left' },
                { data: 'ruang', name: 'ruang.ruang', className: 'dt-left' },
                { data: 'users', name: 'users', className: 'dt-left' },
                { data: 'keterangan', name: 'keterangan', className: 'dt-left' },
                
                
            ]
        });
    }

    function tampil_penjualanfifoperitem1(){
        let i = 1;	
        return $('#penjualanfifoperitem1').DataTable({
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
                totalhb = api
                    .column(8)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                subtotalhj = api
                    .column(10)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                ppnjual = api
                    .column(11)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                diskonjual = api
                    .column(12)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                totalhj = api
                    .column(13)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                laba = api
                    .column(14)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Total over this page
                pagetotalhb = api
                    .column(8, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagesubtotalhj = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pageppnjual = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagediskonjual = api
                    .column(12, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagetotalhj = api
                    .column(13, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagelaba = api
                    .column(14, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Update footer
                api.column(1).footer().innerHTML = 'SUB TOTAL :';
                api.column(8).footer().innerHTML = formatAngka(pagetotalhb,'');  
                api.column(10).footer().innerHTML = formatAngka(pagesubtotalhj,'');  
                api.column(11).footer().innerHTML = formatAngka(pageppnjual,'');  
                api.column(12).footer().innerHTML = formatAngka(pagediskonjual,'');  
                api.column(13).footer().innerHTML = formatAngka(pagetotalhj,'');  
                api.column(14).footer().innerHTML = formatAngka(pagelaba,'');  

            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualanfifoperitem')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'barang.code', className: 'dt-center' },
                { data: 'barcode', name: 'barang.barcode', className: 'dt-center' },
                { data: 'nabara', name: 'barang.nabara', className: 'dt-left' },
                { data: 'jmlrecord', name: 'jmlrecord', className: 'dt-center' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'satuan', name: 'barang.satuan.kode', className: 'dt-center' },
                { data: 'hbs', name: 'hbs', className: 'dt-right' },
                { data: 'totalhb', name: 'totalhb', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'subtotalhj', name: 'subtotalhj', className: 'dt-right' },
                { data: 'ppnjual', name: 'ppnjual', className: 'dt-right' },
                { data: 'diskonjual', name: 'diskonjual', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
            ]
        });
    }

    function tampil_penjualanfifopercustomer1(){
        let i = 1;	
        return $('#penjualanfifopercustomer1').DataTable({
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
                totalhb = api
                    .column(7)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                subtotalhj = api
                    .column(9)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                ppnjual = api
                    .column(10)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                diskonjual = api
                    .column(11)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                totalhj = api
                    .column(12)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                laba = api
                    .column(13)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Total over this page
                pagetotalhb = api
                    .column(7, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagesubtotalhj = api
                    .column(9, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pageppnjual = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagediskonjual = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagetotalhj = api
                    .column(12, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagelaba = api
                    .column(13, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Update footer
                api.column(1).footer().innerHTML = 'SUB TOTAL :';
                api.column(7).footer().innerHTML = formatAngka(pagetotalhb,'');  
                api.column(9).footer().innerHTML = formatAngka(pagesubtotalhj,'');  
                api.column(10).footer().innerHTML = formatAngka(pageppnjual,'');  
                api.column(11).footer().innerHTML = formatAngka(pagediskonjual,'');  
                api.column(12).footer().innerHTML = formatAngka(pagetotalhj,'');  
                api.column(13).footer().innerHTML = formatAngka(pagelaba,''); 

            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualanfifopercustomer')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nia', name: 'anggota.nia', className: 'dt-center' },
                { data: 'nama', name: 'anggota.nama', className: 'dt-left' },
                { data: 'lembaga', name: 'anggota.lembaga.lembaga', className: 'dt-left' },
                { data: 'jmlrecord', name: 'jmlrecord', className: 'dt-center' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'hbs', name: 'hbs', className: 'dt-right' },
                { data: 'totalhb', name: 'totalhb', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'subtotalhj', name: 'subtotalhj', className: 'dt-right' },
                { data: 'ppnjual', name: 'ppnjual', className: 'dt-right' },
                { data: 'diskonjual', name: 'diskonjual', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
            ]
        });
    }

    function tampil_penjualanfifoperfaktur1(){
        let i = 1;	
        return $('#penjualanfifoperfaktur1').DataTable({
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
                totalhb = api
                    .column(6)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                subtotalhj = api
                    .column(8)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                ppnjual = api
                    .column(9)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                diskonjual = api
                    .column(10)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                totalhj = api
                    .column(11)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                laba = api
                    .column(12)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Total over this page
                pagetotalhb = api
                    .column(6, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagesubtotalhj = api
                    .column(8, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pageppnjual = api
                    .column(9, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagediskonjual = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagetotalhj = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagelaba = api
                    .column(12, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Update footer
                api.column(1).footer().innerHTML = 'SUB TOTAL :';
                api.column(6).footer().innerHTML = formatAngka(pagetotalhb,'');  
                api.column(8).footer().innerHTML = formatAngka(pagesubtotalhj,'');  
                api.column(9).footer().innerHTML = formatAngka(pageppnjual,'');  
                api.column(10).footer().innerHTML = formatAngka(pagediskonjual,'');  
                api.column(11).footer().innerHTML = formatAngka(pagetotalhj,'');  
                api.column(12).footer().innerHTML = formatAngka(pagelaba,''); 

            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualanfifoperfaktur')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nomorstatus', name: 'nomorstatus', className: 'dt-center' },
                { data: 'tglstatus', name: 'tglstatus', className: 'dt-center' },
                { data: 'jmlrecord', name: 'jmlrecord', className: 'dt-center' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'hbs', name: 'hbs', className: 'dt-right' },
                { data: 'totalhb', name: 'totalhb', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'subtotalhj', name: 'subtotalhj', className: 'dt-right' },
                { data: 'ppnjual', name: 'ppnjual', className: 'dt-right' },
                { data: 'diskonjual', name: 'diskonjual', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
            ]
        });
    }

    function tampil_penjualanfifoperjenispembayaran1(){
        let i = 1;	
        return $('#penjualanfifoperjenispembayaran1').DataTable({
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
                totalhb = api
                    .column(6)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                subtotalhj = api
                    .column(8)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                ppnjual = api
                    .column(9)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                diskonjual = api
                    .column(10)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                totalhj = api
                    .column(11)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                laba = api
                    .column(12)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Total over this page
                pagetotalhb = api
                    .column(6, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagesubtotalhj = api
                    .column(8, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pageppnjual = api
                    .column(9, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagediskonjual = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagetotalhj = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagelaba = api
                    .column(12, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Update footer
                api.column(1).footer().innerHTML = 'SUB TOTAL :';
                api.column(6).footer().innerHTML = formatAngka(pagetotalhb,'');  
                api.column(8).footer().innerHTML = formatAngka(pagesubtotalhj,'');  
                api.column(9).footer().innerHTML = formatAngka(pageppnjual,'');  
                api.column(10).footer().innerHTML = formatAngka(pagediskonjual,'');  
                api.column(11).footer().innerHTML = formatAngka(pagetotalhj,'');  
                api.column(12).footer().innerHTML = formatAngka(pagelaba,''); 

            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualanfifoperjenispembayaran')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'jenispembayaran.kode', className: 'dt-center' },
                { data: 'jenispembayaran', name: 'jenispembayaran.jenispembayaran', className: 'dt-left' },
                { data: 'jmlrecord', name: 'jmlrecord', className: 'dt-center' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'hbs', name: 'hbs', className: 'dt-right' },
                { data: 'totalhb', name: 'totalhb', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'subtotalhj', name: 'subtotalhj', className: 'dt-right' },
                { data: 'ppnjual', name: 'ppnjual', className: 'dt-right' },
                { data: 'diskonjual', name: 'diskonjual', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
            ]
        });
    }

    function tampil_penjualanfifopertanggal1(){
        let i = 1;	
        return $('#penjualanfifopertanggal1').DataTable({
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
                totalhb = api
                    .column(5)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                subtotalhj = api
                    .column(7)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                ppnjual = api
                    .column(8)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                diskonjual = api
                    .column(9)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                totalhj = api
                    .column(10)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                laba = api
                    .column(11)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Total over this page
                pagetotalhb = api
                    .column(5, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagesubtotalhj = api
                    .column(7, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pageppnjual = api
                    .column(8, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagediskonjual = api
                    .column(9, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagetotalhj = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagelaba = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Update footer
                api.column(1).footer().innerHTML = 'SUB TOTAL :';
                api.column(5).footer().innerHTML = formatAngka(pagetotalhb,'');  
                api.column(7).footer().innerHTML = formatAngka(pagesubtotalhj,'');  
                api.column(8).footer().innerHTML = formatAngka(pageppnjual,'');  
                api.column(9).footer().innerHTML = formatAngka(pagediskonjual,'');  
                api.column(10).footer().innerHTML = formatAngka(pagetotalhj,'');  
                api.column(11).footer().innerHTML = formatAngka(pagelaba,'');  

            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualanfifopertanggal')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'tglstatus', name: 'tglstatus', className: 'dt-center' },
                { data: 'jmlrecord', name: 'jmlrecord', className: 'dt-center' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'hbs', name: 'hbs', className: 'dt-right' },
                { data: 'totalhb', name: 'totalhb', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'subtotalhj', name: 'subtotalhj', className: 'dt-right' },
                { data: 'ppnjual', name: 'ppnjual', className: 'dt-right' },
                { data: 'diskonjual', name: 'diskonjual', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
            ]
        });
    }


    function tampil_penjualanmovadetail1(){
        let i = 1;	
        return $('#penjualanmovadetail1').DataTable({
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
            totalhb = api
                .column(9)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            subtotalhj = api
                .column(11)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            ppnjual = api
                .column(12)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            diskonjual = api
                .column(13)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            totalhj = api
                .column(14)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            laba = api
                .column(15)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Total over this page
            pagetotalhb = api
                .column(9, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagesubtotalhj = api
                .column(11, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pageppnjual = api
                .column(12, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagediskonjual = api
                .column(13, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagetotalhj = api
                .column(14, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagelaba = api
                .column(15, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(1).footer().innerHTML = 'SUB TOTAL :';
            api.column(9).footer().innerHTML = formatAngka(pagetotalhb,'');            
            api.column(11).footer().innerHTML = formatAngka(pagesubtotalhj,'');            
            api.column(12).footer().innerHTML = formatAngka(pageppnjual,'');            
            api.column(13).footer().innerHTML = formatAngka(pagediskonjual,'');            
            api.column(14).footer().innerHTML = formatAngka(pagetotalhj,'');            
            api.column(15).footer().innerHTML = formatAngka(pagelaba,'');            
        },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualanmovadetail')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nomorstatus', name: 'nomorstatus', className: 'dt-center' },
                { data: 'tanggal', name: 'created_at', className: 'dt-center' },
                { data: 'kode', name: 'barang.code', className: 'dt-center' },
                { data: 'barcode', name: 'barang.barcode', className: 'dt-center' },
                { data: 'nabara', name: 'barang.nabara', className: 'dt-left' },
                { data: 'keluar', name: 'keluar', className: 'dt-center' },
                { data: 'satuan', name: 'barang.satuan.kode', className: 'dt-center' },
                { data: 'hbskeluar', name: 'hbskeluar', className: 'dt-right' },
                { data: 'hppkeluar', name: 'hppkeluar', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'hppj', name: 'hppj', className: 'dt-right' },
                { data: 'ppnkeluar', name: 'ppnkeluar', className: 'dt-right' },
                { data: 'diskonkeluar', name: 'diskonkeluar', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
                { data: 'jenispembayaran', name: 'jenispembayaran.jenispembayaran', className: 'dt-left' },
                { data: 'customer', name: 'anggota.nama', className: 'dt-left' },
                { data: 'ruang', name: 'ruang.ruang', className: 'dt-left' },
                { data: 'users', name: 'users', className: 'dt-left' },
                { data: 'keterangan', name: 'keterangan', className: 'dt-left' },
                
                
            ]
        });
    }

    function tampil_penjualanmovaperitem1(){
        let i = 1;	
        return $('#penjualanmovaperitem1').DataTable({
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
                totalhb = api
                    .column(8)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                subtotalhj = api
                    .column(10)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                ppnjual = api
                    .column(11)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                diskonjual = api
                    .column(12)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                totalhj = api
                    .column(13)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                laba = api
                    .column(14)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Total over this page
                pagetotalhb = api
                    .column(8, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagesubtotalhj = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pageppnjual = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagediskonjual = api
                    .column(12, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagetotalhj = api
                    .column(13, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagelaba = api
                    .column(14, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Update footer
                api.column(1).footer().innerHTML = 'SUB TOTAL :';
                api.column(8).footer().innerHTML = formatAngka(pagetotalhb,'');  
                api.column(10).footer().innerHTML = formatAngka(pagesubtotalhj,'');  
                api.column(11).footer().innerHTML = formatAngka(pageppnjual,'');  
                api.column(12).footer().innerHTML = formatAngka(pagediskonjual,'');  
                api.column(13).footer().innerHTML = formatAngka(pagetotalhj,'');  
                api.column(14).footer().innerHTML = formatAngka(pagelaba,'');  

            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualanmovaperitem')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'barang.code', className: 'dt-center' },
                { data: 'barcode', name: 'barang.barcode', className: 'dt-center' },
                { data: 'nabara', name: 'barang.nabara', className: 'dt-left' },
                { data: 'jmlrecord', name: 'jmlrecord', className: 'dt-center' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'satuan', name: 'barang.satuan.kode', className: 'dt-center' },
                { data: 'hbs', name: 'hbs', className: 'dt-right' },
                { data: 'totalhb', name: 'totalhb', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'subtotalhj', name: 'subtotalhj', className: 'dt-right' },
                { data: 'ppnjual', name: 'ppnjual', className: 'dt-right' },
                { data: 'diskonjual', name: 'diskonjual', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
            ]
        });
    }

    function tampil_penjualanmovapercustomer1(){
        let i = 1;	
        return $('#penjualanmovapercustomer1').DataTable({
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
                totalhb = api
                    .column(7)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                subtotalhj = api
                    .column(9)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                ppnjual = api
                    .column(10)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                diskonjual = api
                    .column(11)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                totalhj = api
                    .column(12)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                laba = api
                    .column(13)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Total over this page
                pagetotalhb = api
                    .column(7, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagesubtotalhj = api
                    .column(9, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pageppnjual = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagediskonjual = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagetotalhj = api
                    .column(12, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagelaba = api
                    .column(13, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Update footer
                api.column(1).footer().innerHTML = 'SUB TOTAL :';
                api.column(7).footer().innerHTML = formatAngka(pagetotalhb,'');  
                api.column(9).footer().innerHTML = formatAngka(pagesubtotalhj,'');  
                api.column(10).footer().innerHTML = formatAngka(pageppnjual,'');  
                api.column(11).footer().innerHTML = formatAngka(pagediskonjual,'');  
                api.column(12).footer().innerHTML = formatAngka(pagetotalhj,'');  
                api.column(13).footer().innerHTML = formatAngka(pagelaba,''); 

            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualanmovapercustomer')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nia', name: 'anggota.nia', className: 'dt-center' },
                { data: 'nama', name: 'anggota.nama', className: 'dt-left' },
                { data: 'lembaga', name: 'anggota.lembaga.lembaga', className: 'dt-left' },
                { data: 'jmlrecord', name: 'jmlrecord', className: 'dt-center' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'hbs', name: 'hbs', className: 'dt-right' },
                { data: 'totalhb', name: 'totalhb', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'subtotalhj', name: 'subtotalhj', className: 'dt-right' },
                { data: 'ppnjual', name: 'ppnjual', className: 'dt-right' },
                { data: 'diskonjual', name: 'diskonjual', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
            ]
        });
    }

    function tampil_penjualanmovaperfaktur1(){
        let i = 1;	
        return $('#penjualanmovaperfaktur1').DataTable({
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
                totalhb = api
                    .column(6)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                subtotalhj = api
                    .column(8)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                ppnjual = api
                    .column(9)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                diskonjual = api
                    .column(10)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                totalhj = api
                    .column(11)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                laba = api
                    .column(12)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Total over this page
                pagetotalhb = api
                    .column(6, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagesubtotalhj = api
                    .column(8, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pageppnjual = api
                    .column(9, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagediskonjual = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagetotalhj = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagelaba = api
                    .column(12, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Update footer
                api.column(1).footer().innerHTML = 'SUB TOTAL :';
                api.column(6).footer().innerHTML = formatAngka(pagetotalhb,'');  
                api.column(8).footer().innerHTML = formatAngka(pagesubtotalhj,'');  
                api.column(9).footer().innerHTML = formatAngka(pageppnjual,'');  
                api.column(10).footer().innerHTML = formatAngka(pagediskonjual,'');  
                api.column(11).footer().innerHTML = formatAngka(pagetotalhj,'');  
                api.column(12).footer().innerHTML = formatAngka(pagelaba,''); 

            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualanmovaperfaktur')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nomorstatus', name: 'nomorstatus', className: 'dt-center' },
                { data: 'tglstatus', name: 'tglstatus', className: 'dt-center' },
                { data: 'jmlrecord', name: 'jmlrecord', className: 'dt-center' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'hbs', name: 'hbs', className: 'dt-right' },
                { data: 'totalhb', name: 'totalhb', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'subtotalhj', name: 'subtotalhj', className: 'dt-right' },
                { data: 'ppnjual', name: 'ppnjual', className: 'dt-right' },
                { data: 'diskonjual', name: 'diskonjual', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
            ]
        });
    }

    function tampil_penjualanmovaperjenispembayaran1(){
        let i = 1;	
        return $('#penjualanmovaperjenispembayaran1').DataTable({
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
                totalhb = api
                    .column(6)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                subtotalhj = api
                    .column(8)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                ppnjual = api
                    .column(9)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                diskonjual = api
                    .column(10)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                totalhj = api
                    .column(11)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                laba = api
                    .column(12)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Total over this page
                pagetotalhb = api
                    .column(6, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagesubtotalhj = api
                    .column(8, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pageppnjual = api
                    .column(9, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagediskonjual = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagetotalhj = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagelaba = api
                    .column(12, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Update footer
                api.column(1).footer().innerHTML = 'SUB TOTAL :';
                api.column(6).footer().innerHTML = formatAngka(pagetotalhb,'');  
                api.column(8).footer().innerHTML = formatAngka(pagesubtotalhj,'');  
                api.column(9).footer().innerHTML = formatAngka(pageppnjual,'');  
                api.column(10).footer().innerHTML = formatAngka(pagediskonjual,'');  
                api.column(11).footer().innerHTML = formatAngka(pagetotalhj,'');  
                api.column(12).footer().innerHTML = formatAngka(pagelaba,''); 

            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualanmovaperjenispembayaran')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'jenispembayaran.kode', className: 'dt-center' },
                { data: 'jenispembayaran', name: 'jenispembayaran.jenispembayaran', className: 'dt-left' },
                { data: 'jmlrecord', name: 'jmlrecord', className: 'dt-center' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'hbs', name: 'hbs', className: 'dt-right' },
                { data: 'totalhb', name: 'totalhb', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'subtotalhj', name: 'subtotalhj', className: 'dt-right' },
                { data: 'ppnjual', name: 'ppnjual', className: 'dt-right' },
                { data: 'diskonjual', name: 'diskonjual', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
            ]
        });
    }

    function tampil_penjualanmovapertanggal1(){
        let i = 1;	
        return $('#penjualanmovapertanggal1').DataTable({
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
                totalhb = api
                    .column(5)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                subtotalhj = api
                    .column(7)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                ppnjual = api
                    .column(8)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                diskonjual = api
                    .column(9)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                totalhj = api
                    .column(10)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                laba = api
                    .column(11)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Total over this page
                pagetotalhb = api
                    .column(5, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagesubtotalhj = api
                    .column(7, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pageppnjual = api
                    .column(8, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagediskonjual = api
                    .column(9, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagetotalhj = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagelaba = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Update footer
                api.column(1).footer().innerHTML = 'SUB TOTAL :';
                api.column(5).footer().innerHTML = formatAngka(pagetotalhb,'');  
                api.column(7).footer().innerHTML = formatAngka(pagesubtotalhj,'');  
                api.column(8).footer().innerHTML = formatAngka(pageppnjual,'');  
                api.column(9).footer().innerHTML = formatAngka(pagediskonjual,'');  
                api.column(10).footer().innerHTML = formatAngka(pagetotalhj,'');  
                api.column(11).footer().innerHTML = formatAngka(pagelaba,'');  

            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualanmovapertanggal')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'tglstatus', name: 'tglstatus', className: 'dt-center' },
                { data: 'jmlrecord', name: 'jmlrecord', className: 'dt-center' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'hbs', name: 'hbs', className: 'dt-right' },
                { data: 'totalhb', name: 'totalhb', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'subtotalhj', name: 'subtotalhj', className: 'dt-right' },
                { data: 'ppnjual', name: 'ppnjual', className: 'dt-right' },
                { data: 'diskonjual', name: 'diskonjual', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
            ]
        });
    }

    function tampil_penjualanlifodetail1(){
        let i = 1;	
        return $('#penjualanlifodetail1').DataTable({
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
            totalhb = api
                .column(9)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            subtotalhj = api
                .column(11)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            ppnjual = api
                .column(12)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            diskonjual = api
                .column(13)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            totalhj = api
                .column(14)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            laba = api
                .column(15)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Total over this page
            pagetotalhb = api
                .column(9, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagesubtotalhj = api
                .column(11, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pageppnjual = api
                .column(12, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagediskonjual = api
                .column(13, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagetotalhj = api
                .column(14, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagelaba = api
                .column(15, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(1).footer().innerHTML = 'SUB TOTAL :';
            api.column(9).footer().innerHTML = formatAngka(pagetotalhb,'');            
            api.column(11).footer().innerHTML = formatAngka(pagesubtotalhj,'');            
            api.column(12).footer().innerHTML = formatAngka(pageppnjual,'');            
            api.column(13).footer().innerHTML = formatAngka(pagediskonjual,'');            
            api.column(14).footer().innerHTML = formatAngka(pagetotalhj,'');            
            api.column(15).footer().innerHTML = formatAngka(pagelaba,'');            
        },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualanlifodetail')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nomorstatus', name: 'nomorstatus', className: 'dt-center' },
                { data: 'tanggal', name: 'created_at', className: 'dt-center' },
                { data: 'kode', name: 'barang.code', className: 'dt-center' },
                { data: 'barcode', name: 'barang.barcode', className: 'dt-center' },
                { data: 'nabara', name: 'barang.nabara', className: 'dt-left' },
                { data: 'keluar', name: 'keluar', className: 'dt-center' },
                { data: 'satuan', name: 'barang.satuan.kode', className: 'dt-center' },
                { data: 'hbskeluar', name: 'hbskeluar', className: 'dt-right' },
                { data: 'hppkeluar', name: 'hppkeluar', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'hppj', name: 'hppj', className: 'dt-right' },
                { data: 'ppnkeluar', name: 'ppnkeluar', className: 'dt-right' },
                { data: 'diskonkeluar', name: 'diskonkeluar', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
                { data: 'jenispembayaran', name: 'jenispembayaran.jenispembayaran', className: 'dt-left' },
                { data: 'customer', name: 'anggota.nama', className: 'dt-left' },
                { data: 'ruang', name: 'ruang.ruang', className: 'dt-left' },
                { data: 'users', name: 'users', className: 'dt-left' },
                { data: 'keterangan', name: 'keterangan', className: 'dt-left' },
                
                
            ]
        });
    }

    function tampil_penjualanlifoperitem1(){
        let i = 1;	
        return $('#penjualanlifoperitem1').DataTable({
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
                totalhb = api
                    .column(8)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                subtotalhj = api
                    .column(10)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                ppnjual = api
                    .column(11)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                diskonjual = api
                    .column(12)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                totalhj = api
                    .column(13)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                laba = api
                    .column(14)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Total over this page
                pagetotalhb = api
                    .column(8, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagesubtotalhj = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pageppnjual = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagediskonjual = api
                    .column(12, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagetotalhj = api
                    .column(13, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagelaba = api
                    .column(14, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Update footer
                api.column(1).footer().innerHTML = 'SUB TOTAL :';
                api.column(8).footer().innerHTML = formatAngka(pagetotalhb,'');  
                api.column(10).footer().innerHTML = formatAngka(pagesubtotalhj,'');  
                api.column(11).footer().innerHTML = formatAngka(pageppnjual,'');  
                api.column(12).footer().innerHTML = formatAngka(pagediskonjual,'');  
                api.column(13).footer().innerHTML = formatAngka(pagetotalhj,'');  
                api.column(14).footer().innerHTML = formatAngka(pagelaba,'');  

            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualanlifoperitem')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'barang.code', className: 'dt-center' },
                { data: 'barcode', name: 'barang.barcode', className: 'dt-center' },
                { data: 'nabara', name: 'barang.nabara', className: 'dt-left' },
                { data: 'jmlrecord', name: 'jmlrecord', className: 'dt-center' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'satuan', name: 'barang.satuan.kode', className: 'dt-center' },
                { data: 'hbs', name: 'hbs', className: 'dt-right' },
                { data: 'totalhb', name: 'totalhb', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'subtotalhj', name: 'subtotalhj', className: 'dt-right' },
                { data: 'ppnjual', name: 'ppnjual', className: 'dt-right' },
                { data: 'diskonjual', name: 'diskonjual', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
            ]
        });
    }

    function tampil_penjualanlifopercustomer1(){
        let i = 1;	
        return $('#penjualanlifopercustomer1').DataTable({
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
                totalhb = api
                    .column(7)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                subtotalhj = api
                    .column(9)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                ppnjual = api
                    .column(10)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                diskonjual = api
                    .column(11)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                totalhj = api
                    .column(12)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                laba = api
                    .column(13)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Total over this page
                pagetotalhb = api
                    .column(7, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagesubtotalhj = api
                    .column(9, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pageppnjual = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagediskonjual = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagetotalhj = api
                    .column(12, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagelaba = api
                    .column(13, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Update footer
                api.column(1).footer().innerHTML = 'SUB TOTAL :';
                api.column(7).footer().innerHTML = formatAngka(pagetotalhb,'');  
                api.column(9).footer().innerHTML = formatAngka(pagesubtotalhj,'');  
                api.column(10).footer().innerHTML = formatAngka(pageppnjual,'');  
                api.column(11).footer().innerHTML = formatAngka(pagediskonjual,'');  
                api.column(12).footer().innerHTML = formatAngka(pagetotalhj,'');  
                api.column(13).footer().innerHTML = formatAngka(pagelaba,''); 

            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualanlifopercustomer')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nia', name: 'anggota.nia', className: 'dt-center' },
                { data: 'nama', name: 'anggota.nama', className: 'dt-left' },
                { data: 'lembaga', name: 'anggota.lembaga.lembaga', className: 'dt-left' },
                { data: 'jmlrecord', name: 'jmlrecord', className: 'dt-center' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'hbs', name: 'hbs', className: 'dt-right' },
                { data: 'totalhb', name: 'totalhb', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'subtotalhj', name: 'subtotalhj', className: 'dt-right' },
                { data: 'ppnjual', name: 'ppnjual', className: 'dt-right' },
                { data: 'diskonjual', name: 'diskonjual', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
            ]
        });
    }

    function tampil_penjualanlifoperfaktur1(){
        let i = 1;	
        return $('#penjualanlifoperfaktur1').DataTable({
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
                totalhb = api
                    .column(6)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                subtotalhj = api
                    .column(8)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                ppnjual = api
                    .column(9)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                diskonjual = api
                    .column(10)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                totalhj = api
                    .column(11)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                laba = api
                    .column(12)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Total over this page
                pagetotalhb = api
                    .column(6, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagesubtotalhj = api
                    .column(8, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pageppnjual = api
                    .column(9, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagediskonjual = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagetotalhj = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagelaba = api
                    .column(12, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Update footer
                api.column(1).footer().innerHTML = 'SUB TOTAL :';
                api.column(6).footer().innerHTML = formatAngka(pagetotalhb,'');  
                api.column(8).footer().innerHTML = formatAngka(pagesubtotalhj,'');  
                api.column(9).footer().innerHTML = formatAngka(pageppnjual,'');  
                api.column(10).footer().innerHTML = formatAngka(pagediskonjual,'');  
                api.column(11).footer().innerHTML = formatAngka(pagetotalhj,'');  
                api.column(12).footer().innerHTML = formatAngka(pagelaba,''); 

            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualanlifoperfaktur')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nomorstatus', name: 'nomorstatus', className: 'dt-center' },
                { data: 'tglstatus', name: 'tglstatus', className: 'dt-center' },
                { data: 'jmlrecord', name: 'jmlrecord', className: 'dt-center' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'hbs', name: 'hbs', className: 'dt-right' },
                { data: 'totalhb', name: 'totalhb', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'subtotalhj', name: 'subtotalhj', className: 'dt-right' },
                { data: 'ppnjual', name: 'ppnjual', className: 'dt-right' },
                { data: 'diskonjual', name: 'diskonjual', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
            ]
        });
    }

    function tampil_penjualanlifoperjenispembayaran1(){
        let i = 1;	
        return $('#penjualanlifoperjenispembayaran1').DataTable({
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
                totalhb = api
                    .column(6)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                subtotalhj = api
                    .column(8)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                ppnjual = api
                    .column(9)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                diskonjual = api
                    .column(10)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                totalhj = api
                    .column(11)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                laba = api
                    .column(12)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Total over this page
                pagetotalhb = api
                    .column(6, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagesubtotalhj = api
                    .column(8, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pageppnjual = api
                    .column(9, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagediskonjual = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagetotalhj = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagelaba = api
                    .column(12, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Update footer
                api.column(1).footer().innerHTML = 'SUB TOTAL :';
                api.column(6).footer().innerHTML = formatAngka(pagetotalhb,'');  
                api.column(8).footer().innerHTML = formatAngka(pagesubtotalhj,'');  
                api.column(9).footer().innerHTML = formatAngka(pageppnjual,'');  
                api.column(10).footer().innerHTML = formatAngka(pagediskonjual,'');  
                api.column(11).footer().innerHTML = formatAngka(pagetotalhj,'');  
                api.column(12).footer().innerHTML = formatAngka(pagelaba,''); 

            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualanlifoperjenispembayaran')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'jenispembayaran.kode', className: 'dt-center' },
                { data: 'jenispembayaran', name: 'jenispembayaran.jenispembayaran', className: 'dt-left' },
                { data: 'jmlrecord', name: 'jmlrecord', className: 'dt-center' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'hbs', name: 'hbs', className: 'dt-right' },
                { data: 'totalhb', name: 'totalhb', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'subtotalhj', name: 'subtotalhj', className: 'dt-right' },
                { data: 'ppnjual', name: 'ppnjual', className: 'dt-right' },
                { data: 'diskonjual', name: 'diskonjual', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
            ]
        });
    }

    function tampil_penjualanlifopertanggal1(){
        let i = 1;	
        return $('#penjualanlifopertanggal1').DataTable({
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
                totalhb = api
                    .column(5)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                subtotalhj = api
                    .column(7)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                ppnjual = api
                    .column(8)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                diskonjual = api
                    .column(9)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                totalhj = api
                    .column(10)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                laba = api
                    .column(11)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Total over this page
                pagetotalhb = api
                    .column(5, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagesubtotalhj = api
                    .column(7, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pageppnjual = api
                    .column(8, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagediskonjual = api
                    .column(9, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagetotalhj = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagelaba = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Update footer
                api.column(1).footer().innerHTML = 'SUB TOTAL :';
                api.column(5).footer().innerHTML = formatAngka(pagetotalhb,'');  
                api.column(7).footer().innerHTML = formatAngka(pagesubtotalhj,'');  
                api.column(8).footer().innerHTML = formatAngka(pageppnjual,'');  
                api.column(9).footer().innerHTML = formatAngka(pagediskonjual,'');  
                api.column(10).footer().innerHTML = formatAngka(pagetotalhj,'');  
                api.column(11).footer().innerHTML = formatAngka(pagelaba,'');  

            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualanlifopertanggal')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'tglstatus', name: 'tglstatus', className: 'dt-center' },
                { data: 'jmlrecord', name: 'jmlrecord', className: 'dt-center' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'hbs', name: 'hbs', className: 'dt-right' },
                { data: 'totalhb', name: 'totalhb', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'subtotalhj', name: 'subtotalhj', className: 'dt-right' },
                { data: 'ppnjual', name: 'ppnjual', className: 'dt-right' },
                { data: 'diskonjual', name: 'diskonjual', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
            ]
        });
    }

    function tampil_penjualanlaindetail1(){
        let i = 1;	
        return $('#penjualanlaindetail1').DataTable({
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
            totalhb = api
                .column(9)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            subtotalhj = api
                .column(11)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            ppnjual = api
                .column(12)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            diskonjual = api
                .column(13)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            totalhj = api
                .column(14)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            laba = api
                .column(15)
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Total over this page
            pagetotalhb = api
                .column(9, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagesubtotalhj = api
                .column(11, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pageppnjual = api
                .column(12, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagediskonjual = api
                .column(13, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagetotalhj = api
                .column(14, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
            pagelaba = api
                .column(15, { page: 'current' })
                .data()
                .reduce((a, b) => intVal(a) + intVal(b), 0);
    
            // Update footer
            api.column(1).footer().innerHTML = 'SUB TOTAL :';
            api.column(9).footer().innerHTML = formatAngka(pagetotalhb,'');            
            api.column(11).footer().innerHTML = formatAngka(pagesubtotalhj,'');            
            api.column(12).footer().innerHTML = formatAngka(pageppnjual,'');            
            api.column(13).footer().innerHTML = formatAngka(pagediskonjual,'');            
            api.column(14).footer().innerHTML = formatAngka(pagetotalhj,'');            
            api.column(15).footer().innerHTML = formatAngka(pagelaba,'');            
        },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualanlaindetail')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nomorstatus', name: 'nomorstatus', className: 'dt-center' },
                { data: 'tanggal', name: 'created_at', className: 'dt-center' },
                { data: 'kode', name: 'barang.code', className: 'dt-center' },
                { data: 'barcode', name: 'barang.barcode', className: 'dt-center' },
                { data: 'nabara', name: 'barang.nabara', className: 'dt-left' },
                { data: 'keluar', name: 'keluar', className: 'dt-center' },
                { data: 'satuan', name: 'barang.satuan.kode', className: 'dt-center' },
                { data: 'hbskeluar', name: 'hbskeluar', className: 'dt-right' },
                { data: 'hppkeluar', name: 'hppkeluar', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'hppj', name: 'hppj', className: 'dt-right' },
                { data: 'ppnkeluar', name: 'ppnkeluar', className: 'dt-right' },
                { data: 'diskonkeluar', name: 'diskonkeluar', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
                { data: 'jenispembayaran', name: 'jenispembayaran.jenispembayaran', className: 'dt-left' },
                { data: 'customer', name: 'anggota.nama', className: 'dt-left' },
                { data: 'ruang', name: 'ruang.ruang', className: 'dt-left' },
                { data: 'users', name: 'users', className: 'dt-left' },
                { data: 'keterangan', name: 'keterangan', className: 'dt-left' },
                
                
            ]
        });
    }

    function tampil_penjualanlainperitem1(){
        let i = 1;	
        return $('#penjualanlainperitem1').DataTable({
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
                totalhb = api
                    .column(8)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                subtotalhj = api
                    .column(10)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                ppnjual = api
                    .column(11)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                diskonjual = api
                    .column(12)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                totalhj = api
                    .column(13)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                laba = api
                    .column(14)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Total over this page
                pagetotalhb = api
                    .column(8, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagesubtotalhj = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pageppnjual = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagediskonjual = api
                    .column(12, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagetotalhj = api
                    .column(13, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagelaba = api
                    .column(14, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Update footer
                api.column(1).footer().innerHTML = 'SUB TOTAL :';
                api.column(8).footer().innerHTML = formatAngka(pagetotalhb,'');  
                api.column(10).footer().innerHTML = formatAngka(pagesubtotalhj,'');  
                api.column(11).footer().innerHTML = formatAngka(pageppnjual,'');  
                api.column(12).footer().innerHTML = formatAngka(pagediskonjual,'');  
                api.column(13).footer().innerHTML = formatAngka(pagetotalhj,'');  
                api.column(14).footer().innerHTML = formatAngka(pagelaba,'');  

            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualanlainperitem')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'barang.code', className: 'dt-center' },
                { data: 'barcode', name: 'barang.barcode', className: 'dt-center' },
                { data: 'nabara', name: 'barang.nabara', className: 'dt-left' },
                { data: 'jmlrecord', name: 'jmlrecord', className: 'dt-center' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'satuan', name: 'barang.satuan.kode', className: 'dt-center' },
                { data: 'hbs', name: 'hbs', className: 'dt-right' },
                { data: 'totalhb', name: 'totalhb', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'subtotalhj', name: 'subtotalhj', className: 'dt-right' },
                { data: 'ppnjual', name: 'ppnjual', className: 'dt-right' },
                { data: 'diskonjual', name: 'diskonjual', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
            ]
        });
    }

    function tampil_penjualanlainpercustomer1(){
        let i = 1;	
        return $('#penjualanlainpercustomer1').DataTable({
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
                totalhb = api
                    .column(7)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                subtotalhj = api
                    .column(9)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                ppnjual = api
                    .column(10)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                diskonjual = api
                    .column(11)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                totalhj = api
                    .column(12)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                laba = api
                    .column(13)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Total over this page
                pagetotalhb = api
                    .column(7, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagesubtotalhj = api
                    .column(9, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pageppnjual = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagediskonjual = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagetotalhj = api
                    .column(12, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagelaba = api
                    .column(13, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Update footer
                api.column(1).footer().innerHTML = 'SUB TOTAL :';
                api.column(7).footer().innerHTML = formatAngka(pagetotalhb,'');  
                api.column(9).footer().innerHTML = formatAngka(pagesubtotalhj,'');  
                api.column(10).footer().innerHTML = formatAngka(pageppnjual,'');  
                api.column(11).footer().innerHTML = formatAngka(pagediskonjual,'');  
                api.column(12).footer().innerHTML = formatAngka(pagetotalhj,'');  
                api.column(13).footer().innerHTML = formatAngka(pagelaba,''); 

            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualanlainpercustomer')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nia', name: 'anggota.nia', className: 'dt-center' },
                { data: 'nama', name: 'anggota.nama', className: 'dt-left' },
                { data: 'lembaga', name: 'anggota.lembaga.lembaga', className: 'dt-left' },
                { data: 'jmlrecord', name: 'jmlrecord', className: 'dt-center' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'hbs', name: 'hbs', className: 'dt-right' },
                { data: 'totalhb', name: 'totalhb', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'subtotalhj', name: 'subtotalhj', className: 'dt-right' },
                { data: 'ppnjual', name: 'ppnjual', className: 'dt-right' },
                { data: 'diskonjual', name: 'diskonjual', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
            ]
        });
    }

    function tampil_penjualanlainperfaktur1(){
        let i = 1;	
        return $('#penjualanlainperfaktur1').DataTable({
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
                totalhb = api
                    .column(6)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                subtotalhj = api
                    .column(8)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                ppnjual = api
                    .column(9)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                diskonjual = api
                    .column(10)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                totalhj = api
                    .column(11)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                laba = api
                    .column(12)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Total over this page
                pagetotalhb = api
                    .column(6, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagesubtotalhj = api
                    .column(8, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pageppnjual = api
                    .column(9, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagediskonjual = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagetotalhj = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagelaba = api
                    .column(12, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Update footer
                api.column(1).footer().innerHTML = 'SUB TOTAL :';
                api.column(6).footer().innerHTML = formatAngka(pagetotalhb,'');  
                api.column(8).footer().innerHTML = formatAngka(pagesubtotalhj,'');  
                api.column(9).footer().innerHTML = formatAngka(pageppnjual,'');  
                api.column(10).footer().innerHTML = formatAngka(pagediskonjual,'');  
                api.column(11).footer().innerHTML = formatAngka(pagetotalhj,'');  
                api.column(12).footer().innerHTML = formatAngka(pagelaba,''); 

            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualanlainperfaktur')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'nomorstatus', name: 'nomorstatus', className: 'dt-center' },
                { data: 'tglstatus', name: 'tglstatus', className: 'dt-center' },
                { data: 'jmlrecord', name: 'jmlrecord', className: 'dt-center' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'hbs', name: 'hbs', className: 'dt-right' },
                { data: 'totalhb', name: 'totalhb', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'subtotalhj', name: 'subtotalhj', className: 'dt-right' },
                { data: 'ppnjual', name: 'ppnjual', className: 'dt-right' },
                { data: 'diskonjual', name: 'diskonjual', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
            ]
        });
    }

    function tampil_penjualanlainperjenispembayaran1(){
        let i = 1;	
        return $('#penjualanlainperjenispembayaran1').DataTable({
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
                totalhb = api
                    .column(6)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                subtotalhj = api
                    .column(8)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                ppnjual = api
                    .column(9)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                diskonjual = api
                    .column(10)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                totalhj = api
                    .column(11)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                laba = api
                    .column(12)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Total over this page
                pagetotalhb = api
                    .column(6, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagesubtotalhj = api
                    .column(8, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pageppnjual = api
                    .column(9, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagediskonjual = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagetotalhj = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagelaba = api
                    .column(12, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Update footer
                api.column(1).footer().innerHTML = 'SUB TOTAL :';
                api.column(6).footer().innerHTML = formatAngka(pagetotalhb,'');  
                api.column(8).footer().innerHTML = formatAngka(pagesubtotalhj,'');  
                api.column(9).footer().innerHTML = formatAngka(pageppnjual,'');  
                api.column(10).footer().innerHTML = formatAngka(pagediskonjual,'');  
                api.column(11).footer().innerHTML = formatAngka(pagetotalhj,'');  
                api.column(12).footer().innerHTML = formatAngka(pagelaba,''); 

            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualanlainperjenispembayaran')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'jenispembayaran.kode', className: 'dt-center' },
                { data: 'jenispembayaran', name: 'jenispembayaran.jenispembayaran', className: 'dt-left' },
                { data: 'jmlrecord', name: 'jmlrecord', className: 'dt-center' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'hbs', name: 'hbs', className: 'dt-right' },
                { data: 'totalhb', name: 'totalhb', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'subtotalhj', name: 'subtotalhj', className: 'dt-right' },
                { data: 'ppnjual', name: 'ppnjual', className: 'dt-right' },
                { data: 'diskonjual', name: 'diskonjual', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
            ]
        });
    }

    function tampil_penjualanlainpertanggal1(){
        let i = 1;	
        return $('#penjualanlainpertanggal1').DataTable({
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
                totalhb = api
                    .column(5)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                subtotalhj = api
                    .column(7)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                ppnjual = api
                    .column(8)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                diskonjual = api
                    .column(9)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                totalhj = api
                    .column(10)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                laba = api
                    .column(11)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Total over this page
                pagetotalhb = api
                    .column(5, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagesubtotalhj = api
                    .column(7, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pageppnjual = api
                    .column(8, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagediskonjual = api
                    .column(9, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagetotalhj = api
                    .column(10, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                pagelaba = api
                    .column(11, { page: 'current' })
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
        
                // Update footer
                api.column(1).footer().innerHTML = 'SUB TOTAL :';
                api.column(5).footer().innerHTML = formatAngka(pagetotalhb,'');  
                api.column(7).footer().innerHTML = formatAngka(pagesubtotalhj,'');  
                api.column(8).footer().innerHTML = formatAngka(pageppnjual,'');  
                api.column(9).footer().innerHTML = formatAngka(pagediskonjual,'');  
                api.column(10).footer().innerHTML = formatAngka(pagetotalhj,'');  
                api.column(11).footer().innerHTML = formatAngka(pagelaba,'');  

            },

            processing: true,
            serverSide: true,
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualanlainpertanggal')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'tglstatus', name: 'tglstatus', className: 'dt-center' },
                { data: 'jmlrecord', name: 'jmlrecord', className: 'dt-center' },
                { data: 'qty', name: 'qty', className: 'dt-center' },
                { data: 'hbs', name: 'hbs', className: 'dt-right' },
                { data: 'totalhb', name: 'totalhb', className: 'dt-right' },
                { data: 'hjs', name: 'hjs', className: 'dt-right' },
                { data: 'subtotalhj', name: 'subtotalhj', className: 'dt-right' },
                { data: 'ppnjual', name: 'ppnjual', className: 'dt-right' },
                { data: 'diskonjual', name: 'diskonjual', className: 'dt-right' },
                { data: 'totalhj', name: 'totalhj', className: 'dt-right' },
                { data: 'laba', name: 'laba', className: 'dt-right' },
            ]
        });
    }


    function tampil_dataTable(){        
        penjualansajadetail1Datatable.draw(null, false);        
        penjualansajaperitem1Datatable.draw(null, false);        
        penjualansajapercustomer1Datatable.draw(null, false);        
        penjualansajaperfaktur1Datatable.draw(null, false);        
        penjualansajaperjenispembayaran1Datatable.draw(null, false);        
        penjualansajapertanggal1Datatable.draw(null, false);        
        
        penjualanfifodetail1Datatable.draw(null, false);        
        penjualanfifoperitem1Datatable.draw(null, false);        
        penjualanfifopercustomer1Datatable.draw(null, false);        
        penjualanfifoperfaktur1Datatable.draw(null, false);        
        penjualanfifoperjenispembayaran1Datatable.draw(null, false);        
        penjualanfifopertanggal1Datatable.draw(null, false);  

        penjualanmovadetail1Datatable.draw(null, false);        
        penjualanmovaperitem1Datatable.draw(null, false);        
        penjualanmovapercustomer1Datatable.draw(null, false);        
        penjualanmovaperfaktur1Datatable.draw(null, false);        
        penjualanmovaperjenispembayaran1Datatable.draw(null, false);        
        penjualanmovapertanggal1Datatable.draw(null, false);    

        penjualanlifodetail1Datatable.draw(null, false);        
        penjualanlifoperitem1Datatable.draw(null, false);        
        penjualanlifopercustomer1Datatable.draw(null, false);        
        penjualanlifoperfaktur1Datatable.draw(null, false);        
        penjualanlifoperjenispembayaran1Datatable.draw(null, false);        
        penjualanlifopertanggal1Datatable.draw(null, false); 

        penjualanlaindetail1Datatable.draw(null, false);        
        penjualanlainperitem1Datatable.draw(null, false);        
        penjualanlainpercustomer1Datatable.draw(null, false);        
        penjualanlainperfaktur1Datatable.draw(null, false);        
        penjualanlainperjenispembayaran1Datatable.draw(null, false);        
        penjualanlainpertanggal1Datatable.draw(null, false);        
    }

    function koneksi_datatable(){
        penjualansajadetail1Datatable = tampil_penjualansajadetail1();    
        penjualansajaperitem1Datatable = tampil_penjualansajaperitem1();    
        penjualansajapercustomer1Datatable = tampil_penjualansajapercustomer1();    
        penjualansajaperfaktur1Datatable = tampil_penjualansajaperfaktur1(); 
        penjualansajaperjenispembayaran1Datatable = tampil_penjualansajaperjenispembayaran1(); 
        penjualansajapertanggal1Datatable = tampil_penjualansajapertanggal1(); 

        penjualanfifodetail1Datatable = tampil_penjualanfifodetail1();    
        penjualanfifoperitem1Datatable = tampil_penjualanfifoperitem1();    
        penjualanfifopercustomer1Datatable = tampil_penjualanfifopercustomer1();    
        penjualanfifoperfaktur1Datatable = tampil_penjualanfifoperfaktur1(); 
        penjualanfifoperjenispembayaran1Datatable = tampil_penjualanfifoperjenispembayaran1(); 
        penjualanfifopertanggal1Datatable = tampil_penjualanfifopertanggal1(); 

        penjualanmovadetail1Datatable = tampil_penjualanmovadetail1();    
        penjualanmovaperitem1Datatable = tampil_penjualanmovaperitem1();    
        penjualanmovapercustomer1Datatable = tampil_penjualanmovapercustomer1();    
        penjualanmovaperfaktur1Datatable = tampil_penjualanmovaperfaktur1(); 
        penjualanmovaperjenispembayaran1Datatable = tampil_penjualanmovaperjenispembayaran1(); 
        penjualanmovapertanggal1Datatable = tampil_penjualanmovapertanggal1(); 

        penjualanlifodetail1Datatable = tampil_penjualanlifodetail1();    
        penjualanlifoperitem1Datatable = tampil_penjualanlifoperitem1();    
        penjualanlifopercustomer1Datatable = tampil_penjualanlifopercustomer1();    
        penjualanlifoperfaktur1Datatable = tampil_penjualanlifoperfaktur1(); 
        penjualanlifoperjenispembayaran1Datatable = tampil_penjualanlifoperjenispembayaran1(); 
        penjualanlifopertanggal1Datatable = tampil_penjualanlifopertanggal1(); 

        penjualanlaindetail1Datatable = tampil_penjualanlaindetail1();    
        penjualanlainperitem1Datatable = tampil_penjualanlainperitem1();    
        penjualanlainpercustomer1Datatable = tampil_penjualanlainpercustomer1();    
        penjualanlainperfaktur1Datatable = tampil_penjualanlainperfaktur1(); 
        penjualanlainperjenispembayaran1Datatable = tampil_penjualanlainperjenispembayaran1(); 
        penjualanlainpertanggal1Datatable = tampil_penjualanlainpertanggal1(); 
    }

   $('#btn_caricustomer1').on('click',function(){
        setTimeout(() => {
            var x = $('#event1').val();
            if(x=='0'){
                $('#tgltransaksi1').change();
            } 
            $('#ModalCariCustomer').modal('show');						
        }, 50);
    });

    tampil_caricustomer();
    function tampil_caricustomer(){
       let i = 1;	
       return $('#customer').DataTable({
           responsive : true,
           retrieve: true,
           autoWidth : true,
           buttons : [ {extend: 'colvis', postfixButtons: [ 'colvisRestore' ] }, {extend:'copy'}, {extend:'csv'}, {extend: 'pdf', orientation: 'portrait', pageSize: 'A4', title:'{{ $caption }}'}, {extend: 'excel', title: '{{ $caption }}'}, {extend:'print', orientation: 'portrait', pageSize: 'A4', title: '{{ $caption }}'}, ],        
           dom: 'lfrtip',
           lengthMenu: [
               [ 10, 25, 50, 100, 500, 1000, 5000, -1 ],
               [ '10', '25', '50', '100', '500','1000','5000', 'All' ]
           ],
           processing: true,
           serverSide: true,
           ajax   : `{{route('pos01.transaksi.bkeluar_showanggota')}}`,
           columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                   orderable: false, 
                   searchable: false },
                { data: 'ecard', name: 'ecard', className: 'dt-center' },
                { data: 'nia', name: 'nia', className: 'dt-center' },
                { data: 'ktp', name: 'ktp', className: 'dt-center' },
                { data: 'nama', name: 'nama'},
                { data: 'lembaga', name: 'anggota.lembaga'},
                { data: 'telp', name: 'telp'},
               
           ]
       });
    }

    $('#show_customer').on('click','.item_ecard',function(){ 
        ambilcaricustomer(this);        
    });
    $('#show_customer').on('click','.item_nia',function(){        
        ambilcaricustomer(this);        
    });
    $('#show_customer').on('click','.item_ktp',function(){        
        ambilcaricustomer(this);        
    });
    $('#show_customer').on('click','.item_nama',function(){        
        ambilcaricustomer(this);        
    });

    function ambilcaricustomer(t){
        var data1 = $(t).attr('data1');
        var data2 = $(t).attr('data2');
        var data3 = $(t).attr('data3');
        var data4 = $(t).attr('data4');
        var data5 = $(t).attr('data5');
        $('#id1').val(data1);
        $('#idcustomer1').val(data1);
        var a = $('#idruang1 option:selected').text();
        var x = $('#idcustomer1 option:selected').text();
        var y = x.trim();
        var z = '<p title="Cari spesifik data pada kolom search ...">Data yang dipilih tidak terdapat pada</br><b>'+a+'</b></p>';
        var k = $('#komen1').html(z);
        $('#event1').val('1');
        
        setTimeout(() => {
           cekcustomer();           
           
        }, 200);

        setTimeout(() => {
            var jmlx = $('#cek1').val();
            if(jmlx=='0'){
                swalgagalpilih(z); 
            }else{
                kirimsyarat();           
                $('#ModalCariCustomer').modal('hide');
            }
            
        }, 200);

    }

    function cekcustomer(){        
        var idcus1=$('#id1').val();

        $.ajax({
            enctype: 'multipart/form-data',
            type   : 'post',
            url    : '{{route('pos01.laporan.penjualan_cekcustomer')}}',
            async : false,
            dataType : 'json',
            // data : FormData,
            data : {idcus1:idcus1},
            // cache: false,
            // processData: false,
            // contentType: false,
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },				 				
            success : function(data){ 
                var resultData = data.data;	                
                    $('#cek1').val(resultData[0].jmlx);                                        
                },
            error : function(data){ 
                
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