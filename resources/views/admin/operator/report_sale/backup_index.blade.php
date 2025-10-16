@extends('admin.layouts.layout')
@section('content')
<div class="container">
    <h2 class="mb-4 font-bold text-lg">📊 Laporan Penjualan per Kategori ({{ ucfirst($filter) }})</h2>

    <form method="GET" class="mb-3">
        <select name="filter" onchange="this.form.submit()" class="form-control w-auto d-inline">
            <option value="daily" {{ $filter == 'daily' ? 'selected' : '' }}>Harian</option>
            <option value="weekly" {{ $filter == 'weekly' ? 'selected' : '' }}>Mingguan</option>
            <option value="monthly" {{ $filter == 'monthly' ? 'selected' : '' }}>Bulanan</option>
        </select>
    </form>

    <canvas id="categoryChart"></canvas>

    <ul class="mt-4">
        @foreach ($data as $row)
            <li>
                <a href="{{ route('reports.category.detail', $row->category_id) }}">
                    {{ $row->category->name ?? 'Tanpa Kategori' }} – Rp {{ number_format($row->total_revenue, 0, ',', '.') }} ({{ $row->total_quantity }} item)
                </a>
            </li>
        @endforeach
    </ul>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('categoryChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Total Penjualan (Rp)',
                data: @json($totals),
                borderWidth: 1,
                backgroundColor: '#4e73df'
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            },
            onClick: function (evt, item) {
                if (item.length > 0) {
                    const index = item[0].index;
                    const categoryId = @json($data->pluck('category_id'));
                    window.location.href = `/reports/category/${categoryId[index]}`;
                }
            }
        }
    });
</script>
@endsection
