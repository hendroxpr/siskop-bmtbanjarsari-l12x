@php
  $username = auth()->user()->name;
  $jmllogin = auth()->user()->jmllogin;
  $foto = auth()->user()->foto;
  if($foto){
    $foto = auth()->user()->foto;
  }else{
    $foto = 'admin-users-foto/blank.png';
  }
  
@endphp

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('') }}/admin/" class="brand-link">
      <img src="{{ asset('assets/admin/dist/img/sistlogo.png') }}" alt="SIST Logo" class="brand-image img-circle elevation-3" style="opacity: .8;width:35px; height:35px; overflow:hidden;">
      <span class="brand-text font-weight-light">SIBT</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->

     
        <div class="user-panel d-flex">
          <div class="image mt-3 pb-3 mb-3 ">          
            <img src="{{ asset('storage/') }}/{{ $foto }}" class="img-circle elevation-2" alt="User Image" style="width:35px; height:35px; overflow:hidden;">
          </div>
          <div class="info">
              <a href="{{ url('') }}/admin/" class="d-block">{{ $username }}({{ $jmllogin }})</a>
              <a href="#"><i class="fa fa-circle text-success"></i> Online <b><span id="jam"></span></b></a>
          </div>
                   
          
        </div>



      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

            @php
              use App\Models\Menuutama;
              use App\Models\Menusub;
              
              $kunci1 = auth()->user()->kunci1;
              $kunci2 = auth()->user()->kunci2;
              
              $level = auth()->user()->levels;
              if ($level === 1) {
                  $menuutama= Menuutama::where([['adminmenu','=','Y']])
                              ->orderby('urutan')->get();
              }elseif ($level===2) {
                  $menuutama= Menuutama::where([['usermenu','=','Y']])
                              ->orderby('urutan')->get();
              }else {
                  $menuutama= Menuutama::where([['entrymenu','=','Y']])
                              ->orderby('urutan')->get();
              }   
            @endphp

            @foreach ($menuutama as $utama)
             
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-th-list"></i>
                  <p>
                    {{ $utama->namamenu }}
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
              
                <ul class="nav nav-treeview">
                  
                  @php
                    
                    $id=$utama->id;
                    
                    $menusub= Menusub::where([['idmainmenu','=',$id],['idaplikasi','>=',$kunci1],['idaplikasi','<=',$kunci2]]);
                    if ($level === 1) {
                        if($id === 1) {
                          $menusub= Menusub::where([['idmainmenu','=',$id]]);
                          $menusub = $menusub->where([['adminmenu','=','Y']])
                                      ->orderby('urutan')->get();
                        }else{
                          $menusub = $menusub->where([['adminmenu','=','Y']])
                                      ->orderby('urutan')->get();
                        }
                    }elseif ($level===2) {
                        $menusub = $menusub->where([['usermenu','=','Y']])
                                    ->orderby('urutan')->get();
                    }else {
                        $menusub = $menusub->where([['entrymenu','=','Y']])
                                    ->orderby('urutan')->get();
                    }   

                @endphp
                    
                  @foreach ($menusub as $sub)
                    <li class="nav-item">
                      <a href="{{ url('') }}{{ $sub->link }}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ $sub->namamenu }}</p>
                      </a>
                    </li>
                  @endforeach
                  
                </ul>
                
              </li>


            @endforeach


          <li class="nav-item">
            <a href="{{ route('admin.users_index') }}" class="nav-link">
              <i class="nav-icon fa fa-user"></i>                      
              <p>
                Edit Profile                
              </p>
            </a>
          </li>

        <li class="nav-item">
            <a href="{{ route('front.admin_logout') }}" class="nav-link">
              <i class="nav-icon fas fa-power-off"></i>                      
              <p>
                Logout               
              </p>
            </a>
        </li>

         
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <script type="text/javascript">

    //set date time zone
      "<?php date_default_timezone_set('Asia/Jakarta');?>";
      //time start
      var jamx = new Date("<?=session('awaljam');?>").getTime();    
      var jamr = new Date().getTime();

      myTimer=setInterval(startTimer,1000);
      function startTimer(){ 
          //time count up time every 1 second
          var jamy = new Date().getTime();
          var c=jamy-jamx;
          var ss=checkwaktu(Math.floor((c % (1000 * 60)) / 1000));        
          var mm=checkwaktu(Math.floor((c % (1000 * 60 * 60)) / (1000 * 60)));
          var hh=checkwaktu(Math.floor((c % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)));
          var dd=checkhari(Math.floor(c / (1000 * 60 * 60 * 24)));
          document.getElementById('jam').innerHTML=hh+":"+mm+":"+ss;        
      }
      
      function checkwaktu(x){
          if(x<10&&x>=0){x="0"+x};
          if(x<0){x="59"};
          return x;
      }
      
      function checkhari(x){
          if(x<10&&x>=0){x="0"+x};
          if(x<0){x="23"};
          return x;
      }
      
      $(document).ready(function(){
            startTimer();
      });
      
  </script>
