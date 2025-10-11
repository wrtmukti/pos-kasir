@extends('layouts.layout')

@section('content')
<div class="container py-4">
    {{-- Alert Pesan --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger shadow-sm fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        </div>
    @endif

     <!-- Navbar -->
    {{-- <nav class="navbar navbar-light bg-white shadow-sm sticky-top">
      <div class="container d-flex justify-content-between">
        <span class="navbar-brand">Kopi Bagaskara</span>
        <div>
          <i class="fas fa-search mr-3 text-brown"></i>
          <i class="fas fa-bars text-brown" id="menuToggle"></i>
        </div>
      </div>
    </nav>
    
    <div id="offcanvasMenu" class="offcanvas-menu">
      <button class="close mb-3" id="closeMenu">&times;</button>
      <h6 class="font-weight-bold text-brown">Menu</h6>
      <ul class="list-unstyled mt-3">
        <li><a href="#" class="d-block py-2 text-brown">Beranda</a></li>
        <li><a href="#" class="d-block py-2 text-brown">Promo</a></li>
        <li><a href="#" class="d-block py-2 text-brown">Reservasi</a></li>
        <li><a href="#" class="d-block py-2 text-brown">Kontak</a></li>
      </ul>
    </div> --}}

    {{-- Judul --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="font-weight-bold text-brown mb-0">Status Pesanan Anda</h4>
        <small class="text-muted">Diperbarui {{ now()->format('d M Y, H:i') }}</small>
    </div>
    

    {{-- Card Container --}}
    <div class="card show shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <table class="table mb-0 align-middle text-center table-hover">
                <thead class="bg-brown text-white">
                    <tr>
                        <th class="py-3">Meja</th>
                        <th class="py-3">Status Pesanan</th>
                        <th class="py-3">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td class="fw-semibold text-brown py-3">{{ $order->customer->no_table ?? '-' }}</td>
                            <td class="py-3">
                                @switch($order->status)
                                    @case(0)
                                        <span class="badge rounded-pill bg-secondary text-white px-3 py-2">Menunggu Konfirmasi</span>
                                        @break
                                    @case(1)
                                        <span class="badge rounded-pill bg-warning text-dark px-3 py-2">Pesanan Diproses</span>
                                        @break
                                    @case(2)
                                        <span class="badge rounded-pill bg-info text-dark px-3 py-2">Menunggu Pembayaran</span>
                                        @break
                                    @case(3)
                                        <span class="badge rounded-pill bg-success px-3 py-2">Selesai</span>
                                        @break
                                    @case(4)
                                        <span class="badge rounded-pill bg-danger px-3 py-2">Pesanan Ditolak</span>
                                        @break
                                    @default
                                        <span class="badge rounded-pill bg-light text-dark px-3 py-2">Tidak Diketahui</span>
                                @endswitch
                            </td>
                            <td class="text-muted py-3">{{ $order->note ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-4 text-muted">
                                <i class="bi bi-inbox fs-4 d-block mb-1"></i>
                                Belum ada pesanan yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <a href="/" class="btn btn-brown shadow-sm w-100 mt-4 py-2">
        <i class="bi bi-house-door me-1"></i> Kembali ke Beranda
    </a>
</div>
@endsection
