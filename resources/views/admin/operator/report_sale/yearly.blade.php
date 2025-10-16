@extends('admin.operator.report_sale.layout')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('reports.index') }}" class="btn btn-light border d-flex align-items-center me-4">
           Kembali
       </a>
        <h2>📈 Penjualan Tahunan</h2>
        <form action="{{ route('reports.yearly') }}" method="GET" class="d-flex align-items-center gap-2">
            <input 
                type="number" 
                name="year" 
                value="{{ request('year', $year) }}" 
                class="form-control"
                style="width: 120px;"
                min="2000"
                max="{{ now()->year }}"
            >
            <button type="submit" class="btn btn-primary">Tampilkan</button>
        </form>
    </div>

    <h5 class="mb-4">
        Tahun: <b>{{ $year }}</b>
    </h5>

    <canvas id="yearlyChart" style="cursor: pointer;"></canvas>

    <div class="mt-4 p-3 bg-light rounded shadow-sm">
        <h4 class="mb-0">💰 Total Omset Tahun Ini: 
            <b>Rp{{ number_format($totalRevenue, 0, ',', '.') }}</b>
        </h4>
    </div>
</div>

{{-- CHART.JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('yearlyChart');

const labels = {!! json_encode($labels) !!}; // ['Jan', 'Feb', ...]
const year = {{ $year }};

const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [
            @foreach ($chartData as $cat => $values)
            {
                label: "{{ $cat }}",
                data: {!! json_encode($values) !!},
                backgroundColor: 'rgba(153, 102, 255, 0.6)',
            },
            @endforeach
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' },
            title: { display: true, text: 'Penjualan Tahunan per Kategori' }
        },
        onClick: (evt, elements) => {
            if (elements.length > 0) {
                const index = elements[0].index;
                const clickedMonth = index + 1; // 1 = Januari, 12 = Desember
                const monthlyUrl = "{{ route('reports.monthly') }}" + "?year=" + year + "&month=" + clickedMonth;
                window.location.href = monthlyUrl;
            }
        },
        interaction: {
            mode: 'nearest',
            intersect: true
        },
        hover: {
            onHover: (event, chartElement) => {
                event.native.target.style.cursor = chartElement.length ? 'pointer' : 'default';
            }
        }
    }
});
</script>
@endsection
