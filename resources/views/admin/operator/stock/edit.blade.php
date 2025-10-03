@extends('admin.layouts.layout')
@section('content')


<div class="row my-3">
  <p class="display-4 text-center fw-bold">Tambah Stok "{{ $stock->name }}"</p>
</div>

<div class="card shadow p-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <form action="/admin/operator/stock/update/{{ $stock->id }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
          <label for="exampleInputEmail1">Jumlah yang ditambahkan</label>
          <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Jumlah" name="amount">
        </div>
        <button type="submit" class="btn btn-primary">Tambah</button>
      </form>
    </div>
  </div>
</div>

@endsection