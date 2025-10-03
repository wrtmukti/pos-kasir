@extends('admin.layouts.layout')
@section('content')

{{-- cart icon --}}
<div class="row">
  <div class="col-5 mb-3">
    <!-- Button trigger modal -->
    <div class="shopping-cart">
      <div class="sum-prices">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        <i class="mdi mdi-cart  shoppingCartButton"></i>
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
          <input name="status" class="form-control" type="hidden" value="1" readonly="readonly">
          <input name="type" class="form-control" type="hidden" value="1" readonly="readonly">
          <input name="price" id="total-prices" class="form-control" type="hidden" value="" readonly="readonly">

          <div class="modal-body producstOnCart hide">
            <ul id="buyItems">
              <h4 class="empty">Your shopping cart is empty</h4>
              
            </ul>
          </div>
          <div class="modal-footer d-none" id="modal-footer">
            <input id="total_price" class="form-control text-center mb-4 fw-bold" type="text" value="" readonly="readonly">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary  checkout">Checkout</button>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>

{{-- product --}}


  <ul class="nav nav-tabs" role="tablist"   >
    @foreach ($categories as $category)    
    <li class="nav-item">
        <a href="#tab{{ $category->id }}" class="nav-link {{ $category->id == 1 ? 'active' : '' }}" role="tab" data-bs-toggle="tab">{{ $category->category_name }}</a>
    </li>
    @endforeach
  </ul>
  <div class="tab-content" >
    @foreach ($categories as $category)    
      <div role="tabpanel" class="tab-pane fade show {{ $category->id == 1 ? 'active' : '' }}" id="tab{{ $category->id }}">
        @php
            $product = $products->where('category_id', $category->id)
        @endphp
        <div class="row">
          @if ($product->count() == 0 )
              <div class="text-center">
                <p>Produk "{{ $category->category_name }}" kosong</p>
              </div>
          @else
            <div class="products">
              <div class="row">
                @foreach ($product as $data)
                <div class="col-md-4 mb-3">
                  <div class="product">
                    <div class="product-under">
                      <div class="product-summary">
                        <div class="card shadow" >
                          <div class="row">
                            <div class="col-6">
                              <figure class="product-image">
                                <img src="{{ asset('images/product/' . $data->image) }}" class="card-img-top imgProduct" alt="...">
                              </figure>
                            </div>
                            <div class="col-6">
                              <div class="p-1 text-center mt-3">
                                <span class="productName  fw-bold">{{ $data->name }}</span><br>
                                Rp. <span class="priceValue" style="">{{ $data->price }}</span>,-
                              </div>
                              <div class="text-center my-3">
                                <button class="btn btn-primary addToCart "  data-product-id="{{ $data->id }}"> +<i class="mdi mdi-cart "></i></button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
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

 





<script>
    let productsInCart = JSON.parse(localStorage.getItem('shoppingCart'));
    if(!productsInCart){
      productsInCart = [];
    }
    const parentElement = document.querySelector('#buyItems');
    const cartSumPrice = document.querySelector('#sum-prices');
    const products = document.querySelectorAll('.product-under');


    const countTheSumPrice = function () { // 4
      let sum = 0;
      productsInCart.forEach(item => {
        sum += item.price;
      });
      return sum;
    }

    const updateShoppingCartHTML = function () {  // 3
      localStorage.setItem('shoppingCart', JSON.stringify(productsInCart));
      if (productsInCart.length > 0) {
        let result = productsInCart.map(product => {
          return `
            <li class="buyItem mb-3" style="list-style:none;">
              <div class="row ">
                <div class="col-4">
                  <img src="${product.image}"  style="width: 100%;" class="imgModal">
                </div>
                <div class="col-8">
                  <input name="product_id[]" class="form-control" type="hidden" value="${product.id}" readonly="readonly">
                  <input name="" class="form-control" type="text" value="${product.name}" readonly="readonly">
                  <input name="" class="form-control" type="text" value="Rp. ${product.price},-" readonly="readonly">
                  <div class="row mt-2 pe-2">
                    <div class="col-3">
                      <button class="button-minus btn btn-primary" style="padding:8px" data-id=${product.id}>-</button>
                    </div>
                    <div class="col-6">
                      <input name="amount[]" class="form-control countOfProduct text-center" type="text" value="${product.count}" readonly="readonly">
                    </div>
                    <div class="col-3">
                      <button class="button-plus btn btn-primary" style="padding:8px" data-id=${product.id}>+</button>
                    </div>
                  </div>
                </div>
              </div>
            </li>`
        });
        parentElement.innerHTML = result.join('');
        document.querySelector('.checkout').classList.remove('hidden');
        cartSumPrice.innerHTML = 'Rp. ' + countTheSumPrice() + ',-';
        document.getElementById("total-prices").value =  countTheSumPrice();
        document.getElementById("total_price").value ='Total : Rp. ' + countTheSumPrice() + ',-';
        document.querySelector('#modal-footer').classList.remove('d-none');


      }
      else {
        document.querySelector('.checkout').classList.add('hidden');
        document.querySelector('#modal-footer').classList.add('d-none');
        parentElement.innerHTML = '<h4 class="empty">Keranjang Kamu Kosong :(</h4>';
        cartSumPrice.innerHTML = '';
      }
    }

    function updateProductsInCart(product) { // 2
      for (let i = 0; i < productsInCart.length; i++) {
        if (productsInCart[i].id == product.id) {
          productsInCart[i].count += 1;
          productsInCart[i].price = productsInCart[i].basePrice * productsInCart[i].count;
          return;
        }
      }
      productsInCart.push(product);
    }

    products.forEach(item => {   // 1
      item.addEventListener('click', (e) => {
        if (e.target.classList.contains('addToCart')) {
          const productID = e.target.dataset.productId;
          const productName = item.querySelector('.productName').innerHTML;
          const productPrice = item.querySelector('.priceValue').innerHTML;
          const productImage = item.querySelector('img').src;
          let product = {
            name: productName,
            image: productImage,
            id: productID,
            count: 1,
            price: +productPrice,
            basePrice: +productPrice,
          }
          updateProductsInCart(product);
          updateShoppingCartHTML();
        }
      });
    });

    parentElement.addEventListener('click', (e) => { // Last
      const isPlusButton = e.target.classList.contains('button-plus');
      const isMinusButton = e.target.classList.contains('button-minus');
      if (isPlusButton || isMinusButton) {
        for (let i = 0; i < productsInCart.length; i++) {
          if (productsInCart[i].id == e.target.dataset.id) {
            if (isPlusButton) {
              productsInCart[i].count += 1
            }
            else if (isMinusButton) {
              productsInCart[i].count -= 1
            }
            productsInCart[i].price = productsInCart[i].basePrice * productsInCart[i].count;

          }
          if (productsInCart[i].count <= 0) {
            productsInCart.splice(i, 1);
          }
        }
        updateShoppingCartHTML();
      }
    });

    updateShoppingCartHTML();
</script>
@endsection