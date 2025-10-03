@extends('admin.layouts.layout')
@section('content')
<div class="row">
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


  <div class="row my-3">
    <h2 class="fw-bold text-center">Laporan Penjualan</h2>
  </div>

  <div class="row my-3">
    <div class="col-3">
      <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuSizeButton2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Kategori
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuSizeButton2">
          <a class="dropdown-item" href="/admin/operator/report/0" class="fw-bold text-center">Harian</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="/admin/operator/report/1" class="fw-bold text-center">Bulanan</a>
        </div>
      </div>
    </div>
  </div>



  @if ($products->count() == 0)
    <div class="alert alert-danger text-center">
      Laporan Masih Kosong
    </div>
  @else
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover" id="saleTable">
                <thead>
                  <tr>
                    {{-- <th class="text-center" style="width: 60%;"><h5 class=" fw-bold">No</h5></th> --}}
                    <th class="text-center" style="width: 40%;"><h5 class=" fw-bold">Tanggal</h5></th>
                    <th class="text-center"><h5 class=" fw-bold"> Penjualan</h5></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($products as $date => $data)
                  
                  <tr>
                    {{-- <td class="text-center fw-bold "><a href="/admin/order/{{ $data->id }}" class="nav-link text-dark">{{ $loop->iteration }}</a></td> --}}
                    <td class="text-center">
                      <a href="/admin/operator/report/sale/{{ $date }}" class="nav-link  text-dark">{{ $date }}</a>
                    </td>
                    <td class="text-center">
                      <?php $total=0; ?>
                      @foreach ($data as $item)
                          <?php $product = $item->products->count(); ?>
                          <?php $total += $product; ?>
                      @endforeach
                      <a  href="/admin/operator/report/sale/{{ $date }}" class="nav-link  text-dark">{{ $total }} Produk Terjual</a>
                    </td>
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