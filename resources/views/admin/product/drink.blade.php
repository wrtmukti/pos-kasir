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
  <h1 class="text-center display-3 fw-bold mb-3">Produk Minuman</h1>
  <form action="/admin/product/category" method="post">
    @method('POST')
    @csrf 
  <input type="hidden" value="1" name="category_type">
  <button type="submit" class="btn btn-primary text-center">+ Produk</a>
  </form>
</div>






@if ($products->count() == 0)
<div class="alert alert-danger text-center">
  Produk Kosong
</div>
@else
<div class="container p-0">
  <div class="scroller scroller-left"><i class="  mdi mdi-arrow-left  "></i></div>
  <div class="scroller scroller-right"><i class="  mdi mdi-arrow-right  "></i></div>
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
                <div class="card">
                  <div class="row">
                    <div class="col-6">
                      <img src="{{ asset('images/product/' . $data->image) }}" class="card-img-top imgProduct rounded" alt="...">
                    </div>
                    <div class="col-6 p-3">
                      <p class="fw-bold mt-3">{{ $data->name }} </p>
                      <p class="card-text">stok  ( {{ $data->stocks->min('amount') }} )</p>
                      <p class="card-text"> Rp. {{ $data->price }},-</p>
                    </div>
                  </div>
                  <div class="card-footer">
                    <div class="row">
                      <div class="col-6 text-center">
                        <form action="/admin/product/{{$data->id}}" method="post" style="text-decoration: none">
                          @csrf
                          @method('DELETE')
                          <input type="hidden" name="{{$data->id}}" value="DELETE">
                          <button type="sumbit" class="btn btn-danger text-center " onclick="return confirm('Yakin ingin menghapus produk?');">
                            Hapus</i>
                          </button>
                        </form> 
                      </div>
                      <div class="col-6">
                        <form action="/admin/product/active/{{$data->id}}" method="post" style="text-decoration: none">
                          @csrf
                          @method('PUT')
                          @if ($data->status == 0)
                            <input type="hidden" name="status" value="1">
                            <button type="sumbit" class="btn btn-success text-center  btn-ico" onclick="return confirm('Yakin ingin menonaktifkan produk?');">
                              Aktif</i>
                            </button> 
                          @else
                          <input type="hidden" name="status" value="0">
                          <button type="sumbit" class="btn btn-dark text-center  btn-ico" onclick="return confirm('Yakin ingin mengaktifkan produk?');">
                            Nonaktif</i>
                          </button> 
                          @endif
                          
                        </form> 
                      </div>
                    </div>
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

@endsection