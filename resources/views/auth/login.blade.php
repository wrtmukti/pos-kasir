<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Login || Bagaskara Admin</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{ asset('vendor/star-admin/template/vendors/feather/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/star-admin/template/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/star-admin/template/vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/star-admin/template/vendors/typicons/typicons.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/star-admin/template/vendors/simple-line-icons/css/simple-line-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/star-admin/template/vendors/css/vendor.bundle.base.css') }}">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('vendor/star-admin/template/css/vertical-layout-light/style.css') }}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset('images/website/nobglogo1.png') }}" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo">
                <img src="{{ asset('images/website/nobglogo1.png') }}" alt="Logo" class="logo">
              </div>
              <h4>Hallo! Kamu belum login</h4>
              <h6 class="fw-light">Login Untuk melanjutkan</h6>
              <form class="pt-3" method="POST" action="{{ route('login') }}">
                  @csrf

                  {{-- Email --}}
                  <div class="form-group mb-3">
                      <input type="email" 
                            id="email" 
                            class="form-control form-control-lg @error('email') is-invalid @enderror" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="email" 
                            autofocus 
                            placeholder="Masukkan Email">

                      {{-- error validation dari Laravel --}}
                      @error('email')
                          <small class="text-danger mt-1 d-block">{{ $message }}</small>
                      @enderror

                      {{-- pesan dari session jika login gagal --}}
                      @if (session('error') && str_contains(session('error'), 'Email'))
                          <small class="text-danger mt-1 d-block">{{ session('error') }}</small>
                      @endif
                  </div>

                  {{-- Password --}}
                  <div class="form-group mb-3">
                      <input type="password" 
                            class="form-control form-control-lg @error('password') is-invalid @enderror" 
                            id="password" 
                            placeholder="Password"  
                            name="password" 
                            required 
                            autocomplete="current-password">

                      @error('password')
                          <small class="text-danger mt-1 d-block">{{ $message }}</small>
                      @enderror

                      @if (session('error') && str_contains(session('error'), 'Password'))
                          <small class="text-danger mt-1 d-block">{{ session('error') }}</small>
                      @endif
                  </div>

                  {{-- Tombol --}}
                  <div class="mt-3">
                      <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" type="submit">
                          Login
                      </button>
                  </div>
              </form>

            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="{{ asset('vendor/star-admin/template/vendors/js/vendor.bundle.base.js') }}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="{{ asset('vendor/star-admin/template/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="{{ asset('vendor/star-admin/template/js/off-canvas.js') }}"></script>
  <script src="{{ asset('vendor/star-admin/template/js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('vendor/star-admin/template/js/template.js') }}"></script>
  <script src="{{ asset('vendor/star-admin/template/js/settings.js') }}"></script>
  <script src="{{ asset('vendor/star-admin/template/js/todolist.js') }}"></script>
  <!-- endinject -->
</body>

</html>
