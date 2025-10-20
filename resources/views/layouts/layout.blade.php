<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bagaskara</title>

  <!-- Bootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('vendor/bootstrap-4.5.3-dist/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free-5.15.4-web/css/all.min.css') }}">
  <!-- Google Font: Inter -->
  <link rel="stylesheet" href="{{ asset('fonts/inter/fonts.css') }}">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/index.css') }}" />
  <link rel="shortcut icon" href="{{ asset('images/website/nobglogo2.png') }}" />

  <style>
    body {
      font-family: 'Inter', sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .offcanvas-menu {
      position: fixed;
      top: 0;
      right: -250px;
      width: 250px;
      height: 100%;
      background: #fff;
      box-shadow: -2px 0 8px rgba(0,0,0,0.1);
      padding: 20px;
      transition: right 0.3s ease;
      z-index: 1050;
    }
    .offcanvas-menu.show {
      right: 0;
    }

    footer {
      background: #f8f9fa;
      padding: 15px 0;
      margin-top: auto;
      text-align: center;
      border-top: 1px solid #e5e5e5;
    }
    footer small {
      color: #6c757d;
      font-size: 13px;
    }
    footer a {
      color: #795548;
      font-weight: 600;
      text-decoration: none;
    }
    footer a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-light bg-white shadow-sm sticky-top">
    <div class="container d-flex justify-content-between">
      <a href="/{{ $table->id }}" class="navbar-brand">Kopi Bagaskara</a>
      <div>
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
    </ul>
  </div>

  <!-- Content -->
  <div class="container py-3">
    @yield('content')
  </div>

  <!-- Footer -->
  <footer>
    <small>
      &copy; {{ date('Y') }} <a href="#">Kopi Kampus Bagaskara</a>
    </small>
  </footer>

  <!-- JS -->
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
