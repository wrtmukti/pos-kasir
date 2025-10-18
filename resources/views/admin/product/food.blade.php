@extends('admin.layouts.layout')
@section('content')

@if (session('success'))
<div class="alert alert-success text-center">
  <p class="fw-bold">{{ session('success') }}</p>
</div>
@endif
@if (session('danger'))
<div class="alert alert-danger text-center">
  <p class="fw-bold">{{ session('danger') }}</p>
</div>
@endif

<div class="row my-3 justify-content-center">
  <h1 class="text-center display-3 fw-bold mb-3">Produk Makanan</h1>
  <form action="/admin/product/category" method="post">
    @method('POST')
    @csrf 
    <input type="hidden" value="0" name="category_type">
    <button type="submit" class="btn btn-primary text-center">+ Produk</button>
  </form>
</div>

@if ($products->count() == 0)
<div class="alert alert-danger text-center">
  product Kosong
</div>
@else
<div class="container p-0">
  <div class="scroller scroller-left"><i class="mdi mdi-arrow-left"></i></div>
  <div class="scroller scroller-right"><i class="mdi mdi-arrow-right"></i></div>
  <div class="wrapper">
    <ul class="nav nav-tabs list" id="myTAB">
      @foreach ($categories as $category)    
      <li class="nav-item">
          <a href="#category{{ $category->id }}" class="nav-link {{ $category->id == 1 ? 'active' : '' }}" data-bs-toggle="tab">{{ $category->category_name }}</a>
      </li>
      @endforeach
    </ul>
  </div>

  <div class="tab-content">
    @foreach ($categories as $category)    
      <div class="tab-pane fade show {{ $category->id == 1 ? 'active' : '' }}" id="category{{ $category->id }}">
        @php
          $product = $products->where('category_id', $category->id)
        @endphp
        <div class="row">
          @if ($product->count() == 0 )
            <div class="text-center">
              <p>Produk "{{ $category->category_name }}" kosong</p>
            </div>
          @else
            @foreach ($product as $data)  
              <div class="col-md-3 mb-3">
                <div class="card h-100 shadow-sm" >
                  <div class="card-img-wrapper">
                    <img src="{{ asset('images/product/' . $data->image) }}" class="card-img-top imgProduct" alt="...">
                  </div>
                  <div class="card-body">
                    <p class="fw-bold mb-1">{{ $data->name }}</p>
                    <p class="card-text mb-1">Stok ({{ $data->remaining_stock }})</p> 
                    <p class="card-text fw-semibold text-danger">Rp. {{ $data->price }},-</p>
                  </div>
                  <div class="card-footer bg-light">
                    <div class="row">
                      <div class="col-6 text-center">
                          <button type="button" class="btn btn-primary btn-sm w-100"  data-bs-toggle="modal" data-bs-target="#editModal{{ $data->id }}">
                            Ubah
                          </button>
                      </div>
                      <div class="col-6 text-center">
                        <form action="/admin/product/active/{{$data->id}}" method="POST" style="text-decoration: none">
                          @csrf
                          @method('PUT')
                          @if ($data->status == 1)
                            <input type="hidden" name="status" value="0">
                            <button type="submit" class="btn btn-success btn-sm w-100" onclick="return confirm('Yakin ingin menonaktifkan produk?');">
                              Aktif
                            </button> 
                          @else
                            <input type="hidden" name="status" value="1">
                            <button type="submit" class="btn btn-dark btn-sm w-100" onclick="return confirm('Yakin ingin mengaktifkan produk?');">
                              Nonaktif
                            </button> 
                          @endif
                        </form> 
                      </div>
                    </div>
                  </div>

                  
                </div>
              </div>
              <!-- MODAL EDIT -->
              <script>
              window.stockOptions = `
                @foreach ($stocks as $stock)
                  <option value="{{ $stock->id }}">{{ $stock->name }}</option>
                @endforeach
              `;
              </script>
              <div class="modal fade" id="editModal{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $data->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    
                    <!-- FORM UPDATE -->
                    <form action="{{ route('product.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                      @csrf
                      @method('PUT')

                      <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="editModalLabel{{ $data->id }}">Edit Produk - {{ $data->name }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      
                      <input type="hidden" class="form-control" name="type" value="0">
                      <div class="modal-body">
                        <div class="row">
                          <div class="col-6">
                            <div class="form-group">
                              <label>Kode Produk</label>
                              <input type="text" class="form-control" name="name" value="{{ $data->code }}" disabled>
                            </div>
                          </div>
                          <div class="col-6">
                            <div class="form-group">
                              <label>Nama Produk</label>
                              <input type="text" class="form-control" name="name" value="{{ $data->name }}" required>
                            </div>
                          </div>
                        </div>
                        
                        <div class="row">
                          <div class="col-6">
                            <div class="form-group">
                              <label>Harga</label>
                              <input type="number" class="form-control" name="price" value="{{ $data->price }}" required>
                            </div>
                          </div>
                          <div class="col-6">
                            <div class="form-group">
                              <label>Status</label>
                              <select class="form-control" name="status">
                                <option value="1" {{ $data->status == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ $data->status == 0 ? 'selected' : '' }}>Nonaktif</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label>Deskripsi Produk</label>
                          <input type="text" class="form-control" name="description" value="{{ $data->description }}" required>
                        </div>
    
                        <div class="form-group mt-2">
                          <label>Ganti Gambar (opsional)</label>
                          <input type="file" class="form-control" name="image">
                          {{-- <small class="text-muted">Gambar saat ini: {{ $data->image }}</small> --}}
                        </div>
                        <div class="form-group mt-3">
                          <label>Stok Produk</label>

                          <div id="stock-wrapper-{{ $data->id }}">
                            @foreach ($data->stocks as $index => $stock)
                              <div class="stock-row d-flex align-items-center mb-2">
                                <select name="stocks[{{ $index }}][id]" class="form-control me-2" style="width: 50%;" required>
                                  <option value="">-- Pilih Stok --</option>
                                  @foreach ($stocks as $s)
                                    <option value="{{ $s->id }}" {{ $stock->id == $s->id ? 'selected' : '' }}>
                                      {{ $s->name }}
                                    </option>
                                  @endforeach
                                </select>

                                <input type="number" name="stocks[{{ $index }}][quantity]" 
                                      class="form-control me-2" 
                                      placeholder="Jumlah" 
                                      min="1" 
                                      style="width: 30%;" 
                                      value="{{ $stock->pivot->quantity ?? 1 }}" 
                                      required>

                                <button type="button" class="btn btn-danger btn-sm remove-stock">Hapus</button>
                              </div>
                            @endforeach
                          </div>

                          <button type="button" class="btn btn-primary btn-sm mt-2 add-stock" data-wrapper="#stock-wrapper-{{ $data->id }}">Tambah Stok</button>
                        </div>

                      </div>

                      <div class="modal-footer justify-content-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <!-- FORM DELETE (terpisah agar tidak bentrok dengan form update) -->
                        <button type="button" class="btn btn-danger" onclick="deleteProduct({{ $data->id }})">Hapus</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                      </div>

                    </form>

                    <!-- FORM DELETE TERPISAH -->
                    <form id="deleteForm-{{ $data->id }}" action="/admin/product/{{ $data->id }}" method="POST" class="d-none">
                      @csrf
                      @method('DELETE')
                    </form>

                  </div>
                </div>
              </div>

            @endforeach
          @endif
        </div>
      </div>
    @endforeach
  </div>
</div>
@endif

<script>
  document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".add-stock").forEach(btn => {
    btn.addEventListener("click", function () {
      const wrapperId = this.dataset.wrapper;
      const wrapper = document.querySelector(wrapperId);
      const index = wrapper.querySelectorAll(".stock-row").length;

      const newRow = document.createElement("div");
      newRow.classList.add("stock-row", "d-flex", "align-items-center", "mb-2");
      newRow.innerHTML = `
        <select name="stocks[${index}][id]" class="form-control me-2" style="width: 50%;" required>
          <option value="">-- Pilih Stok --</option>
          ${window.stockOptions || ''}
        </select>
        <input type="number" name="stocks[${index}][quantity]" class="form-control me-2" placeholder="Jumlah" min="1" style="width: 30%;" required>
        <button type="button" class="btn btn-danger btn-sm remove-stock">Hapus</button>
      `;
      wrapper.appendChild(newRow);
    });
  });

  // Hapus stok row
  document.addEventListener("click", function (e) {
    if (e.target.classList.contains("remove-stock")) {
      e.target.closest(".stock-row").remove();
    }
  });
});

</script>

<script>
  function deleteProduct(id) {
    if (confirm('Yakin ingin menghapus produk ini?')) {
      document.getElementById(`deleteForm-${id}`).submit();
    }
  }
</script>


{{-- Tambahkan CSS di bawah --}}
<style>
  .card-img-wrapper {
    width: 100%;
    height: 180px; /* tinggi konsisten */
    overflow: hidden;
    border-bottom: 1px solid #ddd;
  }

  .card-img-top {
    width: 100%;
    height: 100%;
    object-fit: cover; /* biar proporsional tanpa loncat ukuran */
    transition: transform 0.3s ease;
  }

  .card-img-top:hover {
    transform: scale(1.05);
  }

  .card {
    border-radius: 12px;
  }

  .card-body {
    padding: 10px 15px;
  }
</style>

@endsection
