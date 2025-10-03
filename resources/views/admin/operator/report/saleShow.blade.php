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
    <p class=" text-center">Tanggal {{ $date }}</p>

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
                    <th class="text-center" style="width: 40%;"><h5 class=" fw-bold">Nama Produk</h5></th>
                    <th class="text-center"><h5 class=" fw-bold"> Penjualan</h5></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($products as $data)
                  
                  <tr>
                    {{-- <td class="text-center fw-bold "><a href="/admin/order/{{ $data->id }}" class="nav-link text-dark">{{ $loop->iteration }}</a></td> --}}
                    <td class="text-center">
                      <a  class="nav-link  text-dark">{{ $data->name }}</a>
                    </td>
                    <td class="text-center">
                     @php
                         
                         $product_id = $data->id;
                         $product = App\Models\Product::with('orders')->where('id', $product_id)->whereRelation('orders', DB::raw("(DATE_FORMAT(created_at,'%d-%m-%Y'))"), '=', $date)->get();
                     @endphp
                     <?php $quantity = 0; ?>
                     @foreach ($product as $item)
                         <?php $quantity += $item->pivot->quantity ?> 
                     @endforeach
                      <a   class="nav-link  text-dark">{{ $quantity }} item Terjual</a>
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