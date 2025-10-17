@extends('admin.layouts.layout')

@section('content')
<div class="container py-5 text-center">
    <h2 class="text-primary mb-3">QR Code Meja {{ $meja->no_table }}</h2>

    <div id="printArea" class="p-4 border rounded shadow-sm d-inline-block bg-white">
        <div>{!! $qr !!}</div>
        <p class="mt-2 fw-bold text-dark">{{ $link }}</p>
    </div>

    <div class="mt-4">
        <button onclick="printQr()" class="btn btn-success">
            <i class="bi bi-printer-fill me-1"></i> Cetak QR
        </button>
        <a href="{{ route('meja.index') }}" class="btn btn-secondary ms-2">
            <i class="bi bi-arrow-left-circle me-1"></i> Kembali
        </a>
    </div>
</div>

<script>
function printQr() {
    var printContents = document.getElementById('printArea').innerHTML;
    var w = window.open('', '', 'width=600,height=600');
    w.document.write('<html><head><title>Cetak QR</title></head><body style="text-align:center;">' + printContents + '</body></html>');
    w.document.close();
    w.focus();
    w.print();
    w.close();
}
</script>
@endsection
