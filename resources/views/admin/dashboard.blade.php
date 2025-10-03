@extends('admin.layouts.layout')
@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="row ">
        <img src="{{ asset('images/user/' . $user->image) }}" class="text-center" alt="" width="80%">
      </div>
      <div class="row justify-content-center">
        <h4 class="text-center">Hi, {{ $user->name }}</h4>
        @if ($user->role == '1')
          <p class="text-center">Kamu sebagai Operator</p> 
        @endif
        @if ($user->role == '0')
          <p class="text-center">Kamu sebagai Staff admin</p> 
        @endif
      </div>
    </div>
  </div>
</div>

@endsection