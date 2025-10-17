@extends('admin.layouts.layout')
@section('content')

{{-- cart icon --}}
<div class="row">
  <div class="col-5 mb-3">
    <div class="shopping-cart">
      <div class="sum-prices">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
          <i class="mdi mdi-cart shoppingCartButton"></i>
        </button>
        <h5 id="sum-prices"></h5>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Keranjang Saya</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form action="/admin/order" method="post">
          @csrf
          <input name="status" type="hidden" value="1">
          <input name="type" type="hidden" value="1">
          <input name="price" id="total-prices" type="hidden" readonly>
          <input name="total_payment" id="total-payment" type="hidden" readonly>

          <div class="modal-body producstOnCart hide">
            <ul id="buyItems">
              <h4 class="empty">Keranjang masih kosong :(</h4>
            </ul>
          </div>

          <div class="px-4">
            <!-- Total Harga -->
            <div class="mb-3">
              <label class="fw-bold">Total Harga</label>
              <input id="total_price" class="form-control text-center fw-bold" type="text" readonly>
            </div>

            <!-- Voucher -->
            <div class="voucher-section mt-3">
              <label for="voucher_code" class="fw-bold">Kode Voucher</label>
              <div class="input-group mb-3">
                <input type="text" id="voucher_code" name="voucher_code" class="form-control" placeholder="Masukkan kode voucher">
                <button type="button" id="checkVoucher" class="btn btn-success">Cek</button>
              </div>
              <div id="voucherMessage" class="text-muted small"></div>
            </div>

            <!-- Total Pembayaran -->
            <div class="my-3">
              <label class="fw-bold">Total Pembayaran</label>
              <input id="total_payment" class="form-control text-center fw-bold" type="text" readonly>
              <input id="total_payment_form" class="form-control text-center fw-bold" type="hidden">
            </div>
          </div>

          <div class="modal-footer d-none" id="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary checkout">Checkout</button>
          </div>

          <input type="hidden" name="voucher_id" id="voucher_code_hidden">
        </form>
      </div>
    </div>
  </div>
</div>

{{-- product --}}
<ul class="nav nav-tabs" role="tablist">
  @foreach ($categories as $category)
  <li class="nav-item">
    <a href="#tab{{ $category->id }}" class="nav-link {{ $category->id == 1 ? 'active' : '' }}" role="tab" data-bs-toggle="tab">{{ $category->category_name }}</a>
  </li>
  @endforeach
</ul>

<div class="tab-content mt-4">
  @foreach ($categories as $category)
  <div role="tabpanel" class="tab-pane fade show {{ $category->id == 1 ? 'active' : '' }}" id="tab{{ $category->id }}">
    @php
      $product = $products->where('category_id', $category->id);
    @endphp
    <div class="row">
      @if ($product->count() == 0)
      <div class="text-center">
        <p>Produk "{{ $category->category_name }}" kosong</p>
      </div>
      @else
      <div class="products">
        <div class="row">
          @foreach ($product as $data)
          @php
              $discount = $data->diskons->first();
              $discountPrice = $data->price;
              $badge = '';

              if ($discount) {
                  if ($discount->type_diskon == 0) {
                      $discountPrice = $data->price - ($data->price * $discount->value / 100);
                      $badge = '-' . $discount->value . '%';
                  } else {
                      $discountPrice = max(0, $data->price - $discount->value);
                      $badge = '-Rp' . number_format($discount->value, 0, ',', '.');
                  }
              }
          @endphp
          <div class="col-md-2 col-sm-6 mb-4">
            <div class="card shadow-sm h-100 text-center">
              <div class="card-img-wrapper position-relative" style="height: 180px; overflow: hidden;">
                <img src="{{ asset('images/product/' . $data->image) }}" class="card-img-top imgProduct" alt="{{ $data->name }}" style="height: 100%; width: 100%; object-fit: cover;">
                @if($discount)
                <span class="badge bg-danger position-absolute top-0 end-0 m-2">{{ $badge }}</span>
                @endif
              </div>
              <div class="card-body d-flex flex-column justify-content-between">
                <div>
                  <h6 class="fw-bold mb-2 productName">{{ $data->name }}</h6>
                  @if($discount)
                    <p class="text-muted mb-1">
                      <small><del>Rp. {{ number_format($data->price, 0, ',', '.') }}</del></small>
                    </p>
                    <p class="fw-bold text-success mb-3">
                      Rp. <span class="priceValue">{{ $discountPrice }}</span>
                    </p>
                  @else
                    <p class="text-muted mb-3">
                      Rp. <span class="priceValue">{{ $data->price }}</span>
                    </p>
                  @endif
                </div>
                <button class="btn btn-primary addToCart mt-auto"
                        data-product-id="{{ $data->id }}"
                        data-product-price="{{ $discountPrice }}">
                  + <i class="mdi mdi-cart"></i>
                </button>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      @endif
    </div>
  </div>
  @endforeach
</div>

<style>
  .card { border-radius: 10px; overflow: hidden; transition: transform .2s ease-in-out; }
  .card:hover { transform: translateY(-5px); }
  .card-body { min-height: 150px; }
  .priceValue { font-weight: bold; color: #2a9d8f; }

  .quantity-control {
    display: flex; align-items: center; justify-content: center; gap: 4px;
  }
  .quantity-control button {
    width: 32px; height: 32px; padding: 0; font-size: 16px; border-radius: 4px;
  }
  .quantity-control .input-qty {
    width: 50px; height: 32px; font-size: 14px; text-align: center; padding: 2px; font-weight: 600;
  }
  @media (max-width: 576px) {
    .quantity-control button { width: 28px; height: 28px; font-size: 14px; }
    .quantity-control .input-qty { width: 40px; height: 28px; font-size: 13px; }
  }
</style>

<script>
let productsInCart = JSON.parse(localStorage.getItem('shoppingCart')) || [];
const parentElement = document.querySelector('#buyItems');
const cartSumPrice = document.querySelector('#sum-prices');
const totalPriceDisplay = document.getElementById("total_price");
const totalHidden = document.getElementById("total-prices");
const totalPayment = document.getElementById("total_payment");
const total_Payment = document.getElementById("total-payment");

const countTheSumPrice = () => productsInCart.reduce((sum, item) => sum + item.price, 0);

const updateTotals = () => {
  const total = countTheSumPrice();
  totalHidden.value = total;
  totalPriceDisplay.value = "Total : Rp. " + total.toLocaleString("id-ID");
  totalPayment.value = "Rp. " + total.toLocaleString("id-ID");
};

const updateShoppingCartHTML = () => {
  localStorage.setItem('shoppingCart', JSON.stringify(productsInCart));
  if (productsInCart.length > 0) {
    let result = productsInCart.map(product => `
      <li class="buyItem mb-3" style="list-style:none;">
        <div class="row">
          <div class="col-4"><img src="${product.image}" style="width: 100%; border-radius:6px;"></div>
          <div class="col-8">
            <input name="product_id[]" type="hidden" value="${product.id}">
            <input class="form-control mb-1" type="text" value="${product.name}" readonly>
            <input class="form-control mb-2" type="text" value="Rp. ${product.price},-" readonly>
            <div class="quantity-control mt-2">
              <button class="button-minus buttonku btn btn-sm btn-outline-primary" data-id=${product.id}>−</button>
              <input name="amount[]" class="form-control text-center input-qty" type="text" value="${product.count}" readonly>
              <button class="button-plus buttonku btn btn-sm btn-outline-primary" data-id=${product.id}>+</button>
            </div>
          </div>
        </div>
      </li>`);
    parentElement.innerHTML = result.join('');
    document.querySelector('#modal-footer').classList.remove('d-none');
    cartSumPrice.innerHTML = 'Rp. ' + countTheSumPrice() + ',-';
    updateTotals();
  } else {
    parentElement.innerHTML = '<h4 class="empty">Keranjang Kamu Kosong :(</h4>';
    cartSumPrice.innerHTML = '';
    document.querySelector('#modal-footer').classList.add('d-none');
  }
}

const updateProductsInCart = (product) => {
  const index = productsInCart.findIndex(p => p.id == product.id);
  if (index > -1) {
    productsInCart[index].count += 1;
    productsInCart[index].price = productsInCart[index].basePrice * productsInCart[index].count;
  } else {
    productsInCart.push(product);
  }
}

document.querySelectorAll('.addToCart').forEach(button => {
  button.addEventListener('click', e => {
    const item = e.target.closest('.card');
    const product = {
      id: button.dataset.productId,
      name: item.querySelector('.productName').innerText,
      image: item.querySelector('img').src,
      count: 1,
      basePrice: +button.dataset.productPrice,
      price: +button.dataset.productPrice
    };
    updateProductsInCart(product);
    updateShoppingCartHTML();
  });
});

parentElement.addEventListener('click', e => {
  const id = e.target.dataset.id;
  if (!id) return;
  const item = productsInCart.find(p => p.id == id);
  if (e.target.classList.contains('button-plus')) item.count++;
  else if (e.target.classList.contains('button-minus')) item.count--;
  if (item.count <= 0) productsInCart = productsInCart.filter(p => p.id != id);
  item.price = item.basePrice * item.count;
  updateShoppingCartHTML();
});

updateShoppingCartHTML();

// === Voucher Logic ===
document.addEventListener("DOMContentLoaded", function () {
  const checkBtn = document.getElementById("checkVoucher");
  const codeInput = document.getElementById("voucher_code");
  const messageBox = document.getElementById("voucherMessage");
  const voucherHidden = document.getElementById("voucher_code_hidden");

  checkBtn.addEventListener("click", function () {
    const code = codeInput.value.trim();
    const total = parseFloat(totalHidden.value || 0);

    messageBox.textContent = "";
    if (!code) return messageBox.textContent = "Masukkan kode voucher terlebih dahulu.";
    if (total <= 0) return messageBox.textContent = "Total harga tidak valid.";

    fetch("{{ route('voucher.check') }}", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ code: code, total_price: total })
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          messageBox.classList.add("text-success");
          messageBox.textContent = `Voucher "${data.voucher_name}" berhasil diterapkan! Diskon: ${data.voucher_type == 0 ? data.value + "%" : "Rp " + data.discount.toLocaleString()}`;
          totalPayment.value = "Rp. " + data.new_total.toLocaleString("id-ID");
          total_Payment.value = data.new_total;
          voucherHidden.value = data.voucher_id;
          document.querySelectorAll(".buttonku").forEach(btn => {
            btn.disabled = true;
          });
          // Disable voucher input
          codeInput.disabled = true;
          checkBtn.disabled = true;
          checkBtn.textContent = "Voucher Berhasil Diterapkan";
        } else {
          messageBox.classList.add("text-danger");
          messageBox.textContent = data.message;
          voucherHidden.value = "";
        }
      })
      .catch(() => {
        messageBox.classList.add("text-danger");
        messageBox.textContent = "Terjadi kesalahan saat memeriksa voucher.";
      });
  });
});
</script>

@endsection
