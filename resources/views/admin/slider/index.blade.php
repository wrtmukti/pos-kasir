@extends('admin.layouts.layout')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h2>Daftar Slider</h2>
  <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">Tambah Slider</a>
</div>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
  <thead>
    <tr>
      <th>No</th>
      <th>Gambar</th>
      <th>Keterangan</th>
      <th>Status</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    @foreach($sliders as $index => $slider)
    <tr>
      <td>{{ $index + 1 }}</td>
      <td><img src="/images/sliders/{{ $slider->image }}" width="300px"></td>
      <td>{{ $slider->title ?? '-' }}</td>
      <td>{{ $slider->status ? 'Aktif' : 'Nonaktif' }}</td>
      <td>
        <a href="{{ route('admin.sliders.edit', $slider->id) }}" class="btn btn-sm btn-warning">Edit</a>
        <form action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST" class="d-inline">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@endsection
