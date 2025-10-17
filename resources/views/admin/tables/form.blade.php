@extends('admin.layouts.layout')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        {{ isset($meja) ? 'Edit Data Meja' : 'Tambah Meja Baru' }}
                    </h5>
                </div>
                <div class="card-body">
                    
                    {{-- Tampilkan Error Global di atas form --}}
                    @if ($errors->any() && !$errors->has('no_table') && !$errors->has('status'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Terjadi Kesalahan!</strong> Mohon periksa kembali input Anda.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form 
                        action="{{ isset($meja) ? route('meja.update', $meja->id) : route('meja.store') }}" 
                        method="POST">
                        @csrf
                        @if(isset($meja))
                            @method('PUT')
                        @endif

                        {{-- Input No Meja --}}
                        <div class="mb-3">
                            <label for="no_table" class="form-label fw-bold">Nomor Meja</label>
                            <input 
                                type="number" 
                                name="no_table" 
                                id="no_table"
                                class="form-control @error('no_table') is-invalid @enderror" 
                                value="{{ old('no_table', $meja->no_table ?? '') }}" 
                                placeholder="Cth: 1, 2, 10"
                                required
                                aria-describedby="no_table_help">
                            
                            {{-- Tampilkan Error Spesifik Field --}}
                            @error('no_table')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <div id="no_table_help" class="form-text">
                                Masukkan nomor unik untuk meja.
                            </div>
                        </div>

                        {{-- Select Status --}}
                        <div class="mb-4">
                            <label for="status" class="form-label fw-bold">Status Meja</label>
                            <select 
                                name="status" 
                                id="status"
                                class="form-select @error('status') is-invalid @enderror" 
                                required>
                                
                                {{-- Menggunakan 'selected' untuk mempertahankan pilihan atau nilai lama --}}
                                <option value="0" {{ old('status', $meja->status ?? null) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                                <option value="1" {{ old('status', $meja->status ?? null) == 1 ? 'selected' : '' }}>Aktif</option>
                            </select>
                            
                            @error('status')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        {{-- Aksi/Tombol --}}
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end pt-3 border-top">
                            <a href="{{ route('meja.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-success me-md-2">
                                <i class="bi bi-save"></i> 
                                {{ isset($meja) ? 'Simpan Perubahan' : 'Simpan Meja' }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection