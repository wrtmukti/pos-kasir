@extends('admin.layouts.layout')
@section('content')

{{-- {{ dd($category_type) }} --}}
<div class="row my-3">
  @if ($category_type == 0)      
  <p class="display-4 text-center fw-bold">Pilih Kategori Makanan</p>
  @else
  <p class="display-4 text-center fw-bold">Pilih Kategori Minuman</p>     
  @endif
</div>

<div class="row my-3">
  <div class="col-md-3">
    <form action="/admin/product/category/create" method="post">
      @method('POST')
      @csrf 
      @if ($category_type == 0) 
        <input type="hidden" value="0" name="category_type">
        <button type="submit" class="btn btn-primary">+ Tambah Kategori Makanan</a>
      @else
        <input type="hidden" value="1" name="category_type">
        <button type="submit" class="btn btn-primary">+ Tambah Kategori Minuman</a>
      @endif
    </form>
  </div>
</div>

@if ($categories->count() == 0)
  <div class="alert alert-danger text-center">
    <p class="fw-bold">kategori masih kosong:(</p>
  </div>
@else
  <div class="card shadow p-5">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <form action="/admin/product/create" method="POST">
          @csrf
          <input type="hidden" name="category_type" value="{{ $category_type }}">
          <div class="form-group">
            @if ($category_type == 0) 
              <label for="">Nama Kategori Makanan</label>
              <select name="category_id" class="form-control" id="exampleFormControlSelect1"  id="exampleFormControlSelect1">
                @foreach ($categories as $data)
                  <option value="{{ $data->id }}">{{ $data->category_name }}</option>
                @endforeach
              </select>
            @else 
              <label for="">Nama Kategori Minuman</label>
              <select name="category_id" class="form-control" id="exampleFormControlSelect1"  id="exampleFormControlSelect1">
                @foreach ($categories as $data)
                  <option value="{{ $data->id }}">{{ $data->category_name }}</option>
                @endforeach
              </select>
            @endif        
          </div>

          
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>
@endif
@endsection