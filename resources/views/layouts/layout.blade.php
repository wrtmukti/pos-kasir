<!DOCTYPE html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />

    <title>KEDAI PAPAJI</title>
    <meta name="description" content="" />
    {{-- <meta name="viewport" content="width=device-width, initial-scale=1" /> --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/website/nobglogo1.png') }}" />

    <!-- ========================= CSS here ========================= -->
    <link rel="stylesheet" href="{{ asset('vendor/shopgrids/assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/shopgrids/assets/css/LineIcons.3.0.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/shopgrids/assets/css/tiny-slider.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/shopgrids/assets/css/glightbox.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/shopgrids/assets/css/main.css') }}" />
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/lightslider.css') }}" />
</head>

<body>
    
    <!-- Start Header Area -->
    <header class="header navbar-area">
        <!-- Start Topbar -->
        
        <!-- End Topbar -->
        <!-- Start Header Middle -->
        <div class="header-middle">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-3 col-7">
                        <!-- Start Header Logo -->
                        <a class="navbar-brand" href="/">
                            <h4 class="fw-bold">Kedai Papaji</h4>
                        </a>
                        <!-- End Header Logo -->
                    </div>
                    <div class="col-lg-5 col-md-7 d-xs-none">
                        <!-- Start Main Menu Search -->
                        
                        <!-- End Main Menu Search -->
                    </div>
                    <div class="col-lg-4 col-md-2 col-5">
                        <div class="middle-right-area">
                            <div class="nav-hotline">
                                <i class="lni lni-phone"></i>
                                <h3>CS (wa):
                                    <span>081380787604</span>
                                </h3>
                            </div>
                            <div class="navbar-cart">
                                <div class="cart-items">
                                    <div class=" shopping-cart">
                                        <div class="sum-prices">
                                            <button type="button" class="main-btn bg-primary" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal1">
                                                <i class="lni lni-cart shoppingCartButton text-white"></i>
                                            </button>
                                            <span class="text-dark" id="sum-prices"></span>
                                        </div>
                                    </div>

                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Header Middle -->
        @php
            $category = \App\Models\Category::all();
            $food = \App\Models\Category::where('category_type', '0')->get();
            $drink = \App\Models\Category::where('category_type', '1')->get();
        @endphp
        <!-- Start Header Bottom -->
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 col-md-6 col-12">
                    <div class="nav-inner">
                        <!-- Start Mega Category Menu -->
                        <div class="mega-category-menu">
                            <span class="cat-button"><i class="lni lni-menu"></i></span>
                        </div>
                        <!-- End Mega Category Menu -->
                        <!-- Start Navbar -->
                        <nav class="navbar navbar-expand-lg">
                            <button class="navbar-toggler mobile-menu-btn" type="button" data-bs-toggle="collapse"
                                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                aria-expanded="false" aria-label="Toggle navigation">
                                <span class="toggler-icon"></span>
                                <span class="toggler-icon"></span>
                                <span class="toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
                                <ul id="nav" class="navbar-nav ms-auto">
                                    <li class="nav-item">
                                        <a href="/" class="active" aria-label="Toggle navigation">Beranda</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="dd-menu collapsed" href="javascript:void(0)" data-bs-toggle="collapse"
                                            data-bs-target="#submenu-1-2" aria-controls="navbarSupportedContent"
                                            aria-expanded="false" aria-label="Toggle navigation">Produk Makanan</a>
                                        <ul class="sub-menu collapse" id="submenu-1-2">
                                            @foreach ($food as $data)
                                            <li class="nav-item"><a href="/category/{{ $data->id }}">{{ $data->category_name }}</a></li>
                                            @endforeach
                                            
                                        </ul>
                                    </li>
                                    <li class="nav-item">
                                        <a class="dd-menu collapsed" href="javascript:void(0)" data-bs-toggle="collapse"
                                            data-bs-target="#submenu-1-3" aria-controls="navbarSupportedContent"
                                            aria-expanded="false" aria-label="Toggle navigation">Produk Minuman</a>
                                        <ul class="sub-menu collapse" id="submenu-1-3">
                                            @foreach ($drink as $data)
                                                <li class="nav-item"><a href="/category/{{ $data->id }}">{{ $data->category_name }}</a></li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    <li class="nav-item">
                                        <a class="" href="/order/status" >Status Pesanan</a>
                                    </li>

                                </ul>
                                
                            </div> <!-- navbar collapse -->
                           
                        
                        </nav>
                        <!-- End Navbar -->
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <!-- Start Nav Social -->
                    <div class="nav-social">
                        {{-- <form action="" class="search-bar" style="margin-top: -10%">
                            <input type="search" class="input-search" name="search" pattern=".*\S.*" required>
                            <button class="search-btn" type="submit">
                                <span>Search</span>
                            </button>
                        </form> --}}
                        {{-- <a type="button" class="btn btn-primary text-white" href="/order/status"><i class="lni  lni-pointer-right me-2"></i>Cek Order</a> --}}
                        <form class="d-flex" role="search" action="/search" method="post">
                            @csrf
                            <input class="form-control me-2 rounded-pill" type="search" name="search" placeholder="cari produk.." aria-label="Search">
                            <button type="submit" class="btn btn-primary rounded-pill" class="btn btn-primary">
                                <i class="lni lni-search text-white"></i>
                            </button>
                          </form>
                    </div>
                    <!-- End Nav Social -->
                </div>
            </div>
        </div>
        <!-- End Header Bottom -->
    </header>
    <!-- End Header Area -->

    {{-- modal --}}
    <div class="modal fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Keranjang Saya</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="/order/store" method="post">
                @csrf
                <input name="status" class="form-control" type="hidden" value="0" readonly="readonly">
                <input name="type" class="form-control" type="hidden" value="0" readonly="readonly">
                <input name="note" class="form-control" type="hidden" value="" readonly="readonly">
                <input name="price" id="total-prices" class="form-control" type="hidden" value="" readonly="readonly">
    
                <div class="modal-body producstOnCart hide">
                <ul id="buyItems">
                    <h4 class="empty">Keranjang kamu kosong :(</h4>
                </ul>
                </div>
                <div class="modal-footer d-none" id="modal-footer">
                <input id="total_price" class="form-control text-center mb-4 fw-bold" type="text" value="" readonly="readonly">
                @php
                    $tables = App\Models\Table::where('status', 1)->get();
                @endphp
                <div class="form-group float-start">
                    <label for="no_table" class="text-danger">*No Meja</label>
                    <select name="no_table" class="form-control" id="exampleFormControlSelect1" value=" {{ old('no_table') }}">
                        <option value="">Pilih Meja</option>
                        @foreach ($tables as $data)
                        <option value="{{ $data->no_table }}">{{ $data->no_table }}</option>
                        @endforeach
                    </select>          
                </div>
                <textarea name="note" class="form-control" type="text"  placeholder="*catatan" ></textarea>
                
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary  checkout">Checkout</button>
                <div class="row mt-2">
                  <span class="text-success">ingin dapat info menarik?</span>
                  <input name="customer_name" class="form-control" type="text" value="" placeholder="isi nama kamu" >
                  <input name="customer_whatsapp" class="form-control mb-3" type="text" value="" placeholder="no whatsapp">
                </div>
            
                </div>
            </form>
    
            </div>
        </div>
    </div>

    {{-- CONTENT --}}
    @yield('content')
    {{-- END OF CONTENT --}}


    <!-- Start Footer Area -->
    <footer class="footer">
        <!-- Start Footer Top -->
        <div class="footer-top">
            <div class="container">
                <div class="inner-content">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-12">
                            <div class="">
                                <h3 style="font: white;"> Kedai Papaji </h3>
                            </div>
                        </div>
                        <div class="col-lg-9 col-md-8 col-12">
                            <div class="footer-newsletter">
                                <h4 class="title">
                                    Kirim Pesan Review Untuk Kami
                                    <span>Dapatkan Promo special untuk reviewer kami</span>
                                </h4>
                                <div class="newsletter-form-head">
                                    <form action="#" method="get" target="_blank" class="newsletter-form">
                                        <input name="EMAIL" placeholder="kirim pesan disini." type="email">
                                        <div class="button">
                                            <button class="btn">Kirim<span class="dir-part"></span></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Footer Top -->

        <!-- End Footer Middle -->
        <!-- Start Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="inner-content">
                    <div class="row align-items-center">

                        <div class="copyright">
                            <p>Developed by Mukti Wiratama</a></p>
                        </div>
                        {{-- <div class="col-lg-6 col-12">
                        </div> --}}
                        {{-- <div class="col-lg-6 col-12">
                            <ul class="socila">
                                <li>
                                    <span>Follow Me On:</span>
                                </li>
                                <li><a href="https://instagram.com/mukti.wrtm" target="blank"><i class="lni lni-instagram"></i></a></li>
                                <li><a href="https://twitter.com/wrtm_mukti" target="blank"><i class="lni lni-twitter-original"></i></a></li>
                                <li><a href="https://www.facebook.com/mukti.wrtm/" target="blank"><i class="lni lni-facebook-filled"></i></a></li>
                                <li><a href="https://github.com/wrtmukti" target="blank"><i class="lni lni-github"></i></a></li>
                            </ul>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
        <!-- End Footer Bottom -->
    </footer>
    <!--/ End Footer Area -->

    <!-- ========================= scroll-top ========================= -->
    <a href="#" class="scroll-top">
        <i class="lni lni-chevron-up"></i>
    </a>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/lightslider.js') }}"></script>


    <!-- ========================= JS here ========================= -->
    <script src="{{ asset('vendor/shopgrids/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/shopgrids/assets/js/tiny-slider.js') }}"></script>
    <script src="{{ asset('vendor/shopgrids/assets/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('vendor/shopgrids/assets/js/main.js') }}"></script>
    <script type="text/javascript">
        //========= Hero Slider 
        tns({
            container: '.hero-slider',
            slideBy: 'page',
            autoplay: true,
            autoplayButtonOutput: false,
            mouseDrag: true,
            gutter: 0,
            items: 1,
            nav: false,
            controls: true,
            controlsText: ['<i class="lni lni-chevron-left"></i>', '<i class="lni lni-chevron-right"></i>'],
        });

        //======== Brand Slider
        tns({
            container: '.brands-logo-carousel',
            autoplay: true,
            autoplayButtonOutput: false,
            mouseDrag: true,
            gutter: 15,
            nav: false,
            controls: false,
            responsive: {
                0: {
                    items: 1,
                },
                540: {
                    items: 3,
                },
                768: {
                    items: 5,
                },
                992: {
                    items: 6,
                }
            }
        });
    </script>

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
            <li class="buyItem mb-3">
              <div class="row ">
                <div class="col-5">
                  <img src="${product.image}" class="imgProduct">
                </div>
                <div class="col-7">
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
        parentElement.innerHTML = '<p class="empty text-center">Keranjang Kamu Kosong :(</p>';
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

  <script>
    $(document).ready(function() {
    $('#autoWidth').lightSlider({
        autoWidth:true,
        loop:true,
        onSliderLoad: function() {
            $('#autoWidth').removeClass('cS-hidden');
        } 
    });  
  });
  </script>
</body>

</html>