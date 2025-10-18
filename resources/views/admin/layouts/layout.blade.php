<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin Bagaskara</title>


  <!-- plugins:css -->
  {{-- <link rel="stylesheet" href="{{ asset('vendor/cart/css/bootstrap.min.css') }}"> --}}
  {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous"> --}}
  <link rel="stylesheet" href="{{ asset('vendor/bootstrap-5.0.2-dist/css/bootstrap.min.css') }}">
  {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css" integrity="sha512-mR/b5Y7FRsKqrYZou7uysnOdCIJib/7r5QeJMFvLNHNhtye3xJp1TdJVPLtetkukFn227nKpXD9OjUc09lx97Q==" crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
  <link rel="stylesheet" href="{{ asset('vendor/star-admin/template/vendors/feather/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/star-admin/template/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/star-admin/template/vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/star-admin/template/vendors/typicons/typicons.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/star-admin/template/vendors/simple-line-icons/css/simple-line-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/star-admin/template/vendors/css/vendor.bundle.base.css') }}">
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="{{ asset('vendor/star-admin/template/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/star-admin/template/js/select.dataTables.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
  <link rel="stylesheet" href="{{ asset('css/tabslider.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/datatables-1.12.1/dataTables.bootstrap5.min.css') }}"> 
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('vendor/star-admin/template/css/vertical-layout-light/style.css') }}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset('images/website/nobglogo2.png') }}" />
</head>
<body>
  

  <div class="container-scroller">
     @if (session('status'))
    <div class="row p-0 m-0 proBanner" id="proBanner">
      <div class="col-md-12 p-0 m-0">
        <div class="card-body card-body-padding d-flex align-items-center justify-content-between">
          <div class="ps-lg-1">
            <div class="d-flex align-items-center justify-content-between">
              <p class="mb-0 font-weight-medium me-3 buy-now-text">{{ session('status') }}</p>
              {{-- <p class="mb-0 font-weight-medium me-3 buy-now-text">wkwkwkk</p> --}}
            </div>
          </div>
          <div class="d-flex align-items-center justify-content-between">
            <a href="#"><i class="mdi mdi-home me-3 text-white"></i></a>
            <button id="bannerClose" class="btn border-0 p-0">
              <i class="mdi mdi-close text-white me-0"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
    @endif
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row ">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
            <span class="icon-menu"></span>
          </button>
        </div>
        <div>
          <a class="navbar-brand" href="/admin">
            <img src="{{ asset('images/website/nobglogo1.png') }}" alt="" width="150%">
          </a>
        </div>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-top"> 
        <ul class="navbar-nav">
          <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
            <h1 class="welcome-text">Hallo!! <span class="text-black fw-bold">{{ Auth::user()->name }}</span></h1>
            <h3 class="welcome-sub-text">Semangat Untuk Hari Ini :) </h3>
          </li>
        </ul>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item d-none d-lg-block">
            <div id="datepicker-popup" class="input-group date datepicker navbar-date-picker">
              <span class="input-group-addon input-group-prepend border-right">
                <span class="icon-calendar input-group-text calendar-icon"></span>
              </span>
              <input type="text" class="form-control">
            </div>
          </li>
          {{-- <li class="nav-item">
            <form class="search-form" action="#">
              <i class="icon-search"></i>
              <input type="search" class="form-control" placeholder="Search Here" title="Search here">
            </form>
          </li> --}}
            @php
                $orders = App\Models\Order::where('status', '=', '0')->orderBy('created_at', 'desc')->get();
            @endphp
          <li class="nav-item dropdown">
            <a class="nav-link count-indicator" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
              @if ($orders->count() > 0)
                <p class="text-dark fw-bold rounded-pill bg-danger text-white" style="margin: 10px -12px -10px 12px; " >{{ $orders->count() }}</p>                  
              @endif
              <i class="icon-bell"></i>
            </a>
            
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="notificationDropdown">
              <a class="dropdown-item py-3 border-bottom">
                <p class="mb-0 font-weight-medium float-left">{{ $orders->count() }} Pesanan Masuk </p>
              </a>
              @foreach ($orders as $data)
              <a href="/admin/order/online/{{ $data->customer->id }}" class="dropdown-item preview-item py-3">
                <div class="preview-thumbnail">
                  <i class="mdi mdi-food m-auto text-primary"></i>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject fw-normal text-dark mb-1">Pesanan meja {{ $data->customer->no_table }} masuk</h6>
                  <p class="fw-light small-text mb-0"> {{ $data->created_at->diffForHumans(); }}</p>
                </div>
              </a>
              @endforeach
              

            </div>
          </li>

          <li class="nav-item dropdown d-none d-lg-block user-dropdown">
            <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
              <img class="img-xs rounded-circle" src="{{ asset('images/user/' . Auth::user()->image) }}" alt="Profile image"> </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
              <div class="dropdown-header text-center">
                <img class="img-md rounded-circle" src="{{ asset('images/user/' . Auth::user()->image) }}" alt="Profile image">
                <p class="mb-1 mt-3 font-weight-semibold">{{ Auth::user()->name }}</p>
              </div>
              <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> Profile <span class="badge badge-pill badge-danger">1</span></a>
              <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-message-text-outline text-primary me-2"></i> Pesan</a>
              <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-calendar-check-outline text-primary me-2"></i> Aktivitas</a>
              <a class="dropdown-item"><i class="dropdown-item-icon mdi mdi-help-circle-outline text-primary me-2"></i> FAQ</a>
              <a  class="dropdown-item"><i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Logout</a>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      <div class="theme-setting-wrapper">
        <div id="settings-trigger"><i class="ti-settings"></i></div>
        <div id="theme-settings" class="settings-panel">
          <i class="settings-close ti-close"></i>
          <p class="settings-heading">SIDEBAR SKINS</p>
          <div class="sidebar-bg-options selected" id="sidebar-light-theme"><div class="img-ss rounded-circle bg-light border me-3"></div>Light</div>
          <div class="sidebar-bg-options" id="sidebar-dark-theme"><div class="img-ss rounded-circle bg-dark border me-3"></div>Dark</div>
          <p class="settings-heading mt-2">HEADER SKINS</p>
          <div class="color-tiles mx-0 px-4">
            <div class="tiles success"></div>
            <div class="tiles warning"></div>
            <div class="tiles danger"></div>
            <div class="tiles info"></div>
            <div class="tiles dark"></div>
            <div class="tiles default"></div>
          </div>
        </div>
      </div>
      
      <!-- partial -->
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas p-2" id="sidebar">
        <ul class="nav">
          
          <li class="nav-item nav-category">Admin</li>
          <li class="nav-item">
            <a class="nav-link" href="/admin/">
              <i class="menu-icon mdi mdi-account "></i>
              <span class="menu-title">Welcome</span>
            </a>
          </li>
          
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#Toko" aria-expanded="false" aria-controls="Toko">
              <i class="menu-icon mdi mdi-store "></i>
              <span class="menu-title">Toko</span>
              <i class="menu-arrow"></i> 
            </a>
            <div class="collapse" id="Toko">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="/admin/sliders/">Banner</a></li>
                <li class="nav-item"> <a class="nav-link" href="/admin/meja/">Meja</a></li>
              </ul>
            </div>
          </li>
          
          <li class="nav-item nav-category">Pesanan dan Transaksi</li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#Pesanan" aria-expanded="false" aria-controls="Pesanan">
              <i class="menu-icon mdi  mdi mdi-shopping  "></i>
              <span class="menu-title">Pesanan</span>
              <i class="menu-arrow"></i> 
            </a>
            <div class="collapse" id="Pesanan">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="/admin/order/create">Buat Pesanan</a></li>
                <li class="nav-item"> <a class="nav-link" href="/admin/order/manual">Pesanan Kasir</a></li>
                <li class="nav-item"> <a class="nav-link" href="/admin/order/online">Pesanan Pelanggan</a></li>
                {{-- <li class="nav-item"> <a class="nav-link" href="/admin/table">Meja</a></li> --}}
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <i class="menu-icon  mdi mdi-clipboard-outline  "></i>
              <span class="menu-title">Transaksi</span>
              <i class="menu-arrow"></i> 
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="/admin/transaction/">Riwayat Transaksi</a></li>
                <li class="nav-item"> <a class="nav-link" href="/admin/transaction/summary/1">Rekap Kas</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item nav-category">Stok dan Produk</li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
              <i class="menu-icon mdi  mdi mdi-food "></i>
              <span class="menu-title">Produk</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="form-elements">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="/admin/product/food">Makanan</a></li>
              </ul>
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="/admin/product/drink">Minuman</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/admin/stock/">
              <i class="mdi mdi-food-variant menu-icon"></i>
              <span class="menu-title">Stok</span>
            </a>
          </li>
          <li class="nav-item nav-category">Promo</li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#disc" aria-expanded="false" aria-controls="disc">
              <i class="menu-icon mdi mdi-percent  "></i>
              <span class="menu-title">Diskon dan Voucer</span>
              <i class="menu-arrow"></i> 
            </a>
            <div class="collapse" id="disc">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="/admin/operator/discount">Diskon</a></li>
                <li class="nav-item"> <a class="nav-link" href="/admin/operator/voucher">Voucer</a></li>
              </ul>
            </div>
          </li>
          {{-- OPERATOR --}}
          @if (Auth::user()->role == 1)
          

          <li class="nav-item nav-category">Laporan</li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#operator" aria-expanded="false" aria-controls="operator">
              <i class="menu-icon mdi mdi-file-document  "></i>
              <span class="menu-title">Laporan</span>
              <i class="menu-arrow"></i> 
            </a>
            <div class="collapse" id="operator">
              <ul class="nav flex-column sub-menu">
                {{-- <li class="nav-item"> <a class="nav-link" href="/admin/operator/report/0">Laporan Penjualan</a></li> --}}
                <li class="nav-item"> <a class="nav-link" href="/admin/operator/reports/">Laporan Penjualan</a></li>
                <li class="nav-item"> <a class="nav-link" href="/admin/operator/customer">Data Pelanggan</a></li>
                <li class="nav-item"> <a class="nav-link" href="/admin/operator/employee">Data Karyawan</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/admin/logs/">
              <i class="menu-icon mdi mdi-history "></i>
              <span class="menu-title">Logs</span>
            </a>
          </li>
          @endif
          {{-- OPERATOR --}}
          
          <li class="nav-item nav-category">User</li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="menu-icon  mdi mdi-arrow-left "></i>
              <span class="menu-title">Logout</span>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
              </form>
            </a>
          </li> 
        </ul>
      </nav>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper bg-white rounded">

          {{-- MAIN CONTENT --}}
          @yield('content')
          {{-- END OF CONTENT --}}

        </div>
        <!-- content-wrapper ends -->

        <!-- partial:partials/_footer.html -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Copyright © Mukti Wiratama 2022. All rights reserved.</span>
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
 {{-- plugin cart --}}
 <script src="{{ asset('js/tabslider.js') }}"></script>
 <script src="{{ asset('vendor/bootstrap-4.5.3-dist/bootstrap.bundle.min.js') }}" ></script>

  <!-- plugins:js -->
  <script src="{{ asset('vendor/jquery-3.6.0/jquery-3.6.0.min.js') }}"></script>
  <script src="{{ asset('vendor/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js') }}"></script>
  {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js" integrity="sha512-FHZVRMUW9FsXobt+ONiix6Z0tIkxvQfxtCSirkKc5Sb4TKHmqq1dZa8DphF0XqKb3ldLu/wgMa8mT6uXiLlRlw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
  
  <script type="text/javascript" charset="utf8" src="{{ asset('vendor/datatables-1.12.1/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('vendor/datatables-1.12.1/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('vendor/datatables-1.12.1/dataTables.bootstrap5.min.js') }}"></script>
  
  {{-- <script src="{{ asset('vendor/star-admin/template/vendors/js/vendor.bundle.base.js') }}"></script> --}}
  <!-- endinject -->

  <!-- Plugin js for this page -->
  <script src="{{ asset('vendor/star-admin/template/vendors/chart.js/Chart.min.js') }}"></script>
  <script src="{{ asset('vendor/star-admin/template/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ asset('vendor/star-admin/template/vendors/progressbar.js/progressbar.min.js') }}"></script>

  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="{{ asset('vendor/star-admin/template/js/off-canvas.js') }}"></script>
  <script src="{{ asset('vendor/star-admin/template/js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('vendor/star-admin/template/js/template.js') }}"></script>
  <script src="{{ asset('vendor/star-admin/template/js/settings.js') }}"></script>
  <script src="{{ asset('vendor/star-admin/template/js/todolist.js') }}"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="{{ asset('vendor/star-admin/template/js/jquery.cookie.js') }}" type="text/javascript"></script>
  <script src="{{ asset('vendor/star-admin/template/js/dashboard.js') }}"></script>
  <script src="{{ asset('vendor/star-admin/template/js/Chart.roundedBarCharts.js') }}"></script>
  <!-- End custom js for this page-->
  {{-- data table --}}
  <script>
    $(document).ready( function () {
    $('#saleTable').DataTable();
} );
  </script>
  {{-- tab scroller  --}}
  <script>
    var hidWidth;
    var scrollBarWidths = 40;

    var widthOfList = function(){
    var itemsWidth = 0;
    $('.list li').each(function(){
        var itemWidth = $(this).outerWidth();
        itemsWidth+=itemWidth;
      });
      return itemsWidth;
    };

    var widthOfHidden = function(){
      return (($('.wrapper').outerWidth())-widthOfList()-getLeftPosi())-scrollBarWidths;
    };

    var getLeftPosi = function(){
      return $('.list').position().left;
    };

    var reAdjust = function(){
      if (($('.wrapper').outerWidth()) < widthOfList()) {
        $('.scroller-right').show();
      }
      else {
        $('.scroller-right').hide();
      }
      
      if (getLeftPosi()<0) {
        $('.scroller-left').show();
      }
      else {
        $('.item').animate({left:"-="+getLeftPosi()+"px"},'slow');
        $('.scroller-left').hide();
      }
    }

    reAdjust();

    $(window).on('resize',function(e){  
        reAdjust();
    });

    $('.scroller-right').click(function() {
      
      $('.scroller-left').fadeIn('slow');
      $('.scroller-right').fadeOut('slow');
      
      $('.list').animate({left:"+="+widthOfHidden()+"px"},'slow',function(){

      });
    });

    $('.scroller-left').click(function() {
      
      $('.scroller-right').fadeIn('slow');
      $('.scroller-left').fadeOut('slow');
      
        $('.list').animate({left:"-="+getLeftPosi()+"px"},'slow',function(){
        
        });
    });    
  </script>
  {{-- kembalian --}}
  <script>
    $(function () {
    $("input").blur(function () {
      $("#kembalian").val(function () {
        var i1 = parseFloat($("#cash").val());
        var i2 = parseFloat($("#debit").val());
        var total_price = parseFloat($("#total_price").val());
        i1 = isNaN(i1) ? 0 : i1;
        i2 = isNaN(i2) ? 0 : i2;
        return (i1 + i2) - total_price;
      });
      $("#cash").val(function () {
        var cash = parseFloat($("#cash").val());
        cash = isNaN(cash) ? 0 : cash;
        return cash;
      });
      $("#debit").val(function () {
        var debit = parseFloat($("#debit").val());
        debit = isNaN(debit) ? 0 : debit;
        return debit;
      });
    });
  });
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const paymentSelect = document.getElementById('payment_method');
      const paymentFields = document.getElementById('payment_fields');
      const cashInput = document.getElementById('cash');
      const kembalianWrapper = document.getElementById('kembalian_wrapper');
      const kembalianInput = document.getElementById('kembalian');
      const totalbayar = document.getElementById('total_price');

      paymentSelect.addEventListener('change', function () {
        const value = this.value;

        if (value === '') {
          // Belum memilih metode pembayaran
          paymentFields.classList.add('d-none');
          cashInput.value = '';
          kembalianWrapper.classList.remove('d-none');
          kembalianInput.value = '';
        } else {
          // Tampilkan form total dibayar
          paymentFields.classList.remove('d-none');

          if (value === 'debit') {
            // Debit: sembunyikan kembalian dan set nilainya ke 0
            kembalianWrapper.classList.add('d-none');
            kembalianInput.value = 0;
            cashInput.value = totalbayar.value;
          } else if (value === 'cash') {
            // Cash: tampilkan kembalian, reset nilainya
            kembalianWrapper.classList.remove('d-none');
            kembalianInput.value = '';
            cashInput.value = 0;
          }
        }
      });
    });
  </script>
  @stack('scripts') 
</body>

</html>

