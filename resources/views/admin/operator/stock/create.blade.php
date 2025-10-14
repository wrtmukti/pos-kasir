@extends('admin.layouts.layout')
@section('content')


<div class="row my-3">
  <p class="display-3 text-center fw-bold">Tambah Stok</p>
</div>

<div class="card shadow p-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <form action="/admin/stock" method="POST">
        @csrf
        <div class="form-group">
          <label for="name">Nama Stok</label>
          <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="nama" autofocus>
          @error('name')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
        <div class="form-group">
          <label for="type">Tipe Stok</label>
          <select name="type" class="form-control" id="exampleFormControlSelect1" value=" {{ old('type') }}">>
            <option value="0">makanan</option>
            <option value="1">minuman</option>
          </select>          
          @error('type')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
        <div class="form-group">
          <label for="unit">Ketersediaan</label>
          <select name="unit" class="form-control" id="exampleFormControlSelect1" value=" {{ old('unit') }}">>
            <option value="1">Pcs</option>
            <option value="2">Pack</option>
            <option value="3">Bulk(gr)</option>
            <option value="0">Not Specified</option>
          </select>          
          @error('unit')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
        <div class="form-group">
          <label for="counted">Hitung Otomatis</label>
          <select name="counted" class="form-control" id="exampleFormControlSelect1" value=" {{ old('counted') }}">>
            <option value="1">Ya</option>
            <option value="0">Tidak</option>
          </select>          
          @error('counted')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
        <div class="form-group">
          <label for="amount">Jumlah Stok</label>
          <input id="amount" type="text" class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount') }}" required autocomplete="nama" autofocus>
          @error('amount')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>

        
        <button type="submit" class="btn btn-primary">Simpan</button>
      </form>
    </div>
  </div>
</div>

@endsection