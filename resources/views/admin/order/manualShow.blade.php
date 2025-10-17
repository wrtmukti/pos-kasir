@extends('admin.layouts.layout')
@section('content')
{{-- {{ dd($order) }} --}}

<div class="row my-3">
  @if ($order->type == '0')
      <h2 class="text-center text-dark fw-bold">Pesanan Meja No. {{ $order->customer->no_table }}</h2>
      <span class="text-center">{{ $order->created_at->diffForHumans(); }}</span>
      @else
      <h2 class="text-center">Pesanan Customer ID {{ $order->id }}</h2>
      <span class="text-center">{{ $order->created_at->diffForHumans(); }}</span>
  @endif
</div>

<hr>

<div class="row ">
  <div class="card shadow-sm">
    <div class="card-body">
      <div class="row">
        <div class="product-section">
          <div class="row">
            @foreach ($order->products as $data)
              <div class="col-6 col-md-3 mb-4 d-flex">
                  <div class="card product-card shadow-sm w-100 rounded-3 overflow-hidden">
                    <img 
                      src="{{ asset('images/product/' . $data->image) }}" 
                      class="card-img-top product-image" 
                      alt="{{ $data->name }}"
                    >
                    <div class="card-body text-center d-flex flex-column justify-content-between">
                      <p class="mb-2">{{ $data->name }} <span class="fw-bold">x {{ $data->pivot->quantity }} pcs</span></p>

                      @php
                          $discount = $data->diskons->sortByDesc('created_at')->first();
                          $finalPrice = $data->price;

                          if ($discount) {
                              if ($discount->type_diskon == 0) {
                                  // diskon persentase
                                  $finalPrice = $data->price - ($data->price * ($discount->value / 100));
                              } else {
                                  // diskon nominal
                                  $finalPrice = max(0, $data->price - $discount->value);
                              }
                          }
                      @endphp

                      @if ($discount)
                        <p class="mb-0">
                          <span class="text-muted text-decoration-line-through">
                            Rp {{ number_format($data->price, 0, ',', '.') }}
                          </span>
                          <span class="fw-bold text-danger">
                            Rp {{ number_format($finalPrice, 0, ',', '.') }}
                          </span>
                        </p>
                      @else
                        <p class="fw-bold text-dark mb-0">
                          Rp {{ number_format($data->price, 0, ',', '.') }}
                        </p>
                      @endif
                    </div>
                    @if ($data->pivot->note !== null)    
                      <div class="card-footer rounded bg-secondary text-white shadow mb-3">
                        <div class="row  py-1 px-2 ">
                          <textarea class=" rounded" readonly="readonly">*{{ $data->pivot->note }}</textarea>
                        </div>
                      </div>
                    @endif
                  </div>
                </div>

            @endforeach
          </div>
          </div>
      </div>
    </div>
    <div class="card-footer rounded bg-secondary text-white shadow mb-2">
      <div class="row ">
        <span class="fw-bold text-center">Total: Rp. {{ $order->price }},-</span>
      </div>
    </div>
  </div>
</div>




<div class="row my-5">
  @switch($order->status)
    @case(1)
      <div class="row justify-content-end">
        @if (Auth::user()->role == 1)
          <div class="col-6 col-lg-1 text-right">
            <form action="/admin/order/delete/{{$order->id}}" method="post" style="text-decoration: none">
              @csrf
              @method('DELETE')
              <input type="hidden" name="{{$order->id}}" value="DELETE">
              <div class="row">
                <button type="sumbit" class="btn btn-secondary nav-link p-2" onclick="return confirm('Yakin ingin menghapus order?');">
                  Hapus</i>
                </button>
              </div>
            </form> 
          </div>
        @endif
        <div class="col-6 col-lg-1 me-lg-3">
            <form action="/admin/order/{{$order->id}}" method="post">
              @csrf
              {{ method_field('Patch') }}
              <input type="hidden"  name="status" value="2">
              <div class="row ps-2">
                <button type="submit" class="btn btn-success nav-link p-2">Pesanan dikirim</button>
              </div>
            </form>
        </div>
      </div>
        @break
    @case(2)
        <div class="row justify-content-end">
          @if (Auth::user()->role == 1)
            <div class="col-6 col-lg-1">
              <form action="/admin/order/delete/{{$order->id}}" method="post" style="text-decoration: none">
                @csrf
                @method('DELETE')
                <input type="hidden" name="{{$order->id}}" value="DELETE">
                <div class="row">
                  <button type="sumbit" class="btn btn-secondary nav-link p-2" onclick="return confirm('Yakin ingin menghapus order?');">
                    Hapus</i>
                  </button>
                </div>
              </form> 
            </div>
          @endif
          <div class="col-6 col-lg-1 me-lg-3">
            <a  role="button" class="badge badge-success nav-link rounded-pill p-2">Menunggu Pembayaran</a>
          </div>
        </div>
        @break
    @case(3)
    <div class="row justify-content-end">
      @if (Auth::user()->role == 1)
        <div class="col-6 col-lg-1">
          <form action="/admin/order/delete/{{$order->id}}" method="post" style="text-decoration: none">
            @csrf
            @method('DELETE')
            <input type="hidden" name="{{$order->id}}" value="DELETE">
            <div class="row">
              <button type="sumbit" class="btn btn-secondary nav-link p-2" onclick="return confirm('Yakin ingin menghapus order?');">
                Hapus</i>
              </button>
            </div>
          </form> 
        </div>
      @endif
      <div class="col-6 col-lg-1 me-lg-3">
        <div class="row ms-2">
          <a  class="badge badge-success nav-link rounded-pill p-2">Sudah dibayar</a>
        </div>
      </div>
    </div>
        @break
    @default           
  @endswitch
</div>

<div class="row my-5 justify-content-end">
    <div class="col-12 col-lg-2 me-lg-5">
      @if ($order->status == 3)
        <a type="button" href="/admin/transaction/invoice/manual/{{ $order->transaction_id }}" class="btn btn-dark text-white">
          Cetak Struk
        </a>
        @else    
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#payment">
          Pembayaran
        </button>
      @endif 
    </div>
  <!-- Modal -->
  <div class="modal fade" id="payment" tabindex="-1" aria-labelledby="message" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          
          @if ($order->status < 2)
            <p>Ordernya yang belum selesai ajg :(</p>
          @else
            <form action="/admin/transaction/payment" method="post">
              @csrf
              <div class="modal-body">
                <input type="hidden" name="order_type" value="1">    
                <input type="hidden" name="payment_status" value="0">
                  @if($order->voucher_id)
                    <div class="row">
                      <label for="product" class="fw-bold">Voucher diterapkan</label>
                      <div class="col-6">
                        <div class="form-group">
                          <label for="amount">{{ $order->voucher->name }}</label>
                            @if($order->voucher->voucher_type === 0)
                          <input class="form-control text-center" id="amount" type="text" name="" value="Potongan {{$order->voucher->value}}%" readonly="readonly">
                          @elseif ($order->voucher->voucher_type === 1)
                          <input class="form-control text-center" id="amount" type="text" name="" value="Potongan Rp.{{ $order->voucher->value }}" readonly="readonly">
                          @endif
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-group">
                          <label for="amount">Jumlah Potongan</label>
                          @if($order->voucher->voucher_type === 0)
                          <input class="form-control text-center" id="amount" type="text" name="" value="-{{ $order->price * $order->voucher->value / 100  }}" readonly="readonly">
                          @elseif ($order->voucher->voucher_type === 1)
                          <input class="form-control text-center" id="amount" type="text" name="" value="-{{ $order->voucher->value }}" readonly="readonly">
                          @endif
                        </div>
                      </div>
                    </div> 
                    <div class="row">
                      <div class="form-group col-6">
                        <label for="total_price">Total Harga Pesanan</label>
                        <input id="" type="text" class="form-control" name="" value="{{ $order->price  }}" readonly="readonly" >
                      </div>
                      <div class="form-group col-6">
                        <label for="total_price" class="fw-bold">Total Pembayaran</label>
                          @if($order->voucher->voucher_type === 0)
                          <input class="form-control text-center" id="total_price" type="text" name="total_price" value="{{ $order->price - ($order->price * $order->voucher->value / 100 ) }}" readonly="readonly">
                          @elseif ($order->voucher->voucher_type === 1)
                          <input class="form-control text-center" id="total_price" type="text" name="total_price" value="{{ $order->price - ( $order->voucher->value) }}" readonly="readonly">
                          @endif
                      </div>
                    </div>
                  @else
                    <div class="form-group">
                        <label for="total_price" class="fw-bold">Total Pembayaran</label>
                        <input id="total_price" type="text" class="form-control" name="total_price" value="{{ $order->price   }}" readonly="readonly" >
                      </div>
                  @endif
                <div class="form-group">
                  <label for="payment_method">Metode Pembayaran</label>
                  <select name="payment_method" class="form-control" id="payment_method" value=" {{ old('payment_status') }}">
                    <option value="">-- Pilih Metode Pembayaran --</option>
                    <option value="cash">Cash</option>
                    <option value="debit">Debit</option>
                  </select>
                </div>
                <div id="payment_fields" class="form-group row d-none">
                  <div class="form-group col-6">
                      <label for="payment_method">Total dibayar</label>
                      <input id="cash" type="text" class="form-control" name="value" value="" placeholder="Total dibayar"  >
                  </div>
                  <div class="form-group col-6" id="kembalian_wrapper">
                    <label for="kembalian">Kembalian</label>
                    <input id="kembalian" type="text" class="form-control" name="kembalian"  value="" readonly="readonly" >
                  </div>
                </div>
                {{-- <div class="form-group row text-center">
                  <div class="form-group col-6">
                    <label for="payment_method">Cash</label>
                    <input id="cash" type="text" class="form-control" name="cash" value="" placeholder="Cash"  >
                  </div>
                  <div class="form-group col-6">
                    <label for="payment_method">Debit</label>
                    <input id="debit" type="text" class="form-control" name="debit" value="" placeholder="Debit" >
                  </div>
                </div> --}}
                {{-- <div class="form-group">
                  <label for="kembalian">Kembalian</label>
                  <input id="kembalian" type="text" class="form-control" name="kembalian"  value="" readonly="readonly" >
                </div> --}}
                
              </div>
              <div class="modal-footer">
                <div class="text-end">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                  <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
              </div>
            </form>
          @endif
        </div>
      </div>
    </div>
  </div> 

</div>

@endsection