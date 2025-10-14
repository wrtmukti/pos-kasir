@extends('admin.layouts.layout')

@section('title', isset($diskon) ? 'Edit Diskon' : 'Tambah Diskon')

@section('content')

<div class="row my-4">
  <h2 class="text-center fw-bold">
    {{ isset($diskon) ? 'Edit Diskon' : 'Tambah Diskon Baru' }}
  </h2>
</div>

<div class="card shadow p-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      
      {{-- ALERT (jika sukses/gagal) --}}
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @elseif(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      {{-- FORM DISKON --}}
      <form action="{{ isset($diskon) ? route('discount.update', $diskon->id) : route('discount.store') }}" method="POST">
        @csrf
        @if(isset($diskon))
          @method('PUT')
        @endif

        <div class="form-group mb-3">
          <label for="keterangan" class="form-label fw-semibold">Keterangan</label>
          <input type="text" name="keterangan" id="keterangan" 
                 class="form-control @error('keterangan') is-invalid @enderror"
                 value="{{ old('keterangan', $diskon->keterangan ?? '') }}" required>
          @error('keterangan')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group mb-3">
          <label for="id_product" class="form-label fw-semibold">Produk</label>
          <select name="id_product" class="form-select @error('id_product') is-invalid @enderror" required>
            <option value="">-- Pilih Produk --</option>
            @foreach($products as $product)
              <option value="{{ $product->id }}" 
                {{ old('id_product', $diskon->id_product ?? '') == $product->id ? 'selected' : '' }}>
                {{ $product->name }}
              </option>
            @endforeach
          </select>
          @error('id_product')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group mb-3">
          <label for="type_diskon" class="form-label fw-semibold">Tipe Diskon</label>
          <select name="type_diskon" class="form-select @error('type_diskon') is-invalid @enderror" required>
            <option value="0" {{ old('type_diskon', $diskon->type_diskon ?? '') == 0 ? 'selected' : '' }}>Presentase (%)</option>
            <option value="1" {{ old('type_diskon', $diskon->type_diskon ?? '') == 1 ? 'selected' : '' }}>Potongan Harga (Rp)</option>
          </select>
          @error('type_diskon')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group mb-3">
          <label for="value" class="form-label fw-semibold">Nilai Diskon</label>
          <input type="number" name="value" id="value"
                 class="form-control @error('value') is-invalid @enderror"
                 value="{{ old('value', $diskon->value ?? '') }}" required>
          @error('value')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="row mb-3">
          <div class="col">
            <label for="start_date" class="form-label fw-semibold">Mulai Berlaku</label>
            <input type="datetime-local" name="start_date"
                   class="form-control @error('start_date') is-invalid @enderror"
                   value="{{ old('start_date', isset($diskon) ? date('Y-m-d\TH:i', strtotime($diskon->start_date)) : '') }}" required>
            @error('start_date')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col">
            <label for="end_date" class="form-label fw-semibold">Berakhir Pada</label>
            <input type="datetime-local" name="end_date"
                   class="form-control @error('end_date') is-invalid @enderror"
                   value="{{ old('end_date', isset($diskon) ? date('Y-m-d\TH:i', strtotime($diskon->end_date)) : '') }}" required>
            @error('end_date')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="form-group mb-4">
          <label for="status" class="form-label fw-semibold">Status</label>
          <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="active" {{ old('status', $diskon->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $diskon->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
          </select>
          @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="d-flex justify-content-between">
          <a href="{{ route('discount.index') }}" class="btn btn-secondary">Kembali</a>
          <button type="submit" class="btn btn-primary">
            {{ isset($diskon) ? 'Update Diskon' : 'Simpan Diskon' }}
          </button>
        </div>

      </form>

    </div>
  </div>
</div>

@endsection