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
                        <a class="nav-link" id="tab-penjualansajapersupplier" data-toggle="pill" href="#isi-tab-penjualansajapersupplier" role="tab" aria-controls="tab-penjualansajapersupplier" aria-selected="false">Penjualan Per Supplier</a>
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

                    <!--tab-penjualansajapersupplier -->
                    <div class="tab-pane fade" id="isi-tab-penjualansajapersupplier" role="tabpanel" aria-labelledby="tab-penjualansajapersupplier">
                        <div id="reload" class="table-responsive">
                            <table id="penjualansajapersupplier1" class="table table-bordered table-striped table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:10px;">#</th>                            
                                        <th style="width:10px">Kode</th>
                                        <th style="width:200px">Supplier</th>							
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
                                <tfoot id="show_footerpenjualansajapersupplier1">
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
                                <tbody id="show_penjualansajapersupplier1">
                                
                                </tbody>
                            </table>            
                        </div>
                    </div>
                    <!--/tab-penjualansajapersupplier -->

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
                upenjuaanfifo
            </div>
            <!--/tab-upenjualanfifo -->
            
            <!--tab-upenjualanmova -->
            <div class="tab-pane fade" id="isi-tab-upenjualanmova" role="tabpanel" aria-labelledby="tab-upenjualanmova">
                upenjuaanmova
            </div>
            <!--/tab-upenjualanmova -->
            
            <!--tab-upenjualanlifo -->
            <div class="tab-pane fade" id="isi-tab-upenjualanlifo" role="tabpanel" aria-labelledby="tab-upenjualanlifo">
                upenjuaanlifo
            </div>
            <!--/tab-upenjualanlilo -->
            
            <!--tab-upenjualanlain -->
            <div class="tab-pane fade" id="isi-tab-upenjualanlain" role="tabpanel" aria-labelledby="tab-upenjualanlain">
                upenjuaanlain
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
    var penjualansajapersupplier1Datatable;
    var penjualansajaperfaktur1Datatable;
    var penjualansajaperjenispembayaran1Datatable;
    var penjualansajapertanggal1Datatable;

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
    $('#tab-penjualansajapersupplier').on('click',function(){
        $('#tabpenjualansub1').val('tab-penjualansajapersupplier');
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

    function tampil_penjualansajapersupplier1(){
        let i = 1;	
        return $('#penjualansajapersupplier1').DataTable({
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
            ajax   : `{{route('pos01.laporan.penjualan_showpenjualansajapersupplier')}}`,
            columns: [
                // { data: 'no', name:'id', render: function (data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }},
                {  "data": 'DT_RowIndex',
                    orderable: false, 
                    searchable: false },
                { data: 'kode', name: 'supplier.kode', className: 'dt-center' },
                { data: 'supplier', name: 'supplier.supplier', className: 'dt-left' },
                { data: 'alamat', name: 'supplier.alamat', className: 'dt-left' },
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

    function tampil_dataTable(){        
        penjualansajadetail1Datatable.draw(null, false);        
        penjualansajaperitem1Datatable.draw(null, false);        
        penjualansajapersupplier1Datatable.draw(null, false);        
        penjualansajaperfaktur1Datatable.draw(null, false);        
        penjualansajaperjenispembayaran1Datatable.draw(null, false);        
        penjualansajapertanggal1Datatable.draw(null, false);        
    }

    function koneksi_datatable(){
        penjualansajadetail1Datatable = tampil_penjualansajadetail1();    
        penjualansajaperitem1Datatable = tampil_penjualansajaperitem1();    
        penjualansajapersupplier1Datatable = tampil_penjualansajapersupplier1();    
        penjualansajaperfaktur1Datatable = tampil_penjualansajaperfaktur1(); 
        penjualansajaperjenispembayaran1Datatable = tampil_penjualansajaperjenispembayaran1(); 
        penjualansajapertanggal1Datatable = tampil_penjualansajapertanggal1(); 
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