@extends('admin.operator.report_sale.layout')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>📆 Penjualan Mingguan</h2>
        <form action="{{ route('reports.weekly') }}" method="GET" class="d-flex align-items-center gap-2">
            <input 
                type="date" 
                name="date" 
                value="{{ request('date', $currentDate->toDateString()) }}" 
                class="form-control"
                style="width: 200px;"
            >
            <button type="submit" class="btn btn-primary">Tampilkan</button>
        </form>
    </div>

    <h5 class="mb-4">
        Periode: <b>{{ $startOfWeek->translatedFormat('d F Y') }} - {{ $endOfWeek->translatedFormat('d F Y') }}</b>
    </h5>

    <canvas id="weeklyChart" style="cursor: pointer;"></canvas>

    <div class="mt-4 p-3 bg-light rounded shadow-sm">
        <h4 class="mb-0">💰 Total Omset Minggu Ini: 
            <b>Rp{{ number_format($totalRevenue, 0, ',', '.') }}</b>
        </h4>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const weeklyLabels = {!! json_encode($labels) !!};
const startOfWeek = "{{ $startOfWeek->toDateString() }}";
const ctx = document.getElementById('weeklyChart');
const weeklyChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: weeklyLabels,
        datasets: [
            @foreach ($chartData as $cat => $values)
            {
                label: "{{ $cat }}",
                data: {!! json_encode($values) !!},
                borderWidth: 2,
                fill: false,
            },
            @endforeach
        ]
    },
    options: {
        responsive: true,
        onClick: (e) => {
            const points = weeklyChart.getElementsAtEventForMode(e, 'nearest', { intersect: true }, true);
            if (points.length) {
                const dayIndex = points[0].index;
                const date = moment(startOfWeek).add(dayIndex, 'days').format('YYYY-MM-DD');
                window.location.href = `{{ route('reports.daily') }}?date=${date}`;
            }
        },
        plugins: {
            legend: { position: 'bottom' },
            title: { display: true, text: 'Penjualan Mingguan per Kategori' }
        }
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
@endsection
