@extends('admin.layouts.layout')
@section('content')
<h2>Tambah Slider</h2>

<form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
  @csrf

  <div class="mb-3">
    <label for="title">Judul</label>
    <input type="text" name="title" class="form-control" value="{{ old('title') }}">
  </div>

  <div class="mb-3">
    <label for="image">Gambar</label>
    <input type="file" name="image" class="form-control" required>
  </div>

  <div class="mb-3">
    <label for="status">Status</label>
    <select name="status" class="form-control">
      <option value="1">Aktif</option>
      <option value="0">Nonaktif</option>
    </select>
  </div>

  <button type="submit" class="btn btn-primary">Simpan</button>
</form>
@endsection
