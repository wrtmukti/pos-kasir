@extends('admin.layouts.layout')
@section('content')
{{-- {{ dd($transaction_id) }} --}}

<div class="row my-3">
      <h2 class="text-center text-dark fw-bold">Pesanan Meja  {{ $customer->no_table }}</h2>
</div>

<hr>

@foreach ($orders as $order)
  <div class="row mt-3 justify-content-start">
    <div class="col">
      <p class="fw-bold">Pesanan ke-{{ $loop->iteration }}<span class="text-success"> ({{ $order->created_at->diffForHumans(); }})</span></p>
    </div>
  </div>
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
      @if ($order->note !== null)    
        <div class="card-footer rounded bg-secondary text-white shadow mb-3">
          <div class="row  py-1 px-2 ">
            <textarea class=" rounded" readonly="readonly">*{{  $order->note }}</textarea>
          </div>
        </div>
      @endif
      <div class="row mb-3 px-2">
        @switch($order->status)
          @case(0)
            <div class="row justify-content-end">
              @if (Auth::user()->role == 1)
                  <div class="col-4 col-lg-1">
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
              
              <div class="col-4 col-lg-1">
                {{-- tolak --}}
                <div class="row px-2">
                  <button type="button" class="btn btn-danger p-2" data-bs-toggle="modal" data-bs-target="#penolakan">
                    Tolak
                  </button>
                </div>
                
                <div class="modal fade" id="penolakan" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Pesanan Meja No. {{ $order->customer->no_table }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form action="/admin/order/{{$order->id}}" method="post">
                        @csrf
                        {{ method_field('Patch') }}
                        <div class="modal-body">            
                          <input type="hidden" name="status" value="4">
                          <div class="form-group">
                            <label for="note">Catatan </label>
                            <textarea class="form-control" id="note" type="text" name="note" ></textarea>
                          </div>
                        </div>
                  
                        <div class="modal-footer">
                          <button type="submit" class="btn btn-danger rounded-pill">Tolak Pesanan</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-4 col-lg-1  me-lg-3">
                {{-- terima --}}
                <div class="row">
                  <button type="button" class="btn btn-warning mb-3 p-2" data-bs-toggle="modal" data-bs-target="#exampleModal{{ $order->id }}">
                    Terima 
                  </button>
                </div>
                <div class="modal fade" id="exampleModal{{ $order->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Pesanan Meja No. {{ $order->customer->no_table }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form action="/admin/order/{{$order->id}}" method="post">
                        @csrf
                        {{ method_field('Patch') }}
                        <div class="modal-body">            
                          <input type="hidden" name="status" value="1">
                          @foreach ($order->products as $data)
                            <input class="form-group" type="hidden" name="product_id[]" value="{{ $data->id }}" readonly="readonly">
                            <div class="form-group">
                              {{-- <label for="product" class="fw-bold">Produk ke-{{ $loop->iteration }}</label> --}}
                              <div class="row">
                                <div class="col-6">
                                  <label for="name">Produk</label>
                                  <input class="form-control" id="name" type="text" id="product" name="" value="{{ $data->name }}" readonly="readonly">
                                </div>
                                <div class="col-6">
                                  <label for="name">Jumlah</label>
                                  <input class=" form-control text-center" id="amount" type="text" name="amount[]" value="{{ $data->pivot->quantity }}" readonly="readonly">
                                </div>
                              </div>
                             
                            </div>
                          @endforeach

                          <div class="form-group">
                            <label for="amount">Total Harga Pesanan </label>
                            <input class="form-control text-center" id="amount" type="text" name="" value="{{ $order->price }}" readonly="readonly">
                          </div>
                                                    <hr>
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
                          @endif
                          <div class="form-group">
                            <label for="amount">Total Pembayaran </label>
                            <input class="form-control text-center fw-bold" id="amount" type="text" name="total_payment" value="{{ $order->total_payment }}" readonly="readonly">
                          </div>
                        </div>
                  
                        <div class="modal-footer">
                          <button type="submit" class="btn btn-warning rounded-pill">Terima Pesanan</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>  
            </div>      
              
              @break
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
    </div>
  </div>
@endforeach

  <div class="row my-5 justify-content-end">
    <div class="col-12 col-lg-2 me-lg-5">
        @if ($orders->where('status', '<', '3' )->count() == 0)
          <div class="row">
            <a type="button" href="/admin/transaction/invoice/online/{{ $transaction_id }}" class="btn btn-dark text-white">
              Cetak Struk
            </a>
          </div>
        @else    
          <div class="row">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#payment">
              Proses Pembayaran
            </button>
          </div>
        @endif 
    </div>
   
    <!-- Modal -->
    <div class="modal fade" id="payment" tabindex="-1" aria-labelledby="message" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            
            @if ($orders->where('status', '<', '2')->count() > 0)
              <p>Ada order yang belum selesai nih :(</p>
            @else
            
              @php
                  $total = 0;
                  $order_finish = $orders->where('status', '2');
              @endphp
              @foreach ($orders as $order)
              <?php $total += $order->price ?>
              @endforeach
              <form action="/admin/transaction/payment" method="post">
                @csrf
                <div class="modal-body">
                  <input type="hidden" name="order_type" value="0">
                  <input type="hidden" name="customer_id" value="{{ $customer->id }}">
      
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
                          <input id="" type="text" class="form-control" name="" value="{{ $total  }}" readonly="readonly" >
                        </div>
                        <div class="form-group col-6">
                          <label for="total_price" class="fw-bold">Total Pembayaran</label>
                            @if($order->voucher->voucher_type === 0)
                            <input class="form-control text-center" id="total_price" type="text" name="total_price" value="{{ $total - ($order->price * $order->voucher->value / 100 ) }}" readonly="readonly">
                            @elseif ($order->voucher->voucher_type === 1)
                            <input class="form-control text-center" id="total_price" type="text" name="total_price" value="{{ $total - ( $order->voucher->value) }}" readonly="readonly">
                            @endif
                        </div>
                      </div>
                    @else
                      <div class="form-group">
                          <label for="total_price" class="fw-bold">Total Pembayaran</label>
                          <input id="total_price" type="text" class="form-control" name="total_price" value="{{ $total  }}" readonly="readonly" >
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