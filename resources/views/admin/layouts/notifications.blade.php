@php
$orders = App\Models\Order::where('status', '=', '0')->orderBy('created_at', 'desc')->get();
@endphp

<a class="nav-link count-indicator" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
  @if ($orders->count() > 0)
    <p class="text-dark fw-bold rounded-pill bg-danger text-white" style="margin: 10px -12px -10px 12px; " >{{ $orders->count() }}</p>                  
  @endif
  <i class="icon-bell"></i>
</a>
<div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="notificationDropdown">
  <a class="dropdown-item py-3 border-bottom">
    <p class="mb-0 font-weight-medium float-left">{{ $orders->count() }} Pesanan Masuk</p>
  </a>
  @foreach ($orders as $data)
  <a href="/admin/order/online/{{ $data->customer->id }}" class="dropdown-item preview-item py-3">
    <div class="preview-thumbnail">
      <i class="mdi mdi-food m-auto text-primary"></i>
    </div>
    <div class="preview-item-content">
      <h6 class="preview-subject fw-normal text-dark mb-1">Pesanan meja {{ $data->customer->no_table }} masuk</h6>
      <p class="fw-light small-text mb-0">{{ $data->created_at->diffForHumans() }}</p>
    </div>
  </a>
  @endforeach
</div>