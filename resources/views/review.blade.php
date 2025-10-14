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
            $qty = $item['qty'];

            $diskon = $p->diskons->first();

            $originalPrice = $p->price;
            $finalPrice = $originalPrice;
            $diskonLabel = '';

            if ($diskon) {
                if ($diskon->type_diskon == 0) { 
                    $finalPrice = $originalPrice - ($originalPrice * $diskon->value / 100);
                    $diskonLabel = "-{$diskon->value}%";
                } elseif ($diskon->type_diskon == 1) { 
                    $finalPrice = $originalPrice - $diskon->value;
                    $diskonLabel = "-Rp " . number_format($diskon->value, 0, ',', '.');
                }
            }

            $originalTotal = $originalPrice * $qty;
            $finalTotal = $finalPrice * $qty;
          @endphp


          <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
            <img src="/images/product/{{ $p->image }}" alt="{{ $p->name }}" 
                class="rounded mr-3" style="width:100px;height:100px;object-fit:cover;">

            <div class="flex-grow-1">
              <div class="font-weight-bold d-flex align-items-center mb-1">
                {{ $p->name }}
                @if ($diskonLabel)
                  <span class="badge bg-danger text-white ml-2" style="font-size:0.7rem;">
                    {{ $diskonLabel }}
                  </span>
                @endif
              </div>

              {{-- Harga total per produk --}}
              @if ($diskon)
                <div class="text-muted small">
                  <s>Rp {{ number_format($originalTotal, 0, ',', '.') }}</s>
                </div>
                <div class="text-success font-weight-bold small">
                  Rp {{ number_format($finalTotal, 0, ',', '.') }}
                </div>
              @else
                <div class="text-brown font-weight-bold small">
                  Rp {{ number_format($originalTotal, 0, ',', '.') }}
                </div>
              @endif

              <div class="text-muted small mt-1">Qty: {{ $qty }}</div>

              {{-- Hidden fields --}}
              <input type="hidden" name="products[{{ $loop->index }}][product_id]" value="{{ $p->id }}">
              <input type="hidden" name="products[{{ $loop->index }}][quantity]" value="{{ $qty }}">
              <textarea name="products[{{ $loop->index }}][note]" 
                        class="form-control mt-1" rows="2" 
                        placeholder="Tambahkan catatan jika diperlukan"></textarea>
            </div>
          </div>

        @endforeach

        {{-- Total keseluruhan --}}
        <div class="d-flex justify-content-between mt-3">
          <span class="font-weight-bold text-brown">Total Harga Pesanan</span>
          <span class="font-weight-bold text-brown">
            Rp {{ number_format($total_price, 0, ',', '.') }}
          </span>
        </div>
      </div>
    </div>

    

    {{-- Voucher --}}
    <div class="card show shadow-sm mb-3">
      <div class="card-body">
        <h6 class="font-weight-bold text-brown mb-2">Gunakan Voucher</h6>
        <div class="input-group">
          <input type="text" id="voucher-code" name="voucher_code" class="form-control" placeholder="Masukkan kode voucher">
          <button type="button" id="check-voucher" class="btn btn-brown">Cek</button>
        </div>
        <div id="voucher-result" class="mt-2"></div>
      </div>
    </div>

    {{-- Total Harga --}}
    <div class="btn btn-outline-brown btn-block bg-white mb-2 text-left">
      <div class="d-flex justify-content-between">
        <span class="font-weight-bold text-brown">Total Pembayaran</span>
        <span id="total-text" class="font-weight-bold text-success">
          Rp {{ number_format($total_price, 0, ',', '.') }}
        </span>
      </div>
      {{-- input hidden agar ikut dikirim ke server --}}
      <input type="hidden" name="total_payment" id="total-payment" value="{{ $total_price }}">
    </div>

    {{-- Metode Pembayaran --}}
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

    {{-- Pilih Meja --}}
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

    <button type="submit" class="btn btn-brown btn-block shadow-sm mt-3">Konfirmasi Pesanan</button>
  </form>
</div>

{{-- SCRIPT PROMO --}}
<script>
  document.getElementById('togglePromo').addEventListener('click', function() {
    const form = document.getElementById('promoForm');
    form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'block' : 'none';
  });
</script>

{{-- SCRIPT VOUCHER --}}
{{-- SCRIPT VOUCHER --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkBtn = document.getElementById('check-voucher');
    const codeInput = document.getElementById('voucher-code');
    const resultDiv = document.getElementById('voucher-result');
    const totalField = document.querySelector('input[name="total_payment"]');
    const totalText = document.getElementById('total-text');

    let voucherApplied = false; // 🧠 Penanda agar voucher hanya bisa 1x

    checkBtn.addEventListener('click', function() {
        if (voucherApplied) {
            resultDiv.innerHTML = `
                <div class="alert alert-warning mt-2 py-2">
                    ⚠️ Voucher sudah diterapkan, tidak bisa digunakan dua kali.
                </div>
            `;
            return;
        }

        const code = codeInput.value.trim();
        const totalPrice = parseFloat(totalField.value);

        if (!code) {
            resultDiv.innerHTML = '<div class="text-danger small mt-2">Masukkan kode voucher terlebih dahulu.</div>';
            return;
        }

        checkBtn.disabled = true;
        checkBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Cek...';

        fetch('/check-voucher', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                code: code,
                total_price: totalPrice
            })
        })
        .then(res => res.json())
        .then(data => {
            checkBtn.disabled = false;
            checkBtn.innerHTML = 'Cek';

            if (data.success) {
                let typeDiskon = parseInt(data.type_diskon ?? data.voucher_type ?? 0);
                let diskonLabel = '';

                if (typeDiskon === 0) {
                    diskonLabel = `(Potongan ${data.value}% )`;
                } else if (typeDiskon === 1) {
                    diskonLabel = `(Potongan Rp ${parseInt(data.value).toLocaleString('id-ID')},-)`;
                }

                resultDiv.innerHTML = `
                    <div class="alert alert-success mt-2 py-2">
                        ✅ ${data.message}<br>
                        <strong>${data.voucher_name}</strong><br>
                        ${diskonLabel}: Rp ${data.discount.toLocaleString('id-ID')}<br>
                        Total baru: Rp ${data.new_total.toLocaleString('id-ID')}
                    </div>
                    <input type="hidden" name="voucher_id" id="total-payment" value="${data.voucher_id}">
                `;

                // Update total baru
                totalText.textContent = `Rp ${data.new_total.toLocaleString('id-ID')}`;
                totalField.value = data.new_total;

                // 🚫 Matikan input dan tombol supaya tidak bisa dipakai lagi
                codeInput.disabled = true;
                checkBtn.disabled = true;
                checkBtn.classList.add('disabled');
                checkBtn.textContent = 'Voucher Diterapkan ✅';

                voucherApplied = true; // Set flag true
            } else {
                resultDiv.innerHTML = `<div class="alert alert-danger mt-2 py-2">❌ ${data.message}</div>`;
            }
        })
        .catch((err) => {
            console.error('ERROR FETCH:', err);
            checkBtn.disabled = false;
            checkBtn.innerHTML = 'Cek';
            resultDiv.innerHTML = '<div class="alert alert-danger mt-2 py-2">Terjadi kesalahan pada server.</div>';
        });
    });
});
</script>



@endsection
