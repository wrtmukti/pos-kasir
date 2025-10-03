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
                <th class="text-center"><h5 class=" fw-bold">No Table</h5></th>
                <th class="text-center"><h5 class=" fw-bold">Status</h5></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($tables as  $data)
                <tr>
                  <td class="text-center">
                    <h5 class="fw-bold">{{ $data->no_table }}</h5>
                  </td>
                  <td class="text-center">
                    <form action="/admin/table/{{$data->id}}" method="POST" style="text-decoration: none">
                      @csrf
                      @method('PUT')
                      @if ($data->status == 1)
                        <input type="hidden" name="status" value="0">
                        <button type="sumbit" class="btn btn-success text-center">
                          Aktif</i>
                        </button> 
                      @else
                      <input type="hidden" name="status" value="1">
                      <button type="sumbit" class="btn btn-dark text-center ">
                        Nonaktif</i>
                      </button> 
                      @endif
                      
                    </form> 
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
@endsection