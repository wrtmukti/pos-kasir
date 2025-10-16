@extends('admin.operator.report_sale.layout')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('reports.index') }}" class="btn btn-light border d-flex align-items-center me-4">
           Kembali
       </a>
        <h2>🗓️ Penjualan Bulanan</h2>
        <form action="{{ route('reports.monthly') }}" method="GET" class="d-flex align-items-center gap-2">
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
        Periode: <b>{{ $startOfMonth->translatedFormat('1 F Y') }} - {{ $endOfMonth->translatedFormat('d F Y') }}</b>
    </h5>

    <canvas id="monthlyChart" style="cursor: pointer;"></canvas>

    <div class="mt-4 p-3 bg-light rounded shadow-sm">
        <h4 class="mb-0">💰 Total Omset Bulan Ini: 
            <b>Rp{{ number_format($totalRevenue, 0, ',', '.') }}</b>
        </h4>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script>
const monthlyLabels = {!! json_encode($labels) !!};
const startOfMonth = "{{ $startOfMonth->toDateString() }}";
const monthlyCtx = document.getElementById('monthlyChart');
const generateColor = () => `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 1)`;
        

const monthlyChart = new Chart(monthlyCtx, {
    type: 'bar',
    data: {
        labels: monthlyLabels,
        datasets: [
            @foreach ($chartData as $cat => $values)
            {
                label: "{{ $cat }}",
                data: {!! json_encode($values) !!},
                backgroundColor: generateColor(), 
                borderWidth: 1,
                borderColor: 'rgba(255, 255, 255, 0.2)',
            },
            @endforeach
        ]
    },
    options: {
        responsive: true,
        onClick: (e) => {
            const points = monthlyChart.getElementsAtEventForMode(e, 'nearest', { intersect: true }, true);
            if (points.length) {
                const index = points[0].index;
                const date = moment(startOfMonth).add(index, 'days').format('YYYY-MM-DD');
                window.location.href = `{{ route('reports.daily') }}?date=${date}`;
            }
        },
        plugins: {
            legend: { position: 'bottom' },
            title: { display: true, text: 'Penjualan Bulanan per Kategori' }
        }
    }
});
</script>
@endsection
