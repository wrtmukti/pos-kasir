@extends('admin.layouts.layout')
@section('content')


<div class="row my-3">
  <p class="display-4 text-center fw-bold">Update Stok "{{ $stock->name }}"</p>
</div>

<div class="card shadow p-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <form action="/admin/operator/stock/update/{{ $stock->id }}" method="POST">
        @csrf
        @method('PUT')
        {{-- Nama Stok --}}
        <div class="form-group">
          <label for="name">Nama Stok</label>
          <input id="name" 
                 type="text" 
                 class="form-control @error('name') is-invalid @enderror" 
                 name="name" 
                 value="{{ old('name', $stock->name) }}" 
                 required 
                 autofocus>
          @error('name')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>

        {{-- Tipe Stok --}}
        <div class="form-group">
          <label for="type">Tipe Stok</label>
          <select name="type" class="form-control" id="type" required>
            <option value="0" {{ old('type', $stock->type) == 0 ? 'selected' : '' }}>Makanan</option>
            <option value="1" {{ old('type', $stock->type) == 1 ? 'selected' : '' }}>Minuman</option>
          </select>          
          @error('type')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>

        {{-- Unit --}}
        <div class="form-group">
          <label for="unit">Ketersediaan</label>
          <select name="unit" class="form-control" id="unit" required>
            <option value="1" {{ old('unit', $stock->unit) == 1 ? 'selected' : '' }}>Pcs</option>
            <option value="2" {{ old('unit', $stock->unit) == 2 ? 'selected' : '' }}>Pack</option>
            <option value="3" {{ old('unit', $stock->unit) == 3 ? 'selected' : '' }}>Bulk(gr)</option>
            <option value="0" {{ old('unit', $stock->unit) == 0 ? 'selected' : '' }}>Not Specified</option>
          </select>          
          @error('unit')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>

        {{-- Hitung Otomatis --}}
        <div class="form-group">
          <label for="counted">Hitung Otomatis</label>
          <select name="counted" class="form-control" id="counted" required>
            <option value="1" {{ old('counted', $stock->counted) == 1 ? 'selected' : '' }}>Ya</option>
            <option value="0" {{ old('counted', $stock->counted) == 0 ? 'selected' : '' }}>Tidak</option>
          </select>          
          @error('counted')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>

        {{-- Jumlah Stok --}}
        <div class="form-group">
          <label for="amount">Jumlah Stok</label>
          <input id="amount" 
                 type="text" 
                 class="form-control @error('amount') is-invalid @enderror" 
                 name="amount" 
                 value="{{ old('amount', $stock->amount) }}" 
                 required>
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