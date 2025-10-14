@extends('admin.layouts.layout')

@section('content')
<div class="row">

  {{-- ALERT SECTION --}}
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

  {{-- TITLE --}}
  <div class="row my-3">
    <h2 class="fw-bold text-center">Daftar Voucher</h2>
  </div>

  {{-- BUTTON TAMBAH --}}
  <div class="row my-3">
    <div class="col-6">
      <a href="{{ route('voucher.create') }}" class="btn btn-primary">+ Tambah Voucher</a>
    </div>
  </div>

  {{-- JIKA DATA KOSONG --}}
  @if ($vouchers->count() == 0)
    <div class="alert alert-danger text-center">
      Belum ada data voucher
    </div>
  @else
    {{-- TABEL --}}
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover" id="saleTable">
                <thead>
                  <tr>
                    <th class="text-center"><h5 class="fw-bold">Nama</h5></th>
                    <th class="text-center"><h5 class="fw-bold">Kode</h5></th>
                    <th class="text-center"><h5 class="fw-bold">Jenis</h5></th>
                    <th class="text-center"><h5 class="fw-bold">Nilai</h5></th>
                    <th class="text-center"><h5 class="fw-bold">Kuota</h5></th>
                    <th class="text-center"><h5 class="fw-bold">Mulai</h5></th>
                    <th class="text-center"><h5 class="fw-bold">Akhir</h5></th>
                    <th class="text-center"><h5 class="fw-bold">Status</h5></th>
                    @if (Auth::user()->role == 1)
                      <th class="text-center"><h5 class="fw-bold">Aksi</h5></th>
                    @endif
                  </tr>
                </thead>
                <tbody>
                  @foreach ($vouchers as $voucher)
                  <tr>
                    <td class="text-center">{{ $voucher->name }}</td>
                    <td class="text-center">{{ $voucher->code }}</td>

                    {{-- Jenis Voucher --}}
                    <td class="text-center">
                      {{ $voucher->voucher_type == 0 ? 'Persentase' : 'Potongan Harga' }}
                    </td>

                    {{-- Nilai --}}
                    <td class="text-center">
                      {{ $voucher->voucher_type == 0 ? $voucher->value . '%' : 'Rp ' . number_format($voucher->value, 0, ',', '.') }}
                    </td>

                    <td class="text-center">{{ $voucher->balance ?? '-' }}</td>

                    {{-- Waktu --}}
                    <td class="text-center">
                      {{ $voucher->starttime ? date('d M Y H:i', strtotime($voucher->starttime)) : '-' }}
                    </td>
                    <td class="text-center">
                      {{ $voucher->endtime ? date('d M Y H:i', strtotime($voucher->endtime)) : '-' }}
                    </td>

                    {{-- Status --}}
                    <td class="text-center">
                      <span class="badge bg-{{ $voucher->status == 1 ? 'success' : 'secondary' }}">
                        {{ $voucher->status == 1 ? 'Aktif' : 'Non Aktif' }}
                      </span>
                    </td>

                    {{-- Aksi --}}
                    @if (Auth::user()->role == 1)
                    <td class="text-center">
                      <a href="{{ route('voucher.edit', $voucher->id) }}" class="btn btn-warning btn-sm">Edit</a>
                      <form action="{{ route('voucher.destroy', $voucher->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Yakin ingin menghapus voucher ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                      </form>
                    </td>
                    @endif
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endif

</div>
@endsection
