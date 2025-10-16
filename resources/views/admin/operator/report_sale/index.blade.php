@extends('admin.layouts.layout')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="container mt-4">
    {{-- Judul Halaman --}}
    <h3 class="fw-bold mb-4">📊 Laporan Penjualan</h3>

    {{-- Navigasi Jenis Laporan (UI-Friendly Cards) --}}
    <p class="text-muted mb-3">Pilih periode laporan yang ingin Anda analisis:</p>
    <div class="row g-3 mb-5">
        
        {{-- Kartu Harian --}}
        <div class="col-sm-6 col-md-3">
            <a href="{{ route('reports.daily') }}" class="card card-body shadow-sm border-0 text-center text-decoration-none h-100 transition-hover bg-primary text-white">
                <i class="bi bi-calendar-day-fill fa-2x mb-2"></i>
                <h5 class="fw-bold mb-0">Harian</h5>
                <small class="opacity-75">Data penjualan hari ini</small>
            </a>
        </div>

        {{-- Kartu Mingguan --}}
        <div class="col-sm-6 col-md-3">
            <a href="{{ route('reports.weekly') }}" class="card card-body shadow-sm border-0 text-center text-decoration-none h-100 transition-hover bg-success text-white">
                <i class="bi bi-calendar-week-fill fa-2x mb-2"></i>
                <h5 class="fw-bold mb-0">Mingguan</h5>
                <small class="opacity-75">Tren penjualan 7 hari terakhir</small>
            </a>
        </div>

        {{-- Kartu Bulanan --}}
        <div class="col-sm-6 col-md-3">
            <a href="{{ route('reports.monthly') }}" class="card card-body shadow-sm border-0 text-center text-decoration-none h-100 transition-hover bg-info text-white">
                <i class="bi bi-calendar-month-fill fa-2x mb-2"></i>
                <h5 class="fw-bold mb-0">Bulanan</h5>
                <small class="opacity-75">Performa bulan berjalan</small>
            </a>
        </div>

        {{-- Kartu Tahunan --}}
        <div class="col-sm-6 col-md-3">
            <a href="{{ route('reports.yearly') }}" class="card card-body shadow-sm border-0 text-center text-decoration-none h-100 transition-hover bg-warning text-dark">
                <i class="bi bi-calendar-check-fill fa-2x mb-2"></i>
                <h5 class="fw-bold mb-0">Tahunan</h5>
                <small class="opacity-75">Analisis pendapatan per tahun</small>
            </a>
        </div>
    </div>
    {{-- End of Navigasi Cards --}}

    {{-- Kartu Laporan Utama (Tabel) --}}
    <div class="card shadow-lg border-0">
        <div class="card-header bg-white border-bottom">
            <h5 class="fw-semibold mb-0">Rekap Penjualan per Kategori</h5>
        </div>
        <div class="card-body">
            {{-- Tabel Laporan --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Kategori</th>
                            <th scope="col" class="text-end">Total Terjual</th>
                            <th scope="col" class="text-end">Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($report as $r)
                            <tr>
                                <td>{{ $r->category->category_name ?? 'Tanpa Kategori' }}</td>
                                <td class="text-end">{{ number_format($r->total_sold) }}</td>
                                <td class="text-end">Rp {{ number_format($r->total_income, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted p-4">
                                    Belum ada data penjualan yang tercatat untuk periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- / Tabel Laporan --}}
        </div>
        <div class="card-footer bg-light border-top">
            <small class="text-muted">Data diambil secara ringkasan (summary).</small>
        </div>
    </div>
    {{-- / Kartu Laporan Utama --}}
</div>
@endsection

{{-- CATATAN: Pastikan Anda telah menyertakan Bootstrap Icons (misalnya, via CDN) di dalam admin.layouts.layout Anda agar ikon (i.bi) dapat muncul. --}}