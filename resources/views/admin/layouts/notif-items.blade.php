{{-- Header --}}
<a class="dropdown-item py-3 border-bottom">
  <p class="mb-0 font-weight-medium float-left">
    Notifikasi ({{ $totalNotif }})
  </p>
</a>

{{-- Pesanan Masuk --}}
@if ($orders->count() > 0)
  <div class="dropdown-item border-bottom bg-light py-2">
    <strong>{{ $orders->count() }} Pesanan Masuk</strong>
  </div>
  @foreach ($orders as $data)
    <a href="/admin/order/online/{{ $data->customer->id }}" class="dropdown-item preview-item py-3">
      <div class="preview-thumbnail">
        <i class="mdi mdi-cart m-auto text-primary"></i>
      </div>
      <div class="preview-item-content">
        <h6 class="preview-subject fw-normal text-dark mb-1">
          Pesanan meja {{ $data->customer->no_table }} masuk
        </h6>
        <p class="fw-light small-text mb-0">{{ $data->created_at->diffForHumans() }}</p>
      </div>
    </a>
  @endforeach
@endif

{{-- Stok Mau Habis --}}
@if ($stocks->count() > 0)
  <div class="dropdown-item border-top bg-light py-2">
    <strong>{{ $stocks->count() }} Stok Akan Habis</strong>
  </div>
  @foreach ($stocks as $item)
    <a href="{{ Auth::user()->role == 1 ? '/admin/operator/stock/edit/'.$item->id : '/admin/stock/' }}" 
       class="dropdown-item preview-item py-3">
      <div class="preview-thumbnail">
        <i class="mdi mdi-food-variant m-auto text-danger"></i>
      </div>
      <div class="preview-item-content">
        <h6 class="preview-subject fw-normal text-dark mb-1">
          {{ $item->name }} tersisa {{ $item->amount }}
          @if ($item->unit == 1) Pcs
          @elseif ($item->unit == 2) Pack
          @elseif ($item->unit == 3) Gram
          @endif
        </h6>
        <p class="fw-light small-text mb-0">{{ $item->updated_at->diffForHumans() }}</p>
      </div>
    </a>
  @endforeach
@endif

{{-- Tidak ada notifikasi --}}
@if ($totalNotif == 0)
  <div class="dropdown-item py-3 text-center text-muted">
    Tidak ada notifikasi
  </div>
@endif
