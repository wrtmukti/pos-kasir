@php
$orders = App\Models\Order::where('status', '=', '0')->orderBy('created_at', 'desc')->get();
$stocks = App\Models\Stock::where('amount', '<=', '10')->orderBy('created_at', 'desc')->get();
$totalNotif = $orders->count() + $stocks->count();
@endphp

<a class="nav-link count-indicator" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
  <span class="notif-count text-dark fw-bold rounded-pill bg-danger text-white px-2 py-1 {{ $totalNotif > 0 ? '' : 'd-none' }}" 
        style="margin: 10px -12px -10px 12px;">{{ $totalNotif }}</span>                  
  <i class="icon-bell"></i>
</a>

<div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0 notif-list" 
     aria-labelledby="notificationDropdown" style="width: 300px;">

  @include('admin.layouts.notif-items', [
      'orders' => $orders,
      'stocks' => $stocks,
      'totalNotif' => $totalNotif
  ])

</div>