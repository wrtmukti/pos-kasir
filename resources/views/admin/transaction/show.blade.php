@extends('admin.layouts.layout')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="row my-3">
      <h2 class="fw-bold text-center">Transaksi ID #{{ $transaction->id }}</h2>
    </div>
    {{-- transaction --}}
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card shadow">
         @if ($transaction->payment_status == 0)
           <div class="card-header bg-primary">
             <h3 class="text-center my-3 fw-bold text-light">Pemasukan</h3>
           </div>   
           @else
           <div class="card-header bg-danger">
             <h3 class="text-center my-3 fw-bold text-light">Pengeluaran</h3>
           </div>   
         @endif
         <div class="card-body">
          <div class="row">
            <div class=" col-md-4 border-end">
              <h4 class="text-center fw-bold text-dark">Cash Rp. {{ $transaction->cash }},-</h4>
            </div>
            <div class=" col-md-4 border-end">
              @if ($transaction->debit == 0)
              <h4 class="text-center fw-bold text-dark">Debit Rp. 0,-</h4>
              @else
              <h4 class="text-center fw-bold text-dark">Debit Rp.{{ $transaction->debit }},-</h4>
              @endif
            </div>
            <div class=" col-md-4 ">
              <h4 class="text-center fw-bold text-dark">Total Rp. {{ $transaction->total_price }},-</h4>
            </div>
          </div>
          <hr>
          <div class="row mt-3">
            <h4 class="text-center fw-bold text-dark">Keterangan:</h4>
          </div>
          <div class="row justify-content-center">
            <div class="col-md-6 p-3">
              <h5 class="text-center text-dark">"{{ $transaction->note }}"</h4>
            </div>
          </div>
         </div>
        </div>
      </div>
    </div>
    {{-- order --}}
    @if ($transaction->orders->count() > 0)
      <div class="row mt-5 mb-3 justify-content-center">
        <div class="col-md-8">
          <h2 class="fw-bold">Pesanan</h2>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-md-8">
          <div class="card shadow py-3 table-responsive p-3"  >
            <table class="table table-hover" id="saleTable">
              <thead>
                <th class="text-center">Tipe</th>
                <th class="text-center">total</th>
              </thead>
              <tbody>
                @foreach ($transaction->orders as $data)
                    
                <tr>
                  <td>
                    @if ($data->status == 0)
                    <a href="/admin/order/online/{{ $data->id }}" class="nav-link text-center text-dark">Pesanan Online</a>
                    @else
                    <a href="/admin/order/manual/{{ $data->id }}" class="nav-link text-center text-dark">Pesanan Manual</a>
                        
                    @endif                  
                  </td>
                  <td>
                    @if ($data->status == 0)
                    <a href="/admin/order/online/{{ $data->id }}" class="nav-link text-center text-dark">Rp. {{ $data->price }},-</a>
                    @else
                    <a href="/admin/order/manual/{{ $data->id }}" class="nav-link text-center text-dark">Rp. {{ $data->price }},-</a>
                        
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    @endif
  </div>
</div>
@endsection