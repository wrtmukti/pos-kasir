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
    <h2 class="fw-bold text-center">Karyawan</h2>
  </div>

  <div class="row my-3">
    <div class="col-6">
      <a href="/register" class="btn btn-primary">+ Karyawan</a>
    </div>
  </div>



  @if ($users->count() == 0)
    <div class="alert alert-danger text-center">
      Karyawan Kosong
    </div>
  @else
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-hover"  id="saleTable">
                <thead>
                  <tr>
                    <th class="" style="width: 50%;"><h5 class=" fw-bold">Nama </h5></th>
                    <th class="text-center"><h5 class=" fw-bold"> Posisi</h5></th>
                    <th class="text-center"><h5 class=" fw-bold"> Aksi</h5></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($users as $data)
                  <tr>
                    <td class="">
                      <div class="row">
                        <div class="col-3">
                          <img src="{{ asset('images/user/' . $data->image) }}" class=" imgProduct rounded-pill" alt="...">
                        </div>
                        <div class="col-9">
                          <a  class="nav-link  text-dark">{{ $data->name }}</a>
                        </div>
                      </div>
                    </td>
                    <td class="text-center">
                      @if ($data->role = 0)
                      <a  class="nav-link  text-dark">Staff</a>
                      @else
                      <a  class="nav-link  text-dark">Operator</a>
                      @endif
                    </td>
                    <td>
                      <form action="/admin/operator/employee/delete/{{$data->id}}" method="post" style="text-decoration: none">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="{{$data->id}}" value="DELETE">
                        <button type="sumbit" class="btn btn-danger text-center " onclick="return confirm('Yakin ingin menghapus produk?');">
                          Hapus</i>
                        </button>
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
  @endif


</div>
@endsection