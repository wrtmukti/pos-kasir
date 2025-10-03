@extends('admin.layouts.layout')
@section('content')


<div class="row my-3">
  <p class="display-4 text-center fw-bold">Tambah Transaksi</p>
</div>

<div class="card shadow p-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <form action="/admin/transaction" method="POST">
        @csrf
        <div class="form-group">
          <label for="payment_status">Tipe Transaksi</label>
          <select name="payment_status" class="form-control" id="exampleFormControlSelect1" value=" {{ old('payment_status') }}">>
            <option value="1">Pengeluaran</option>
            <option value="0">Pemasukan</option>
          </select>          
          @error('payment_status')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>
        <div class="form-group row text-center">
          <div class="form-group col-6">
            <label for="payment_method">Cash</label>
            <input id="cash" type="text" class="form-control" name="cash" value="" placeholder="Cash"  >
          </div>
          <div class="form-group col-6">
            <label for="payment_method">Debit</label>
            <input id="debit" type="text" class="form-control" name="debit" value="" placeholder="Debit" >
          </div>
        </div>
        <div class="form-group">
            <label for="note">Catatan Transaksi</label>
            <textarea name="note" id="note" type="text"  class="form-control @error('note') is-invalid @enderror"  value="{{ old('note') }}" required autocomplete="note" autofocus></textarea>
            @error('note')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
</div>

@endsection