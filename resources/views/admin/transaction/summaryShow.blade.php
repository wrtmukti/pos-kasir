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
    <h2 class="fw-bold text-center">Rekap Kas</h2>
    <p class="fw-bold text-center">{{ $date }}</p>
  </div>

  @if ($transactions->count() == 0)
    <div class="alert alert-danger text-center">
      Transaksi Masih Kosong
    </div>
  @else
    <div class="row my-3 justify-content-center">
      <div class="col-12 col-lg-8 col-md-12">
        <div class="card shadow">
          <?php 
                    $income = 0;
                    $outcome = 0;
                    $waiting = 0;
                    ?>
                    {{-- {{ dd($transactions->orders) }} --}}
                  @foreach ($transactions as $data)
                    @if ($data->payment_status === null)
                      <?php $waiting += $data->total_price ?>  
                    @else
                      @switch($data->payment_status)
                          @case(0)
                          <?php $income += $data->total_price ?>
                              @break
                          @case(1)
                              <?php $outcome += $data->total_price ?>
                              @break
                          @default
                      @endswitch    
                    @endif               
                  @endforeach
                  <?php 
                    $income_cash = 0;
                    $income_debit = 0;
                    $outcome_cash = 0;
                    $outcome_debit = 0;
                  ?>
                  @foreach ($transactions as $data)
                      @switch($data->payment_status)
                          @case(0)
                              <?php $income_cash += $data->cash ?>
                              <?php $income_debit += $data->debit ?>
                              @break
                          @case(1)
                              <?php $outcome_cash += $data->cash ?>
                              <?php $outcome_debit += $data->debit ?>
                              @break
                          @default
                      @endswitch
                  @endforeach
  
          {{-- pemasukan --}}
          <div class="card-header bg-light border-primary">
            <div class="row justify-content-center ">
              <div class="col-6 col-lg-3 col-md-3">
                <h4 class=" text-center text-primary">Pemasukan</h4>
              </div>
              <div class="col-6 col-lg-3 col-md-3">
                <p class="text-center text-dark">Cash : Rp. {{ $income_cash }},- </p>
              </div>
            </div>
            <div class="row justify-content-center ">
              <div class="col-6 col-lg-3 col-md-3">
                <p class="text-center text-dark"> Rp . {{ $income }},-</p>
              </div>
              <div class="col-6 col-lg-3 col-md-3">
                <p class="text-center text-dark">Debit : Rp. {{ $income_debit }},-</p>
              </div>
            </div>
          </div>
          {{-- pengeluaran --}}
          <div class="card-header bg-light border-primary">
            <div class="row justify-content-center ">
              <div class="col-6 col-lg-3 col-md-3">
                <h4 class="text-center text-danger">Pengeluaran</h4>
              </div>
              <div class="col-6 col-lg-3 col-md-3">
                <p class="text-center text-dark">Cash : Rp. {{ $outcome_cash }},- </p>
              </div>
            </div>
            <div class="row justify-content-center ">
              <div class="col-6 col-lg-3 col-md-3">
                <p class="text-center text-dark"> Rp . {{ $outcome }},-</p>
              </div>
              <div class="col-6 col-lg-3 col-md-3">
                <p class="text-center text-dark">Debit : Rp. {{ $outcome_debit }},-</p>
              </div>
            </div>
          </div>
          {{-- Total --}}
          <div class="card-header bg-secondary">
            <h4 class="text-center text-light">TOTAL</h4>
            <p class="text-center text-light"> Total : Rp . {{ $income - $outcome }},-</p>
            <div class="row justify-content-center ">
              <div class="col-6 col-lg-3 col-md-3">
                <p class="text-center text-light">Cash : Rp. {{$income_cash - $outcome_cash }},- </p>
              </div>
              <div class="col-6 col-lg-3 col-md-3">
                <p class="text-center text-light">Debit : Rp. {{$income_debit - $outcome_debit }},-</p>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>
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
                    <th class="text-center"><h5 class=" fw-bold">Transaksi</h5></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($transactions as $data)
                  <tr>
                    {{-- <td class="text-center fw-bold "><a href="/admin/transaction/{{ $data->id }}" class="nav-link text-dark">{{ $loop->iteration }}</a></td> --}}
                    <td class="text-center">
                      <a href="/admin/transaction/{{ $data->id }}" class="nav-link  text-dark">{{ $data->updated_at->format("d M Y") }}</a>
                      <a href="/admin/transaction/{{ $data->id }}" class="nav-link  text-dark">{{ $data->updated_at->format("H:i") }}</a>
                    </td>
                    @if ($data->payment_status !== null)
                      @if ($data->payment_status == 0)
                        <td class="text-center fw-bold "><a href="/admin/transaction/{{ $data->id }}" class="nav-link text-primary">+{{ $data->total_price }}</a></td>
                      @else
                        <td class="text-center fw-bold "><a href="/admin/transaction/{{ $data->id }}" class="nav-link text-danger">-{{ $data->total_price }}</a></td>
                      @endif
                    @else
                        <td class="text-center fw-bold "><a href="/admin/transaction/{{ $data->id }}" class="nav-link text-warning ">waiting</a></td> 
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
