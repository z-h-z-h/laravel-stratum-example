@extends('layout')

@section('content')
    <p>Address: {{ $address }}</p><br>

    <div class="card mb-5">
        <div class="card-body">
            <h3 class="card-title">Balance</h3>
            @if($balance)
                <p>Confirmed: {{ $balance['confirmed'] }}</p>
                <p>Unconfirmed: {{ $balance['unconfirmed'] }}</p>
            @else
                <div class="alert alert-danger">Error retrieving balance</div>
            @endif
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-body">
            <h3 class="card-title">Unconfirmed transactions</h3>
            @isset($unconfirmed)
                @forelse($unconfirmed as $transaction)
                    <p>
                        tx_hash: {{ $transaction['tx_hash'] }}<br>
                        height: {{ $transaction['height'] }}<br>
                        fee: {{ $transaction['fee'] }}<br>
                    </p>
                @empty
                    There is no transactions
                @endforelse
            @else
                <div class="alert alert-danger">Error retrieving unconfirmed transactions</div>
            @endif
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-body">
            <h3 class="card-title">Last 10 transactions</h3>
            @if($history)
                @forelse(array_slice($history, 0, 10) as $transaction)
                    <p>
                        tx_hash: {{ $transaction['tx_hash'] }}<br>
                        height: {{ $transaction['height'] }}<br>
                    </p>
                @empty
                    There is no transactions
                @endforelse
            @else
                <div class="alert alert-danger">Error retrieving transactions</div>
            @endif
        </div>
    </div>
@endsection
