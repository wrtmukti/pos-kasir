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
    <h2 class="fw-bold text-center">Daftar Diskon</h2>
  </div>

  {{-- BUTTON TAMBAH --}}
  <div class="row my-3">
    <div class="col-6">
      <a href="{{ route('discount.create') }}" class="btn btn-primary">+ Tambah Diskon</a>
    </div>
  </div>

  {{-- JIKA DATA KOSONG --}}
  @if ($discounts->count() == 0)
    <div class="alert alert-danger text-center">
      Belum ada data diskon
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
                    <th class="text-center"><h5 class="fw-bold">Keterangan</h5></th>
                    <th class="text-center d-none d-sm-table-cell"><h5 class="fw-bold">Produk</h5></th>
                    <th class="text-center d-none d-sm-table-cell"><h5 class="fw-bold">Jenis</h5></th>
                    <th class="text-center d-none d-sm-table-cell"><h5 class="fw-bold">Nilai</h5></th>
                    <th class="text-center d-none d-sm-table-cell"><h5 class="fw-bold">Mulai</h5></th>
                    <th class="text-center d-none d-sm-table-cell"><h5 class="fw-bold">Akhir</h5></th>
                    <th class="text-center"><h5 class="fw-bold">Status</h5></th>
                    @if (Auth::user()->role == 1)
                      <th class="text-center d-none d-sm-table-cell"><h5 class="fw-bold">Aksi</h5></th>
                    @endif
                  </tr>
                </thead>
                <tbody>
                  @foreach ($discounts as $data)
                  <tr>
                    {{-- KETERANGAN --}}
                    <td class="text-center">
                      <a class="nav-link text-dark">{{ $data->keterangan }}</a>
                    </td>

                    {{-- PRODUK --}}
                    <td class="text-center d-none d-sm-table-cell">
                      <a class="nav-link text-dark">{{ $data->product->name ?? '-' }}</a>
                    </td>

                    {{-- JENIS DISKON --}}
                    <td class="text-center d-none d-sm-table-cell">
                      <a class="nav-link text-dark">
                        @if ($data->type_diskon == 1)
                          Potongan Harga
                        @elseif ($data->type_diskon == 0)
                          Persentase
                        @endif
                      </a>
                    </td>

                    {{-- NILAI --}}
                    <td class="text-center d-none d-sm-table-cell">
                      <a class="nav-link text-dark">
                        {{ $data->type_diskon == 0 ? $data->value . '%' : 'Rp ' . number_format($data->value, 0, ',', '.') }}
                      </a>
                    </td>

                    {{-- TANGGAL --}}
                    <td class="text-center d-none d-sm-table-cell">
                      <a class="nav-link text-dark">{{ date('d M Y H:i', strtotime($data->start_date)) }}</a>
                    </td>
                    <td class="text-center d-none d-sm-table-cell">
                      <a class="nav-link text-dark">{{ date('d M Y H:i', strtotime($data->end_date)) }}</a>
                    </td>

                    {{-- STATUS --}}
                    <td class="text-center">
                      <span class="badge bg-{{ $data->status == 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($data->status) }}
                      </span>
                    </td>

                    {{-- AKSI --}}
                    @if (Auth::user()->role == 1)
                    <td class="text-center d-none d-sm-table-cell">
                      <a href="{{ route('discount.edit', $data->id) }}" class="btn btn-warning btn-sm">Edit</a>
                      <form action="{{ route('discount.destroy', $data->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Yakin ingin menghapus diskon ini?')">
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