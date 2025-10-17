@extends('admin.layouts.layout')

@section('content')
<div class="container py-4">
    
    {{-- Header dan Tombol Tambah --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-primary">Daftar Meja</h1>
        <a href="{{ route('meja.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-lg"></i> Tambah Meja
        </a>
    </div>

    {{-- Alert Sukses --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Kartu Tabel --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom fw-bold text-secondary">
            Data Meja yang Tersedia
        </div>
        
        <div class="card-body p-0">
            
            @if ($mejas->isEmpty())
                <div class="p-4 text-center text-muted">
                    <i class="bi bi-info-circle-fill me-2"></i> Belum ada data meja. Silakan tambahkan meja baru.
                </div>
            @else
                {{-- Table Responsive untuk Mobile --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">No Meja</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mejas as $meja)
                            <tr>
                                <td class="fw-bold fs-5">{{ $meja->no_table }}</td>
                                
                                <td>
                                    @if ($meja->status == 1)
                                        <span class="badge bg-success py-2 px-3">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-danger py-2 px-3">
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </td>
                                
                                <td>
                                    <div class="d-flex flex-nowrap justify-content-center">
                                        {{-- Tombol QR --}}
                                        <a href="{{ route('meja.qr', $meja->id) }}" 
                                           class="btn btn-outline-primary btn-sm me-2" 
                                           title="Lihat & Cetak QR">
                                            <i class="mdi mdi-qrcode-scan"></i>
                                        </a>

                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('meja.edit', $meja) }}" class="btn btn-outline-warning btn-sm me-2" title="Edit">
                                            <i class="mdi mdi-tooltip-edit"></i>
                                        </a>

                                        {{-- Tombol Hapus --}}
                                        <button 
                                            type="button" 
                                            class="btn btn-outline-danger btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal" 
                                            data-id="{{ $meja->id }}" 
                                            data-no-table="{{ $meja->no_table }}" 
                                            title="Hapus">
                                            <i class="mdi mdi-delete-forever"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md"> 
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold text-dark" id="deleteModalLabel">
                    <i class="bi bi-exclamation-octagon-fill text-danger me-2"></i> Perhatian!
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-0 text-center">
                <i class="bi bi-trash-fill text-danger" style="font-size: 3rem;"></i> 
                
                <h4 class="mt-3 mb-2 fw-bold">Konfirmasi Penghapusan</h4>
                
                <p class="text-secondary">
                    Anda yakin ingin menghapus Meja <b>No. <span id="modal-no-table" class="fw-bolder text-dark"></span></b>? 
                    <br> Aksi ini bersifat permanen dan <b>tidak dapat dibatalkan</b>.
                </p>
            </div>
            <div class="modal-footer d-flex justify-content-center border-top-0 pt-0 pb-3">
                <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">
                    Batal
                </button>
                
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger shadow-sm">
                        <i class="bi bi-trash"></i> Ya, Hapus Data Ini
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteModal = document.getElementById('deleteModal');
        
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget; 
                const mejaId = button.getAttribute('data-id');
                const mejaNoTable = button.getAttribute('data-no-table');
                
                const modalNoTable = deleteModal.querySelector('#modal-no-table');
                const deleteForm = deleteModal.querySelector('#deleteForm');
                
                if (modalNoTable) {
                    modalNoTable.textContent = mejaNoTable;
                }

                const routeUrl = '{{ route("meja.destroy", ":id") }}';
                deleteForm.action = routeUrl.replace(':id', mejaId); 
            });
        }
    });
</script>
@endpush
@endsection
