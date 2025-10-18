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

    {{-- Judul --}}
    <div class="row mb-3 justify-content-center">
        <h5 class="font-weight-bold text-brown mb-0">Status Pesanan Anda</h5><br>
        <small class="text-muted">Diperbarui {{ now()->format('d M Y, H:i') }}</small>
    </div>
    

    {{-- Card Container --}}
    <div class="card show shadow-sm border-0 rounded-1">
        <div class="card-body p-0">
            <table class="table mb-0 align-middle text-center table-hover">
                <thead class="bg-brown text-white">
                    <tr>
                        <th class="py-3">No</th>
                        <th class="py-3">Pembayaran</th>
                        <th class="py-3">Status Pesanan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td class="fw-semibold text-brown py-3">{{ $loop->iteration }}</td>
                            <td class="text-muted py-3">
                                Rp {{ number_format($order->total_payment, 0, ',', '.') }}
                            </td>
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
                                        <span class="">"{{ $order->note }}"</span>
                                        @break
                                    @default
                                        <span class="badge rounded-pill bg-light text-dark px-3 py-2">Tidak Diketahui</span>
                                @endswitch
                            </td>
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

                @if($orders->count() > 0)
                    <tfoot>
                        <tr class="bg-light">
                            <td colspan="2" class="fw-bold text-end text-brown py-3">Total Pembayaran:</td>
                            <td class="fw-bold  py-3">
                                Rp {{ number_format($orders->where('status', '<>', 4)->sum('total_payment'), 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>

        </div>
    </div>

    <a href="/{{ $table->id }}" class="btn btn-brown shadow-sm w-100 mt-4 py-2">
        <i class="bi bi-house-door me-1"></i> Kembali ke Beranda
    </a>
</div>
@endsection
