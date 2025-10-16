@extends('admin.operator.report_sale.layout')

@section('title', 'Penjualan Harian')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4 p-md-5">

            {{-- HEADER: Tombol Kembali, Judul, dan Form Tanggal (Layout disesuaikan dengan gambar) --}}
            <div class="d-flex align-items-center mb-5 pb-3 border-bottom">
                
                {{-- Tombol Kembali --}}
                <a href="{{ route('reports.index') }}" class="btn btn-light border d-flex align-items-center me-4">
                    Kembali
                </a>

                {{-- Judul Halaman --}}
                <h2 class="fw-normal mb-0 text-center flex-grow-1 fs-4">
                    Penjualan Harian
                </h2>

                {{-- Form Pemilihan Tanggal --}}
                <form action="{{ route('reports.daily') }}" method="GET" class="d-flex align-items-center gap-2 ms-4">
                    {{-- Input date (dd/mm/yyyy pada tampilan) --}}
                    <input 
                        type="date" 
                        name="date" 
                        value="{{ request('date', $date->toDateString()) }}" 
                        class="form-control"
                        style="width: 140px; height: 38px;"
                        required
                    >
                    {{-- Tombol Tampilkan --}}
                    <button type="submit" class="btn btn-primary" style="height: 38px;">Tampilkan</button>
                </form>
            </div>
            
            {{-- DETAIL LAPORAN: Tanggal & Omset --}}
            <div class="row mb-5 align-items-start">
                <div class="col-md-6">
                    <h6 class="text-uppercase text-muted fw-normal mb-1">Tanggal Laporan</h6>
                    <h3 class="fw-bold text-dark">
                        {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                    </h3>
                </div>
                <div class="col-md-6 text-md-end pt-3"> 
                    <div class="d-inline-block p-2 px-3 bg-success bg-opacity-10 border border-success rounded-3 shadow-sm">
                        <span class="text-success fs-6 me-1">Total Omset Harian: </span>
                        <b class="fs-5 text-success">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</b>
                    </div>
                </div>
            </div>

            {{-- AREA CHART --}}
            <h6 class="fw-semibold mb-3">Analisis Penjualan per Kategori</h6>
            <div class="border rounded bg-white shadow-sm p-3" style="height: 450px;">
                <canvas id="dailyChart" class="w-100 h-100"></canvas>
            </div>
            
        </div>
    </div>
</div>
@endsection

{{-- Script CHART.JS (Logika data diambil dari controller yang sudah Anda buat) --}}
@push('scripts') 
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('dailyChart');
        const categoryLabels = {!! json_encode($data->pluck('category.category_name')) !!};

        if (ctx && categoryLabels.length > 0) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: categoryLabels,
                    datasets: [
                        {
                            label: 'Pendapatan (Rp)',
                            data: {!! json_encode($data->pluck('total_revenue')) !!},
                            backgroundColor: 'rgba(54, 162, 235, 0.8)',
                            yAxisID: 'y1',
                        },
                        {
                            label: 'Jumlah Terjual (Item)',
                            data: {!! json_encode($data->pluck('total_qty')) !!},
                            backgroundColor: 'rgba(255, 159, 64, 0.8)',
                            yAxisID: 'y2',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, 
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'bottom' },
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y1: { type: 'linear', display: true, position: 'left', title: { display: true, text: 'Pendapatan (Rp)' }, ticks: { callback: (val) => 'Rp' + new Intl.NumberFormat('id-ID').format(val) }},
                        y2: { type: 'linear', display: true, position: 'right', grid: { drawOnChartArea: false }, title: { display: true, text: 'Jumlah Terjual (Item)' }, ticks: { callback: (val) => new Intl.NumberFormat('id-ID').format(val) }}
                    }
                }
            });
        } else if (ctx) {
             // Jika tidak ada data, tampilkan pesan peringatan
             ctx.parentElement.innerHTML = '<div class="alert alert-warning text-center m-5" role="alert"><strong>Tidak ada data penjualan</strong> untuk tanggal yang dipilih. Silakan pilih tanggal lain.</div>';
        }
    });
</script>
@endpush