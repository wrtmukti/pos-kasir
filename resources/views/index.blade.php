
@extends('layouts.layout')
@section('content')

  {{-- Alert Message --}}
  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3 shadow-sm" role="alert">
      <i class="bi bi-check-circle-fill"></i>
      {{ session('success') }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  @endif

  @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show mt-3 shadow-sm" role="alert">
      <i class="bi bi-exclamation-triangle-fill"></i>
      {{ session('error') }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  @endif

  <!-- Navbar -->
  <nav class="navbar navbar-light bg-white shadow-sm sticky-top">
    <div class="container d-flex justify-content-between">
      <span class="navbar-brand">Kopi Bagaskara</span>
      <div>
        <i class="fas fa-search mr-3 text-brown"></i>
        <i class="fas fa-bars text-brown" id="menuToggle"></i>
      </div>
    </div>
  </nav>

  <!-- Offcanvas menu -->
  <div id="offcanvasMenu" class="offcanvas-menu">
    <button class="close mb-3" id="closeMenu">&times;</button>
    <h6 class="font-weight-bold text-brown">Menu</h6>
    <ul class="list-unstyled mt-3">
      <li><a href="#" class="d-block py-2 text-brown">Beranda</a></li>
      <li><a href="#" class="d-block py-2 text-brown">Promo</a></li>
      <li><a href="#" class="d-block py-2 text-brown">Reservasi</a></li>
      <li><a href="#" class="d-block py-2 text-brown">Kontak</a></li>
    </ul>
  </div>

  <!-- Tanggal -->
  <div class="mt-3">
    <div class="d-flex align-items-center bg-white p-2 rounded shadow-sm">
      <i class="far fa-calendar-alt mr-2 text-brown"></i>
      <span id="today"></span>
    </div>
  </div>

  <!-- Hero / Foto Cafe -->
  <div class="mt-3">
    <div id="heroCarousel" class="carousel slide hero-carousel shadow-sm" data-ride="carousel">
      <ol class="carousel-indicators">
        <li data-target="#heroCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#heroCarousel" data-slide-to="1"></li>
        <li data-target="#heroCarousel" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="img6.jpg" class="d-block w-100" alt="Cafe Image 1">
        </div>
        <div class="carousel-item">
          <img src="img1.jpeg" class="d-block w-100" alt="Cafe Image 2">
        </div>
        <div class="carousel-item">
          <img src="img3.jpg" class="d-block w-100" alt="Cafe Image 3">
        </div>
      </div>
      <a class="carousel-control-prev" href="#heroCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      </a>
      <a class="carousel-control-next" href="#heroCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
      </a>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-2">
      <div>
        <small id="openStatus" class="text-success font-weight-bold">Buka, 24 Jam</small>
      </div>
      <button class="btn btn-brown btn-sm">Reservasi <i class="fas fa-arrow-right ml-1"></i></button>
    </div>
  </div>

  <!-- Search bar -->
  <div class="mt-3 sticky-top" style="top:57px;">
    <div class="input-group shadow-sm">
      <div class="input-group-prepend">
        <span class="input-group-text bg-white text-brown"><i class="fas fa-search"></i></span>
      </div>
      <input type="text" id="search" class="form-control" placeholder="Cari Makanan...">
    </div>
  </div>

  <!-- Kategori -->
  <div class="mt-3 sticky-top" style="top:95px;">
    <div class="category-bar bg-white shadow-sm">
      <button class="btn btn-brown btn-sm category-btn" data-category="all">Semua Menu</button>
       @foreach ($food_categories as $food)
          <button class="btn btn-outline-brown btn-sm category-btn" data-category="{{ $food->category_name }}">{{ $food->category_name }}</button>
       @endforeach
       @foreach ($drink_categories as $drink)
          <button class="btn btn-outline-brown btn-sm category-btn" data-category="{{ $drink->category_name }}">{{ $drink->category_name }}</button>
       @endforeach
      {{-- <button class="btn btn-outline-brown btn-sm category-btn" data-category="breakfast">Breakfast</button>
      <button class="btn btn-outline-brown btn-sm category-btn" data-category="burgers">Burgers</button>
      <button class="btn btn-outline-brown btn-sm category-btn" data-category="alacarte">Ala Carte</button> --}}
    </div>
  </div>

  <!-- Header Produk -->
  <div class="mt-3 d-flex justify-content-between align-items-center">
    <h5 id="category-title" class="mb-0 font-weight-bold text-brown">Semua Produk</h5>
    <button class="btn btn-light btn-sm" id="toggleView"><i class="fas fa-th-large"></i></button>
  </div>

  <!-- Produk -->
  <div class="my-3">
    <div id="product-container" class="row"></div>
  </div>

  <!-- Bar Keranjang -->
  <form id="checkoutForm" action="/orders" method="POST">
    @csrf
    <input type="hidden" name="cart_data" id="cartData">
    <input type="hidden" name="total_price" id="totalPrice">

    <div id="cart">
      <span id="cart-info" class="font-weight-bold text-brown"></span>
      <button type="submit" class="btn btn-brown btn-sm">Checkout</button>
    </div>

    <!-- Overlay Detail Keranjang -->
    <div id="cart-detail">
      <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="mb-0 font-weight-bold text-brown">Keranjang Saya</h5>
        <button type="button" class="close" onclick="toggleCartDetail()">×</button>
      </div>

      <div id="cart-items" class="cart-items p-3"></div>

      <div class="p-3 border-top d-flex justify-content-between align-items-center">
        <span id="cart-total" class="font-weight-bold text-brown"></span>
        <button type="submit" class="btn btn-brown">Checkout</button>
      </div>
    </div>
  </form>

  <script>
  // Data produk dikirim dari Laravel
  const products = @json($products);

  let cart = [];
  let listView = false;

  // Tanggal hari ini
  const today = new Date();
  const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
  document.getElementById("today").textContent = today.toLocaleDateString("id-ID", options);

  function renderProducts(category = "all", searchTerm = "") {
    const container = document.getElementById("product-container");
    container.innerHTML = "";

    let filtered;

    // Filter pencarian
    if (searchTerm) {
      filtered = products.filter(p =>
        (p.name?.toLowerCase() ?? "").includes(searchTerm.toLowerCase()) ||
        (p.description?.toLowerCase() ?? "").includes(searchTerm.toLowerCase())
      );
      document.getElementById("category-title").textContent = "Hasil Pencarian";
    } else {
      // Filter kategori
      filtered = category === "all"
        ? products
        : products.filter(p => p.category?.category_name?.toLowerCase() === category.toLowerCase());

      const categoryTitle = document.getElementById("category-title");
      if (category === "all") categoryTitle.textContent = "Semua Produk";
      else categoryTitle.textContent = category.charAt(0).toUpperCase() + category.slice(1);
    }

    if (filtered.length === 0) {
      container.innerHTML = `<div class="col-12 text-center text-muted py-5">Tidak ada produk ditemukan.</div>`;
      return;
    }

    filtered.forEach((p, i) => {
      const col = document.createElement("div");
      col.className = "col-6 col-md-4 col-lg-3 mb-3 product-item";

      // fallback image jika kosong
      const imgSrc = p.image ? `/images/product/${p.image}` : '/default.jpg';

      col.innerHTML = `
        <div class="card h-100 shadow-sm">
          <img src="${imgSrc}" class="card-img-top" alt="${p.name}" style="height:150px;object-fit:cover;">
          <div class="card-body d-flex flex-column">
            <h6 class="card-title">${p.name}</h6>
            <small class="text-muted">${p.description ?? ''}</small>
            <p class="text-brown font-weight-bold mt-1">Rp ${Number(p.price).toLocaleString()}</p>
            <button class="btn btn-brown btn-sm mt-auto" onclick="addToCart(${p.id})">Tambah</button>
          </div>
        </div>
      `;
      container.appendChild(col);

      setTimeout(() => {
        col.querySelector(".card").classList.add("show");
      }, 150 * i);
    });

    if (listView) applyListView();
  }

  document.getElementById("toggleView").addEventListener("click", () => {
    listView = !listView;
    if (listView) {
      document.getElementById("toggleView").innerHTML = '<i class="fas fa-th-list"></i>';
      applyListView();
    } else {
      document.getElementById("toggleView").innerHTML = '<i class="fas fa-th-large"></i>';
      document.getElementById("product-container").classList.remove("list-view");
      renderProducts(document.querySelector(".category-btn.btn-brown").dataset.category, document.getElementById("search").value);
    }
  });

  function applyListView() {
    document.getElementById("product-container").classList.add("list-view");
    document.querySelectorAll(".product-item .card").forEach(card => {
      const img = card.querySelector("img");
      img.style.height = "100px";
      img.style.width = "120px";
    });
  }

  function addToCart(id) {
    const product = products.find(p => p.id === id);
    const found = cart.find(item => item.id === id);
    if (found) {
      found.qty++;
    } else {
      cart.push({ ...product, qty: 1 });
    }
    updateCart();
  }

  function decreaseQty(id) {
    const found = cart.find(item => item.id === id);
    if (found) {
      found.qty--;
      if (found.qty <= 0) {
        cart = cart.filter(item => item.id !== id);
      }
    }
    updateCart();
  }

  function updateCart() {
    const cartBox = document.getElementById("cart");
    const cartInfo = document.getElementById("cart-info");
    const cartDetail = document.getElementById("cart-detail");
    const cartItems = document.getElementById("cart-items");
    const cartTotal = document.getElementById("cart-total");
    const checkoutForm = document.getElementById("checkoutForm");
    const cartDataInput = document.getElementById("cartData");
    const totalPriceInput = document.getElementById("totalPrice");

    if (cart.length === 0) {
      cartBox.classList.remove("show");
      cartDetail.classList.remove("show");
      checkoutForm.style.display = "none";
    } else {
      cartBox.classList.add("show");
      checkoutForm.style.display = "block";

      const total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
      cartInfo.textContent = `${cart.reduce((sum, item) => sum + item.qty, 0)} Item • Rp ${total.toLocaleString()}`;
      cartTotal.textContent = `Total: Rp ${total.toLocaleString()}`;

      // Data untuk form
      cartDataInput.value = JSON.stringify(cart.map(item => ({
        product_id: item.id,
        qty: item.qty
      })));
      totalPriceInput.value = total;

      // Render isi keranjang
      cartItems.innerHTML = "";
      cart.forEach(item => {
        const row = document.createElement("div");
        const imgSrc = item.image ? `/images/product/${item.image}` : '/default.jpg';
        row.className = "d-flex justify-content-between align-items-center bg-light p-2 rounded mb-2";
        row.innerHTML = `
          <div class="d-flex align-items-center flex-grow-1">
            <img src="${imgSrc}" class="rounded mr-2" style="width:50px;height:50px;object-fit:cover;">
            <div>
              <div class="font-weight-bold small">${item.name}</div>
              <div class="text-brown small">Rp ${(item.price * item.qty).toLocaleString()}</div>
            </div>
          </div>
          <div class="d-flex align-items-center ml-auto">
            <button onclick="decreaseQty(${item.id})" class="btn btn-outline-secondary btn-sm shadow-sm"
              style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;border-radius:6px;">-</button>
            <span class="px-2 font-weight-bold">${item.qty}</span>
            <button onclick="addToCart(${item.id})" class="btn btn-outline-secondary btn-sm shadow-sm"
              style="width:32px;height:32px;display:flex;align-items:center;justify-content:center;border-radius:6px;">+</button>
          </div>
        `;
        cartItems.appendChild(row);
      });
    }
  }

  function toggleCartDetail() {
    document.getElementById("cart-detail").classList.toggle("show");
  }

  // Search global
  document.getElementById("search").addEventListener("input", (e) => {
    const searchTerm = e.target.value;
    const activeCategory = document.querySelector(".category-btn.btn-brown").dataset.category;
    renderProducts(activeCategory, searchTerm);
  });

  // Toggle menu
  document.getElementById("menuToggle").addEventListener("click", () => {
    document.getElementById("offcanvasMenu").classList.add("show");
  });
  document.getElementById("closeMenu").addEventListener("click", () => {
    document.getElementById("offcanvasMenu").classList.remove("show");
  });

  // Event kategori
  document.querySelectorAll(".category-btn").forEach(btn => {
    btn.addEventListener("click", function() {
      document.querySelectorAll(".category-btn").forEach(b => b.classList.remove("btn-brown", "text-white"));
      document.querySelectorAll(".category-btn").forEach(b => b.classList.add("btn-outline-secondary"));
      this.classList.remove("btn-outline-secondary");
      this.classList.add("btn-brown", "text-white");
      renderProducts(this.dataset.category, document.getElementById("search").value);
    });
  });

  document.getElementById("cart").addEventListener("click", toggleCartDetail);

  // Pertama kali render
  renderProducts();
</script>
    
@endsection