<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>


    <link rel="icon" href="{{ asset('storage/') }}/{{ $instansi[0]->logo }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/front/plugins/bootstrap-5.2.2/css/bootstrap.min.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/front/css/font-awesome.min.css') }}"> 
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/front/plugins/toastr/toastr.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/front/plugins/datetimepicker/datetimepicker.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/front/plugins/jquery.smartmenus/jquery.smartmenus.bootstrap-4.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/front/plugins/jquery.smartmenus/sm-core.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/front/plugins/jquery.smartmenus/sm-clean.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/front/plugins/magnific-popup/magnific-popup.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/front/css/loading.css') }}">
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/front/css/login.style.css') }}"> 
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/front/themes/sky_light/style.css') }}">
    <script src="{{ asset('assets/front/js/frontend.min.js') }}"></script>



</head>
<body class="text-center" style="display:flex; align:center; justify-content:center;">
    @include('sweetalert::alert')

    <noscript>
        You need to enable javaScript to run this app.
    </noscript>
    
    <div class="col-md-5">
      <img class="mb-4" style="width: 20%" src="{{ asset('storage/') }}/{{ $instansi[0]->logo }}">
          <h2 class="font-weight-bold"><font style="color:#343a40;">{{ $instansi[0]->namasingkat }}</font></h2>
          <h6 class="font-weight-bold text-dark mb-4">{{ $instansi[0]->motto }}</h6>

        <form method="post" id="formLogin" name="formLogin" action="{{ route('front.admin_create') }}">
            @csrf
          <div class="form-floating">
            <input type="text" class="form-control @error('username2') is-invalid @enderror rounded-0" name="username2" id="username2" placeholder="User name" autofocus required value="{{ old('username2') }}">
            <label for="username2">User name</label>
            @error('username2')
              <div class="invalid-feedback">
                  {{ $message }}
              </div>
            @enderror

            <div class="form-floating">
              <input type="email2" class="form-control @error('email2') is-invalid @enderror rounded-0" name="email2" id="email2" placeholder="name@example.com" required value="{{ old('email2') }}">
              <label for="email2">Email address</label>
              @error('email2')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>
            <div class="form-floating">
              <input type="password" class="form-control @error('password2') is-invalid @enderror rounded-0" name="password2" id="password2" placeholder="Password" required>
              <label for="password2">Password</label>
              @error('')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>
            
          </div>
          <div class="form-floating">
            <input type="text" class="form-control @error('namadepan2') is-invalid @enderror rounded-0" name="namadepan2" id="namadepan2" placeholder="Nama depan" required value="{{ old('namadepan2') }}">
            <label for="namadepan2">Nama depan</label>
            @error('namadepan2')
              <div class="invalid-feedback">
                  {{ $message }}
              </div>
            @enderror
          </div>
          <div class="form-floating">
            <input type="text" class="form-control @error('namatengah2') is-invalid @enderror rounded-0" name="namatengah2" id="namatengah2" placeholder="Nama tengah2" required value="{{ old('namatengah2') }}">
            <label for="namatengah2">Nama tengah</label>
            @error('namatengah2')
              <div class="invalid-feedback">
                  {{ $message }}
              </div>
            @enderror
          </div>

            <div class="form-floating">
                    <input type="text" class="form-control @error('namabelakang2') is-invalid @enderror rounded-0" name="namabelakang2" id="namabelakang2" placeholder="Nama belakang2" required value="{{ old('namabelakang2') }}">
                    <label for="namabelakang2">Nama belakang</label>
                    @error('namabelakang2')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
            </div>

            <div class="form-floating">
                <select class="form-select rounded-0" id="idaplikasi2" name="idaplikasi2" aria-label="Floating label select example">                 
                </select>
                <label for="idaplikasi2">Unit</label>
            </div>
            
            <div class="py-0">
                <button type="submit" style="width: 100%; padding-top:15px; padding-bottom:15px;" class="btn btn-lg btn-primary btn-block rounded-0" id="submit">Register</button>
            </div>

        </form>

        <p class="pt-3 text-muted">
            Already Registered? <a class="text-decoration-none" href="{{ route('front.admin_loginform') }}">Login now</a>  | Back to <a class="text-decoration-none" href="{{ route('front.index') }}">SIBT</a>
         </p>

         <p class="pt-3 text-muted">
              <div class="float-right d-none d-sm-block">
                  Developed by team IT {{ session('memnamasingkat') }}
                </div>
                <strong> 
                    Copyright &copy; 2025-<?= date('Y'); ?> {{ session('memnamasingkat') }}
                </strong> 
                All rights reserved. Build v{{ Illuminate\Foundation\Application::VERSION }} 
                PHP v<?php print phpversion(); ?>               
          </p>

    </div>

    
<script type="text/javascript">

    $(document).ready(function(){
        tampil_listaplikasi(); 

        //menampilkan combo aplikasi
        function tampil_listaplikasi(){				
            $.ajax({
                type: 'get',
                url   : '{{route('front.admin_registerlistaplikasi')}}',
                
                success: function(data){				    
                    $("#idaplikasi2").html(data);                
                }
            })
                        
        }
       
    
    });
    </script>	
    


</body>
</html>