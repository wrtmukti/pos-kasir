@extends('admin.layouts.layout')

@section('title', isset($voucher) ? 'Edit Voucher' : 'Tambah Voucher')

@section('content')

<div class="row my-4">
  <h2 class="text-center fw-bold">
    {{ isset($voucher) ? 'Edit Voucher' : 'Tambah Voucher Baru' }}
  </h2>
</div>

<div class="card shadow p-5">
  <div class="row justify-content-center">
    <div class="col-md-8">

      {{-- ALERT SUCCESS / ERROR --}}
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

      {{-- FORM VOUCHER --}}
      <form action="{{ isset($voucher) ? route('voucher.update', $voucher->id) : route('voucher.store') }}" method="POST">
        @csrf
        @if(isset($voucher))
          @method('PUT')
        @endif

        {{-- Nama Voucher --}}
        <div class="form-group mb-3">
          <label for="name" class="form-label fw-semibold">Nama Voucher</label>
          <input type="text" name="name" id="name" 
                 class="form-control @error('name') is-invalid @enderror"
                 value="{{ old('name', $voucher->name ?? '') }}" required>
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Kode Voucher --}}
        <div class="form-group mb-3">
          <label for="code" class="form-label fw-semibold">Kode Voucher</label>
          <input type="text" name="code" id="code" 
                 class="form-control @error('code') is-invalid @enderror"
                 value="{{ old('code', $voucher->code ?? '') }}" required>
          @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Jenis Voucher --}}
        <div class="form-group mb-3">
          <label for="voucher_type" class="form-label fw-semibold">Tipe Voucher</label>
          <select name="voucher_type" id="voucher_type" 
                  class="form-select @error('voucher_type') is-invalid @enderror" required>
            <option value="">-- Pilih Jenis Voucher --</option>
            <option value="0" {{ old('voucher_type', $voucher->voucher_type ?? '') == 0 ? 'selected' : '' }}>Persentase (%)</option>
            <option value="1" {{ old('voucher_type', $voucher->voucher_type ?? '') == 1 ? 'selected' : '' }}>Potongan Harga (Rp)</option>
          </select>
          @error('voucher_type')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Nilai Voucher --}}
        <div class="form-group mb-3">
          <label for="value" class="form-label fw-semibold">Nilai Voucher</label>
          <input type="number" name="value" id="value" 
                 class="form-control @error('value') is-invalid @enderror"
                 value="{{ old('value', $voucher->value ?? '') }}" required>
          @error('value')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Balance --}}
        <div class="form-group mb-3">
          <label for="balance" class="form-label fw-semibold">Sisa Kuota Voucher</label>
          <input type="number" name="balance" id="balance"
                 class="form-control @error('balance') is-invalid @enderror"
                 value="{{ old('balance', $voucher->balance ?? '') }}">
          @error('balance')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Waktu Mulai & Berakhir --}}
        <div class="row mb-3">
          <div class="col">
            <label for="starttime" class="form-label fw-semibold">Mulai Berlaku</label>
            <input type="datetime-local" name="starttime"
                   class="form-control @error('starttime') is-invalid @enderror"
                   value="{{ old('starttime', isset($voucher) ? date('Y-m-d\TH:i', strtotime($voucher->starttime)) : '') }}">
            @error('starttime')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col">
            <label for="endtime" class="form-label fw-semibold">Berakhir Pada</label>
            <input type="datetime-local" name="endtime"
                   class="form-control @error('endtime') is-invalid @enderror"
                   value="{{ old('endtime', isset($voucher) ? date('Y-m-d\TH:i', strtotime($voucher->endtime)) : '') }}">
            @error('endtime')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        {{-- Status --}}
        <div class="form-group mb-3">
          <label for="status" class="form-label fw-semibold">Status Voucher</label>
          <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="0" {{ old('status', $voucher->status ?? '') == 0 ? 'selected' : '' }}>Non Aktif</option>
            <option value="1" {{ old('status', $voucher->status ?? '') == 1 ? 'selected' : '' }}>Aktif</option>
          </select>
          @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Catatan --}}
        <div class="form-group mb-4">
          <label for="note" class="form-label fw-semibold">Catatan</label>
          <textarea name="note" id="note" rows="3" 
                    class="form-control @error('note') is-invalid @enderror">{{ old('note', $voucher->note ?? '') }}</textarea>
          @error('note')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Tombol Aksi --}}
        <div class="d-flex justify-content-between">
          <a href="{{ route('voucher.index') }}" class="btn btn-secondary">Kembali</a>
          <button type="submit" class="btn btn-primary">
            {{ isset($voucher) ? 'Update Voucher' : 'Simpan Voucher' }}
          </button>
        </div>

      </form>

    </div>
  </div>
</div>

@endsection
