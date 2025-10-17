@php
    $meminstansi = session('memnamasingkat');
@endphp

<footer class="main-footer">
  <div class="float-right d-none d-sm-block">
    Developed by team IT {{ $meminstansi }}
  </div>
  <strong> 
      Copyright &copy; 2025-<?= date('Y'); ?> {{ $meminstansi }}
  </strong> 
  All rights reserved. Build v{{ Illuminate\Foundation\Application::VERSION }} 
  PHP v<?php print phpversion(); ?>
</footer>