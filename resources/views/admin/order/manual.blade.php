@extends('admin.layouts.layout')
@section('content')
<div class="row my-3">
  <h3 class="text-center">Daftar Pesanan</h3>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th class="text-center"><h5 class=" fw-bold">ORDER</h5></th>
                <th class="text-center"><h5 class=" fw-bold">STATUS</h5></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($orders as  $data)
                <tr>
                    <td class="text-center">
                      <div class="row">
                        <div class="">
                          <a href="/admin/order/manual/{{ $data->id }}"  class="badge badge-success nav-link fw-bold">Manual</a>
                          <a href="/admin/order/{{ $data->id }}" class="nav-link text-dark">{{ $data->created_at->diffForHumans(); }}</a>
                        </div>
                      </div>
                      {{-- <div class="row">
                        <a href="/admin/order/{{ $data->id }}" class="nav-link text-dark">{{ $data->updated_at->format("H:i") }}</a>
                      </div> --}}
                    </td>     
                  @switch($data->status)
                      @case(0)
                          <td class="text-center"><a class="btn btn-danger rounded-pill" href="/admin/order/manual/{{ $data->id }}"style="font-size: 12px">Pending</a></td> 
                          @break
                      @case(1)
                          <td class="text-center"><a class="btn btn-warning rounded-pill" href="/admin/order/manual/{{ $data->id }}"style="font-size: 12px">Progress</a></td>
                          @break
                      @case(2)
                          <td class="text-center"><a class="btn btn-success rounded-pill" href="/admin/order/manual/{{ $data->id }}"style="font-size: 12px">Payment</a></td>
                          @break
                          @case(3)
                          <td class="text-center"><a class="btn btn-primary rounded-pill" href="/admin/order/manual/{{ $data->id }} " style="font-size: 12px">Finished</a></td>
                          @break
                          @case(4)
                          <td class="text-center"><a class="btn btn-secondary rounded-pill" href="/admin/order/manual/{{ $data->id }} " style="font-size: 12px">Ditolak</a></td>
                          @break
                      @default
                          
                    @endswitch
                  
                  
                  {{-- <td class="text-danger"> 28.76% <i class="ti-arrow-down"></i></td> --}}
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection