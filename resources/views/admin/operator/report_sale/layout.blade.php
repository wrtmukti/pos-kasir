@extends('admin.layouts.layout')
@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-xl font-bold mb-4">{{ $title ?? 'Laporan Penjualan' }}</h2>

    <canvas id="chart" height="120"></canvas>

    <div class="mt-6 border-t pt-4 text-center">
        <h3 class="font-semibold text-lg">{{ $subtitle ?? 'Total Omzet' }}</h3>
        <p class="text-2xl font-bold text-green-600">
            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@yield('chart')
@endsection
