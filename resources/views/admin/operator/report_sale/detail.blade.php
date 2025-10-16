@extends('admin.layouts.layout')
@section('content')
<div class="container">
    <h2 class="mb-4">🛒 Detail Penjualan Kategori: {{ $category->name }}</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Jumlah Terjual</th>
                <th>Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $p)
            <tr>
                <td>{{ $p->product->name ?? 'Tidak Diketahui' }}</td>
                <td>{{ $p->total_qty }}</td>
                <td>Rp {{ number_format($p->total_rev, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('reports.index') }}" class="btn btn-secondary mt-3">⬅️ Kembali</a>
</div>
@endsection
