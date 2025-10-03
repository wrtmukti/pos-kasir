@extends('admin.layouts.app')
@section('content')
    
<div class="container">
  <div class="row m-5">
    <div class="col-md-3">
      <a class="btn btn-primary" href="/admin/operator/outlet/create">+ Tambah Outlet</a>
    </div>
  </div>
  @if (session('status'))
  <div class="alert alert-success text-center">
    {{ session('status') }}
  </div>
  @endif

  @if ($outlets->count() == 0)
  <div class="alert alert-danger text-center">
    Outlet Kosong
  </div>
  @else
  <div class="row">

    @foreach ($outlets as $data)
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <p class="display-5">{{ $data->name }}</p>
        </div>
        <div class="card-footer">
          <a href="/admin/{{ $data->id }}">masuk</a>
          <form action="/admin/operator/outlet/{{$data->id}}" method="post">
            @csrf
            @method('DELETE')
            <input type="hidden" name="{{$data->id}}" value="DELETE">
            <button type="sumbit" class="btn btn-link text-danger btn-icon" onclick="return confirm('Yakin ingin menghapus data?');">
              <i class="mdi mdi-delete" data-feather="delete"></i>
            </button>
          </form>          
        </div>
      </div>
    </div>
    @endforeach
  </div>
  @endif
</div>

@endsection




