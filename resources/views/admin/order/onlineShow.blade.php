@extends('admin.layouts.layout')
@section('content')
{{-- {{ dd($transaction_id) }} --}}

<div class="row my-3">
      <h2 class="text-center text-dark fw-bold">Pesanan Meja No. {{ $customer->no_table }}</h2>
</div>

<hr>

@foreach ($orders as $order)
  <div class="row mt-3 justify-content-start">
    <div class="col">
      <h5>Pesanan ke-{{ $loop->iteration }}</h5>
    </div>
    <div class="col">
      <p class="text-success"> ({{ $order->created_at->diffForHumans(); }})</p>
    </div>
  </div>
  <div class="row ">
    <div class="card ">
      <div class="card-body">
        <div class="row">
          @foreach ($order->products as $data)
              <div class=" col-md-3 mb-3">
                <div class="card">
                  <div class="row">
                    <div class="col-6">
                      <img src="{{ asset('images/product/' . $data->image) }}" class="card-img-top imgProduct rounded" alt="...">
                    </div>
                    <div class="col-6 p-3">
                      <p class="card-text fw-bold">{{ $data->name }} </p>
                      <p class="card-text fw-bold"> x {{ $data->pivot->quantity }} pcs</p>
                      <p class="card-text ">Rp. {{ $data->price * $data->pivot->quantity }},-</p>
                    </div>
                  </div>
                </div>
              </div>
          @endforeach
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
            <div class="row">
              <div class="col-6">
                {{-- terima --}}
                <div class="row px-2">
                  <button type="button" class="btn btn-warning rounded-pill mb-3 p-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Terima 
                  </button>
                </div>
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                              <label for="product" class="fw-bold">Produk ke-{{ $loop->iteration }}</label>
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
                              <hr>
                            </div>
                          @endforeach
                          <div class="form-group">
                            <label for="amount">Total Harga Pemesanan </label>
                            <input class="form-control text-center" id="amount" type="text" name="" value="{{ $order->price }}" readonly="readonly">
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
              <div class="col-6">
                {{-- tolak --}}
                <div class="row ">
                  <button type="button" class="btn btn-danger rounded-pill p-2" data-bs-toggle="modal" data-bs-target="#penolakan">
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
            </div>      
              
              @break
          @case(1)
              <form action="/admin/order/{{$order->id}}" method="post">
                @csrf
                {{ method_field('Patch') }}
                <input type="hidden"  name="status" value="2">
                <div class="row">
                  <button type="submit" class="btn btn-success rounded-pill p-2">Pesanan selesai dibuat</button>
                </div>
              </form>
              @break
          @case(2)
              <a  class="badge badge-primary nav-link rounded-pill p-2">Pesanan selesai</a>
              @break
          @case(3)
              <a  class="badge badge-success nav-link rounded-pill p-2">Sudah dibayar</a>
              @break
          @default
              
        @endswitch
        @if (Auth::user()->role == 1)
        <div class="row mt-2 justify-content-end">
          <form action="/admin/order/delete/{{$order->id}}" method="post" style="text-decoration: none">
            @csrf
            @method('DELETE')
            <input type="hidden" name="{{$order->id}}" value="DELETE">
            <div class="row">
              <button type="sumbit" class="btn btn-secondary nav-link rounded-pill p-2" onclick="return confirm('Yakin ingin menghapus order?');">
                Hapus pesanan</i>
              </button>
            </div>
          </form> 
        </div>
        @endif
      </div>
    </div>
  </div>
@endforeach

  <div class="row my-5">
    @if ($orders->where('status', '<', '3' )->count() == 0)
      <a type="button" href="/admin/transaction/invoice/online/{{ $transaction_id }}" class="btn btn-dark text-white">
        Cetak Struk
      </a>
      @else    
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#payment">
        Pembayaran
      </button>
    @endif 
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
                  <div class="form-group">
                    <label for="total_price">Total yang harus dibayar</label>
                    <input id="total_price" type="text" class="form-control" name="total_price" value="{{ $total  }}" readonly="readonly" >
                  </div>
                  <div class="form-group row text-center">
                    <div class="form-group col-6">
                      <label for="payment_method">Cash</label>
                      <input id="cash" type="text" class="form-control" name="cash" value="" placeholder="Cash"  >
                    </div>
                    <div class="form-group col-6">
                      <label for="payment_method">Debit</label>
                      <input id="debit" type="text" class="form-control" name="debit" value="" placeholder="Debit" >
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="kembalian">Kembalian</label>
                    <input id="kembalian" type="text" class="form-control" name="kembalian"  value="" readonly="readonly" >
                  </div>
                  
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