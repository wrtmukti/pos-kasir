@extends('admin.layouts.layout')
@section('content')


<div class="row my-3">
  @if ($category_type == 0)      
    <p class="display-4 text-center fw-bold">Pilih Kategori Makanan</p>
  @else
    <p class="display-4 text-center fw-bold">Pilih Kategori Minuman</p>     
  @endif
</div>

<div class="card shadow p-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <form action="/admin/product/category/store" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
          <input type="hidden" value="{{ $category_type }}" name="category_type">
          <label for="name">Nama Kategori</label>
          <input id="category_name" type="text" class="form-control @error('category_name') is-invalid @enderror" name="category_name" value="{{ old('category_name') }}" required autocomplete="category_name" autofocus>
          @error('category_name')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
        <div class="form-group">
          <label for="name">Gambar</label>
          <input type="file" name="image" class="form-control" placeholder="image">
        </div>
        
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
</div>

@endsection