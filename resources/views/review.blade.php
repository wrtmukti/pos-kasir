@extends('layouts.layout')

@section('content')
<div class="container py-3">
  <h5 class="font-weight-bold text-brown mb-3">Konfirmasi Pesanan</h5>
  

  <form action="/submits" method="POST">
    @csrf
    <input type="hidden" name="total_price" value="{{ $total_price }}">

    <div class="card show shadow-sm mb-3">
      <div class="card-body">
        @foreach ($products as $p)
          @php
            $item = collect($cart)->firstWhere('product_id', $p->id);
          @endphp
          <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
            <img src="/images/product/{{ $p->image }}" alt="{{ $p->name }}" class="rounded mr-3" style="width:100px;height:100px;object-fit:cover;">
            <div class="flex-grow-1">
              <div class="font-weight-bold">{{ $p->name }}</div>
              <div class="text-muted small">Rp {{ number_format($p->price, 0, ',', '.') }} × {{ $item['qty'] }}</div>
              <input type="hidden" name="products[{{ $loop->index }}][product_id]" value="{{ $p->id }}">
              <input type="hidden" name="products[{{ $loop->index }}][quantity]" value="{{ $item['qty'] }}">
              <textarea name="products[{{ $loop->index }}][note]" class="form-control mt-1" rows="2" placeholder="Tambahkan catatan jika diperlukan"></textarea>
            </div>
          </div>
        @endforeach

        <div class="d-flex justify-content-between mt-3">
          <span class="font-weight-bold text-brown">Total Harga</span>
          <span class="font-weight-bold text-brown">Rp {{ number_format($total_price, 0, ',', '.') }}</span>
        </div>
      </div>
    </div>

    <div class="card show shadow-sm mb-3">
      <div class="card-body">
        <h6 class="font-weight-bold text-brown mb-2">Metode Pembayaran</h6>
        <div class="form-group">
          <select name="payment_method" class="form-control">
            <option value="cash">Bayar Tunai</option>
            <option value="qris">QRIS / E-Wallet</option>
            <option value="transfer">Transfer Bank</option>
          </select>
        </div>
      </div>
    </div>

    <button type="submit" class="btn btn-brown btn-block shadow-sm">Konfirmasi Pesanan</button>
  </form>
</div>
@endsection
