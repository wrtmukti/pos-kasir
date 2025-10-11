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
        <h6 class="font-weight-bold text-brown mb-2">Meja</h6>
        <div class="form-group">
          <select name="no_table" class="form-control">
            @foreach ($tables as $table)
              <option value="{{ $table->id }}">{{ $table->no_table }}</option>
            @endforeach
          </select>
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

        {{-- PROMO SECTION --}}
        <div class="mt-4">
          <button type="button" id="togglePromo" class="btn btn-outline-brown btn-block">
            🎁 Mau dapat info promo menarik?
          </button>

          <div id="promoForm" class="mt-3" style="display:none;">
            <div class="form-group">
              <label for="promo_name">Nama</label>
              <input type="text" name="customer_name" id="promo_name" class="form-control" placeholder="Masukkan nama anda">
            </div>
            <div class="form-group mt-2">
              <label for="promo_whatsapp">Nomor WhatsApp</label>
              <input type="text" name="customer_whatsapp" id="promo_whatsapp" class="form-control" placeholder="Contoh: 081234567890">
            </div>
          </div>
        </div>
      </div>
    </div>

    <button type="submit" class="btn btn-brown btn-block shadow-sm mt-3">Konfirmasi Pesanan</button>
  </form>
</div>

{{-- SCRIPT --}}
<script>
  document.getElementById('togglePromo').addEventListener('click', function() {
    const form = document.getElementById('promoForm');
    form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
  });
</script>
@endsection
