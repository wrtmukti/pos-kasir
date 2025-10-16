@extends('admin.layouts.layout')
@section('content')


<div class="row my-3">
  <p class="display-4 text-center fw-bold">Tambah Product</p>
  <p class="text-center">Kategori " {{ $category->category_name }} "</p>
</div>

<div class="card shadow p-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <form action="/admin/product" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
          {{-- <label for="type">Tipe Produk</label> --}}
          <input id="type" type="hidden" class="form-control @error('type') is-invalid @enderror" name="type" value="{{ $category_type }}" placeholder="" required autocomplete="type" autofocus readonly="readonly">
          @error('type')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
        <div class="form-group">
          {{-- <label for="category_id">Kategori</label> --}}
          <input id="category_id" type="hidden" class="form-control @error('category_id') is-invalid @enderror" name="category_id" value="{{ $category->id }}" placeholder="" required autocomplete="category_id" autofocus readonly="readonly">
          @error('category_id')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
        <div class="form-group">
          <label for="code">Kode Produk</label>
          <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code') }}" required autocomplete="code" autofocus>
          @error('code')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
        <div class="form-group">
          <label for="name">Nama Produk</label>
          <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
          @error('name')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
        <div class="form-group">
            <label for="price">Harga</label>
            <input id="price" type="text" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') }}" required autocomplete="price" autofocus>
            @error('price')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
          <label for="status">Status</label>
          <select name="status" class="form-control" id="exampleFormControlSelect1" value=" {{ old('status') }}">>
            <option value="1">aktif</option>
            <option value="0">nonaktif</option>
          </select>          
          @error('status')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
        <div class="form-group">
          <label for="name">Gambar</label>
          <input type="file" name="image" class="form-control" placeholder="image">
        </div>
        <div class="form-group">
          <label for="description">Deskripsi Produk</label>
          <input id="description" type="textarea" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ old('description') }}"  autocomplete="description" autofocus>
          @error('description')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
        <div class="form-group">
          <label for="">Stok dan Jumlah</label>

          <div id="stock-wrapper">
            <div class="stock-row d-flex align-items-center mb-2">
              <select name="stocks[0][id]" class="form-control me-2" style="width: 50%;" required>
                <option value="">-- Pilih Stok --</option>
                @foreach ($stocks as $stock)
                  <option value="{{ $stock->id }}">{{ $stock->name }}</option>
                @endforeach
              </select>

              <input type="number" name="stocks[0][quantity]" class="form-control me-2" placeholder="Jumlah" min="1" style="width: 30%;" required>

              <button type="button" class="btn btn-danger btn-sm remove-stock">Hapus</button>
            </div>
          </div>

          <button type="button" class="btn btn-primary btn-sm mt-2" id="add-stock">+ Tambah Stok</button>
        </div>
        
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
</div>

<script>
  let stockIndex = 1;

  // Tambah baris stok baru
  document.getElementById('add-stock').addEventListener('click', function () {
    const wrapper = document.getElementById('stock-wrapper');
    const newRow = document.createElement('div');
    newRow.classList.add('stock-row', 'd-flex', 'align-items-center', 'mb-2');

    newRow.innerHTML = `
      <select name="stocks[${stockIndex}][id]" class="form-control me-2" style="width: 50%;" required>
        <option value="">-- Pilih Stok --</option>
        @foreach ($stocks as $stock)
          <option value="{{ $stock->id }}">{{ $stock->name }}</option>
        @endforeach
      </select>

      <input type="number" name="stocks[${stockIndex}][quantity]" class="form-control me-2" placeholder="Jumlah" min="1" style="width: 30%;" required>

      <button type="button" class="btn btn-danger btn-sm remove-stock">Hapus</button>
    `;

    wrapper.appendChild(newRow);
    stockIndex++;
  });

  // Hapus baris stok
  document.addEventListener('click', function (e) {
    if (e.target && e.target.classList.contains('remove-stock')) {
      e.target.closest('.stock-row').remove();
    }
  });
</script>

@endsection