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
</div>

<div class="row my-5">
  @if ($order->status == 3)
    <a type="button" href="/admin/transaction/invoice/manual/{{ $order->transaction_id }}" class="btn btn-dark text-white">
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
          
          @if ($order->status < 2)
            <p>Ordernya yang belum selesai ajg :(</p>
          @else
            <form action="/admin/transaction/payment" method="post">
              @csrf
              <div class="modal-body">
                <input type="hidden" name="order_type" value="1">
                <input type="hidden" name="order_id" value="{{ $order->id }}">
    
                <input type="hidden" name="payment_status" value="0">
                <div class="form-group">
                  <label for="total_price">Total yang harus dibayar</label>
                  <input id="total_price" type="text" class="form-control" name="total_price" value="{{ $order->price  }}" readonly="readonly" >
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