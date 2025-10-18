@extends('admin.layouts.layout')
@section('content')
<h2>Edit Banner</h2>

<form action="{{ route('admin.sliders.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
  @csrf
  @method('PUT')

  <div class="mb-3">
    <label for="title">Judul</label>
    <input type="text" name="title" class="form-control" value="{{ old('title', $slider->title) }}">
  </div>

  <div class="mb-3">
    <label for="image">Gambar</label>
    <br>
    <img src="/images/sliders/{{ $slider->image}}" width="150" class="mb-2">
    <input type="file" name="image" class="form-control">
  </div>

  <div class="mb-3">
    <label for="status">Status</label>
    <select name="status" class="form-control">
      <option value="1" {{ $slider->status ? 'selected' : '' }}>Aktif</option>
      <option value="0" {{ !$slider->status ? 'selected' : '' }}>Nonaktif</option>
    </select>
  </div>

  <button type="submit" class="btn btn-primary">Update</button>
</form>
@endsection
