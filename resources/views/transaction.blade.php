@extends('layout')

@section('content')
    <p>Transaction: {{ $txid }}</p><br>

    <div class="card mb-5">
        <div class="card-body">
            <h3 class="card-title">Raw</h3>
            @if($raw)
                {{ $raw }}
            @else
                <div class="alert alert-danger">Error retrieving raw transaction</div>
            @endif
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-body">
            <h3 class="card-title">Parsed</h3>
            @if($data)
               <pre>
                   {{ $data }}
               </pre>
          @else
                <div class="alert alert-danger">Error retrieving transaction data</div>
            @endif
        </div>
    </div>
@endsection
