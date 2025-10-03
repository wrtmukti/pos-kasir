@extends('layouts.layout')

@section('content')

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
<div class="row mt-3">
  <div class="col-12">
      <div class="section-title">
          <h2>Status Pesanan</h2>
          <p>cek secara berkala untuk melihat status pesanan kamu :)</p>
      </div>
  </div>
</div>

@if ($orders->count() == 0)
<div class="alert alert-danger text-center">
  Belum ada pesanan
</div>
@else
  <div class="row justify-content-center my-3">
    <div class="col-md-12">
      <div class="card shadow p-lg-2">
        <div class="table-responsive">
          <table class="table table-hover">
            <div class="thead">
              <tr>
                <th class="text-center" style="width: 20%">Meja</th>
                <th class="text-center">Status Pesanan</th>
              </tr>
            </div>
            <tbody>
              @foreach ($orders as $data)
              <tr>
                <td class="text-center" style="font-size: 12px">{{ $data->customer->no_table }}</td>
                <td class="text-center">
                @switch($type = $data->status)
                    @case(0)
                        <a class="btn btn-danger rounded-pill "  style="font-size: 12px">Pesanan dikirim</a>
                        @break
                    @case(1)
                        <a class="btn btn-warning rounded-pill"  style="font-size: 12px">Pesanan diterima</a>
                        @break
                    @case(2)
                        <a class="btn btn-success rounded-pill"  style="font-size: 12px">Menunggu pembayaran</a>
                        @break
                    @case(3)
                      <a class="btn btn-primary rounded-pill"  style="font-size: 12px">Selesai</a>
                        @break
                    @case(4)
                      <a class="btn btn-secondary rounded-pill"  style="font-size: 12px">Ditolak :(</a>
                      <p class="text-center"> "{{ $data->note }}"</p>
                        @break
                    @default
                @endswitch
                </td>
                
                @endforeach
                </tr>
              </tbody>
          </table>
        </div>
      </div>
      
    </div>
  </div>
@endif


@endsection