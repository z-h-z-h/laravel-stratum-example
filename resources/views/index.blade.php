@extends('layout')

@section('content')
    <p>Enter address or transaction id to get data</p>
    <div class="row">
        <div class="col">
            <form action="{{ route('address.form') }}" method="post">
                @csrf
                <div class="form-group">
                    <input type="text" class="form-control" name="address" id="address"
                           placeholder="Enter Bitcoin address"
                           required
                           value="{{ old('address') }}">
                </div>
                <button type="submit" class="btn btn-block btn-secondary">Get Address Data</button>
            </form>
        </div>
        <div class="col">
            <form action="{{ route('tx.form') }}" method="post">
                @csrf
                <div class="form-group">
                    <input type="text" class="form-control" name="txid" id="txid" placeholder="Enter TXID"

                           value="{{ old('txid') }}">
                </div>
                <button type="submit" class="btn btn-block btn-secondary">Get Raw TX</button>
            </form>
        </div>
    </div>
@endsection
