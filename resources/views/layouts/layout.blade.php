<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bagaskara</title>
  <!-- Bootstrap 4 -->
  {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> --}}
  <link rel="stylesheet" href="{{ asset('vendor/bootstrap-4.5.3-dist/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> --}}
  <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free-5.15.4-web/css/all.min.css') }}">
  <!-- Google Font: Inter -->
  {{-- <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"> --}}
  <link rel="stylesheet" href="{{ asset('fonts/inter/fonts.css') }}">

  <link rel="stylesheet" href="{{ asset('css/index.css') }}" />
  <link rel="shortcut icon" href="{{ asset('images/website/nobglogo2.png') }}" />


  <style>
    
  </style>
</head>
<body>
    <!-- Navbar -->
  <nav class="navbar navbar-light bg-white shadow-sm sticky-top">
    <div class="container d-flex justify-content-between">
      <a href="/{{ $table->id }}" class="navbar-brand">Kopi Bagaskara</a>
      <div>
        {{-- <i class="fas fa-search mr-3 text-brown"></i> --}}
        <i class="fas fa-bars text-brown" id="menuToggle"></i>
      </div>
    </div>
  </nav>

  <!-- Offcanvas menu -->
  <div id="offcanvasMenu" class="offcanvas-menu">
    <button class="close mb-3" id="closeMenu">&times;</button>
    <h6 class="font-weight-bold text-brown">Menu</h6>
    <ul class="list-unstyled mt-3">
      <li><a href="/{{ $table->id }}" class="d-block py-2 text-brown">Beranda</a></li>
      <li><a href="/orders/status/{{ $table->id }}" class="d-block py-2 text-brown">Status Order</a></li>
      {{-- <li><a href="#" class="d-block py-2 text-brown">Promo</a></li> --}}
      {{-- <li><a href="#" class="d-block py-2 text-brown">Reservasi</a></li> --}}
    </ul>
  </div>
  <div class="container">
    @yield('content')
  </div>
    
  {{-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> --}}
  <script src="{{ asset('vendor/jquery-3.5.1/jquery-3.5.1.slim.min.js') }}"></script>
  <script src="{{ asset('vendor/bootstrap-4.5.3-dist/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('js/index.js') }}"></script>
  <script>
    // Toggle menu
  document.getElementById("menuToggle").addEventListener("click", () => {
    document.getElementById("offcanvasMenu").classList.add("show");
  });
  document.getElementById("closeMenu").addEventListener("click", () => {
    document.getElementById("offcanvasMenu").classList.remove("show");
  });
  </script>
</body>
</html>