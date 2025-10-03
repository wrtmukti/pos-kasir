<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Kedai Papaji</title>
  <!-- plugins:css -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css" integrity="sha512-mR/b5Y7FRsKqrYZou7uysnOdCIJib/7r5QeJMFvLNHNhtye3xJp1TdJVPLtetkukFn227nKpXD9OjUc09lx97Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <link rel="stylesheet" href=" {{ asset('vendor/star-admin/template/vendors/feather/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/star-admin/template/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/star-admin/template/vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/star-admin/template/vendors/typicons/typicons.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/star-admin/template/vendors/simple-line-icons/css/simple-line-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/star-admin/template/vendors/css/vendor.bundle.base.css') }}">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="{{ asset('vendor/star-admin/template/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/star-admin/template/js/select.dataTables.min.css') }}">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{ asset('vendor/star-admin/template/css/vertical-layout-light/style.css') }}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset('vendor/star-admin/template/images/favicon.png') }}" />
</head>
<body>
  
    @yield('content')

  <!-- plugins:js -->
  <script src="{{ asset('vendor/star-admin/template/vendors/js/vendor.bundle.base.js') }}"></script>
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
</body>

</html>

