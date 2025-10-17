<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    {{-- <link rel="icon" href="{{ asset('storage/') }}/{{ $instansi[0]->logo }}"> --}}
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
    
            <form method="post" id="formLogin" name="formLogin" action="{{ route('front.admin_login') }}">
                @csrf              
                <div class="form-floating mb-0">
                    <input type="email" class="form-control @error('email') is-invalid @enderror rounded-0" name="email" id="email" placeholder="name@example.com" autofocus required value="{{ old('email') }}" autocomplete="on">
                    <label for="floatingInput">Email address</label>
                    @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control @error('password') is-invalid @enderror rounded-0" name="password" id="password" placeholder="Password" required>
                    <label for="floatingPassword">Password</label>
                    @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                
                <div class="py-0">
                    <button type="submit" style="width: 100%; padding-top:15px; padding-bottom:15px;" class="btn btn-lg btn-primary btn-block rounded-0" id="submit">Login</button>
                </div>

            </form>

            <p class="pt-3 text-muted">
                <a class="text-decoration-none" href="{{ route('front.admin_registerform') }}">Register now?</a> | Back to <a class="text-decoration-none" href="{{ route('front.index') }}">SIBT</a>
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
    
    



</body>
</html>