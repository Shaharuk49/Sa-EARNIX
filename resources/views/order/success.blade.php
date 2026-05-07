@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Order Success!</h2>
    <p>Order ID: {{ $order->id }}</p>
    <p>Product: {{ $order->product->name }}</p>
    <p>Status: {{ $order->status }}</p>
    <p>Total Amount: BDT {{ $order->selling_price }}</p>

    <a href="{{ route('dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
</div>
@endsection