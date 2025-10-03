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
    <h2 class="fw-bold text-center">Stok</h2>
  </div>

  <div class="row my-3">
    <div class="col-6">
      <a href="/admin/stock/create" class="btn btn-primary">+ Stok</a>
    </div>
  </div>



  @if ($stocks->count() == 0)
    <div class="alert alert-danger text-center">
      Barang Masih Kosong
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
                    {{-- <th class="text-center" style="width: 60%;"><h5 class=" fw-bold">No</h5></th> --}}
                    <th class="text-center" style="width: 40%;"><h5 class=" fw-bold">Nama Stok</h5></th>
                    <th class="text-center"><h5 class=" fw-bold"> jumlah</h5></th>
                    @if (Auth::user()->role == 1)
                    <th class="text-center"><h5 class=" fw-bold"> Aksi</h5></th>
                    @endif
                  </tr>
                </thead>
                <tbody>
                  @foreach($stocks as $data)
                  
                  <tr>
                    {{-- <td class="text-center fw-bold "><a href="/admin/order/{{ $data->id }}" class="nav-link text-dark">{{ $loop->iteration }}</a></td> --}}
                    <td class="">
                      <a  class="nav-link  text-dark">{{ $data->name }}</a>
                    </td>
                    <td class="text-center">
                      <a  class="nav-link  text-dark">{{ $data->amount }} pcs</a>
                      @if (Auth::user()->role == 1)
                      <a href="/admin/operator/stock/edit/{{ $data->id }}" class="btn btn-primary" >Tambah</a>
                      @endif
                    </td>
                    @if (Auth::user()->role == 1)
                    <td>
                      <form action="/admin/operator/stock/delete/{{$data->id}}" method="post" style="text-decoration: none">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="{{$data->id}}" value="DELETE">
                        <button type="sumbit" class="btn btn-danger text-center " onclick="return confirm('Yakin ingin menghapus produk?');">
                          Hapus</i>
                        </button>
                      </form> 
                    </td>
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